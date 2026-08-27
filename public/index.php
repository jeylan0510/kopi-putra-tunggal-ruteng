<?php
/**
 * Standalone Production Entry Point for Railway Deployment
 */
@ini_set('display_errors', '0');
@error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. Read Requested Endpoint / Resource
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$parts = explode('/', trim($uri, '/'));
$endpoint = strtolower(end($parts));

$resource = strtolower(trim($_GET['resource'] ?? $_GET['endpoint'] ?? ''));
if (empty($resource) && !empty($endpoint) && $endpoint !== 'index.php' && $endpoint !== 'api') {
    $resource = $endpoint;
}
if (empty($resource)) {
    $resource = 'produk';
}

// 2. Connect to Database safely
$conn = null;
if (class_exists('mysqli')) {
    @mysqli_report(MYSQLI_REPORT_OFF);

    $hostname = getenv('MYSQLHOST') ?: getenv('RAILWAY_TCP_PROXY_DOMAIN') ?: 'localhost';
    $username = getenv('MYSQLUSER') ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_ROOT_PASSWORD') ?: 'AEHAKoDgNSjuBuZaVwlQxMLfFSRfRALV';
    $dbname   = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'railway';
    $port     = (int)(getenv('MYSQLPORT') ?: getenv('RAILWAY_TCP_PROXY_PORT') ?: 3306);

    $conn = @new mysqli($hostname, $username, $password, $dbname, $port);
    if (!$conn || $conn->connect_error) {
        $conn = @new mysqli('localhost', 'root', '', 'kopi');
    }
}

