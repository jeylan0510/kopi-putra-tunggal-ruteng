<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Kopi Ruteng | Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ICON -->
    <link rel="icon" href="assets/images/logo.png">

    <!-- FONTS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <!-- ICON & TEMPLATE CSS -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    <link rel="stylesheet" href="assets/css/kopi-ruteng-safe.css">
  
</head>
<body>
  <!-- SIDEBAR -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="dashboard.php" class="b-brand text-primary">
          <img src="assets/images/banner.png" style="width: 200px;">
        </a>
      </div>

      <div class="navbar-content">
        <ul class="pc-navbar">

          <!-- DASHBOARD -->
          <li class="pc-item pc-caption">
            <label><i class="ti ti-dashboard"></i> Dashboard</label>
          </li>
          <li class="pc-item">
            <a href="dashboard.php" class="pc-link active">
              <span class="pc-micon"><i class="ti ti-home"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <!-- MASTER DATA -->
          <li class="pc-item pc-caption">
            <label><i class="ti ti-database"></i> Master Data</label>
          </li>
          <li class="pc-item">
            <a href="produk.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-coffee"></i></span>
              <span class="pc-mtext">Data Produk</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="pelanggan.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-users"></i></span>
              <span class="pc-mtext">Data Pelanggan</span>
            </a>
          </li>

          <!-- TRANSAKSI -->
          <li class="pc-item pc-caption">
            <label><i class="ti ti-cash"></i> Transaksi</label>
          </li>
          <li class="pc-item">
            <a href="pesanan_masuk.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
              <span class="pc-mtext">Pesanan Masuk</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="riwayat_transaksi.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-history"></i></span>
              <span class="pc-mtext">Riwayat Transaksi</span>
            </a>
          </li>

          <!-- LAPORAN -->
          <li class="pc-item pc-caption">
            <label><i class="ti ti-file-invoice"></i> Laporan</label>
          </li>
          <li class="pc-item">
            <a href="laporan_penjualan.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-report"></i></span>
              <span class="pc-mtext">Laporan Penjualan</span>
            </a>
          </li>

          <!-- PENGGUNA -->
          <li class="pc-item pc-caption">
            <label><i class="ti ti-user"></i> Pengguna</label>
          </li>
          <li class="pc-item">
            <a href="admin.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-id"></i></span>
              <span class="pc-mtext">Data Admin</span>
            </a>
          </li>

        </ul>

        <!-- User Profile & Logout Section -->
        <div class="sidebar-user-profile">
          <div class="user-profile-card">
            <img src="assets/images/user.png" alt="Admin">
            <div class="user-profile-info">
              <h5><?= $_SESSION['nama'] ?></h5>
              <span class="admin-badge">☕ Admin</span>
            </div>
          </div>
          <a href="logout.php" class="logout-btn">
            <i class="ti ti-logout"></i>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- HEADER -->
  <header class="pc-header">
    <div class="header-wrapper">
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <li class="pc-h-item header-mobile-collapse">
            <a href="#" class="pc-head-link head-link-primary ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
        </ul>
      </div>

      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="pc-h-item">
            <span style="color: var(--coffee-cream); font-weight: 500; padding: 0 15px;">
              <i class="ti ti-coffee" style="margin-right: 8px;"></i>
              Admin Panel
            </span>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- JS -->
  <script src="assets/js/plugins/popper.min.js"></script>
  <script src="assets/js/plugins/simplebar.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/fonts/custom-font.js"></script>
  <script src="assets/js/pcoded.js"></script>
  <script src="assets/js/plugins/feather.min.js"></script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Poppins");</script>
  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_caption_change('true');</script>
  <script>layout_rtl_change('false');</script>
  <script>
    // Avoid aria-hidden warning: blur focused element inside modal before it is hidden
    document.addEventListener('hide.bs.modal', function (e) {
      try {
        var active = document.activeElement;
        if (active && e.target && e.target.contains(active)) {
          // move focus away to avoid aria-hidden on focused element
          active.blur();
        }
      } catch (err) {}
    });
  </script>
</body>
</html>