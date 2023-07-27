<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api'
], function ($router) {
    
    /**
     * Authentication Module
     */
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
    

    /**
     * Products Module
     */
    
    Route::resource('invoice', InvoiceController::class);
    Route::post('invoice/create', [InvoiceController::class, 'create']);
    Route::post('invoice/edit', [InvoiceController::class, 'edit']);
    Route::get('invoice/show/{id}', [InvoiceController::class, 'show']);

    Route::resource('customer', CustomerController::class);
    Route::post('customer/create', [CustomerController::class, 'create']);
    Route::post('customer/edit', [CustomerController::class, 'edit']);
    Route::get('customer/show/{id}', [CustomerController::class, 'show']);


    Route::resource('products', ProductsController::class);
    Route::get('products/view/all', [ProductsController::class, 'indexAll']);
    Route::get('products/view/search', [ProductsController::class, 'search']);

});

