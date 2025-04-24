<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesConfigurationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesTeamController;
use App\Http\Controllers\SalespersonController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\SalespersonTeamController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaperSizeController;
use App\Http\Controllers\PaperFluteController;
use App\Http\Controllers\ScoringToolController;
use App\Http\Controllers\FinishingController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ColorGroupController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProductDesignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaperQualityController;
use Inertia\Inertia;

Route::get('/test-vue', function () {
    return Inertia::render('Dashboard');
});

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
    // prefix /vue
    Route::prefix('vue')->group(function () {
         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
         Route::get('/system-requirement/color', [ColorController::class, 'vueIndex'])->name('vue.color.index');
    });
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

    // Salesperson Team Routes
    Route::get('/salesperson-team', [SalespersonTeamController::class, 'index'])->name('salesperson-team.index');
    Route::post('/salesperson-team', [SalespersonTeamController::class, 'store'])->name('salesperson-team.store');
    Route::get('/salesperson-team/{id}/edit', [SalespersonTeamController::class, 'edit'])->name('salesperson-team.edit');
    Route::put('/salesperson-team/{id}', [SalespersonTeamController::class, 'update'])->name('salesperson-team.update');
    Route::delete('/salesperson-team/{id}', [SalespersonTeamController::class, 'destroy'])->name('salesperson-team.destroy');

    Route::resource('product-group', ProductGroupController::class);
    // Route baru untuk View & Print Product Group
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/product-group/view-print', [ProductGroupController::class, 'viewAndPrint'])
         ->name('product-group.view-print');
    
    Route::resource('product', ProductController::class);
    // Route baru untuk View & Print Product
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/product/view-print', [ProductController::class, 'viewAndPrint'])
         ->name('product.view-print');

    // Product Design Routes
    Route::get('/product-design', [ProductDesignController::class, 'index'])->name('product-design.index');
    Route::get('/product-design/create', [ProductDesignController::class, 'create'])->name('product-design.create');
    Route::post('/product-design', [ProductDesignController::class, 'store'])->name('product-design.store');
    Route::get('/product-design/{id}/edit', [ProductDesignController::class, 'edit'])->name('product-design.edit');
    Route::put('/product-design/{id}', [ProductDesignController::class, 'update'])->name('product-design.update');
    Route::delete('/product-design/{id}', [ProductDesignController::class, 'destroy'])->name('product-design.destroy');
    // Route baru untuk View & Print Product Design
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/product-design/view-print', [ProductDesignController::class, 'viewAndPrint'])
         ->name('product-design.view-print');

    // Tambahkan route berikut di dalam middleware auth
    Route::resource('paper-size', PaperSizeController::class);
    // Route baru untuk View & Print Paper Size
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/paper-size/view-print', [PaperSizeController::class, 'viewAndPrint'])
         ->name('paper-size.view-print');

    // Scoring Tool Routes
    Route::get('/scoring-tool', [ScoringToolController::class, 'index'])->name('scoring-tool.index');
    Route::post('/scoring-tool', [ScoringToolController::class, 'store'])->name('scoring-tool.store');
    Route::get('/scoring-tool/{id}/edit', [ScoringToolController::class, 'edit'])->name('scoring-tool.edit');
    Route::put('/scoring-tool/{id}', [ScoringToolController::class, 'update'])->name('scoring-tool.update');
    Route::delete('/scoring-tool/{id}', [ScoringToolController::class, 'destroy'])->name('scoring-tool.destroy');
    Route::get('/scoring-tool/{id}', [ScoringToolController::class, 'show'])->name('scoring-tool.show');
    // Route baru untuk View & Print Scoring Tool
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/scoring-tool/view-print', [ScoringToolController::class, 'viewAndPrint'])
         ->name('scoring-tool.view-print');

    // Color Routes - Dipindahkan ke dalam middleware auth
    Route::get('/color', [ColorController::class, 'index'])->name('color.index');
    Route::post('/color', [ColorController::class, 'store'])->name('color.store');
    Route::get('/color/create', [ColorController::class, 'create'])->name('color.create');
    Route::get('/color/{color}/edit', [ColorController::class, 'edit'])->name('color.edit');
    Route::put('/color/{color}', [ColorController::class, 'update'])->name('color.update');
    Route::delete('/color/{color}', [ColorController::class, 'destroy'])->name('color.destroy');
    // Route baru untuk View & Print Color
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/color/view-print', [ColorController::class, 'viewAndPrint'])
         ->name('color.view-print');
    
    // Finishing Routes
    Route::resource('finishing', FinishingController::class);
    // Route baru untuk View & Print Finishing
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/finishing/view-print', [FinishingController::class, 'viewAndPrint'])
         ->name('finishing.view-print');

    // Route baru untuk View & Print Color Group
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/color-group/view-print', [ColorGroupController::class, 'viewAndPrint'])
         ->name('color-group.view-print');
         
    // Paper Quality Routes
    Route::get('/paper-quality', [PaperQualityController::class, 'index'])->name('paper-quality.index');
    Route::get('/paper-quality/create', [PaperQualityController::class, 'create'])->name('paper-quality.create');
    Route::post('/paper-quality', [PaperQualityController::class, 'store'])->name('paper-quality.store');
    Route::get('/paper-quality/{id}/edit', [PaperQualityController::class, 'edit'])->name('paper-quality.edit');
    Route::put('/paper-quality/{id}', [PaperQualityController::class, 'update'])->name('paper-quality.update');
    Route::delete('/paper-quality/{id}', [PaperQualityController::class, 'destroy'])->name('paper-quality.destroy');
    Route::patch('/paper-quality/{id}/toggle-status', [PaperQualityController::class, 'toggleStatus'])->name('paper-quality.toggle-status');
    // Route baru untuk View & Print Paper Quality
    Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/paper-quality/view-print', [PaperQualityController::class, 'viewAndPrint'])
         ->name('paper-quality.view-print');
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
// Route baru untuk View & Print Sales Team
Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/sales-team/view-print', [SalesTeamController::class, 'viewAndPrint'])
     ->middleware('auth') // Pastikan di dalam auth middleware
     ->name('sales-team.view-print');

