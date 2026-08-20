<?php
include 'index.php';

// Hitung total produk
$queryProduk = "SELECT COUNT(*) AS total_produk FROM produk_kopi";
$resultProduk = mysqli_fetch_assoc(mysqli_query($conn, $queryProduk));

// Hitung total pelanggan
$queryPelanggan = "SELECT COUNT(*) AS total_pelanggan FROM pelanggan";
$resultPelanggan = mysqli_fetch_assoc(mysqli_query($conn, $queryPelanggan));

// Hitung pesanan selesai
$querySelesai = "SELECT COUNT(*) AS total_selesai FROM transaksi WHERE status_pesanan = 'Selesai'";
$resultSelesai = mysqli_fetch_assoc(mysqli_query($conn, $querySelesai));

// Hitung pesanan dalam proses
$queryProses = "SELECT COUNT(*) AS total_proses FROM transaksi WHERE status_pesanan IN ('Menunggu Pembayaran', 'Diproses', 'Dikirim')";
$resultProses = mysqli_fetch_assoc(mysqli_query($conn, $queryProses));

// Variabel
$totalProduk = $resultProduk['total_produk'];
$totalPelanggan = $resultPelanggan['total_pelanggan'];
$totalSelesai = $resultSelesai['total_selesai'];
$totalProses = $resultProses['total_proses'];
?>

<div class="pc-container">
  <div class="pc-content">
    <div class="row">
      <!-- Total Produk -->
      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="avtar avtar-lg">
                  <i class="text-white ti ti-coffee"></i>
                </div>
              </div>
            </div>
            <span class="text-white d-block f-34 f-w-500 my-2"><?= $totalProduk ?></span>
            <p class="mb-0 opacity-75">Total Produk Kopi</p>
          </div>
        </div>
      </div>

      <!-- Total Pelanggan -->
      <div class="col-xl-3 col-md-6">
        <div class="card bg-brown dashnum-card text-white overflow-hidden" style="background-color:#6f4e37;">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="avtar avtar-lg">
                  <i class="text-white ti ti-users"></i>
                </div>
              </div>
            </div>
            <span class="text-white d-block f-34 f-w-500 my-2"><?= $totalPelanggan ?></span>
            <p class="mb-0 opacity-75">Total Pelanggan</p>
          </div>
        </div>
      </div>

      <!-- Pesanan Selesai -->
      <div class="col-xl-3 col-md-6">
        <div class="card bg-success dashnum-card text-white overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="avtar avtar-lg">
                  <i class="text-white ti ti-check"></i>
                </div>
              </div>
            </div>
            <span class="text-white d-block f-34 f-w-500 my-2"><?= $totalSelesai ?></span>
            <p class="mb-0 opacity-75">Pesanan Selesai</p>
          </div>
        </div>
      </div>

      <!-- Pesanan Dalam Proses -->
      <div class="col-xl-3 col-md-6">
        <div class="card bg-warning dashnum-card text-white overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="avtar avtar-lg">
                  <i class="text-white ti ti-truck-delivery"></i>
                </div>
              </div>
            </div>
            <span class="text-white d-block f-34 f-w-500 my-2"><?= $totalProses ?></span>
            <p class="mb-0 opacity-75">Pesanan Dalam Proses</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Welcome Section -->
    <div class="row mt-4">
      <div class="card">
        <div class="row">
          <div class="col-md-6">
            <img src="assets/images/dashboard-coffee.jpg" alt="Admin Illustration" class="img-fluid">
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <div>
              <h4>Selamat Datang di Dashboard Kopi Ruteng, <?= $_SESSION['nama'] ?>!</h4>
              <p>
                Sistem ini membantu Anda mengelola data penjualan, produk kopi, pelanggan, dan transaksi dengan mudah.
                Semua informasi penting ditampilkan secara ringkas di halaman ini.
              </p>
              <p>
                Gunakan menu di sisi kiri untuk menambah produk baru, memproses pesanan pelanggan, dan melihat laporan penjualan.
                Selamat bekerja dan tetap semangat menyajikan kopi terbaik dari Ruteng! ☕
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="pc-footer"></footer>