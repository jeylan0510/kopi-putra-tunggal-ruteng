<?php
include 'index.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: pesanan_masuk.php");
    exit;
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'");
if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pesanan_masuk.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($query);

// Jika form disubmit
if (isset($_POST['update'])) {
    $status_pesanan = $_POST['status_pesanan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $update = mysqli_query($conn, "
        UPDATE transaksi 
        SET status_pesanan='$status_pesanan', status_pembayaran='$status_pembayaran'
        WHERE id_transaksi='$id_transaksi'
    ");

    if ($update) {
        echo "<script>alert('Status berhasil diperbarui!'); window.location='pesanan_masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status.');</script>";
    }
}
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3>Ubah Status Pesanan</h3>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Status Pesanan</label>
                                <select class="form-select" name="status_pesanan" required>
                                    <option value="<?= $data['status_pesanan'] ?>" selected><?= $data['status_pesanan'] ?></option>
                                    <option value="Pending">Pending</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Pembayaran</label>
                                <select class="form-select" name="status_pembayaran" required>
                                    <option value="<?= $data['status_pembayaran'] ?>" selected><?= $data['status_pembayaran'] ?></option>
                                    <option value="Belum Bayar">Belum Bayar</option>
                                    <option value="Sudah Bayar">Sudah Bayar</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="pesanan_masuk.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>