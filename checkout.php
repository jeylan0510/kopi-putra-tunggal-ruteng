<?php
include 'indexuser.php';
session_start();

// Cek apakah keranjang kosong
if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong!'); window.location='daftarproduk.php';</script>";
    exit();
}

// Hitung total harga dari keranjang
$total_harga = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total_harga += $item['harga'] * $item['jumlah'];
}

// Simpan total di session untuk proses bayar
$_SESSION['total_harga'] = $total_harga;
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col">
                <div class="card mx-auto" style="max-width:500px;">
                    <div class="card-header text-center">
                        <h3>Checkout Kopi Ruteng</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">Ringkasan Pesanan:</h5>
                        <ul class="list-group mb-3">
                            <?php foreach ($_SESSION['keranjang'] as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($item['nama_kopi']) ?> x <?= $item['jumlah'] ?>
                                    <span>Rp <?= number_format($item['harga'] * $item['jumlah'],0,',','.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Total</strong>
                                <strong>Rp <?= number_format($total_harga,0,',','.'); ?></strong>
                            </li>
                        </ul>

                        <form method="POST" action="bayar.php" enctype="multipart/form-data">
                            <!-- Hidden input untuk metode pembayaran -->
                            <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">

                            <div class="mt-3">
                                <h6>Pilih Metode Pembayaran:</h6>
                                <div class="d-flex justify-content-around mt-2">
                                    <button type="button" class="btn btn-outline-success" id="btn_qris">
                                        <i class="fas fa-qrcode"></i><br>QRIS
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="btn_dana">
                                        <i class="fas fa-wallet"></i><br>Transfer DANA
                                    </button>
                                    <button type="button" class="btn btn-outline-warning" id="btn_cod">
                                        <i class="fas fa-truck"></i><br>COD
                                    </button>
                                </div>
                            </div>

                            <div id="qris_info" class="mt-3" style="display:none;">
                                <h6>Bayar via QRIS</h6>
                                <p>Scan QRIS di bawah ini untuk membayar:</p>
                                <center>
                                    <img src="assets/images/qris.jpg" alt="QRIS Kopi Ruteng" style="width:200px;">
                                </center>
                            </div>

                            <div id="dana_info" class="mt-3" style="display:none;">
                                <h6>Bayar via DANA</h6>
                                <p>Transfer ke nomor DANA: <strong>0812-3456-7890</strong></p>
                            </div>

                            <div id="cod_info" class="mt-3" style="display:none;">
                                <h6>Bayar via COD</h6>
                                <p>Masukkan alamat pengiriman Anda:</p>
                                <textarea name="alamat_pengiriman" class="form-control" placeholder="Alamat Lengkap"></textarea>
                            </div>

                            <div class="mt-4">
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*">
                                </div>

                                <input type="hidden" name="total_harga" value="<?= $total_harga; ?>">

                                <!-- Tombol Lanjutkan Pembayaran -->
                                <button type="submit" name="proses_bayar" id="btn_submit" class="btn btn-success w-100" disabled>Lanjutkan Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const btnQRIS = document.getElementById('btn_qris');
    const btnDANA = document.getElementById('btn_dana');
    const btnCOD = document.getElementById('btn_cod');

    const qrisInfo = document.getElementById('qris_info');
    const danaInfo = document.getElementById('dana_info');
    const codInfo = document.getElementById('cod_info');

    const metodeInput = document.getElementById('metode_pembayaran');
    const btnSubmit = document.getElementById('btn_submit');

    function pilihMetode(metode) {
        qrisInfo.style.display = 'none';
        danaInfo.style.display = 'none';
        codInfo.style.display = 'none';

        if (metode === 'QRIS') qrisInfo.style.display = 'block';
        if (metode === 'DANA') danaInfo.style.display = 'block';
        if (metode === 'COD') codInfo.style.display = 'block';

        // Set value hidden input metode_pembayaran
        metodeInput.value = metode;

        // Aktifkan tombol submit
        btnSubmit.disabled = false;

        // Atur required untuk file bukti pembayaran (COD tidak wajib)
        const bukti = document.getElementById('bukti_pembayaran');
        if(metode === 'COD') {
            bukti.removeAttribute('required');
        } else {
            bukti.setAttribute('required', 'required');
        }
    }

    btnQRIS.addEventListener('click', () => pilihMetode('QRIS'));
    btnDANA.addEventListener('click', () => pilihMetode('DANA'));
    btnCOD.addEventListener('click', () => pilihMetode('COD'));
</script>