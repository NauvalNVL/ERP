<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController;
use App\Http\Controllers\WarehouseLocationController;
use App\Http\Controllers\CustomerSalesTypeController;
use App\Http\Controllers\FgDoConfigController;
use App\Http\Controllers\DeliveryOrderFormatController;
use App\Http\Controllers\MaterialManagement\SystemRequirement\MmGlDistributionController;
use App\Http\Controllers\CustomerWarehouseRequirementController;
use App\Http\Controllers\UpdateMcController;

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

// Direct route for ObsoleteReactiveSku.vue component
Route::get('/material-management/skus/categories', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'getCategories']);
Route::get('/material-management/skus/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'getSkusForPrint']);
Route::get('/material-management/skus', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'index']);
Route::post('/material-management/skus/bulk-toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'bulkToggleActive']);
Route::get('/material-management/skus/{sku}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'show']);
Route::put('/material-management/skus/{sku}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'update']);
Route::patch('/material-management/skus/{sku}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'toggleActive']);
Route::post('/material-management/skus/{sku}/change-code', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'changeSkuCode']);
Route::get('/material-management/skus/{sku_id}/balance', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'getSkuBalance']);
Route::post('/material-management/skus', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'store']);
Route::get('/material-management/types', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuController::class, 'getTypes']);

// Purchaser API routes
Route::get('/purchasers', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'index']);
Route::post('/purchasers', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'store']);
Route::get('/purchasers/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'getForPrint']);
Route::get('/purchasers-by-type', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'getByType']);
Route::get('/purchasers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'show']);
Route::put('/purchasers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'update']);
Route::delete('/purchasers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'destroy']);
Route::patch('/purchasers/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'toggleActive']);

// Approval flow routes
Route::post('/purchasers/{id}/setup-approval-flow', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'setupApprovalFlow']);
Route::get('/purchasers/{id}/approval-flow', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'getApprovalFlow']);
Route::post('/purchasers/{id}/test-email-flow', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'testEmailFlow']);

// Utility routes
Route::get('/search-approvers', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'searchApprovers']);
Route::post('/validate-email', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaserController::class, 'validateEmail']);

// SKU Price API routes
Route::get('/sku-prices', [App\Http\Controllers\SkuPriceController::class, 'index']);
Route::get('/sku-prices/for-print', [App\Http\Controllers\SkuPriceController::class, 'getSkuPricesForPrint']);
Route::post('/sku-prices', [App\Http\Controllers\SkuPriceController::class, 'store']);
Route::get('/sku-prices/{id}', [App\Http\Controllers\SkuPriceController::class, 'show']);
Route::put('/sku-prices/{id}', [App\Http\Controllers\SkuPriceController::class, 'update']);
Route::delete('/sku-prices/{id}', [App\Http\Controllers\SkuPriceController::class, 'destroy']);
Route::get('/sku-prices/current/{skuCode}', [App\Http\Controllers\SkuPriceController::class, 'getCurrentPrice']);

// Material Management SKU Price API routes
Route::get('/material-management/sku-prices', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'search']);
Route::get('/material-management/sku-prices/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'getSkuPricesForPrint']);
Route::post('/material-management/sku-prices', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'store']);
Route::put('/material-management/sku-prices/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'update']);
Route::delete('/material-management/sku-prices/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'destroy']);
Route::get('/material-management/sku-prices/current/{skuCode}', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'getCurrentPrice']);
Route::post('/material-management/sku-prices/validate-sku', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmSkuPriceController::class, 'validateSku']);

// Foreign Currency API routes
Route::get('/foreign-currencies', [App\Http\Controllers\ForeignCurrencyController::class, 'apiIndex']);
Route::post('/foreign-currencies', [App\Http\Controllers\ForeignCurrencyController::class, 'apiStore']);
Route::get('/foreign-currencies/{id}', [App\Http\Controllers\ForeignCurrencyController::class, 'apiShow']);
Route::put('/foreign-currencies/{id}', [App\Http\Controllers\ForeignCurrencyController::class, 'apiUpdate']);
Route::delete('/foreign-currencies/{id}', [App\Http\Controllers\ForeignCurrencyController::class, 'apiDestroy']);

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
Route::get('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiShow']);
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

