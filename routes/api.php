<?php

use App\Http\Controllers\Api\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductsAddControllerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\MessagesController;

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
// User
Route::get('/users/{email}', [UserController::class, 'getOne']);
Route::post('/users/auth', [UserController::class, 'auth']);
Route::post('/users/regist', [UserController::class, 'createNew']);
Route::post('/users/{id}', [UserController::class, 'setRaitingProod']);
Route::get('/activate/{link}', [UserController::class, 'setActivated']);


// Messages
Route::get('/messages/{id}', [MessagesController::class, 'getAll']);
Route::post('/messages/{id}', [MessagesController::class, 'createNewPost']);
Route::post('/messages/delete/{id}', [MessagesController::class, 'deletePost']);
Route::post('/messages/send/telegram', [MessagesController::class, 'sendTelegram']);
// Goods
Route::get('/goods/{type}', [ProductsAddControllerController::class, 'index']);
Route::post('/goods', [ProductsAddControllerController::class, 'create']);
Route::post('/goods/{id}', [ProductsAddControllerController::class, 'setRaiting']);
Route::post('/goods/delete/{id}', [ProductsAddControllerController::class, 'deleteOne']);

// Orders
Route::post('/orders', [OrdersController::class, 'store']);

// Basket


// ADMIN_PANEL
Route::post('/admin/{id}', [AdminController::class, 'getAll']);   //
