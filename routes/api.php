<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandardFormulaController;
use App\Http\Controllers\SOConfigController;
use App\Http\Controllers\UpdateCustomerAccountController;
use App\Http\Controllers\CustomerAlternateAddressController;
use App\Http\Controllers\CorrugatorConfigController;
use App\Http\Controllers\CorrugatorSpecByProductController;
use App\Http\Controllers\RollTrimByCorrugatorController;
use App\Http\Controllers\RollTrimByProductDesignController;
use App\Http\Controllers\RollSizeController;
use App\Http\Controllers\SideTrimByFluteController;
use App\Http\Controllers\SideTrimByProductDesignController;
use App\Http\Controllers\ProductDesignController;
use App\Http\Controllers\ComputationMethodController;
use App\Http\Controllers\FinishingController;
use App\Http\Controllers\ApproveMcController;
use App\Http\Controllers\SalesManagement\SalesOrder\Report\SalesOrderReportController;
use App\Http\Controllers\SalesManagement\CustomerService\CustomerServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaperFluteController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmControlPeriodController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTransactionTypeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTaxTypeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmTaxGroupController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmAnalysisCodeController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmLocationController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmCategoryController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController;
use App\Http\Controllers\WarehouseLocationController;
use App\Http\Controllers\CustomerSalesTypeController;
use App\Http\Controllers\FgDoConfigController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmConfigController;

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

Route::get('/paper-flutes', [PaperFluteController::class, 'apiIndex']);
Route::get('/products', [ProductController::class, 'getProductsJson']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Customer Accounts API routes for status change
Route::put('/customer-accounts/{customer_code}/status', [App\Http\Controllers\UpdateCustomerAccountController::class, 'updateStatus']); 

// Standard Formula API routes
Route::get('/standard-formula', [StandardFormulaController::class, 'apiIndex']);
Route::post('/standard-formula', [StandardFormulaController::class, 'apiStore']);
Route::post('/standard-formula/seed', [StandardFormulaController::class, 'apiSeed']);

// SO Config API routes
Route::post('/so-config', [SOConfigController::class, 'apiStore']); 
Route::get('/so-periods', [SOConfigController::class, 'apiIndexPeriods']);
Route::get('/so-rough-cut-capacity', [SOConfigController::class, 'apiIndexRoughCutCapacity']);

// Customer Accounts API routes
Route::get('/customer-accounts', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::post('/customer-accounts', [UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiUpdate']);
Route::get('/customers-with-status', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::get('/ac-auto-wo-customers', [UpdateCustomerAccountController::class, 'apiIndexAcAutoWoCustomers']);

// Customer Alternate Address API routes
Route::get('/customer-alternate-addresses', [CustomerAlternateAddressController::class, 'apiIndex']);

// Corrugator Config Routes
Route::get('/corrugator-configs', [CorrugatorConfigController::class, 'apiIndex']);
Route::get('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiShow']);
Route::post('/corrugator-configs', [CorrugatorConfigController::class, 'apiStore']);
Route::put('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiUpdate']);
Route::delete('/corrugator-configs/{id}', [CorrugatorConfigController::class, 'apiDestroy']);
Route::post('/corrugator-configs/seed', [CorrugatorConfigController::class, 'apiSeed']); 

// Corrugator Specification by Product Routes
Route::get('/corrugator-specs-by-product', [CorrugatorSpecByProductController::class, 'apiIndex']);
Route::get('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiShow']);
Route::post('/corrugator-specs-by-product', [CorrugatorSpecByProductController::class, 'apiStore']);
Route::post('/corrugator-specs-by-product/batch', [CorrugatorSpecByProductController::class, 'apiBatchUpdate']);
Route::put('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiUpdate']);
Route::delete('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiDestroy']);
Route::get('/corrugator-specs-by-product/export', [CorrugatorSpecByProductController::class, 'apiExport']);

// Roll Trim By Corrugator API Routes
Route::get('/roll-trim-by-corrugator/flutes', [RollTrimByCorrugatorController::class, 'getPaperFlutes']);
Route::get('/roll-trim-by-corrugator', [RollTrimByCorrugatorController::class, 'apiIndex']);
Route::post('/roll-trim-by-corrugator/batch', [RollTrimByCorrugatorController::class, 'apiBatchUpdate']);

