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
  <title>Kopi Ruteng | Pelanggan</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="author" content="codedthemes">
  <link rel="icon" href="assets/images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
  <link rel="stylesheet" href="assets/fonts/feather.css">
  <link rel="stylesheet" href="assets/fonts/fontawesome.css">
  <link rel="stylesheet" href="assets/fonts/material.css">
  <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="assets/css/style-preset.css">
  
  <style>
    :root {
      --coffee-dark: #3E2723;
      --coffee-brown: #5D4037;
      --coffee-medium: #795548;
      --coffee-light: #A1887F;
      --coffee-cream: #D7CCC8;
      --coffee-accent: #8D6E63;
      --coffee-gold: #C9A96E;
      --coffee-beans: #4E342E;
    }

    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #3E2723 0%, #5D4037 100%);
      background-attachment: fixed;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 20% 80%, rgba(201, 169, 110, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(141, 110, 99, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    .loader-bg {
      background: var(--coffee-dark);
    }

    .loader-fill {
      background: var(--coffee-gold);
    }

    /* Sidebar Styling */
    .pc-sidebar {
      background: linear-gradient(180deg, var(--coffee-beans) 0%, var(--coffee-dark) 100%);
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
      border-right: 2px solid var(--coffee-gold);
    }

    .pc-sidebar::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A96E' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.3;
      pointer-events: none;
    }

    .b-brand {
      padding: 25px 20px;
      background: rgba(201, 169, 110, 0.1);
      border-bottom: 1px solid var(--coffee-gold);
      transition: all 0.3s ease;
    }

    .b-brand:hover {
      background: rgba(201, 169, 110, 0.15);
    }

    .pc-navbar {
      padding: 15px 10px;
    }

    .pc-item.pc-caption {
      margin-top: 25px;
      margin-bottom: 10px;
      position: relative;
    }

    .pc-item.pc-caption label {
      color: var(--coffee-gold);
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      padding: 8px 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .pc-item.pc-caption i {
      font-size: 14px;
      opacity: 0.8;
    }

    .pc-link {
      color: var(--coffee-cream);
      border-radius: 12px;
      margin: 5px 10px;
      padding: 12px 18px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .pc-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 4px;
      height: 100%;
      background: var(--coffee-gold);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .pc-link:hover {
      background: rgba(201, 169, 110, 0.15);
      color: var(--coffee-gold);
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(201, 169, 110, 0.2);
    }

    .pc-link:hover::before {
      transform: scaleY(1);
    }

    .pc-link.active {
      background: linear-gradient(90deg, rgba(201, 169, 110, 0.25) 0%, rgba(201, 169, 110, 0.1) 100%);
      color: var(--coffee-gold);
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(201, 169, 110, 0.2);
    }

    .pc-link.active::before {
      transform: scaleY(1);
    }

    .pc-micon {
      color: inherit;
      font-size: 20px;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(201, 169, 110, 0.1);
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .pc-link:hover .pc-micon,
    .pc-link.active .pc-micon {
      background: var(--coffee-gold);
      color: var(--coffee-dark);
      transform: rotate(5deg) scale(1.1);
    }

    /* Header Styling */
    .pc-header {
      background: linear-gradient(90deg, var(--coffee-beans) 0%, var(--coffee-dark) 100%);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      border-bottom: 2px solid var(--coffee-gold);
    }

    .pc-head-link {
      color: var(--coffee-cream);
      background: rgba(201, 169, 110, 0.1);
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .pc-head-link:hover {
      background: var(--coffee-gold);
      color: var(--coffee-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(201, 169, 110, 0.3);
    }

    .user-avtar {
      border: 2px solid var(--coffee-gold);
      border-radius: 50%;
      padding: 2px;
      background: var(--coffee-cream);
      box-shadow: 0 0 15px rgba(201, 169, 110, 0.4);
    }

    /* Dropdown Menu */
    .dropdown-menu {
      background: var(--coffee-dark);
      border: 2px solid var(--coffee-gold);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
      border-radius: 15px;
    }

    .dropdown-header {
      background: linear-gradient(135deg, var(--coffee-beans) 0%, var(--coffee-brown) 100%);
      border-bottom: 2px solid var(--coffee-gold);
      padding: 20px;
    }

    .dropdown-header h4 {
      color: var(--coffee-gold);
      font-family: 'Playfair Display', serif;
      margin-bottom: 5px;
    }

    .dropdown-header .text-muted {
      color: var(--coffee-cream) !important;
      font-weight: 500;
    }

    .dropdown-item {
      color: var(--coffee-cream);
      padding: 12px 20px;
      transition: all 0.3s ease;
      border-radius: 8px;
      margin: 5px 10px;
    }

    .dropdown-item:hover {
      background: rgba(201, 169, 110, 0.2);
      color: var(--coffee-gold);
      transform: translateX(5px);
    }

    .dropdown-item i {
      color: var(--coffee-gold);
      margin-right: 10px;
      font-size: 18px;
    }

    /* Main Content Area */
    .pc-container {
      background: rgba(255, 255, 255, 0.95);
    }

    /* Card Styling */
    .card {
      background: white;
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(62, 39, 35, 0.15);
      transition: all 0.3s ease;
      border-top: 3px solid var(--coffee-gold);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(62, 39, 35, 0.25);
    }

    .card-header {
      background: linear-gradient(135deg, var(--coffee-cream) 0%, rgba(215, 204, 200, 0.5) 100%);
      border-bottom: 2px solid var(--coffee-gold);
      color: var(--coffee-dark);
      font-weight: 600;
    }

    /* Button Styling */
    .btn-primary {
      background: linear-gradient(135deg, var(--coffee-brown) 0%, var(--coffee-dark) 100%);
      border: none;
      color: var(--coffee-cream);
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(62, 39, 35, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee-beans) 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(62, 39, 35, 0.4);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background: var(--coffee-dark);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--coffee-gold);
      border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--coffee-accent);
    }

    /* Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .pc-navbar > li {
      animation: fadeIn 0.5s ease forwards;
      opacity: 0;
    }

    .pc-navbar > li:nth-child(1) { animation-delay: 0.1s; }
    .pc-navbar > li:nth-child(2) { animation-delay: 0.2s; }
    .pc-navbar > li:nth-child(3) { animation-delay: 0.3s; }
    .pc-navbar > li:nth-child(4) { animation-delay: 0.4s; }
    .pc-navbar > li:nth-child(5) { animation-delay: 0.5s; }
    .pc-navbar > li:nth-child(6) { animation-delay: 0.6s; }
    .pc-navbar > li:nth-child(7) { animation-delay: 0.7s; }
    .pc-navbar > li:nth-child(8) { animation-delay: 0.8s; }

    /* Coffee Steam Animation */
    @keyframes steam {
      0% {
        transform: translateY(0) scaleX(1);
        opacity: 0.6;
      }
      50% {
        transform: translateY(-10px) scaleX(1.1);
        opacity: 0.3;
      }
      100% {
        transform: translateY(-20px) scaleX(0.9);
        opacity: 0;
      }
    }

    .ti-coffee::after {
      content: '';
      position: absolute;
      width: 4px;
      height: 8px;
      background: var(--coffee-gold);
      border-radius: 50%;
      top: -5px;
      left: 50%;
      animation: steam 2s infinite;
      opacity: 0;
    }
  </style>
</head>
<body>
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <!-- Sidebar -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="dashboarduser.php" class="b-brand text-primary">
          <img src="assets/images/banner.png" style="width: 190px;">
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item pc-caption">
            <label><i class="ti ti-home"></i> Home</label>
          </li>
          <li class="pc-item">
            <a href="dashboarduser.php" class="pc-link active">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label><i class="ti ti-coffee"></i> Produk & Keranjang</label>
          </li>
          <li class="pc-item">
            <a href="daftarproduk.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-coffee"></i></span>
              <span class="pc-mtext">Daftar Produk</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="keranjang.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
              <span class="pc-mtext">Keranjang</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label><i class="ti ti-receipt"></i> Transaksi</label>
          </li>
          <li class="pc-item">
            <a href="pesanan.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-receipt"></i></span>
              <span class="pc-mtext">Pesanan Saya</span>
            </a>
          </li>
          <li class="pc-item pc-caption">
            <label><i class="ti ti-logout"></i> Menu</label>
          </li>
          <li class="pc-item">
            <a href="logout.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-logout"></i></span>
              <span class="pc-mtext">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="pc-header">
    <div class="header-wrapper">
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <li class="pc-h-item header-mobile-collapse">
            <a href="#" class="pc-head-link head-link-primary ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link head-link-primary ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Script -->
  <script src="assets/js/plugins/popper.min.js"></script>
  <script src="assets/js/plugins/simplebar.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/fonts/custom-font.js"></script>
  <script src="assets/js/pcoded.js"></script>
  <script src="assets/js/plugins/feather.min.js"></script>
  <script>layout_change('light');</script>
  <script>font_change("Poppins");</script>
  <script>change_box_container('false');</script>
  <script>layout_caption_change('true');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script src="assets/js/plugins/apexcharts.min.js"></script>
  <script src="assets/js/pages/dashboard-default.js"></script>
</body>
</html>