<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemConfigurationController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\CustomisedProgramController;
use App\Http\Controllers\ProgramPrinterController;
use App\Http\Controllers\SalesConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
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
    Route::get('/customised-program', [CustomisedProgramController::class, 'index'])->name('customised-program.index');
    Route::post('/customised-program', [CustomisedProgramController::class, 'store'])->name('customised-program.store');
});

// Amend Password Routes
Route::get('/users/amend-password', [UserController::class, 'showAmendForm'])->name('users.amend-password');
Route::post('/users/amend-password', [UserController::class, 'updatePassword'])->name('users.update-password');

Route::prefix('system-configuration')->group(function () {
    Route::get('/', [SystemConfigurationController::class, 'index'])->name('system-configuration.index');
    Route::put('/', [SystemConfigurationController::class, 'update'])->name('system-configuration.update');
});
Route::get('/define-printer', [PrinterController::class, 'index'])->name('define-printer');
Route::get('/program-printer', [ProgramPrinterController::class, 'index'])->name('program-printer.index');