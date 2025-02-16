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
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'Logout berhasil.');
})->name('logout');


// Routes yang hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {

    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    // Master Data: Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index'); // Halaman daftar users dengan DataTables
        Route::get('/data', [UserController::class, 'getUsersData'])->name('users.data'); // API DataTables
        Route::get('/add', [UserController::class, 'create'])->name('users.create'); // Halaman Tambah User
        Route::post('/store', [UserController::class, 'store'])->name('users.store'); // Simpan User
    });

       // Master Data: Employees
       Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
       Route::get('/employees/add', [EmployeeController::class, 'create'])->name('employees.create');
       Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
       Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
       Route::post('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
       Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.delete');
   

});
