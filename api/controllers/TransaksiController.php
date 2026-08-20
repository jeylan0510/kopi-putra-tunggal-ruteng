<?php
/**
 * Controller for Transaksi / Pesanan CRUD API operations
 */

class TransaksiController {
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
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk memperbarui transaksi.");
                }
                $this->update($id);
                break;

            case 'DELETE':
                if (!$id) {
                    sendResponse(false, 400, "Parameter 'id' wajib diisi untuk menghapus transaksi.");
                }
                $this->delete($id);
                break;

            default:
                sendResponse(false, 405, "Metode HTTP '{$method}' tidak diizinkan.");
                break;
        }
    }

    private function getAll() {
        $id_pelanggan = isset($_GET['id_pelanggan']) ? (int)$_GET['id_pelanggan'] : 0;
        $status       = isset($_GET['status']) ? mysqli_real_escape_string($this->conn, $_GET['status']) : '';

        $where = [];
        if ($id_pelanggan > 0) {
            $where[] = "t.id_pelanggan = $id_pelanggan";
        }
        if (!empty($status)) {
            $where[] = "t.status_pesanan = '$status'";
        }

        $sql = "SELECT t.id_transaksi, t.id_pelanggan, t.tgl_transaksi, t.total_harga, 
                       t.status_pesanan, t.metode_pembayaran, t.status_pembayaran,
                       p.username AS nama_pelanggan, p.email AS email_pelanggan
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan";

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY t.tgl_transaksi DESC";

        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            sendResponse(false, 500, "Gagal mengambil data transaksi: " . mysqli_error($this->conn));
        }

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['id_transaksi'] = (int)$row['id_transaksi'];
            $row['id_pelanggan'] = (int)$row['id_pelanggan'];
            $row['total_harga']  = (float)$row['total_harga'];
            $list[] = $row;
        }

        sendResponse(true, 200, "Data transaksi berhasil diambil.", [
            'total' => count($list),
            'items' => $list
        ]);
    }

    private function getOne($id) {
        $id = (int)$id;
        $sql = "SELECT t.id_transaksi, t.id_pelanggan, t.tgl_transaksi, t.total_harga, 
                       t.status_pesanan, t.metode_pembayaran, t.status_pembayaran,
                       p.username AS nama_pelanggan, p.email AS email_pelanggan, p.no_hp, p.alamat
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                WHERE t.id_transaksi = $id";

        $result = mysqli_query($this->conn, $sql);
        if (!$result || mysqli_num_rows($result) === 0) {
            sendResponse(false, 404, "Transaksi dengan ID $id tidak ditemukan.");
        }

        $row = mysqli_fetch_assoc($result);
        $row['id_transaksi'] = (int)$row['id_transaksi'];
        $row['id_pelanggan'] = (int)$row['id_pelanggan'];
        $row['total_harga']  = (float)$row['total_harga'];

        // Get details if detail_pesanan table exists
        $detailRes = mysqli_query($this->conn, "SHOW TABLES LIKE 'detail_pesanan'");
        if ($detailRes && mysqli_num_rows($detailRes) > 0) {
            $detailsQuery = mysqli_query($this->conn, "
                SELECT d.*, pr.nama_kopi, pr.harga 
                FROM detail_pesanan d
                LEFT JOIN produk_kopi pr ON d.id_produk = pr.id_produk
                WHERE d.id_transaksi = $id
            ");
            $details = [];
            if ($detailsQuery) {
                while ($d = mysqli_fetch_assoc($detailsQuery)) {
                    $details[] = $d;
                }
            }
            $row['detail_items'] = $details;
        }

        sendResponse(true, 200, "Detail transaksi berhasil diambil.", $row);
    }

    private function create() {
        $data = getRequestData();

        $id_pelanggan      = isset($data['id_pelanggan']) ? (int)$data['id_pelanggan'] : 0;
        $total_harga       = isset($data['total_harga']) ? (float)$data['total_harga'] : 0;
        $metode_pembayaran = trim($data['metode_pembayaran'] ?? 'Transfer Bank');
        $status_pesanan    = trim($data['status_pesanan'] ?? 'Menunggu Pembayaran');
        $status_pembayaran = trim($data['status_pembayaran'] ?? 'Belum Bayar');

        if ($id_pelanggan <= 0) {
            sendResponse(false, 400, "id_pelanggan wajib diisi.");
        }
        if ($total_harga <= 0) {
            sendResponse(false, 400, "total_harga harus lebih dari 0.");
        }

        $metode_esc = mysqli_real_escape_string($this->conn, $metode_pembayaran);
        $pesanan_esc = mysqli_real_escape_string($this->conn, $status_pesanan);
        $bayar_esc   = mysqli_real_escape_string($this->conn, $status_pembayaran);
        $now         = date('Y-m-d H:i:s');

        $sql = "INSERT INTO transaksi (id_pelanggan, tgl_transaksi, total_harga, status_pesanan, metode_pembayaran, status_pembayaran)
                VALUES ($id_pelanggan, '$now', $total_harga, '$pesanan_esc', '$metode_esc', '$bayar_esc')";

        if (mysqli_query($this->conn, $sql)) {
            $newId = mysqli_insert_id($this->conn);
            sendResponse(true, 201, "Transaksi berhasil dibuat.", [
                'id_transaksi'      => $newId,
                'id_pelanggan'      => $id_pelanggan,
                'tgl_transaksi'     => $now,
                'total_harga'       => $total_harga,
                'status_pesanan'    => $status_pesanan,
                'metode_pembayaran' => $metode_pembayaran,
                'status_pembayaran' => $status_pembayaran
            ]);
        } else {
            sendResponse(false, 500, "Gagal membuat transaksi: " . mysqli_error($this->conn));
        }
    }

    private function update($id) {
        $id = (int)$id;
        $checkQuery = mysqli_query($this->conn, "SELECT * FROM transaksi WHERE id_transaksi = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Transaksi dengan ID $id tidak ditemukan.");
        }
        $existing = mysqli_fetch_assoc($checkQuery);

        $data = getRequestData();

        $status_pesanan    = isset($data['status_pesanan']) ? trim($data['status_pesanan']) : $existing['status_pesanan'];
        $status_pembayaran = isset($data['status_pembayaran']) ? trim($data['status_pembayaran']) : $existing['status_pembayaran'];
        $metode_pembayaran = isset($data['metode_pembayaran']) ? trim($data['metode_pembayaran']) : $existing['metode_pembayaran'];
        $total_harga       = isset($data['total_harga']) ? (float)$data['total_harga'] : (float)$existing['total_harga'];

        $pesanan_esc = mysqli_real_escape_string($this->conn, $status_pesanan);
        $bayar_esc   = mysqli_real_escape_string($this->conn, $status_pembayaran);
        $metode_esc  = mysqli_real_escape_string($this->conn, $metode_pembayaran);

        $sql = "UPDATE transaksi SET 
                    status_pesanan = '$pesanan_esc',
                    status_pembayaran = '$bayar_esc',
                    metode_pembayaran = '$metode_esc',
                    total_harga = $total_harga
                WHERE id_transaksi = $id";

        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Status transaksi berhasil diperbarui.", [
                'id_transaksi'      => $id,
                'status_pesanan'    => $status_pesanan,
                'status_pembayaran' => $status_pembayaran,
                'metode_pembayaran' => $metode_pembayaran,
                'total_harga'       => $total_harga
            ]);
        } else {
            sendResponse(false, 500, "Gagal memperbarui transaksi: " . mysqli_error($this->conn));
        }
    }

    private function delete($id) {
        $id = (int)$id;
        $checkQuery = mysqli_query($this->conn, "SELECT id_transaksi FROM transaksi WHERE id_transaksi = $id");
        if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
            sendResponse(false, 404, "Transaksi dengan ID $id tidak ditemukan.");
        }

        $sql = "DELETE FROM transaksi WHERE id_transaksi = $id";
        if (mysqli_query($this->conn, $sql)) {
            sendResponse(true, 200, "Transaksi dengan ID $id berhasil dihapus.");
        } else {
            sendResponse(false, 500, "Gagal menghapus transaksi: " . mysqli_error($this->conn));
        }
    }
}
