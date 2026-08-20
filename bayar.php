<?php
include 'indexuser.php';
session_start();

if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong!'); window.location='daftarproduk.php';</script>";
    exit();
}

$id_pelanggan = $_SESSION['id'];
$tgl_transaksi = date('Y-m-d');
$total_harga = 0;

// Ambil data dari form
$metode = $_POST['metode_pembayaran'] ?? '';
$alamat_pengiriman = $metode === 'COD' ? mysqli_real_escape_string($conn, $_POST['alamat_pengiriman'] ?? '') : null;

// Validasi metode pembayaran
if (empty($metode)) {
    echo "<script>alert('Pilih metode pembayaran terlebih dahulu.'); window.location='checkout.php';</script>";
    exit();
}

// Validasi alamat pengiriman untuk COD
if ($metode === 'COD' && empty($alamat_pengiriman)) {
    echo "<script>alert('Alamat pengiriman wajib diisi untuk COD.'); window.location='checkout.php';</script>";
    exit();
}

// Hitung total harga dari keranjang
foreach ($_SESSION['keranjang'] as $item) {
    $total_harga += $item['harga'] * $item['jumlah'];
}

// Upload bukti pembayaran untuk metode selain COD
$new_file_name = null;
if ($metode !== 'COD') {
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
        $file_name = $_FILES['bukti_pembayaran']['name'];
        $file_tmp  = $_FILES['bukti_pembayaran']['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>alert('Format file bukti pembayaran tidak diperbolehkan.'); window.location='checkout.php';</script>";
            exit();
        }

        $new_file_name = time().'_'.$file_name;
        $upload_dir = 'assets/images/bukti/';
        $upload_path = $upload_dir . $new_file_name;

        if (!move_uploaded_file($file_tmp, $upload_path)) {
            echo "<script>alert('Gagal mengupload bukti pembayaran.'); window.location='checkout.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Bukti pembayaran wajib diupload.'); window.location='checkout.php';</script>";
        exit();
    }
}

// Tentukan status pembayaran & status pesanan
if ($metode === 'COD') {
    $status_pembayaran = 'Belum Bayar';
    $status_pesanan = 'Menunggu Pembayaran';
} else {
    $status_pembayaran = 'Sudah Bayar';
    $status_pesanan = 'Diproses';
}

// Simpan ke tabel transaksi
$query_transaksi = mysqli_query($conn, "INSERT INTO transaksi 
    (id_pelanggan, tgl_transaksi, total_harga, status_pembayaran, metode_pembayaran, alamat_pengiriman, status_pesanan)
    VALUES ('$id_pelanggan','$tgl_transaksi','$total_harga','$status_pembayaran','$metode','$alamat_pengiriman','$status_pesanan')");

if (!$query_transaksi) {
    die('Error insert transaksi: '.mysqli_error($conn));
}

$id_transaksi = mysqli_insert_id($conn);

// 🧾 Simpan ke tabel pembayaran
$tgl_pembayaran = ($metode !== 'COD') ? date('Y-m-d H:i:s') : null;
$query_pembayaran = mysqli_query($conn, "
    INSERT INTO pembayaran (id_transaksi, jumlah_bayar, bukti_pembayaran, tgl_pembayaran)
    VALUES ('$id_transaksi', '$total_harga', " . 
    ($new_file_name ? "'$new_file_name'" : "NULL") . ", " .
    ($tgl_pembayaran ? "'$tgl_pembayaran'" : "NULL") . ")");

if (!$query_pembayaran) {
    die('Error insert pembayaran: '.mysqli_error($conn));
}

// Simpan detail produk
foreach ($_SESSION['keranjang'] as $id_produk => $item) {
    $jumlah = $item['jumlah'];
    $harga = $item['harga'];
    $subtotal = $item['harga'] * $item['jumlah'];

    mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) 
        VALUES ('$id_transaksi','$id_produk','$jumlah','$harga','$subtotal')");
    
    // Update stok produk
    mysqli_query($conn, "UPDATE produk_kopi SET stok = stok - '$jumlah' WHERE id_produk = '$id_produk'");
}

// Kosongkan keranjang
unset($_SESSION['keranjang']);
unset($_SESSION['total_harga']);

echo "<script>
    alert('Transaksi berhasil dibuat! Total bayar: Rp ".number_format($total_harga,0,',','.')."');
    window.location='pesanan.php';
</script>";
exit();
?>