// Add API route for Update MC Master Cards
Route::get('/update-mc/master-cards', [App\Http\Controllers\UpdateMcController::class, 'apiIndex']);

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
Route::get('/material-management/control-period', [MmControlPeriodController::class, 'getControlPeriod'])->name('mm.control-period.get');
Route::post('/material-management/control-period', [MmControlPeriodController::class, 'updateControlPeriod'])->name('mm.control-period.update');
Route::options('/material-management/control-period', function () {
    return response()->json([], 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');
});
Route::get('/material-management/transaction-types', [MmTransactionTypeController::class, 'getTransactionTypes']);
Route::post('/material-management/transaction-types', [MmTransactionTypeController::class, 'store']);
Route::get('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'show']);
Route::put('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'update']);
Route::delete('/material-management/transaction-types/{code}', [MmTransactionTypeController::class, 'destroy']); 

// Add Category API routes
Route::prefix('material-management/categories')->group(function () {
    Route::get('/', [MmCategoryController::class, 'getCategories']);
    Route::get('/for-print', [MmCategoryController::class, 'getCategoriesForPrint']);
    Route::post('/', [MmCategoryController::class, 'store']);
    Route::get('/{code}', [MmCategoryController::class, 'show']);
    Route::put('/{code}', [MmCategoryController::class, 'update']);
    Route::delete('/{code}', [MmCategoryController::class, 'destroy']);
    Route::patch('/{code}/toggle-active', [MmCategoryController::class, 'toggleActive']);
    Route::post('/seed', [MmCategoryController::class, 'seedSampleData']);
});

// Add Unit API routes
Route::prefix('material-management/units')->group(function () {
    Route::get('/', [MmUnitController::class, 'getUnits']);
    Route::post('/', [MmUnitController::class, 'store']);
    Route::get('/{code}', [MmUnitController::class, 'show']);
    Route::put('/{code}', [MmUnitController::class, 'update']);
    Route::delete('/{code}', [MmUnitController::class, 'destroy']);
    Route::patch('/{code}/toggle-active', [MmUnitController::class, 'toggleActive']);
    Route::post('/seed', [MmUnitController::class, 'seedSampleData']);
});

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

Route::prefix('delivery-order-formats')->group(function () {
    Route::get('/', [DeliveryOrderFormatController::class, 'getFormatsJson'])->name('delivery-order-formats.index');
    Route::post('/', [DeliveryOrderFormatController::class, 'store'])->name('delivery-order-formats.store');
    Route::get('/{code}', [DeliveryOrderFormatController::class, 'show'])->name('delivery-order-formats.show');
    Route::put('/{code}', [DeliveryOrderFormatController::class, 'update'])->name('delivery-order-formats.update');
    Route::delete('/{code}', [DeliveryOrderFormatController::class, 'destroy'])->name('delivery-order-formats.destroy');
});

// Industry, Geo, and Salesperson API routes
Route::get('/industries', [App\Http\Controllers\IndustryController::class, 'apiIndex']);
Route::get('/geos', [App\Http\Controllers\GeoController::class, 'apiIndex']);
Route::get('/salespersons', [App\Http\Controllers\SalespersonController::class, 'apiIndex']);
Route::get('/customer-groups', [App\Http\Controllers\CustomerGroupController::class, 'apiIndex']);

// Customer Group API routes
Route::get('/customer-groups', [App\Http\Controllers\CustomerGroupController::class, 'apiIndex'])->name('api.customer-groups.index');
Route::post('/customer-groups', [App\Http\Controllers\CustomerGroupController::class, 'apiStore'])->name('api.customer-groups.store');
Route::put('/customer-groups/{group_code}', [App\Http\Controllers\CustomerGroupController::class, 'apiUpdate'])->name('api.customer-groups.update');
Route::delete('/customer-groups/{group_code}', [App\Http\Controllers\CustomerGroupController::class, 'apiDestroy'])->name('api.customer-groups.destroy');
Route::post('/customer-groups/seed', [App\Http\Controllers\CustomerGroupController::class, 'seed'])->name('api.customer-groups.seed');

// Update Customer Account API route
Route::post('/update-customer-account', [App\Http\Controllers\UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/update-customer-account/{id}', [App\Http\Controllers\UpdateCustomerAccountController::class, 'apiUpdate']);

// Material Management - System Requirement - Inventory Setup - SKU routes
Route::prefix('material-management/system-requirement/inventory-setup')->group(function () {
    // SKU routes - REMOVED DUPLICATES - using main material-management/skus routes instead
    // Route::get('/sku', [MmSkuController::class, 'index']);
    // Route::post('/sku', [MmSkuController::class, 'store']);
    // Route::get('/sku/{sku}', [MmSkuController::class, 'show']);
    // Route::put('/sku/{sku}', [MmSkuController::class, 'update']);
    // Route::delete('/sku/{sku}', [MmSkuController::class, 'destroy']);
    // Route::patch('/sku/{sku}/toggle-active', [MmSkuController::class, 'toggleActive']);
    // Route::post('/sku/seed', [MmSkuController::class, 'seed']);
    
    // Supporting endpoints for SKU component (these are duplicates and should be removed or moved)
    // Route::get('/category', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmCategoryController::class, 'apiIndex']);
    // Route::get('/sku-types', [MmSkuController::class, 'getTypes']);
    // Route::get('/units', [App\Http\Controllers\MaterialManagement\SystemRequirement\MmUnitController::class, 'apiIndex']);
    
    // SKU Reorder Level View & Print with detailed data - MUST BE BEFORE apiResource
    Route::get('sku-reorder-levels/view-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'getViewPrint']);
    
    Route::apiResource('sku-reorder-levels', App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class);
    Route::get('sku-reorder-levels/by-sku/{skuId}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'getBySku']);
    
    // Copy & Paste functionality routes
    Route::post('sku-reorder-levels/copy', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'copyReorderLevels']);
    Route::post('sku-reorder-levels/copy-to-periods', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'copyToMultiplePeriods']);
    Route::post('sku-reorder-levels/copy-to-skus', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'copyToMultipleSkus']);
    Route::get('sku-reorder-levels/sku-suggestions', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'getSkuSuggestions']);
});

// Move available-periods route outside the prefix group
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels/available-periods', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'getAvailablePeriods']);

// SKU Reorder Level for View & Print
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'getForPrint']);

// Test connection route
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels/test-connection', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'testConnection']);

// Test simple route
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels/test-simple', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'testSimple']);

// Test basic route
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels/test-basic', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'testBasic']);

// Simple test route
Route::get('test-sku-reorder', function() {
    return response()->json(['message' => 'Route working']);
});

// Test minimal route
Route::get('material-management/system-requirement/inventory-setup/sku-reorder-levels/test-minimal', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController::class, 'testMinimal']);

