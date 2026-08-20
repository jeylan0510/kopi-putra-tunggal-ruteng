<?php
include 'index.php';
session_start();

// Ambil data transaksi yang sudah selesai atau dibatalkan
$query_riwayat = mysqli_query($conn, "
    SELECT 
        t.id_transaksi, 
        pl.nama_pelanggan, 
        t.tgl_transaksi, 
        t.total_harga, 
        t.status_pesanan, 
        t.status_pembayaran, 
        t.metode_pembayaran, 
        p.jumlah_bayar, 
        p.tgl_pembayaran
    FROM transaksi t
    LEFT JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
    LEFT JOIN pembayaran p ON t.id_transaksi = p.id_transaksi
    WHERE t.status_pesanan IN ('Selesai', 'Dibatalkan')
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
                        <h2>Riwayat Transaksi</h2>
                        <p class="text-muted mb-4">Berisi daftar transaksi yang telah selesai atau dibatalkan.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="text-dark fs-6">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total Harga</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status Pesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($query_riwayat) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($query_riwayat)): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($row['tgl_transaksi'])); ?></td>
                                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
                                                
                                                <td>
                                                    <?php
                                                    $status_class = ($row['status_pesanan'] == 'Selesai') ? 'success' : 'danger';
                                                    ?>
                                                    <span class="badge bg-<?= $status_class ?>"><?= $row['status_pesanan'] ?></span>
                                                </td>
                                                
                                                <td>
                                                    <?php
                                                    $pay_class = 'secondary';
                                                    if ($row['status_pembayaran'] == 'Sudah Bayar') $pay_class = 'success';
                                                    elseif ($row['status_pembayaran'] == 'Belum Bayar') $pay_class = 'warning';
                                                    elseif ($row['status_pembayaran'] == 'Ditolak') $pay_class = 'danger';
                                                    ?>
                                                    <span class="badge bg-<?= $pay_class ?>"><?= $row['status_pembayaran'] ?></span>
                                                </td>
                                                
                                                <td><?= $row['tgl_pembayaran'] ? date('d-m-Y H:i', strtotime($row['tgl_pembayaran'])) : '-' ?></td>
                                                
                                                <td>
                                                    <a href="detail_pesanan_admin.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-info btn-sm">Detail</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Belum ada transaksi selesai atau dibatalkan.</td>
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