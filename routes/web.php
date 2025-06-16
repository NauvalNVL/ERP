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
use App\Http\Controllers\ISOCurrencyController;
use App\Http\Controllers\CustomerAlternateAddressController;
use App\Http\Controllers\UpdateMcController;
use App\Http\Controllers\ApproveMcController;
use App\Http\Controllers\RealeseApproveMcController;
use App\Http\Controllers\StandardFormulaController;
use App\Http\Controllers\SOConfigController;
use App\Http\Controllers\ScoringFormulaController;
use App\Http\Controllers\ObsolateReactiveMcController;
use App\Http\Controllers\CustomerSalesTypeController;
use App\Http\Controllers\CorrugatorConfigController;
use App\Http\Controllers\CorrugatorSpecByProductController;
use App\Http\Controllers\RollTrimByCorrugatorController;
use App\Http\Controllers\RollTrimByProductDesignController;
use App\Http\Controllers\RollSizeController;
use App\Http\Controllers\SideTrimByFluteController;
use App\Http\Controllers\SideTrimByProductDesignController;
use App\Http\Controllers\ComputationMethodController;
use App\Http\Controllers\BundlingComputationMethodController;
use App\Http\Controllers\ComputationFormulaController;

// Test Routes
Route::get('/test-vue', function () {
    return Inertia::render('Dashboard');
});

