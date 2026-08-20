<?php
include "koneksi.php";

if (isset($_POST['regis'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass     = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek email
    $cekEmail = mysqli_query($conn, "SELECT email FROM pelanggan WHERE email='$email'");
    if (mysqli_num_rows($cekEmail) > 0) {
        echo '<script>alert("Email sudah terdaftar! Silahkan gunakan email lain.");window.location="register.php"</script>';
        exit;
    }

    // Cek username
    $cekUsername = mysqli_query($conn, "SELECT username FROM pelanggan WHERE username='$username'");
    if (mysqli_num_rows($cekUsername) > 0) {
        echo '<script>alert("Username sudah digunakan! Silahkan gunakan username lain.");window.location="register.php"</script>';
        exit;
    }

    // Hash password
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    // Simpan ke tabel pelanggan
    $query = mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, email, no_hp, alamat, username, password)
                                  VALUES ('$nama', '$email', '$no_hp', '$alamat', '$username', '$hashedPass')");

    if ($query) {
        echo '<script>alert("Berhasil buat akun! Silakan login.");window.location="login.php"</script>';
    } else {
        echo "Error Query: " . mysqli_error($conn);
    }
}
?>