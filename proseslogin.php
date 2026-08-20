<?php
session_start();
include "koneksi.php";

// Ambil input dari form login
$email = $_POST['email'] ?? '';
$pass =  $_POST['password'] ?? '';

// Validasi input kosong
if (empty($email) || empty($pass)) {
    echo '<script>alert("Email dan Password tidak boleh kosong!");window.location="login.php"</script>';
    exit();
}

// --- LOGIN SEBAGAI ADMIN ---
$stmt_admin = $conn->prepare("SELECT * FROM admin WHERE email = ?");
$stmt_admin->bind_param("s", $email);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    $data = $result_admin->fetch_assoc();
    // Cek password
    if (password_verify($pass, $data['password'])) {
        $_SESSION['id'] = $data['id_admin'];
        $_SESSION['nama'] = $data['nama_admin'];
        $_SESSION['role'] = "admin";
        $_SESSION['status'] = "login";
        header("location:dashboard.php");
        exit();
    }
}

// --- LOGIN SEBAGAI PELANGGAN ---
$stmt_user = $conn->prepare("SELECT * FROM pelanggan WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $data = $result_user->fetch_assoc();
    // Cek password
    if (password_verify($pass, $data['password'])) {
        $_SESSION['id'] = $data['id_pelanggan'];
        $_SESSION['nama'] = $data['nama_pelanggan'];
        $_SESSION['role'] = "pelanggan";
        $_SESSION['status'] = "login";
        header("location:dashboarduser.php");
        exit();
    }
}

// --- LOGIN GAGAL ---
echo '<script>alert("Email atau Password salah!");window.location="login.php"</script>';
exit();
?>