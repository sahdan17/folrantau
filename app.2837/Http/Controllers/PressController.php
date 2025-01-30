<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use App\Models\Spot;
use App\Models\Line;
use App\Models\DropPress;
use App\Python;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\PressureDataExport;
use Maatwebsite\Excel\Facades\Excel;

class PressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();
        $selectedDate = $today->toDateString();
        // $selectedDate = "2024-05-22";
        $selectedHour = 24;
        
        $spots = Spot::get();

        foreach ($spots as $key => $spot) {
            $selectedSpot[$key] = $spot->id;
        }

        $spotsSelect = Spot::where('id', $selectedSpot)->get();
        foreach ($selectedSpot as $spotId) {
            foreach ($spots as $spot) {
                if ($spot->id == $spotId) {
                    $selectedName[$spotId] = $spot->namaSpot;
                    $latestPressure[$spotId] = Pressure::whereDate('timestamp', $selectedDate)
                        ->where('idSpot', $spotId)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                }
            }
        }

        foreach($selectedSpot as $spot){
            if ($latestPressure[$spot]) {
                $endDateTime = Carbon::parse($selectedDate)->endOfDay();
                $startDateTime = $endDateTime->copy()->subHours($selectedHour);

                $pressures[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->get();

                foreach ($pressures[$spot] as $press){
                    $psiValues[$spot][] = $press->psiValue;
                    $timestamps[$spot][] = $press->timestamp;
                }
            } 
            else {
                $pressures[$spot] = [];
                $psiValues[$spot] = [];
                $timestamps[$spot] = [];
            }
        }

        return response()->json([
            'selectedDate' => $selectedDate,
            'selectedHour' => $selectedHour,
            'selectedName' => $selectedName,
            'selectedSpot' => $selectedSpot,
            'spots' => $spots,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }

    public function indexByDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'hour' => 'required|numeric',
            'spot' => 'required|array|min:1',
            'spot.*' => 'required|numeric|distinct',
        ]);

        $selectedDate = $request->date;
        $selectedHour = $request->hour;
        $selectedSpot = $request->spot;
        $spots = Spot::get();

        $spotsSelect = Spot::where('id', $selectedSpot)->get();
        foreach ($selectedSpot as $spotId) {
            foreach ($spots as $spot) {
                if ($spot->id == $spotId) {
                    $selectedName[$spotId] = $spot->namaSpot;
                    $latestPressure[$spotId] = Pressure::whereDate('timestamp', $selectedDate)
                        ->where('idSpot', $spotId)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                }
            }
        }

        foreach($selectedSpot as $spot){
            if ($latestPressure[$spot]) {
                $endDateTime = Carbon::parse($selectedDate)->endOfDay();
                $startDateTime = $endDateTime->copy()->subHours($selectedHour);

                $pressures[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->get();

                foreach ($pressures[$spot] as $press){
                    $psiValues[$spot][] = $press->psiValue;
                    $timestamps[$spot][] = $press->timestamp;
                }
            } 
            else {
                $pressures[$spot] = [];
                $psiValues[$spot] = [];
                $timestamps[$spot] = [];
            }
        }

        return response()->json([
            'selectedDate' => $selectedDate,
            'selectedHour' => $selectedHour,
            'selectedName' => $selectedName,
            'selectedSpot' => $selectedSpot,
            'spots' => $spots,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }
}
