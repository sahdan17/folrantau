<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use App\Models\Spot;
use App\Models\Line;
use App\Models\DropPress;
use App\Models\HistOnOff;
use App\Python;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\PressureDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Predictions;
use App\Http\Controllers\PredController;

class PressureController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct(private PredController $pred) {}

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);

        $psiValue = round($request['psiValue'], 2);

        $timestamp = Carbon::now('Asia/Jakarta');
        $data['timestamp'] = $timestamp;
        $time = $timestamp->format('H:i');
        
  //      if ($time >= "23:55" && $time <= "23:59"){
   //         $this->pred->dailyReminder(4);
   //     } elseif ($time >= "06:00" && $time <= "06:05"){
  //          $this->pred->dailyReminder(1);
  //      } elseif ($time >= "12:00" && $time <= "12:05"){
 //           $this->pred->dailyReminder(2);
 //       } elseif ($time >= "18:00" && $time <= "18:05"){
 //           $this->pred->dailyReminder(3);
//        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Pressure::create([
            'psiValue' => $psiValue,
            'idSpot' => $request['idSpot'],
            'timestamp' => $timestamp,
        ]);
        
        $message = '';
        $cekNotif = Carbon::now('Asia/Jakarta')->subSeconds(10);
        $minuteAgo = Carbon::now('Asia/Jakarta')->subSeconds(10);
        
        // if ($request->idSpot == 1){
        //    $this->pred->pred1($request->psiValue, $timestamp);
        //} elseif ($request->idSpot == 2){
        //    $this->pred->pred2($request->psiValue, $timestamp);
        //} elseif ($request->idSpot == 6){
   //         $this->pred->pred6($request->psiValue, $timestamp);
  //      } elseif ($request->idSpot == 5){
   //         $this->pred->pred5($request->psiValue, $timestamp);
   //     } elseif ($request->idSpot == 3){
  //          $this->pred->pred3($request->psiValue, $timestamp);
   //     } elseif ($request->idSpot == 4){
   //         $this->pred->pred4($request->psiValue, $timestamp);
    //    } elseif ($request->idSpot == 7){
    //        $this->pred->pred7($request->psiValue, $timestamp);
    //    }

        return $this->sendResponse($data, 'Kirim data successfully!');
    }
    
    public function storeTest(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);

        $psiValue = round($request['psiValue'], 2);

        $timestamp = Carbon::now('Asia/Jakarta');
        $data['timestamp'] = $timestamp;
        $time = $timestamp->format('H:i');
        
        if ($time >= "23:55" && $time <= "23:59"){
            $this->pred->dailyReminder(4);
        } elseif ($time >= "12:00" && $time <= "14:05"){
            $this->pred->dailyReminder(1);
        } elseif ($time >= "12:00" && $time <= "12:05"){
            $this->pred->dailyReminder(2);
        } elseif ($time >= "18:00" && $time <= "18:05"){
            $this->pred->dailyReminder(3);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        // $data = Pressure::create([
        //     'psiValue' => $psiValue,
        //     'idSpot' => $request['idSpot'],
        //     'timestamp' => $timestamp,
        // ]);
        
        $message = '';
        $cekNotif = Carbon::now('Asia/Jakarta')->subSeconds(10);
        $minuteAgo = Carbon::now('Asia/Jakarta')->subSeconds(10);
        
        // if ($request->idSpot == 1){
        //     $this->pred->pred1Test($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 2){
        //     $this->pred->pred2($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 6){
        //     $this->pred->pred6($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 5){
        //     $this->pred->pred5($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 3){
        //     $this->pred->pred3($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 4){
        //     $this->pred->pred4($request->psiValue, $timestamp);
        // } elseif ($request->idSpot == 7){
        //     $this->pred->pred7($request->psiValue, $timestamp);
        // }

        return $this->sendResponse($data, 'Kirim data successfully!');
    }

    public function listDownload()
    {
        $user = Auth::user();        
        $spots = Spot::get();
        foreach ($spots as $spot) {
            $caseStatements[] = "SUM(CASE WHEN p.idSpot = {$spot->id} THEN 1 ELSE 0 END) AS `idSpot{$spot->id}`";
            $ids[] = $spot->id;
        }
        $caseSql = implode(", ", $caseStatements);

        $idSql = implode(", ", $ids);

        $query = "
        SELECT DATE(p.timestamp) AS tanggal, 
            {$caseSql},
            SUM(CASE WHEN p.idSpot IN ({$idSql}) THEN 1 ELSE 0 END) AS jumlah
        FROM pressure p
        JOIN spot s ON p.idSpot = s.id
        GROUP BY tanggal
        ORDER BY tanggal;
        ";

        $press = DB::select(DB::raw($query));

        return view('admin.download', compact('press', 'spots', 'user'));
    }

    public function getPressureData(Request $req)
    {
        $data = $req->all();

        $validator = Validator::make($data,[
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        $tgl = $req->tanggal;

        $tglFile = Carbon::parse($req->tanggal)->isoFormat('D MMMM Y');
        
        $spots = Spot::get();

        $tgl1 = date('Y-m-d', strtotime($tgl. ' + 1 days'));

        // foreach ($spots as $spot) {
        //     $caseStatements[] = "CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END AS `" . addslashes($spot->namaSpot) . "`";
        // }

        // $caseSql = implode(", ", $caseStatements);

        foreach ($spots as $spot) {
            $caseStatements[] = "MAX(CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END) AS `" . addslashes($spot->namaSpot) . "`";
        }

        $caseSql = implode(", ", $caseStatements);

        // $query = "
        //     SELECT p.psiValue, p.timestamp, 
        //         $caseSql
        //     FROM pressure p
        //     JOIN Spot s ON p.idSpot = s.id
        //     WHERE p.timestamp > '{$tgl}' AND p.timestamp < '{$tgl1}'
        //     ORDER BY p.idSpot, p.timestamp;
        // ";

        $query = "
            SELECT DATE(p.timestamp) AS tanggal,
            TIME(p.timestamp) AS waktu,
                $caseSql
            FROM pressure p
            JOIN spot s ON p.idSpot = s.id
            WHERE p.timestamp > '{$tgl}' AND p.timestamp < '{$tgl1}'
            GROUP BY p.timestamp
            ORDER BY p.timestamp;
        ";

        $pressureData = DB::select(DB::raw($query));

        return Excel::download(new PressureDataExport($pressureData), 'pressure_data_'.$tglFile.'.xlsx');
    }

    public function deleteData(Request $req)
    {
        $data = $req->all();

        $validator = Validator::make($data,[
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        $tgl = $req->tanggal;
        $tgl1 = date('Y-m-d', strtotime($tgl. ' + 1 days'));

        DB::table('pressure')
            ->whereBetween('timestamp', [$tgl, $tgl1])
            ->delete();

        return redirect('list');
    }
}


// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Pressure;
// use App\Models\Spot;
// use App\Models\Line;
// use App\Models\DropPress;
// use App\Models\HistOnOff;
// use App\Python;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;
// use App\Exports\PressureDataExport;
// use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Facades\Auth;
// use App\Helpers\Predictions;

// class PressureController extends Controller
// {
//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */

//     public function store(Request $request)
//     {
//         $data = $request->all();

//         $validator = Validator::make($data,[
//             'psiValue' => 'required|numeric',
//             'idSpot' => 'required',
//         ]);

//         // $data['psiValue'] = round($data['psiValue'], 2);
//         $psiValue = round($request['psiValue'], 2);

//         $timestamp = Carbon::now('Asia/Jakarta');
//         $data['timestamp'] = $timestamp;

//         if ($validator->fails()) {
//             return $this->sendError('Validation Error!', $validator->errors());
//         }

//         $data = Pressure::create([
//             'psiValue' => $psiValue,
//             'idSpot' => $request['idSpot'],
//             'timestamp' => $timestamp,
//         ]);
        
//         $message = '';
//         $cekNotif = Carbon::now('Asia/Jakarta')->subMinutes(5);
//         $minuteAgo = Carbon::now('Asia/Jakarta')->subSeconds(10);
        
//         if ($request->idSpot == 1){
//             if ($request->psiValue >= 131 xor $request->psiValue <= 25){
//                 $data['output'] = "id 1 normal";
//                 if ($request->psiValue >= 131){
//                     $notif = Pressure::where('idSpot', 1)
//                         ->where('psiValue', '>', 120)
//                         ->where('timestamp', '>', $cekNotif)
//                         ->get();

//                     if (count($notif) == 5){
//                         $notifWA = "🟢 _MGS BJG - MOS TPN_
//         *START POMPA*";
//                         $this->sendNotif($notifWA, $request->idSpot, 'start', $timestamp);
//                     }
//                 } else {
//                     $notif = Pressure::where('idSpot', 1)
//                         ->where('psiValue', '<', 10)
//                         ->where('timestamp', '>', $cekNotif)
//                         ->get();

//                     if (count($notif) == 5){
//                         $notifWA = "🔴 _MGS BJG - MOS TPN_
//         *STOP POMPA*";
//                         $this->sendNotif($notifWA, $request->idSpot, 'stop', $timestamp);
//                     }
//                 }
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MGS BJG';
//                 $data['message'] = $message;
//                 $avg2 = Pressure::where('idSpot', 2)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg2 == null){
//                     $avg2 = 0;
//                 }
                
//                 $data['avg'] = $avg2;

//                 if ($avg2 < 79 and $avg2 > 52){
//                     try {
//                         $a = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $b = is_numeric($avg2) ? floatval($avg2) : null;
//                         $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$outputs} dari MGS BJG";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                        
//                         $message = "*Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";

//                         // $message = "Terjadi penurunan tekanan pada line MGS BJG-BOOSTER pada {$timestamp}, {$output} dari MGS BJG";
//                     } catch (\Exception $e) {
//                         return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                         $output = 'gagal dapat data lokasi';
//                     }
//                 }
//             }
//         } elseif ($request->idSpot == 2){
//             if ($request->psiValue >= 79 xor $request->psiValue <= 52){
//                 $data['output'] = "id 2 normal";
//             }else{
//                 // $message = 'Terjadi penurunan tekanan pada field BOOSTER';
//                 $avg1 = Pressure::where('idSpot', 1)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg1 == null){
//                     $avg1 = 0;
//                 }
                
//                 $data['avg'] = $avg1;
                
//                 if ($avg1 < 131 and $avg1 > 25){
//                     try {
//                         $b = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $a = is_numeric($avg1) ? floatval($avg1) : null;
//                         $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$outputs} dari BOOSTER";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                        
//                         $message = "*Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                        
//                         // $message = "Terjadi penurunan tekanan pada line MGS BJG-BOOSTER pada {$timestamp}, {$output} dari BOOSTER";
                        
//                     } catch (\Exception $e) {
//                         return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     }
//                 }
//             }
//         } elseif ($request->idSpot == 6){
//             if ($request->psiValue <= 217 and $request->psiValue >= 135){
//                 // $message = 'Terjadi penurunan tekanan pada field GS 01 KTT';
//                 $avg6 = Pressure::where('idSpot', 6)
//                         ->where('psiValue', '>=', 217)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg6 >= 135 and $avg6 <= 217){
//                     $avg5 = Pressure::where('idSpot', 5)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel5 = Pressure::where('idSpot', 5)
//                             ->where('psiValue', '>=', 39)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg6 == null){
//                         $avg6 = 217;
//                     }
//                     if ($avg5 == null){
//                         $avg5 = 0;
//                     }
//                     if ($avgDel5 == null){
//                         $avgDel5 = 39;
//                     }
    
//                     $delta6 = $avg6 - $request['psiValue'];
//                     $delta5 = $avgDel5 - $avg5;
                    
//                     if ($avg5 >= 30 and $avg6 <= 39){
//                         try {
//                             $a = is_numeric($delta6) ? floatval($delta6) : null;
//                             $b = is_numeric($delta5) ? floatval($delta5) : null;
//                             $prediksi_lokasi = Predictions::predictLoc4($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                         //     if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //         $output = "{$outputs} dari GS 01 KTT";
//                         //     } else {
//                         //     $output = $outputs;
//                         // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline GS 01 KTT - GS 02 SGL*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line GS 01 KTT-GS 02 SGL pada {$timestamp}, {$output} dari GS 01 KTT";
    
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             } else {                
//                 // aman
//             }
//         } elseif ($request->idSpot == 5){
//             if ($request->psiValue <= 39 and $request->psiValue >= 30){
//                 // pressure drop KTT-SGL
//                 // $message = 'Terjadi penurunan tekanan pada field GS 02 SGL';
//                 $avg5 = Pressure::where('idSpot', 5)
//                         ->where('psiValue', '>=', 39)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg5 >= 30 and $avg5 <= 39){
//                     $avg6 = Pressure::where('idSpot', 6)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel6 = Pressure::where('idSpot', 6)
//                             ->where('psiValue', '>=', 217)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg5 == null){
//                         $avg5 = 39;
//                     }
//                     if ($avg6 == null){
//                         $avg6 = 0;
//                     }
//                     if ($avgDel6 == null){
//                         $avgDel6 = 217;
//                     }
    
//                     $delta5 = $avg5 - $request['psiValue'];
//                     $delta6 = $avgDel6 - $avg6;
                    
//                     if ($avg5 >= 30 and $avg6 <= 39){
//                         try {
//                             $b = is_numeric($delta5) ? floatval($delta5) : null;
//                             $a = is_numeric($delta6) ? floatval($delta6) : null;
//                             $prediksi_lokasi = Predictions::predictLoc4($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari GS 02 SGL";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline GS 01 KTT - GS 02 SGL*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line GS 01 KTT-GS 02 SGL pada {$timestamp}, {$output} dari GS 02 SGL";
    
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             } elseif ($request->psiValue <= 109 and $request->psiValue >= 70) {
//                 // pressure drop SGL-KAS
//                 // $message = 'Terjadi penurunan tekanan pada field GS 02 SGL';
//                 $avg5 = Pressure::where('idSpot', 5)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');
//                 if ($avg5 <= 109 and $avg5 >= 70){
//                     try {
//                         $b = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $prediksi_lokasi = Predictions::predictLoc3($a);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$output} dari GS 02 SGL";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                            
//                         $message = "*Trunkline GS 02 SGL - MGS KAS*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                        
//                         // $message = "Terjadi penurunan tekanan pada line GS 02 SGL-MGS KAS pada {$timestamp}, {$output} dari GS 02 SGL";
                        
//                     } catch (\Exception $e) {
//                         return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     }
//                 }
                
//             } else {
//                 // aman
//             }
//         } elseif ($request->idSpot == 3){
//             if ($request->psiValue >= 127 xor $request->psiValue <= 30){
//                 $data['output'] = "id 3 normal";
//                 if ($request->psiValue >= 127){
//                     $notif = Pressure::where('idSpot', 3)
//                         ->where('psiValue', '>', 110)
//                         ->where('timestamp', '>', $cekNotif)
//                         ->get();

//                     if (count($notif) == 5){
//                         $notifWA = "🟢 _MGS KAS - MOS TPN_
//         *START POMPA*";
//                         $this->sendNotif($notifWA, $request->idSpot, 'start', $timestamp);
//                     }
//                 } else {
//                     $notif = Pressure::where('idSpot', 3)
//                         ->where('psiValue', '<', 20)
//                         ->where('timestamp', '>', $cekNotif)
//                         ->get();

//                     if (count($notif) == 5){
//                         $notifWA = "🔴 _MGS KAS - MOS TPN_
//         *STOP POMPA*";
//                         $this->sendNotif($notifWA, $request->idSpot, 'stop', $timestamp);
//                     }
//                 }
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MGS KAS';
//                 $avg3 = Pressure::where('idSpot', 3)
//                         ->where('psiValue', '>=', 127)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg3 >= 30 and $avg3 <= 127){
//                     $avg4 = Pressure::where('idSpot', 4)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel4 = Pressure::where('idSpot', 4)
//                             ->where('psiValue', '>=', 17)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg3 == null){
//                         $avg3 = 127;
//                     }
//                     if ($avg4 == null){
//                         $avg4 = 0;
//                     }
//                     if ($avgDel4 == null){
//                         $avgDel4 = 17;
//                     }
    
//                     $delta3 = $avg3 - $request['psiValue'];
//                     $delta4 = $avgDel4 - $avg4;
                    
//                     if ($avg4 > 10 and $avg4 < 17){
//                         try {
//                             $a = is_numeric($delta3) ? floatval($delta3) : null;
//                             $b = is_numeric($delta4) ? floatval($delta4) : null;
//                             $prediksi_lokasi = Predictions::predictLoc2($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari MGS KAS";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline MGS KAS - MOS TPN*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line MGS KAS-MOS TPN pada {$timestamp}, {$output} dari MGS KAS";
                            
//                             $data['avg3'] = $avg3;
//                             $data['avg4'] = $avg4;
//                             $data['avgDel4'] = $avgDel4;
//                             $data['delta3'] = $delta3;
//                             $data['delta4'] = $delta4;
    
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             }
//         } elseif ($request->idSpot == 4){
//             if ($request->psiValue >= 10 xor $request->psiValue <= 8){
//                 $data['output'] = "id 4 normal";
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MOS TPN';
//                 $avg4 = Pressure::where('idSpot', 4)
//                         ->where('psiValue', '>=', 127)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg4 >=8 and $avg4 <=10){
//                     $avg3 = Pressure::where('idSpot', 3)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel3 = Pressure::where('idSpot', 3)
//                             ->where('psiValue', '>=', 17)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg4 == null){
//                         $avg4 = 17;
//                     }
//                     if ($avg3 == null){
//                         $avg3 = 0;
//                     }
//                     if ($avgDel3 == null){
//                         $avgDel3 = 127;
//                     }
    
//                     $delta4 = $avg4 - $request['psiValue'];
//                     $delta3 = $avgDel3 - $avg3;
                    
//                     if ($avg3 >= 30 and $avg <= 127){
//                         try {
//                             $b = is_numeric($delta3) ? floatval($delta3) : null;
//                             $a = is_numeric($delta4) ? floatval($delta4) : null;
//                             $prediksi_lokasi = Predictions::predictLoc2($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari MOS TPN";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline MGS KAS - MOS TPN*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line MGS KAS-MOS TPN pada {$timestamp}, {$output} dari MOS TPN";
                            
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             }
//         }
        
//         if ($message!=''){
//             $curl = curl_init();

//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => 'https://api.fonnte.com/send',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => array(
//                     'target' => '120363287006483582@g.us',
//                     // 'target' => '082289002445',
//                     'message' => $message),
//                 CURLOPT_HTTPHEADER => array(
//                     'Authorization: _v-ovBD!PVaxjDhc2Li2'
//                 ),
//             ));
            
//             $response = curl_exec($curl);
//             curl_close($curl);
//         }

//         return $this->sendResponse($data, 'Kirim data successfully!');
//     }
    
//     private function sendNotif($message, $idSpot, $ket, $timestamp)
//     {
//         $send = false;
//         $history = HistOnOff::where('idSpot', $idSpot)
//                 ->orderBy('timestamp', 'desc')
//                 ->limit(1)
//                 ->first();
                
//         $spotName = Spot::where('id', $idSpot)
//                 ->first();

//         if ($history != null){
//             if ($history->ket != $ket){
//                 $createHist = HistOnOff::create([
//                     'idSpot' => $idSpot,
//                     'ket' => $ket,
//                     'timestamp' => $timestamp,
//                 ]);
//                 $send = true;
//             } else {
//                 $message = "*⚫ Trouble FOL $spotName->namaSpot*, kemungkinan yang terjadi:
// - Listrik padam
// - Data tidak masuk lebih dari 5 menit";
//                 $send = true;
//             }
//         } else {
//             $createHist = HistOnOff::create([
//                 'idSpot' => $idSpot,
//                 'ket' => $ket,
//                 'timestamp' => $timestamp,
//             ]);
//             $send = true;
//         }

//         if ($send){
//             $curl = curl_init();

//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => 'https://api.fonnte.com/send',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => array(
//                     // 'target' => '120363287006483582@g.us',
//                     'target' => '120363306996925103@g.us',
//                     // 'target' => '082289002445',
//                     'message' => $message),
//                 CURLOPT_HTTPHEADER => array(
//                     'Authorization: _v-ovBD!PVaxjDhc2Li2'
//                 ),
//             ));
        
//             $response = curl_exec($curl);
//             curl_close($curl);
        
//             return $response;
//         }
//     }
    
//     public function storeTest(Request $request)
//     {
//         $data = $request->all();

//         $validator = Validator::make($data,[
//             'psiValue' => 'required|numeric',
//             'idSpot' => 'required',
//         ]);

//         $data['psiValue'] = round($data['psiValue'], 2);

//         $timestamp = Carbon::now('Asia/Jakarta');
//         $data['timestamp'] = $timestamp;

//         if ($validator->fails()) {
//             return $this->sendError('Validation Error!', $validator->errors());
//         }

//         // $data = Pressure::create([
//         //     'psiValue' => $data['psiValue'],
//         //     'idSpot' => $request['idSpot'],
//         //     'timestamp' => $timestamp,
//         // ]);
        
//         $message = '';
//         // $minuteAgo = Carbon::now('Asia/Jakarta')->subMinutes(1);
//         $minuteAgo = Carbon::now('Asia/Jakarta')->subSeconds(10);
        
//         if ($request->idSpot == 1){
//             if ($request->psiValue >= 131 xor $request->psiValue <= 25){
//                 $data['output'] = "id 1 normal";
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MGS BJG';
//                 $data['message'] = $message;
//                 $avg2 = Pressure::where('idSpot', 2)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg2 == null){
//                     $avg2 = 0;
//                 }
                
//                 $data['avg'] = $avg2;

//                 // if ($avg2 < 79 and $avg2 > 52){
//                     // try {
//                         $a = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $b = is_numeric($avg2) ? floatval($avg2) : null;
//                         $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$outputs} dari MGS BJG";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                        
//                         $message = "*Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";

//                         // $message = "Terjadi penurunan tekanan pada line MGS BJG-BOOSTER pada {$timestamp}, {$output} dari MGS BJG";
//                     // } catch (\Exception $e) {
//                     //     return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     //     $output = 'gagal dapat data lokasi';
//                     // }
//                 // }
//             }
//         } elseif ($request->idSpot == 2){
//             if ($request->psiValue >= 79 xor $request->psiValue <= 52){
//                 $data['output'] = "id 2 normal";
//             }else{
//                 // $message = 'Terjadi penurunan tekanan pada field BOOSTER';
//                 $avg1 = Pressure::where('idSpot', 1)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg1 == null){
//                     $avg1 = 0;
//                 }
                
//                 $data['avg'] = $avg1;
                
//                 if ($avg1 < 131 and $avg1 > 25){
//                     try {
//                         $b = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $a = is_numeric($avg1) ? floatval($avg1) : null;
//                         $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$outputs} dari BOOSTER";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                        
//                         $message = "*Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                        
//                         // $message = "Terjadi penurunan tekanan pada line MGS BJG-BOOSTER pada {$timestamp}, {$output} dari BOOSTER";
                        
//                     } catch (\Exception $e) {
//                         return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     }
//                 }
//             }
//         } elseif ($request->idSpot == 6){
//             if ($request->psiValue <= 217 and $request->psiValue >= 150){
//                 // $message = 'Terjadi penurunan tekanan pada field GS 01 KTT';
//                 $avg6 = Pressure::where('idSpot', 6)
//                         ->where('psiValue', '>=', 217)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 // if ($avg6 >= 150 and $avg6 <= 217){
//                     $avg5 = Pressure::where('idSpot', 5)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel5 = Pressure::where('idSpot', 5)
//                             ->where('psiValue', '>=', 39)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg6 == null){
//                         $avg6 = 217;
//                     }
//                     if ($avg5 == null){
//                         $avg5 = 0;
//                     }
//                     if ($avgDel5 == null){
//                         $avgDel5 = 39;
//                     }
    
//                     $delta6 = $avg6 - $request['psiValue'];
//                     $delta5 = $avgDel5 - $avg5;
                    
//                     // if ($avg5 >= 30 and $avg6 <= 39){
//                         // try {
//                             $a = is_numeric($delta6) ? floatval($delta6) : null;
//                             $b = is_numeric($delta5) ? floatval($delta5) : null;
//                             $prediksi_lokasi = Predictions::predictLoc4($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                         //     if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //         $output = "{$outputs} dari GS 01 KTT";
//                         //     } else {
//                         //     $output = $outputs;
//                         // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline GS 01 KTT - GS 02 SGL*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line GS 01 KTT-GS 02 SGL pada {$timestamp}, {$output} dari GS 01 KTT";
    
//                             $data['output'] = $output;
                            
//                         // } catch (\Exception $e) {
//                         //     return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                         //     $output = 'gagal dapat data lokasi';
//                         // }
//                     // }
//                 // }
//             } else {                
//                 // aman
//             }
//         } elseif ($request->idSpot == 5){
//             if ($request->psiValue <= 39 and $request->psiValue >= 30){
//                 // pressure drop KTT-SGL
//                 // $message = 'Terjadi penurunan tekanan pada field GS 02 SGL';
//                 $avg5 = Pressure::where('idSpot', 5)
//                         ->where('psiValue', '>=', 39)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg5 >= 30 and $avg5 <= 39){
//                     $avg6 = Pressure::where('idSpot', 6)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel6 = Pressure::where('idSpot', 6)
//                             ->where('psiValue', '>=', 217)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg5 == null){
//                         $avg5 = 39;
//                     }
//                     if ($avg6 == null){
//                         $avg6 = 0;
//                     }
//                     if ($avgDel6 == null){
//                         $avgDel6 = 217;
//                     }
    
//                     $delta5 = $avg5 - $request['psiValue'];
//                     $delta6 = $avgDel6 - $avg6;
                    
//                     if ($avg5 >= 30 and $avg6 <= 39){
//                         try {
//                             $b = is_numeric($delta5) ? floatval($delta5) : null;
//                             $a = is_numeric($delta6) ? floatval($delta6) : null;
//                             $prediksi_lokasi = Predictions::predictLoc4($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari GS 02 SGL";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline GS 01 KTT - GS 02 SGL*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line GS 01 KTT-GS 02 SGL pada {$timestamp}, {$output} dari GS 02 SGL";
    
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             } elseif ($request->psiValue <= 109 and $request->psiValue >= 70) {
//                 // pressure drop SGL-KAS
//                 // $message = 'Terjadi penurunan tekanan pada field GS 02 SGL';
//                 $avg5 = Pressure::where('idSpot', 5)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');
//                 if ($avg5 <= 109 and $avg5 >= 70){
//                     try {
//                         $b = is_numeric($data['psiValue']) ? floatval($data['psiValue']) : null;
//                         $prediksi_lokasi = Predictions::predictLoc3($a);
            
//                         $output = $prediksi_lokasi;
//                         // if ($outputs != "Tidak Terdapat Kebocoran"){
//                         //     $output = "{$output} dari GS 02 SGL";
//                         // } else {
//                         //     $output = $outputs;
//                         // }
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                            
//                         $message = "*Trunkline GS 02 SGL - MGS KAS*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                        
//                         // $message = "Terjadi penurunan tekanan pada line GS 02 SGL-MGS KAS pada {$timestamp}, {$output} dari GS 02 SGL";
                        
//                     } catch (\Exception $e) {
//                         return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     }
//                 }
                
//             } else {
//                 // aman
//             }
//         } elseif ($request->idSpot == 3){
//             if ($request->psiValue >= 127 xor $request->psiValue <= 30){
//                 $data['output'] = "id 3 normal";
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MGS KAS';
//                 $avg3 = Pressure::where('idSpot', 3)
//                         ->where('psiValue', '>=', 127)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg3 >= 30 and $avg3 <= 127){
//                     $avg4 = Pressure::where('idSpot', 4)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel4 = Pressure::where('idSpot', 4)
//                             ->where('psiValue', '>=', 17)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg3 == null){
//                         $avg3 = 127;
//                     }
//                     if ($avg4 == null){
//                         $avg4 = 0;
//                     }
//                     if ($avgDel4 == null){
//                         $avgDel4 = 17;
//                     }
    
//                     $delta3 = $avg3 - $request['psiValue'];
//                     $delta4 = $avgDel4 - $avg4;
                    
//                     if ($avg4 > 10 and $avg4 < 17){
//                         try {
//                             $a = is_numeric($delta3) ? floatval($delta3) : null;
//                             $b = is_numeric($delta4) ? floatval($delta4) : null;
//                             $prediksi_lokasi = Predictions::predictLoc2($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari MGS KAS";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline MGS KAS - MOS TPN*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line MGS KAS-MOS TPN pada {$timestamp}, {$output} dari MGS KAS";
                            
//                             $data['avg3'] = $avg3;
//                             $data['avg4'] = $avg4;
//                             $data['avgDel4'] = $avgDel4;
//                             $data['delta3'] = $delta3;
//                             $data['delta4'] = $delta4;
    
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             }
//         } elseif ($request->idSpot == 4){
//             if ($request->psiValue >= 10 xor $request->psiValue <= 8){
//                 $data['output'] = "id 4 normal";
//             } else {
//                 // $message = 'Terjadi penurunan tekanan pada field MOS TPN';
//                 $avg4 = Pressure::where('idSpot', 4)
//                         ->where('psiValue', '>=', 127)
//                         ->where('timestamp', '>', $minuteAgo)
//                         ->avg('psiValue');

