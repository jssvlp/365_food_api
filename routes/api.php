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
Route::get('test', function(){
    dd('asdasd');
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me'])->name('me');

    Route::post('signup',[Controllers\Api\V1\AuthController::class, 'signup']);

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories',[Controllers\CategoryController::class,'all']);

//Products
Route::get('/products/fs',[Controllers\ProductController::class,'getProductsFromApi']);
Route::get('/products',[Controllers\ProductController::class,'all']);
Route::get('/products/{idproducto}',[Controllers\ProductController::class, 'get']);
Route::get('/products/category/{codfamilia}',[Controllers\ProductController::class,'getProductsByFamily']);

//Orders
Route::post('/orders',[Controllers\OrderController::class,'store']);
//Orders Tracking
Route::put('/orders/{orderNumber}/tracking', [Controllers\TrackingController::class,'changeStatus']);

//Clients
Route::post('/clients',[Controllers\ClientController::class,'store']);
Route::get('/clients/{codcliente}',[Controllers\ClientController::class,'get']);
Route::get('/clients',[Controllers\ClientController::class,'all']);

//Client address
Route::post('/address', [Controllers\AddressController::class, 'store']);
Route::post('/address/create', [Controllers\AddressController::class, 'create']);
Route::get('/address/client/{codcliente}',[Controllers\AddressController::class,'get']);

//Auth
Route::post('/auth/login', [Controllers\AuthController::class, 'login']);
Route::post('/auth/signOut', [Controllers\AuthController::class, 'signOut']);






