<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\Spot;
use Carbon\Carbon;

use Illuminate\Http\Request;

class BatteryController extends Controller
{
    public function storeBattery(Request $request){
        $timestamp = Carbon::now('Asia/Jakarta');
        
        $battery = Battery::where('idSpot', $request->idSpot)
                ->first();
                
        if (!$battery) {
            $batt = Battery::create([
                'idSpot' => $request->idSpot,
                'batt' => $request->batt,
                'isActive' => true,
                'timestamp' => $timestamp,
            ]);
        } else {
            $batt = Battery::where('idSpot', $request->idSpot)->update([
                'batt' => $request->batt,
                'isActive' => true,
                'timestamp' => $timestamp,
            ]);
        }
        
        return $this->sendResponse($batt, 'Kirim data successfully!');
    }
    
    public function updateActive() {
        $spots = Spot::all();
        $timestamp = Carbon::now('Asia/Jakarta');
        $oneHourAgo = $timestamp->clone()->subHour();
        
        foreach($spots as $s) {
            $battery = Battery::where('idSpot', $s->id)
                ->first();
                    
            if ($battery) {
                $batt = $battery->batt;
                $lastTimestamp = Carbon::parse($battery->timestamp, 'Asia/Jakarta');
    
                if ($lastTimestamp->between($oneHourAgo, $timestamp)) {
                    Battery::where('idSpot', $s->id)->update([
                        'isActive' => true,
                    ]);
                } else {
                    Battery::where('idSpot', $s->id)->update([
                        'isActive' => false,
                    ]);
                }
            }
        }
    }
    
    public function getBattery(Request $request){
        $spots = Spot::all();
        
        foreach($spots as $s){
            $battery[$s->id] = Battery::where('idSpot', $s->id)
                    ->orderBy('timestamp', 'desc')
                    ->first();
                    
            if ($battery[$s->id] != null){
                $batt[$s->id] = $battery[$s->id]->batt;
            } else {
                $batt[$s->id] = 0;
            }
        }
        
        return $this->sendResponse($batt, 'Kirim data successfully!');
    }
}
