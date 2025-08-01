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
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmConfigController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmControlPeriodController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTransactionTypeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTaxTypeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTaxGroupController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmAnalysisCodeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmLocationController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmCategoryController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmUnitController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmReportGroupController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmGlDistributionController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController;
use App\Http\Controllers\CustomerWarehouseRequirementController;

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
    return redirect()->route('login');
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
         
         // Customer Service Routes
         Route::get('/customer-service/dashboard', function () {
             return Inertia::render('sales-management/customer-service/customer-service-dashboard');
         })->name('vue.customer-service.dashboard');

         Route::get('/customer-service/account-credit', function () {
             return Inertia::render('sales-management/customer-service/customer-account-credit');
         })->name('vue.customer-service.account-credit');

         Route::get('/customer-service/delivery-schedule', function () {
             return Inertia::render('sales-management/customer-service/sales-order-delivery-schedule');
         })->name('vue.customer-service.delivery-schedule');

         Route::get('/customer-service/finished-goods', function () {
             return Inertia::render('sales-management/customer-service/customer-finished-goods');
         })->name('vue.customer-service.finished-goods');

         Route::get('/customer-service/production-monitoring-board', function () {
             return Inertia::render('sales-management/customer-service/production-monitoring-board');
         })->name('vue.customer-service.production-monitoring-board');
         
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
         
         Route::get('/obsolete-reactive-customer-account/view-print', function () {
            return Inertia::render('sales-management/system-requirement/customer-account/view-and-print-nonactive-customer');
        })->name('vue.obsolete-reactive-customer-account.view-print');
         
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
        Route::get('/sales-management/system-requirement/master-card/obsolete-reactive-mc', [ObsolateReactiveMcController::class, 'index'])->name('vue.master-card.obsolete-reactive-mc');

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
        
        // Add route for view-and-print-mc-print-dc-block-listing
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-listing', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-listing');
        })->name('vue.master-card.view-and-print-mc-print-dc-block-listing');
        
        // Add route for view-and-print-mc-print-dc-block-matching
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-matching', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-matching');
        })->name('vue.master-card.view-and-print-mc-print-dc-block-matching');
        
        // Add route for view-and-print-mc-by-color
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-by-color', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-by-color');
        })->name('vue.master-card.view-and-print-mc-by-color');

        // Add route for view-and-print-mc-by-psize-pquality
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-by-psize-pquality', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-by-psize-pquality');
        })->name('vue.master-card.view-and-print-mc-by-psize-pquality');

        // Add route for view-and-print-mc-by-machine
        Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-by-machine', function() {
            return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-by-machine');
        })->name('vue.master-card.view-and-print-mc-by-machine');

    // Auth Routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Warehouse Management Routes
    Route::get('/warehouse-management/finished-goods', function () {
        return Inertia::render('warehouse-management/FinishedGoods/index');
    })->name('vue.warehouse-management.finished-goods');

    Route::get('/warehouse-management/finished-goods/setup-maintenance', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance');

    Route::get('/warehouse-management/finished-goods/fg-normal', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal');
    })->name('vue.warehouse-management.finished-goods.fg-normal');

    Route::get('/warehouse-management/finished-goods/fg-normal/check-fg-balance', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/CheckFGBalance');
    })->name('vue.warehouse-management.finished-goods.fg-normal.check-fg-balance');

    Route::get('/warehouse-management/finished-goods/fg-normal/clear-fg-mc-lock', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/ClearFGMCLock');
    })->name('vue.warehouse-management.finished-goods.fg-normal.clear-fg-mc-lock');

    Route::get('/warehouse-management/finished-goods/fg-normal/print-fg-stock-in-log', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/PrintFGStockInLog');
    })->name('vue.warehouse-management.finished-goods.fg-normal.print-fg-stock-in-log');

    Route::get('/warehouse-management/finished-goods/fg-normal/print-fg-stock-out-log', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/PrintFGStockOutLog');
    })->name('vue.warehouse-management.finished-goods.fg-normal.print-fg-stock-out-log');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-location-transfer', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGLocationTransfer');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-location-transfer');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-barcode', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGStockInByBarcode');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-stock-in-by-barcode');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-so', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGStockInBySO');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-stock-in-by-so');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-wo', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGStockInByWO');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-stock-in-by-wo');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-by-batch', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGStockOutByBatch');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-stock-out-by-batch');

    Route::get('/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-by-mc', function () {
        return Inertia::render('warehouse-management/FinishedGoods/FGNormal/UpdateFGStockOutByMC');
    })->name('vue.warehouse-management.finished-goods.fg-normal.update-fg-stock-out-by-mc');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/fg-do-configuration', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/fg-do-configuration');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.fg-do-configuration');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-control-period', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-control-period');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-control-period');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-customer-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-customer-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-delivery-order-format', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-delivery-order-format');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-delivery-order-format');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-requirement', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-customer-warehouse-requirement');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-customer-warehouse-requirement');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-analysis-code', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-analysis-code');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-analysis-code');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-control-period', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-control-period');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-control-period');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-customer-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-customer-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-delivery-order-format', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-delivery-order-format');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-delivery-order-format');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-requirement', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-customer-warehouse-requirement');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-customer-warehouse-requirement');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-analysis-code', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-analysis-code');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-analysis-code');

    Route::get('/warehouse-management/delivery-order', function () {
        return Inertia::render('warehouse-management/DeliveryOrder/index');
    })->name('vue.warehouse-management.delivery-order');

    Route::get('/warehouse-management/invoice', function () {
        return Inertia::render('warehouse-management/Invoice/index');
    })->name('vue.warehouse-management.invoice');

    Route::get('/warehouse-management/debit-credit-note', function () {
        return Inertia::render('warehouse-management/DebitCreditNote/index');
    })->name('vue.warehouse-management.debit-credit-note');

    Route::get('/warehouse-management/warehouse-analysis', function () {
        return Inertia::render('warehouse-management/WarehouseAnalysis/index');
    })->name('vue.warehouse-management.warehouse-analysis');

    Route::get('/warehouse-management/custom-indonesia', function () {
        return Inertia::render('warehouse-management/CustomIndonesia/index');
    })->name('vue.warehouse-management.custom-indonesia');

    Route::get('/warehouse-management/accounts', function () {
        return Inertia::render('warehouse-management/Accounts/index');
    })->name('vue.warehouse-management.accounts');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/fg-do-configuration', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/fg-do-configuration');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.fg-do-configuration');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-control-period', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-control-period');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-control-period');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-customer-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-customer-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-delivery-order-format', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-delivery-order-format');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-delivery-order-format');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-requirement', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-customer-warehouse-requirement');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-customer-warehouse-requirement');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/define-analysis-code', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-analysis-code');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.define-analysis-code');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-control-period', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-control-period');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-control-period');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-location', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-customer-warehouse-location');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-customer-warehouse-location');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-delivery-order-format', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-delivery-order-format');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-delivery-order-format');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-requirement', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-customer-warehouse-requirement');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-customer-warehouse-requirement');

    Route::get('/warehouse-management/finished-goods/setup-maintenance/view-print-analysis-code', function () {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/view-print-analysis-code');
    })->name('vue.warehouse-management.finished-goods.setup-maintenance.view-print-analysis-code');

    Route::prefix('material-management/system-requirement/standard-setup')->name('mm.sr.ss.')->group(function () {
        Route::get('configuration', [MmConfigController::class, 'index'])->name('config');
        Route::get('control-period', [MmControlPeriodController::class, 'index'])->name('cp.index');
        Route::get('control-period/view-print', [MmControlPeriodController::class, 'viewPrint'])->name('cp.vp');
        Route::get('transaction-type', [MmTransactionTypeController::class, 'index'])->name('tt.index');
        Route::get('transaction-type/view-print', [MmTransactionTypeController::class, 'viewPrint'])->name('tt.vp');
        Route::get('tax-type', [MmTaxTypeController::class, 'index'])->name('taxtype.index');
        Route::get('tax-type/view-print', [MmTaxTypeController::class, 'viewPrint'])->name('taxtype.vp');
        Route::get('tax-group', [MmTaxGroupController::class, 'index'])->name('taxgroup.index');
        Route::get('tax-group/view-print', [MmTaxGroupController::class, 'viewPrint'])->name('taxgroup.vp');
        Route::get('receive-destination', [MmReceiveDestinationController::class, 'index'])->name('rd.index');
        Route::get('receive-destination/view-print', [MmReceiveDestinationController::class, 'viewPrint'])->name('rd.vp');
        Route::get('analysis-code', [MmAnalysisCodeController::class, 'index'])->name('ac.index');
        Route::get('analysis-code/view-print', [MmAnalysisCodeController::class, 'viewPrint'])->name('ac.vp');
    });

    Route::prefix('material-management/system-requirement/inventory-setup')->name('mm.sr.is.')->group(function () {
        Route::get('category', [MmCategoryController::class, 'index'])->name('category.index');
        Route::get('category/view-print', [MmCategoryController::class, 'viewPrint'])->name('category.vp');
        Route::get('location', [MmLocationController::class, 'indexView'])->name('location.index');
        Route::get('location/view-print', [MmLocationController::class, 'viewPrint'])->name('location.vp');
        Route::get('unit', [MmUnitController::class, 'index'])->name('unit.index');
        Route::get('unit/view-print', [MmUnitController::class, 'viewPrint'])->name('unit.vp');
    });
});

