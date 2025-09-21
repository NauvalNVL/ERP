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

// Approver API routes
Route::get('/approvers', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'index']);
Route::post('/approvers', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'store']);
Route::get('/approvers/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'getForPrint']);
Route::get('/approvers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'show']);
Route::put('/approvers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'update']);
Route::delete('/approvers/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'destroy']);
Route::patch('/approvers/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\ApproverController::class, 'toggleActive']);

// Purchase Sub Control API routes
Route::get('/purchase-sub-controls', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'index']);
Route::post('/purchase-sub-controls', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'store']);
Route::get('/purchase-sub-controls/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'getForPrint']);
Route::get('/purchase-sub-controls/categories', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'getCategories']);
Route::get('/purchase-sub-controls/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'show']);
Route::put('/purchase-sub-controls/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'update']);
Route::patch('/purchase-sub-controls/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\PurchaseSubControlController::class, 'toggleActive']);

// SKU Item Note Analysis Group API routes
Route::get('/sku-item-note-analysis-groups', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'index']);
Route::post('/sku-item-note-analysis-groups', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'store']);
Route::get('/sku-item-note-analysis-groups/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'getForPrint']);
Route::get('/sku-item-note-analysis-groups/categories', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'getCategories']);
Route::get('/sku-item-note-analysis-groups/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'show']);
Route::put('/sku-item-note-analysis-groups/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'update']);
Route::delete('/sku-item-note-analysis-groups/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'destroy']);
Route::patch('/sku-item-note-analysis-groups/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController::class, 'toggleActive']);

// SKU Item Note Analysis Code API routes
Route::get('/sku-item-note-analysis-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'index']);
Route::post('/sku-item-note-analysis-codes', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'store']);
Route::get('/sku-item-note-analysis-codes/for-print', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'getForPrint']);
Route::get('/sku-item-note-analysis-codes/groups', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'getAnalysisGroups']);
Route::get('/sku-item-note-analysis-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'show']);
Route::put('/sku-item-note-analysis-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'update']);
Route::delete('/sku-item-note-analysis-codes/{id}', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'destroy']);
Route::patch('/sku-item-note-analysis-codes/{id}/toggle-active', [App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisCodeController::class, 'toggleActive']);

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
Route::get('/update-mc/master-cards/{mcSeq}', [App\Http\Controllers\UpdateMcController::class, 'apiShow']);
Route::post('/update-mc/master-cards', [App\Http\Controllers\UpdateMcController::class, 'store']);

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

// Inventory Reports API Routes
Route::prefix('material-management/inventory-reports')->group(function () {
    Route::get('/sku-balance', function() {
        return response()->json([
            'data' => [
                [
                    'sku' => 'SKU001',
                    'sku_name' => 'Raw Material A',
                    'category_code' => 'CAT001',
                    'type' => 'S',
                    'balance' => 1000,
                    'unit_price' => 50000,
                    'total_value' => 50000000,
                    'status' => 'Active'
                ]
            ],
            'summary' => [
                'totalSkus' => 1,
                'totalBalance' => 1000,
                'totalValue' => 50000000,
                'lowStockItems' => 0
            ],
            'pagination' => [
                'from' => 1,
                'to' => 1,
                'total' => 1
            ]
        ]);
    });
    
    Route::post('/sku-balance/generate', function() {
        return response()->json(['message' => 'Report generated successfully']);
    });
    
    Route::post('/sku-balance/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::get('/sku-reorder', function() {
        return response()->json([
            'data' => [
                [
                    'sku' => 'SKU001',
                    'sku_name' => 'Raw Material A',
                    'category_code' => 'CAT001',
                    'current_balance' => 50,
                    'min_level' => 100,
                    'reorder_level' => 150,
                    'max_level' => 500,
                    'shortage' => 50,
                    'status' => 'Below Min'
                ]
            ],
            'summary' => [
                'belowMinLevel' => 1,
                'atReorderLevel' => 0,
                'aboveMaxLevel' => 0,
                'totalValue' => 2500000
            ],
            'pagination' => [
                'from' => 1,
                'to' => 1,
                'total' => 1
            ]
        ]);
    });
    
    Route::post('/sku-reorder/generate', function() {
        return response()->json(['message' => 'Report generated successfully']);
    });
    
    Route::post('/sku-reorder/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::get('/sku-ledger', function() {
        return response()->json([
            'data' => [
                [
                    'id' => 1,
                    'transaction_date' => '2024-01-15',
                    'reference_number' => 'RC-2024-001',
                    'transaction_type' => 'RC',
                    'description' => 'Receive from supplier',
                    'in_quantity' => 100,
                    'out_quantity' => null,
                    'running_balance' => 100,
                    'unit_price' => 50000,
                    'total_value' => 5000000
                ]
            ],
            'summary' => [
                'totalIn' => 100,
                'totalOut' => 0,
                'netMovement' => 100,
                'totalValue' => 5000000
            ],
            'pagination' => [
                'from' => 1,
                'to' => 1,
                'total' => 1
            ]
        ]);
    });
    
    Route::post('/sku-ledger/generate', function() {
        return response()->json(['message' => 'Report generated successfully']);
    });
    
    Route::post('/sku-ledger/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::get('/sku-aging', function() {
        return response()->json([
            'data' => [
                [
                    'sku' => 'SKU001',
                    'sku_name' => 'Raw Material A',
                    'category_code' => 'CAT001',
                    'current' => 500,
                    'days_31_60' => 200,
                    'days_61_90' => 150,
                    'days_91_180' => 100,
                    'over_180' => 50,
                    'total_value' => 50000000,
                    'status' => 'Current'
                ]
            ],
            'summary' => [
                'current' => 500,
                'days_31_60' => 200,
                'days_61_90' => 150,
                'days_91_180' => 100,
                'over_180' => 50
            ],
            'pagination' => [
                'from' => 1,
                'to' => 1,
                'total' => 1
            ]
        ]);
    });
    
    Route::post('/sku-aging/generate', function() {
        return response()->json(['message' => 'Report generated successfully']);
    });
    
    Route::post('/sku-aging/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::get('/sku-open-item-aging', function() {
        return response()->json([
            'data' => [
                [
                    'id' => 1,
                    'transaction_date' => '2024-01-15',
                    'sku_code' => 'SKU001',
                    'reference_number' => 'RC-2024-001',
                    'transaction_type' => 'RC',
                    'quantity' => 100,
                    'unit_price' => 50000,
                    'total_value' => 5000000,
                    'age_days' => 15,
                    'status' => 'Current'
                ]
            ],
            'summary' => [
                'current' => 1,
                'days_31_60' => 0,
                'days_61_90' => 0,
                'days_91_180' => 0,
                'over_180' => 0
            ],
            'pagination' => [
                'from' => 1,
                'to' => 1,
                'total' => 1
            ]
        ]);
    });
    
    Route::post('/sku-open-item-aging/generate', function() {
        return response()->json(['message' => 'Report generated successfully']);
    });
    
    Route::post('/sku-open-item-aging/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::get('/inquire-sku-account', function() {
        return response()->json([
            'sku' => [
                'sku_code' => 'SKU001',
                'sku_name' => 'Raw Material A',
                'category_code' => 'CAT001',
                'type' => 'S',
                'unit' => 'PCS',
                'current_balance' => 1000,
                'total_value' => 50000000,
                'min_level' => 100,
                'reorder_level' => 150,
                'max_level' => 500,
                'unit_price' => 50000,
                'last_updated' => '2024-01-15',
                'status' => 'Active'
            ],
            'transactions' => [
                [
                    'id' => 1,
                    'transaction_date' => '2024-01-15',
                    'reference_number' => 'RC-2024-001',
                    'transaction_type' => 'RC',
                    'in_quantity' => 100,
                    'out_quantity' => null,
                    'running_balance' => 100,
                    'total_value' => 5000000
                ]
            ],
            'aging' => [
                'current' => 500,
                'days_31_60' => 200,
                'days_61_90' => 150,
                'days_91_180' => 100,
                'over_180' => 50
            ]
        ]);
    });
    
    Route::post('/inquire-sku-account/export', function() {
        return response()->json(['message' => 'Report exported successfully']);
    });
    
    Route::post('/inquire-sku-account/print', function() {
        return response()->json(['message' => 'Report printed successfully']);
    });
});
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
    Route::get('/check-mcs/{mcsNumber}', [UpdateMcController::class, 'checkMcs']);
});

