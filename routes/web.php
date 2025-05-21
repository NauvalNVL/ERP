<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesConfigurationController;
use App\Http\Controllers\SalesTeamController;
use App\Http\Controllers\SalespersonController;
use App\Http\Controllers\SalespersonTeamController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDesignController;
use App\Http\Controllers\PaperSizeController;
use App\Http\Controllers\PaperFluteController;
use App\Http\Controllers\PaperQualityController;
use App\Http\Controllers\ScoringToolController;
use App\Http\Controllers\FinishingController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ColorGroupController;
use App\Http\Controllers\ForeignCurrencyController;
use App\Http\Controllers\BusinessFormController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\SalesManagement\SystemRequirement\SystemRequirementController;
use App\Http\Controllers\UpdateCustomerAccountController;

// Test Routes
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

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Vue Routes
    Route::prefix('vue')->group(function () {
         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
         Route::get('/color', [ColorController::class, 'vueIndex'])->name('vue.color.index');
         Route::get('/sales-configuration', [SalesConfigurationController::class, 'vueIndex'])->name('vue.sales-configuration');
         Route::get('/color-group', [ColorGroupController::class, 'vueIndex'])->name('vue.color-group.index');
         Route::get('/finishing', [FinishingController::class, 'vueIndex'])->name('vue.finishing.index');
         Route::get('/geo', [GeoController::class, 'vueIndex'])->name('vue.geo.index');
         Route::get('/industry', [IndustryController::class, 'vueIndex'])->name('vue.industry.index');
         Route::get('/paper-quality', [PaperQualityController::class, 'vueIndex'])->name('vue.paper-quality.index');
         Route::get('/paper-quality/status', [PaperQualityController::class, 'vueManageStatus'])->name('vue.paper-quality.status');
         Route::get('/paper-size', [PaperSizeController::class, 'vueIndex'])->name('vue.paper-size.index');
         Route::get('/product', [ProductController::class, 'vueIndex'])->name('vue.product.index');
         Route::get('/product-design', [ProductDesignController::class, 'vueIndex'])->name('vue.product-design.index');
         Route::get('/product-group', [ProductGroupController::class, 'vueIndex'])->name('vue.product-group.index');
         Route::get('/sales-team', [SalesTeamController::class, 'vueIndex'])->name('vue.sales-team.index');
         Route::get('/sales-person', [SalespersonController::class, 'vueIndex'])->name('vue.sales-person.index');
         Route::get('/sales-person-team', [SalespersonTeamController::class, 'vueIndex'])->name('vue.sales-person-team.index');
         Route::get('/scoring-tool', [ScoringToolController::class, 'vueIndex'])->name('vue.scoring-tool.index');

         // View and print routes
         Route::get('/salesperson-team/view-print', [SystemRequirementController::class, 'vueViewPrintSalespersonTeam'])->name('vue.salesperson-team.view-print');
         Route::get('/color/view-print', [ColorController::class, 'vueViewAndPrint'])->name('vue.color.view-print');
         Route::get('/color-group/view-print', [ColorGroupController::class, 'vueViewAndPrint'])->name('vue.color-group.view-print');
         Route::get('/finishing/view-print', [FinishingController::class, 'vueViewAndPrint'])->name('vue.finishing.view-print');
         Route::get('/geo/view-print', [GeoController::class, 'vueViewAndPrint'])->name('vue.geo.view-print');
         Route::get('/industry/view-print', [IndustryController::class, 'vueViewAndPrint'])->name('vue.industry.view-print');
         Route::get('/paper-flute/view-print', [PaperFluteController::class, 'vueViewAndPrint'])->name('vue.paper-flute.view-print');
         Route::get('/paper-quality/view-print', [PaperQualityController::class, 'vueViewAndPrint'])->name('vue.paper-quality.view-print');
         Route::get('/paper-size/view-print', [PaperSizeController::class, 'vueViewAndPrint'])->name('vue.paper-size.view-print');
         Route::get('/product/view-print', [ProductController::class, 'vueViewAndPrint'])->name('vue.product.view-print');
         Route::get('/product-design/view-print', [ProductDesignController::class, 'vueViewAndPrint'])->name('vue.product-design.view-print');
         Route::get('/product-group/view-print', [ProductGroupController::class, 'vueViewAndPrint'])->name('vue.product-group.view-print');
         Route::get('/salesperson/view-print', [SalespersonController::class, 'vueViewAndPrint'])->name('vue.salesperson.view-print');
         Route::get('/sales-team/view-print', [SalesTeamController::class, 'vueViewAndPrint'])->name('vue.sales-team.view-print');
         Route::get('/scoring-tool/view-print', [ScoringToolController::class, 'vueViewAndPrint'])->name('vue.scoring-tool.view-print');
    });

    // Auth Routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Sales Configuration
    Route::get('/sales-configuration', [SalesConfigurationController::class, 'index'])->name('sales-configuration.index');
    Route::post('/sales-configuration', [SalesConfigurationController::class, 'store'])->name('sales-configuration.store');
    Route::put('/sales-configuration', [SalesConfigurationController::class, 'update'])->name('sales-configuration.update');
    Route::get('/sales-configuration/vue', [SalesConfigurationController::class, 'vueIndex'])->name('sales-configuration.vue');

    // Salesperson Team
    Route::prefix('salesperson-team')->group(function () {
        Route::get('/', [SalespersonTeamController::class, 'index'])->name('salesperson-team.index');
        Route::post('/', [SalespersonTeamController::class, 'store'])->name('salesperson-team.store');
        Route::get('/{id}/edit', [SalespersonTeamController::class, 'edit'])->name('salesperson-team.edit');
        Route::put('/{id}', [SalespersonTeamController::class, 'update'])->name('salesperson-team.update');
        Route::delete('/{id}', [SalespersonTeamController::class, 'destroy'])->name('salesperson-team.destroy');
        Route::get('/view-print', [SalespersonTeamController::class, 'viewAndPrint'])->name('salesperson-team.view-print');
    });

    // Product Management
    Route::prefix('product-group')->group(function () {
        Route::get('/', [ProductGroupController::class, 'index'])->name('product-group.index');
        Route::post('/', [ProductGroupController::class, 'store'])->name('product-group.store');
        Route::get('/{id}/edit', [ProductGroupController::class, 'edit'])->name('product-group.edit');
        Route::put('/{id}', [ProductGroupController::class, 'update'])->name('product-group.update');
        Route::delete('/{id}', [ProductGroupController::class, 'destroy'])->name('product-group.destroy');
        Route::get('/view-print', [ProductGroupController::class, 'viewAndPrint'])->name('product-group.view-print');
    });

    // Product Routes
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
        Route::get('/view-print', [ProductController::class, 'viewAndPrint'])->name('product.view-print');
        Route::get('/categories', [ProductController::class, 'getCategoriesJson'])->name('product.categories');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/{id}', [ProductController::class, 'show'])->name('product.show');
    });

    // Product Design
    Route::prefix('product-design')->group(function () {
        Route::get('/', [ProductDesignController::class, 'index'])->name('product-design.index');
        Route::get('/create', [ProductDesignController::class, 'create'])->name('product-design.create');
        Route::post('/', [ProductDesignController::class, 'store'])->name('product-design.store');
        Route::get('/{id}/edit', [ProductDesignController::class, 'edit'])->name('product-design.edit');
        Route::put('/{id}', [ProductDesignController::class, 'update'])->name('product-design.update');
        Route::delete('/{id}', [ProductDesignController::class, 'destroy'])->name('product-design.destroy');
        Route::get('/view-print', [ProductDesignController::class, 'viewAndPrint'])->name('product-design.view-print');
    });

    // Paper Management
    Route::get('/paper-size/view-print', [PaperSizeController::class, 'viewAndPrint'])->name('paper-size.view-print');
    Route::resource('paper-size', PaperSizeController::class);

    Route::get('/paper-flute/view-print', [PaperFluteController::class, 'viewAndPrint'])->name('paper-flute.view-print');
    Route::resource('paper-flute', PaperFluteController::class);

    // Paper Quality
    Route::prefix('paper-quality')->group(function () {
        Route::get('/', [PaperQualityController::class, 'index'])->name('paper-quality.index');
        Route::get('/create', [PaperQualityController::class, 'create'])->name('paper-quality.create');
        Route::post('/', [PaperQualityController::class, 'store'])->name('paper-quality.store');
        Route::get('/{id}/edit', [PaperQualityController::class, 'edit'])->name('paper-quality.edit');
        Route::put('/{id}', [PaperQualityController::class, 'update'])->name('paper-quality.update');
        Route::delete('/{id}', [PaperQualityController::class, 'destroy'])->name('paper-quality.destroy');
        Route::patch('/{id}/toggle-status', [PaperQualityController::class, 'toggleStatus'])->name('paper-quality.toggle-status');
        Route::get('/view-print', [PaperQualityController::class, 'viewAndPrint'])->name('paper-quality.view-print');
        Route::get('/manage-status', [PaperQualityController::class, 'manageStatus'])->name('paper-quality.manage-status');
    });

    // Scoring Tool
    Route::prefix('scoring-tool')->group(function () {
        Route::get('/', [ScoringToolController::class, 'index'])->name('scoring-tool.index');
        Route::get('/json', [ScoringToolController::class, 'index'])->name('scoring-tool.json');
        Route::post('/', [ScoringToolController::class, 'store'])->name('scoring-tool.store');
        Route::get('/{id}/edit', [ScoringToolController::class, 'edit'])->name('scoring-tool.edit');
        Route::put('/{id}', [ScoringToolController::class, 'update'])->name('scoring-tool.update');
        Route::delete('/{id}', [ScoringToolController::class, 'destroy'])->name('scoring-tool.destroy');
        Route::get('/view-print', [ScoringToolController::class, 'viewAndPrint'])->name('scoring-tool.view-print');
        Route::get('/{id}', [ScoringToolController::class, 'show'])->name('scoring-tool.show');
    });

    // Color Management
    Route::resource('color', ColorController::class)->except(['show']);
    Route::get('/color/view-print', [ColorController::class, 'viewAndPrint'])->name('color.view-print');

    // Definisikan route spesifik sebelum resource
    Route::get('/color-group/view-print', [ColorGroupController::class, 'viewAndPrint'])->name('color-group.view-print');
    Route::resource('color-group', ColorGroupController::class);

    // Finishing
    Route::prefix('finishing')->group(function () {
        Route::get('/', [FinishingController::class, 'index'])->name('finishing.index');
        Route::get('/create', [FinishingController::class, 'create'])->name('finishing.create');
        Route::post('/', [FinishingController::class, 'store'])->name('finishing.store');
        Route::get('/{finishing}/edit', [FinishingController::class, 'edit'])->name('finishing.edit');
        Route::put('/{finishing}', [FinishingController::class, 'update'])->name('finishing.update');
        Route::delete('/{finishing}', [FinishingController::class, 'destroy'])->name('finishing.destroy');
        Route::get('/view-print', [FinishingController::class, 'viewAndPrint'])->name('finishing.view-print');
        Route::get('/json/all', [FinishingController::class, 'getFinishingsJson'])->name('finishing.json');
        Route::get('/search/{code}', [FinishingController::class, 'search'])->name('finishing.search');
        Route::get('/{id}', [FinishingController::class, 'show'])->name('finishing.show');
    });

    // System Manager
    Route::prefix('system-manager')->group(function () {
        Route::prefix('system-maintenance')->group(function () {
            // Foreign Currency
            Route::resource('foreign-currency', ForeignCurrencyController::class);
            Route::get('foreign-currency/view-print', [ForeignCurrencyController::class, 'viewAndPrint'])->name('foreign-currency.view-print');

            // Business Form
            Route::get('business-form/view-print', [BusinessFormController::class, 'viewAndPrint'])->name('business-form.view-print');
            Route::get('business-form-search', [BusinessFormController::class, 'search'])->name('business-form.search');
            Route::resource('business-form', BusinessFormController::class);
        });
    });

    // Geo Routes
    Route::prefix('geo')->group(function () {
        Route::get('/view-print', [GeoController::class, 'viewAndPrint'])->name('geo.view-print');
        Route::get('/', [GeoController::class, 'index'])->name('geo.index');
        Route::post('/', [GeoController::class, 'store'])->name('geo.store');
        Route::get('/{code}', [GeoController::class, 'show'])->name('geo.show');
        Route::put('/{code}', [GeoController::class, 'update'])->name('geo.update');
        Route::delete('/{code}', [GeoController::class, 'destroy'])->name('geo.destroy');
    });

    // Sales Team
    Route::prefix('sales-team')->group(function () {
        Route::get('/', [SalesTeamController::class, 'index'])->name('sales-team.index');
        Route::post('/', [SalesTeamController::class, 'store'])->name('sales-team.store');
        Route::get('/search', [SalesTeamController::class, 'search'])->name('sales-team.search');
        Route::get('/{id}/edit', [SalesTeamController::class, 'edit'])->name('sales-team.edit');
        Route::put('/{id}', [SalesTeamController::class, 'update'])->name('sales-team.update');
        Route::delete('/{id}', [SalesTeamController::class, 'destroy'])->name('sales-team.destroy');
        Route::get('/view-print', [SalesTeamController::class, 'viewAndPrint'])->name('sales-team.view-print');
    });

    // Salesperson
    Route::prefix('salesperson')->group(function () {
        Route::get('/', [SalespersonController::class, 'index'])->name('salesperson.index');
        Route::post('/update/{code}', [SalespersonController::class, 'update'])->name('salesperson.update');
        Route::post('/seed', [SalespersonController::class, 'seed'])->name('salesperson.seed');
        Route::get('/search', [SalespersonController::class, 'search'])->name('salesperson.search');
        Route::get('/details/{code}', [SalespersonController::class, 'getDetails'])->name('salesperson.details');
        Route::get('/view-print', [SalespersonController::class, 'viewAndPrint'])->name('salesperson.view-print');
    });

    // Industry
    Route::prefix('industry')->group(function () {
        Route::get('/', [IndustryController::class, 'index'])->name('industry.index');
        Route::post('/', [IndustryController::class, 'store'])->name('industry.store');
        Route::get('/{id}/edit', [IndustryController::class, 'edit'])->name('industry.edit');
        Route::put('/{id}', [IndustryController::class, 'update'])->name('industry.update');
        Route::delete('/{id}', [IndustryController::class, 'destroy'])->name('industry.destroy');
        Route::get('/view-print', [IndustryController::class, 'viewAndPrint'])->name('industry.view-print');
        Route::get('/search/{code}', [IndustryController::class, 'search'])->name('industry.search');
    });

    // Customer Group Routes
    Route::prefix('customer-group')->group(function () {
        Route::get('/', [CustomerGroupController::class, 'index'])->name('customer-group.index');
        Route::post('/', [CustomerGroupController::class, 'store'])->name('customer-group.store');
        Route::put('/{group_code}', [CustomerGroupController::class, 'update'])->name('customer-group.update');
        Route::delete('/{group_code}', [CustomerGroupController::class, 'destroy'])->name('customer-group.destroy');
        Route::get('/view-print', [CustomerGroupController::class, 'viewAndPrint'])->name('customer-group.view-print');
    });

    // New route for view and print salesperson team functionality
    Route::get('/salesperson-team/view-print', [SystemRequirementController::class, 'viewPrintSalespersonTeam'])->name('salesperson-team.view-print');

    // Update Customer Account
    Route::get('/update-customer-account', [UpdateCustomerAccountController::class, 'index'])->name('update-customer-account.index');
});

