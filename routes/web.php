<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\KytReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Jika user sudah login, langsung ke Dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Routes untuk Login dan Logout
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login')->with('success', 'Logout berhasil.');
})->name('logout');

// Routes yang hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {

    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // **🔹 Master Data: Users**
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/data', [UserController::class, 'getUsersData'])->name('users.data');
        Route::get('/add', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
    });

    // **🔹 Master Data: Companies**
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/add', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/store', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::post('/update/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/delete/{id}', [CompanyController::class, 'destroy'])->name('companies.delete');
    });

    // **🔹 Master Data: Departments**
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/add', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/delete/{id}', [DepartmentController::class, 'destroy'])->name('departments.delete');
    });

    // **🔹 Master Data: Positions**
    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('positions.index');
        Route::get('/add', [PositionController::class, 'create'])->name('positions.create');
        Route::post('/store', [PositionController::class, 'store'])->name('positions.store');
        Route::get('/edit/{id}', [PositionController::class, 'edit'])->name('positions.edit');
        Route::post('/update/{id}', [PositionController::class, 'update'])->name('positions.update');
        Route::delete('/delete/{id}', [PositionController::class, 'destroy'])->name('positions.delete');
    });

    // **🔹 Master Data: Employees**
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::post('/{id}/update', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        // Route untuk mendapatkan posisi berdasarkan company
        Route::post('/get-positions-by-company', [EmployeeController::class, 'getPositionsByCompany'])->name('employees.getPositions');

        // Route Add User untuk Employee
        Route::get('/{id}/add-user', [EmployeeController::class, 'addUser'])->name('employees.addUser');
    });

    Route::prefix('kyt_reports')->group(function () {
        Route::get('/', [KytReportController::class, 'index'])->name('kyt_reports.index');
        Route::get('/create', [KytReportController::class, 'create'])->name('kyt_reports.create');
        Route::post('/store', [KytReportController::class, 'store'])->name('kyt_reports.store');
        Route::get('/{kytReport}/edit', [KytReportController::class, 'edit'])->name('kyt_reports.edit');
        Route::put('/{kytReport}/update', [KytReportController::class, 'update'])->name('kyt_reports.update');
        Route::delete('/{kytReport}/delete', [KytReportController::class, 'destroy'])->name('kyt_reports.destroy');
        Route::get('/get-employees/{company_id}', [KytReportController::class, 'getEmployeesByCompany'])->name('kyt_reports.getEmployees');
    });

});
