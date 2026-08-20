<?php
include 'index.php'; // ganti indexuser.php dengan indexadmin.php
session_start();

// Ambil semua transaksi dari tabel transaksi
$query_transaksi = mysqli_query($conn, "
    SELECT t.id_transaksi, t.id_pelanggan, pl.nama_pelanggan, t.tgl_transaksi, t.total_harga, t.status_pesanan, 
           t.metode_pembayaran, p.jumlah_bayar, p.bukti_pembayaran, t.status_pembayaran, p.tgl_pembayaran
    FROM transaksi t
    LEFT JOIN pembayaran p ON t.id_transaksi = p.id_transaksi
    LEFT JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
    ORDER BY t.tgl_transaksi DESC
");

$no = 1;
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Daftar Pesanan Masuk</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th>No</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total Harga</th>
                                        <th>Status Transaksi</th>
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
                                                <td><?= htmlspecialchars($transaksi['nama_pelanggan']); ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($transaksi['tgl_transaksi'])); ?></td>
                                                <td>Rp <?= number_format($transaksi['total_harga'],0,',','.'); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = 'secondary';
                                                    switch($transaksi['status_pesanan']){
                                                        case 'Pending': $status_class='warning'; break;
                                                        case 'Diproses': $status_class='info'; break;
                                                        case 'Dikirim': $status_class='primary'; break;
                                                        case 'Selesai': $status_class='success'; break;
                                                        case 'Dibatalkan': $status_class='danger'; break;
                                                    }
                                                    ?>
                                                    <span class="btn btn-<?= $status_class ?> btn-sm"><?= $transaksi['status_pesanan'] ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($transaksi['metode_pembayaran']); ?></td>
                                                <td>
                                                    <?php
                                                    $bayar_class = 'secondary';
                                                    switch($transaksi['status_pembayaran']){
                                                        case 'Belum Bayar': $bayar_class='warning'; break;
                                                        case 'Sudah Bayar': $bayar_class='success'; break;
                                                        case 'Ditolak': $bayar_class='danger'; break;
                                                    }
                                                    ?>
                                                    <span class="btn btn-<?= $bayar_class ?> btn-sm"><?= $transaksi['status_pembayaran'] ?></span>
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
                                                    <a href="detail_pesanan_admin.php?id=<?= $transaksi['id_transaksi'] ?>" class="btn btn-info btn-sm">Detail</a>
                                                    <a href="ubah_status_pesanan.php?id=<?= $transaksi['id_transaksi'] ?>" class="btn btn-warning btn-sm">Ubah Status</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Belum ada pesanan masuk.</td>
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