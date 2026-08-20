<?php
include 'indexuser.php';
session_start();

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = array();
    }

    // Ambil data produk dari DB
    $query = mysqli_query($conn, "SELECT * FROM produk_kopi WHERE id_produk = '$id_produk'");
    $produk = mysqli_fetch_array($query);

    if ($produk) {
        $id_produk = $produk['id_produk'];
        $nama_kopi = $produk['nama_kopi'];
        $berat = $produk['berat'];
        $harga = $produk['harga'];
        $stok = $produk['stok'];
        $foto_produk = $produk['foto_produk'];

        if ($stok > 0) {
            if (!isset($_SESSION['keranjang'][$id_produk])) {
                $_SESSION['keranjang'][$id_produk] = array(
                    'nama_kopi' => $nama_kopi,
                    'berat' => $berat,
                    'harga' => $harga,
                    'jumlah' => 1, // default 1 unit
                    'stok' => $stok,
                    'foto_produk' => $foto_produk
                );
            }
        } else {
            echo "<script>alert('Maaf, stok produk ini habis'); window.location='daftarproduk.php';</script>";
        }
    }
}

if (isset($_GET['hapus_produk'])) {
    unset($_SESSION['keranjang'][$_GET['hapus_produk']]);
}

if (isset($_POST['update_keranjang'])) {
    foreach ($_POST['jumlah'] as $id_produk => $jumlah) {
        if ($jumlah <= 0) {
            unset($_SESSION['keranjang'][$id_produk]);
        } else {
            $_SESSION['keranjang'][$id_produk]['jumlah'] = min($jumlah, $_SESSION['keranjang'][$id_produk]['stok']);
        }
    }
}

if (isset($_GET['reset_keranjang'])) {
    unset($_SESSION['keranjang']);
}

$total_harga = 0;
if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
    foreach ($_SESSION['keranjang'] as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }
}
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <?php if (!empty($_SESSION['keranjang'])): ?>
                    <div class="card">
                        <div class="card-body table-border-style">
                            <h3 class="mb-4">Keranjang Kopi Ruteng</h3>
                            <form method="POST" action="keranjang.php">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kopi</th>
                                                <th>Berat</th>
                                                <th>Stok</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($_SESSION['keranjang'] as $id_produk => $item): ?>
                                                <?php $subtotal = $item['harga'] * $item['jumlah']; ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $item['nama_kopi']; ?></td>
                                                    <td><?= $item['berat']; ?> Gram</td>
                                                    <td><?= $item['stok']; ?></td>
                                                    <td>
                                                        <input type="number" name="jumlah[<?= $id_produk; ?>]" value="<?= $item['jumlah']; ?>" min="1" max="<?= $item['stok']; ?>" class="form-control" style="width: 80px;">
                                                    </td>
                                                    <td>Rp <?= number_format($item['harga'],0,',','.'); ?></td>
                                                    <td>Rp <?= number_format($subtotal,0,',','.'); ?></td>
                                                    <td><a href="keranjang.php?hapus_produk=<?= $id_produk; ?>" class="btn btn-danger btn-sm">Hapus</a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" name="update_keranjang" class="btn btn-primary">Update Keranjang</button>
                                <div class="mt-3 d-flex justify-content-between">
                                    <h4>Total: Rp <?= number_format($total_harga,0,',','.'); ?></h4>
                                    <a href="checkout.php" class="btn btn-success">Lanjut ke Pembayaran</a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger text-center">Keranjang kosong</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 