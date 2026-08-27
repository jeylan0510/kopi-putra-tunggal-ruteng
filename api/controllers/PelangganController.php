<?php
/**
 * Controller for Pelanggan CRUD API operations
 */

class PelangganController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

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
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk memperbarui data pelanggan.");
                }
                $this->update($id);
                break;

            case 'DELETE':
                if (!$id) {
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk menghapus pelanggan.");
                }
                $this->delete($id);
                break;

            default:
                sendResponse(false, 405, "Metode HTTP '{$method}' tidak diizinkan.");
                break;
        }
    }

    private function getAll() {
        $this->ensureTableExists();

        $sql = "SELECT id_pelanggan, username, email, no_hp, alamat FROM pelanggan ORDER BY id_pelanggan ASC";
        $result = mysqli_query($this->conn, $sql);

        $list = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['id_pelanggan'] = (int)$row['id_pelanggan'];
                $list[] = $row;
            }
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function ensureTableExists() {
        if (!$this->conn) return;
        $createSql = "CREATE TABLE IF NOT EXISTS pelanggan (
            id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            no_hp VARCHAR(20) NULL,
            alamat TEXT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        @mysqli_query($this->conn, $createSql);

        $checkEmpty = @mysqli_query($this->conn, "SELECT COUNT(*) as total FROM pelanggan");
        if ($checkEmpty) {
            $row = mysqli_fetch_assoc($checkEmpty);
            if ((int)($row['total'] ?? 0) === 0) {
                $pass = md5('123456');
                $seedSql = "INSERT INTO pelanggan (username, email, password, no_hp, alamat) VALUES
                ('jeylan0510', 'jeylan@gmail.com', '$pass', '081234567890', 'Ruteng, Manggarai, NTT'),
                ('budi_santoso', 'budi@gmail.com', '$pass', '082198765432', 'Jl. Ahmad Yani No. 12, Ruteng'),
                ('maria_ani', 'maria@gmail.com', '$pass', '085333444555', 'Jl. Motang Rua, Ruteng');";
                @mysqli_query($this->conn, $seedSql);
            }
        }
    }

    private function getOne($id) {
        $id = (int)$id;
        $sql = "SELECT id_pelanggan, username, email, no_hp, alamat FROM pelanggan WHERE id_pelanggan = $id";
        $result = mysqli_query($this->conn, $sql);

        if (!$result || mysqli_num_rows($result) === 0) {
            sendResponse(false, 404, "Pelanggan dengan ID $id tidak ditemukan.");
        }

        $row = mysqli_fetch_assoc($result);
        $row['id_pelanggan'] = (int)$row['id_pelanggan'];

        sendResponse(true, 200, "Detail pelanggan berhasil diambil.", $row);
    }

    private function create() {
        $data = getRequestData();

        $username = trim($data['username'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $no_hp    = trim($data['no_hp'] ?? '');
        $alamat   = trim($data['alamat'] ?? '');

        $errors = [];
        if (empty($username)) $errors[] = "Username tidak boleh kosong.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
        if (empty($password)) $errors[] = "Password tidak boleh kosong.";

        if (!empty($errors)) {
            sendResponse(false, 400, "Validasi gagal: " . implode(" ", $errors));
        }

        // Check if email or username exists
        $username_esc = mysqli_real_escape_string($this->conn, $username);
        $email_esc    = mysqli_real_escape_string($this->conn, $email);

        $check = mysqli_query($this->conn, "SELECT id_pelanggan FROM pelanggan WHERE username = '$username_esc' OR email = '$email_esc'");
        if (mysqli_num_rows($check) > 0) {
            sendResponse(false, 400, "Username atau Email sudah terdaftar.");
        }

        // Password hash or direct store depending on existing db pattern
        $pass_hash   = md5($password); // standard hash matched with legacy system if any, or password_hash
        $no_hp_esc   = mysqli_real_escape_string($this->conn, $no_hp);
        $alamat_esc  = mysqli_real_escape_string($this->conn, $alamat);

        $sql = "INSERT INTO pelanggan (username, email, password, no_hp, alamat) 
                VALUES ('$username_esc', '$email_esc', '$pass_hash', '$no_hp_esc', '$alamat_esc')";

        if (mysqli_query($this->conn, $sql)) {
            $newId = mysqli_insert_id($this->conn);
            sendResponse(true, 201, "Pelanggan berhasil ditambahkan.", [
                'id_pelanggan' => $newId,
                'username'     => $username,
                'email'        => $email,
                'no_hp'        => $no_hp,
                'alamat'       => $alamat
            ]);
        } else {
            sendResponse(false, 500, "Gagal menambahkan pelanggan: " . mysqli_error($this->conn));
        }
    }

    private function update($id) {
        $id = (int)$id;
        $checkQuery = mysqli_query($this->conn, "SELECT * FROM pelanggan WHERE id_pelanggan = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Pelanggan dengan ID $id tidak ditemukan.");
        }
        $existing = mysqli_fetch_assoc($checkQuery);

        $data = getRequestData();

        $username = isset($data['username']) ? trim($data['username']) : $existing['username'];
        $email    = isset($data['email']) ? trim($data['email']) : $existing['email'];
        $no_hp    = isset($data['no_hp']) ? trim($data['no_hp']) : $existing['no_hp'];
        $alamat   = isset($data['alamat']) ? trim($data['alamat']) : $existing['alamat'];

        $username_esc = mysqli_real_escape_string($this->conn, $username);
        $email_esc    = mysqli_real_escape_string($this->conn, $email);
        $no_hp_esc    = mysqli_real_escape_string($this->conn, $no_hp);
        $alamat_esc   = mysqli_real_escape_string($this->conn, $alamat);

        $pass_sql = "";
        if (!empty($data['password'])) {
            $pass_hash = md5($data['password']);
            $pass_sql = ", password = '$pass_hash'";
        }

        $sql = "UPDATE pelanggan SET 
                    username = '$username_esc',
                    email = '$email_esc',
                    no_hp = '$no_hp_esc',
                    alamat = '$alamat_esc'
                    $pass_sql
                WHERE id_pelanggan = $id";

        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Data pelanggan berhasil diperbarui.", [
                'id_pelanggan' => $id,
                'username'     => $username,
                'email'        => $email,
                'no_hp'        => $no_hp,
                'alamat'       => $alamat
            ]);
        } else {
            sendResponse(false, 500, "Gagal memperbarui data pelanggan: " . mysqli_error($this->conn));
        }
    }

    private function delete($id) {
        $id = (int)$id;
        $checkQuery = mysqli_query($this->conn, "SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Pelanggan dengan ID $id tidak ditemukan.");
        }

        $sql = "DELETE FROM pelanggan WHERE id_pelanggan = $id";
        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Pelanggan dengan ID $id berhasil dihapus.");
        } else {
            sendResponse(false, 500, "Gagal menghapus pelanggan: " . mysqli_error($this->conn));
        }
    }
}
