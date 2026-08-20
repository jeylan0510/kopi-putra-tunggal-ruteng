<?php
include 'index.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: pesanan_masuk.php");
    exit;
}

$id_transaksi = $_GET['id'];

// Ambil detail transaksi
$query_transaksi = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan, p.no_hp, t.alamat_pengiriman,
           pr.nama_kopi, dt.jumlah, dt.harga_satuan, dt.subtotal,
           t.metode_pembayaran, pb.jumlah_bayar, pb.bukti_pembayaran, pb.tgl_pembayaran
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    LEFT JOIN produk_kopi pr ON dt.id_produk = pr.id_produk
    LEFT JOIN pembayaran pb ON t.id_transaksi = pb.id_transaksi
    WHERE t.id_transaksi = '$id_transaksi'
");

if (!$query_transaksi || mysqli_num_rows($query_transaksi) == 0) {
    echo "<script>alert('Data transaksi tidak ditemukan'); window.location='pesanan_admin.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan, p.no_hp, t.alamat_pengiriman,
           t.metode_pembayaran, pb.jumlah_bayar, pb.bukti_pembayaran, pb.tgl_pembayaran
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    LEFT JOIN pembayaran pb ON t.id_transaksi = pb.id_transaksi
    WHERE t.id_transaksi = '$id_transaksi'
"));
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <h3>Detail Pesanan</h3>
                        <hr>
                        <table class="table">
                            <tr><th>ID Transaksi</th><td><?= $data['id_transaksi'] ?></td></tr>
                            <tr><th>Nama Pelanggan</th><td><?= $data['nama_pelanggan'] ?></td></tr>
                            <tr><th>No HP</th><td><?= $data['no_hp'] ?></td></tr>
                            <tr><th>Alamat</th><td><?= $data['alamat_pengiriman'] ?></td></tr>
                            <tr><th>Tanggal Transaksi</th><td><?= date('d-m-Y H:i', strtotime($data['tgl_transaksi'])) ?></td></tr>
                            <tr><th>Total Harga</th><td>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td></tr>
                            <tr><th>Status Pesanan</th><td><?= $data['status_pesanan'] ?></td></tr>
                            <tr><th>Metode Pembayaran</th><td><?= $data['metode_pembayaran'] ?></td></tr>
                            <tr><th>Status Pembayaran</th><td><?= $data['status_pembayaran'] ?></td></tr>
                            <tr><th>Tanggal Pembayaran</th><td><?= $data['tgl_pembayaran'] ? date('d-m-Y H:i', strtotime($data['tgl_pembayaran'])) : '-' ?></td></tr>
                            <tr>
                                <th>Bukti Pembayaran</th>
                                <td>
                                    <?php if ($data['bukti_pembayaran']): ?>
                                        <a href="assets/images/bukti/<?= $data['bukti_pembayaran'] ?>" target="_blank">
                                            <img src="assets/images/bukti/<?= $data['bukti_pembayaran'] ?>" width="100">
                                        </a>
                                    <?php else: ?> -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Detail Barang Dipesan</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_data_seek($query_transaksi, 0);
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query_transaksi)) {
                                    echo "<tr>
                                            <td>$no</td>
                                            <td>{$row['nama_kopi']}</td>
                                            <td>{$row['jumlah']}</td>
                                            <td>Rp " . number_format($row['harga_satuan'], 0, ',', '.') . "</td>
                                            <td>Rp " . number_format($row['subtotal'], 0, ',', '.') . "</td>
                                          </tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>

                        <a href="pesanan_masuk.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>