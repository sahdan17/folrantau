<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PressureController;
use App\Http\Controllers\DropPressController;

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

Route::post('store', [PressureController::class, 'store']);
Route::post('index', [PressureController::class, 'index']);
Route::post('indexByDate', [PressureController::class, 'indexByDate']);
Route::post('predictLoss', [DropPressController::class, 'predictLoss']);
Route::post('/download', [PressureController::class,'getPressureData']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
