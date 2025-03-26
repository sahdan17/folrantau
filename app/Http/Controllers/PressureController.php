<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use App\Models\Spot;
use App\Models\Line;
use App\Models\DropPress;
use App\Models\HistOnOff;
use App\Models\UniqueDates;
use App\Python;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\PressureDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Predictions;
use App\Http\Controllers\PredController;
use Illuminate\Support\Facades\Http;

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

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Pressure::create([
            'psiValue' => $psiValue,
            'idSpot' => $request['idSpot'],
            'timestamp' => $timestamp,
        ]);
        
        if ($request->idSpot == 2 && $psiValue >= 300 || $psiValue <= 50){
            $this->pred->notifPompa($psiValue, $timestamp);
        } 
        
        if ($request->idSpot == 1 && $psiValue >= 400 && $psiValue <= 479){
            $this->pred->prediction();
        } elseif ($request->idSpot == 2 && $psiValue >= 350 && $psiValue <= 424.32){
            $this->pred->prediction();
        } elseif ($request->idSpot == 3 && $psiValue >= 300 && $psiValue <= 378){
            $this->pred->prediction();
        } elseif ($request->idSpot == 4 && $psiValue >= 200 && $psiValue <= 297){
            $this->pred->prediction();
        } elseif ($request->idSpot == 5 && $psiValue >= 100 && $psiValue <= 173){
            $this->pred->prediction();
        } elseif ($request->idSpot == 6 && $psiValue >= 100 && $psiValue <= 164){
            $this->pred->prediction();
        } elseif ($request->idSpot == 7 && $psiValue >= 55 && $psiValue <= 119){
            $this->pred->prediction();
        } elseif ($request->idSpot == 8 && $psiValue >= 55 && $psiValue <= 69){
            $this->pred->prediction();
        }

        return $this->sendResponse($data, 'Kirim data successfully!');
    }

    public function storeSend(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);

        $psiValue = round($request['psiValue'], 2);
        $idSpot = $request['idSpot'];

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Http::post('https://rtuold.findingoillosses.com/api/storeReceive', [
            'psiValue' => $psiValue,
            'idSpot' => $idSpot
        ]); 

        return $this->sendResponse($data, 'Kirim data successfully!');
    }

    public function storeReceive(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);

        $psiValue = round($request['psiValue'], 2);

        $timestamp = Carbon::now('Asia/Jakarta');
        $data['timestamp'] = $timestamp;

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Pressure::create([
            'psiValue' => $psiValue,
            'idSpot' => $request['idSpot'],
            'timestamp' => $timestamp,
        ]);
        
        if ($request->idSpot == 2 && $psiValue >= 300 || $psiValue <= 50){
            $this->pred->notifPompa($psiValue, $timestamp);
        } 
        
        if ($request->idSpot == 1 && $psiValue >= 400 && $psiValue <= 479){
            $this->pred->prediction();
        } elseif ($request->idSpot == 2 && $psiValue >= 350 && $psiValue <= 424.32){
            $this->pred->prediction();
        } elseif ($request->idSpot == 3 && $psiValue >= 300 && $psiValue <= 378){
            $this->pred->prediction();
        } elseif ($request->idSpot == 4 && $psiValue >= 200 && $psiValue <= 297){
            $this->pred->prediction();
        } elseif ($request->idSpot == 5 && $psiValue >= 100 && $psiValue <= 173){
            $this->pred->prediction();
        } elseif ($request->idSpot == 6 && $psiValue >= 100 && $psiValue <= 164){
            $this->pred->prediction();
        } elseif ($request->idSpot == 7 && $psiValue >= 55 && $psiValue <= 119){
            $this->pred->prediction();
        } elseif ($request->idSpot == 8 && $psiValue >= 55 && $psiValue <= 69){
            $this->pred->prediction();
        }

        return $this->sendResponse($data, 'Kirim data successfully!');
    }
    
    public function storeTest(Request $request)
    {
        $controller = new PredController();
        $data = $request->all();

        $validator = Validator::make($data,[
            'psiValue' => 'required|numeric',
            'idSpot' => 'required',
        ]);

        $psiValue = round($request['psiValue'], 2);

        $timestamp = Carbon::now('Asia/Jakarta');
        $data['timestamp'] = $timestamp;
        $time = $timestamp->format('H:i');

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        // $data = Pressure::create([
        //     'psiValue' => $psiValue,
        //     'idSpot' => $request['idSpot'],
        //     'timestamp' => $timestamp,
        // ]);
        
        if ($request->idSpot == 1 && $psiValue >= 300 || $psiValue <= 25){
            $controller->notifPompa($psiValue, $timestamp);
        } 
        
        if ($request->idSpot == 1 && $psiValue >= 400 && $psiValue <= 479){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 2 && $psiValue >= 350 && $psiValue <= 424.32){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 3 && $psiValue >= 300 && $psiValue <= 378){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 4 && $psiValue >= 200 && $psiValue <= 297){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 5 && $psiValue >= 100 && $psiValue <= 173){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 6 && $psiValue >= 100 && $psiValue <= 164){
            $this->pred->prediction($timestamp);
        } elseif ($request->idSpot == 7 && $psiValue >= 70 && $psiValue <= 119){
            $this->pred->prediction($timestamp);
        } 
        // elseif ($request->idSpot == 8 && $psiValue >= 25 && $psiValue <= 50){
        //     $this->pred->prediction($timestamp);
        // }

        // return $this->sendResponse($data, 'Kirim data successfully!');
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

        foreach ($spots as $spot) {
            $caseStatements[] = "MAX(CASE WHEN p.idSpot = {$spot->id} THEN p.psiValue END) AS `" . addslashes($spot->namaSpot) . "`";
        }

        $caseSql = implode(", ", $caseStatements);

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
    
    public function updateUniqueDates(){
        $update = DB::statement("
            INSERT IGNORE INTO unique_dates (unique_date)
            SELECT DISTINCT DATE(timestamp)
            FROM pressure
            WHERE DATE(timestamp) NOT IN (SELECT unique_date FROM unique_dates);
        ");
        
        return $this->sendResponse($update, 'Update berhasil');
    }
    
    public function getTanggal()
    {
        $uniqueDates = UniqueDates::orderBy('unique_date', 'DESC')
            ->pluck('unique_date')
            ->toArray();
    
        return $this->sendResponse($uniqueDates, '');
    }
    
    public function getDownloadList(request $request){
        $dates = $request->dates;
        $spots = Spot::all();
        
        $spotQueries = [];
        foreach ($spots as $spot) {
            $id = $spot->id;
            $nama = $spot->namaSpot;
            $spotQueries[] = "SUM(CASE WHEN idSpot = $id THEN 1 ELSE 0 END) AS '$id'";
        }
    
        $spotSelect = implode(', ', $spotQueries);
    
        $query = "
            SELECT 
                DATE(timestamp) AS tanggal,
                $spotSelect,
                COUNT(*) AS total_data
            FROM pressure
            WHERE DATE(timestamp) IN ('" . implode("', '", $dates) . "')
            GROUP BY DATE(timestamp)
            ORDER BY tanggal DESC
        ";
    
        $list = DB::select($query);
        
        return $this->sendResponse($list, '');
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
            
        $unique = UniqueDates::where('unique_date', $tgl)
            ->delete();
            
        return response()->json(['success' => true, 'tanggal' => $tgl]);
    }
}
