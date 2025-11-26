<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\AnalysisCodeController;
use App\Http\Controllers\StitchWireController;
use App\Http\Controllers\ChemicalCoatController;
use App\Http\Controllers\ReinforcementTapeController;
use App\Http\Controllers\BundlingStringController;
use App\Http\Controllers\WrappingMaterialController;
use App\Http\Controllers\GlueingMaterialController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ColorGroupController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\SalesManagement\SystemRequirement\SystemRequirementController;
use App\Http\Controllers\UpdateCustomerAccountController;
use App\Http\Controllers\CustomerAlternateAddressController;
use App\Http\Controllers\UpdateMcController;
use App\Http\Controllers\RealeseApproveMcController;
// use App\Http\Controllers\SOConfigController; // TODO: Controller tidak ditemukan
use App\Http\Controllers\ScoringFormulaController;
use App\Http\Controllers\CustomerSalesTypeController;
use App\Http\Controllers\CustomerWarehouseRequirementController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleClassController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\SalesManagement\SalesOrder\Report\SalesOrderReportController;

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

// Test Geo API
Route::get('/test-geo-api', function () {
try {
$geos = \App\Models\Geo::orderBy('CODE')->take(5)->get();

$transformed = $geos->map(function($geo) {
return [
'code' => $geo->CODE,
'country' => $geo->COUNTRY,
'state' => $geo->STATE,
'town' => $geo->TOWN,
'town_section' => $geo->TOWN_SECTION,
'area' => $geo->AREA
];
});

return response()->json([
'success' => true,
'count' => \App\Models\Geo::count(),
'sample_data' => $transformed,
'raw_first' => $geos->first()
]);
} catch (\Exception $e) {
return response()->json([
'success' => false,
'error' => $e->getMessage(),
'trace' => $e->getTraceAsString()
], 500);
}
});