// Test controller route with closure
Route::get('test-controller', function() {
    try {
        return response()->json([
            'success' => true,
            'message' => 'Controller test successful',
            'data' => [
                'controller_working' => true,
                'timestamp' => now()->toISOString()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Controller test failed: ' . $e->getMessage()
        ], 500);
    }
});

// Test view-print with closure
Route::get('test-view-print', function() {
    try {
        $result = [
            [
                'sku' => 'TEST001',
                'sku_name' => 'Test SKU 1',
                'category_code' => 'CAT001',
                'type' => 'S',
                'uom' => 'PCS',
                'boh' => 100.00,
                'is_active' => true,
                'min_level' => 10.00,
                'max_level' => 50.00,
                'reorder_level' => 25.00,
            ],
            [
                'sku' => 'TEST002',
                'sku_name' => 'Test SKU 2',
                'category_code' => 'CAT002',
                'type' => 'NS',
                'uom' => 'KG',
                'boh' => 50.00,
                'is_active' => true,
                'min_level' => 5.00,
                'max_level' => 25.00,
                'reorder_level' => 15.00,
            ]
        ];

        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'View print test failed: ' . $e->getMessage()
        ], 500);
    }
});

// Simple fallback route for available periods
Route::get('available-periods-fallback', function() {
    try {
        $periods = \App\Models\SkuReorderLevel::select('period')
            ->distinct()
            ->orderBy('period')
            ->pluck('period');
        
        // If no periods exist in database, generate default periods for the next 12 months
        if ($periods->isEmpty()) {
            $periods = collect();
            $now = now();
            for ($i = 0; $i < 12; $i++) {
                $periods->push($now->copy()->addMonths($i)->format('m/Y'));
            }
        }
        
        return response()->json($periods);
    } catch (\Exception $e) {
        // Fallback: generate default periods if database query fails
        $periods = collect();
        $now = now();
        for ($i = 0; $i < 12; $i++) {
            $periods->push($now->copy()->addMonths($i)->format('m/Y'));
        }
        return response()->json($periods);
    }
});

