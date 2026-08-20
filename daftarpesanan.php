<?php
include 'index.php';

$query = mysqli_query($conn, "
    SELECT 
        s.id_sewa,
        u.username AS nama_user,
        b.nama_barang,
        s.jumlah,
        s.total_harga,
        s.jaminan,
        s.tgl_sewa,
        s.tgl_pengembalian,
        s.status
    FROM sewa s
    INNER JOIN users u ON s.id = u.id
    INNER JOIN barang b ON s.id_barang = b.id_barang
    ORDER BY s.tgl_sewa DESC
");

$no = 1;
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <p></p>
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Daftar Penyewaan</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                        <th>Jaminan</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($sewa = mysqli_fetch_assoc($query)) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $sewa['nama_user']; ?></td>
                                            <td><?= $sewa['nama_barang']; ?></td>
                                            <td><?= $sewa['jumlah']; ?></td>
                                            <td>Rp<?= number_format($sewa['total_harga'], 0, ',', '.'); ?></td>
                                            <td><?= $sewa['jaminan']; ?></td>
                                            <td><?= date('d-m-Y', strtotime($sewa['tgl_sewa'])); ?></td>
                                            <td><?= date('d-m-Y', strtotime($sewa['tgl_pengembalian'])); ?></td>
                                            <td>
                                                <?php if ($sewa['status'] === 'Bayar') { ?>
                                                    <span class="badge bg-warning">Bayar</span>
                                                <?php } elseif ($sewa['status'] === 'Diproses') { ?>
                                                    <span class="badge bg-primary">Diproses</span>
                                                <?php } elseif ($sewa['status'] === 'Selesai') { ?>
                                                    <span class="badge bg-success">Selesai</span>
                                                <?php } elseif ($sewa['status'] === 'Dibatalkan') { ?>
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($sewa['status'] === 'Bayar') { ?>
                                                    <form method="POST" action="update_status.php">
                                                        <input type="hidden" name="id_sewa" value="<?= $sewa['id_sewa']; ?>">
                                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto" required>
                                                            <option value="Diproses">Diproses</option>
                                                            <option value="Dibatalkan">Dibatalkan</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                    </form>
                                                <?php } elseif ($sewa['status'] === 'Diproses') { ?>
                                                    <form method="POST" action="update_status.php">
                                                        <input type="hidden" name="id_sewa" value="<?= $sewa['id_sewa']; ?>">
                                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto" required>
                                                            <option value="Selesai">Selesai</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>