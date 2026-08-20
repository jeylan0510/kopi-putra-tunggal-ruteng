<?php
include 'indexuser.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM produk_kopi WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (nama_kopi LIKE ? OR deskripsi LIKE ?)";
}

$stmt = $conn->prepare($sql);


if (!empty($search)) {
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ecom-wrapper">
                    <div class="ecom-content">
                        <div class="d-sm-flex align-items-center justify-content-between mb-3">
                            <form method="GET" action="daftarproduk.php" class="form-search position-relative">
                                <i class="ti ti-search position-absolute"></i>
                                <input type="search" name="search" class="form-control ps-5" placeholder="Cari Produk" value="<?= htmlspecialchars($search) ?>">
                            </form>
                        </div>
                        <div class="row">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="col-sm-6 col-md-4 col-xxl-3">
                                    <div class="card product-card">
                                        <div class="card-img-top position-relative">
                                            <img src="assets/images/produk/<?= htmlspecialchars($row['foto_produk']) ?>" class="img-fluid card-img" alt="<?= htmlspecialchars($row['nama_kopi']) ?>">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="mb-0"><?= htmlspecialchars($row['nama_kopi']) ?></h5>
                                            <p class="mb-0"><small>Stok: <?= $row['stok'] ?></small></p>
                                            <p class="mb-1"><small>Berat: <?= isset($row['berat']) ? $row['berat'] : '250' ?> gram</small></p>
                                            <div class="d-flex align-items-center justify-content-between mt-3">
                                                <h4 class="mb-0">
                                                    <b>Rp <?= number_format($row['harga'], 0, ',', '.') ?></b>
                                                </h4>
                                                <a class="btn btn-primary" href="keranjang.php?id_produk=<?= $row['id_produk'] ?>">
                                                    <i class="ti ti-shopping-cart"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Info Produk -->
                                <div class="modal fade" id="infoModal<?= $row['id_produk'] ?>" tabindex="-1" aria-labelledby="infoModalLabel<?= $row['id_produk'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="infoModalLabel<?= $row['id_produk'] ?>"><?= htmlspecialchars($row['nama_kopi']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                                                <p>Stok: <?= htmlspecialchars($row['stok']) ?></p>
                                                <p>Berat: <?= isset($row['berat']) ? htmlspecialchars($row['berat']) : '250' ?> gram</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-img {
    object-fit: cover;
    height: 200px;
}
.form-search {
    position: relative;
}
.form-search .ti-search {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    font-size: 20px;
}
.form-search .form-control {
    padding-left: 35px;
}
</style>
<footer class="pc-footer"></footer>