Route::get('/test-finishings', function () {
    try {
        $finishings = [
            ['code' => 'G', 'description' => 'Glue Application', 'is_compute' => false],
            ['code' => 'S', 'description' => 'Stitching', 'is_compute' => false],
            ['code' => 'A', 'description' => 'Assembly', 'is_compute' => false],
            ['code' => 'H', 'description' => 'Heat Treatment', 'is_compute' => false],
            ['code' => 'W', 'description' => 'Wrapping', 'is_compute' => false]
        ];

        $created = [];
        foreach ($finishings as $finishing) {
            $created[] = \App\Models\Finishing::updateOrCreate(
                ['code' => $finishing['code']],
                $finishing
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Test finishings created successfully',
            'data' => $created
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating test finishings: ' . $e->getMessage()
        ], 500);
    }
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

// Logout Route (accessible to authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Vue Routes
         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
         
         // System Security Routes
         Route::get('/user', [UserController::class, 'vueIndex'])->name('vue.system-security.index');
         Route::get('/user/create', [UserController::class, 'vueCreate'])->name('vue.system-security.create');
         Route::post('/user', [UserController::class, 'store'])->name('vue.system-security.store');
         Route::get('/user/{user}/edit', [UserController::class, 'vueEdit'])->name('vue.system-security.edit');
         Route::put('/user/{user}', [UserController::class, 'update'])->name('vue.system-security.update');
         Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('vue.system-security.destroy');
         Route::get('/system-security/amend-password', [UserController::class, 'vueAmendPassword'])->name('vue.system-security.amend-password');
         Route::get('/system-security/define-access', [UserController::class, 'vueDefineAccess'])->name('vue.system-security.define-access');
         
         // System Maintenance Routes
         Route::get('/iso-currency', function () {
             return Inertia::render('system-manager/system-maintenance/iso-currency');
         })->name('vue.iso-currency.index');
         
         Route::get('/iso-currency/view-print', function () {
             return Inertia::render('system-manager/system-maintenance/view-and-print-iso-currency');
         })->name('vue.iso-currency.view-print');
         
         Route::get('/foreign-currency', function () {
             return Inertia::render('system-manager/system-maintenance/foreign-currency');
         })->name('vue.foreign-currency.index');
         
         Route::get('/foreign-currency/view-print', function () {
             return Inertia::render('system-manager/system-maintenance/view-and-print-foreign-currency');
         })->name('vue.foreign-currency.view-print');
         
         Route::get('/business-form', function() {
             return Inertia::render('system-manager/system-maintenance/business-form', [
                 'header' => 'Define Business Form'
             ]);
         })->name('vue.business-form.index');
         
         Route::get('/business-form/view-print', function() {
             return Inertia::render('system-manager/system-maintenance/view-and-print-business-form', [
                 'header' => 'View & Print Business Forms'
             ]);
         })->name('vue.business-form.view-print');

         // Sales Management Routes
         // Sales Configuration
         Route::get('/sales-configuration', [SalesConfigurationController::class, 'vueIndex'])->name('vue.sales-configuration.index');
         
         // Standard Formula Configuration
         Route::get('/standard-formula-configuration', [StandardFormulaController::class, 'index'])->name('vue.standard-formula.index');
         
         // Corrugator Configuration Route
         Route::get('/standard-formula/setup-corrugator', [CorrugatorConfigController::class, 'index'])->name('vue.standard-formula.setup-corrugator');
         
         // Corrugator Specification by Product Route
         Route::get('/standard-formula/setup-corrugator-specification-by-product', [CorrugatorSpecByProductController::class, 'index'])->name('vue.standard-formula.setup-corrugator-specification-by-product');
         Route::get('/standard-formula/setup-corrugator-specification-by-product/view-print', [CorrugatorSpecByProductController::class, 'viewPrint'])->name('vue.standard-formula.setup-corrugator-specification-by-product.view-print');
         
         // Roll Trim By Corrugator Route
         Route::get('/standard-formula/setup-roll-trim-by-corrugator', [RollTrimByCorrugatorController::class, 'index'])->name('vue.standard-formula.setup-roll-trim-by-corrugator');
         Route::get('/standard-formula/setup-roll-trim-by-corrugator/view-print', [RollTrimByCorrugatorController::class, 'viewPrint'])->name('vue.standard-formula.setup-roll-trim-by-corrugator.view-print');
         
         // Roll Trim By Product Design Route
         Route::get('/standard-formula/setup-roll-trim-by-product-design', [RollTrimByProductDesignController::class, 'index'])->name('vue.standard-formula.setup-roll-trim-by-product-design');
         Route::get('/standard-formula/setup-roll-trim-by-product-design/view-print', [RollTrimByProductDesignController::class, 'viewPrint'])->name('vue.standard-formula.setup-roll-trim-by-product-design.view-print');
         
         // Sales Order Setup - Define SO Config
         Route::get('/sales-order/setup/define-so-config', [SOConfigController::class, 'index'])->name('vue.sales-order.setup.define-so-config');
         
         // Sales Order Setup - Define SO Period
         Route::get('/sales-order/setup/define-so-period', function () {
             return Inertia::render('sales-management/sales-order/setup/define-so-period');
         })->name('vue.sales-order.setup.define-so-period');
         
         // Sales Order Setup - Define SO Rough Cut
         Route::get('/sales-order/setup/define-so-rough-cut', function () {
             return Inertia::render('sales-management/sales-order/setup/define-so-rough-cut');
         })->name('vue.sales-order.setup.define-so-rough-cut');
         
         // Sales Order Setup - Define AC# Auto WO
         Route::get('/sales-order/setup/define-ac-auto-wo', function () {
             return Inertia::render('sales-management/sales-order/setup/define-ac-auto-wo');
         })->name('vue.sales-order.setup.define-ac-auto-wo');
         
         // Sales Order Setup - Define MC Auto WO
         Route::get('/sales-order/setup/define-mc-auto-wo', function () {
             return Inertia::render('sales-management/sales-order/setup/define-mc-auto-wo');
         })->name('vue.sales-order.setup.define-mc-auto-wo');
         
         // Sales Order Setup - Print SO Period
         Route::get('/sales-order/setup/print-so-period', function () {
             return Inertia::render('sales-management/sales-order/setup/print-so-period');
         })->name('vue.sales-order.setup.print-so-period');
         
         // Sales Order Setup - Print SO Rough Cut
         Route::get('/sales-order/setup/print-so-rough-cut', function () {
             return Inertia::render('sales-management/sales-order/setup/print-so-rough-cut');
         })->name('vue.sales-order.setup.print-so-rough-cut');
         
         // Sales Order Setup - Print AC# Auto WO
         Route::get('/sales-order/setup/print-ac-auto-wo', function () {
             return Inertia::render('sales-management/sales-order/setup/print-ac-auto-wo');
         })->name('vue.sales-order.setup.print-ac-auto-wo');
         
         // Sales Order Setup - Print MC Auto WO
         Route::get('/sales-order/setup/print-mc-auto-wo', function () {
             return Inertia::render('sales-management/sales-order/setup/print-mc-auto-wo');
         })->name('vue.sales-order.setup.print-mc-auto-wo');

         // Sales Order Report - Rough Cut Report - Define Report Format
         Route::get('/sales-order/report/rough-cut-report/define-report-format', function () {
             return Inertia::render('sales-management/sales-order/report/rough-cut-report/define-report-format');
         })->name('vue.sales-order.report.rough-cut-report.define-report-format');

         // Sales Order Report - Rough Cut Report - Print Rough Cut Report
         Route::get('/sales-order/report/rough-cut-report/print-rough-cut-report', function () {
             return Inertia::render('sales-management/sales-order/report/rough-cut-report/print-rough-cut-report');
         })->name('vue.sales-order.report.rough-cut-report.print-rough-cut-report');

         // Sales Order Report - Print SO Cancel Report
         Route::get('/sales-order/report/print-so-cancel-report', function () {
             return Inertia::render('sales-management/sales-order/report/print-so-cancel-report');
         })->name('vue.sales-order.report.print-so-cancel-report');

         // Sales Order Report - Print SO Report
         Route::get('/sales-order/report/print-so-report', function () {
             return Inertia::render('sales-management/sales-order/report/print-so-report');
         })->name('vue.sales-order.report.print-so-report');

         Route::get('/scoring-formula', [ScoringFormulaController::class, 'index'])->name('vue.scoring-formula.index');
         Route::get('/scoring-formula/view-print', [ScoringFormulaController::class, 'viewAndPrint'])->name('vue.scoring-formula.view-print');
         
         // Standard Requirement Routes
         Route::get('/sales-team', [SalesTeamController::class, 'vueIndex'])->name('vue.sales-team.index');
         Route::get('/sales-team/view-print', [SalesTeamController::class, 'vueViewAndPrint'])->name('vue.sales-team.view-print');
         
         Route::get('/sales-person', [SalespersonController::class, 'vueIndex'])->name('vue.sales-person.index');
         Route::get('/sales-person/view-print', [SalespersonController::class, 'vueViewAndPrint'])->name('vue.sales-person.view-print');
         
         Route::get('/sales-person-team', [SalespersonTeamController::class, 'vueIndex'])->name('vue.sales-person-team.index');
         Route::get('/sales-person-team/view-print', [SystemRequirementController::class, 'vueViewPrintSalespersonTeam'])->name('vue.sales-person-team.view-print');
         
         Route::get('/industry', [IndustryController::class, 'vueIndex'])->name('vue.industry.index');
         Route::get('/industry/view-print', [IndustryController::class, 'vueViewAndPrint'])->name('vue.industry.view-print');
         
         Route::get('/geo', [GeoController::class, 'vueIndex'])->name('vue.geo.index');
         Route::get('/geo/view-print', [GeoController::class, 'vueViewAndPrint'])->name('vue.geo.view-print');
         
         Route::get('/product-group', [ProductGroupController::class, 'vueIndex'])->name('vue.product-group.index');
         Route::get('/product-group/view-print', [ProductGroupController::class, 'vueViewAndPrint'])->name('vue.product-group.view-print');
         
         Route::get('/product', [ProductController::class, 'vueIndex'])->name('vue.product.index');
         Route::get('/product/view-print', [ProductController::class, 'vueViewAndPrint'])->name('vue.product.view-print');
         
         Route::get('/product-design', [ProductDesignController::class, 'vueIndex'])->name('vue.product-design.index');
         Route::get('/product-design/standard-formula', function() {
             return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ProductDesign');
         })->name('vue.product-design.standard-formula');
         Route::get('/product-design/view-print', [ProductDesignController::class, 'vueViewAndPrint'])->name('vue.product-design.view-print');
         
         Route::get('/scoring-tool', [ScoringToolController::class, 'vueIndex'])->name('vue.scoring-tool.index');
         Route::get('/scoring-tool/view-print', [ScoringToolController::class, 'vueViewAndPrint'])->name('vue.scoring-tool.view-print');
         
         Route::get('/paper-quality', [PaperQualityController::class, 'vueIndex'])->name('vue.paper-quality.index');
         Route::get('/paper-quality/status', [PaperQualityController::class, 'vueManageStatus'])->name('vue.paper-quality.status');
         Route::get('/paper-quality/view-print', [PaperQualityController::class, 'vueViewAndPrint'])->name('vue.paper-quality.view-print');
         
         Route::get('/paper-flute', [PaperFluteController::class, 'vueIndex'])->name('vue.paper-flute.index');
         Route::get('/paper-flute/view-print', [PaperFluteController::class, 'vueViewAndPrint'])->name('vue.paper-flute.view-print');
         
         Route::get('/paper-size', [PaperSizeController::class, 'vueIndex'])->name('vue.paper-size.index');
         Route::get('/paper-size/view-print', [PaperSizeController::class, 'vueViewAndPrint'])->name('vue.paper-size.view-print');
         
         Route::get('/color-group', [ColorGroupController::class, 'vueIndex'])->name('vue.color-group.index');
         Route::get('/color-group/view-print', [ColorGroupController::class, 'vueViewAndPrint'])->name('vue.color-group.view-print');
         
         Route::get('/color', [ColorController::class, 'vueIndex'])->name('vue.color.index');
         Route::get('/color/view-print', [ColorController::class, 'vueViewAndPrint'])->name('vue.color.view-print');
         
         Route::get('/finishing', [FinishingController::class, 'vueIndex'])->name('vue.finishing.index');
         Route::get('/finishing/view-print', [FinishingController::class, 'vueViewAndPrint'])->name('vue.finishing.view-print');
         
         // Customer Account Routes
         Route::get('/customer-group', function () {
             return Inertia::render('sales-management/system-requirement/customer-account/customer-group');
         })->name('vue.customer-group.index');
         
         Route::get('/customer-group/view-print', [CustomerGroupController::class, 'vueViewAndPrint'])->name('vue.customer-group.view-print');

         Route::get('/update-customer-account', function () {
             return Inertia::render('sales-management/system-requirement/customer-account/update-customer-account');
         })->name('vue.update-customer-account.index');
         
         Route::post('/update-customer-account', [UpdateCustomerAccountController::class, 'store'])->name('update-customer-account.store');
         
         Route::get('/update-customer-account/view-print', function () {
             return Inertia::render('sales-management/system-requirement/customer-account/view-and-print-customer-account');
         })->name('vue.update-customer-account.view-print');
         
         Route::get('/obsolete-reactive-customer-account', function () {
             return Inertia::render('sales-management/system-requirement/customer-account/obsolete-reactive-customer-ac');
         })->name('vue.obsolete-reactive-customer-account.index');
         
         Route::get('/customer-alternate-address', [CustomerAlternateAddressController::class, 'index'])->name('vue.customer-alternate-address.index');
         Route::get('/customer-alternate-address/view-print', function () {
             return Inertia::render('sales-management/system-requirement/customer-account/view-and-print-customer-alternate-address');
         })->name('vue.customer-alternate-address.view-print');
         
         Route::get('/customer-sales-type', [CustomerSalesTypeController::class, 'index'])->name('vue.customer-sales-type.index');
         Route::get('/customer-sales-type/view-print', [CustomerSalesTypeController::class, 'viewAndPrint'])->name('vue.customer-sales-type.view-print');

         // Master Card Routes
         Route::get('/sales-management/system-requirement/master-card/update-mc', [UpdateMcController::class, 'index'])->name('vue.master-card.update-mc');
         Route::post('/api/update-mc/search-ac', [UpdateMcController::class, 'searchAc']);
         Route::post('/api/update-mc/search-mcs', [UpdateMcController::class, 'searchMcs']);
         
         // Add route for approve-mc
         Route::get('/sales-management/system-requirement/master-card/approve-mc', function () {
             // Fetch master cards data
             $masterCards = \App\Models\ApproveMC::orderBy('mc_seq')->get();
             $customers = \App\Models\UpdateCustomerAccount::orderBy('customer_name')->get();
             
             return Inertia::render('sales-management/system-requirement/master-card/approve-mc', [
                 'masterCards' => $masterCards,
                 'customers' => $customers
             ]);
         })->name('vue.master-card.approve-mc');
         
         // Add route for realese-approve-mc
         Route::get('/sales-management/system-requirement/master-card/realese-approve-mc', [RealeseApproveMcController::class, 'index'])->name('vue.master-card.realese-approve-mc');

                  // Add route for obsolate-reactive-mc
        Route::get('/sales-management/system-requirement/master-card/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'index'])->name('vue.master-card.obsolate-reactive-mc');

        // Add route for view-and-print-MC
        Route::get('/sales-management/system-requirement/master-card/view-and-print-MC', [ObsolateReactiveMcController::class, 'viewAndPrint'])->name('vue.master-card.view-and-print-mc');

        // Add route for view-and-print-mc-maintenance-log
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log', [ObsolateReactiveMcController::class, 'viewAndPrintMcMaintenanceLog'])->name('vue.master-card.view-and-print-mc-maintenance-log');
        
        // Add route for view-and-print-mc-approval-log
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-approval-log', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-approval-log');
        })->name('vue.master-card.view-and-print-mc-approval-log');

        // Add route for view-and-print-non-active-mc
        Route::get('/sales-management/system-requirement/master-card/view-and-print-non-active-mc', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-non-active-mc');
        })->name('vue.master-card.view-and-print-non-active-mc');

        // Add route for initialized-mc-maintenance-log
        Route::get('/sales-management/system-requirement/master-card/initialized-mc-maintenance-log', function() {
            return Inertia::render('sales-management/system-requirement/master-card/initialized-mc-maintenance-log');
        })->name('vue.master-card.initialized-mc-maintenance-log');

    // Auth Routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// API Routes for Vue components
