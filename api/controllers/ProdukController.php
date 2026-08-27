<?php
/**
 * Controller for Produk Kopi CRUD API operations
 */

class ProdukController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Handle Request routing for Produk resource
     * 
     * @param string $method HTTP Method (GET, POST, PUT, DELETE)
     * @param int|null $id Resource ID
     */
    public function handleRequest($method, $id = null) {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getOne($id);
                } else {
                    $this->getAll();
                }
                break;

            case 'POST':
                $this->create();
                break;

            case 'PUT':
                if (!$id) {
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk memperbarui data produk.");
                }
                $this->update($id);
                break;

            case 'DELETE':
                if (!$id) {
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk menghapus produk.");
                }
                $this->delete($id);
                break;

            default:
                sendResponse(false, 405, "Metode HTTP '{$method}' tidak diizinkan.");
                break;
        }
    }

    /**
     * READ ALL Produk
     */
    private function getAll() {
        $this->ensureTableExists();

        $search = isset($_GET['search']) ? mysqli_real_escape_string($this->conn, $_GET['search']) : '';
        $jenis  = isset($_GET['jenis']) ? mysqli_real_escape_string($this->conn, $_GET['jenis']) : '';

        $whereClause = [];
        if (!empty($search)) {
            $whereClause[] = "nama_kopi LIKE '%$search%'";
        }
        if (!empty($jenis)) {
            $whereClause[] = "jenis_kopi = '$jenis'";
        }

        $sql = "SELECT * FROM produk_kopi";
        if (count($whereClause) > 0) {
            $sql .= " WHERE " . implode(" AND ", $whereClause);
        }
        $sql .= " ORDER BY id_produk ASC";

        $result = mysqli_query($this->conn, $sql);
        $produkList = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['id_produk'] = (int)$row['id_produk'];
                $row['stok']      = (int)$row['stok'];
                $row['harga']     = (float)$row['harga'];
                $row['berat']     = isset($row['berat']) ? (float)$row['berat'] : 0;
                $row['status']    = ($row['stok'] > 0) ? "Tersedia" : "Tidak Tersedia";
                $produkList[]     = $row;
            }
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($produkList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function ensureTableExists() {
        if (!$this->conn) return;
        $createSql = "CREATE TABLE IF NOT EXISTS produk_kopi (
            id_produk INT AUTO_INCREMENT PRIMARY KEY,
            nama_kopi VARCHAR(100) NOT NULL,
            jenis_kopi VARCHAR(50) NOT NULL,
            stok INT NOT NULL DEFAULT 0,
            harga DOUBLE NOT NULL DEFAULT 0,
            berat DOUBLE NOT NULL DEFAULT 0,
            deskripsi TEXT NULL,
            foto_produk VARCHAR(255) DEFAULT 'default.jpg'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        @mysqli_query($this->conn, $createSql);

        $checkEmpty = @mysqli_query($this->conn, "SELECT COUNT(*) as total FROM produk_kopi");
        if ($checkEmpty) {
            $row = mysqli_fetch_assoc($checkEmpty);
            if ((int)($row['total'] ?? 0) === 0) {
                $seedSql = "INSERT INTO produk_kopi (nama_kopi, jenis_kopi, stok, harga, berat, deskripsi, foto_produk) VALUES
                ('Kopi Arabika Flores Bajawa', 'Arabika', 15, 75000, 250, 'Kopi Arabika khas Flores Bajawa dengan cita rasa manis karamel dan aroma bunga.', 'default.jpg'),
                ('Kopi Robusta Ruteng Manggarai', 'Robusta', 20, 50000, 250, 'Kopi Robusta khas Ruteng Manggarai dengan bodi tebal dan rasa cokelat hitam.', 'default.jpg'),
                ('Kopi Liberika Manggarai', 'Liberika', 10, 85000, 200, 'Kopi Liberika unik dengan aroma buah nangka dan keasaman seimbang.', 'default.jpg'),
                ('Kopi Toraja Kalosi', 'Arabika', 12, 90000, 250, 'Kopi khas Toraja dengan aroma rempah dan rasa yang kaya.', 'default.jpg'),
                ('Kopi Gayo Specialty', 'Arabika', 18, 95000, 250, 'Kopi Arabika Gayo Aceh dengan body sedang dan aroma herbal murni.', 'default.jpg');";
                @mysqli_query($this->conn, $seedSql);
            }
        }
    }

    /**
     * READ SINGLE Produk by ID
     */
    private function getOne($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM produk_kopi WHERE id_produk = $id";
        $result = mysqli_query($this->conn, $sql);

        if (!$result || mysqli_num_rows($result) === 0) {
            sendResponse(false, 404, "Produk dengan ID $id tidak ditemukan.");
        }

        $row = mysqli_fetch_assoc($result);
        $row['id_produk'] = (int)$row['id_produk'];
        $row['stok']      = (int)$row['stok'];
        $row['harga']     = (float)$row['harga'];
        $row['berat']     = isset($row['berat']) ? (float)$row['berat'] : 0;
        $row['status']    = ($row['stok'] > 0) ? "Tersedia" : "Tidak Tersedia";
        $row['url_foto']  = !empty($row['foto_produk']) ? "assets/images/produk/" . $row['foto_produk'] : null;

        sendResponse(true, 200, "Detail produk berhasil diambil.", $row);
    }

    /**
     * CREATE Produk
     */
    private function create() {
        $data = getRequestData();

        $nama_kopi  = trim($data['nama_kopi'] ?? '');
        $jenis_kopi = trim($data['jenis_kopi'] ?? '');
        $stok       = isset($data['stok']) ? (int)$data['stok'] : 0;
        $harga      = isset($data['harga']) ? (float)$data['harga'] : 0;
        $berat      = isset($data['berat']) ? (float)$data['berat'] : 0;
        $deskripsi  = trim($data['deskripsi'] ?? '');

        // Validation
        $errors = [];
        if (empty($nama_kopi)) $errors[] = "Nama kopi tidak boleh kosong.";
        if (empty($jenis_kopi)) $errors[] = "Jenis kopi tidak boleh kosong.";
        if ($stok < 0) $errors[] = "Stok tidak boleh kurang dari 0.";
        if ($harga <= 0) $errors[] = "Harga harus lebih dari 0.";
        if ($berat <= 0) $errors[] = "Berat harus lebih dari 0 gram.";
        if ($berat > 1000) $errors[] = "Berat maksimal 1000 gram (1kg).";

        if (!empty($errors)) {
            sendResponse(false, 400, "Validasi gagal: " . implode(" ", $errors));
        }

        // Upload file handling if any
        $foto_produk = 'default.jpg';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../assets/images/produk/";
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                $new_filename = uniqid('prod_') . '_' . time() . '.' . $file_ext;
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $new_filename)) {
                    $foto_produk = $new_filename;
                }
            }
        }

        $nama_kopi_esc  = mysqli_real_escape_string($this->conn, $nama_kopi);
        $jenis_kopi_esc = mysqli_real_escape_string($this->conn, $jenis_kopi);
        $deskripsi_esc  = mysqli_real_escape_string($this->conn, $deskripsi);
        $foto_esc       = mysqli_real_escape_string($this->conn, $foto_produk);

        $sql = "INSERT INTO produk_kopi (nama_kopi, jenis_kopi, stok, harga, berat, deskripsi, foto_produk) 
                VALUES ('$nama_kopi_esc', '$jenis_kopi_esc', $stok, $harga, $berat, '$deskripsi_esc', '$foto_esc')";

        if (mysqli_query($this->conn, $sql)) {
            $newId = mysqli_insert_id($this->conn);
            sendResponse(true, 201, "Produk berhasil ditambahkan.", [
                'id_produk'  => $newId,
                'nama_kopi'  => $nama_kopi,
                'jenis_kopi' => $jenis_kopi,
                'stok'       => $stok,
                'harga'      => $harga,
                'berat'      => $berat,
                'deskripsi'  => $deskripsi,
                'foto_produk'=> $foto_produk
            ]);
        } else {
            sendResponse(false, 500, "Gagal menyimpan produk: " . mysqli_error($this->conn));
        }
    }

    /**
     * UPDATE Produk by ID
     */
    private function update($id) {
        $id = (int)$id;

        // Check if product exists
        $checkQuery = mysqli_query($this->conn, "SELECT * FROM produk_kopi WHERE id_produk = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Produk dengan ID $id tidak ditemukan.");
        }
        $existing = mysqli_fetch_assoc($checkQuery);

        $data = getRequestData();

        $nama_kopi  = isset($data['nama_kopi']) ? trim($data['nama_kopi']) : $existing['nama_kopi'];
        $jenis_kopi = isset($data['jenis_kopi']) ? trim($data['jenis_kopi']) : $existing['jenis_kopi'];
        $stok       = isset($data['stok']) ? (int)$data['stok'] : (int)$existing['stok'];
        $harga      = isset($data['harga']) ? (float)$data['harga'] : (float)$existing['harga'];
        $berat      = isset($data['berat']) ? (float)$data['berat'] : (float)($existing['berat'] ?? 0);
        $deskripsi  = isset($data['deskripsi']) ? trim($data['deskripsi']) : $existing['deskripsi'];

        // Validation
        $errors = [];
        if (empty($nama_kopi)) $errors[] = "Nama kopi tidak boleh kosong.";
        if (empty($jenis_kopi)) $errors[] = "Jenis kopi tidak boleh kosong.";
        if ($stok < 0) $errors[] = "Stok tidak boleh kurang dari 0.";
        if ($harga <= 0) $errors[] = "Harga harus lebih dari 0.";
        if ($berat <= 0) $errors[] = "Berat harus lebih dari 0 gram.";
        if ($berat > 1000) $errors[] = "Berat maksimal 1000 gram (1kg).";

        if (!empty($errors)) {
            sendResponse(false, 400, "Validasi gagal: " . implode(" ", $errors));
        }

        $gambar_sql = "";
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../assets/images/produk/";
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                $new_filename = uniqid('prod_') . '_' . time() . '.' . $file_ext;
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $new_filename)) {
                    $gambar_sql = ", foto_produk='" . mysqli_real_escape_string($this->conn, $new_filename) . "'";
                    // Delete old file if present
                    if (!empty($existing['foto_produk']) && file_exists($target_dir . $existing['foto_produk']) && $existing['foto_produk'] !== 'default.jpg') {
                        unlink($target_dir . $existing['foto_produk']);
                    }
                }
            }
        }

        $nama_kopi_esc  = mysqli_real_escape_string($this->conn, $nama_kopi);
        $jenis_kopi_esc = mysqli_real_escape_string($this->conn, $jenis_kopi);
        $deskripsi_esc  = mysqli_real_escape_string($this->conn, $deskripsi);

        $sql = "UPDATE produk_kopi SET 
                    nama_kopi = '$nama_kopi_esc',
                    jenis_kopi = '$jenis_kopi_esc',
                    stok = $stok,
                    harga = $harga,
                    berat = $berat,
                    deskripsi = '$deskripsi_esc'
                    $gambar_sql
                WHERE id_produk = $id";

        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Produk kopi berhasil diperbarui.", [
                'id_produk'  => $id,
                'nama_kopi'  => $nama_kopi,
                'jenis_kopi' => $jenis_kopi,
                'stok'       => $stok,
                'harga'      => $harga,
                'berat'      => $berat,
                'deskripsi'  => $deskripsi
            ]);
        } else {
            sendResponse(false, 500, "Gagal memperbarui produk: " . mysqli_error($this->conn));
        }
    }

    /**
     * DELETE Produk by ID
     */
    private function delete($id) {
        $id = (int)$id;

        $checkQuery = mysqli_query($this->conn, "SELECT foto_produk FROM produk_kopi WHERE id_produk = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Produk dengan ID $id tidak ditemukan.");
        }

        $data = mysqli_fetch_assoc($checkQuery);
        $file_gambar = "../assets/images/produk/" . $data['foto_produk'];

        if (!empty($data['foto_produk']) && file_exists($file_gambar) && $data['foto_produk'] !== 'default.jpg') {
            unlink($file_gambar);
        }

        $sql = "DELETE FROM produk_kopi WHERE id_produk = $id";
        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Produk dengan ID $id berhasil dihapus.");
        } else {
            sendResponse(false, 500, "Gagal menghapus produk: " . mysqli_error($this->conn));
        }
    }
}
