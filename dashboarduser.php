<?php
include 'indexuser.php';
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <!-- Banner dan Sambutan -->
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="assets/images/pelanggan.png" alt="Ilustrasi Kopi" class="img-fluid">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div>
                                <h4>Selamat Datang di Kopi Ruteng, <?= $_SESSION['nama'] ?>!</h4>
                                <p>
                                    Nikmati pengalaman menikmati kopi terbaik dari Ruteng langsung dari kedai kami. 
                                    Di dashboard ini, Anda dapat melihat daftar menu kopi, melakukan pemesanan, 
                                    serta memantau status pesanan Anda dengan mudah.
                                </p>
                                <p>
                                    Gunakan menu navigasi untuk melihat daftar kopi dan minuman lainnya, menambahkan 
                                    ke keranjang, serta melakukan transaksi dengan nyaman. Kami selalu berusaha 
                                    memberikan layanan terbaik agar pengalaman Anda semakin menyenangkan.
                                </p>
                                </p> Hubungi kami jika ada masalah atau saran untuk cita rasa kopi kami.
                                </p> No hanphone: 081-337-364-209
                                </p> Instagram: PUTRA TUNGGAL RUTENG COFFE
                                <a href="daftarproduk.php" class="btn btn-dark">☕ Lihat Daftar Menu Kopi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik / Info Ringkas (Opsional) -->
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h5>Total Menu Kopi</h5>
                            <?php
                            $totalMenu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk_kopi"));
                            ?>
                            <h2><?= $totalMenu['total'] ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h5>Total Pesanan Anda</h5>
                            <?php
                            $idPelanggan = $_SESSION['id'];
                            $totalPesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE id_pelanggan='$idPelanggan'"));
                            ?>
                            <h2><?= $totalPesanan['total'] ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h5>Pesanan Diproses</h5>
                            <?php
                            $pesananProses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE id_pelanggan='$idPelanggan' AND status_pesanan='Diproses'"));
                            ?>
                            <h2><?= $pesananProses['total'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
<footer class="pc-footer"></footer>