// FG Stock-In by WO API routes
Route::prefix('fg-stock-in-wo')->group(function () {
    Route::get('/work-orders', [App\Http\Controllers\Api\FgStockInByWoController::class, 'getWorkOrders']);
    Route::get('/work-orders/{woNumber}/details', [App\Http\Controllers\Api\FgStockInByWoController::class, 'getWorkOrderDetails']);
    Route::post('/', [App\Http\Controllers\Api\FgStockInByWoController::class, 'store']);
    Route::get('/', [App\Http\Controllers\Api\FgStockInByWoController::class, 'index']);
});

// Work Orders API routes
Route::get('/work-orders', [App\Http\Controllers\Api\FgStockInByWoController::class, 'getWorkOrders']);

// Purchase Requisition API Routes
Route::prefix('purchase-requisitions')->group(function () {
    Route::get('/', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'apiIndex']);
    Route::post('/', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'destroy']);
    
    // PR Actions
    Route::post('/{id}/submit', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'submit']);
    Route::post('/{id}/approve', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'approve']);
    Route::post('/{id}/reject', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'reject']);
    Route::post('/{id}/cancel', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'cancel']);
    
    // Utility endpoints
    Route::get('/statistics/dashboard', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'getStatistics']);
    Route::get('/approvals/my-pending', [App\Http\Controllers\MaterialManagement\PurchaseOrder\PurchaseRequisitionController::class, 'myPendingApprovals']);
});

    // Vendor API Routes
    Route::prefix('vendors')->group(function () {
        Route::get('/', [App\Http\Controllers\VendorController::class, 'index']);
        Route::get('/search', [App\Http\Controllers\VendorController::class, 'search']);
        Route::get('/suggestions', [App\Http\Controllers\VendorController::class, 'suggestions']);
        Route::get('/{apAcNumber}', [App\Http\Controllers\VendorController::class, 'show']);
    });

    // Location API Routes
    Route::prefix('locations')->group(function () {
        Route::get('/', function () {
            return response()->json([
                [
                    'id' => 1,
                    'code' => 'WH001',
                    'name' => 'Main Warehouse',
                    'type' => 'Warehouse',
                    'status' => 'Active'
                ],
                [
                    'id' => 2,
                    'code' => 'WH002',
                    'name' => 'Secondary Warehouse',
                    'type' => 'Warehouse',
                    'status' => 'Active'
                ],
                [
                    'id' => 3,
                    'code' => 'ST001',
                    'name' => 'Store Room 1',
                    'type' => 'Storage',
                    'status' => 'Active'
                ],
                [
                    'id' => 4,
                    'code' => 'ST002',
                    'name' => 'Store Room 2',
                    'type' => 'Storage',
                    'status' => 'Inactive'
                ],
                [
                    'id' => 5,
                    'code' => 'OF001',
                    'name' => 'Office Storage',
                    'type' => 'Office',
                    'status' => 'Active'
                ]
            ]);
        });
    });

    // Purchase Sub Control API Routes
    Route::prefix('purchase-sub-controls')->group(function () {
        Route::get('/', function () {
            return response()->json([
                [
                    'id' => 1,
                    'psc_code' => 'PSC001',
                    'psc_name' => 'Raw Materials Control',
                    'category' => 'Raw Materials',
                    'purchaser' => 'John Doe',
                    'status' => 'Active'
                ],
                [
                    'id' => 2,
                    'psc_code' => 'PSC002',
                    'psc_name' => 'Packaging Materials Control',
                    'category' => 'Packaging',
                    'purchaser' => 'Jane Smith',
                    'status' => 'Active'
                ],
                [
                    'id' => 3,
                    'psc_code' => 'PSC003',
                    'psc_name' => 'Office Supplies Control',
                    'category' => 'Office Supplies',
                    'purchaser' => 'Mike Johnson',
                    'status' => 'Active'
                ],
                [
                    'id' => 4,
                    'psc_code' => 'PSC004',
                    'psc_name' => 'Maintenance Supplies Control',
                    'category' => 'Maintenance',
                    'purchaser' => 'Sarah Wilson',
                    'status' => 'Inactive'
                ],
                [
                    'id' => 5,
                    'psc_code' => 'PSC005',
                    'psc_name' => 'IT Equipment Control',
                    'category' => 'IT Equipment',
                    'purchaser' => 'David Brown',
                    'status' => 'Active'
                ],
                [
                    'id' => 6,
                    'psc_code' => 'PSC006',
                    'psc_name' => 'Safety Equipment Control',
                    'category' => 'Safety Equipment',
                    'purchaser' => 'Lisa Davis',
                    'status' => 'Active'
                ],
                [
                    'id' => 7,
                    'psc_code' => 'PSC007',
                    'psc_name' => 'Chemical Supplies Control',
                    'category' => 'Chemicals',
                    'purchaser' => 'Tom Miller',
                    'status' => 'Pending'
                ],
                [
                    'id' => 8,
                    'psc_code' => 'PSC008',
                    'psc_name' => 'Tooling Control',
                    'category' => 'Tools',
                    'purchaser' => 'Amy Garcia',
                    'status' => 'Active'
                ]
            ]);
        });
    });

    // PO Arrival Schedule API Routes
    Route::prefix('po-arrival-schedule')->group(function () {
        Route::post('/generate', function (Request $request) {
            // Mock implementation - replace with actual report generation
            $filters = $request->all();
            
            // Simulate report generation delay
            sleep(2);
            
            // Return mock Excel file (CSV for now)
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="PO_Arrival_Schedule.csv"',
            ];
            
            $data = [
                ['PO Number', 'Supplier', 'SKU', 'Description', 'Quantity', 'ETA', 'Status'],
                ['PO-2024-001', 'ABADI KARYA MAKMUR', 'SKU001', 'Bearing Type A', '100', '2024-06-25', 'Outstanding'],
                ['PO-2024-002', 'ACCURA SOLIDTECH', 'SKU002', 'Locknut Type B', '50', '2024-06-28', 'Partial'],
                ['PO-2024-003', 'ACEN JAYA ELEKTRIK', 'SKU003', 'Push Button', '200', '2024-07-01', 'Completed'],
            ];
            
            $csv = '';
            foreach ($data as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            
            return response($csv, 200, $headers);
        });
    });

    // PR/PO Reports API Routes
    Route::prefix('pr-po-reports')->group(function () {
        Route::post('/generate', function (Request $request) {
            // Mock implementation for PR/PO Reports
            $filters = $request->all();
            sleep(2);
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="PR_PO_Reports.csv"',
            ];
            
            $data = [
                ['PR Number', 'PO Number', 'Supplier', 'SKU', 'Quantity', 'Amount', 'Status'],
                ['PR-2024-001', 'PO-2024-001', 'ABADI KARYA MAKMUR', 'SKU001', '100', '15000000', 'Approved'],
                ['PR-2024-002', 'PO-2024-002', 'ACCURA SOLIDTECH', 'SKU002', '50', '8500000', 'Pending'],
            ];
            
            $csv = '';
            foreach ($data as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            
            return response($csv, 200, $headers);
        });
        
        Route::post('/preview', function (Request $request) {
            return response()->json([
                'summary' => [
                    'totalPR' => 45,
                    'totalPO' => 38,
                    'totalAmount' => 250000000,
                    'avgProcessingTime' => 4.5
                ],
                'prpoData' => [
                    [
                        'id' => 1,
                        'pr_number' => 'PR-2024-001',
                        'po_number' => 'PO-2024-001',
                        'supplier' => 'ABADI KARYA MAKMUR',
                        'sku' => 'SKU001',
                        'quantity' => 100,
                        'amount' => 15000000,
                        'pr_status' => 'Approved',
                        'po_status' => 'Approved'
                    ],
                    [
                        'id' => 2,
                        'pr_number' => 'PR-2024-002',
                        'po_number' => 'PO-2024-002',
                        'supplier' => 'ACCURA SOLIDTECH',
                        'sku' => 'SKU002',
                        'quantity' => 50,
                        'amount' => 8500000,
                        'pr_status' => 'Pending',
                        'po_status' => 'Draft'
                    ]
                ]
            ]);
        });
        
        Route::get('/recent', function () {
            return response()->json([
                [
                    'id' => 1,
                    'generatedDate' => '2024-01-20',
                    'period' => '01/2024',
                    'recordCount' => 150,
                    'generatedBy' => 'Admin User'
                ]
            ]);
        });
    });

    // PO RC & RT Reports API Routes
    Route::prefix('po-rc-rt-reports')->group(function () {
        Route::post('/generate', function (Request $request) {
            $filters = $request->all();
            sleep(2);
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="PO_RC_RT_Report.csv"',
            ];
            
            $data = [
                ['Date', 'Type', 'PO Number', 'Vendor', 'SKU', 'Qty', 'Amount', 'Status'],
                ['2024-01-15', 'RC', 'PO-2024-001', 'ABADI KARYA MAKMUR', 'SKU001', '100', '15000000', 'Posted'],
                ['2024-01-16', 'RT', 'PO-2024-002', 'ACCURA SOLIDTECH', 'SKU002', '10', '1500000', 'Draft'],
            ];
            
            $csv = '';
            foreach ($data as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            
            return response($csv, 200, $headers);
        });
        
        Route::post('/preview', function (Request $request) {
            return response()->json([
                'summary' => [
                    'totalRC' => 25,
                    'totalRT' => 5,
                    'totalAmount' => 'Rp 250,000,000',
                    'avgProcessingTime' => 3.2
                ],
                'transactions' => [
                    [
                        'id' => 1,
                        'date' => '2024-01-15',
                        'type' => 'RC',
                        'poNumber' => 'PO-2024-001',
                        'vendor' => 'ABADI KARYA MAKMUR',
                        'sku' => 'SKU001',
                        'quantity' => 100,
                        'amount' => 15000000,
                        'status' => 'Posted'
                    ]
                ]
            ]);
        });
        
        Route::get('/recent', function () {
            return response()->json([
                [
                    'id' => 1,
                    'generatedDate' => '2024-01-20',
                    'period' => '01/2024',
                    'recordCount' => 30,
                    'generatedBy' => 'Admin User'
                ]
            ]);
        });
    });

    // PSC Reports API Routes
    Route::prefix('psc-reports')->group(function () {
        Route::post('/generate', function (Request $request) {
            $filters = $request->all();
            sleep(2);
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="PSC_Report.csv"',
            ];
            
            $data = [
                ['PSC Code', 'PSC Name', 'Category', 'Purchaser', 'SKU Count', 'Total Value', 'Status'],
                ['PSC001', 'Raw Materials Control', 'Raw Materials', 'John Doe', '150', '250000000', 'Active'],
                ['PSC002', 'Packaging Materials Control', 'Packaging', 'Jane Smith', '75', '120000000', 'Active'],
            ];
            
            $csv = '';
            foreach ($data as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            
            return response($csv, 200, $headers);
        });
        
        Route::post('/preview', function (Request $request) {
            return response()->json([
                'summary' => [
                    'totalPSC' => 8,
                    'activePSC' => 7,
                    'totalItems' => 500,
                    'avgValue' => 'Rp 45,000,000'
                ],
                'pscData' => [
                    [
                        'id' => 1,
                        'psc_code' => 'PSC001',
                        'category' => 'Raw Materials',
                        'purchaser' => 'John Doe',
                        'sku_count' => 150,
                        'total_value' => 250000000,
                        'status' => 'Active',
                        'last_activity' => '2024-01-20'
                    ]
                ]
            ]);
        });
        
        Route::get('/recent', function () {
            return response()->json([
                [
                    'id' => 1,
                    'generatedDate' => '2024-01-20',
                    'period' => '01/2024',
                    'recordCount' => 8,
                    'generatedBy' => 'Admin User'
                ]
            ]);
        });
    });

    // SKU Historical Price Reports API Routes
    Route::prefix('sku-historical-price-reports')->group(function () {
        Route::post('/generate', function (Request $request) {
            $filters = $request->all();
            sleep(2);
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="SKU_Historical_Price_Report.csv"',
            ];
            
            $data = [
                ['Date', 'SKU', 'Vendor', 'Price Type', 'Price', 'Currency', 'Change', 'Source'],
                ['2024-01-15', 'SKU001', 'ABADI KARYA MAKMUR', 'Purchase', '150000', 'IDR', '+5.2%', 'PO'],
                ['2024-01-16', 'SKU002', 'ACCURA SOLIDTECH', 'Standard', '85000', 'IDR', '-2.1%', 'Contract'],
            ];
            
            $csv = '';
            foreach ($data as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            
            return response($csv, 200, $headers);
        });
        
        Route::post('/preview', function (Request $request) {
            return response()->json([
                'summary' => [
                    'totalRecords' => 1250,
                    'uniqueSkus' => 150,
                    'avgPrice' => 125000,
                    'priceChanges' => 45
                ],
                'priceHistory' => [
                    [
                        'id' => 1,
                        'date' => '2024-01-15',
                        'sku' => 'SKU001',
                        'vendor' => 'ABADI KARYA MAKMUR',
                        'price_type' => 'Purchase',
                        'price' => 150000,
                        'currency' => 'IDR',
                        'price_change' => 5.2,
                        'source' => 'PO'
                    ]
                ],
                'priceTrends' => [
                    ['date' => '2024-01-01', 'avg_price' => 120000],
                    ['date' => '2024-01-15', 'avg_price' => 125000]
                ]
            ]);
        });
        
        Route::get('/recent', function () {
            return response()->json([
                [
                    'id' => 1,
                    'generatedDate' => '2024-01-20',
                    'period' => '2024-01-01 to 2024-01-20',
                    'recordCount' => 1250,
                    'generatedBy' => 'Admin User'
                ]
            ]);
        });
    });