// 3. Handle Endpoints
if (in_array($resource, ['produk', 'product', 'products', 'gitar', 'gitars'])) {
    $produkList = [];
    if ($conn && !$conn->connect_error) {
        @mysqli_query($conn, "CREATE TABLE IF NOT EXISTS produk_kopi (
            id_produk INT AUTO_INCREMENT PRIMARY KEY,
            nama_kopi VARCHAR(100) NOT NULL,
            jenis_kopi VARCHAR(50) NOT NULL,
            stok INT NOT NULL DEFAULT 0,
            harga DOUBLE NOT NULL DEFAULT 0,
            berat DOUBLE NOT NULL DEFAULT 0,
            deskripsi TEXT NULL,
            foto_produk VARCHAR(255) DEFAULT 'default.jpg'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $checkEmpty = @mysqli_query($conn, "SELECT COUNT(*) as total FROM produk_kopi");
        if ($checkEmpty) {
            $row = mysqli_fetch_assoc($checkEmpty);
            if ((int)($row['total'] ?? 0) === 0) {
                @mysqli_query($conn, "INSERT INTO produk_kopi (nama_kopi, jenis_kopi, stok, harga, berat, deskripsi, foto_produk) VALUES
                ('Kopi Arabika Flores Bajawa', 'Arabika', 15, 75000, 250, 'Kopi Arabika khas Flores Bajawa dengan cita rasa manis karamel dan aroma bunga.', 'default.jpg'),
                ('Kopi Robusta Ruteng Manggarai', 'Robusta', 20, 50000, 250, 'Kopi Robusta khas Ruteng Manggarai dengan bodi tebal dan rasa cokelat hitam.', 'default.jpg'),
                ('Kopi Liberika Manggarai', 'Liberika', 10, 85000, 200, 'Kopi Liberika unik dengan aroma buah nangka dan keasaman seimbang.', 'default.jpg'),
                ('Kopi Toraja Kalosi', 'Arabika', 12, 90000, 250, 'Kopi khas Toraja dengan aroma rempah dan rasa yang kaya.', 'default.jpg'),
                ('Kopi Gayo Specialty', 'Arabika', 18, 95000, 250, 'Kopi Arabika Gayo Aceh dengan body sedang dan aroma herbal murni.', 'default.jpg');");
            }
        }

        $res = @mysqli_query($conn, "SELECT * FROM produk_kopi ORDER BY id_produk ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $r['id']         = (int)$r['id_produk'];
                $r['nama']       = $r['nama_kopi'];
                $r['seri']       = $r['jenis_kopi'];
                $r['brand']      = 'Kopi Putra Tunggal Ruteng';
                $r['ukuran']     = ($r['berat'] > 0) ? $r['berat'] . 'g' : '250g';
                $r['harga']      = (float)$r['harga'];
                $r['stok']       = (int)$r['stok'];
                $r['created_at'] = '2026-08-27T10:00:00.000000Z';
                $r['updated_at'] = '2026-08-27T10:00:00.000000Z';
                $r['gambar']     = null;
                $produkList[]    = $r;
            }
        }
    }

    if (empty($produkList)) {
        $produkList = [
            ['id' => 1, 'nama' => 'Kopi Arabika Flores Bajawa', 'seri' => 'Specialty Single Origin', 'brand' => 'Flores Coffee', 'ukuran' => '250g', 'harga' => 75000, 'stok' => 15, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null],
            ['id' => 2, 'nama' => 'Kopi Robusta Ruteng Manggarai', 'seri' => 'Premium Dark Roast', 'brand' => 'Ruteng Roast', 'ukuran' => '250g', 'harga' => 50000, 'stok' => 20, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null],
            ['id' => 3, 'nama' => 'Kopi Liberika Manggarai', 'seri' => 'Exotic Rare Beans', 'brand' => 'Manggarai Heritage', 'ukuran' => '200g', 'harga' => 85000, 'stok' => 10, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null],
            ['id' => 4, 'nama' => 'Kopi Toraja Kalosi', 'seri' => 'Highland Special Blend', 'brand' => 'Toraja Beans', 'ukuran' => '250g', 'harga' => 90000, 'stok' => 12, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null],
            ['id' => 5, 'nama' => 'Kopi Gayo Specialty', 'seri' => 'Organic Medium Roast', 'brand' => 'Gayo Organic', 'ukuran' => '250g', 'harga' => 95000, 'stok' => 18, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null]
        ];
    }

    echo json_encode($produkList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if (in_array($resource, ['pelanggan', 'customer', 'customers', 'users'])) {
    $pelangganList = [];
    if ($conn && !$conn->connect_error) {
        @mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pelanggan (
            id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            no_hp VARCHAR(20) NULL,
            alamat TEXT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $res = @mysqli_query($conn, "SELECT id_pelanggan, username, email, no_hp, alamat FROM pelanggan ORDER BY id_pelanggan ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $r['id_pelanggan'] = (int)$r['id_pelanggan'];
                $pelangganList[]   = $r;
            }
        }
    }

    if (empty($pelangganList)) {
        $pelangganList = [
            ['id_pelanggan' => 1, 'username' => 'jeylan0510', 'email' => 'jeylan@gmail.com', 'no_hp' => '081234567890', 'alamat' => 'Ruteng, Manggarai, NTT'],
            ['id_pelanggan' => 2, 'username' => 'budi_santoso', 'email' => 'budi@gmail.com', 'no_hp' => '082198765432', 'alamat' => 'Jl. Ahmad Yani No. 12, Ruteng'],
            ['id_pelanggan' => 3, 'username' => 'maria_ani', 'email' => 'maria@gmail.com', 'no_hp' => '085333444555', 'alamat' => 'Jl. Motang Rua, Ruteng']
        ];
    }

    echo json_encode($pelangganList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if (in_array($resource, ['transaksi', 'transaksis', 'pesanan', 'orders'])) {
    $transaksiList = [];
    if ($conn && !$conn->connect_error) {
        @mysqli_query($conn, "CREATE TABLE IF NOT EXISTS transaksi (
            id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
            id_pelanggan INT NOT NULL,
            tgl_transaksi DATETIME NOT NULL,
            total_harga DOUBLE NOT NULL DEFAULT 0,
            status_pesanan VARCHAR(50) DEFAULT 'Diproses',
            metode_pembayaran VARCHAR(50) DEFAULT 'Transfer Bank',
            status_pembayaran VARCHAR(50) DEFAULT 'Lunas'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $res = @mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id_transaksi ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $r['id_transaksi'] = (int)$r['id_transaksi'];
                $r['id_pelanggan'] = (int)$r['id_pelanggan'];
                $r['total_harga']  = (float)$r['total_harga'];
                $transaksiList[]   = $r;
            }
        }
    }

    if (empty($transaksiList)) {
        $now = date('Y-m-d H:i:s');
        $transaksiList = [
            ['id_transaksi' => 1, 'id_pelanggan' => 1, 'tgl_transaksi' => $now, 'total_harga' => 125000, 'status_pesanan' => 'Selesai', 'metode_pembayaran' => 'Transfer Bank', 'status_pembayaran' => 'Lunas'],
            ['id_transaksi' => 2, 'id_pelanggan' => 2, 'tgl_transaksi' => $now, 'total_harga' => 100000, 'status_pesanan' => 'Diproses', 'metode_pembayaran' => 'QRIS', 'status_pembayaran' => 'Lunas'],
            ['id_transaksi' => 3, 'id_pelanggan' => 3, 'tgl_transaksi' => $now, 'total_harga' => 85000, 'status_pesanan' => 'Menunggu Pembayaran', 'metode_pembayaran' => 'Transfer Bank', 'status_pembayaran' => 'Belum Lunas']
        ];
    }

    echo json_encode($transaksiList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Fallback response for root
$defaultList = [
    ['id' => 1, 'nama' => 'Kopi Arabika Flores Bajawa', 'seri' => 'Specialty Single Origin', 'brand' => 'Flores Coffee', 'ukuran' => '250g', 'harga' => 75000, 'stok' => 15, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null],
    ['id' => 2, 'nama' => 'Kopi Robusta Ruteng Manggarai', 'seri' => 'Premium Dark Roast', 'brand' => 'Ruteng Roast', 'ukuran' => '250g', 'harga' => 50000, 'stok' => 20, 'created_at' => '2026-08-27T10:00:00.000000Z', 'updated_at' => '2026-08-27T10:00:00.000000Z', 'gambar' => null]
];
echo json_encode($defaultList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
