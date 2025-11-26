<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UpdateCustomerAccountController;
use App\Http\Controllers\CustomerAlternateAddressController;
use App\Http\Controllers\ProductDesignController;
use App\Http\Controllers\FinishingController;
use App\Http\Controllers\StitchWireController;
use App\Http\Controllers\ColorGroupController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\AnalysisCodeController;
use App\Http\Controllers\ChemicalCoatController;
use App\Http\Controllers\ReinforcementTapeController;
use App\Http\Controllers\BundlingStringController;
use App\Http\Controllers\WrappingMaterialController;
use App\Http\Controllers\GlueingMaterialController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\SalesManagement\SalesOrder\Report\SalesOrderReportController;
use App\Http\Controllers\SalesManagement\CustomerService\CustomerServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaperFluteController;
use App\Http\Controllers\WarehouseLocationController;
use App\Http\Controllers\CustomerSalesTypeController;
use App\Http\Controllers\DeliveryOrderFormatController;
use App\Http\Controllers\UpdateMcController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\WarehouseManagement\Invoice\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Menu search routes for AppLayout header search
// This endpoint is used by the Vue AppLayout to provide a searchable menu.
// It scans all registered routes, filters for GET web routes whose names
// start with 'vue.', and returns a simplified list of { title, uri, name }.
Route::get('/menu-routes', function (Request $request) {
    $routes = collect(Route::getRoutes())
        ->filter(function ($route) {
            // Only GET routes
            if (!in_array('GET', $route->methods(), true)) {
                return false;
            }

            $uri = $route->uri();

            // Skip API routes themselves to avoid noise
            if (strpos($uri, 'api/') === 0) {
                return false;
            }

            $name = $route->getName();

            // Only include routes that are used for Vue pages / menus
            if ($name && strpos($name, 'vue.') === 0) {
                return true;
            }

            return false;
        })
        ->map(function ($route) {
            $uri = '/' . ltrim($route->uri(), '/');
            $name = $route->getName();

            // Humanize title from last URI segment (e.g. 'prepare-mc-so' -> 'Prepare Mc So')
            $segment = collect(explode('/', trim($uri, '/')))->last();
            $segment = $segment ?: $uri;
            $title = ucwords(str_replace(['-', '_'], ' ', $segment));

            // Custom labels for specific invoice DO routes so they appear nicely in menu search
            if ($name === 'vue.warehouse-management.invoice.iv-processing.prepare-by-do-current-period') {
                $title = 'Prepare Invoice by D/Order (Current Period)';
            } elseif ($name === 'vue.warehouse-management.invoice.iv-processing.prepare-by-do-open-period') {
                $title = 'Prepare Invoice by D/Order (Open Period)';
            }

            return [
                'title' => $title,
                'uri'   => $uri,
                'name'  => $name,
            ];
        })
        // Ensure each URI appears only once
        ->unique('uri')
        ->values();

    return response()->json($routes);
});

// NOTE: Material Management SKU routes are now defined in the material-management prefix group below (around line 1104)
// to avoid duplication and ensure proper route ordering

// User API routes
Route::get('/users', [UserController::class, 'apiIndex']);
Route::get('/users/search/{userId}', [UserController::class, 'searchUser']);
Route::post('/users/{userId}/permissions', [UserController::class, 'updateUserPermissions']);

// Protected user status toggle (use web + auth to leverage session authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::put('/users/{userId}/status', [UserController::class, 'apiToggleStatus']);
});

Route::get('/paper-flutes', [PaperFluteController::class, 'apiIndex']);

// Product API routes
Route::get('/products', [ProductController::class, 'getProductsJson']);
Route::get('/categories', [ProductController::class, 'getCategoriesJson']);
Route::post('/products', [ProductController::class, 'apiStore']);
Route::match(['put', 'patch'], '/products/{id}', [ProductController::class, 'apiUpdate'])->where('id', '[0-9]+');
Route::delete('/products/{id}', [ProductController::class, 'apiDestroy'])->where('id', '[0-9]+');

// Color API routes
Route::get('/colors', [ColorController::class, 'apiIndex']);

