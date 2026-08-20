<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\BookingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/schedules', [HomeController::class, 'schedules'])->name('schedules.search');

// Breeze Auth Routes
require __DIR__.'/auth.php';

// Auth Routes (General)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Redirect based on role when accessing /dashboard directly (default Breeze redirect)
    Route::get('/dashboard', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');
});

// User Routes (Protected)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', function() {
        return redirect()->route('user.dashboard');
    });
    
    // Booking flow
    Route::get('/booking/{schedule}', [UserBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{schedule}', [UserBookingController::class, 'store'])->name('booking.store');
    Route::get('/ticket/{booking}', [UserBookingController::class, 'ticket'])->name('ticket.show');
    
    // Payments
    Route::post('/payment/{booking}', [UserBookingController::class, 'uploadPayment'])->name('payment.upload');
});

// Admin Routes (Protected)
Route::middleware(['auth', App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('buses', BusController::class);
    Route::resource('routes', RouteController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('bookings', BookingController::class);
    
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});
