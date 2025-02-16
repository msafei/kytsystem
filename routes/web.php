<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL ke halaman login jika belum login
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // **Master Data: Users**
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index'); // Halaman daftar users dengan DataTables
        Route::get('/data', [UserController::class, 'getUsersData'])->name('users.data'); // API DataTables
        Route::get('/add', [UserController::class, 'create'])->name('users.create'); // Halaman Tambah User
        Route::post('/store', [UserController::class, 'store'])->name('users.store'); // Simpan User
    });

    // // **Master Data: Employee**
    // Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

    // // **Master Data: Company**
    // Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

    // // **Master Data: Department**
    // Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');

    // // **Profil**
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});