// Color Group API routes
Route::get('/color-groups', [ColorGroupController::class, 'apiIndex']);
Route::post('/color-groups', [ColorGroupController::class, 'store']);
Route::put('/color-groups/{code}', [ColorGroupController::class, 'update']);
Route::delete('/color-groups/{code}', [ColorGroupController::class, 'destroy']);
Route::post('/color-groups/seed', [ColorGroupController::class, 'seed']);

// Analysis Code API routes
Route::get('/analysis-codes', [AnalysisCodeController::class, 'apiIndex']);
Route::get('/analysis-codes/{code}', [AnalysisCodeController::class, 'show']);
Route::post('/analysis-codes', [AnalysisCodeController::class, 'store']);
Route::put('/analysis-codes/{code}', [AnalysisCodeController::class, 'update']);
Route::delete('/analysis-codes/{code}', [AnalysisCodeController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});

// 🔍 DEBUG: Check auth status (NO AUTH REQUIRED - for debugging)
Route::get('/auth-status', function (Request $request) {
    return response()->json([
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'user' => Auth::user() ? [
            'id' => Auth::user()->id ?? null,
            'userID' => Auth::user()->userID ?? null,
            'userName' => Auth::user()->userName ?? null,
            'class' => get_class(Auth::user()),
        ] : null,
        'session_id' => session()->getId(),
        'has_session' => session()->has('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'),
    ]);
});

// Invoice preparation endpoints
Route::prefix('invoices')->group(function () {
    // 🔓 PUBLIC routes (no auth required) - Read-only data
    Route::get('/customer-details', [InvoiceController::class, 'getCustomerDetails']);
    Route::get('/sales-tax-options', [InvoiceController::class, 'getSalesTaxOptions']);
    Route::get('/do-items', [InvoiceController::class, 'getDoItems']);
    Route::get('/delivery-orders', [InvoiceController::class, 'getDeliveryOrders']);
    Route::get('/do-status', [InvoiceController::class, 'getDoStatus']);
    Route::post('/calculate-total', [InvoiceController::class, 'calculateTotal']);

    // 🔒 PROTECTED routes (auth required) - Write operations & sensitive data
    // Using 'web' middleware to ensure session authentication works
    Route::middleware(['web', 'auth'])->group(function () {
        Route::get('/current-period-do', [InvoiceController::class, 'currentPeriodDo']);
        Route::post('/prepare', [InvoiceController::class, 'prepare']);
        Route::post('/cancel', [InvoiceController::class, 'cancelInvoice']);
        Route::get('/log', [InvoiceController::class, 'getInvoiceLog']);
        Route::post('/seed-test-customers', [InvoiceController::class, 'seedTestCustomers']);
        // ✅ Amend Invoice routes (write operations)
        Route::post('/{invoiceNo}/print', [InvoiceController::class, 'updatePrintAudit']); // Update print audit trail
        Route::put('/{invoiceNo}', [InvoiceController::class, 'update']); // Update invoice (Amend Invoice)
    });

// Tax Type API routes (CPS-style Define Tax Type) - MUST be before wildcard routes
Route::get('/tax-types', [App\Http\Controllers\Invoice\TaxTypeController::class, 'getTaxTypes']);
Route::post('/tax-types', [App\Http\Controllers\Invoice\TaxTypeController::class, 'store']);
Route::post('/tax-types/seed', [App\Http\Controllers\Invoice\TaxTypeController::class, 'seed']);
Route::get('/tax-types/{code}', [App\Http\Controllers\Invoice\TaxTypeController::class, 'show']);
Route::put('/tax-types/{code}', [App\Http\Controllers\Invoice\TaxTypeController::class, 'update']);
Route::delete('/tax-types/{code}', [App\Http\Controllers\Invoice\TaxTypeController::class, 'destroy']);

// Tax Group API routes (CPS-style Define Tax Group) - MUST be before wildcard routes
Route::get('/tax-groups', [App\Http\Controllers\Invoice\TaxGroupController::class, 'index']);
Route::get('/tax-groups/with-types', [App\Http\Controllers\Invoice\TaxGroupController::class, 'getTaxGroupsWithTypes']);
Route::post('/tax-groups', [App\Http\Controllers\Invoice\TaxGroupController::class, 'store']);
Route::post('/tax-groups/seed', [App\Http\Controllers\Invoice\TaxGroupController::class, 'seed']);
Route::get('/tax-groups/{code}', [App\Http\Controllers\Invoice\TaxGroupController::class, 'show']);
Route::get('/tax-groups/{code}/tax-items', [App\Http\Controllers\Invoice\TaxGroupController::class, 'getTaxItems']);
Route::get('/tax-groups/{code}/tax-types', [App\Http\Controllers\Invoice\TaxGroupController::class, 'getTaxTypes']);
Route::post('/tax-groups/{code}/tax-types', [App\Http\Controllers\Invoice\TaxGroupController::class, 'saveTaxTypes']);
Route::put('/tax-groups/{code}', [App\Http\Controllers\Invoice\TaxGroupController::class, 'update']);
Route::delete('/tax-groups/{code}', [App\Http\Controllers\Invoice\TaxGroupController::class, 'destroy']);

// Customer Sales Tax Index routes - MUST be before wildcard routes
Route::get('/customer-tax-indices/{customerCode}', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'getCustomerIndices']);
Route::get('/customer-tax-indices/{customerCode}/{indexNumber}', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'show']);
Route::post('/customer-tax-indices', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'store']);
Route::delete('/customer-tax-indices/{customerCode}/{indexNumber}', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'destroy']);
Route::get('/customer-tax-indices/{customerCode}/{indexNumber}/product-tieups', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'getProductTieups']);
Route::post('/customer-tax-indices/{customerCode}/{indexNumber}/product-tieups', [App\Http\Controllers\Invoice\CustomerSalesTaxIndexController::class, 'saveProductTieups']);

