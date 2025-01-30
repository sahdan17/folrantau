<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PressureController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\DropPressController;
use App\Http\Controllers\SpotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/predLoss', function () {
    return view('predLoss');
});

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/pressure', [PressureController::class,'indexByDate']);
// Route::get('/', [PressureController::class,'index']);
Route::post('/index', [PressController::class,'index'])->name('index');
Route::post('/pressure', [PressController::class,'indexByDate'])->name('pressure');
Route::post('/download/{tanggal}', [PressureController::class,'getPressureData'])->name('download');
Route::post('/delete/{tanggal}', [PressureController::class,'deleteData'])->name('delete');
Route::get('/list', [PressureController::class,'listDownload']);
Route::get('/predictLoss', [DropPressController::class,'predictLoss']);
Route::post('/predictLoss-proc', [DropPressController::class,'predictLossProcess']);
Route::get('/getField', [SpotController::class,'getSpot']);
Route::get('/getLine', [SpotController::class,'getLine']);
Route::post('/createField', [SpotController::class,'createSpot']);
Route::post('/createLine', [SpotController::class,'createLine']);