//                 if ($avg4 >=8 and $avg4 <=10){
//                     $avg3 = Pressure::where('idSpot', 3)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
    
//                     $avgDel3 = Pressure::where('idSpot', 3)
//                             ->where('psiValue', '>=', 17)
//                             ->where('timestamp', '>', $minuteAgo)
//                             ->avg('psiValue');
                            
//                     if ($avg4 == null){
//                         $avg4 = 17;
//                     }
//                     if ($avg3 == null){
//                         $avg3 = 0;
//                     }
//                     if ($avgDel3 == null){
//                         $avgDel3 = 127;
//                     }
    
//                     $delta4 = $avg4 - $request['psiValue'];
//                     $delta3 = $avgDel3 - $avg3;
                    
//                     if ($avg3 >= 30 and $avg <= 127){
//                         try {
//                             $b = is_numeric($delta3) ? floatval($delta3) : null;
//                             $a = is_numeric($delta4) ? floatval($delta4) : null;
//                             $prediksi_lokasi = Predictions::predictLoc2($a, $b);
                    
//                             $output = $prediksi_lokasi;
//                             // if ($outputs != "Tidak Terdapat Kebocoran"){
//                             //     $output = "{$output} dari MOS TPN";
//                             // } else {
//                             //     $output = $outputs;
//                             // }
                            
