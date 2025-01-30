<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use App\Models\Spot;
use App\Models\Line;
use App\Models\DropPress;
use App\Models\HistOnOff;
use App\Models\ListFOLOff;
use App\Python;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\PressureDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Predictions;
use Illuminate\Support\Facades\Http;

class PredController extends Controller
{
    public function __construct()
    {
        // $this->cekNotif = Carbon::now('Asia/Jakarta')->subSeconds(10);
        $this->cekNotif = Carbon::now('Asia/Jakarta')->subMinutes(5);
        $this->cekNotifTPN = Carbon::now('Asia/Jakarta')->subMinutes(5);
        // $this->minuteAgo = Carbon::now('Asia/Jakarta')->subSeconds(20);
        $this->minuteAgoBJG = Carbon::now('Asia/Jakarta')->subMinutes(1);
        $this->minuteAgo = Carbon::now('Asia/Jakarta')->subMinutes(1);
    }
    
    public function notifPompa($psiValue, $timestamp){
        if ($psiValue >= 300){
            $notif = Pressure::where('idSpot', 1)
                    ->where('psiValue', '>=', 300)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

            if (count($notif) == 10){
                $notifWA = "🟢 _PPP RTU - Terminal PSU_
        *START POMPA*";
                $this->sendNotif($notifWA, 1, 'start', $timestamp);
            }
        } else if ($psiValue <= 50) {
            $notif = Pressure::where('idSpot', 1)
                    ->where('psiValue', '<=', 50)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

            if (count($notif) == 10){
                $notifWA = "🔴 _PPP RTU - Terminal PSU_
        *STOP POMPA*";
                $this->sendNotif($notifWA, 1, 'stop', $timestamp);
            }
        }
    }
    
    public function prediction(){
        $timestamp = Carbon::now('Asia/Jakarta');
        $date = $timestamp->format('Y-m-d');
        $spots = Spot::get();
        
        $drop = [0, 483, 443, 396, 303, 203, 189, 134, 71, 0];
        
        foreach ($spots as $s){
            $p[$s->id] = 0;
            
            $psi = Pressure::where('idSpot', $s->id)
                    ->orderBy('timestamp', 'desc')
                    ->first();
                    
            $psi1 = Pressure::where('idSpot', $s->id)
                    ->whereDate('timestamp', $date)
                    ->where('psiValue', '>=', $drop[$s->id])
                    ->orderBy('timestamp', 'desc')
                    ->first();
                    
            if ($psi1 == null){
                $psi1 = $drop[$s->id];
            } else {
                $psi1 = $psi1->psiValue;
            }
            
            $psi = $psi->psiValue;
            
            $delta = $psi1 - $psi;
                    
            // $p[$s->id] = $psi->psiValue;
            $p[$s->id] = $delta;
        }
        
        // dd($p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8], $p[9]);
        
        $output = Predictions::predictLoc($p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8], $p[9], 'dynamic');
        
        $carbonDate = Carbon::parse($timestamp);
        $namaHari = $carbonDate->translatedFormat('l');
        $tanggal = $carbonDate->format('d-m-Y');
        $waktu = $carbonDate->format('H:i:s');
        
        if ($output[1] == true){
                            $message = "⚠️ *Trunkline PPP RTU - Terminal PSU*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
        } 
//         else {
//             $message = "*Trunkline PPP RTU - Terminal PSU*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
// tanggal {$tanggal}
// jam {$waktu}
// {$output[0]}
// ";
//         }

// dd($message);

        $this->sendToDBJambi($message, '120363316938739260@g.us');
        
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
        //         'target' => '120363316938739260@g.us',
        //         // 'target' => '082289002445',
        //         'message' => $message),
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: _v-ovBD!PVaxjDhc2Li2'
        //     ),
        // ));
        
