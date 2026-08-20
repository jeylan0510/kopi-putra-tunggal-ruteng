<?php
// ═══════════════════════════════════════════════════════════════════════════════════════════
// FILE: editproduk.php - Edit Produk dengan Validasi Berat Maksimal 1kg
// ═══════════════════════════════════════════════════════════════════════════════════════════

include 'index.php';

if (isset($_POST['edit'])) {
    // Ambil data dari form
    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama_kopi = mysqli_real_escape_string($conn, $_POST['nama_kopi']);
    $jenis_kopi = mysqli_real_escape_string($conn, $_POST['jenis_kopi']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $berat = mysqli_real_escape_string($conn, $_POST['berat']); // Tambahkan berat
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // VALIDASI BERAT (Maksimal 1000 gram = 1kg)
    // ─────────────────────────────────────────────────────────────────────────────────────────
    $errors = array();
    
    if (empty($berat)) {
        $errors[] = "Berat tidak boleh kosong";
    } elseif ($berat <= 0) {
        $errors[] = "Berat harus lebih dari 0 gram";
    } elseif ($berat > 1000) {
        $errors[] = "Berat maksimal 1000 gram (1kg)";
    }
    
    // Validasi lainnya
    if ($stok < 0) {
        $errors[] = "Stok tidak boleh kurang dari 0";
    }
    
    if ($harga <= 0) {
        $errors[] = "Harga harus lebih dari 0";
    }
    
    // Update status otomatis berdasarkan stok
    $status = ($stok > 0) ? "Tersedia" : "Tidak Tersedia";
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // PROSES UPLOAD GAMBAR (OPTIONAL)
    // ─────────────────────────────────────────────────────────────────────────────────────────
    $gambar_sql = "";
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/images/produk/";
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Validasi ukuran file
        if ($_FILES['gambar']['size'] > $max_size) {
            $errors[] = "Ukuran gambar maksimal 5MB";
        }
        
        // Validasi ekstensi file
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Format gambar tidak valid. Gunakan: JPG, PNG, GIF, WEBP";
        }
        
        // Jika tidak ada error, upload gambar
        if (empty($errors)) {
            // Generate nama file unik
            $gambar = uniqid() . '_' . time() . '.' . $file_ext;
            
            // Buat folder jika belum ada
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Upload file
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $gambar)) {
                $gambar_sql = ", foto_produk='$gambar'";
                
                // Hapus gambar lama (optional)
                $get_old = mysqli_query($conn, "SELECT foto_produk FROM produk_kopi WHERE id_produk='$id_produk'");
                if ($row = mysqli_fetch_assoc($get_old)) {
                    $old_image = $target_dir . $row['foto_produk'];
                    if (file_exists($old_image) && $row['foto_produk'] != 'default.jpg') {
                        unlink($old_image);
                    }
                }
            } else {
                $errors[] = "Gagal mengupload gambar";
            }
        }
    }
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // EKSEKUSI UPDATE JIKA TIDAK ADA ERROR
    // ─────────────────────────────────────────────────────────────────────────────────────────
    if (empty($errors)) {
        // Update database (TAMBAHKAN kolom berat)
        $sql = "UPDATE produk_kopi SET
                    nama_kopi='$nama_kopi',
                    jenis_kopi='$jenis_kopi',
                    stok='$stok',
                    harga='$harga',
                    berat='$berat',
                    deskripsi='$deskripsi'
                    $gambar_sql
                WHERE id_produk='$id_produk'";

        $query = mysqli_query($conn, $sql);

        if ($query) {
            // BERHASIL - Redirect dengan notifikasi sukses
            echo "<script>
                    alert('✓ Produk kopi berhasil diupdate!');
                    window.location='produk.php';
                  </script>";
        } else {
            // GAGAL - Tampilkan error MySQL
            echo "<script>
                    alert('✗ Gagal mengupdate produk!\\nError: " . addslashes(mysqli_error($conn)) . "');
                    window.location='produk.php';
                  </script>";
        }
    } else {
        // Tampilkan error validasi
        $error_message = implode("\\n", array_map('addslashes', $errors));
        echo "<script>
                alert('⚠️ Validasi Gagal:\\n" . $error_message . "');
                window.history.back();
              </script>";
    }
}
?>