// Guest Routes
Route::middleware('guest')->group(function () {
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

// Logout Route (accessible to authenticated users)
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

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
Route::post('/system-security/update-password', [UserController::class, 'updatePassword'])->name('vue.system-security.update-password');
Route::get('/system-security/define-access', [UserController::class, 'vueDefineAccess'])->name('vue.system-security.define-access');
Route::post('/user-permissions/{userId}', [UserController::class, 'updateUserPermissions'])->name('user-permissions.update');
Route::get('/system-security/copy-paste-access', [UserController::class, 'vueCopyPasteAccess'])->name('vue.system-security.copy-paste-access');
Route::get('/system-security/reactive-unobsolete-user', function () {
    return Inertia::render('system-manager/ReactiveUnobsoleteUser');
})->name('vue.system-security.reactive-unobsolete-user');
Route::get('/system-security/view-print-user', [UserController::class, 'vueViewPrintUser'])->name('vue.system-security.view-print-user');

// Sales Management Routes

        // Sales Order Setup - Define SO Config
         // TODO: SOConfigController tidak ditemukan, perlu dibuat
         Route::get('/sales-order/setup/define-so-config', function () {
             return Inertia::render('sales-management/sales-order/setup/define-so-config');
         })->name('vue.sales-order.setup.define-so-config');

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

// Sales Order Transaction - Prepare MC SO
        Route::get('/sales-order/transaction/prepare-mc-so', function () {
        return Inertia::render('sales-management/sales-order/Transaction/PrepareMCSO');
        })->name('vue.sales-order.transaction.prepare-mc-so');

        // Sales Order Product Design API (with CSRF protection)
        Route::post('/api/sales-order/product-design', [App\Http\Controllers\SalesOrderController::class, 'saveProductDesign'])->middleware('auth');

        // Sales Order Delivery Schedule API (with CSRF protection)
        Route::post('/api/sales-order/delivery-schedule', [App\Http\Controllers\SalesOrderController::class, 'saveDeliverySchedule'])->middleware('auth');

        // Sales Order Transaction - Prepare SB SO
        Route::get('/sales-order/transaction/prepare-sb-so', function () {
            return Inertia::render('sales-management/sales-order/Transaction/PrepareSBSO');
        })->name('vue.sales-order.transaction.prepare-sb-so');

// Sales Order Transaction - Print SO
Route::get('/sales-order/transaction/print-so', function () {
return Inertia::render('sales-management/sales-order/Transaction/PrintSO');
})->name('vue.sales-order.transaction.print-so');

 // Sales Order Transaction - Cancel SO
        Route::get('/sales-order/transaction/cancel-so', function () {
            return Inertia::render('sales-management/sales-order/Transaction/CancelSO');
        })->name('vue.sales-order.transaction.cancel-so');

                // Sales Order Transaction - Amend SO
        Route::get('/sales-order/transaction/amend-so', function () {
            return Inertia::render('sales-management/sales-order/Transaction/AmendSO');
        })->name('vue.sales-order.transaction.amend-so');

        // Sales Order API routes for reports
        Route::get('/api/sales-order/print-log', [SalesOrderController::class, 'printLog']);
        Route::get('/api/sales-order/print-jit-tracking', [SalesOrderController::class, 'printJitTracking']);

        // Get current authenticated user info (for PrintSO.vue)
        Route::get('/api/user/current', function() {
            // Clean any output buffers to prevent BOM issues
            while (ob_get_level()) {
                ob_end_clean();
            }

            $user = Auth::user();
            if (!$user) {
                Log::warning('User not authenticated in /api/user/current');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401, ['Content-Type' => 'application/json; charset=utf-8']);
            }

            // Log user data for debugging
            Log::info('Web /api/user/current - Current user data:', [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'official_name' => $user->official_name,
                'official_title' => $user->official_title
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->user_id ?? $user->username ?? 'N/A',
                    'username' => $user->username ?? 'N/A',
                    'official_name' => $user->official_name ?? $user->name ?? 'N/A',
                    'official_title' => $user->official_title ?? 'N/A'
                ]
            ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
        });

        // Print SO API routes
        Route::post('/api/so-report', [SalesOrderReportController::class, 'apiGenerateSoReport']);
        Route::get('/api/sales-order/{soNumber}/delivery-schedules', [SalesOrderController::class, 'getDeliverySchedules']);

        // Sales Order API routes
Route::post('/api/sales-order', [SalesOrderController::class, 'store']);
Route::get('/api/sales-order/master-card/{mcSeq}', [SalesOrderController::class, 'getMasterCard']);
        Route::get('/api/sales-order/product-design/{masterCardSeq}', [SalesOrderController::class, 'getProductDesignData']);
Route::get('/api/sales-order/salesperson/{salespersonCode}', [SalesOrderController::class, 'getSalesperson']);
        Route::post('/api/sales-order/product-design', [SalesOrderController::class, 'saveProductDesign']);
        Route::post('/api/sales-order/delivery-schedule', [SalesOrderController::class, 'saveDeliverySchedule']);
        Route::get('/api/sales-order/{soNumber}/delivery-schedule-summary', [SalesOrderController::class, 'getDeliveryScheduleSummary']);
Route::get('/api/sales-orders', [SalesOrderController::class, 'getSalesOrders']);

// Delivery Order API routes (use web.php to keep session/CSRF context)
Route::post('/api/delivery-orders', [DeliveryOrderController::class, 'store']);
// Print Delivery Order (preview range / single DO)
Route::get('/api/delivery-orders/print-range', [DeliveryOrderController::class, 'getPrintRange']);

// Delivery Order Transaction - Prepare Delivery Order (Multiple Item)
Route::get('/delivery-order/transaction/prepare-delivery-order-multiple-item', function () {
return Inertia::render('warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem');
})->name('vue.delivery-order.transaction.prepare-delivery-order-multiple-item');

// Delivery Order Transaction routes moved to warehouse management section

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
Route::get('/sales-team/status', [SalesTeamController::class, 'vueManageStatus'])->name('vue.sales-team.status');
Route::get('/sales-team/view-print', [SalesTeamController::class, 'vueViewAndPrint'])->name('vue.sales-team.view-print');
// Alias for search menu
Route::get('/define-sales-team', [SalesTeamController::class, 'vueIndex'])->name('vue.define-sales-team');

Route::get('/sales-person', [SalespersonController::class, 'vueIndex'])->name('vue.sales-person.index');
Route::get('/sales-person/status', [SalespersonController::class, 'vueManageStatus'])->name('vue.sales-person.status');
Route::get('/sales-person/view-print', [SalespersonController::class, 'vueViewAndPrint'])->name('vue.sales-person.view-print');
// Alias for search menu
Route::get('/define-salesperson', [SalespersonController::class, 'vueIndex'])->name('vue.define-salesperson');
Route::get('/view-print-salesperson', [SalespersonController::class, 'vueViewAndPrint'])->name('vue.view-print-salesperson');

Route::get('/sales-person-team', [SalespersonTeamController::class, 'vueIndex'])->name('vue.sales-person-team.index');
Route::get('/sales-person-team/status', [SalespersonTeamController::class, 'vueManageStatus'])->name('vue.sales-person-team.status');
Route::get('/sales-person-team/view-print', [SalespersonTeamController::class, 'vueViewAndPrint'])->name('vue.sales-person-team.view-print');
// Alias for search menu
Route::get('/define-salesperson-team', [SalespersonTeamController::class, 'vueIndex'])->name('vue.define-salesperson-team');

Route::get('/industry', [IndustryController::class, 'vueIndex'])->name('vue.industry.index');
Route::get('/industry/status', [IndustryController::class, 'vueManageStatus'])->name('vue.industry.status');
Route::get('/industry/view-print', [IndustryController::class, 'vueViewAndPrint'])->name('vue.industry.view-print');
// Alias for search menu
Route::get('/define-industry', [IndustryController::class, 'vueIndex'])->name('vue.define-industry');

Route::get('/geo', [GeoController::class, 'vueIndex'])->name('vue.geo.index');
Route::get('/geo/status', [GeoController::class, 'vueManageStatus'])->name('vue.geo.status');
Route::get('/geo/view-print', [GeoController::class, 'vueViewAndPrint'])->name('vue.geo.view-print');
// Alias for search menu
Route::get('/define-geo', [GeoController::class, 'vueIndex'])->name('vue.define-geo');

Route::get('/product-group', [ProductGroupController::class, 'vueIndex'])->name('vue.product-group.index');
Route::get('/product-group/status', [ProductGroupController::class, 'vueManageStatus'])->name('vue.product-group.status');
Route::get('/product-group/view-print', [ProductGroupController::class, 'vueViewAndPrint'])->name('vue.product-group.view-print');
// Alias for search menu
Route::get('/define-product-group', [ProductGroupController::class, 'vueIndex'])->name('vue.define-product-group');

Route::get('/product', [ProductController::class, 'vueIndex'])->name('vue.product.index');
Route::get('/product/status', [ProductController::class, 'vueManageStatus'])->name('vue.product.status');
Route::get('/product/view-print', [ProductController::class, 'vueViewAndPrint'])->name('vue.product.view-print');
// Alias for search menu
Route::get('/define-product', [ProductController::class, 'vueIndex'])->name('vue.define-product');

Route::get('/product-design', [ProductDesignController::class, 'vueIndex'])->name('vue.product-design.index');
Route::get('/product-design/status', [ProductDesignController::class, 'vueManageStatus'])->name('vue.product-design.status');
Route::get('/product-design/view-print', [ProductDesignController::class, 'vueViewAndPrint'])->name('vue.product-design.view-print');
// Alias for search menu
Route::get('/define-product-design', [ProductDesignController::class, 'vueIndex'])->name('vue.define-product-design');

Route::get('/scoring-tool', [ScoringToolController::class, 'vueIndex'])->name('vue.scoring-tool.index');
Route::get('/scoring-tool/status', [ScoringToolController::class, 'vueManageStatus'])->name('vue.scoring-tool.status');
Route::get('/scoring-tool/view-print', [ScoringToolController::class, 'vueViewAndPrint'])->name('vue.scoring-tool.view-print');
// Alias for search menu
Route::get('/define-scoring-tool', [ScoringToolController::class, 'vueIndex'])->name('vue.define-scoring-tool');

Route::get('/paper-quality', [PaperQualityController::class, 'vueIndex'])->name('vue.paper-quality.index');
Route::get('/paper-quality/status', [PaperQualityController::class, 'vueManageStatus'])->name('vue.paper-quality.status');
Route::get('/paper-quality/view-print', [PaperQualityController::class, 'vueViewAndPrint'])->name('vue.paper-quality.view-print');
// Alias for search menu
Route::get('/define-paper-quality', [PaperQualityController::class, 'vueIndex'])->name('vue.define-paper-quality');

Route::get('/paper-flute', [PaperFluteController::class, 'vueIndex'])->name('vue.paper-flute.index');
Route::get('/paper-flute/status', [PaperFluteController::class, 'vueManageStatus'])->name('vue.paper-flute.status');
Route::get('/paper-flute/view-print', [PaperFluteController::class, 'vueViewAndPrint'])->name('vue.paper-flute.view-print');
// Alias for search menu
Route::get('/define-paper-flute', [PaperFluteController::class, 'vueIndex'])->name('vue.define-paper-flute');

Route::get('/paper-size', [PaperSizeController::class, 'vueIndex'])->name('vue.paper-size.index');
Route::get('/paper-size/status', [PaperSizeController::class, 'vueManageStatus'])->name('vue.paper-size.status');
Route::get('/paper-size/view-print', [PaperSizeController::class, 'vueViewAndPrint'])->name('vue.paper-size.view-print');
// Alias for search menu
Route::get('/define-paper-size', [PaperSizeController::class, 'vueIndex'])->name('vue.define-paper-size');

Route::get('/color-group', [ColorGroupController::class, 'index'])->name('vue.color-group.index');
Route::get('/color-group/status', [ColorGroupController::class, 'vueManageStatus'])->name('vue.color-group.status');
Route::get('/color-group/view-print', [ColorGroupController::class, 'vueViewAndPrint'])->name('vue.color-group.view-print');
// Alias for search menu
Route::get('/define-color-group', [ColorGroupController::class, 'index'])->name('vue.define-color-group');

Route::get('/color', [ColorController::class, 'vueIndex'])->name('color.index');
Route::get('/color/status', [ColorController::class, 'vueManageStatus'])->name('color.status');
Route::get('/color/view-print', [ColorController::class, 'vueViewAndPrint'])->name('color.view-print');
// Alias for search menu
Route::get('/define-color', [ColorController::class, 'vueIndex'])->name('vue.define-color');

         Route::get('/finishing', [FinishingController::class, 'vueIndex'])->name('vue.finishing.index');
         Route::get('/finishing/status', [FinishingController::class, 'vueManageStatus'])->name('vue.finishing.status');
         Route::get('/finishing/view-print', [FinishingController::class, 'vueViewAndPrint'])->name('vue.finishing.view-print');
         // Alias for search menu
         Route::get('/define-finishing', [FinishingController::class, 'vueIndex'])->name('vue.define-finishing');

Route::get('/analysis-code', [AnalysisCodeController::class, 'index'])->name('vue.analysis-code.index');
Route::get('/analysis-code/status', [AnalysisCodeController::class, 'vueManageStatus'])->name('vue.analysis-code.status');
Route::get('/analysis-code/view-print', function () {
    return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-analysis-code');
})->name('vue.analysis-code.view-print');
// Alias for search menu
Route::get('/define-analysis-code', [AnalysisCodeController::class, 'index'])->name('vue.define-analysis-code');

         Route::get('/stitch-wire', [StitchWireController::class, 'vueIndex'])->name('vue.stitch-wire.index');
         Route::get('/stitch-wire/status', [StitchWireController::class, 'vueManageStatus'])->name('vue.stitch-wire.status');
         Route::get('/stitch-wire/view-print', [StitchWireController::class, 'vueViewAndPrint'])->name('vue.stitch-wire.view-print');
         // Alias for search menu
         Route::get('/define-stitch-wire', [StitchWireController::class, 'vueIndex'])->name('vue.define-stitch-wire');

         Route::get('/chemical-coat', [ChemicalCoatController::class, 'vueIndex'])->name('vue.chemical-coat.index');
         Route::get('/chemical-coat/view-print', [ChemicalCoatController::class, 'vueViewAndPrint'])->name('vue.chemical-coat.view-print');
         // Alias for search menu
         Route::get('/define-chemical-coat', [ChemicalCoatController::class, 'vueIndex'])->name('vue.define-chemical-coat');

         Route::get('/reinforcement-tape', [ReinforcementTapeController::class, 'index'])->name('vue.reinforcement-tape.index');
         Route::get('/reinforcement-tape/view-print', [ReinforcementTapeController::class, 'vueViewAndPrint'])->name('vue.reinforcement-tape.view-print');
         // Alias for search menu
         Route::get('/define-reinforcement-tape', [ReinforcementTapeController::class, 'index'])->name('vue.define-reinforcement-tape');

         Route::get('/bundling-string', [BundlingStringController::class, 'index'])->name('vue.bundling-string.index');
         Route::get('/bundling-string/view-print', [BundlingStringController::class, 'vueViewAndPrint'])->name('vue.bundling-string.view-print');
         // Alias for search menu
         Route::get('/define-bundling-string', [BundlingStringController::class, 'index'])->name('vue.define-bundling-string');

         Route::get('/wrapping-material', [WrappingMaterialController::class, 'index'])->name('vue.wrapping-material.index');
         Route::get('/wrapping-material/view-print', [WrappingMaterialController::class, 'vueViewAndPrint'])->name('vue.wrapping-material.view-print');
         // Alias for search menu
         Route::get('/define-wrapping-material', [WrappingMaterialController::class, 'index'])->name('vue.define-wrapping-material');

         Route::get('/glueing-material', [GlueingMaterialController::class, 'index'])->name('vue.glueing-material.index');
         Route::get('/glueing-material/view-print', [GlueingMaterialController::class, 'vueViewAndPrint'])->name('vue.glueing-material.view-print');
         // Alias for search menu
         Route::get('/define-glueing-material', [GlueingMaterialController::class, 'index'])->name('vue.define-glueing-material');

         Route::get('/machine', [MachineController::class, 'index'])->name('vue.machine.index');
         Route::post('/machine', [MachineController::class, 'store'])->name('vue.machine.store');
         Route::put('/machine/{id}', [MachineController::class, 'update'])->name('vue.machine.update');
         Route::delete('/machine/{id}', [MachineController::class, 'destroy'])->name('vue.machine.destroy');
         Route::get('/machine/view-print', [MachineController::class, 'vueViewAndPrint'])->name('vue.machine.view-print');
         // Alias for search menu
         Route::get('/define-machine', [MachineController::class, 'index'])->name('vue.define-machine');
         Route::get('/view-and-print-machine', [MachineController::class, 'vueViewAndPrint'])->name('vue.view-and-print-machine');

// Customer Account Routes
Route::get('/customer-group', function () {
return Inertia::render('sales-management/system-requirement/customer-account/customer-group');
})->name('vue.customer-group.index');

Route::get('/customer-group/view-print', [CustomerGroupController::class, 'vueViewAndPrint'])->name('vue.customer-group.view-print');
// Alias for search menu
Route::get('/define-customer-group', function () {
return Inertia::render('sales-management/system-requirement/customer-account/customer-group');
})->name('vue.define-customer-group');

// Update Customer Account - Main route (searchable in menu)
Route::get('/update-customer-account', function () {
return Inertia::render('sales-management/system-requirement/customer-account/update-customer-account');
})->name('vue.update-customer-account.index');

// Alias for search menu (without 'index' suffix)
Route::get('/customer-account-update', function () {
return Inertia::render('sales-management/system-requirement/customer-account/update-customer-account');
})->name('vue.update-customer-account-management');

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
Route::get('/api/update-mc/check-mcs/{mcsNumber}', [UpdateMcController::class, 'checkMcs']);

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
Route::get('/sales-management/system-requirement/master-card/obsolete-reactive-mc', [UpdateMcController::class, 'obsoleteReactiveIndex'])->name('vue.master-card.obsolete-reactive-mc');

// Add route for view-and-print-MC
Route::get('/sales-management/system-requirement/master-card/view-and-print-MC', [UpdateMcController::class, 'viewAndPrint'])->name('vue.master-card.view-and-print-mc');

// Add route for view-and-print-mc-maintenance-log
Route::get('/sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log', [UpdateMcController::class, 'viewAndPrintMcMaintenanceLog'])->name('vue.master-card.view-and-print-mc-maintenance-log');

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

// Warehouse Management Routes
Route::get('/warehouse-management/finished-goods', function () {
return Inertia::render('warehouse-management/FinishedGoods/index');
})->name('vue.warehouse-management.finished-goods');

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

// Warehouse Management - Delivery Order - Setup - Vehicle
Route::get('/warehouse-management/delivery-order/setup/vehicle', [VehicleController::class, 'index'])
->name('vue.warehouse-management.delivery-order.setup.vehicle');
Route::get('/warehouse-management/delivery-order/setup/vehicle/view-print', [VehicleController::class, 'viewPrint'])
->name('vue.warehouse-management.delivery-order.setup.vehicle.view-print');
Route::get('/warehouse-management/delivery-order/setup/obsolete-unobsolete-vehicle', function () {
return Inertia::render('warehouse-management/DeliveryOrder/Setup/ObsoleteUnobsoleteVehicle');
})->name('vue.warehouse-management.delivery-order.setup.obsolete-unobsolete-vehicle');

// Warehouse Management - Delivery Order - Setup - Vehicle Class
Route::get('/warehouse-management/delivery-order/setup/vehicle-class', [VehicleClassController::class, 'index'])
->name('vue.warehouse-management.delivery-order.setup.vehicle-class');
Route::get('/warehouse-management/delivery-order/setup/vehicle-class/view-print', [VehicleClassController::class, 'viewPrint'])
->name('vue.warehouse-management.delivery-order.setup.vehicle-class.view-print');

Route::get('/warehouse-management/delivery-order/setup/obsolete-unobsolete-vehicle-class', function () {
return Inertia::render('warehouse-management/DeliveryOrder/Setup/ObsoleteUnobsoleteVehicleClass');
})->name('vue.warehouse-management.delivery-order.setup.obsolete-unobsolete-vehicle-class');

// Warehouse Management - Delivery Order - DO Processing
Route::get('/warehouse-management/delivery-order/do-processing/prepare-multiple', function () {
return Inertia::render('warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem');
})->name('vue.warehouse-management.delivery-order.do-processing.prepare-multiple');

Route::get('/warehouse-management/delivery-order/do-processing/amend-delivery-order', function () {
return Inertia::render('warehouse-management/DeliveryOrder/DOProcessing/AmendDeliveryOrder');
})->name('vue.warehouse-management.delivery-order.do-processing.amend-delivery-order');

Route::get('/warehouse-management/delivery-order/do-processing/cancel-delivery-order', function () {
return Inertia::render('warehouse-management/DeliveryOrder/DOProcessing/CancelDeliveryOrder');
})->name('vue.warehouse-management.delivery-order.do-processing.cancel-delivery-order');

Route::get('/warehouse-management/delivery-order/do-processing/print-delivery-order', function () {
return Inertia::render('warehouse-management/DeliveryOrder/DOProcessing/PrintDeliveryOrder');
})->name('vue.warehouse-management.delivery-order.do-processing.print-delivery-order');

Route::get('/warehouse-management/invoice', function () {
return Inertia::render('warehouse-management/Invoice/index');
})->name('vue.warehouse-management.invoice');
// Invoice → IV Processing → Amend Invoice (Vue page)
Route::get('/warehouse-management/invoice/iv-processing/amend-invoice', function () {
return Inertia::render('warehouse-management/Invoice/IVProcessing/AmendInvoice');
})->name('vue.warehouse-management.invoice.iv-processing.amend-invoice');

Route::get('/warehouse-management/invoice/iv-processing/cancel-active-invoice', function () {
return Inertia::render('warehouse-management/Invoice/IVProcessing/CancelActiveInvoice');
})->name('vue.warehouse-management.invoice.iv-processing.cancel-active-invoice');

Route::get('/warehouse-management/invoice/iv-processing/print-invoice', function () {
return Inertia::render('warehouse-management/Invoice/IVProcessing/PrintInvoice');
})->name('vue.warehouse-management.invoice.iv-processing.print-invoice');

// Invoice → Setup pages (Vue)
Route::get('/warehouse-management/invoice/setup/define-tax-group', function () {
return Inertia::render('warehouse-management/Invoice/Setup/DefineTaxGroup');
})->name('vue.warehouse-management.invoice.setup.define-tax-group');

Route::get('/warehouse-management/invoice/setup/define-tax-type', function () {
return Inertia::render('warehouse-management/Invoice/Setup/DefineTaxType');
})->name('vue.warehouse-management.invoice.setup.define-tax-type');

Route::get('/warehouse-management/invoice/setup/define-customer-sales-tax-index', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'index'])->name('vue.warehouse-management.invoice.setup.define-customer-sales-tax-index');

// Invoice → Setup → View & Print (Tax masters)
Route::get('/warehouse-management/invoice/setup/print-tax-type', function () {
return Inertia::render('warehouse-management/Invoice/Setup/PrintTaxType');
})->name('vue.warehouse-management.invoice.setup.print-tax-type');

Route::get('/warehouse-management/invoice/setup/print-tax-group', function () {
return Inertia::render('warehouse-management/Invoice/Setup/PrintTaxGroup');
})->name('vue.warehouse-management.invoice.setup.print-tax-group');

Route::get('/warehouse-management/invoice/setup/print-customer-sales-tax-index', function () {
return Inertia::render('warehouse-management/Invoice/Setup/PrintCustomerSalesTaxIndex');
})->name('vue.warehouse-management.invoice.setup.print-customer-sales-tax-index');

Route::get('/warehouse-management/invoice/setup/invoice-configuration', function () {
return Inertia::render('warehouse-management/Invoice/Setup/SetupInvoiceConfiguration');
})->name('vue.warehouse-management.invoice.setup.invoice-configuration');

Route::get('/warehouse-management/debit-credit-note', function () {
return Inertia::render('warehouse-management/DebitCreditNote/index');
})->name('vue.warehouse-management.debit-credit-note');

// Warehouse Management - Invoice - IV Processing - Prepare by DO (Current Period)
Route::get('/warehouse-management/invoice/iv-processing/prepare-by-do-current-period', function () {
return Inertia::render('warehouse-management/Invoice/IVProcessing/PrepareInvoicebyDO');
})->name('vue.warehouse-management.invoice.iv-processing.prepare-by-do-current-period');

// Warehouse Management - Invoice - IV Processing - Prepare by DO (Open Period)
Route::get('/warehouse-management/invoice/iv-processing/prepare-by-do-open-period', function () {
return Inertia::render('warehouse-management/Invoice/IVProcessing/PrepareInvoicebyDO', [
  'openPeriod' => true,
]);
})->name('vue.warehouse-management.invoice.iv-processing.prepare-by-do-open-period');

// Backward-compatibility alias (old menu path)
Route::get('/warehouse-management/invoice/iv-processing/prepare-do-current', function () {
return redirect()->route('vue.warehouse-management.invoice.iv-processing.prepare-by-do-current-period');
});

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

// Salesperson Team API routes (using same controller)
Route::get('/salesperson-teams', [SalespersonController::class, 'getSalespersonTeams']);
Route::post('/salesperson-teams/assign', [SalespersonController::class, 'assignToTeam']);

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

// Product API routes moved to routes/api.php

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
Route::get('/color-groups', [ColorGroupController::class, 'apiIndex']);
Route::post('/color-groups', [ColorGroupController::class, 'store']);
Route::put('/color-groups/{code}', [ColorGroupController::class, 'update']);
Route::delete('/color-groups/{code}', [ColorGroupController::class, 'destroy']);
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
Route::get('/industry/api', [IndustryController::class, 'apiIndex']);
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

// Customer Sales Type API routes
Route::get('/customer-sales-types', [CustomerSalesTypeController::class, 'apiIndex']);
Route::post('/customer-sales-types', [CustomerSalesTypeController::class, 'apiStore']);
Route::put('/customer-sales-types/{id}', [CustomerSalesTypeController::class, 'apiUpdate']);
Route::delete('/customer-sales-types/{id}', [CustomerSalesTypeController::class, 'apiDestroy']);

// Color Group API routes (using ColorGroupController)
Route::get('/color-groups', [ColorGroupController::class, 'apiIndex']);
Route::post('/color-groups', [ColorGroupController::class, 'store']);
Route::put('/color-groups/{code}', [ColorGroupController::class, 'update']);
Route::delete('/color-groups/{code}', [ColorGroupController::class, 'destroy']);
Route::post('/color-groups/seed', [ColorGroupController::class, 'seed']);

// Finishing API routes
Route::get('/finishings', [FinishingController::class, 'apiIndex']);
Route::post('/finishings', [FinishingController::class, 'store']);
Route::put('/finishings/{code}', [FinishingController::class, 'update']);
Route::delete('/finishings/{code}', [FinishingController::class, 'destroy']);
Route::post('/finishings/seed', [FinishingController::class, 'seed']);

// ApproveMC API routes
Route::get('/approve-mc', [UpdateMcController::class, 'apiIndex']);
Route::post('/approve-mc', [UpdateMcController::class, 'store']);
Route::put('/approve-mc/{id}', [UpdateMcController::class, 'store']);
Route::post('/approve-mc/approve/{id}', [UpdateMcController::class, 'store']);
Route::post('/approve-mc/reject/{id}', [UpdateMcController::class, 'store']);
Route::get('/approve-mc/by-customer/{customerId}', [UpdateMcController::class, 'apiIndex']);

// Update MC API routes
Route::get('/update-mc/master-cards', [UpdateMcController::class, 'apiIndex']);
Route::post('/update-mc/master-cards', [UpdateMcController::class, 'store']);
Route::get('/update-mc/master-cards/{mcSeq}', [UpdateMcController::class, 'apiShow']);
Route::get('/update-mc/check-mcs/{mcsNumber}', [UpdateMcController::class, 'checkMcs']);

// RealeseApproveMC API routes
Route::get('/realese-approve-mc', [RealeseApproveMcController::class, 'apiIndex']);
Route::post('/realese-approve-mc', [RealeseApproveMcController::class, 'store']);
Route::post('/realese-approve-mc/release/{id}', [RealeseApproveMcController::class, 'release']);
Route::post('/realese-approve-mc/unreleased/{id}', [RealeseApproveMcController::class, 'unreleased']);
Route::get('/realese-approve-mc/by-customer/{customerId}', [RealeseApproveMcController::class, 'getByCustomer']);

// ObsolateReactiveMC API routes
Route::get('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'apiIndex']);
Route::post('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'store']);
Route::post('/obsolate-reactive-mc/obsolate/{id}', [ObsolateReactiveMcController::class, 'obsolate']);
Route::post('/obsolate-reactive-mc/reactive/{id}', [ObsolateReactiveMcController::class, 'reactive']);
Route::get('/obsolate-reactive-mc/by-customer/{customerId}', [ObsolateReactiveMcController::class, 'getByCustomer']);

// Delivery Order API routes
Route::get('/delivery-orders', [DeliveryOrderController::class, 'index']);
Route::post('/delivery-orders', [DeliveryOrderController::class, 'store']);
Route::get('/delivery-orders/{doNumber}', [DeliveryOrderController::class, 'show']);
Route::put('/delivery-orders/{doNumber}', [DeliveryOrderController::class, 'update']);
Route::post('/delivery-orders/{doNumber}/cancel', [DeliveryOrderController::class, 'cancel']);
Route::get('/delivery-orders/print-range', [DeliveryOrderController::class, 'getPrintRange']);
Route::get('/vehicles/{vehicleNumber}', [DeliveryOrderController::class, 'getVehicle']);

}); // Close Route::prefix('api') group

// Expose a concise list of navigable menu routes for header search
Route::get('/menu-routes', function () {
    $routes = [];
    $abbrMap = [
        'mc' => 'Master Card',
        'so' => 'Sales Order',
        'fg' => 'Finished Goods',
        'ac' => 'Account',
        'pd' => 'Product Design',
        'po' => 'Purchase Order',
    ];
    $crudExclude = ['index', 'create', 'edit'];
    $genericView = ['view', 'view-print', 'viewandprint', 'view-printing', 'viewandprinting', 'view_print'];

    $humanize = function (string $leaf) use ($abbrMap) {
        $tokens = preg_split('/[-_\s]+/', strtolower($leaf));
        $words = array_map(function ($t) use ($abbrMap) {
            if ($t === '') return '';
            if (isset($abbrMap[$t])) return $abbrMap[$t];
            if (strlen($t) <= 3 && preg_match('/^[a-z]+$/', $t)) return strtoupper($t);
            return ucwords($t);
        }, $tokens);
        return trim(preg_replace('/\s+/', ' ', implode(' ', $words)));
    };

    foreach (Route::getRoutes() as $route) {
        $name = $route->getName();
        $methods = $route->methods();
        if (!in_array('GET', $methods)) {
            continue;
        }
        $uri = '/' . ltrim($route->uri(), '/');
        if (str_starts_with($uri, '/api/')) {
            continue;
        }
        if ($name && str_contains($name, 'vue.')) {
            $parts = explode('.', $name);
            $leaf = end($parts) ?: $name;
            if (in_array(strtolower($leaf), $crudExclude, true)) {
                continue;
            }

            // Determine title; if leaf is generic view, prepend with previous meaningful segment
            $title = '';
            $leafLower = strtolower($leaf);

            // Custom title mappings for specific routes
            $customTitles = [
                'vue.update-customer-account-management' => 'Update Customer Account',
            ];

            if (isset($customTitles[$name])) {
                $title = $customTitles[$name];
            } elseif (in_array($leafLower, $genericView, true)) {
                // find previous non-generic segment
                $prev = '';
                for ($i = count($parts) - 2; $i >= 0; $i--) {
                    $candidate = $parts[$i];
                    if (!in_array(strtolower($candidate), array_merge($crudExclude, $genericView), true)) {
                        $prev = $candidate;
                        break;
                    }
                }
                $subject = $prev !== '' ? $humanize($prev) : 'Data';
                $title = 'View & Print ' . $subject;
            } else {
                $title = $humanize($leaf);
            }

            $routes[] = [
                'name'  => $name,
                'uri'   => $uri,
                'title' => $title,
            ];
        }
    }

    $routes = collect($routes)
        ->unique('uri')
        ->values()
        ->all();

    return response()->json($routes);
});

Route::resource('sales-person-teams', SalespersonTeamController::class);
Route::resource('update-customer-accounts', UpdateCustomerAccountController::class);

// Close authenticated routes group
});