// Salesperson Routes
Route::get('/salesperson', [SalespersonController::class, 'index'])->name('salesperson.index');
Route::get('/salesperson/create', [SalespersonController::class, 'create'])->name('salesperson.create');
Route::post('/salesperson', [SalespersonController::class, 'store'])->name('salesperson.store');
Route::get('/salesperson/{id}/edit', [SalespersonController::class, 'edit'])->name('salesperson.edit');
Route::put('/salesperson/{id}', [SalespersonController::class, 'update'])->name('salesperson.update');
Route::delete('/salesperson/{id}', [SalespersonController::class, 'destroy'])->name('salesperson.destroy');
Route::get('/salesperson/search', [SalespersonController::class, 'search'])->name('salesperson.search');
// Route baru untuk View & Print Salesperson
Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/salesperson/view-print', [SalespersonController::class, 'viewAndPrint'])
     ->middleware('auth') // Pastikan di dalam auth middleware
     ->name('salesperson.view-print');

// Industry Routes
Route::get('/industry', [IndustryController::class, 'index'])->name('industry.index');
Route::post('/industry', [IndustryController::class, 'store'])->name('industry.store');
Route::get('/industry/{id}/edit', [IndustryController::class, 'edit'])->name('industry.edit');
Route::put('/industry/{id}', [IndustryController::class, 'update'])->name('industry.update');
Route::delete('/industry/{id}', [IndustryController::class, 'destroy'])->name('industry.destroy');

// Route baru untuk View & Print Industry
Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/industry/view-print', [IndustryController::class, 'viewAndPrint'])
     ->middleware('auth') // Pastikan di dalam auth middleware
     ->name('industry.view-print');

Route::resource('geo', GeoController::class);
// Route baru untuk View & Print Geo
Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/geo/view-print', [GeoController::class, 'viewAndPrint'])
     ->middleware('auth') // Pastikan route ini di dalam auth middleware
     ->name('geo.view-print');

Route::resource('paper-flute', PaperFluteController::class)->middleware('auth');
// Route baru untuk View & Print Paper Flute
Route::get('/sales-management/system-requirement/system-requirement/standard-requirement/paper-flute/view-print', [PaperFluteController::class, 'viewAndPrint'])
     ->middleware('auth') // Pastikan route ini di dalam auth middleware
     ->name('paper-flute.view-print');

// Color Routes
Route::resource('color', ColorController::class);

Route::resource('color-group', ColorGroupController::class);

// Add a route to run the color seeder
Route::get('/run-color-seeder', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'ColorSeeder']);
        return redirect()->back()->with('success', 'Color seeder berhasil dijalankan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
})->name('run.color.seeder');

// Add a route to run the salesperson seeder
Route::get('/run-salesperson-seeder', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'SalespersonSeeder']);
        return redirect()->back()->with('success', 'Salesperson seeder berhasil dijalankan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
})->name('run.salesperson.seeder');

// Add a POST route for AJAX requests to run the salesperson seeder
Route::post('/run-salesperson-seeder', function () {
    try {
        $output = Artisan::call('db:seed', ['--class' => 'SalespersonSeeder']);
        $count = DB::table('salesperson')->count();
        return response()->json([
            'success' => true,
            'message' => 'Salesperson seeder berhasil dijalankan',
            'count' => $count,
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// Add a POST route for AJAX requests to run the paper quality seeder
Route::post('/run-paper-quality-seeder', function () {
    try {
        $output = Artisan::call('db:seed', ['--class' => 'PaperQualitySeeder']);
        $count = DB::table('paper_qualities')->count();
        return response()->json([
            'success' => true,
            'message' => 'Paper Quality seeder berhasil dijalankan',
            'count' => $count,
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

