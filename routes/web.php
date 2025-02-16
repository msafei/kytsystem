<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Routes untuk Login dan Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes yang hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {
    
    // Route Dashboard
    Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen User (Tambah User)
    Route::get('/add-user', [UserController::class, 'create'])->name('add-user');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
});