Route::prefix('api')->group(function () {
    // User API routes
    Route::get('/users', [UserController::class, 'searchUsers']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::get('/users/{user}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('/users/{user}/permissions', [UserController::class, 'updateAccess']);
    Route::post('/users/update-password', [UserController::class, 'updatePassword']);
    
    // Salesperson API routes
    Route::get('/salesperson', [SalespersonController::class, 'apiIndex']);
    Route::post('/salesperson/store', [SalespersonController::class, 'store']);
    Route::post('/salesperson/update/{code}', [SalespersonController::class, 'update']);
    Route::post('/salesperson/delete/{code}', [SalespersonController::class, 'destroy']);
    Route::post('/salesperson/seed', [SalespersonController::class, 'seed']);
    
    // Paper Quality API routes
    Route::get('/paper-qualities', [PaperQualityController::class, 'apiIndex']);
    Route::post('/paper-qualities', [PaperQualityController::class, 'apiStore']);
    Route::put('/paper-qualities/{id}', [PaperQualityController::class, 'apiUpdate']);
    Route::delete('/paper-qualities/{id}', [PaperQualityController::class, 'apiDestroy']);
    
    // Paper Flute API routes
    Route::get('/paper-flutes', [PaperFluteController::class, 'index']);
    Route::post('/paper-flutes', [PaperFluteController::class, 'store']);
    Route::put('/paper-flutes/{code}', [PaperFluteController::class, 'update']);
    Route::delete('/paper-flutes/{code}', [PaperFluteController::class, 'destroy']);
    Route::get('/paper-flutes/seeder-data', [PaperFluteController::class, 'getSeederData']);
    Route::post('/paper-flutes/seeder-data', [PaperFluteController::class, 'updateSeederData']);
    
    Route::get('/categories', [ProductController::class, 'getCategoriesJson']);
    Route::get('/products', [ProductController::class, 'getProductsJson']);
    Route::post('/products', [ProductController::class, 'apiStore']);
    Route::put('/products/{id}', [ProductController::class, 'apiUpdate']);
    Route::delete('/products/{id}', [ProductController::class, 'apiDestroy']);
    
    // Product Designs API routes - Fixed duplicate /api prefix
    Route::get('/product-designs', [ProductDesignController::class, 'getDesignsJson']);
    Route::get('/product-designs/by-code/{code}', [ProductDesignController::class, 'getByCode']);
    Route::post('/product-designs', [ProductDesignController::class, 'apiStore']);
    Route::put('/product-designs/{id}', [ProductDesignController::class, 'apiUpdate']);
    Route::delete('/product-designs/{id}', [ProductDesignController::class, 'apiDestroy']);
    
    // Paper Size API routes
    Route::get('/paper-sizes', [PaperSizeController::class, 'apiIndex']);
    Route::post('/paper-sizes', [PaperSizeController::class, 'apiStore']);
    Route::put('/paper-sizes/{id}', [PaperSizeController::class, 'apiUpdate']);
    Route::delete('/paper-sizes/{id}', [PaperSizeController::class, 'apiDestroy']);
    
    Route::get('/product-groups', [ProductGroupController::class, 'index']);
    Route::get('/sales-teams', [SalesTeamController::class, 'apiIndex']);
    Route::get('/scoring-tools', [ScoringToolController::class, 'apiIndex']);
    
    // API Routes for Vue Components
    Route::get('/colors', [ColorController::class, 'index']);
    Route::get('/geo', [GeoController::class, 'index']);
    Route::post('/geo', [GeoController::class, 'store']);
    Route::put('/geo/{code}', [GeoController::class, 'update']);
    Route::delete('/geo/{code}', [GeoController::class, 'destroy']);
    Route::get('/color-groups', [ColorGroupController::class, 'index']);
    Route::post('/color-groups', [ColorGroupController::class, 'store']);
    Route::put('/color-groups/{id}', [ColorGroupController::class, 'update']);
    Route::delete('/color-groups/{id}', [ColorGroupController::class, 'destroy']);
    Route::post('/color-groups/seed', [ColorGroupController::class, 'seed']);
    
    // Colors API routes - Using color_id as parameter
    Route::post('/colors', [ColorController::class, 'store']);
    Route::put('/colors/{color_id}', [ColorController::class, 'update']);
    Route::delete('/colors/{color_id}', [ColorController::class, 'destroy']);
    Route::post('/colors/seed', [ColorController::class, 'seed']);
    
    Route::get('/industry', [IndustryController::class, 'index']);
    Route::post('/industry', [IndustryController::class, 'store']);
    Route::put('/industry/{id}', [IndustryController::class, 'update']);
    Route::delete('/industry/{id}', [IndustryController::class, 'destroy']);
    Route::get('/industry/search/{code}', [IndustryController::class, 'search']);
    Route::get('/industries', [IndustryController::class, 'apiIndex']);
    Route::get('/paper-flutes', [PaperFluteController::class, 'index']);
    
    // Product Group API routes
    Route::post('/product-groups', [ProductGroupController::class, 'apiStore']);
    Route::put('/product-groups/{id}', [ProductGroupController::class, 'apiUpdate']);
    Route::delete('/product-groups/{id}', [ProductGroupController::class, 'apiDestroy']);
    Route::post('/product-groups/seed', [ProductGroupController::class, 'apiSeed']);
    
    // Salesperson Team API routes
    Route::get('/salesperson-teams', [SalespersonTeamController::class, 'apiIndex']);
    Route::post('/salesperson-teams', [SalespersonTeamController::class, 'apiStore']);
    Route::put('/salesperson-teams/{id}', [SalespersonTeamController::class, 'update']);
    Route::delete('/salesperson-teams/{id}', [SalespersonTeamController::class, 'destroy']);
    Route::post('/salesperson-teams/seed', [SalespersonTeamController::class, 'apiSeed']);

    // Sales Team API routes
    Route::post('/sales-teams', [SalesTeamController::class, 'store']);
    Route::put('/sales-teams/{id}', [SalesTeamController::class, 'update']);
    Route::delete('/sales-teams/{id}', [SalesTeamController::class, 'destroy']);
    Route::post('/sales-teams/seed', [SalesTeamController::class, 'seed']);

    // Scoring Tool API routes
    Route::post('/scoring-tools', [ScoringToolController::class, 'apiStore']);
    Route::put('/scoring-tools/{id}', [ScoringToolController::class, 'update']);
    Route::delete('/scoring-tools/{id}', [ScoringToolController::class, 'destroy']);
    Route::post('/scoring-tools/seed', [ScoringToolController::class, 'seed']);
    
    // Business Form API routes
    Route::get('/business-forms', [BusinessFormController::class, 'apiIndex']);
    Route::post('/business-forms', [BusinessFormController::class, 'apiStore']);
    Route::get('/business-forms/{id}', [BusinessFormController::class, 'apiShow']);
    Route::put('/business-forms/{id}', [BusinessFormController::class, 'apiUpdate']);
    Route::delete('/business-forms/{id}', [BusinessFormController::class, 'apiDestroy']);

    // ISO Currency API endpoints
    Route::get('/iso-currencies', [ISOCurrencyController::class, 'apiIndex']);
    Route::get('/iso-currencies/{id}', [ISOCurrencyController::class, 'apiShow']);
    Route::post('/iso-currencies', [ISOCurrencyController::class, 'apiStore']);
    Route::put('/iso-currencies/{id}', [ISOCurrencyController::class, 'apiUpdate']);
    Route::delete('/iso-currencies/{id}', [ISOCurrencyController::class, 'apiDestroy']);

    // Customer Groups API routes
    Route::get('/customer-groups', [CustomerGroupController::class, 'apiIndex']);
    Route::post('/customer-groups', [CustomerGroupController::class, 'apiStore']);
    Route::put('/customer-groups/{group_code}', [CustomerGroupController::class, 'apiUpdate']);
    Route::delete('/customer-groups/{group_code}', [CustomerGroupController::class, 'apiDestroy']);
    Route::post('/customer-groups/seed', [CustomerGroupController::class, 'seed']);

    // Customer Accounts API routes
    Route::get('/customer-accounts', [UpdateCustomerAccountController::class, 'apiIndex']);
    Route::post('/customer-accounts', [UpdateCustomerAccountController::class, 'apiStore']);
    Route::put('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiUpdate']);
    
    // Customer Alternate Address API routes
    Route::get('/customer-alternate-addresses', [CustomerAlternateAddressController::class, 'apiIndex']);
    Route::get('/customer-alternate-addresses/{customerCode}', [CustomerAlternateAddressController::class, 'apiGetCustomerAddresses']);
    Route::post('/customer-alternate-addresses', [CustomerAlternateAddressController::class, 'apiStore']);
    Route::put('/customer-alternate-addresses/{id}', [CustomerAlternateAddressController::class, 'apiUpdate']);
    Route::delete('/customer-alternate-addresses/{id}', [CustomerAlternateAddressController::class, 'apiDestroy']);
    Route::post('/seed-customer-alternate-addresses', [CustomerAlternateAddressController::class, 'seed']);
    
    // Foreign Currency API endpoints
    Route::get('/foreign-currencies', [ForeignCurrencyController::class, 'apiIndex']);
    Route::get('/foreign-currencies/{id}', [ForeignCurrencyController::class, 'apiShow']);
    
    // Customer Sales Type API routes
    Route::get('/customer-sales-types', [CustomerSalesTypeController::class, 'apiIndex']);
    Route::post('/customer-sales-types', [CustomerSalesTypeController::class, 'apiStore']);
    Route::put('/customer-sales-types/{id}', [CustomerSalesTypeController::class, 'apiUpdate']);
    Route::delete('/customer-sales-types/{id}', [CustomerSalesTypeController::class, 'apiDestroy']);

    // Color Group API routes
    Route::get('/color-groups', [ColorGroupController::class, 'index']);
    Route::post('/color-groups', [ColorGroupController::class, 'store']);
    Route::put('/color-groups/{id}', [ColorGroupController::class, 'update']);
    Route::delete('/color-groups/{id}', [ColorGroupController::class, 'destroy']);
    Route::post('/color-groups/seed', [ColorGroupController::class, 'seed']);
    
    // Finishing API routes
    Route::get('/finishings', [FinishingController::class, 'apiIndex']);
    Route::post('/finishings', [FinishingController::class, 'store']);
    Route::put('/finishings/{code}', [FinishingController::class, 'update']);
    Route::delete('/finishings/{code}', [FinishingController::class, 'destroy']);
    Route::post('/finishings/seed', [FinishingController::class, 'seed']);

    // ApproveMC API routes
    Route::get('/approve-mc', [ApproveMcController::class, 'apiIndex']);
    Route::post('/approve-mc', [ApproveMcController::class, 'store']);
    Route::put('/approve-mc/{id}', [ApproveMcController::class, 'update']);
    Route::post('/approve-mc/approve/{id}', [ApproveMcController::class, 'approve']);
    Route::post('/approve-mc/reject/{id}', [ApproveMcController::class, 'reject']);
    Route::get('/approve-mc/by-customer/{customerId}', [ApproveMcController::class, 'getByCustomer']);
    
    // RealeseApproveMC API routes
    Route::get('/realese-approve-mc', [RealeseApproveMcController::class, 'apiIndex']);
    Route::post('/realese-approve-mc', [RealeseApproveMcController::class, 'store']);
    Route::post('/realese-approve-mc/release/{id}', [RealeseApproveMcController::class, 'release']);
    Route::post('/realese-approve-mc/unreleased/{id}', [RealeseApproveMcController::class, 'unreleased']);
    Route::get('/realese-approve-mc/by-customer/{customerId}', [RealeseApproveMcController::class, 'getByCustomer']);

    // Standard Formula API routes
    Route::get('/standard-formula', [StandardFormulaController::class, 'apiIndex']);
    Route::post('/standard-formula', [StandardFormulaController::class, 'apiStore']);
    Route::post('/standard-formula/seed', [StandardFormulaController::class, 'apiSeed']);

    // Scoring Formula API routes
    Route::get('/scoring-formulas', [ScoringFormulaController::class, 'apiIndex']);
    Route::get('/scoring-formulas/{id}', [ScoringFormulaController::class, 'apiShow']);
    Route::post('/scoring-formulas', [ScoringFormulaController::class, 'apiStore']);
    Route::put('/scoring-formulas/{id}', [ScoringFormulaController::class, 'apiUpdate']);
    Route::delete('/scoring-formulas/{id}', [ScoringFormulaController::class, 'apiDestroy']);
    Route::get('/scoring-formulas/product-design/{productDesignId}', [ScoringFormulaController::class, 'getByProductDesign']);
    Route::get('/scoring-formulas/paper-flute/{paperFluteId}', [ScoringFormulaController::class, 'getByPaperFlute']);
    Route::post('/scoring-formulas/seed', [ScoringFormulaController::class, 'apiSeed']);

    // ObsolateReactiveMC API routes
    Route::get('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'apiIndex']);
    Route::post('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'store']);
    Route::post('/obsolate-reactive-mc/obsolate/{id}', [ObsolateReactiveMcController::class, 'obsolate']);
    Route::post('/obsolate-reactive-mc/reactive/{id}', [ObsolateReactiveMcController::class, 'reactive']);
    Route::get('/obsolate-reactive-mc/by-customer/{customerId}', [ObsolateReactiveMcController::class, 'getByCustomer']);
    
    // Corrugator Configuration API routes
    Route::get('/corrugator-configs', [CorrugatorConfigController::class, 'apiIndex']);
    Route::get('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiShow']);
    Route::post('/corrugator-configs', [CorrugatorConfigController::class, 'apiStore']);
    Route::put('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiUpdate']);
    Route::delete('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiDestroy']);
    Route::post('/corrugator-configs/seed', [CorrugatorConfigController::class, 'apiSeed']);

    // Corrugator Specs By Product API routes
    Route::get('/corrugator-specs-by-product', [CorrugatorSpecByProductController::class, 'apiIndex']);
    Route::get('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiShow']);
    Route::post('/corrugator-specs-by-product', [CorrugatorSpecByProductController::class, 'apiStore']);
    Route::put('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiUpdate']);
    Route::delete('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiDestroy']);
    Route::post('/corrugator-specs-by-product/batch', [CorrugatorSpecByProductController::class, 'apiBatchUpdate']);
    Route::get('/corrugator-specs-by-product/export', [CorrugatorSpecByProductController::class, 'apiExport']);

    // Bundling Computation Method API routes
    Route::get('/bundling-computation-methods', [BundlingComputationMethodController::class, 'apiIndex']);
    Route::get('/bundling-computation-methods/{id}', [BundlingComputationMethodController::class, 'apiShow']);
    Route::post('/bundling-computation-methods', [BundlingComputationMethodController::class, 'apiStore']);
    Route::put('/bundling-computation-methods/{id}', [BundlingComputationMethodController::class, 'apiUpdate']);
    Route::delete('/bundling-computation-methods/{id}', [BundlingComputationMethodController::class, 'apiDestroy']);
    Route::post('/bundling-computation-methods/seed', [BundlingComputationMethodController::class, 'apiSeed']);

    // Diecut Computation Formula API routes
    Route::get('/diecut-computation-formulas', [ComputationFormulaController::class, 'apiIndex']);
    Route::get('/diecut-computation-formulas/{id}', [ComputationFormulaController::class, 'apiShow']);
    Route::post('/diecut-computation-formulas', [ComputationFormulaController::class, 'apiStore']);
    Route::put('/diecut-computation-formulas/{id}', [ComputationFormulaController::class, 'apiUpdate']);
    Route::delete('/diecut-computation-formulas/{id}', [ComputationFormulaController::class, 'apiDestroy']);
    Route::post('/diecut-computation-formulas/seed', [ComputationFormulaController::class, 'apiSeed']);
});

