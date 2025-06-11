<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandardFormulaController;
use App\Http\Controllers\SOConfigController;

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