// Roll Trim By Product Design API routes
Route::get('/roll-trim-by-product-design', [RollTrimByProductDesignController::class, 'apiIndex']);
Route::post('/roll-trim-by-product-design', [RollTrimByProductDesignController::class, 'apiStore']);
Route::post('/roll-trim-by-product-design/batch', [RollTrimByProductDesignController::class, 'apiBatchUpdate']);
Route::put('/roll-trim-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiUpdate']);
Route::delete('/roll-trim-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiDestroy']);
Route::get('/roll-trim-by-product-design/export', [RollTrimByProductDesignController::class, 'apiExport']);
Route::post('/roll-trim-by-product-design/seed', [RollTrimByProductDesignController::class, 'apiSeed']); 

// Roll Size API routes
Route::get('/roll-sizes', [RollSizeController::class, 'apiIndex']);
Route::post('/roll-sizes', [RollSizeController::class, 'apiStore']);
Route::post('/roll-sizes/batch', [RollSizeController::class, 'apiBatchUpdate']);
Route::put('/roll-sizes/{id}', [RollSizeController::class, 'apiUpdate']);
Route::delete('/roll-sizes/{id}', [RollSizeController::class, 'apiDestroy']);
Route::get('/roll-sizes/export', [RollSizeController::class, 'apiExport']);
Route::post('/roll-sizes/seed', [RollSizeController::class, 'apiSeed']); 

// Side Trim By Flute API routes
Route::get('/side-trims-by-flute', [SideTrimByFluteController::class, 'apiIndex']);
Route::post('/side-trims-by-flute', [SideTrimByFluteController::class, 'apiStore']);
Route::post('/side-trims-by-flute/batch', [SideTrimByFluteController::class, 'apiBatchUpdate']);
Route::put('/side-trims-by-flute/{id}', [SideTrimByFluteController::class, 'apiUpdate']);
Route::delete('/side-trims-by-flute/{id}', [SideTrimByFluteController::class, 'apiDestroy']);
Route::get('/side-trims-by-flute/export', [SideTrimByFluteController::class, 'apiExport']);
Route::post('/side-trims-by-flute/seed', [SideTrimByFluteController::class, 'apiSeed']);
Route::get('/side-trims-by-flute/diagnostic', [SideTrimByFluteController::class, 'diagnosticComputeToggle']);

// Side Trim By Product Design API routes
Route::get('/side-trims-by-product-design', [SideTrimByProductDesignController::class, 'apiIndex']);
Route::post('/side-trims-by-product-design', [SideTrimByProductDesignController::class, 'apiStore']);
Route::put('/side-trims-by-product-design/{id}', [SideTrimByProductDesignController::class, 'apiUpdate']);
Route::delete('/side-trims-by-product-design/{id}', [SideTrimByProductDesignController::class, 'apiDestroy']);
Route::get('/side-trims-by-product-design/export', [SideTrimByProductDesignController::class, 'apiExport']);
Route::post('/side-trims-by-product-design/seed', [SideTrimByProductDesignController::class, 'apiSeed']);
Route::post('/side-trims-by-product-design/batch', [SideTrimByProductDesignController::class, 'apiBatchUpdate']);

// Product Design API routes
Route::get('/product-designs', [ProductDesignController::class, 'getDesignsJson']);
Route::post('/product-designs', [ProductDesignController::class, 'apiStore']);
Route::put('/product-designs/{id}', [ProductDesignController::class, 'apiUpdate']);
Route::delete('/product-designs/{id}', [ProductDesignController::class, 'apiDestroy']);
Route::get('/product-designs/export', [ProductDesignController::class, 'apiExport']); 

// Computation Method API routes
Route::get('/computation-methods', [ComputationMethodController::class, 'apiIndex']);
Route::get('/computation-methods/{id}', [ComputationMethodController::class, 'apiShow']);
Route::post('/computation-methods', [ComputationMethodController::class, 'apiStore']);
Route::put('/computation-methods/{id}', [ComputationMethodController::class, 'apiUpdate']);
Route::delete('/computation-methods/{id}', [ComputationMethodController::class, 'apiDestroy']);
Route::get('/computation-methods/export', [ComputationMethodController::class, 'apiExport']);
Route::post('/computation-methods/seed', [ComputationMethodController::class, 'apiSeed']); 

