<?php

// Direct API Handler Bypass for Railway Cloud Deployment
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

if (str_contains($uri, '/api') || $uri === '/' || $uri === '' || $uri === '/index.php') {
    $parts = explode('/', trim($uri, '/'));
    $endpoint = strtolower(end($parts));
    if (!empty($endpoint) && $endpoint !== 'index.php' && $endpoint !== 'api') {
        if (!isset($_GET['resource'])) {
            $_GET['resource'] = $endpoint;
        }
    }
    if (file_exists(__DIR__ . '/api/index.php')) {
        require_once __DIR__ . '/api/index.php';
        exit;
    }
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
