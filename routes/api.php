<?php

use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPriceController;
use App\Http\Controllers\PairController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TokenController;
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
Route::post('/sanctum/token', [LoginController::class, 'generateApiToken']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('exchange')->group(function () {
        Route::post('', [ExchangeController::class, 'create']);
        Route::put('/{id}', [ExchangeController::class, 'update']);
        Route::delete('/{id}', [ExchangeController::class, 'delete']);
        Route::get('', [ExchangeController::class, 'getAll']);
        Route::get('/{id}/portfolios', [PortfolioController::class, 'getByExchange']);
    });

    Route::prefix('token')->group(function () {
        Route::post('', [TokenController::class, 'create']);
        Route::put('/{id}', [TokenController::class, 'update']);
        Route::delete('/{id}', [TokenController::class, 'delete']);
        Route::get('/{id}', [TokenController::class, 'getById']);
        Route::get('', [TokenController::class, 'getByName']);

        Route::prefix('/{baseId}/pair/{quoteId}')->group(function () {
            Route::post('', [PairController::class, 'create']);
            Route::delete('', [PairController::class, 'delete']);
            Route::get('', [PairController::class, 'getByBaseIdAndQuoteId']);
        });
        Route::prefix('/{id}/pairs')->group(function () {
            Route::get('', [PairController::class, 'getByBaseId']);
            Route::get('/as-quote', [PairController::class, 'getByQuoteId']);
        });
    });

    Route::prefix('portfolio')->group(function () {
        Route::post('', [PortfolioController::class, 'create']);
        Route::put('', [PortfolioController::class, 'update']);
        Route::delete('/{id}', [PortfolioController::class, 'delete']);
        Route::get('/{id}', [PortfolioController::class, 'getById']);
        Route::get('', [PortfolioController::class, 'getByUser']);
        Route::get('/{id}/positions', [PositionController::class, 'getByPortfolio']);
    });

    Route::prefix('position')->group(function () {
        Route::post('', [PositionController::class, 'create']);
        Route::put('', [PositionController::class, 'update']);
        Route::get('/{id}', [PositionController::class, 'getById']);
        Route::get('', [PositionController::class, 'getByUser']);
        Route::get('/{id}/orders', [OrderController::class, 'getByPosition']);
        Route::prefix('/{positionId}/order')->group(function () {
            Route::post('', [OrderController::class, 'create']);
            Route::put('/{orderId}', [OrderController::class, 'update']);
            Route::delete('/{orderId}', [OrderController::class, 'delete']);
            Route::get('/{orderId}', [OrderController::class, 'getById']);
            Route::get('/{orderId}/prices', [OrderPriceController::class, 'getByOrder']);
            Route::prefix('/{orderId}/price')->group(function () {
                Route::post('', [OrderPriceController::class, 'create']);
                Route::put('/{priceId}', [OrderPriceController::class, 'update']);
                Route::delete('/{priceId}', [OrderPriceController::class, 'delete']);
            });
        });
    });

    Route::prefix('order')->group(function () {

    });

});

