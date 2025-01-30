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

class PressureController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $data = $request->all();

    //     $validator = Validator::make($data,[
    //         'psiValue' => 'required|numeric',
    //         'idSpot' => 'required',
    //     ]);

    //     $timestamp = Carbon::now('Asia/Jakarta');
    //     $data['timestamp'] = $timestamp;

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error!', $validator->errors());
    //     }

    //     $data = Pressure::create([
    //         'psiValue' => $request['psiValue'],
    //         'idSpot' => $request['idSpot'],
    //         'timestamp' => $timestamp,
    //     ]);

    //     $idSpot = $request->idSpot;
    //     $psiVal = $request->psiValue;

    //     $spots = Spot::where('id', $idSpot)
    //                         ->first();

    //     $minVal = $spots->minValue;

    //     if ($psiVal < $minVal){
    //         $lineList = Line::where('spot2', $idSpot)
    //             ->orWhere('spot1',$idSpot)
    //             ->get();

    //         foreach ($lineList as $key => $list){
    //             $lines[$key] = $list->id;
    //             $spot1[$key] = $list->spot1;
    //             $spot2[$key] = $list->spot2;
    //         }

    //         $off = false;
    
    //         if (in_array(1,$lines)){
    //             $idLine = 1;
    //             $spot12 = array_merge($spot1,$spot2);
    //             foreach ($spot12 as $key => $spot){
    //                 if ($spot!=$idSpot){
    //                     $idSpot2 = $spot;
    //                 }
    //             }
    //         }

    //         if (in_array(2,$lines)){
    //             $idLine = 2;
    //             if (count($lines)>1){
    //                 $index = array_search(2,$lines);
    //                 $spot12[] = $spot1[$index];
    //                 $spot12[] = $spot2[$index];
    //             }
    //             if (count($lines)==1){
    //                 $spot12 = array_merge($spot1,$spot2);
    //             }
    //             foreach ($spot12 as $key => $spot){
    //                 if ($spot!=$idSpot){
    //                     $idSpot2 = $spot;
    //                 }
    //             }
    //         }

    //         if (in_array(4,$lines)){
    //             $idLine=4;
    //             if (count($lines)==1){
    //                 $idSpot2=5;
    //             }
    //             if(count($lines)>1){
    //                 $idSpot2=6;
    //             }

    //         }

    //         $dataSpot2 = Spot::where('id', $idSpot2)
    //                     ->first();
    //         $minValSpot2 = $dataSpot2->minValue;

    //         $minuteAgo = Carbon::now('Asia/Jakarta')->subMinutes(1);
    //         // $minuteAgo = Carbon::now('Asia/Jakarta')->subHours(481);

    //         $pressures = Pressure::where('timestamp', '>=', $minuteAgo)
    //                     ->where('timestamp', '<=', $timestamp)
    //                     ->where('idSpot', $idSpot2)
    //                     ->get();

    //         foreach($pressures as $key => $press){
    //             $pressList[$key] = $press->psiValue;
    //         }

    //         $psiVal2 = array_sum($pressList)/count($pressList);
    //         if (in_array(4,$lines)){
    //             if ($psiVal2 < $dataSpot2->stopValue){
    //                 $off = true;
    //                 $idLine = 3;
    //             }
    //         }

    //         if ($off){
    //             $command = "python ../app/Python/pred3/Stream_data.py {$psiVal}";
    //         }
    //         if (!$off){
    //             $command = "python ../app/Python/pred{$idLine}/Stream_data.py {$psiVal} {$psiVal2}";
    //         }
    //         $output = shell_exec($command);

    //         $data['output'] = $output;  

    //         DropPress::create([
    //             'idLine' => $idLine,
    //             'ket' => $output,
    //             'timestamp' => $timestamp,
    //         ]);

    //         $curl = curl_init();

    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => 'https://api.fonnte.com/send',
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'POST',
    //             CURLOPT_POSTFIELDS => array(
    //             'target' => '082289002445',
    //             'message' => "Terjadi kebocoran pada jalur {$idLine} pada {$timestamp}, {$output}"),
    //             CURLOPT_HTTPHEADER => array(
    //                 'Authorization: 2BTbYV8eHsSGmSVb!5zz'
    //             ),
    //         ));

    //         $response = curl_exec($curl);
    //         curl_close($curl);
    //     }

    //     return $this->sendResponse($data, 'Kirim data successfully!');
    // }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);
        
        $data['psiValue'] = round($data['psiValue'], 2);

        $timestamp = Carbon::now('Asia/Jakarta');
        $data['timestamp'] = $timestamp;

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Pressure::create([
            'psiValue' => $request['psiValue'],
            'idSpot' => $request['idSpot'],
            'timestamp' => $timestamp,
        ]);

        // $idSpot = $request->idSpot;
        // $psiVal = $request->psiValue;

        // $spots = Spot::where('id', $idSpot)
        //                     ->first();

        // $minVal = $spots->minValue;
        // $stopVal = $spots->stopValue;

        // if ($psiVal < $minVal and $psiVal > $stopVal){
            // $lineList = Line::where('spot2', $idSpot)
            //     ->orWhere('spot1',$idSpot)
            //     ->get();

            // foreach ($lineList as $key => $list){
            //     $lines[$key] = $list->id;
            //     $spot1[$key] = $list->spot1;
            //     $spot2[$key] = $list->spot2;
            // }

            // $off = false;
    
            // if (in_array(1,$lines)){
            //     $idLine = 1;
            //     $spot12 = array_merge($spot1,$spot2);
            //     foreach ($spot12 as $key => $spot){
            //         if ($spot!=$idSpot){
            //             $idSpot2 = $spot;
            //         }
            //     }
            // }

            // if (in_array(2,$lines)){
            //     $idLine = 2;
            //     if (count($lines)>1){
            //         $index = array_search(2,$lines);
            //         $spot12[] = $spot1[$index];
            //         $spot12[] = $spot2[$index];
            //     }
            //     if (count($lines)==1){
            //         $spot12 = array_merge($spot1,$spot2);
            //     }
            //     foreach ($spot12 as $key => $spot){
            //         if ($spot!=$idSpot){
            //             $idSpot2 = $spot;
            //         }
            //     }
            // }

            // if (in_array(4,$lines)){
            //     $idLine=4;
            //     if (count($lines)==1){
            //         $idSpot2=5;
            //     }
            //     if(count($lines)>1){
            //         $idSpot2=6;
            //     }

            // }

            // $dataSpot2 = Spot::where('id', $idSpot2)
            //             ->first();
            // $minValSpot2 = $dataSpot2->minValue;

            // $minuteAgo = Carbon::now('Asia/Jakarta')->subMinutes(1);
            // $minuteAgo = Carbon::now('Asia/Jakarta')->subHours(1);

            // $pressures = Pressure::where('timestamp', '>=', $minuteAgo)
            //             ->where('timestamp', '<=', $timestamp)
            //             ->where('idSpot', $idSpot2)
            //             ->get();

            // foreach($pressures as $key => $press){
            //     $pressList[$key] = $press->psiValue;
            // }

            // $psiVal2 = array_sum($pressList)/count($pressList);
            // if (in_array(4,$lines)){
            //     if ($psiVal2 < $dataSpot2->stopValue){
            //         $off = true;
            //         $idLine = 3;
            //     }
            // }

            // $psiVal2 = 68;

            // if ($off){
            //     $command = "python ../app/Python/pred3/Stream_data.py {$psiVal}";
            // }
            // if (!$off){
            //     $command = "python ../app/Python/pred{$idLine}/Stream_dataLM.py {$psiVal} {$psiVal2}";
            // }
            // $output = shell_exec($command);

            // $data['output'] = $output;  

            // dd($psiVal,$psiVal2);
            // dd($data);

            // DropPress::create([
            //     'idLine' => $idLine,
            //     'ket' => $output,
            //     'timestamp' => $timestamp,
            // ]);

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://api.fonnte.com/send',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => array(
            //     'target' => '082289002445',
            //     'message' => "Terjadi kebocoran pada jalur {$idLine} pada {$timestamp}, {$output}"),
            //     CURLOPT_HTTPHEADER => array(
            //         'Authorization: 2BTbYV8eHsSGmSVb!5zz'
            //     ),
            // ));

            // $response = curl_exec($curl);
            // curl_close($curl);
        // }

        return $this->sendResponse($data, 'Kirim data successfully!');
    }

    public function listDownload()
    {
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

        return view('download', compact('press', 'spots'));
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

        foreach ($spots as $spot) {
            $caseStatements[] = "CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END AS `" . addslashes($spot->namaSpot) . "`";
        }

        $caseSql = implode(", ", $caseStatements);

        $query = "
            SELECT p.timestamp, 
                $caseSql
            FROM pressure p
            JOIN spot s ON p.idSpot = s.id
            WHERE p.timestamp > '{$tgl}' AND p.timestamp < '{$tgl1}'
            ORDER BY p.idSpot, p.timestamp;
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
