<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, CategoryController, ItemController, TransactionController, ReportController};

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

Route::prefix('auth')->group(function(){
	Route::post('login', [AuthController::class, 'login']);
	Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:1,2'])->group(function(){
	Route::apiResource('categories', CategoryController::class);
	Route::apiResource('items', ItemController::class);
});

Route::middleware(['auth:api', 'role:2'])->group(function(){
	Route::prefix('transactions')->group(function(){
		Route::post('in', [TransactionController::class, 'in']);
		Route::post('out', [TransactionController::class, 'out']);
	});
});

Route::middleware(['auth:api', 'role:1'])->group(function(){
	Route::prefix('reports')->group(function(){
		Route::post('stock', [ReportController::class, 'stock']);
		Route::post('incoming', [ReportController::class, 'incoming']);
		Route::post('outcoming', [ReportController::class, 'outcoming']);
	});
});