// Password Management
Route::get('/system-security/amend-password', [UserController::class, 'showAmendForm'])->name('users.amend-password');
Route::post('/system-security/amend-password', [UserController::class, 'updatePassword'])->name('users.update-password');

// Seeder Routes
Route::get('/run-scoringtool-seeder', [ScoringToolController::class, 'getSeederData'])->name('run.scoringtool.seeder');
Route::post('/update-scoringtool-seeder', [ScoringToolController::class, 'updateSeederData'])->name('update.scoringtool.seeder');

Route::get('/run-salespersonteam-seeder', function () {
    Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\SalespersonTeamSeeder',
        '--force' => true
    ]);
    return redirect()->back()->with('success', 'Salesperson Team Seeder berhasil dijalankan!');
})->name('run.salespersonteam.seeder');

// API endpoints para el frontend Vue
Route::get('/api/products', [ProductController::class, 'getProductsJson'])->name('api.products');
Route::post('/api/products', [ProductController::class, 'apiStore'])->name('api.products.store');
Route::put('/api/products/{id}', [ProductController::class, 'apiUpdate'])->name('api.products.update');
Route::delete('/api/products/{id}', [ProductController::class, 'apiDestroy'])->name('api.products.destroy');
Route::get('/api/product-categories', [ProductController::class, 'getCategoriesJson'])->name('api.product.categories');

