<?php
/**
 * RESTful API Router & Entry Point
 * Aplikasi Kopi
 */

// 1. Set CORS and Header JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 2. Include Database Connection & Response Helper
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/helpers/response.php';

// Check DB Connection
if (!$conn || $conn->connect_error) {
    sendResponse(false, 500, "Koneksi database gagal: " . ($conn->connect_error ?? mysqli_connect_error()));
}

// 3. Include Controllers
require_once __DIR__ . '/controllers/ProdukController.php';
require_once __DIR__ . '/controllers/PelangganController.php';
require_once __DIR__ . '/controllers/TransaksiController.php';

// 4. Parse HTTP Method, Resource, and ID
$method = $_SERVER['REQUEST_METHOD'];

// Accept resource from query param 'resource' or 'endpoint'
$resource = isset($_GET['resource']) ? strtolower(trim($_GET['resource'])) : '';
if (empty($resource) && isset($_GET['endpoint'])) {
    $resource = strtolower(trim($_GET['endpoint']));
}

// Extract ID parameter
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Routing logic
switch ($resource) {
    case 'produk':
    case 'product':
    case 'products':
        $controller = new ProdukController($conn);
        $controller->handleRequest($method, $id);
        break;

    case 'pelanggan':
    case 'customer':
    case 'customers':
        $controller = new PelangganController($conn);
        $controller->handleRequest($method, $id);
        break;

    case 'transaksi':
    case 'pesanan':
    case 'orders':
        $controller = new TransaksiController($conn);
        $controller->handleRequest($method, $id);
        break;

    case '':
        // Default API Info
        sendResponse(true, 200, "Selamat datang di Kopi RESTful API System.", [
            'api_name'    => 'Kopi Store REST API',
            'version'     => '1.0.0',
            'status'      => 'Online',
            'endpoints'   => [
                'produk'    => [
                    'GET /api/index.php?resource=produk'           => 'Mengambil daftar semua produk kopi',
                    'GET /api/index.php?resource=produk&id={id}'   => 'Mengambil detail 1 produk kopi',
                    'POST /api/index.php?resource=produk'          => 'Menambah produk kopi baru',
                    'PUT /api/index.php?resource=produk&id={id}'    => 'Memperbarui data produk kopi',
                    'DELETE /api/index.php?resource=produk&id={id}' => 'Menghapus produk kopi'
                ],
                'pelanggan' => [
                    'GET /api/index.php?resource=pelanggan'          => 'Mengambil daftar pelanggan',
                    'GET /api/index.php?resource=pelanggan&id={id}'  => 'Mengambil detail pelanggan',
                    'POST /api/index.php?resource=pelanggan'         => 'Mendaftarkan pelanggan baru',
                    'PUT /api/index.php?resource=pelanggan&id={id}'   => 'Memperbarui data pelanggan',
                    'DELETE /api/index.php?resource=pelanggan&id={id}'=> 'Menghapus pelanggan'
                ],
                'transaksi' => [
                    'GET /api/index.php?resource=transaksi'          => 'Mengambil daftar pesanan/transaksi',
                    'GET /api/index.php?resource=transaksi&id={id}'  => 'Mengambil detail pesanan/transaksi',
                    'POST /api/index.php?resource=transaksi'         => 'Membuat pesanan/transaksi baru',
                    'PUT /api/index.php?resource=transaksi&id={id}'   => 'Memperbarui status pesanan',
                    'DELETE /api/index.php?resource=transaksi&id={id}'=> 'Menghapus pesanan'
                ]
            ]
        ]);
        break;

    default:
        sendResponse(false, 404, "Endpoint resource '$resource' tidak ditemukan. Gunakan: produk, pelanggan, atau transaksi.");
        break;
}
