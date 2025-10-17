<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'sellers'], function () {
    Route::get('/', [SellerController::class, 'index']);
    Route::post('/', [SellerController::class, 'store']);
    Route::get('/{id}', [SellerController::class, 'show']);
});

Route::get('sales', SaleController::class);
