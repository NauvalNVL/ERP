<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesConfigurationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesTeamController;
use App\Http\Controllers\SalespersonController;
use App\Http\Controllers\IndustryController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'Connected']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/sales-configuration', [SalesConfigurationController::class, 'index'])->name('sales-configuration.index');
    Route::post('/sales-configuration', [SalesConfigurationController::class, 'store'])->name('sales-configuration.store');
    Route::put('/sales-configuration', [SalesConfigurationController::class, 'update'])->name('sales-configuration.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});

// Amend Password Routes
Route::get('/system-security/amend-password', [UserController::class, 'showAmendForm'])->name('users.amend-password');
Route::post('/system-security/amend-password', [UserController::class, 'updatePassword'])->name('users.update-password');

Route::get('/sales-team', [SalesTeamController::class, 'index'])->name('sales-team.index');
Route::post('/sales-team', [SalesTeamController::class, 'store'])->name('sales-team.store');
Route::get('/sales-team/search', [SalesTeamController::class, 'search'])->name('sales-team.search');
Route::get('/sales-team/{id}/edit', [SalesTeamController::class, 'edit'])->name('sales-team.edit');
Route::put('/sales-team/{id}', [SalesTeamController::class, 'update'])->name('sales-team.update');
Route::delete('/sales-team/{id}', [SalesTeamController::class, 'destroy'])->name('sales-team.destroy');

// Salesperson Routes
Route::get('/salesperson', [SalespersonController::class, 'index'])->name('salesperson.index');
Route::get('/salesperson/create', [SalespersonController::class, 'create'])->name('salesperson.create');
Route::post('/salesperson', [SalespersonController::class, 'store'])->name('salesperson.store');
Route::get('/salesperson/{id}/edit', [SalespersonController::class, 'edit'])->name('salesperson.edit');
Route::put('/salesperson/{id}', [SalespersonController::class, 'update'])->name('salesperson.update');
Route::delete('/salesperson/{id}', [SalespersonController::class, 'destroy'])->name('salesperson.destroy');
Route::get('/salesperson/search', [SalespersonController::class, 'search'])->name('salesperson.search');

// Industry Routes
Route::get('/industry', [IndustryController::class, 'index'])->name('industry.index');
Route::post('/industry', [IndustryController::class, 'store'])->name('industry.store');
Route::get('/industry/{id}/edit', [IndustryController::class, 'edit'])->name('industry.edit');
Route::put('/industry/{id}', [IndustryController::class, 'update'])->name('industry.update');
Route::delete('/industry/{id}', [IndustryController::class, 'destroy'])->name('industry.destroy');