// Finishing API routes
Route::get('/finishings', [FinishingController::class, 'apiIndex']);
Route::post('/finishings', [FinishingController::class, 'store']);
Route::put('/finishings/{code}', [FinishingController::class, 'update']);
Route::delete('/finishings/{code}', [FinishingController::class, 'destroy']);
Route::post('/finishings/seed', [FinishingController::class, 'seed']); 

// Approve MC API routes
Route::get('/approve-mc/by-customer/{customerId}', [ApproveMcController::class, 'getByCustomer']);
Route::get('/mc-auto-wo-not-releasing', [ApproveMcController::class, 'apiIndexMcAutoWoNotReleasing']);

// Sales Order Report API routes
Route::get('/report-formats', [SalesOrderReportController::class, 'apiIndexReportFormats']);
Route::post('/so-report', [SalesOrderReportController::class, 'apiGenerateSoReport']);

// Customer Service API routes
Route::get('/customer-service/dashboard-data', [CustomerServiceController::class, 'apiDashboardData']);
Route::get('/customer-service/account-credit-data', [CustomerServiceController::class, 'apiAccountCreditData']);
Route::get('/customer-service/delivery-schedule-data', [CustomerServiceController::class, 'apiDeliveryScheduleData']);
Route::get('/customer-service/finished-goods-data', [CustomerServiceController::class, 'apiFinishedGoodsData']);
Route::get('/customer-service/production-monitoring-data', [CustomerServiceController::class, 'apiProductionMonitoringData']); 

// New: Placeholder API routes for Sales Order Delivery Schedule Modals
Route::get('/po-refs', function() {
    return response()->json([
        ['po_ref' => 'PO-ALPHA-001', 'customer_code' => 'CUST001', 'so_number' => 'SO-2023-001', 'status' => 'Active'],
        ['po_ref' => 'PO-BETA-002', 'customer_code' => 'CUST002', 'so_number' => 'SO-2023-002', 'status' => 'Closed'],
        ['po_ref' => 'PO-GAMMA-003', 'customer_code' => 'CUST003', 'so_number' => 'SO-2023-003', 'status' => 'Active'],
    ]);
});

Route::get('/area-groups', function() {
    return response()->json([
        ['code' => 'AG01', 'name' => 'Central Region'],
        ['code' => 'AG02', 'name' => 'East Region'],
        ['code' => 'AG03', 'name' => 'West Region'],
    ]);
});

