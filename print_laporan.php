<?php
include 'koneksi.php';

// Ambil data filter tanggal dari parameter GET
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

// Query laporan
$query = "
    SELECT 
        t.id_transaksi,
        t.id_pelanggan,
        t.tgl_transaksi,
        t.total_harga,
        t.metode_pembayaran,
        t.status_pembayaran,
        p.tgl_pembayaran
    FROM transaksi t
    LEFT JOIN pembayaran p ON t.id_transaksi = p.id_transaksi
";

// Jika ada filter tanggal
if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query .= " WHERE DATE(t.tgl_transaksi) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

$query .= " ORDER BY t.tgl_transaksi DESC";
$result = mysqli_query($conn, $query);

// Siapkan data dan total pendapatan
$data = [];
$totalPendapatan = 0;

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $totalPendapatan += $row['total_harga'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        p {
            text-align: center;
            margin: 5px 0 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .total {
            text-align: right;
            font-weight: bold;
            padding-right: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 14px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h2>Kopi Ruteng</h2>
    <h4>Laporan Penjualan</h4>
    <p>
        Periode:
        <?php
        if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
            echo date('d/m/Y', strtotime($tgl_mulai)) . " - " . date('d/m/Y', strtotime($tgl_selesai));
        } else {
            echo "Semua Data Transaksi";
        }
        ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>ID Pelanggan</th>
                <th>Tanggal Transaksi</th>
                <th>Tanggal Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php $no = 1; foreach ($data as $d): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['id_transaksi']; ?></td>
                        <td><?= $d['id_pelanggan']; ?></td>
                        <td><?= date('d/m/Y', strtotime($d['tgl_transaksi'])); ?></td>
                        <td><?= !empty($d['tgl_pembayaran']) ? date('d/m/Y', strtotime($d['tgl_pembayaran'])) : '-'; ?></td>
                        <td><?= $d['metode_pembayaran'] ?? '-'; ?></td>
                        <td><?= $d['status_pembayaran'] ?? '-'; ?></td>
                        <td>Rp <?= number_format($d['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Tidak ada data transaksi untuk periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="total">Total Pendapatan</td>
                <td><strong>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Makassar, <?= date('d F Y'); ?></p>
        <p><strong>Admin Kopi Ruteng</strong></p>
        <br><br><br>
        <p>(____________________)</p>
    </div>

    <div class="no-print" style="text-align:center; margin-top:20px;">
        <button onclick="window.print()">🖨️ Cetak Laporan</button>
        <a href="laporan_penjualan.php"><button>Kembali</button></a>
    </div>
</body>
</html>