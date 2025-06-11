<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandardFormulaController;
use App\Http\Controllers\SOConfigController;
use App\Http\Controllers\UpdateCustomerAccountController;
use App\Http\Controllers\CustomerAlternateAddressController;
use App\Http\Controllers\TestController;

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

// Test API route
Route::get('/test-customers', [App\Http\Controllers\TestController::class, 'testCustomers']);

// SO Config API routes
Route::post('/so-config', [SOConfigController::class, 'apiStore']); 

// Customer Accounts API routes
Route::get('/customer-accounts', [UpdateCustomerAccountController::class, 'apiIndex']);
Route::post('/customer-accounts', [UpdateCustomerAccountController::class, 'apiStore']);
Route::put('/customer-accounts/{id}', [UpdateCustomerAccountController::class, 'apiUpdate']);
Route::get('/customers-with-status', [UpdateCustomerAccountController::class, 'apiIndex']);

// Customer Alternate Address API routes
Route::get('/customer-alternate-addresses', [CustomerAlternateAddressController::class, 'apiIndex']); 