// Purchase Order API Routes
Route::prefix('purchase-orders')->group(function () {
    Route::get('/', function () {
        // Return mock data for now - replace with actual controller
        return response()->json([
            [
                'id' => 1,
                'po_number' => 'PO-2024-001',
                'supplier' => 'PT Supplier ABC',
                'approved_date' => '2024-01-15',
                'rejected_date' => '2024-01-15',
                'rejection_reason' => 'Price too high compared to market rate',
                'delivery_status' => 'Pending',
                'total_amount' => 15000000,
                'status' => 'Rejected'
            ],
            [
                'id' => 2,
                'po_number' => 'PO-2024-002', 
                'supplier' => 'CV Supplier XYZ',
                'approved_date' => '2024-01-16',
                'rejected_date' => '2024-01-16',
                'rejection_reason' => 'Incomplete documentation and specifications',
                'delivery_status' => 'In Transit',
                'total_amount' => 8500000,
                'status' => 'Approved'
            ]
        ]);
    });
    Route::post('/{id}/cancel', function ($id) {
        return response()->json(['message' => 'Purchase order cancelled successfully']);
    });
    Route::post('/{id}/amend-rejected', function ($id) {
        return response()->json(['message' => 'Amendment notes recorded successfully']);
    });
});

// RC/RT API Routes
Route::prefix('rc-rt')->group(function () {
    // RC Transactions
    Route::get('/rc-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGetRcTransactions']);
    Route::get('/rt-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGetRtTransactions']);
    
    // CRUD Operations
    Route::post('/transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiStore']);
    Route::put('/transactions/{id}', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiUpdate']);
    Route::post('/transactions/{id}/post', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiPost']);
    Route::post('/transactions/{id}/cancel', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiCancel']);
    
    // Lookup Data
    Route::get('/suppliers', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGetSuppliers']);
    Route::get('/skus', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGetSkus']);
    Route::get('/locations', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGetLocations']);
    
    // Reports
    Route::post('/generate-report', [App\Http\Controllers\MaterialManagement\InventoryControl\RcRtController::class, 'apiGenerateReport']);
});

// DR/CN API Routes
Route::prefix('dr-cn')->group(function () {
    // Generate Note Number
    Route::post('/generate-number', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGenerateNoteNumber']);
    
    // DN Transactions
    Route::get('/dn-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGetDnTransactions']);
    Route::get('/cn-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGetCnTransactions']);
    
    // CRUD Operations
    Route::post('/transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiStore']);
    Route::put('/transactions/{id}', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiUpdate']);
    Route::post('/transactions/{id}/post', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiPost']);
    Route::post('/transactions/{id}/cancel', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiCancel']);
    
    // Lookup Data
    Route::get('/vendors', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGetVendors']);
    Route::get('/skus', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGetSkus']);
    Route::get('/locations', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGetLocations']);
    
    // Reports
    Route::post('/generate-report', [App\Http\Controllers\MaterialManagement\InventoryControl\DrCnController::class, 'apiGenerateReport']);
});

// IS/MI/MO/LT API Routes
Route::prefix('is-mi-mo-lt')->group(function () {
    // IS Transactions
    Route::get('/is-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetIsTransactions']);
    Route::get('/mi-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetMiTransactions']);
    Route::get('/mo-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetMoTransactions']);
    Route::get('/lt-transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetLtTransactions']);
    
    // All Transactions (for ViewPrintLog)
    Route::get('/transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetTransactions']);
    
    // CRUD Operations
    Route::post('/transactions', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiStore']);
    Route::put('/transactions/{id}', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiUpdate']);
    Route::post('/transactions/{id}/post', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiPost']);
    Route::post('/transactions/{id}/cancel', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiCancel']);
    
    // Generate Number
    Route::post('/generate-number', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGenerateNumber']);
    
    // Lookup Data
    Route::get('/skus', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetSkus']);
    Route::get('/locations', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetLocations']);
    Route::get('/categories', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGetCategories']);
    
    // Reports
    Route::post('/generate-report', [App\Http\Controllers\MaterialManagement\InventoryControl\IsMiMoLtController::class, 'apiGenerateReport']);
});

// Inventory Period-End Closing API Routes
Route::prefix('material-management/inventory-control/period-end-closing')->group(function () {
    Route::get('/current-period', function() {
        return response()->json([
            'current_period' => '6 2025',
            'next_period' => '7 2025',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ]);
    });

    Route::get('/transaction-counts', function() {
        return response()->json([
            'rc_count' => 441,
            'rt_count' => 3,
            'is_count' => 1432,
            'mi_count' => 3,
            'mo_count' => 0,
            'dn_count' => 0,
            'cn_count' => 0,
            'lt_count' => 0,
            'zero_tran_status' => 'Zero Tran is Allowed to Close'
        ]);
    });

    Route::post('/perform-closing', function() {
        // Simulate processing time
        sleep(2);
        
        return response()->json([
            'success' => true,
            'message' => 'Inventory period closed successfully',
            'new_current_period' => '7 2025',
            'new_next_period' => '8 2025',
            'closed_at' => now()->format('Y-m-d H:i:s')
        ]);
    });

    Route::get('/closing-status', function() {
        return response()->json([
            'can_close' => true,
            'pending_transactions' => 0,
            'warnings' => [
                'Ensure nobody is using the Inventory Control module.',
                'Ensure that you have already backup the data.'
            ]
        ]);
    });
});

// Inventory Stock-Take API Routes
Route::prefix('material-management/inventory-control/stock-take')->group(function () {
    Route::get('/current-batch', function() {
        return response()->json([
            'current_period' => '6 2025',
            'last_batch' => [
                'run_period' => '5 2025',
                'run_date' => '26/05/2025',
                'run_time' => '09:13',
                'run_uid' => 'acc03',
                'run_ref' => 'STOCK OPNAME 05/2025'
            ]
        ]);
    });

    Route::post('/create-batch', function() {
        return response()->json([
            'success' => true,
            'message' => 'Batch created successfully',
            'batch_ref' => 'STOCK OPNAME 06/2025'
        ]);
    });

    Route::get('/sku-list', function() {
        return response()->json([
            ['sku_code' => '001-A01001', 'sku_name' => 'ANNELING WIRE 2.8MM (KAWAT PRESS BALLER)', 'category' => '001', 'uom' => 'KG'],
            ['sku_code' => '001-A02003', 'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)', 'category' => '001', 'uom' => 'BAG'],
            ['sku_code' => '001-A03001', 'sku_name' => 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)', 'category' => '001', 'uom' => 'KG'],
            ['sku_code' => '001-B01001', 'sku_name' => 'BORAK', 'category' => '001', 'uom' => 'KG'],
            ['sku_code' => '001-B03001', 'sku_name' => 'BEDAK POWDER', 'category' => '001', 'uom' => 'BTL'],
            ['sku_code' => '007-S01327', 'sku_name' => 'SERVICE AC SERVO DRIVER TIPE DASD-S60 SPEA', 'category' => '007', 'uom' => 'UNIT']
        ]);
    });

    Route::post('/save-stock-take-data', function() {
        return response()->json([
            'success' => true,
            'message' => 'Stock-take data saved successfully'
        ]);
    });

    Route::get('/stock-take-data', function() {
        return response()->json([
            ['sku' => '001-A01001', 'name' => 'ANNELING WIRE 2.8MM (KAWAT PRESS BALLER)', 'loc' => '006', 'ref' => '001-A01001-003'],
            ['sku' => '001-A02003', 'name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)', 'loc' => '002', 'ref' => '001-A02003-005'],
            ['sku' => '001-A03001', 'name' => 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)', 'loc' => '003', 'ref' => '001-A03001-001'],
            ['sku' => '001-B01001', 'name' => 'BORAK', 'loc' => '001', 'ref' => '001-B01001-002'],
            ['sku' => '001-B03001', 'name' => 'BEDAK POWDER', 'loc' => '012', 'ref' => '001-B03001-004']
        ]);
    });

    Route::post('/generate-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'report_id' => 'STK-' . date('Ymd-His'),
            'records' => 23,
            'pages' => 2
        ]);
    });

    Route::post('/generate-matching-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Matching report generated successfully',
            'matched_records' => 2,
            'unmatched_records' => 0,
            'report_id' => 'MATCH-' . date('Ymd-His')
        ]);
    });
});

// Setup Account API Routes
Route::prefix('material-management/account/setup-account')->group(function () {
    // Purchase SKU Accounts
    Route::get('/purchase-sku-accounts', function() {
        return response()->json([
            ['sku_code' => '001-A01001', 'sku_name' => 'ANNELING WIRE 2.8MM', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-A02003', 'sku_name' => 'ARMEX BAKING SODA POWDER', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-A03001', 'sku_name' => 'ALUMINIUM CHLOROHYDRANT', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-B01001', 'sku_name' => 'BORAK', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-B03001', 'sku_name' => 'BEDAK POWDER', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials']
        ]);
    });

    Route::post('/purchase-sku-accounts', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase SKU account setup saved successfully'
        ]);
    });

    // Purchase Tax Accounts
    Route::get('/purchase-tax-accounts', function() {
        return response()->json([
            ['tax_type' => 'VAT', 'tax_rate' => '11%', 'account_code' => '2100', 'account_name' => 'VAT Payable'],
            ['tax_type' => 'PPh21', 'tax_rate' => '2%', 'account_code' => '2101', 'account_name' => 'PPh21 Payable'],
            ['tax_type' => 'PPh22', 'tax_rate' => '1.5%', 'account_code' => '2102', 'account_name' => 'PPh22 Payable'],
            ['tax_type' => 'PPh23', 'tax_rate' => '2%', 'account_code' => '2103', 'account_name' => 'PPh23 Payable']
        ]);
    });

    Route::post('/purchase-tax-accounts', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase tax account setup saved successfully'
        ]);
    });

    // Purchase DN/CN Accounts
    Route::get('/purchase-dn-cn-accounts', function() {
        return response()->json([
            ['transaction_type' => 'DN', 'account_code' => '1200', 'account_name' => 'Accounts Payable - DN'],
            ['transaction_type' => 'CN', 'account_code' => '1201', 'account_name' => 'Accounts Payable - CN']
        ]);
    });

    Route::post('/purchase-dn-cn-accounts', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase DN/CN account setup saved successfully'
        ]);
    });

    // Inventory SKU Accounts
    Route::get('/inventory-sku-accounts', function() {
        return response()->json([
            ['sku_code' => '001-A01001', 'sku_name' => 'ANNELING WIRE 2.8MM', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-A02003', 'sku_name' => 'ARMEX BAKING SODA POWDER', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-A03001', 'sku_name' => 'ALUMINIUM CHLOROHYDRANT', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-B01001', 'sku_name' => 'BORAK', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials'],
            ['sku_code' => '001-B03001', 'sku_name' => 'BEDAK POWDER', 'account_code' => '1100', 'account_name' => 'Inventory - Raw Materials']
        ]);
    });

    Route::post('/inventory-sku-accounts', function() {
        return response()->json([
            'success' => true,
            'message' => 'Inventory SKU account setup saved successfully'
        ]);
    });

    // Purchase AP Accounts
    Route::get('/purchase-ap-accounts', function() {
        return response()->json([
            ['vendor_code' => 'V001', 'vendor_name' => 'PT SUPPLIER UTAMA', 'account_code' => '2000', 'account_name' => 'Accounts Payable'],
            ['vendor_code' => 'V002', 'vendor_name' => 'PT SUPPLIER SEKUNDER', 'account_code' => '2000', 'account_name' => 'Accounts Payable'],
            ['vendor_code' => 'V003', 'vendor_name' => 'PT SUPPLIER TERSIER', 'account_code' => '2000', 'account_name' => 'Accounts Payable']
        ]);
    });

    // Generate Reports
    Route::post('/generate-purchase-sku-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase SKU accounts report generated successfully',
            'report_id' => 'PSKU-' . date('Ymd-His'),
            'records' => 5,
            'pages' => 1
        ]);
    });

    Route::post('/generate-purchase-tax-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase tax accounts report generated successfully',
            'report_id' => 'PTAX-' . date('Ymd-His'),
            'records' => 4,
            'pages' => 1
        ]);
    });

    Route::post('/generate-purchase-dn-cn-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase DN/CN accounts report generated successfully',
            'report_id' => 'PDNCN-' . date('Ymd-His'),
            'records' => 2,
            'pages' => 1
        ]);
    });

    Route::post('/generate-inventory-sku-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Inventory SKU accounts report generated successfully',
            'report_id' => 'ISKU-' . date('Ymd-His'),
            'records' => 5,
            'pages' => 1
        ]);
    });

    Route::post('/generate-purchase-ap-report', function() {
        return response()->json([
            'success' => true,
            'message' => 'Purchase AP accounts report generated successfully',
            'report_id' => 'PAP-' . date('Ymd-His'),
            'records' => 3,
            'pages' => 1
        ]);
    });
});

// RC Posting Batch API Routes
Route::prefix('material-management/accounts/rc-posting-batch')->group(function () {
    Route::get('/current-periods', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'getCurrentPeriods']);
    Route::get('/available-rc-notes', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'getAvailableRcNotes']);
    Route::post('/prepare', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'prepareBatchAction']);
    Route::post('/cancel', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'cancelBatchAction']);
    Route::get('/batches', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'getBatches']);
    Route::post('/confirm-to-post', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'confirmToPostAction']);
    Route::get('/batch-details', [App\Http\Controllers\MaterialManagement\Accounts\RcPostingBatchController::class, 'getBatchDetails']);
});