// Add to the end of the file
Route::get('/material-management/control-period', [MmControlPeriodController::class, 'getControlPeriod']);
Route::post('/material-management/control-period', [MmControlPeriodController::class, 'updateControlPeriod']);
Route::get('/material-management/transaction-types', [MmTransactionTypeController::class, 'getTransactionTypes']);
Route::post('/material-management/transaction-types', [MmTransactionTypeController::class, 'store']);
Route::get('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'show']);
Route::put('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'update']);
Route::delete('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'destroy']); 

// Add Tax Type API routes
Route::get('/material-management/tax-types', [MmTaxTypeController::class, 'getTaxTypes']);
Route::post('/material-management/tax-types', [MmTaxTypeController::class, 'store']);
Route::get('/material-management/tax-types/{code}', [MmTaxTypeController::class, 'show']);
Route::put('/material-management/tax-types/{code}', [MmTaxTypeController::class, 'update']);
Route::delete('/material-management/tax-types/{code}', [MmTaxTypeController::class, 'destroy']); 

// Add Tax Group API routes
Route::get('/material-management/tax-groups', [MmTaxGroupController::class, 'getTaxGroups']);
Route::post('/material-management/tax-groups', [MmTaxGroupController::class, 'store']);
Route::get('/material-management/tax-groups/{code}', [MmTaxGroupController::class, 'show']);
Route::put('/material-management/tax-groups/{code}', [MmTaxGroupController::class, 'update']);
Route::delete('/material-management/tax-groups/{code}', [MmTaxGroupController::class, 'destroy']);
Route::post('/material-management/tax-groups/seed', [MmTaxGroupController::class, 'seed']); 

// Add Receive Destination API routes
Route::get('/material-management/receive-destinations', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'getReceiveDestinations']);
Route::post('/material-management/receive-destinations', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'store']);
Route::get('/material-management/receive-destinations/{code}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'show']);
Route::put('/material-management/receive-destinations/{code}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'update']);
Route::delete('/material-management/receive-destinations/{code}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'destroy']);
Route::post('/material-management/receive-destinations/seed', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmReceiveDestinationController::class, 'seed']); 

// Add Analysis Code API routes
Route::get('/material-management/analysis-codes', [MmAnalysisCodeController::class, 'getAnalysisCodes']);
Route::get('/material-management/analysis-codes/groups', [MmAnalysisCodeController::class, 'getGroups']);
Route::get('/material-management/analysis-codes/group2s', [MmAnalysisCodeController::class, 'getGroup2s']);
Route::post('/material-management/analysis-codes', [MmAnalysisCodeController::class, 'store']);
Route::get('/material-management/analysis-codes/{code}', [MmAnalysisCodeController::class, 'show']);
Route::put('/material-management/analysis-codes/{code}', [MmAnalysisCodeController::class, 'update']);
Route::delete('/material-management/analysis-codes/{code}', [MmAnalysisCodeController::class, 'destroy']);
Route::post('/material-management/analysis-codes/seed', [MmAnalysisCodeController::class, 'seed']); 

Route::get('/material-management/control-periods/summary', [MmControlPeriodController::class, 'getControlPeriodSummary']);

// Add Location API routes
Route::prefix('material-management/locations')->group(function () {
    Route::get('for-print', [MmLocationController::class, 'getLocationsForPrint']);
    Route::post('seed', [MmLocationController::class, 'seed']);
    Route::patch('{code}/toggle-active', [MmLocationController::class, 'toggleActive']);
});
Route::apiResource('material-management/locations', MmLocationController::class)->except(['index'])->parameters(['locations' => 'code']);
Route::get('material-management/locations', [MmLocationController::class, 'index']);

// FgDoConfig API routes
Route::prefix('fg-do-config')->group(function () {
    Route::get('/', [FgDoConfigController::class, 'getConfig']);
    Route::post('/', [FgDoConfigController::class, 'updateConfig']);
});

// Warehouse Location API routes
Route::prefix('warehouse-locations')->group(function () {
    Route::get('/', [WarehouseLocationController::class, 'index']);
    Route::post('/', [WarehouseLocationController::class, 'store']);
    Route::get('/{code}', [WarehouseLocationController::class, 'show']);
    Route::put('/{code}', [WarehouseLocationController::class, 'update']);
    Route::delete('/{code}', [WarehouseLocationController::class, 'destroy']);
    Route::get('/json', [WarehouseLocationController::class, 'getWarehouseLocationsJson'])->name('warehouse-locations.json'); // For search/listing in modal
}); 

Route::get('/customer-sales-types', [CustomerSalesTypeController::class, 'index']);
Route::post('/customer-sales-types', [CustomerSalesTypeController::class, 'store']); 

// Diecut Computation Formula API routes
Route::get('/diecut-computation-formulas', [App\Http\Controllers\ComputationFormulaController::class, 'apiIndex']);
Route::get('/diecut-computation-formulas/{id}', [App\Http\Controllers\ComputationFormulaController::class, 'apiShow']);
Route::post('/diecut-computation-formulas', [App\Http\Controllers\ComputationFormulaController::class, 'apiStore']);
Route::put('/diecut-computation-formulas/{id}', [App\Http\Controllers\ComputationFormulaController::class, 'apiUpdate']);
Route::delete('/diecut-computation-formulas/{id}', [App\Http\Controllers\ComputationFormulaController::class, 'apiDestroy']);
Route::post('/diecut-computation-formulas/seed', [App\Http\Controllers\ComputationFormulaController::class, 'apiSeed']); 

Route::prefix('material-management/config-data')->group(function () {
    Route::get('transaction-types', [MmTransactionTypeController::class, 'getAll']);
    Route::get('purchasers', [\App\Http\Controllers\SalespersonController::class, 'getAllPurchasers']);
    Route::get('receive-locations', [MmReceiveDestinationController::class, 'getAll']);
    Route::get('tax-groups', [MmTaxGroupController::class, 'getAll']);
    Route::get('locations', [MmLocationController::class, 'getAll']);
    Route::get('source-codes', [MmConfigController::class, 'getSourceCodes']); // Placeholder, assuming it's simple
    Route::get('gl-distributions', [MmConfigController::class, 'getGlDistributions']); // Placeholder
});