// Amend Invoice endpoints - Wildcard routes MUST be last
Route::get('/test-inv-data', function() {
    $count = DB::table('INV')->count();
    $sample = DB::table('INV')->limit(5)->get();
    return response()->json([
        'total_invoices' => $count,
        'sample_data' => $sample,
        'message' => $count > 0 ? "Database has $count invoices" : "Database is empty (no invoices)"
    ]);
}); // Debug endpoint
Route::get('/', [InvoiceController::class, 'index']); // Get list of invoices
Route::get('/{invoiceNo}/with-items', [InvoiceController::class, 'showWithItems']); // Get invoice with items
Route::get('/{invoiceNo}', [InvoiceController::class, 'show']); // Get single invoice details
// ✅ Note: PUT /{invoiceNo} and POST /{invoiceNo}/print are now in protected middleware group above

// Debug endpoint to test salesperson query
Route::get('/test-salesperson/{customerCode}', function($customerCode) {
$customerData = DB::select("
           SELECT
               CODE as customer_code,
               NAME as customer_name,
               SLM as salesperson_code,
               AREA as area
           FROM CUSTOMER
           WHERE CODE = ?
       ", [$customerCode]);

$result = [
'customer_code' => $customerCode,
'found' => !empty($customerData),
'data' => $customerData,
'count' => count($customerData)
];

// Also get salesperson name if found
if (!empty($customerData) && !empty($customerData[0]->salesperson_code)) {
$salespersonTeam = DB::select("
               SELECT s_person_code, salesperson_name
               FROM salesperson_teams
               WHERE s_person_code = ?
           ", [$customerData[0]->salesperson_code]);

$result['salesperson_name'] = !empty($salespersonTeam) ? $salespersonTeam[0]->salesperson_name : 'Not found in salesperson_teams';
}

return response()->json($result);
});
});

// Debug endpoint to check customer tables
Route::get('/debug/customer-tables', function() {
try {
$tables = ['CUSTOMER', 'Customer_Account', 'customer_account', 'update_customer_accounts'];
$result = [];

foreach ($tables as $table) {
try {
$count = DB::table($table)->count();
$sample = DB::table($table)->first();
$result[$table] = [
'exists' => true,
'count' => $count,
'columns' => $sample ? array_keys((array)$sample) : [],
'sample' => $sample
];
} catch (\Exception $e) {
$result[$table] = [
'exists' => false,
'error' => $e->getMessage()
];
}
}

// Try to find specific customer
try {
$testCustomer = DB::table('CUSTOMER')->where('CODE', '000283')->first();
$result['test_query'] = [
'found' => $testCustomer ? true : false,
'data' => $testCustomer
];
} catch (\Exception $e) {
$result['test_query'] = ['error' => $e->getMessage()];
}

return response()->json($result);
} catch (\Exception $e) {
return response()->json(['error' => $e->getMessage()], 500);
}
});

