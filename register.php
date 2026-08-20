<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | Kopi Ruteng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="codedthemes">
    <link rel="icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/material.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style-preset.css">
</head>
<body>
    <div class="loader-bg">
        <div class="loader-track"><div class="loader-fill"></div></div>
    </div>
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card">
                    <div class="card-body">
                        <a href="#" class="d-flex justify-content-center">
                            <img src="assets/images/banner.png" alt="image" class="img-fluid" style="width: 300px;">
                        </a>
                        <h5 class="my-4 d-flex justify-content-center">Buat Akun Pelanggan Baru</h5>
                        
                        <form action="prosesregister.php" method="POST">
                            <!-- Nama Lengkap -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingNama" name="nama_pelanggan" placeholder="Nama Lengkap" required>
                                <label for="floatingNama">Nama Lengkap</label>
                            </div>

                            <!-- Username -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="Username" required>
                                <label for="floatingUsername">Username</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="Email" required>
                                <label for="floatingEmail">Email</label>
                            </div>

                            <!-- Nomor HP -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingNoHp" name="no_hp" placeholder="No. HP" required>
                                <label for="floatingNoHp">Nomor HP</label>
                            </div>

                            <!-- Alamat -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="floatingAlamat" name="alamat" placeholder="Alamat" style="height: 80px;" required></textarea>
                                <label for="floatingAlamat">Alamat</label>
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                                <label for="floatingPassword">Password</label>
                            </div>

                            <!-- Tombol -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary" name="regis">Daftar Sekarang</button>
                            </div>
                        </form>

                        <hr>
                        <h5 class="d-flex justify-content-center">
                            Sudah Punya Akun?
                            <a href="login.php" class="ms-1">Login</a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/fonts/custom-font.js"></script>
    <script src="assets/js/pcoded.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script>
        preset_change("preset-1");
    </script>
</body>
</html>