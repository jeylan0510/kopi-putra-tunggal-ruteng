<?php
include 'koneksi.php';
session_start();

// Cek apakah tombol hapus ditekan dan ada ID
if (isset($_POST['hapus']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Jalankan query hapus pelanggan
    $hapus = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id'");

    if ($hapus) {
        echo "<script>alert('Pelanggan berhasil dihapus.'); window.location='pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pelanggan: ".mysqli_error($conn)."'); window.location='pelanggan.php';</script>";
    }
} else {
    // Jika diakses langsung tanpa POST
    header("Location: pelanggan.php");
    exit();
}
?>