        // $response = curl_exec($curl);
        // curl_close($curl);
    }

    private function sendNotif($message, $idSpot, $ket, $timestamp)
    {
        $send = false;
        $history = HistOnOff::where('idSpot', $idSpot)
                ->orderBy('timestamp', 'desc')
                ->limit(1)
                ->first();
                
        $spotName = Spot::where('id', $idSpot)
                ->first();

        if ($history != null){
            if ($history->ket != $ket){
                $createHist = HistOnOff::create([
                    'idSpot' => $idSpot,
                    'ket' => $ket,
                    'timestamp' => $timestamp,
                ]);
                $send = true;
            } else {
//                 $message = "*⚫ Trouble FOL $spotName->namaSpot*, kemungkinan yang terjadi:
// - Listrik padam
// - Data tidak masuk lebih dari 5 menit";
//                 $send = true;
            }
        } else {
            $createHist = HistOnOff::create([
                'idSpot' => $idSpot,
                'ket' => $ket,
                'timestamp' => $timestamp,
            ]);
            $send = true;
        }

        if ($send){
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
                    'target' => '120363341933049255@g.us',
                    // 'target' => '082289002445',
                    'message' => $message),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: _v-ovBD!PVaxjDhc2Li2'
                ),
            ));
        
            $response = curl_exec($curl);
            curl_close($curl);
        
            return $response;
        }
    }

    public function sendNotifWA($message){
        $this->sendToDBJambi($message, '120363287006483582@g.us');
    }
    
    public function folOffOn() {
        $timestamp = Carbon::now('Asia/Jakarta');
        $date = $timestamp->format('Y-m-d');
        $spots = Spot::all();
        $spotMap = $spots->pluck('namaSpot', 'id');
        
        try {
            $list = ListFOLOff::whereDate('timestamp', $date)
                            // ->orderBy('idSpot')
                            ->get();
                            
            $list = $list->groupBy('idSpot');
            $i = 1;
            
            $message = "*List FOL Off*";
            
            foreach ($list as $id => $l) {
                if ($l[0]->ket == 'on') {
                    $l->shift();
                }
                
                foreach ($l as $l2) {
                    if ($l2->ket == 'on') {
                        $listOn[$id][] = $l2->timestamp;
                    } else {
                        $listOff[$id][] = $l2->timestamp;
                    }
                }
                
                if (count($list[$id]) == 0) {
                    $list->forget($id);
                }
            }
            
            // dd($list);
            
            foreach ($list as $id => $l) {
                $namaSpot = $spotMap[$id];
                
                $report[$id] = "\n";
                $report[$id] .= "
    {$i}. FOL *{$namaSpot}*\n";
                
                foreach ($listOff[$id] as $index => $lo) {
                    $dump = $listOn[$id][$index] ?? 'now';
                
                    $loCarbon = Carbon::parse($lo, 'Asia/Jakarta');
                
                    if ($dump === 'now') {
                        $dumpCarbon = Carbon::now('Asia/Jakarta');
                        $dumpText = '_now_';
                    } else {
                        $dumpCarbon = Carbon::parse($dump, 'Asia/Jakarta');
                        $dumpText   = $dumpCarbon->format('H:i'); 
                    }
                
                    $loTimeString   = $loCarbon->format('H:i'); 
                    $dumpTimeString = $dumpCarbon->format('H:i');
                
                    $diffInMinutes = $loCarbon->diffInMinutes($dumpCarbon);
                
                    $hours   = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;
                    $durasi  = "{$hours} jam {$minutes} menit";
                    
                    $report[$id] .= "Pukul {$loTimeString} sd {$dumpText}\n";
                    $report[$id] .= "Selama: {$durasi}\n";
                }
                
                $message .= "$report[$id]";
                $i++;
            }
            
            $this->sendToDBJambi($message, '120363341933049255@g.us');
        } catch (\Exception $e) {
            $this->sendToDBJambi($e, '6282289002445');
        }
    }
    
    public function getFolOff() {
        $minute5 = Carbon::now('Asia/Jakarta')->subMinutes(5);
        
        $spots = Spot::all();
        // $spots = Spot::all();
        
        $subQuery = Pressure::select('idSpot', DB::raw('MAX(timestamp) as max_timestamp'))
            ->whereRaw('DATE(timestamp) >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)')
            ->groupBy('idSpot');
        
        $results = Pressure::from('pressure as t1')
            ->joinSub($subQuery, 't2', function ($join) {
                $join->on('t1.idSpot', '=', 't2.idSpot')
                     ->on('t1.timestamp', '=', 't2.max_timestamp');
            })
            ->select('t1.*')
            ->orderByRaw('CAST(t1.idSpot AS UNSIGNED)')
            ->get();
            
        foreach ($results as $r) {
            $dataTime = Carbon::parse($r->timestamp, 'Asia/Jakarta');
            $idSpot = $r->idSpot;
            
            $history = null;
            $history = ListFOLOff::where('idSpot', $r->idSpot)
                                    ->orderBy('timestamp', 'desc')
                                    ->first();
                                    
            if ($dataTime->lessThan($minute5)) {
                if ($history == null || $history->ket == 'on') {
                    ListFOLOff::create([
                        'idSpot' => $idSpot,
                        'ket' => 'off',
                        'timestamp' => $r->timestamp,
                    ]);
                }
            } else {
                if ($history != null && $history->ket == 'off') {
                    ListFOLOff::create([
                        'idSpot' => $idSpot,
                        'ket' => 'on',
                        'timestamp' => $r->timestamp,
                    ]);
                }
            }
        }
        
        // $this->sendErrorToWa("berhasil");
        
        return response()->json([
            'message' => 'berhasil',
        ]);
    }
    
    public function sendToDBJambi($message, $target) {
        $response = Http::post('https://folpertaminafieldjambi.com/api/sendToDB', [
            'message' => $message,
            'target'  => $target
        ]);
        
        if ($response->successful()) {
            return "Berhasil: " . $response->body();
        } else {
            return "Gagal: " . $response->status();
        }
    }
}
