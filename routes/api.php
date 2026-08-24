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
