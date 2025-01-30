<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PressureController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\DropPressController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BSNWController;
use App\Http\Controllers\BatteryController;

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

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SesiController::class, 'index'])->name('login');
    Route::post('/login', [SesiController::class, 'login']);
});

Route::get('/image1', function () {
    return response()->file(public_path('images/gambar1.png'));
});

Route::get('updateUniqueDates', [PressureController::class, 'updateUniqueDates']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [AdminController::class, 'index']);
    Route::get('/logout', [SesiController::class, 'logout']);
    Route::get('/', function () {
        return redirect('home');
    });
    Route::post('/index', [PressController::class,'index'])->name('index');
    Route::post('/pressure', [PressController::class,'indexByDate'])->name('pressure');
    Route::post('/combineChart', [PressController::class,'indexCombine'])->name('combineChart');
    Route::post('/download/{tanggal}', [PressureController::class,'getPressureData'])->name('download');
    Route::post('/downloadExcelFOL', [PressureController::class,'getPressureData']);
    Route::post('/delete/{tanggal}', [PressureController::class,'deleteData'])->name('delete');
    Route::post('/deleteDataFOL', [PressureController::class,'deleteData']);
    Route::get('/list', [PressureController::class,'listDownload'])->middleware('userAkses:admin');
    Route::post('/getTanggal', [PressureController::class,'getTanggal']);
    Route::post('getDownloadList', [PressureController::class, 'getDownloadList']);
    Route::get('/list', [PressureController::class,'listDownload'])->middleware('userAkses:admin');
    Route::get('/predLoss', [DropPressController::class,'predictLoss']);
    Route::post('/predictLoss-proc', [DropPressController::class,'predictLossProcess']);
    Route::get('/predLoc', [DropPressController::class,'predictLocation']);
    Route::post('/predictLoc-proc', [DropPressController::class,'predictLocProcess']);
    Route::get('/getField', [SpotController::class,'getSpot'])->middleware('userAkses:admin');
    Route::get('/getLine', [SpotController::class,'getLine'])->middleware('userAkses:admin');
    Route::post('/createField', [SpotController::class,'createSpot']);
    Route::post('/createLine', [SpotController::class,'createLine']);
    Route::get('/combine', [PressController::class, 'combine'])->name('admin.combine');
    Route::get('/combine2', [PressController::class, 'combine2'])->name('admin.combine2');
    Route::post('/inputBSNW', [BSNWController::class,'input']);
    Route::post('/indexBSNW', [BSNWController::class,'index'])->name('indexBSNW');
    Route::get('/getBSNW', [BSNWController::class,'get'])->name('getBSNW');
    Route::get('/dailyrekap', [BSNWController::class,'dailyrekap'])->name('dailyrekap');
    Route::post('/report', [BSNWController::class,'report'])->name('report');
    Route::get('/gauge', [PressController::class,'viewGauge'])->name('viewGauge');
    Route::get('/getLastData', [PressController::class,'getLastData']);
    Route::get('/viewTable', [SesiController::class, 'viewTable2']);
    Route::get('/viewTable2', [SesiController::class, 'viewTable']);
    Route::post('/getHistoryData', [PressController::class,'getHistoryData']);
    Route::post('/getHistoryDataTest', [PressController::class,'getHistoryDataTest']);
    Route::post('/getHistoryData2', [PressController::class,'getHistoryData2']);
    Route::post('/getPressureData', [PressController::class,'getPressureData']);
    Route::post('/getBatteryData', [PressController::class,'getBatteryData']);
    Route::post('/getMaxData', [PressController::class,'getMaxData']);
    Route::get('/chart', [SesiController::class, 'viewChart']);
    Route::post('/predictSegmen', [PressController::class,'predictSegmen']);
    Route::post('/predictSegmen2', [PressController::class,'predictSegmen2']);
    Route::post('/sendToWA', [PressController::class,'sendToWA']);
    Route::get('/getBattery', [BatteryController::class,'getBattery']);
    Route::get('/downloadFOL', [SesiController::class, 'viewDownloadFOL'])->middleware('userAkses:admin');
});