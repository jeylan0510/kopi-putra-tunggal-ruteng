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
        $sql = "SELECT id_pelanggan, username, email, no_hp, alamat FROM pelanggan ORDER BY id_pelanggan DESC";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            sendResponse(false, 500, "Gagal mengambil data pelanggan: " . mysqli_error($this->conn));
        }

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['id_pelanggan'] = (int)$row['id_pelanggan'];
            $list[] = $row;
        }

        sendResponse(true, 200, "Data pelanggan berhasil diambil.", [
            'total' => count($list),
            'items' => $list
        ]);
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
