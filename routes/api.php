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

// Kopi API Routes for Railway Deployment
Route::any('/index.php', function () {
    require base_path('api/index.php');
    exit;
});

Route::any('/{endpoint}', function ($endpoint) {
    if (!isset($_GET['resource'])) {
        $_GET['resource'] = $endpoint;
    }
    require base_path('api/index.php');
    exit;
});