//                             $carbonDate = Carbon::parse($timestamp);

//                             $namaHari = $carbonDate->translatedFormat('l');
//                             $tanggal = $carbonDate->format('d-m-Y');
//                             $waktu = $carbonDate->format('H:i:s');
                            
//                             $message = "*Trunkline MGS KAS - MOS TPN*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
//     tanggal {$tanggal}
//     jam {$waktu}
// - {$output}";
                            
//                             // $message = "Terjadi penurunan tekanan pada line MGS KAS-MOS TPN pada {$timestamp}, {$output} dari MOS TPN";
                            
//                             $data['output'] = $output;
                            
//                         } catch (\Exception $e) {
//                             return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                             $output = 'gagal dapat data lokasi';
//                         }
//                     }
//                 }
//             }
//         }
        
//         if ($message!=''){
//             $curl = curl_init();

//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => 'https://api.fonnte.com/send',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => array(
//                     // 'target' => '120363287006483582@g.us',
//                     'target' => '082289002445',
//                     'message' => $message),
//                 CURLOPT_HTTPHEADER => array(
//                     'Authorization: _v-ovBD!PVaxjDhc2Li2'
//                 ),
//             ));
            
//             $response = curl_exec($curl);
//             curl_close($curl);
//         }

//         return $this->sendResponse($data, 'Kirim data successfully!');
//     }

