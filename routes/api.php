<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories',[Controllers\CategoryController::class,'all']);

//Products
Route::get('/products/fs',[Controllers\ProductController::class,'getProductsFromApi']);
Route::get('/products',[Controllers\ProductController::class,'all']);

//Orders
Route::post('/orders',[Controllers\OrderController::class,'store']);

//Clients
Route::post('/clients',[Controllers\ClientController::class,'store']);
Route::get('/clients/{codcliente}',[Controllers\ClientController::class,'get']);
Route::get('/clients',[Controllers\ClientController::class,'all']);