Route::get('sku-suggestions-fallback', function(\Illuminate\Http\Request $request) {
    try {
        $search = $request->query('search', '');
        
        $skus = \App\Models\MmSku::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('sku', 'like', "%{$search}%")
                      ->orWhere('sku_name', 'like', "%{$search}%");
            })
            ->select('sku', 'sku_name', 'category_code')
            ->limit(20)
            ->get();
        
        return response()->json($skus);
    } catch (\Exception $e) {
        return response()->json([]);
    }
});

Route::post('copy-reorder-levels-fallback', function(\Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'source_sku_id' => 'required|exists:mm_skus,sku',
            'target_sku_id' => 'required|exists:mm_skus,sku',
            'source_period' => 'required|string|max:7',
            'target_period' => 'required|string|max:7',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        // Get source reorder level
        $sourceLevel = \App\Models\SkuReorderLevel::where('sku_id', $request->source_sku_id)
            ->where('period', $request->source_period)
            ->first();

        if (!$sourceLevel) {
            return response()->json(['error' => 'Source reorder level not found'], 404);
        }

        // Create or update target reorder level
        $targetLevel = \App\Models\SkuReorderLevel::updateOrCreate([
            'sku_id' => $request->target_sku_id,
            'period' => $request->target_period,
        ], [
            'min_level' => $sourceLevel->min_level,
            'max_level' => $sourceLevel->max_level,
            'reorder_level' => $sourceLevel->reorder_level,
        ]);

        \Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Reorder levels copied successfully',
            'data' => $targetLevel
        ]);

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        return response()->json(['error' => 'Failed to copy reorder levels: ' . $e->getMessage()], 500);
    }
});

// SKU Consumption Budget API routes
Route::prefix('material-management/system-requirement/inventory-setup')->group(function () {
    // SKU Consumption Budget View & Print - MUST BE BEFORE other routes
    Route::get('/sku-consumption-budgets/view-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getViewPrint']);
    
    Route::get('/sku-consumption-budgets', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'index']);
    Route::get('/sku-consumption-budgets/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'show']);
    Route::post('/sku-consumption-budgets', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'store']);
    Route::put('/sku-consumption-budgets/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'update']);
    Route::delete('/sku-consumption-budgets/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'destroy']);
    Route::get('/sku-consumption-budgets/by-sku/{skuId}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getBySku']);
    Route::get('/sku-consumption-budgets/by-month/{effectiveMonth}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getByEffectiveMonth']);
    Route::get('/sku-consumption-budgets/sku-suggestions', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getSkuSuggestions']);
    Route::get('/sku-consumption-budgets/available-months', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getAvailableMonths']);
    Route::post('/sku-consumption-budgets/bulk-store', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'bulkStore']);
    Route::get('/sku-consumption-budgets/summary', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuConsumptionBudgetController::class, 'getSummary']);
});

