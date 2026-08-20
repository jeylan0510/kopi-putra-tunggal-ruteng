<?php
include 'indexuser.php';
session_start();

$id_user = $_SESSION['id'];

// Ambil semua transaksi user, join ke tabel pembayaran
$query_transaksi = mysqli_query($conn, "
    SELECT t.id_transaksi, t.id_pelanggan, t.tgl_transaksi, t.total_harga, t.status_pesanan,
           t.metode_pembayaran, t.status_pembayaran, p.tgl_pembayaran, p.bukti_pembayaran
    FROM transaksi t
    LEFT JOIN pembayaran p ON t.id_transaksi = p.id_transaksi
    WHERE t.id_pelanggan = '$id_user'
    ORDER BY t.tgl_transaksi DESC
");

// Cek apakah query berhasil
if (!$query_transaksi) {
    die('Query Error: '.mysqli_error($conn));
}

$no = 1;
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Daftar Pesanan Kopi Ruteng</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total Harga</th>
                                        <th>Status Pesanan</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($query_transaksi) > 0): ?>
                                        <?php while($transaksi = mysqli_fetch_assoc($query_transaksi)): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($transaksi['tgl_transaksi'])); ?></td>
                                                <td>Rp <?= number_format($transaksi['total_harga'],0,',','.'); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = 'secondary';
                                                    switch($transaksi['status_pesanan']){
                                                        case 'Menunggu Pembayaran': $status_class='warning'; break;
                                                        case 'Diproses': $status_class='info'; break;
                                                        case 'Dikirim': $status_class='primary'; break;
                                                        case 'Selesai': $status_class='success'; break;
                                                        case 'Dibatalkan': $status_class='danger'; break;
                                                    }
                                                    ?>
                                                    <span class="btn btn-<?= $status_class ?> btn-sm"><?= $transaksi['status_pesanan'] ?></span>
                                                </td>
                                                <td><?= $transaksi['metode_pembayaran'] ?? '-' ?></td>
                                                <td>
                                                    <?php
                                                    $bayar_class = 'secondary';
                                                    switch($transaksi['status_pembayaran']){
                                                        case 'Belum Bayar': $bayar_class='warning'; break;
                                                        case 'Sudah Bayar': $bayar_class='success'; break;
                                                        case 'Ditolak': $bayar_class='danger'; break;
                                                    }
                                                    ?>
                                                    <span class="btn btn-<?= $bayar_class ?> btn-sm"><?= $transaksi['status_pembayaran'] ?? '-' ?></span>
                                                </td>
                                                <td><?= $transaksi['tgl_pembayaran'] ? date('d-m-Y H:i', strtotime($transaksi['tgl_pembayaran'])) : '-' ?></td>
                                                <td>
                                                    <?php if($transaksi['bukti_pembayaran']): ?>
                                                        <a href="assets/images/bukti/<?= $transaksi['bukti_pembayaran'] ?>" target="_blank">
                                                            <img src="assets/images/bukti/<?= $transaksi['bukti_pembayaran'] ?>" alt="Bukti" width="50">
                                                        </a>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="detail_pesanan.php?id=<?= $transaksi['id_transaksi'] ?>" class="btn btn-info btn-sm">Detail</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada pesanan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>