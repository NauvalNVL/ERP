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

// Customer Accounts API routes
Route::get('/customer-accounts', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::post('/customer-accounts', [UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiUpdate']);
Route::get('/customers-with-status', [UpdateCustomerAccountController::class, 'apiIndex']);

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
Route::get('/corrugator-specs-by-product/export', [CorrugatorSpecByProductController::class, 'apiExport']);
Route::get('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiShow']);
Route::post('/corrugator-specs-by-product', [CorrugatorSpecByProductController::class, 'apiStore']);
Route::post('/corrugator-specs-by-product/batch', [CorrugatorSpecByProductController::class, 'apiBatchUpdate']);
Route::put('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiUpdate']);
Route::delete('/corrugator-specs-by-product/{id}', [CorrugatorSpecByProductController::class, 'apiDestroy']); 

// Roll Trim By Corrugator API Routes
Route::get('/roll-trim-by-corrugator/export', [RollTrimByCorrugatorController::class, 'apiExport']);
Route::get('/roll-trim-by-corrugator/flutes', [RollTrimByCorrugatorController::class, 'getPaperFlutes']);
Route::get('/roll-trim-by-corrugator', [RollTrimByCorrugatorController::class, 'apiIndex']);
Route::post('/roll-trim-by-corrugator', [RollTrimByCorrugatorController::class, 'apiStore']);
Route::put('/roll-trim-by-corrugator/{id}', [RollTrimByCorrugatorController::class, 'apiUpdate']);
Route::delete('/roll-trim-by-corrugator/{id}', [RollTrimByCorrugatorController::class, 'apiDestroy']); 

// Roll Trim By Product Design API routes
Route::get('/roll-trim-by-product-design', [RollTrimByProductDesignController::class, 'apiIndex']);
Route::post('/roll-trim-by-product-design', [RollTrimByProductDesignController::class, 'apiStore']);
Route::put('/roll-trim-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiUpdate']);
Route::delete('/roll-trim-by-product-design/{id}', [RollTrimByProductDesignController::class, 'apiDestroy']);
Route::get('/roll-trim-by-product-design/export', [RollTrimByProductDesignController::class, 'apiExport']);
Route::post('/roll-trim-by-product-design/seed', [RollTrimByProductDesignController::class, 'apiSeed']); 