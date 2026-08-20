<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Kopi Ruteng Me</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="codedthemes">
    <link rel="icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link">
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/material.css">
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    <style>
    
        .btn-back-top-left {
            position: fixed;
            top: 10px;
            left: 20px;
            z-index: 1000;
            background-color: #944c18ff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        .btn-back-top-left:hover {
            background-color: #9d6e22ff;
        }
        .btn-back-top-left i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <a href="#" class="d-flex justify-content-center">
                            <img src="assets/images/banner.png" alt="image" class="img-fluid" style="width: 300px;">
                        </a>
                        <h5 class="my-4 d-flex justify-content-center">Masukkan Email && Password</h5>
                        <form action="proseslogin.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="email" placeholder="Email" required>
                                <label for="floatingInput">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingInput1" name="password" placeholder="Password" required>
                                <label for="floatingInput1">Password</label>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form>
                        <hr>
                        <h5 class="d-flex justify-content-center">Belum Punya Akun?<a href="register.php" class="ms-1">Register</a></h5>
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