// Customer Accounts API routes for status change
Route::put('/customer-accounts/{customer_code}/status', [App\Http\Controllers\UpdateCustomerAccountController::class, 'updateStatus']);
// SO Config API routes
// TODO: SOConfigController tidak ditemukan, perlu dibuat atau gunakan SalesConfigurationController
// Route::post('/so-config', [SOConfigController::class, 'apiStore']);
// Route::get('/so-periods', [SOConfigController::class, 'apiIndexPeriods']);
// Route::get('/so-rough-cut-capacity', [SOConfigController::class, 'apiIndexRoughCutCapacity']);

// Customer Accounts API routes
Route::get('/customer-accounts', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::post('/customer-accounts', [UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiUpdate']);
Route::get('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiShow']);
Route::get('/customers-with-status', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::get('/ac-auto-wo-customers', [UpdateCustomerAccountController::class, 'apiIndexAcAutoWoCustomers']);

// Customer Alternate Address API routes
Route::get('/customer-alternate-addresses', [CustomerAlternateAddressController::class, 'apiIndex']);
Route::get('/customer-alternate-addresses/{customerCode}', [CustomerAlternateAddressController::class, 'apiGetCustomerAddresses']);


// Product Design API routes
Route::get('/product-designs', [ProductDesignController::class, 'getDesignsJson']);
Route::post('/product-designs', [ProductDesignController::class, 'apiStore']);
Route::put('/product-designs/{id}', [ProductDesignController::class, 'apiUpdate']);
Route::delete('/product-designs/{id}', [ProductDesignController::class, 'apiDestroy']);
Route::get('/product-designs/export', [ProductDesignController::class, 'apiExport']);

// Finishing API routes
Route::get('/finishings', [FinishingController::class, 'apiIndex']);
Route::post('/finishings', [FinishingController::class, 'store']);
Route::put('/finishings/{code}', [FinishingController::class, 'update']);
Route::delete('/finishings/{code}', [FinishingController::class, 'destroy']);
Route::post('/finishings/seed', [FinishingController::class, 'seed']);

// Chemical Coat API routes
Route::get('/chemical-coats', [App\Http\Controllers\ChemicalCoatController::class, 'apiIndex']);
Route::post('/chemical-coats', [App\Http\Controllers\ChemicalCoatController::class, 'store']);
Route::put('/chemical-coats/{code}', [App\Http\Controllers\ChemicalCoatController::class, 'update']);
Route::delete('/chemical-coats/{code}', [App\Http\Controllers\ChemicalCoatController::class, 'destroy']);
Route::post('/chemical-coats/seed', [App\Http\Controllers\ChemicalCoatController::class, 'seed']);

// Stitch Wire API routes
Route::get('/stitch-wires', [App\Http\Controllers\StitchWireController::class, 'apiIndex']);
Route::post('/stitch-wires', [App\Http\Controllers\StitchWireController::class, 'store']);
Route::put('/stitch-wires/{code}', [App\Http\Controllers\StitchWireController::class, 'update']);
Route::delete('/stitch-wires/{code}', [App\Http\Controllers\StitchWireController::class, 'destroy']);
Route::post('/stitch-wires/seed', [App\Http\Controllers\StitchWireController::class, 'seed']);

// Reinforcement Tape API routes
Route::get('/reinforcement-tapes', [App\Http\Controllers\ReinforcementTapeController::class, 'apiIndex']);
Route::post('/reinforcement-tapes', [App\Http\Controllers\ReinforcementTapeController::class, 'store']);
Route::put('/reinforcement-tapes/{code}', [App\Http\Controllers\ReinforcementTapeController::class, 'update']);
Route::delete('/reinforcement-tapes/{code}', [App\Http\Controllers\ReinforcementTapeController::class, 'destroy']);
Route::post('/reinforcement-tapes/seed', [App\Http\Controllers\ReinforcementTapeController::class, 'seed']);

// Bundling String API routes
Route::get('/bundling-strings', [App\Http\Controllers\BundlingStringController::class, 'apiIndex']);
Route::post('/bundling-strings', [App\Http\Controllers\BundlingStringController::class, 'store']);
Route::put('/bundling-strings/{code}', [App\Http\Controllers\BundlingStringController::class, 'update']);
Route::delete('/bundling-strings/{code}', [App\Http\Controllers\BundlingStringController::class, 'destroy']);

// Machine API routes
Route::get('/machines', [MachineController::class, 'index']);
Route::post('/bundling-strings/seed', [App\Http\Controllers\BundlingStringController::class, 'seed']);

// Wrapping Material API routes
Route::get('/wrapping-materials', [App\Http\Controllers\WrappingMaterialController::class, 'apiIndex']);
Route::post('/wrapping-materials', [App\Http\Controllers\WrappingMaterialController::class, 'store']);
Route::put('/wrapping-materials/{code}', [App\Http\Controllers\WrappingMaterialController::class, 'update']);
Route::delete('/wrapping-materials/{code}', [App\Http\Controllers\WrappingMaterialController::class, 'destroy']);
Route::post('/wrapping-materials/seed', [App\Http\Controllers\WrappingMaterialController::class, 'seed']);

// Glueing Material API routes
Route::get('/glueing-materials', [App\Http\Controllers\GlueingMaterialController::class, 'apiIndex']);
Route::post('/glueing-materials', [App\Http\Controllers\GlueingMaterialController::class, 'store']);
Route::put('/glueing-materials/{code}', [App\Http\Controllers\GlueingMaterialController::class, 'update']);
Route::delete('/glueing-materials/{code}', [App\Http\Controllers\GlueingMaterialController::class, 'destroy']);
Route::post('/glueing-materials/seed', [App\Http\Controllers\GlueingMaterialController::class, 'seed']);

// Machine API routes
Route::get('/machines', [MachineController::class, 'index']);
Route::post('/machines', [MachineController::class, 'store']);
Route::put('/machines/{id}', [MachineController::class, 'update']);
Route::delete('/machines/{id}', [MachineController::class, 'destroy']);
Route::post('/machines/seed', [MachineController::class, 'seed']);

// Approve MC API routes
Route::get('/approve-mc/by-customer/{customerId}', [UpdateMcController::class, 'apiIndex']);
Route::get('/mc-auto-wo-not-releasing', [UpdateMcController::class, 'apiIndex']);

// Add API route for Update MC Master Cards
Route::get('/update-mc/master-cards', [App\Http\Controllers\UpdateMcController::class, 'apiIndex']);
Route::get('/update-mc/master-cards/{mcSeq}', [App\Http\Controllers\UpdateMcController::class, 'apiShow']);
Route::get('/update-mc/master-cards/{mcSeq}/components', [App\Http\Controllers\UpdateMcController::class, 'apiShowComponents']);
Route::post('/update-mc/master-cards', [App\Http\Controllers\UpdateMcController::class, 'store']);

// Obsolete & Reactive MC API routes
// Public routes (read-only)
Route::get('/obsolete-reactive-mc', [UpdateMcController::class, 'obsoleteReactiveApiIndex']);
Route::get('/obsolete-reactive-mc/by-customer/{customerCode}', [UpdateMcController::class, 'getByCustomer']);
Route::get('/mc/update-log/{mcsNum}', [UpdateMcController::class, 'getUpdateLog']);
Route::get('/mc/details/{mcsNum}', [UpdateMcController::class, 'getMcDetails']);

// Protected routes (write operations - require authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/obsolete-reactive-mc/{mcsNum}/obsolete', [UpdateMcController::class, 'obsolate']);
    Route::post('/obsolete-reactive-mc/{mcsNum}/reactive', [UpdateMcController::class, 'reactive']);
    Route::post('/obsolete-reactive-mc/bulk-obsolete', [UpdateMcController::class, 'bulkObsolete']);
    Route::post('/obsolete-reactive-mc/bulk-reactive', [UpdateMcController::class, 'bulkReactivate']);
});

// Sales Order Report API routes
Route::get('/report-formats', [SalesOrderReportController::class, 'apiIndexReportFormats']);
Route::post('/so-report', [SalesOrderReportController::class, 'apiGenerateSoReport']);

// Customer Service API routes (Dashboard)
Route::get('/customer-service/dashboard-data', [CustomerServiceController::class, 'apiDashboardData']);

// Sales Order API routes
// Route moved to web.php for CSRF protection
// Route::post('/sales-order', [App\Http\Controllers\SalesOrderController::class, 'store']);
// Route moved to web.php for CSRF protection
// Route::post('/sales-order/delivery-schedule', [App\Http\Controllers\SalesOrderController::class, 'saveDeliverySchedule']);
Route::get('/sales-order/delivery-schedule/{soNumber}', [App\Http\Controllers\SalesOrderController::class, 'getDeliveryScheduleSummary']);
// Route moved to web.php for CSRF protection
// Route::post('/sales-order/product-design', [App\Http\Controllers\SalesOrderController::class, 'saveProductDesign'])->middleware('api.csrf');
Route::get('/sales-order/customer/{code}', [App\Http\Controllers\SalesOrderController::class, 'getCustomer']);
// Get last sales order for a given customer (for PrepareMCSO Order Information)
Route::get('/sales-order/last/{customerCode}', [App\Http\Controllers\SalesOrderController::class, 'getLastSalesOrderForCustomer']);
// Save to legacy-style SO table (CPS compatibility)
Route::post('/sales-order/save-to-so', [App\Http\Controllers\SalesOrderController::class, 'apiStoreToSo']);
// Get sales orders for Print SO and Sales Order Table Modal
Route::get('/sales-orders', [App\Http\Controllers\SalesOrderController::class, 'getSalesOrders']);
// Get all outstanding sales orders for AmendSO
Route::get('/sales-orders/outstanding', [App\Http\Controllers\SalesOrderController::class, 'getOutstandingSalesOrders']);

// Debug route to check SO table structure and data
Route::get('/debug/so-table', function() {
    try {
        // Check if table exists and get sample data
        $totalCount = DB::table('so')->count();
        $sampleData = DB::table('so')->limit(5)->get();
        $distinctStatuses = DB::table('so')->select('STS')->distinct()->get();

        return response()->json([
            'success' => true,
            'total_records' => $totalCount,
            'sample_data' => $sampleData,
            'distinct_statuses' => $distinctStatuses->pluck('STS')->toArray(),
            'table_columns' => $sampleData->isNotEmpty() ? array_keys((array)$sampleData->first()) : []
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Debug route to test sales order data directly
Route::get('/debug/sales-orders', function(Request $request) {
while (ob_get_level()) {
ob_end_clean();
}

$customerCode = $request->get('customer_code');

$query = DB::table('so');
if ($customerCode) {
$query->where('AC_Num', $customerCode);
}

$orders = $query->limit(10)->get();

return response()->json([
'success' => true,
'customer_code' => $customerCode,
'count' => $orders->count(),
'data' => $orders,
'sample' => $orders->first()
]);
});

// Get sales order detail by SO number
Route::get('/sales-order/{soNumber}/detail', [App\Http\Controllers\SalesOrderController::class, 'getSalesOrderDetail']);

// Update sales order (Amend SO)
Route::put('/sales-order/{soNumber}/update', [App\Http\Controllers\SalesOrderController::class, 'updateSalesOrder']);

// Cancel sales order
Route::put('/sales-order/{soNumber}/cancel', [App\Http\Controllers\SalesOrderController::class, 'cancelSalesOrder']);

// Get current authenticated user info
Route::get('/user/current', function() {
// Clean any output buffers to prevent BOM issues
while (ob_get_level()) {
ob_end_clean();
}

$user = Auth::user();
if (!$user) {
return response()->json([
'success' => false,
'message' => 'User not authenticated'
], 401, ['Content-Type' => 'application/json; charset=utf-8']);
}

// Log user data for debugging
try {
Log::info('Current user data:', [
'user_id' => $user->user_id ?? null,
'username' => $user->username ?? null,
'official_name' => $user->official_name ?? null,
'official_title' => $user->official_title ?? null,
]);
} catch (\Exception $e) {
Log::warning('Unable to log user data: ' . $e->getMessage());
}

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
// Note: /api/user/current route moved to web.php for proper session authentication
// See routes/web.php line ~258 for the authenticated user endpoint

// Vehicle API routes
Route::get('/vehicles', [VehicleController::class, 'apiIndex']);
Route::post('/vehicles', [VehicleController::class, 'apiStore']);
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
Route::put('/vehicles/{vehicle}', [VehicleController::class, 'apiUpdate']);
Route::put('/vehicles/{vehicle}/status', [VehicleController::class, 'apiUpdateStatus']);
Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'apiDestroy']);

Route::get('/vehicle-classes', [\App\Http\Controllers\VehicleClassController::class, 'apiIndex']);
Route::post('/vehicle-classes', [\App\Http\Controllers\VehicleClassController::class, 'apiStore']);
Route::put('/vehicle-classes/{vehicleClass}', [\App\Http\Controllers\VehicleClassController::class, 'apiUpdate']);
Route::put('/vehicle-classes/{vehicleClass}/status', [\App\Http\Controllers\VehicleClassController::class, 'apiUpdateStatus']);
Route::delete('/vehicle-classes/{vehicleClass}', [\App\Http\Controllers\VehicleClassController::class, 'apiDestroy']);

Route::get('/customer-sales-types', [CustomerSalesTypeController::class, 'index']);
Route::post('/customer-sales-types', [CustomerSalesTypeController::class, 'store']);


// Industry, Geo, and Salesperson API routes
Route::get('/industry', [App\Http\Controllers\IndustryController::class, 'apiIndex']);
Route::post('/industry', [App\Http\Controllers\IndustryController::class, 'store']);
Route::get('/industry/search/{code}', [App\Http\Controllers\IndustryController::class, 'search']);
Route::put('/industry/{code}', [App\Http\Controllers\IndustryController::class, 'update']);
Route::delete('/industry/{code}', [App\Http\Controllers\IndustryController::class, 'destroy']);
Route::post('/industry/seed', [App\Http\Controllers\IndustryController::class, 'seed']);

// Geo API routes (complete CRUD)
Route::get('/geo', [App\Http\Controllers\GeoController::class, 'apiIndex']);
Route::get('/geos', [App\Http\Controllers\GeoController::class, 'apiIndex']); // Alias for compatibility
Route::post('/geo', [App\Http\Controllers\GeoController::class, 'store']);
Route::get('/geo/{code}', [App\Http\Controllers\GeoController::class, 'show']);
Route::put('/geo/{code}', [App\Http\Controllers\GeoController::class, 'update']);
Route::delete('/geo/{code}', [App\Http\Controllers\GeoController::class, 'destroy']);
Route::post('/geo/seed', [App\Http\Controllers\GeoController::class, 'seed']);

Route::get('/salespersons', [App\Http\Controllers\SalespersonController::class, 'apiIndex']);

// Customer Group API routes
Route::get('/customer-groups', [App\Http\Controllers\CustomerGroupController::class, 'apiIndex'])->name('api.customer-groups.index');
Route::post('/customer-groups', [App\Http\Controllers\CustomerGroupController::class, 'apiStore'])->name('api.customer-groups.store');
Route::put('/customer-groups/{group_code}', [App\Http\Controllers\CustomerGroupController::class, 'apiUpdate'])->name('api.customer-groups.update');
Route::delete('/customer-groups/{group_code}', [App\Http\Controllers\CustomerGroupController::class, 'apiDestroy'])->name('api.customer-groups.destroy');
Route::post('/customer-groups/seed', [App\Http\Controllers\CustomerGroupController::class, 'seed'])->name('api.customer-groups.seed');

// Update Customer Account API route
Route::post('/update-customer-account', [App\Http\Controllers\UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/update-customer-account/{id}', [App\Http\Controllers\UpdateCustomerAccountController::class, 'apiUpdate']);


// Update MC API Routes
Route::prefix('update-mc')->group(function () {
Route::post('/search-ac', [UpdateMcController::class, 'searchAc']);
Route::post('/search-mcs', [UpdateMcController::class, 'searchMcs']);
Route::get('/master-cards', [UpdateMcController::class, 'apiIndex']);
Route::get('/check-mcs/{mcsNumber}', [UpdateMcController::class, 'checkMcs']);
});

// Delivery Order API Routes
Route::prefix('delivery-orders')->group(function () {
    Route::post('/fix-missing-data', [DeliveryOrderController::class, 'fixMissingData']);
    Route::get('/print-range', [DeliveryOrderController::class, 'getPrintRange']);
    Route::post('/', [DeliveryOrderController::class, 'store']);
    Route::get('/', [DeliveryOrderController::class, 'index']);
    Route::get('/{doNumber}', [DeliveryOrderController::class, 'show']);
});
