<?php
include 'index.php';
session_start();

// Default: tampilkan semua transaksi yang sudah selesai
$where = "WHERE t.status_pesanan = 'Selesai'";

// Jika admin memilih rentang tanggal
if (isset($_POST['tanggal_mulai']) && isset($_POST['tanggal_selesai'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
        $where .= " AND DATE(t.tgl_transaksi) BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
    }
}

// Query data transaksi
$query_laporan = mysqli_query($conn, "
    SELECT 
        t.id_transaksi, 
        pl.nama_pelanggan, 
        t.tgl_transaksi, 
        t.total_harga, 
        t.metode_pembayaran, 
        p.tgl_pembayaran
    FROM transaksi t
    LEFT JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
    LEFT JOIN pembayaran p ON t.id_transaksi = p.id_transaksi
    $where
    ORDER BY t.tgl_transaksi DESC
");

// Hitung total pendapatan
$query_total = mysqli_query($conn, "
    SELECT SUM(t.total_harga) AS total_pendapatan, COUNT(*) AS total_transaksi
    FROM transaksi t
    $where
");
$data_total = mysqli_fetch_assoc($query_total);
$total_pendapatan = $data_total['total_pendapatan'] ?? 0;
$total_transaksi = $data_total['total_transaksi'] ?? 0;
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Laporan Penjualan</h2>
                        <p class="text-muted mb-3">Menampilkan rekap penjualan berdasarkan tanggal transaksi.</p>

                        <!-- Form Filter Tanggal -->
                        <form method="POST" class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="<?= $_POST['tanggal_mulai'] ?? '' ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="<?= $_POST['tanggal_selesai'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Tampilkan
                                </button>
                            </div>
                        </form>

                        <!-- Ringkasan Penjualan -->
                        <div class="alert alert-success">
                            <strong>Total Transaksi:</strong> <?= $total_transaksi ?> |
                            <strong>Total Pendapatan:</strong> Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
                        </div>

                        <!-- Tabel Laporan -->
                        <div class="table-responsive mt-3">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($query_laporan) > 0): ?>
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($query_laporan)): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($row['tgl_transaksi'])); ?></td>
                                                <td><?= $row['tgl_pembayaran'] ? date('d-m-Y H:i', strtotime($row['tgl_pembayaran'])) : '-' ?></td>
                                                <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
                                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data transaksi pada periode ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Cetak -->
                        <div class="mt-3 text-end">
                            <a href="print_laporan.php?tgl1=<?= $_POST['tanggal_mulai'] ?? '' ?>&tgl2=<?= $_POST['tanggal_selesai'] ?? '' ?>" 
                               target="_blank" class="btn btn-outline-success">
                                <i class="fas fa-print"></i> Cetak Laporan
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>