// Add these routes for the Vue product design component

// API routes for Vue components
Route::get('/api/products', [ProductController::class, 'getProductsJson']);
Route::get('/api/product-categories', [ProductController::class, 'getCategoriesJson']);
Route::post('/api/products', [ProductController::class, 'apiStore']);
Route::put('/api/products/{id}', [ProductController::class, 'apiUpdate']);
Route::delete('/api/products/{id}', [ProductController::class, 'apiDestroy']);

// Product Design API routes
Route::get('/api/product-designs', [ProductDesignController::class, 'getDesignsJson']);
Route::post('/api/product-designs', [ProductDesignController::class, 'apiStore']);
Route::put('/api/product-designs/{id}', [ProductDesignController::class, 'apiUpdate']);
Route::delete('/api/product-designs/{id}', [ProductDesignController::class, 'apiDestroy']);

// Vue routes
Route::get('/vue/product-design', [ProductDesignController::class, 'vueIndex'])->name('vue.product-design');

// Product Group API routes
Route::get('/api/product-groups', [ProductGroupController::class, 'index']);

// API Routes for Vue Components
Route::get('/api/colors', [ColorController::class, 'index']);
Route::get('/api/geo', [GeoController::class, 'index']);
Route::get('/api/color-groups', [ColorGroupController::class, 'index']);
Route::get('/api/industry', [IndustryController::class, 'index']);
Route::get('/api/paper-flutes', [PaperFluteController::class, 'index']);
Route::get('/api/paper-qualities', [PaperQualityController::class, 'apiIndex']);
Route::get('/api/paper-sizes', [PaperSizeController::class, 'apiIndex']);
Route::get('/api/product-designs', [ProductDesignController::class, 'index']);
Route::get('/api/product-groups', [ProductGroupController::class, 'index']);
Route::get('/api/salespersons', [SalespersonController::class, 'apiIndex']);

