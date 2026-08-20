<?php
 include 'index.php';
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <br>
                <button class="btn btn-success rounded" data-bs-toggle="modal" data-bs-target="#addProductModal">Tambah Produk</button>
                <p></p>
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Produk Kopi</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kopi</th>
                                        <th>Jenis Kopi</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Berat</th>
                                        <th>Status</th>
                                        <th class="text-center"> Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM produk_kopi"); 
                                    $no = 1;
                                    $modals = '';
                                    while ($produk = mysqli_fetch_array($query)) {
                                        // Status otomatis cek disini
                                        $status = ($produk['stok'] > 0) ? "Tersedia" : "Tidak Tersedia";
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $produk['nama_kopi']; ?></td>
                                        <td><?= $produk['jenis_kopi']; ?></td>
                                        <td><?= $produk['stok']; ?></td>
                                        <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                                        <td><?= $produk['berat']; ?> gram</td>
                                        <td>
                                            <?php if ($status == "Tersedia") { ?>
                                                <span class="badge bg-success"><?= $status; ?></span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger"><?= $status; ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                data-id="<?= $produk['id_produk']; ?>"
                                                data-nama="<?= htmlspecialchars($produk['nama_kopi'], ENT_QUOTES); ?>"
                                                data-jenis="<?= htmlspecialchars($produk['jenis_kopi'], ENT_QUOTES); ?>"
                                                data-stok="<?= $produk['stok']; ?>"
                                                data-harga="<?= $produk['harga']; ?>"
                                                data-berat="<?= $produk['berat']; ?>"
                                                data-status="<?= ($produk['stok'] > 0) ? 'Tersedia' : 'Tidak Tersedia'; ?>"
                                                data-deskripsi="<?= htmlspecialchars($produk['deskripsi'], ENT_QUOTES); ?>">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                                data-id="<?= $produk['id_produk']; ?>"
                                                data-nama="<?= htmlspecialchars($produk['nama_kopi'], ENT_QUOTES); ?>">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info view-btn" data-bs-toggle="modal" data-bs-target="#viewProductModal"
                                                data-id="<?= $produk['id_produk']; ?>"
                                                data-nama="<?= htmlspecialchars($produk['nama_kopi'], ENT_QUOTES); ?>"
                                                data-jenis="<?= htmlspecialchars($produk['jenis_kopi'], ENT_QUOTES); ?>"
                                                data-stok="<?= $produk['stok']; ?>"
                                                data-harga="<?= $produk['harga']; ?>"
                                                data-berat="<?= $produk['berat']; ?>"
                                                data-deskripsi="<?= htmlspecialchars($produk['deskripsi'], ENT_QUOTES); ?>"
                                                data-foto="<?= htmlspecialchars($produk['foto_produk'], ENT_QUOTES); ?>">
                                                <i class="ti ti-photo"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                            <!-- Reusable Modals (single instances) -->
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="editproduk.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kopi</label>
                                                    <input type="text" class="form-control" name="nama_kopi" id="edit-nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Kopi</label>
                                                    <input type="text" class="form-control" name="jenis_kopi" id="edit-jenis" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Stok</label>
                                                    <input type="number" class="form-control" name="stok" id="edit-stok" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="number" class="form-control" name="harga" id="edit-harga" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Berat (gram)</label>
                                                    <input type="number" class="form-control" name="berat" id="edit-berat" step="0.01" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-control" name="status" id="edit-status" required>
                                                        <option value="Tersedia">Tersedia</option>
                                                        <option value="Tidak Tersedia">Tidak Tersedia</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea class="form-control" name="deskripsi" id="edit-deskripsi"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar</label>
                                                    <input type="file" class="form-control" name="gambar">
                                                    <input type="hidden" name="id_produk" id="edit-id">
                                                    <small class="text-danger">*Kosongkan jika tidak ingin mengubah gambar</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary" name="edit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="hapusproduk.php" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="delete-text">Apakah Anda yakin ingin menghapus produk ini?</p>
                                                <input type="hidden" name="id_produk" id="delete-id">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="view-title">Detail Produk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" id="view-body">
                                            <img src="" id="view-image" class="img-fluid mb-3" alt="">
                                            <table class="table" id="view-table">
                                            </table>
                                            <p id="view-deskripsi"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Script to populate reusable modals when opened
                            ?>
                            <script>
                                document.addEventListener('show.bs.modal', function (e) {
                                    var modalId = e.target.id;
                                    var trigger = e.relatedTarget; // the button that opened the modal
                                    if (!trigger) return;
                                    if (modalId === 'editProductModal') {
                                        document.getElementById('edit-id').value = trigger.getAttribute('data-id');
                                        document.getElementById('edit-nama').value = trigger.getAttribute('data-nama') || '';
                                        document.getElementById('edit-jenis').value = trigger.getAttribute('data-jenis') || '';
                                        document.getElementById('edit-stok').value = trigger.getAttribute('data-stok') || '';
                                        document.getElementById('edit-harga').value = trigger.getAttribute('data-harga') || '';
                                        document.getElementById('edit-berat').value = trigger.getAttribute('data-berat') || '';
                                        document.getElementById('edit-status').value = trigger.getAttribute('data-status') || '';
                                        document.getElementById('edit-deskripsi').value = trigger.getAttribute('data-deskripsi') || '';
                                    } else if (modalId === 'deleteProductModal') {
                                        var id = trigger.getAttribute('data-id');
                                        var nama = trigger.getAttribute('data-nama') || '';
                                        document.getElementById('delete-id').value = id;
                                        document.getElementById('delete-text').textContent = 'Apakah Anda yakin ingin menghapus produk "' + nama + '"?';
                                    } else if (modalId === 'viewProductModal') {
                                        var nama = trigger.getAttribute('data-nama') || '';
                                        var jenis = trigger.getAttribute('data-jenis') || '';
                                        var berat = trigger.getAttribute('data-berat') || '';
                                        var stok = trigger.getAttribute('data-stok') || '';
                                        var harga = trigger.getAttribute('data-harga') || '';
                                        var deskripsi = trigger.getAttribute('data-deskripsi') || '';
                                        var foto = trigger.getAttribute('data-foto') || '';
                                        document.getElementById('view-title').textContent = nama;
                                        var img = document.getElementById('view-image');
                                        if (foto) {
                                            img.src = 'assets/images/produk/' + foto;
                                            img.alt = nama;
                                            img.style.display = '';
                                        } else {
                                            img.style.display = 'none';
                                        }
                                        var table = document.getElementById('view-table');
                                        table.innerHTML = '<tr><th>Jenis</th><td>' + jenis + '</td></tr>' +
                                                          '<tr><th>Berat</th><td>' + berat + ' gram</td></tr>' +
                                                          '<tr><th>Harga</th><td>Rp ' + (parseInt(harga)||0) + '</td></tr>' +
                                                          '<tr><th>Stok</th><td>' + stok + '</td></tr>';
                                        document.getElementById('view-deskripsi').innerHTML = '<strong>Deskripsi:</strong><br>' + deskripsi;
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="tambahproduk.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Kopi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kopi</label>
                        <input type="text" class="form-control" name="nama_kopi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kopi</label>
                        <input type="text" class="form-control" name="jenis_kopi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>
                    <!-- FIELD BERAT BARU -->
                    <div class="mb-3">
                        <label class="form-label">Berat (gram)</label>
                        <input type="number" class="form-control" name="berat" step="0.01" placeholder="Contoh: 250" required>
                        <small class="text-muted">Masukkan berat dalam gram</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="pc-footer"></footer>