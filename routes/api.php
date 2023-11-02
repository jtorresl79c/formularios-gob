<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\TarimaController;
// use App\Http\Controllers\Api\AppController;
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


// Route::get('/remision/getall', [AppController::class, 'getall'])->name('api.remision.getall');

// Route::post('/tarima/calculos', [TarimaController::class, 'calculos'])->name('api.tarima.calculos');
// Route::get('/tarima/getcajas', [TarimaController::class, 'getcajas'])->name('api.tarima.getcajas');
// Route::apiResource('tarima', TarimaController::class);