// Roll Size Route
Route::get('/standard-formula/setup-roll-size', [RollSizeController::class, 'index'])->name('vue.standard-formula.setup-roll-size');
Route::get('/standard-formula/setup-roll-size/view-print', [RollSizeController::class, 'viewPrint'])->name('vue.standard-formula.setup-roll-size.view-print');

// Side Trim By Flute Route
Route::get('/standard-formula/setup-side-trim-by-flute', [SideTrimByFluteController::class, 'index'])->name('vue.standard-formula.setup-side-trim-by-flute');
Route::get('/standard-formula/setup-side-trim-by-flute/view-print', [SideTrimByFluteController::class, 'viewPrint'])->name('vue.standard-formula.setup-side-trim-by-flute.view-print');

// Side Trim By Product Design Route
Route::get('/standard-formula/setup-side-trim-by-product-design', [SideTrimByProductDesignController::class, 'index'])->name('vue.standard-formula.setup-side-trim-by-product-design');
Route::get('/standard-formula/setup-side-trim-by-product-design/view-print', [SideTrimByProductDesignController::class, 'viewPrint'])->name('vue.standard-formula.setup-side-trim-by-product-design.view-print');

// Define routes for the Roll Size feature
Route::get('/standard-formula/setup-roll-size', function () {
    return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/RollSize');
})->name('setup.roll.size');

