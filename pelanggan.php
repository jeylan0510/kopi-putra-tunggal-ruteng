<?php
    include 'index.php';
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h2>Daftar Pelanggan</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM pelanggan"); 
                                    $no = 1;
                                    $modals = '';
                                    while ($pelanggan = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pelanggan['username']; ?></td>
                                        <td><?php echo $pelanggan['email']; ?></td>
                                        <td><?php echo $pelanggan['no_hp']; ?></td>
                                        <td><?php echo $pelanggan['alamat']; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTechnicianModal<?php echo $pelanggan['id_pelanggan']; ?>"><i class="ti ti-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    $id = $pelanggan['id_pelanggan'];
                                    $name = addslashes($pelanggan['username']);
                                    $modals .= "\n<div class=\"modal fade\" id=\"deleteTechnicianModal{$id}\" tabindex=\"-1\" aria-labelledby=\"deleteTechnicianModalLabel{$id}\" aria-hidden=\"true\">";
                                    $modals .= "<div class=\"modal-dialog\"><div class=\"modal-content\">";
                                    $modals .= "<form action=\"hapuspelanggan.php\" method=\"POST\">";
                                    $modals .= "<div class=\"modal-header\"><h5 class=\"modal-title\" id=\"deleteTechnicianModalLabel{$id}\">Hapus Pelanggan</h5><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button></div>";
                                    $modals .= "<div class=\"modal-body\"><p>Apakah Anda yakin ingin menghapus pelanggan \"{$name}\"?</p><input type=\"hidden\" name=\"id\" value=\"{$id}\"></div>";
                                    $modals .= "<div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-primary\" data-bs-dismiss=\"modal\">Batal</button><button type=\"submit\" class=\"btn btn-danger\" name=\"hapus\">Hapus</button></div></form></div></div></div>\n";
                                    }
                                    ?>
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
<footer class="pc-footer"></footer>
