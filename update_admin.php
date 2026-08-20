<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    $id_admin   = $_POST['id_admin'];
    $nama_admin = mysqli_real_escape_string($conn, $_POST['nama_admin']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = $_POST['password'];

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET 
                    nama_admin='$nama_admin',
                    username='$username',
                    email='$email',
                    password='$password_hash'
                  WHERE id_admin='$id_admin'";
    } else {
        $query = "UPDATE admin SET 
                    nama_admin='$nama_admin',
                    username='$username',
                    email='$email'
                  WHERE id_admin='$id_admin'";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data admin berhasil diperbarui!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data admin!'); window.history.back();</script>";
    }
}
?>