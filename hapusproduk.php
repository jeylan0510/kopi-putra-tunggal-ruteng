<?php
include 'index.php'; // atau koneksi.php sesuai struktur kamu

// HAPUS Produk Kopi
if (isset($_POST['hapus'])) {
    $id_produk = $_POST['id_produk'];

    // Ambil gambar lama
    $query_gambar = mysqli_query($conn, "SELECT foto_produk FROM produk_kopi WHERE id_produk='$id_produk'");
    $data = mysqli_fetch_assoc($query_gambar);
    $file_gambar = "assets/images/produk/" . $data['foto_produk'];

    // Hapus file gambar jika ada
    if (!empty($data['foto_produk']) && file_exists($file_gambar)) {
        unlink($file_gambar);
    }

    // Hapus produk dari database
    $query = "DELETE FROM produk_kopi WHERE id_produk='$id_produk'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Produk kopi berhasil dihapus!'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk kopi.'); window.location='produk.php';</script>";
    }
}
?>