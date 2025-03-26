<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PressureController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\PredController;
use App\Http\Controllers\DropPressController;
use App\Http\Controllers\BSNWController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\BatteryController;

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
Route::post('storeTest', [PressureController::class, 'storeTest']);
Route::get('updateUniqueDates', [PressureController::class, 'updateUniqueDates']);
Route::post('index', [PressureController::class, 'index']);
// Route::post('indexByDate', [PressureController::class, 'indexByDate']);
Route::post('predictLoss', [DropPressController::class, 'predictLoss']);
Route::post('/download', [PressureController::class,'getPressureData']);
Route::post('/inputBSNW', [BSNWController::class,'input']);
Route::post('/cekFOL', [PressController::class,'cekFOL']);
Route::post('/getLastData', [PressController::class,'getLastData']);
Route::post('/getHistoryData', [PressController::class,'getHistoryData']);
Route::post('/getHistoryDataTest', [PressController::class,'getHistoryDataTest']);
Route::post('/getHistoryData2', [PressController::class,'getHistoryData2']);
Route::post('/index', [PressController::class,'index']);
Route::post('/indexByDate', [PressController::class,'indexByDate']);
Route::post('/pressure', [PressController::class,'indexByDate'])->name('pressure');
Route::post('/indexTest', [PressController::class,'indexTest']);
Route::post('/predictSegmen', [PressController::class,'predictSegmen2']);
Route::post('/predictSegmen2', [PressController::class,'predictSegmenTest']);
Route::post('/dailyReminder', [PredController::class,'dailyReminder']);
Route::post('/prediction', [PredController::class,'prediction']);
Route::post('/tesSendData', [SesiController::class,'tesSendData']);
Route::post('/predictLoc-proc', [DropPressController::class,'predictLocProcess']);
Route::post('/storeBattery', [BatteryController::class,'storeBattery']);
Route::post('/getBattery', [BatteryController::class,'getBattery']);
Route::post('/updateActive', [BatteryController::class,'updateActive']);
Route::post('/sendToWA', [PressController::class,'sendToWA']);
Route::post('/getBatteryData', [PressController::class,'getBatteryData']);
Route::post('/getPressureData', [PressController::class,'getPressureData']);
Route::post('/getMaxData', [PressController::class,'getMaxData']);
Route::post('/getTanggal', [PressureController::class,'getTanggal']);
Route::post('/deleteDataFOL', [PressureController::class,'deleteData']);
Route::post('/getFolOff', [PredController::class,'getFolOff']);
Route::post('/folOffOn', [PredController::class,'folOffOn']);
Route::post('/sendToDBJambi', [PredController::class,'sendToDBJambi']);
Route::post('/rekapPompa', [PredController::class,'rekapPompa']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