// Report Group Routes
Route::prefix('material-management/system-requirement')->group(function () {
    Route::get('/report-groups', [MmReportGroupController::class, 'index'])->name('report-groups.index');
    Route::get('/report-groups/list', [MmReportGroupController::class, 'getReportGroups'])->name('report-groups.list');
    Route::post('/report-groups', [MmReportGroupController::class, 'store'])->name('report-groups.store');
    Route::get('/report-groups/{id}', [MmReportGroupController::class, 'show'])->name('report-groups.show');
    Route::put('/report-groups/{id}', [MmReportGroupController::class, 'update'])->name('report-groups.update');
    Route::delete('/report-groups/{id}', [MmReportGroupController::class, 'destroy'])->name('report-groups.destroy');
});

// Add a direct route for the inventory-setup folder structure
Route::get('/material-management/system-requirement/inventory-setup/report-group', [MmReportGroupController::class, 'index'])->name('inventory-setup.report-group');
Route::get('/material-management/system-requirement/inventory-setup/report-group/view-print', [MmReportGroupController::class, 'viewPrint'])->name('inventory-setup.report-group.view-print');

// API Routes for Vue components
Route::prefix('api')->group(function () {
    // User API routes
    Route::get('/users', [UserController::class, 'searchUsers']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::get('/users/{user}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('/users/{user}/permissions', [UserController::class, 'updateAccess']);
    Route::post('/users/update-password', [UserController::class, 'updatePassword']);
    
    // Material Management Config API routes
    Route::get('/mm-config', [MmConfigController::class, 'apiGetConfig']);
    Route::post('/mm-config', [MmConfigController::class, 'apiUpdateConfig']);
    
    // Roll Trim By Product Design API routes
    Route::get('/roll-trims-by-product-design', [RollTrimByProductDesignController::class, 'apiIndex']);
    Route::post('/roll-trims-by-product-design', [RollTrimByProductDesignController::class, 'apiStore']);
    Route::put('/roll-trims-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiUpdate']);
    Route::delete('/roll-trims-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiDestroy']);
    Route::get('/roll-trims-by-product-design/export', [RollTrimByProductDesignController::class, 'apiExport']);
    Route::post('/roll-trims-by-product-design/batch', [RollTrimByProductDesignController::class, 'apiBatchUpdate']);
    
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

Route::resource('sales-person-teams', SalespersonTeamController::class);
Route::resource('update-customer-accounts', UpdateCustomerAccountController::class);
Route::resource('mm-configs', MmConfigController::class);

Route::get('/material-management/system-requirement/standard-setup/configuration', [MmConfigController::class, 'index'])->name('mm.config');
Route::get('/material-management/system-requirement/standard-setup/control-period', [MmControlPeriodController::class, 'index'])->name('mm.control-period');
Route::get('/material-management/system-requirement/standard-setup/transaction-type', [MmTransactionTypeController::class, 'index'])->name('mm.transaction-type');
Route::get('/material-management/system-requirement/standard-setup/tax-type', [MmTaxTypeController::class, 'index'])->name('mm.tax-type');
Route::get('/material-management/system-requirement/standard-setup/tax-group', [MmTaxGroupController::class, 'index'])->name('mm.tax-group');
Route::get('/material-management/system-requirement/standard-setup/tax-group/view-print', [MmTaxGroupController::class, 'viewPrint'])->name('mm.tax-group.view-print');
Route::get('/material-management/system-requirement/standard-setup/receive-destination', [MmReceiveDestinationController::class, 'index'])->name('mm.receive-destination');
Route::get('/material-management/system-requirement/standard-setup/receive-destination/view-print', [MmReceiveDestinationController::class, 'viewPrint'])->name('mm.receive-destination.view-print');
Route::get('/material-management/system-requirement/standard-setup/analysis-code', [MmAnalysisCodeController::class, 'index'])->name('mm.analysis-code');
Route::get('/material-management/system-requirement/standard-setup/analysis-code/view-print', [MmAnalysisCodeController::class, 'viewPrint'])->name('mm.analysis-code.view-print');
Route::get('/material-management/system-requirement/standard-setup/control-period/view-print', [\App\Http\Controllers\MaterialManagement\SystemRequirement\MmControlPeriodController::class, 'viewPrint'])->name('mm.control-period.view-print');
Route::get('/material-management/system-requirement/standard-setup/transaction-type/view-print', [\App\Http\Controllers\MaterialManagement\SystemRequirement\MmTransactionTypeController::class, 'viewPrint'])->name('mm.transaction-type.view-print');
Route::get('/material-management/system-requirement/standard-setup/tax-type/view-print', [\App\Http\Controllers\MaterialManagement\SystemRequirement\MmTaxTypeController::class, 'viewPrint'])->name('mm.tax-type.view-print');

// Material Management Routes
Route::prefix('material-management')->group(function () {
    Route::prefix('system-requirement')->group(function () {
        Route::prefix('inventory-setup')->group(function () {
            // GL Distribution Routes
            Route::get('/mm-gl-distribution', [MmGlDistributionController::class, 'index'])->name('mm-gl-distribution.index');
            Route::get('/mm-gl-distribution/print', [MmGlDistributionController::class, 'print'])->name('mm-gl-distribution.print');
        });
    });
});

// Material Management - System Requirement - Inventory Setup
Route::get('mm-gl-distribution', [MmGlDistributionController::class, 'index'])->name('mm-gl-distribution.index');
Route::get('mm-gl-distribution/list', [MmGlDistributionController::class, 'getGlDistributions'])->name('mm-gl-distribution.list');
Route::post('mm-gl-distribution', [MmGlDistributionController::class, 'store'])->name('mm-gl-distribution.store');
Route::get('mm-gl-distribution/{id}', [MmGlDistributionController::class, 'show'])->name('mm-gl-distribution.show');
Route::put('mm-gl-distribution/{id}', [MmGlDistributionController::class, 'update'])->name('mm-gl-distribution.update');
Route::delete('mm-gl-distribution/{id}', [MmGlDistributionController::class, 'destroy'])->name('mm-gl-distribution.destroy');
Route::get('mm-gl-distribution/view-print', [MmGlDistributionController::class, 'viewPrint'])->name('mm-gl-distribution.view-print');

Route::prefix('material-management/system-requirement/inventory-setup')->name('inventory-setup.')->middleware(['auth'])->group(function () {
    Route::get('category', [MmCategoryController::class, 'index'])->name('category.index');
    Route::get('category/view-print', [MmCategoryController::class, 'viewPrint'])->name('category.vp');
    Route::get('location', [MmLocationController::class, 'indexView'])->name('location.index');
    Route::get('location/view-print', [MmLocationController::class, 'viewPrint'])->name('location.vp');
    Route::get('unit', [MmUnitController::class, 'index'])->name('unit.index');
    Route::get('unit/view-print', [MmUnitController::class, 'viewPrint'])->name('unit.vp');
    
    // GL Distribution
    // Route::get('gl-distribution', [MmGlDistributionController::class, 'index'])->name('gl-distribution.index');
    // Route::get('gl-distribution/list', [MmGlDistributionController::class, 'getGlDistributions'])->name('gl-distribution.list');
    // Route::post('gl-distribution', [MmGlDistributionController::class, 'store'])->name('gl-distribution.store');
    // Route::get('gl-distribution/{id}', [MmGlDistributionController::class, 'show'])->name('gl-distribution.show');
    // Route::put('gl-distribution/{id}', [MmGlDistributionController::class, 'update'])->name('gl-distribution.update');
    // Route::delete('gl-distribution/{id}', [MmGlDistributionController::class, 'destroy'])->name('gl-distribution.destroy');
    // Route::get('gl-distribution/view-print', [MmGlDistributionController::class, 'viewPrint'])->name('gl-distribution.view-print');
});

// GL Distribution Routes
Route::prefix('material-management/system-requirement')->group(function () {
    Route::get('/gl-distributions', [MmGlDistributionController::class, 'index'])->name('gl-distributions.index');
    Route::get('/gl-distributions/list', [MmGlDistributionController::class, 'getGlDistributions'])->name('gl-distributions.list');
    Route::post('/gl-distributions', [MmGlDistributionController::class, 'store'])->name('gl-distributions.store');
    Route::get('/gl-distributions/{id}', [MmGlDistributionController::class, 'show'])->name('gl-distributions.show');
    Route::put('/gl-distributions/{id}', [MmGlDistributionController::class, 'update'])->name('gl-distributions.update');
    Route::delete('/gl-distributions/{id}', [MmGlDistributionController::class, 'destroy'])->name('gl-distributions.destroy');
});

// Add direct route for the inventory-setup folder structure
Route::get('/material-management/system-requirement/inventory-setup/gl-distribution', [MmGlDistributionController::class, 'index'])->name('material-management.system-requirement.inventory-setup.gl-distribution');
Route::get('/material-management/system-requirement/inventory-setup/gl-distribution/view-print', [MmGlDistributionController::class, 'viewPrint'])->name('material-management.system-requirement.inventory-setup.gl-distribution.view-print');

// Add SKU routes
Route::get('/material-management/system-requirement/inventory-setup/sku', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/Sku');
})->name('material-management.system-requirement.inventory-setup.sku');

Route::get('/material-management/system-requirement/inventory-setup/sku/print', [MmSkuController::class, 'printSelected'])->name('material-management.system-requirement.inventory-setup.sku.print');

Route::get('/material-management/system-requirement/inventory-setup/sku/view-print', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintSku');
})->name('material-management.system-requirement.inventory-setup.sku.view-print');

