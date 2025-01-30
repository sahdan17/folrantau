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
use Illuminate\Support\Facades\Auth;

class PressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function combine(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'spot' => 'required',
        ]);

        $selectedDate = $request->date;
        $spot = $request->spot;

        // dd($selectedDate, $spot);

        if ($spot == 1 || $spot == 2){
            $selectedSpot = [1,2];
        } elseif ($spot == 3 || $spot == 4){
            $selectedSpot = [3,4];
        } elseif ($spot == 5){
            $selectedSpot = [5];
        } elseif ($spot == 6){
            $selectedSpot = [6];
        } elseif ($spot == 7){
            $selectedSpot = [7];
        }

        $spots = Spot::get();

        $spotsSelect = Spot::where('id', $selectedSpot)->get();
        foreach ($selectedSpot as $spotId) {
            foreach ($spots as $spot) {
                if ($spot->id == $spotId) {
                    $selectedName[$spotId] = $spot->namaSpot;
                    $pressures[$spotId] = Pressure::whereDate('timestamp', $selectedDate)
                        ->where('idSpot', $spotId)
                        ->orderBy('timestamp', 'asc')
                        ->get();
                        
                    if ($spotId == 1){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 128)
                                        ->where('psiValue', '<=', 150)
                                        ->avg('psiValue');
                    } elseif ($spotId == 2){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 70)
                                        ->where('psiValue', '<=', 100)
                                        ->avg('psiValue');
                    } elseif ($spotId == 3){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 120)
                                        ->where('psiValue', '<=', 150)
                                        ->avg('psiValue');
                    } elseif ($spotId == 4){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 16)
                                        ->where('psiValue', '<=', 38)
                                        ->avg('psiValue');
                    } elseif ($spotId == 5){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 92)
                                        ->where('psiValue', '<=', 185)
                                        ->avg('psiValue');
                    } elseif ($spotId == 6){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 225)
                                        ->where('psiValue', '<=', 250)
                                        ->avg('psiValue');
                    } elseif ($spotId == 7){
                        $avgPompa[$spotId] = Pressure::where('idSpot', $spotId)
                                        ->whereDate('timestamp', $selectedDate)
                                        ->where('psiValue', '>=', 38)
                                        ->where('psiValue', '<=', 50)
                                        ->avg('psiValue');
                    }
                }
            }
        }

        foreach($selectedSpot as $spot){
            if ($pressures[$spot]) {
                foreach ($pressures[$spot] as $press){
                    $psiValues[$spot][] = $press->psiValue;
                    $timestamps[$spot][] = $press->timestamp;
                }
            }
            else {
                $pressures[$spot] = [];
                $psiValues[$spot][] = [];
                $timestamps[$spot][] = [];
            }
        }

        return view('admin.combine', [
            'user' => $user,
            'selectedDate' => $selectedDate,
            'selectedName' => $selectedName,
            'selectedSpot' => $selectedSpot,
            'avg' => $avgPompa,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }
    
    public function index()
    {
        $today = Carbon::today();
        $selectedDate = $today->toDateString();
        // $selectedDate = "2024-05-01";
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
                                    
                if ($spot == 1){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 128)
                                    ->where('psiValue', '<=', 150)
                                    ->avg('psiValue');
                } elseif ($spot == 2){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 70)
                                    ->where('psiValue', '<=', 100)
                                    ->avg('psiValue');
                } elseif ($spot == 3){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 120)
                                    ->where('psiValue', '<=', 150)
                                    ->avg('psiValue');
                } elseif ($spot == 4){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 16)
                                    ->where('psiValue', '<=', 38)
                                    ->avg('psiValue');
                } elseif ($spot == 5){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 92)
                                    ->where('psiValue', '<=', 185)
                                    ->avg('psiValue');
                } elseif ($spot == 6){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 225)
                                    ->where('psiValue', '<=', 250)
                                    ->avg('psiValue');
                } elseif ($spot == 7){
                    $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                    ->where('timestamp', '>', $startDateTime)
                                    ->where('timestamp', '<=', $endDateTime)
                                    ->where('psiValue', '>=', 38)
                                    ->where('psiValue', '<=', 50)
                                    ->avg('psiValue');
                }

                foreach ($pressures[$spot] as $press){
                    $psiValues[$spot][] = $press->psiValue;
                    $timestamps[$spot][] = $press->timestamp;
                }
            } 
            else {
                $pressures[$spot] = [];
                $psiValues[$spot] = [];
                $timestamps[$spot] = [];
                $avgPompa[$spot] = [];
            }
        }

        return response()->json([
            'selectedDate' => $selectedDate,
            'selectedHour' => $selectedHour,
            'selectedName' => $selectedName,
            'selectedSpot' => $selectedSpot,
            'avg' => $avgPompa,
            'spots' => $spots,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }
    
    public function indexTest()
    {
        $today = Carbon::today();
        $selectedDate = $today->toDateString();
        // $selectedDate = "2024-05-01";
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

        $today = Carbon::today();
        $selectedDateToday = $today->toDateString();

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
                if ($selectedDate!=$selectedDateToday){
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
                }else{
                    if ($selectedHour == 24){
                        $endDateTime = Carbon::parse($selectedDate)->endOfDay();
                        $startDateTime = $endDateTime->copy()->subHours($selectedHour);

                        $pressures[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->get();
                                            
                        if ($spot == 1){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 128)
                                            ->where('psiValue', '<=', 150)
                                            ->avg('psiValue');
                        } elseif ($spot == 2){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 70)
                                            ->where('psiValue', '<=', 100)
                                            ->avg('psiValue');
                        } elseif ($spot == 3){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 120)
                                            ->where('psiValue', '<=', 150)
                                            ->avg('psiValue');
                        } elseif ($spot == 4){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 16)
                                            ->where('psiValue', '<=', 38)
                                            ->avg('psiValue');
                        } elseif ($spot == 5){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 92)
                                            ->where('psiValue', '<=', 185)
                                            ->avg('psiValue');
                        } elseif ($spot == 6){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 225)
                                            ->where('psiValue', '<=', 250)
                                            ->avg('psiValue');
                        } elseif ($spot == 7){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 38)
                                            ->where('psiValue', '<=', 50)
                                            ->avg('psiValue');
                        }

                        foreach ($pressures[$spot] as $press){
                            $psiValues[$spot][] = $press->psiValue;
                            $timestamps[$spot][] = $press->timestamp;
                        }
                    }else{
                        $now = Carbon::now();
                        $minute = $now->minute;

                        if ($minute < 15) {
                            $endDateTime = $now->copy()->minute(15)->second(0);
                        } elseif ($minute < 30) {
                            $endDateTime = $now->copy()->minute(30)->second(0);
                        } elseif ($minute < 45) {
                            $endDateTime = $now->copy()->minute(45)->second(0);
                        } else {
                            $endDateTime = $now->copy()->addHour()->minute(0)->second(0);
                        }
                        
                        $selectedDateStart = Carbon::parse($selectedDate)->startOfDay();
                        $startDateTime = $endDateTime->copy()->subHours($selectedHour);

                        $pressures[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('timestamp', '>', $selectedDateStart)
                                            ->get();
                                            
                        if ($spot == 1){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 128)
                                            ->where('psiValue', '<=', 150)
                                            ->avg('psiValue');
                        } elseif ($spot == 2){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 70)
                                            ->where('psiValue', '<=', 100)
                                            ->avg('psiValue');
                        } elseif ($spot == 3){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 120)
                                            ->where('psiValue', '<=', 150)
                                            ->avg('psiValue');
                        } elseif ($spot == 4){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 16)
                                            ->where('psiValue', '<=', 38)
                                            ->avg('psiValue');
                        } elseif ($spot == 5){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 92)
                                            ->where('psiValue', '<=', 185)
                                            ->avg('psiValue');
                        } elseif ($spot == 6){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 225)
                                            ->where('psiValue', '<=', 250)
                                            ->avg('psiValue');
                        } elseif ($spot == 7){
                            $avgPompa[$spot] = Pressure::where('idSpot', $spot)
                                            ->where('timestamp', '>', $startDateTime)
                                            ->where('timestamp', '<=', $endDateTime)
                                            ->where('psiValue', '>=', 38)
                                            ->where('psiValue', '<=', 50)
                                            ->avg('psiValue');
                        }

                        foreach ($pressures[$spot] as $press){
                            $psiValues[$spot][] = $press->psiValue;
                            $timestamps[$spot][] = $press->timestamp;
                        }
                    }
                }
                
            } 
            else {
                $pressures[$spot] = [];
                $psiValues[$spot] = [];
                $timestamps[$spot] = [];
                $avgPompa[$spot] = [];
            }
        }

        return response()->json([
            'selectedDate' => $selectedDate,
            'selectedHour' => $selectedHour,
            'selectedName' => $selectedName,
            'selectedSpot' => $selectedSpot,
            'avg' => $avgPompa,
            'spots' => $spots,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }
    
    public function cekFOL()
    {
        $spots = Spot::orderBy('id', 'asc')
                ->get();
        
        $minuteAgo = Carbon::now('Asia/Jakarta')->subMinutes(5);
        $press = [];

        foreach ($spots as $d){
            if (Pressure::where('timestamp', '>', $minuteAgo)
            ->where('idSpot', $d->id)
            ->count() == 0){
                $press[] = $d->namaSpot;
            }
        }

        foreach ($press as $p){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    // 'target' => '120363287006483582@g.us',
                    'target' => '120363306996925103@g.us',
                    // 'target' => '082289002445',
                    'message' => "*FOL $p nonaktif*
Kemungkinan yang terjadi:
- Listrik padam
- Trouble pada FOL"),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: _v-ovBD!PVaxjDhc2Li2'
                ),
            ));
        
            $response = curl_exec($curl);
            curl_close($curl);
        
            // return $response;
        }

        return $this->sendResponse($press, '');
    }
    
    public function viewGauge() {
        $user = Auth::user();
        
        if ($user->role == 'admin'){
            return view('admin.gauge', compact('user'));
        } elseif ($user->role == 'user'){
            return view('user.gauge', compact('user'));
        }

        
    }
    
    public function getLastData()
    {
        $spots = Spot::get();

        foreach ($spots as $d){
            $pressure = Pressure::where('idSpot', $d->id)
                        ->orderBy('timestamp', 'desc')
                        ->limit(1)
                        ->first();

            $press[] = [
                'spot_id' => $d->id,
                'pressure' => $pressure->psiValue,
                'timestamp' => $pressure->timestamp,
                'spotName' => $d->namaSpot
            ];
        }
        return response()->json($press);
    }
    
    public function getHistoryData(Request $request){
        $selectedDate = $request->date;
        $nextDay = Carbon::parse($selectedDate)->addDay();
        $spots = Spot::all();
        
        $pressures = Pressure::where('timestamp', '>=', $selectedDate)
                ->where('timestamp', '<', $nextDay)
                ->get();

        return response()->json([
            'selectedDate' => $selectedDate,
            'pressures' => $pressures,
            'spots' => $spots
        ]);
    }
}