Route::get('/standard-formula/setup-roll-size/view-print', function () {
    return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintRollSize');
})->name('setup.roll.size.view-print');

// Define routes for the Product Design feature
Route::get('/standard-formula/setup-corrugator-run-size-formula/product-design', function () {
    return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ProductDesign');
})->name('setup.product.design');

Route::get('/standard-formula/setup-corrugator-run-size-formula/product-design/view-print', function () {
    return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintProductDesign');
})->name('setup.product.design.view-print');

// Computation Method Route
Route::get('/standard-formula/stitching-computation-method', [ComputationMethodController::class, 'index'])->name('vue.standard-formula.stitching-computation-method');

// Finishing Routes
Route::get('/standard-formula/stitching-computation/finishing', [App\Http\Controllers\FinishingController::class, 'vueIndex'])->name('finishing.index');
Route::get('/standard-formula/stitching-computation/finishing/view-print', [App\Http\Controllers\FinishingController::class, 'vueViewAndPrint'])->name('finishing.view-print');

Route::get('/finishings/test-data', function () {
    try {
        $finishings = [
            ['code' => 'G', 'description' => 'Glue Application', 'is_compute' => false],
            ['code' => 'S', 'description' => 'Stitching', 'is_compute' => false],
            ['code' => 'A', 'description' => 'Assembly', 'is_compute' => false],
            ['code' => 'H', 'description' => 'Heat Treatment', 'is_compute' => false],
            ['code' => 'W', 'description' => 'Wrapping', 'is_compute' => false]
        ];

        $created = [];
        foreach ($finishings as $finishing) {
            $created[] = \App\Models\Finishing::updateOrCreate(
                ['code' => $finishing['code']],
                $finishing
            );
        }

        return response()->json($created);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating test finishings: ' . $e->getMessage()
        ], 500);
    }
});

// Define routes for the Bundling Computation Method feature
Route::get('/standard-formula/bundling-computation/method', [BundlingComputationMethodController::class, 'index'])->name('vue.standard-formula.bundling-computation.method');
Route::get('/standard-formula/bundling-computation/view-print-method', [BundlingComputationMethodController::class, 'viewPrint'])->name('vue.standard-formula.bundling-computation.view-print-method');

// Define routes for the Diecut Computation Method feature
Route::get('/standard-formula/diecut-computation/formula', [ComputationFormulaController::class, 'index'])->name('vue.standard-formula.diecut-computation.formula');
Route::get('/standard-formula/diecut-computation/product-design', function() {
    return Inertia::render('sales-management/standard-formula/diecut-computation-method/ProductDesign');
})->name('vue.standard-formula.diecut-computation.product-design');
Route::get('/standard-formula/diecut-computation/view-print-product-design', function() {
    return Inertia::render('sales-management/standard-formula/diecut-computation-method/ViewPrintProductDesign');
})->name('vue.standard-formula.diecut-computation.view-print-product-design');