// Salesperson Team API routes
Route::get('/api/salesperson-teams', [SalespersonTeamController::class, 'apiIndex']);
Route::post('/api/salesperson-teams', [SalespersonTeamController::class, 'apiStore']);
Route::put('/api/salesperson-teams/{id}', [SalespersonTeamController::class, 'update']);
Route::delete('/api/salesperson-teams/{id}', [SalespersonTeamController::class, 'destroy']);
Route::post('/api/salesperson-teams/seed', [SalespersonTeamController::class, 'apiSeed']);

// Sales Team API routes
Route::get('/api/sales-teams', [SalesTeamController::class, 'index']);
Route::post('/api/sales-teams', [SalesTeamController::class, 'store']);
Route::put('/api/sales-teams/{id}', [SalesTeamController::class, 'update']);
Route::delete('/api/sales-teams/{id}', [SalesTeamController::class, 'destroy']);
Route::post('/api/sales-teams/seed', [SalesTeamController::class, 'seed']);

// Scoring Tool API routes
Route::get('/api/scoring-tools', [ScoringToolController::class, 'index']);
Route::post('/api/scoring-tools', [ScoringToolController::class, 'store']);
Route::put('/api/scoring-tools/{id}', [ScoringToolController::class, 'update']);
Route::delete('/api/scoring-tools/{id}', [ScoringToolController::class, 'destroy']);
Route::post('/api/scoring-tools/seed', [ScoringToolController::class, 'seed']);

