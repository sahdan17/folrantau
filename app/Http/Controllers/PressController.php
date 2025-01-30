<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use App\Models\Spot;
use App\Models\Line;
use App\Models\DropPress;
use App\Models\Battery;
use App\Python;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\PressureDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Predictions;

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
        
        $selectedSpot = [1,2,3,4,5,6,7,8,9];

        $selectedDate = $request->date;
        $spot = $request->spot;

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
            // 'avg' => $avgPompa,
            'psiValues' => $psiValues,
            'timestamps' => $timestamps,
        ]);
    }
    
    public function combine2(Request $request)
    // public function combine2()
    {
        $user = Auth::user();
        $selectedDate = $request->date;
        $spots = Spot::get();
        
        $label1 = $spots[0]->namaSpot;
        $label2 = $spots[count($spots) - 1]->namaSpot;
        $label = "$label1 - $label2";

        return view('admin.combine2', [
            'user' => $user,
            'selectedDate' => $selectedDate,
            'selectedName' => $label
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
    
    public function predictSegmen2(Request $request){
        $km = [0, 0, 8.3, 15.9, 24.9, 30.8, 38.9, 44.5, 56.5];
        $long = [0, 8.3, 7.6, 9, 5.9, 8.1, 5.6, 12, 6.5];
        $method = $request->method;
        
        $y = [];
        $maps = [];
        
        if ($request->type == 'dynamic'){
            $c = [
                0,
                0.0024247536,
                0.592252803261978,
                0.658998339195615,
                0.817709567055072,
                0.796211034795079,
                0.876502590673044,
                0.790145,
                0.89736
            ];
            
            $sc = [
                0,
                -0.16,
                -0.52,
                -1.57,
                -0.11,
                0.11,
                -0.37,
                0.51,
                0.90,
                -267.44
            ];
            
            $first = 63.30;
        } elseif ($request->type == 'static'){
            $c = [
                0,
                0.0024,
                0.523418542,
                0.644473652,
                0.809822979,
                0.797042514,
                0.874595795,
                0.791662201,
                0.898194918,
            ];
            
            $sc = [
                0,
                -33.72,
                -2.04,
                -3.27,
                -1.07,
                -3.88,
                0.46,
                -0.43,
                -0.34,
                30.94
            ];
            
            $first = 63.3;
        }
        
        foreach($request->drop as $key => $d){
            foreach($request->normal as $k => $n){
                $delta[$key][$k] = $n - $d[$k];
            }
        }
        
        if ($method == 'segmen' || $method == 'both') {
            foreach($request->normal as $key => $n){
                $segmen[] = $key . "-" . ($key + 1);
            }
            
            array_pop($segmen);
            
            foreach($delta as $keyDelta => $dlt){
                // $y['segmen'] = $dlt;
                for ($a = 1; $a < count($dlt); $a++){
                    if ($dlt[$a] != 0){
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = ((($dlt[$a] / ($dlt[$a] + $dlt[$a + 1])) * $long[$a]) + $km[$a]) * $c[$a];
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = round($y['segmen'][$keyDelta][$segmen[$a - 1]], 2);
                        $z = $y['segmen'][$keyDelta][$segmen[$a - 1]];
                        
                        if ($z >= 0 && $z <= 64){
                             $maps['segmen'][$keyDelta][$segmen[$a - 1]] = Predictions::processMap(1, $z*1000);
                        } else {
                            $maps['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                        }
                    } else {
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                        $maps['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                    }
                }
            }
        }
        
        if ($method == 'single' || $method == 'both') {
            foreach($delta as $keyDelta => $dlt){
                // $y['single'][$keyDelta] = $dlt;
                $y['single'][$keyDelta] = $first;
                for ($a = 1; $a <= count($dlt); $a++){
                    $y['single'][$keyDelta] = $y['single'][$keyDelta] + ($dlt[$a] * $sc[$a]);
                }
                if ($y['single'][$keyDelta] > 0 && $y['single'][$keyDelta] <= 64) {
                    $y['single'][$keyDelta] = round($y['single'][$keyDelta], 2);
                    $z = $y['single'][$keyDelta];
                    $maps['single'][$keyDelta] = Predictions::processMap(1, $z*1000);
                } else {
                    $y['single'][$keyDelta] = "-";
                    $maps['single'][$keyDelta] = "-";
                }
            }
        }
        
        return response()->json([
            'y' => $y,
            'maps' => $maps
        ]);
    }
    
    public function predictSegmenTest(Request $request){
        $km = [0, 0, 8.3, 15.9, 24.9, 30.8, 38.9, 44.5, 56.5];
        $long = [0, 8.3, 7.6, 9, 5.9, 8.1, 5.6, 12, 6.5];
        $method = $request->method;
        
        $y = [];
        $maps = [];
        
        if ($request->type == 'dynamic'){
            $c = [
                0,
                0.0024247536,
                0.592252803261978,
                0.658998339195615,
                0.817709567055072,
                0.796211034795079,
                0.876502590673044,
                0.790145,
                0.89736
            ];
            
            $sc = [
                0,
                -0.16,
                -0.52,
                -1.57,
                -0.11,
                0.11,
                -0.37,
                0.51,
                0.90,
                -267.44
            ];
            
            $first = 63.30;
        } elseif ($request->type == 'static'){
            $c = [
                0,
                0.0024,
                0.523418542,
                0.644473652,
                0.809822979,
                0.797042514,
                0.874595795,
                0.791662201,
                0.898194918,
            ];
            
            $sc = [
                0,
                -33.72,
                -2.04,
                -3.27,
                -1.07,
                -3.88,
                0.46,
                -0.43,
                -0.34,
                30.94
            ];
            
            $first = 63.3;
        }
        
        foreach($request->drop as $key => $d){
            foreach($request->normal as $k => $n){
                $delta[$key][$k] = $n - $d[$k];
            }
        }
        
        if ($method == 'segmen' || $method == 'both') {
            foreach($request->normal as $key => $n){
                $segmen[] = $key . "-" . ($key + 1);
            }
            
            array_pop($segmen);
            
            foreach($delta as $keyDelta => $dlt){
                // $y['segmen'] = $dlt;
                for ($a = 1; $a < count($dlt); $a++){
                    if ($dlt[$a] != 0){
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = ((($dlt[$a] / ($dlt[$a] + $dlt[$a + 1])) * $long[$a]) + $km[$a]) * $c[$a];
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = round($y['segmen'][$keyDelta][$segmen[$a - 1]], 2);
                        $z = $y['segmen'][$keyDelta][$segmen[$a - 1]];
                        
                        if ($z >= 0 && $z <= 64){
                             $maps['segmen'][$keyDelta][$segmen[$a - 1]] = Predictions::processMap(1, $z*1000);
                        } else {
                            $maps['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                        }
                    } else {
                        $y['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                        $maps['segmen'][$keyDelta][$segmen[$a - 1]] = "-";
                    }
                }
            }
        }
        
        if ($method == 'single' || $method == 'both') {
            foreach($delta as $keyDelta => $dlt){
                // $y['single'][$keyDelta] = $dlt;
                $y['single'][$keyDelta] = $first;
                for ($a = 1; $a <= count($dlt); $a++){
                    $y['single'][$keyDelta] = $y['single'][$keyDelta] + ($dlt[$a] * $sc[$a]);
                }
                if ($y['single'][$keyDelta] > 0 && $y['single'][$keyDelta] <= 64) {
                    $y['single'][$keyDelta] = round($y['single'][$keyDelta], 2);
                    $z = $y['single'][$keyDelta];
                    $maps['single'][$keyDelta] = Predictions::processMap(1, $z*1000);
                } else {
                    $y['single'][$keyDelta] = "-";
                    $maps['single'][$keyDelta] = "-";
                }
            }
        }
        
        return response()->json([
            'y' => $y,
            'maps' => $maps,
            'delta' => $delta
        ]);
    }
    
    public function predict(Request $request) {
        foreach($request->drop as $key => $d){
            foreach($request->normal as $k => $n){
                $delta[$key][$k] = $n - $d[$k];
            }
        }
        
        foreach($delta as $keyDelta => $dlt){
            for ($a = 1; $a < count($dlt); $a++){
                if ($dlt[$a] != 0){
                    $y[$keyDelta][$segmen[$a - 1]] = ((($dlt[$a] / ($dlt[$a] + $dlt[$a + 1])) * $long[$a]) + $km[$a]) * $c[$a];
                    $y[$keyDelta][$segmen[$a - 1]] = round($y[$keyDelta][$segmen[$a - 1]], 2);
                    $z = $y[$keyDelta][$segmen[$a - 1]];
                    
                    if ($z >= 0){
                         $maps[$keyDelta][$segmen[$a - 1]] = Predictions::processMap(1, $z*1000);
                    } else {
                        $maps[$keyDelta][$segmen[$a - 1]] = "-";
                    }
                } else {
                    $y[$keyDelta][$segmen[$a - 1]] = "-";
                    $maps[$keyDelta][$segmen[$a - 1]] = "-";
                }
            }
        }
        
        return response()->json([
            'y' => $y,
            'maps' => $maps
        ]);
    }
    
    public function sendToWA(Request $request){
        $data = $request->data;
        $method = $request->method;
        $msg = [];
        $message = "";
        
        if ($method == 'single' || $method == 'both') {
            $ys = $data['y']['single'];
            $mapss = $data['maps']['single'];
            
            $message = $message . "*Pressure Drop Metode Regresi*\n";
            
            foreach($ys as $key => $d){
                $lists = $key + 1;
                $mapsss = $mapss[$key];
                $message = $message . "Pressure drop ke-$lists
Kebocoran pada titik $d KM dari PPP RTU, link Google Maps: $mapsss\n\n";
            }
        }
        
        if ($method == 'segmen' || $method == 'both') {
            $y = $data['y']['segmen'];
            $maps = $data['maps']['segmen'];
            
            $message = $message . "*Pressure Drop Metode Segmen*\n";
            
            foreach($y as $key => $b){
                $list = $key + 1;
                $message = $message . "Pressure drop ke-$list";
                foreach ($b as $k => $a){
                    $map = $maps[$key][$k];
                    $message = $message . "
Pada Segmen $k, kebocoran pada titik $a KM dari PPP RTU, link Google Maps: $map";
                }
                $message = $message . "
    
";
            }
            $message = $message . "\n";
        } 
        
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
                'target' => '120363316938739260@g.us',
                // 'target' => '082289002445',
                'message' => $message),
            CURLOPT_HTTPHEADER => array(
                'Authorization: _v-ovBD!PVaxjDhc2Li2'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return response()->json($response);
        // return response()->json($message);
    }
    
    public function getLastData()
    {
        $spots = Spot::leftJoin('pressure', function ($join) {
            $join->on('spot.id', '=', 'pressure.idSpot')
                 ->whereIn('pressure.id', function ($query) {
                     $query->selectRaw('MAX(id)')
                           ->from('pressure')
                           ->groupBy('idSpot');
                 });
        })
            ->select('spot.id as spot_id', 'spot.namaSpot as spotName', 'pressure.psiValue as pressure', 'pressure.timestamp as timestamp')
            ->orderBy('spot.id', 'asc')
            ->get();

        return response()->json($spots);
    }
    
    public function getHistoryData(Request $request){
        $selectedDate = $request->date;
        $nextDay = Carbon::parse($selectedDate)->addDay();
        $spots = Spot::all();
        
        $pressures = Pressure::where('timestamp', '>=', $selectedDate)
                ->where('timestamp', '<', $nextDay)
                ->get();
                
        foreach($spots as $s){
            $battery = Battery::where('idSpot', $s->id)
                    ->orderBy('timestamp', 'desc')
                    ->first();
                    
            if ($battery != null){
                $batt[$s->id] = $battery->batt;
            } else {
                $batt[$s->id] = 0;
            }
        }

        return response()->json([
            'selectedDate' => $selectedDate,
            'pressures' => $pressures,
            'spots' => $spots,
            'batterys' => $batt
        ]);
    }
    
    public function getPressureData(Request $request){
        $selectedDate = $request->date;
        $nextDay = Carbon::parse($selectedDate)->addDay();
        $spots = Spot::all();
        $idSpots = $spots->pluck('id')->toArray();
        $tenDaysAgo = now()->subDays(10);
        
        $pressures = Pressure::whereBetween('timestamp', [$selectedDate, $nextDay])->get();

        return response()->json([
            'selectedDate' => $selectedDate,
            'pressures' => $pressures,
            'spots' => $spots
        ]);
    }
    
    public function getBatteryData(Request $request) {
        $selectedDate = $request->date;
        $spots = Spot::all();
        $idSpots = $spots->pluck('id')->toArray();
        
        // $battery = DB::table('battery as b1')
        //     ->select('b1.idSpot', 'b1.batt', 'b1.timestamp')
        //     ->whereIn('b1.idSpot', $idSpots)
        //     ->whereDate('b1.timestamp', $selectedDate)
        //     ->whereRaw('b1.timestamp = (SELECT MAX(b2.timestamp) FROM battery b2 WHERE b2.idSpot = b1.idSpot AND DATE(b2.timestamp) = ?)', [$selectedDate])
        //     ->orderBy('b1.idSpot')
        //     ->get();
        
        $battery = Battery::orderBy('idSpot')
                ->get();

        return response()->json([
            'spots' => $spots,
            'batterys' => $battery,
        ]);
    }
    
    public function getMaxData(Request $request) {
        $selectedDate = $request->date;
        $spots = Spot::all();
        $idSpots = $spots->pluck('id')->toArray();
        $tenDaysAgo = now()->subDays(10);
        
        $max = Pressure::select('idSpot', DB::raw('MAX(psiValue) as max'))
            ->whereIn('idSpot', $idSpots)
            ->where('timestamp', '>=', $tenDaysAgo)
            ->groupBy('idSpot')
            ->get();
            
        return response()->json([
            'spots' => $spots,
            'max' => $max,
        ]);
    }
    
    public function getHistoryData2(Request $request){
        $selectedDate = $request->date1;
        $selectedDate2 = $request->date2;
        $nextDay = Carbon::parse($selectedDate)->addDay();
        $nextDay2 = Carbon::parse($selectedDate2)->addDay();
        $spots = Spot::all();
        
        if ($selectedDate != $selectedDate2) {
            $dates = [$selectedDate, $selectedDate2];
        } else {
            $dates = [$selectedDate];
        }
                
        $pressures = Pressure::where(function ($query) use ($selectedDate, $nextDay) {
                $query->where('timestamp', '>=', $selectedDate)
                      ->where('timestamp', '<', $nextDay);
            })
            ->orWhere(function ($query) use ($selectedDate2, $nextDay2) {
                $query->where('timestamp', '>=', $selectedDate2)
                      ->where('timestamp', '<', $nextDay2);
            })
            ->get();
                
        // foreach($spots as $s){
        //     $battery = Battery::where('idSpot', $s->id)
        //             ->orderBy('timestamp', 'desc')
        //             ->first();
                    
        //     if ($battery != null){
        //         if ($battery->batt == 0){
        //             $batt[$s->id] = 0;
        //         } elseif ($battery->batt >= 1 && $battery->batt <= 25){
        //             $batt[$s->id] = 1;
        //         } elseif ($battery->batt >= 26 && $battery->batt <= 50){
        //             $batt[$s->id] = 2;
        //         } elseif ($battery->batt >= 51 && $battery->batt <= 75){
        //             $batt[$s->id] = 3;
        //         } elseif ($battery->batt >= 76 && $battery->batt <= 100){
        //             $batt[$s->id] = 4;
        //         }
        //     } else {
        //         $batt[$s->id] = 0;
        //     }
        // }

        return response()->json([
            'selectedDate' => $dates,
            'pressures' => $pressures,
            'spots' => $spots
            // 'batterys' => $batt
        ]);
    }
    
    public function updateUniqueDates(){
        $update = DB::statement("
            INSERT IGNORE INTO unique_dates (unique_date)
            SELECT DISTINCT DATE(timestamp)
            FROM pressure
            WHERE DATE(timestamp) NOT IN (SELECT unique_date FROM unique_dates);
        ");
        
        return $this->sendResponse($update, 'Update berhasil');
    }
}