Route::get('/material-management/system-requirement/inventory-setup/amend-sku-type', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/AmendSkuType');
})->name('material-management.system-requirement.inventory-setup.amend-sku-type');

Route::get('/material-management/system-requirement/inventory-setup/sku-price', [
    \App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'index'
])->name('sku-price.index');

Route::put('/material-management/system-requirement/inventory-setup/sku-price/{sku}', [
    \App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'update'
])->name('sku-price.update');

Route::get('/material-management/system-requirement/inventory-setup/sku-price/view-print', [
    \App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'viewPrint'
])->name('sku-price.view-print');

Route::get('/api/sku-price/search', [
    \App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'search'
])->name('sku-price.search');

Route::get('/material-management/system-requirement/inventory-setup/sku-price/view-print', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintSkuPrice');
})->name('material-management.system-requirement.inventory-setup.sku-price.view-print');

Route::get('/material-management/system-requirement/inventory-setup/amend-sku', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/AmendSku');
})->name('material-management.system-requirement.inventory-setup.amend-sku');

Route::get('/material-management/system-requirement/inventory-setup/obsolete-reactive-sku', function() {
    return Inertia::render('material-management/system-requirement/inventory-setup/ObsoleteReactiveSku');
})->name('material-management.system-requirement.inventory-setup.obsolete-reactive-sku');

// Route::get('colors-export', [ColorController::class, 'export'])->name('colors.export');
// Route::get('color-groups-export', [ColorGroupController::class, 'export'])->name('color-groups.export');

// Add route for CustomerWarehouseRequirement
Route::get('/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-requirement', [CustomerWarehouseRequirementController::class, 'index'])->name('vue.warehouse-management.finished-goods.setup-maintenance.define-customer-warehouse-requirement');

// Update MC Routes
Route::prefix('update-mc')->group(function () {
    Route::get('/', [UpdateMcController::class, 'index'])->name('update-mc.index');
    Route::post('/search-ac', [UpdateMcController::class, 'searchAc'])->name('update-mc.search-ac');
    Route::post('/search-mcs', [UpdateMcController::class, 'searchMcs'])->name('update-mc.search-mcs');
    Route::get('/master-cards', [UpdateMcController::class, 'apiIndex'])->name('update-mc.master-cards');
});