//     public function listDownload()
//     {
//         $user = Auth::user();        
//         $spots = Spot::get();
//         foreach ($spots as $spot) {
//             $caseStatements[] = "SUM(CASE WHEN p.idSpot = {$spot->id} THEN 1 ELSE 0 END) AS `idSpot{$spot->id}`";
//             $ids[] = $spot->id;
//         }
//         $caseSql = implode(", ", $caseStatements);

//         $idSql = implode(", ", $ids);

//         $query = "
//         SELECT DATE(p.timestamp) AS tanggal, 
//             {$caseSql},
//             SUM(CASE WHEN p.idSpot IN ({$idSql}) THEN 1 ELSE 0 END) AS jumlah
//         FROM pressure p
//         JOIN spot s ON p.idSpot = s.id
//         GROUP BY tanggal
//         ORDER BY tanggal;
//         ";

//         $press = DB::select(DB::raw($query));

//         return view('admin.download', compact('press', 'spots', 'user'));
//     }

//     public function getPressureData(Request $req)
//     {
//         $data = $req->all();

//         $validator = Validator::make($data,[
//             'tanggal' => 'required|date_format:Y-m-d',
//         ]);

//         $tgl = $req->tanggal;

//         $tglFile = Carbon::parse($req->tanggal)->isoFormat('D MMMM Y');
        