// Custom Tariff Code API routes
Route::prefix('material-management/system-requirement/inventory-setup')->group(function () {
    // Custom Tariff Code View & Print - MUST BE BEFORE other routes
    Route::get('/custom-tariff-codes/view-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'getViewPrint']);
    
    Route::get('/custom-tariff-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'index']);
    Route::get('/custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'show']);
    Route::post('/custom-tariff-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'store']);
    Route::put('/custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'update']);
    Route::delete('/custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'destroy']);
    Route::get('/custom-tariff-codes/suggestions', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'getSuggestions']);
    Route::get('/custom-tariff-codes/categories', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'getCategories']);
    Route::post('/custom-tariff-codes/calculate', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'calculateCustoms']);
    Route::post('/custom-tariff-codes/bulk-store', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'bulkStore']);
    Route::patch('/custom-tariff-codes/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'toggleActive']);
    Route::get('/custom-tariff-codes/summary', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'getSummary']);
    Route::get('/custom-tariff-codes/export', [App\Http\Controllers\MaterialManagement\SystemRequirement\CustomTariffCodeController::class, 'export']);
});

// SKU Custom Tariff Code API routes
Route::prefix('material-management/system-requirement/inventory-setup')->group(function () {
    Route::get('/sku-custom-tariff-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'index']);
    Route::get('/sku-custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'show']);
    Route::post('/sku-custom-tariff-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'store']);
    Route::put('/sku-custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'update']);
    Route::delete('/sku-custom-tariff-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'destroy']);
    Route::get('/sku-custom-tariff-codes/sku-suggestions', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'getSkuSuggestions']);
    Route::get('/sku-custom-tariff-codes/tariff-code-suggestions', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'getTariffCodeSuggestions']);
    Route::get('/sku-custom-tariff-codes/by-sku/{skuId}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'getBySkuId']);
    Route::post('/sku-custom-tariff-codes/calculate', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'calculateCustoms']);
    Route::post('/sku-custom-tariff-codes/bulk-store', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'bulkStore']);
    Route::patch('/sku-custom-tariff-codes/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'toggleActive']);
    Route::get('/sku-custom-tariff-codes/summary', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'getSummary']);
    Route::get('/sku-custom-tariff-codes/export', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'export']);
    Route::get('/sku-custom-tariff-codes/skus-without-tariff-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuCustomTariffCodeController::class, 'getSkusWithoutTariffCodes']);
});

// DR/CR Note API routes
Route::prefix('dr-cr-notes')->group(function () {
    Route::get('/', [App\Http\Controllers\DrCrNoteController::class, 'index']);
    Route::post('/', [App\Http\Controllers\DrCrNoteController::class, 'store']);
    Route::get('/customer-suggestions', [App\Http\Controllers\DrCrNoteController::class, 'getCustomerSuggestions']);
    Route::get('/summary', [App\Http\Controllers\DrCrNoteController::class, 'getSummary']);
    Route::get('/{id}', [App\Http\Controllers\DrCrNoteController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\DrCrNoteController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\DrCrNoteController::class, 'destroy']);
    Route::post('/{id}/approve', [App\Http\Controllers\DrCrNoteController::class, 'approve']);
    Route::post('/{id}/reject', [App\Http\Controllers\DrCrNoteController::class, 'reject']);
    Route::post('/{id}/post', [App\Http\Controllers\DrCrNoteController::class, 'post']);
});

// Unlock SKU Utility API routes
Route::prefix('material-management/unlock-sku-utility')->group(function () {
    Route::get('/locked-skus', [App\Http\Controllers\MaterialManagement\SystemRequirement\UnlockSkuUtilityController::class, 'getLockedSkus']);
    Route::post('/unlock/{sku}', [App\Http\Controllers\MaterialManagement\SystemRequirement\UnlockSkuUtilityController::class, 'unlockSku']);
    Route::post('/bulk-unlock', [App\Http\Controllers\MaterialManagement\SystemRequirement\UnlockSkuUtilityController::class, 'bulkUnlock']);
    Route::post('/unlock-stale', [App\Http\Controllers\MaterialManagement\SystemRequirement\UnlockSkuUtilityController::class, 'unlockStaleLocks']);
    Route::get('/statistics', [App\Http\Controllers\MaterialManagement\SystemRequirement\UnlockSkuUtilityController::class, 'getLockStatistics']);
});

// Test SKU reorder level controller
Route::get('test-sku-reorder-level-controller', function() {
    try {
        $controller = new App\Http\Controllers\MaterialManagement\SystemRequirement\SkuReorderLevelController();
        $request = new Illuminate\Http\Request();
        $response = $controller->getViewPrint($request);
        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Controller test failed: ' . $e->getMessage()
        ], 500);
    }
});

// GL Distribution API routes
Route::prefix('material-management')->group(function () {
    // SKU Routes
    Route::get('/skus', [MmSkuController::class, 'index']);
    Route::post('/skus', [MmSkuController::class, 'store']);
    Route::get('/skus/{sku}', [MmSkuController::class, 'show']);
    Route::put('/skus/{sku}', [MmSkuController::class, 'update']);
    Route::delete('/skus/{sku}', [MmSkuController::class, 'destroy']);
    Route::post('/skus/{sku}/change-code', [MmSkuController::class, 'changeSkuCode']);
    Route::patch('/skus/{sku}/toggle-active', [MmSkuController::class, 'toggleActive']);
    Route::post('/skus/bulk-toggle-active', [MmSkuController::class, 'bulkToggleActive']);
    Route::get('/skus/categories', [MmSkuController::class, 'getCategories']);
    Route::get('/skus/units', [MmSkuController::class, 'getUnits']);
    Route::get('/skus/types', [MmSkuController::class, 'getTypes']);
    Route::get('/skus/{sku_id}/balance', [MmSkuController::class, 'getSkuBalance']);

    // GL Distribution Routes
    Route::get('/gl-distributions/list', [MmGlDistributionController::class, 'getGlDistributions']);
    Route::get('/gl-distributions', [MmGlDistributionController::class, 'getGlDistributions']);
    Route::post('/gl-distributions', [MmGlDistributionController::class, 'store']);
    Route::put('/gl-distributions/{glDistribution}', [MmGlDistributionController::class, 'update']);
    Route::delete('/gl-distributions/{glDistribution}', [MmGlDistributionController::class, 'destroy']);
    Route::get('/chart-of-accounts', [MmGlDistributionController::class, 'getChartOfAccounts']);
});

// Customer Warehouse Location API routes
Route::prefix('customer-warehouse-locations')->group(function () {
    Route::get('/', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'index'])->name('customer-warehouse-locations.index');
    Route::post('/', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'store'])->name('customer-warehouse-locations.store');
    Route::get('/check/{customer_code}', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'check'])->name('customer-warehouse-locations.check');
    Route::get('/{customer_code}', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'show'])->name('customer-warehouse-locations.show');
    Route::put('/{customer_code}', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'update'])->name('customer-warehouse-locations.update');
    Route::delete('/{customer_code}', [App\Http\Controllers\CustomerWarehouseLocationController::class, 'destroy'])->name('customer-warehouse-locations.destroy');
});

