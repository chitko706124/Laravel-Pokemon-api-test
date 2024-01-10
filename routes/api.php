<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\TransactionController;

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

Route::post('/register', [AuthController::class, 'register'])->middleware('guest:api');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('/users', [UserController::class, 'index'])->middleware('auth:api');

Route::apiResource('item', ItemController::class)->middleware('auth:api');

Route::post('/checkout', [ShopController::class, 'checkout'])->middleware('auth:api');

Route::middleware('auth:api')->controller(TransactionController::class)->group(function () {
    Route::get('/transactions', 'allTransactions');
    Route::get('/transaction/{id}', 'detailTransaction');
    Route::get('/auth-user-transactions', 'authTransactions');
});