//         $spots = Spot::get();

//         $tgl1 = date('Y-m-d', strtotime($tgl. ' + 1 days'));

//         // foreach ($spots as $spot) {
//         //     $caseStatements[] = "CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END AS `" . addslashes($spot->namaSpot) . "`";
//         // }

//         // $caseSql = implode(", ", $caseStatements);

//         foreach ($spots as $spot) {
//             $caseStatements[] = "MAX(CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END) AS `" . addslashes($spot->namaSpot) . "`";
//         }

//         $caseSql = implode(", ", $caseStatements);

//         // $query = "
//         //     SELECT p.psiValue, p.timestamp, 
//         //         $caseSql
//         //     FROM pressure p
//         //     JOIN Spot s ON p.idSpot = s.id
//         //     WHERE p.timestamp > '{$tgl}' AND p.timestamp < '{$tgl1}'
//         //     ORDER BY p.idSpot, p.timestamp;
//         // ";

//         $query = "
//             SELECT DATE(p.timestamp) AS tanggal,
//             TIME(p.timestamp) AS waktu,
//                 $caseSql
//             FROM pressure p
//             JOIN spot s ON p.idSpot = s.id
//             WHERE p.timestamp > '{$tgl}' AND p.timestamp < '{$tgl1}'
//             GROUP BY p.timestamp
//             ORDER BY p.timestamp;
//         ";

//         $pressureData = DB::select(DB::raw($query));

//         return Excel::download(new PressureDataExport($pressureData), 'pressure_data_'.$tglFile.'.xlsx');
//     }

//     public function deleteData(Request $req)
//     {
//         $data = $req->all();

//         $validator = Validator::make($data,[
//             'tanggal' => 'required|date_format:Y-m-d',
//         ]);

//         $tgl = $req->tanggal;
//         $tgl1 = date('Y-m-d', strtotime($tgl. ' + 1 days'));

//         DB::table('pressure')
//             ->whereBetween('timestamp', [$tgl, $tgl1])
//             ->delete();

//         return redirect('list');
//     }
// }