// Customer Warehouse Requirement API routes
Route::get('/customer-warehouse-requirements', [CustomerWarehouseRequirementController::class, 'getAllRequirements']);
Route::get('/customer-warehouse-requirements/{customerCode}', [CustomerWarehouseRequirementController::class, 'getByCustomerCode']);
Route::post('/customer-warehouse-requirements', [CustomerWarehouseRequirementController::class, 'store']);
Route::put('/customer-warehouse-requirements/{customerCode}', [CustomerWarehouseRequirementController::class, 'update']);
Route::delete('/customer-warehouse-requirements/{customerCode}', [CustomerWarehouseRequirementController::class, 'destroy']);
Route::get('/warehouse-requirements/customers', [CustomerWarehouseRequirementController::class, 'getCustomers']);
Route::get('/warehouse-requirements/warehouse-locations', [CustomerWarehouseRequirementController::class, 'getWarehouseLocations']);
Route::get('/warehouse-requirements/delivery-order-formats', [CustomerWarehouseRequirementController::class, 'getDeliveryOrderFormats']);

// Update MC API Routes
Route::prefix('update-mc')->group(function () {
    Route::post('/search-ac', [UpdateMcController::class, 'searchAc']);
    Route::post('/search-mcs', [UpdateMcController::class, 'searchMcs']);
    Route::get('/master-cards', [UpdateMcController::class, 'apiIndex']);
});
