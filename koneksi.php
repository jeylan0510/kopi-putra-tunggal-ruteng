<?php
// Disable uncaught mysqli exceptions in PHP 8+
@mysqli_report(MYSQLI_REPORT_OFF);

// Smart Database Connection for Local XAMPP & Railway Cloud
$hostname = getenv('MYSQLHOST') ?: getenv('RAILWAY_TCP_PROXY_DOMAIN') ?: 'localhost';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_ROOT_PASSWORD') ?: 'AEHAKoDgNSjuBuZaVwlQxMLfFSRfRALV';
$dbname   = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'railway';
$port     = (int)(getenv('MYSQLPORT') ?: getenv('RAILWAY_TCP_PROXY_PORT') ?: 3306);

// Attempt Railway connection
$conn = @new mysqli($hostname, $username, $password, $dbname, $port);

// Fallback to localhost if Railway connection failed
if (!$conn || $conn->connect_error) {
    $conn = @new mysqli('localhost', 'root', '', 'kopi');
}
?>
