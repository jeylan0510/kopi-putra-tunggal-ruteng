<?php
include 'index.php';
include 'koneksi_php';
session_start();

// --- Proses Tambah Admin ---
if (isset($_POST['tambah'])) {
    $nama_admin = mysqli_real_escape_string($conn, $_POST['nama_admin']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');

    $insert = mysqli_query($conn, "INSERT INTO admin (nama_admin, username, password, email, created_at)
                                   VALUES ('$nama_admin', '$username', '$password', '$email', '$created_at')");
    if ($insert) {
        echo "<script>alert('Admin berhasil ditambahkan!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan admin!');</script>";
    }
}

// --- Proses Hapus Admin ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM admin WHERE id_admin='$id'");
    echo "<script>alert('Admin berhasil dihapus!'); window.location='admin.php';</script>";
}

// --- Ambil Data Admin ---
$query_admin = mysqli_query($conn, "SELECT * FROM admin ORDER BY created_at DESC");
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="mb-4">Manajemen Admin</h2>

                        <!-- Tombol Tambah -->
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </button>

                        <!-- Tabel Admin -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Admin</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Dibuat Pada</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($query_admin) > 0):
                                        $no = 1;
                                        $modals = '';
                                        while ($row = mysqli_fetch_assoc($query_admin)):
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_admin']); ?></td>
                                        <td><?= htmlspecialchars($row['username']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_admin']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="?hapus=<?= $row['id_admin']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus admin ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>

                                    <?php
                                    // collect edit modal HTML to output after the table
                                    $id = $row['id_admin'];
                                    $nama = addslashes($row['nama_admin']);
                                    $username = addslashes($row['username']);
                                    $email = addslashes($row['email']);
                                    $modals .= "\n<div class=\"modal fade\" id=\"modalEdit{$id}\" tabindex=\"-1\" aria-hidden=\"true\">";
                                    $modals .= "<div class=\"modal-dialog\"><div class=\"modal-content\">";
                                    $modals .= "<form method=\"POST\" action=\"update_admin.php\">";
                                    $modals .= "<div class=\"modal-header\"><h5 class=\"modal-title\">Edit Admin</h5><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\"></button></div>";
                                    $modals .= "<div class=\"modal-body\"><input type=\"hidden\" name=\"id_admin\" value=\"{$id}\">";
                                    $modals .= "<div class=\"mb-3\"><label class=\"form-label\">Nama Admin</label><input type=\"text\" name=\"nama_admin\" class=\"form-control\" value=\"{$nama}\" required></div>";
                                    $modals .= "<div class=\"mb-3\"><label class=\"form-label\">Username</label><input type=\"text\" name=\"username\" class=\"form-control\" value=\"{$username}\" required></div>";
                                    $modals .= "<div class=\"mb-3\"><label class=\"form-label\">Email</label><input type=\"email\" name=\"email\" class=\"form-control\" value=\"{$email}\" required></div>";
                                    $modals .= "<div class=\"mb-3\"><label class=\"form-label\">Password (opsional)</label><input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"Kosongkan jika tidak diubah\"></div>";
                                    $modals .= "</div><div class=\"modal-footer\"><button type=\"submit\" name=\"update\" class=\"btn btn-success\">Simpan Perubahan</button><button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Batal</button></div></form></div></div></div>\n";
                                    ?>
                                    <?php endwhile; else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data admin.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php echo $modals; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Admin</label>
                        <input type="text" name="nama_admin" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>