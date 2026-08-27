<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\RouteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// CRUD API Routes untuk entitas Bus dan Route
Route::apiResource('buses', BusController::class);
Route::apiResource('routes', RouteController::class);

// Kopi Explicit API Routes
Route::any('/produk', function () {
    $_GET['resource'] = 'produk';
    require base_path('api/index.php');
    exit;
});
Route::any('/pelanggan', function () {
    $_GET['resource'] = 'pelanggan';
    require base_path('api/index.php');
    exit;
});
Route::any('/transaksi', function () {
    $_GET['resource'] = 'transaksi';
    require base_path('api/index.php');
    exit;
});
Route::any('/gitars', function () {
    $_GET['resource'] = 'produk';
    require base_path('api/index.php');
    exit;
});
Route::any('/transaksis', function () {
    $_GET['resource'] = 'transaksi';
    require base_path('api/index.php');
    exit;
});
Route::any('/index.php', function () {
    require base_path('api/index.php');
    exit;
});
