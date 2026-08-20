<!-- ═══════════════════════════════════════════════════════════════════════════════════════════ -->
<!-- FILE: tambahproduk.php - Tambah Produk Kopi dengan Design Modern & Interactive -->
<!-- ═══════════════════════════════════════════════════════════════════════════════════════════ -->

<?php
// Include koneksi database
include 'koneksi.php';

// Cek apakah form disubmit
if (isset($_POST['tambah'])) {
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // 1. SANITASI INPUT DATA
    // ─────────────────────────────────────────────────────────────────────────────────────────
    $nama_kopi = mysqli_real_escape_string($conn, trim($_POST['nama_kopi']));
    $jenis_kopi = mysqli_real_escape_string($conn, trim($_POST['jenis_kopi']));
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $berat = mysqli_real_escape_string($conn, $_POST['berat']);
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi']));
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // 2. VALIDASI INPUT
    // ─────────────────────────────────────────────────────────────────────────────────────────
    $errors = array();
    
    if (empty($nama_kopi)) {
        $errors[] = "Nama kopi tidak boleh kosong";
    }
    
    if ($stok < 0) {
        $errors[] = "Stok tidak boleh kurang dari 0";
    }
    
    if ($harga <= 0) {
        $errors[] = "Harga harus lebih dari 0";
    }
    
    if ($berat <= 0) {
        $errors[] = "Berat harus lebih dari 0";
    }
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // 3. PROSES UPLOAD GAMBAR
    // ─────────────────────────────────────────────────────────────────────────────────────────
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "assets/images/produk/";
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (empty($gambar)) {
        $errors[] = "Silakan pilih gambar produk";
    } else {
        // Validasi ukuran file
        if ($_FILES['gambar']['size'] > $max_size) {
            $errors[] = "Ukuran gambar maksimal 5MB";
        }
        
        // Validasi ekstensi
        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            $errors[] = "Format gambar tidak valid. Gunakan: JPG, JPEG, PNG, GIF, atau WEBP";
        }
    }
    
    // ─────────────────────────────────────────────────────────────────────────────────────────
    // 4. EKSEKUSI JIKA TIDAK ADA ERROR
    // ─────────────────────────────────────────────────────────────────────────────────────────
    if (empty($errors)) {
        // Generate nama file unik
        $gambar_baru = uniqid() . '_' . time() . '.' . $ext;
        
        // Upload file
        if (move_uploaded_file($tmp, $folder . $gambar_baru)) {
            // Query insert
            $query = "INSERT INTO produk_kopi (
                        nama_kopi, 
                        jenis_kopi, 
                        stok, 
                        harga, 
                        berat, 
                        deskripsi, 
                        foto_produk
                      ) VALUES (
                        '$nama_kopi', 
                        '$jenis_kopi', 
                        '$stok', 
                        '$harga', 
                        '$berat', 
                        '$deskripsi', 
                        '$gambar_baru'
                      )";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>
                        alert('✓ Produk kopi berhasil ditambahkan!');
                        window.location='produk.php';
                      </script>";
            } else {
                echo "<script>
                        alert('✗ Gagal menambahkan produk: " . mysqli_error($conn) . "');
                        window.location='produk.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('✗ Gagal mengupload gambar!');
                    window.location='produk.php';
                  </script>";
        }
    } else {
        // Tampilkan semua error
        $error_message = implode("\\n", $errors);
        echo "<script>
                alert('✗ Terjadi kesalahan:\\n" . $error_message . "');
                window.location='produk.php';
              </script>";
    }
    
} else {
    // Redirect jika akses langsung
    header('Location: produk.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Kopi - Coffee Shop</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6B4423 0%, #3E2723 50%, #1A1A1A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            max-width: 1000px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #8B4513, #6B4423);
            padding: 30px 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '☕';
            position: absolute;
            font-size: 120px;
            opacity: 0.1;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .back-btn {
            position: absolute;
            top: 30px;
            left: 40px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }
        
        /* Form Container */
        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 40px;
        }
        
        @media (max-width: 768px) {
            .form-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Form Section */
        .form-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-group label .required {
            color: #e74c3c;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .input-with-unit {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .input-with-unit input {
            flex: 1;
        }
        
        .input-with-unit .unit {
            background: #f5f5f5;
            padding: 12px 16px;
            border-radius: 10px;
            font-weight: 600;
            color: #666;
            min-width: 80px;
            text-align: center;
        }
        
        /* Preview Section */
        .preview-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .preview-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .image-preview-container {
            background: white;
            border-radius: 12px;
            padding: 15px;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #d0d0d0;
            position: relative;
            overflow: hidden;
        }
        
        .image-preview-container.has-image {
            border-style: solid;
            border-color: #8B4513;
        }
        
        #imagePreview {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            display: none;
        }
        
        .upload-placeholder {
            text-align: center;
            color: #999;
        }
        
        .upload-placeholder svg {
            width: 64px;
            height: 64px;
            margin-bottom: 10px;
            opacity: 0.5;
        }
        
        .preview-info {
            background: white;
            border-radius: 12px;
            padding: 15px;
        }
        
        .preview-info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .preview-info-item:last-child {
            border-bottom: none;
        }
        
        .preview-info-item .label {
            color: #666;
            font-size: 13px;
        }
        
        .preview-info-item .value {
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        
        /* File Upload Custom */
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            left: -9999px;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 16px;
            background: #8B4513;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .file-upload-label:hover {
            background: #6B4423;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
        }
        
        .file-name {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        /* Button Group */
        .button-group {
            grid-column: 1 / -1;
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8B4513, #6B4423);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.4);
        }
        
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        /* Loading Animation */
        .loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .loading::after {
            content: '';
            border: 3px solid #f3f3f3;
            border-top: 3px solid #8B4513;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="produk.php" class="back-btn">
                ← Kembali
            </a>
            <h1>☕ Tambah Produk Kopi Baru</h1>
            <p>Lengkapi informasi produk kopi dengan detail</p>
        </div>
        
        <!-- Form -->
        <form id="productForm" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <!-- Left Section - Form Inputs -->
                <div class="form-section">
                    <div class="form-group">
                        <label>
                            Nama Kopi <span class="required">*</span>
                        </label>
                        <input type="text" name="nama_kopi" id="nama_kopi" placeholder="Contoh: Arabica Gayo Premium" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Jenis Kopi <span class="required">*</span>
                        </label>
                        <input type="text" name="jenis_kopi" id="jenis_kopi" placeholder="Contoh: Arabica, Robusta, Liberica" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Stok <span class="required">*</span>
                        </label>
                        <input type="number" name="stok" id="stok" placeholder="0" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Harga <span class="required">*</span>
                        </label>
                        <div class="input-with-unit">
                            <input type="number" name="harga" id="harga" placeholder="50000" min="0" required>
                            <span class="unit">Rp</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Berat <span class="required">*</span>
                        </label>
                        <div class="input-with-unit">
                            <input type="number" name="berat" id="berat" placeholder="250" step="0.01" min="0" required>
                            <span class="unit">gram</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsikan produk kopi Anda..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Foto Produk <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="gambar" id="gambar" class="file-upload-input" accept="image/*" required>
                            <label for="gambar" class="file-upload-label">
                                📷 Pilih Gambar
                            </label>
                        </div>
                        <div class="file-name" id="fileName">Belum ada file dipilih</div>
                    </div>
                </div>
                
                <!-- Right Section - Preview -->
                <div class="form-section">
                    <div class="preview-section">
                        <div class="preview-title">Preview Produk</div>
                        
                        <div class="image-preview-container" id="imagePreviewContainer">
                            <img id="imagePreview" src="" alt="Preview">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <p>Preview gambar akan muncul di sini</p>
                            </div>
                        </div>
                        
                        <div class="preview-info">
                            <div class="preview-info-item">
                                <span class="label">Nama Produk:</span>
                                <span class="value" id="previewNama">-</span>
                            </div>
                            <div class="preview-info-item">
                                <span class="label">Jenis:</span>
                                <span class="value" id="previewJenis">-</span>
                            </div>
                            <div class="preview-info-item">
                                <span class="label">Berat:</span>
                                <span class="value" id="previewBerat">-</span>
                            </div>
                            <div class="preview-info-item">
                                <span class="label">Harga:</span>
                                <span class="value" id="previewHarga">-</span>
                            </div>
                            <div class="preview-info-item">
                                <span class="label">Stok:</span>
                                <span class="value" id="previewStok">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Button Group -->
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="window.location='produk.php'">
                        ✕ Batal
                    </button>
                    <button type="submit" name="tambah" class="btn btn-primary" id="submitBtn">
                        ✓ Simpan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        // Preview Gambar
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileName = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            
            if (file) {
                fileName.textContent = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    uploadPlaceholder.style.display = 'none';
                    imagePreviewContainer.classList.add('has-image');
                }
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = 'Belum ada file dipilih';
                imagePreview.style.display = 'none';
                uploadPlaceholder.style.display = 'block';
                imagePreviewContainer.classList.remove('has-image');
            }
        });
        
        // Live Preview Info
        function updatePreview() {
            const nama = document.getElementById('nama_kopi').value || '-';
            const jenis = document.getElementById('jenis_kopi').value || '-';
            const berat = document.getElementById('berat').value;
            const harga = document.getElementById('harga').value;
            const stok = document.getElementById('stok').value || '-';
            
            document.getElementById('previewNama').textContent = nama;
            document.getElementById('previewJenis').textContent = jenis;
            document.getElementById('previewBerat').textContent = berat ? berat + ' gram' : '-';
            document.getElementById('previewHarga').textContent = harga ? 'Rp ' + parseInt(harga).toLocaleString('id-ID') : '-';
            document.getElementById('previewStok').textContent = stok;
        }
        
        // Event listeners untuk live preview
        document.getElementById('nama_kopi').addEventListener('input', updatePreview);
        document.getElementById('jenis_kopi').addEventListener('input', updatePreview);
        document.getElementById('berat').addEventListener('input', updatePreview);
        document.getElementById('harga').addEventListener('input', updatePreview);
        document.getElementById('stok').addEventListener('input', updatePreview);
        
        // Form submission dengan loading state
        document.getElementById('productForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Menyimpan';
        });
    </script>
</body>
</html>