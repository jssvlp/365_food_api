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
Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login'])->name('login');
    Route::post('login/fs', [\App\Http\Controllers\Api\V1\AuthController::class, 'loginFacturasCript'])->name('login.fs');

    //loginFacturasCript
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me'])->name('me');
    Route::post('forgot',[Controllers\Api\V1\AuthController::class, 'forgot']);
    Route::post('');
    Route::post('signup',[Controllers\Api\V1\AuthController::class, 'signup']);
    Route::post('signout', [Controllers\Api\V1\AuthController::class, 'logout']);

    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function(){
    Route::get('/products/noauth',[Controllers\Api\V1\ProductController::class,'all']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'v1/'

], function ($router) {
    //Categories
    Route::get('/categories',[Controllers\Api\V1\CategoryController::class,'all']);

    //Products
    Route::get('/products/fs',[Controllers\Api\V1\ProductController::class,'getProductsFromApi']);
    Route::get('/products',[Controllers\Api\V1\ProductController::class,'all']);
    Route::get('/products/{idproducto}',[Controllers\Api\V1\ProductController::class, 'get']);
    Route::get('/products/category/{codfamilia}',[Controllers\Api\V1\ProductController::class,'getProductsByFamily']);

    //Orders
    Route::post('/orders',[Controllers\Api\V1\OrderController::class,'store']);
    Route::get('/orders/pending', [Controllers\Api\V1\OrderController::class,'pending']);
    Route::get('/orders/{orderNumber}/history', [Controllers\Api\V1\OrderController::class,'orderDetailHistory']);
    Route::get('orders/client/{order}/history', [Controllers\Api\V1\OrderController::class, 'history']);
    
    //Orders Tracking
    Route::put('/orders/{orderNumber}/tracking', [Controllers\Api\V1\OrderController::class,'updateStatus']);
    Route::put('/orders/{orderNumber}/complete', [Controllers\Api\V1\OrderController::class, 'complete']);
    
    //Clients
    Route::post('/clients',[Controllers\Api\V1\ClientController::class,'store']);
    Route::get('/clients/{codcliente}',[Controllers\Api\V1\ClientController::class,'get']);
    Route::get('/clients',[Controllers\Api\V1\ClientController::class,'all']);

    //Client address
    Route::post('/address', [Controllers\Api\V1\AddressController::class, 'store']);
    Route::delete('/address/{id}', [Controllers\Api\V1\AddressController::class, 'destroy']);
    Route::put('/address/{id}', [Controllers\Api\V1\AddressController::class, 'update']);
    Route::post('/address/create', [Controllers\Api\V1\AddressController::class, 'create']);
    Route::get('/address/client/{codcliente}',[Controllers\Api\V1\AddressController::class,'get']);

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});














