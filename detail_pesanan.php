<?php
include 'indexuser.php';
session_start();

$id_user = $_SESSION['id'];

// Ambil ID transaksi dari URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID transaksi tidak ditemukan.'); window.location='pesanan.php';</script>";
    exit();
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi dan pelanggan
$query_transaksi = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan, p.no_hp, t.alamat_pengiriman, pay.tgl_pembayaran, pay.bukti_pembayaran
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    LEFT JOIN pembayaran pay ON t.id_transaksi = pay.id_transaksi
    WHERE t.id_transaksi = '$id_transaksi' AND t.id_pelanggan = '$id_user'
");

if (mysqli_num_rows($query_transaksi) == 0) {
    echo "<script>alert('Transaksi tidak ditemukan.'); window.location='pesanan.php';</script>";
    exit();
}

$transaksi = mysqli_fetch_assoc($query_transaksi);

// Ambil detail produk
$query_detail = mysqli_query($conn, "
    SELECT d.*, pr.nama_kopi
    FROM detail_transaksi d
    JOIN produk_kopi pr ON d.id_produk = pr.id_produk
    WHERE d.id_detail = '$id_transaksi'
");

?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h2>Detail Pesanan</h2>
                        <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($transaksi['nama_pelanggan']); ?></p>
                        <p><strong>No HP:</strong> <?= htmlspecialchars($transaksi['no_hp']); ?></p>
                        <p><strong>Alamat:</strong> <?= htmlspecialchars($transaksi['alamat_pengiriman']); ?></p>
                        <p><strong>Tanggal Transaksi:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tgl_transaksi'])); ?></p>
                        <p><strong>Status Pesanan:</strong> <?= $transaksi['status_pesanan']; ?></p>
                        <p><strong>Metode Pembayaran:</strong> <?= $transaksi['metode_pembayaran']; ?></p>
                        <p><strong>Status Pembayaran:</strong> <?= $transaksi['status_pembayaran']; ?></p>
                        <p><strong>Tanggal Pembayaran:</strong> <?= $transaksi['tgl_pembayaran'] ? date('d-m-Y H:i', strtotime($transaksi['tgl_pembayaran'])) : '-'; ?></p>
                        <p><strong>Bukti Pembayaran:</strong> 
                            <?php if($transaksi['bukti_pembayaran']): ?>
                                <a href="assets/images/bukti/<?= $transaksi['bukti_pembayaran'] ?>" target="_blank">
                                    <img src="assets/images/bukti/<?= $transaksi['bukti_pembayaran'] ?>" width="100">
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </p>

                        <hr>

                        <a href="pesanan.php" class="btn btn-secondary mt-3"><< Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
