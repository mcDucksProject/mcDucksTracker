<?php

use App\Http\Controllers\PortfolioController;
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
Route::post('/sanctum/token', [TokenController::class, 'generateApiToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/portfolio', [PortfolioController::class, 'create']);
    Route::get('/portfolio/user', [PortfolioController::class, 'getUserPortfolios']);
    Route::get('/portfolio/{id}', [PortfolioController::class, 'getPortfolio']);
    Route::put('/portfolio', [PortfolioController::class, 'update']);

});