// API Routes for Vue components
Route::prefix('api')->group(function () {
    Route::get('/categories', [ProductController::class, 'getCategoriesJson']);
    Route::get('/products', [ProductController::class, 'getProductsJson']);
    Route::post('/products', [ProductController::class, 'apiStore']);
    Route::put('/products/{id}', [ProductController::class, 'apiUpdate']);
    Route::delete('/products/{id}', [ProductController::class, 'apiDestroy']);
    
    Route::get('/product-designs', [ProductDesignController::class, 'getDesignsJson']);
    Route::post('/product-designs', [ProductDesignController::class, 'apiStore']);
    Route::put('/product-designs/{id}', [ProductDesignController::class, 'apiUpdate']);
    Route::delete('/product-designs/{id}', [ProductDesignController::class, 'apiDestroy']);
    
    Route::get('/paper-qualities', [PaperQualityController::class, 'apiIndex']);
    
    Route::get('/paper-sizes', [PaperSizeController::class, 'apiIndex']);
    
    Route::get('/product-groups', [ProductGroupController::class, 'index']);
    
    Route::get('/sales-teams', [SalesTeamController::class, 'apiIndex']);
    
    Route::get('/salespersons', [SalespersonController::class, 'apiIndex']);
    
    Route::get('/scoring-tools', [ScoringToolController::class, 'apiIndex']);
});

