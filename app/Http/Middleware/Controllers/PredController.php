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
    
//     public function pred1Test($psiValue, $timestamp){
//         // on off pompa
//         if ($psiValue >= 131 xor $psiValue <= 100){
            
//         } 
//         // pressure drop spot 1 (bjg-tpn)
//         else {
//             $avg1 = Pressure::where('idSpot', 1)
//                     ->where('timestamp', '>', $this->minuteAgoBJG)
//                     ->avg('psiValue');

//             // if ($avg1 < 131 && $avg1 > 100){
//                 $normal1 = Pressure::where('idSpot', 1)
//                         // ->where('timestamp', '>', $this->minuteAgoBJG)
//                         ->where('psiValue', '>=', 131)
//                         ->orderBy('timestamp', 'desc')
//                         ->limit(10)
//                         ->avg('psiValue');
                        
//                 if ($normal1 == null){
//                     $normal1 = 131;
//                 }

//                 $x1 = $normal1 - $avg1;

//                 $normal2 = Pressure::where('idSpot', 2)
//                         // ->where('timestamp', '>', $this->minuteAgoBJG)
//                         ->where('psiValue', '>=', 79)
//                         ->orderBy('timestamp', 'desc')
//                         ->limit(10)
//                         ->avg('psiValue');

//                 if ($normal2 == null){
//                     $normal2 = 79;
//                 }

//                 $avg2 = Pressure::where('idSpot', 2)
//                         ->where('timestamp', '>', $this->minuteAgoBJG)
//                         ->avg('psiValue');

//                 // if ($avg2 < 79 && $avg2 >60){
//                     $x2 = $normal2 - $avg2;

//                     // try {
//                         $a = is_numeric($x1) ? floatval($x1) : null;
//                         $b = is_numeric($x2) ? floatval($x2) : null;
//                         $prediksi_lokasi = Predictions::predictLoc1Test($a, $b);
            
//                         $output = $prediksi_lokasi;
                        
//                         $carbonDate = Carbon::parse($timestamp);

//                         $namaHari = $carbonDate->translatedFormat('l');
//                         $tanggal = $carbonDate->format('d-m-Y');
//                         $waktu = $carbonDate->format('H:i:s');
                        
//                         if ($output[1] == true){
//                             $message = "⚠️ *Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
// tanggal {$tanggal}
// jam {$waktu}
// {$output[0]}
// ";
//                         } else {
//                             $message = "*Trunkline MGS BJG - BOOSTER*
// - Terjadi Penurunan Tekanan
// - Hari {$namaHari}
// tanggal {$tanggal}
// jam {$waktu}
// {$output[0]}
// ";
//                         }
                        
//                         $this->sendNotifWA($message);
//                     // } catch (\Exception $e) {
//                     //     return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
//                     //     $output = 'gagal dapat data lokasi';
//                     // }
//                 // }
//             // }
//         }
//     }

    public function pred1($psiValue, $timestamp){
        // on off pompa
        if ($psiValue >= 131 xor $psiValue <= 100){
            if ($psiValue >= 131){
                $notif = Pressure::where('idSpot', 1)
                    ->where('psiValue', '>', 120)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🟢 1 _MGS BJG - MOS TPN_
    *START POMPA*";
                    $this->sendNotif($notifWA, 1, 'start', $timestamp);
                }
            } else if ($psiValue <= 25) {
                $notif = Pressure::where('idSpot', 1)
                    ->where('psiValue', '<', 10)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🔴 1 _MGS BJG - MOS TPN_
    *STOP POMPA*";
                    $this->sendNotif($notifWA, 1, 'stop', $timestamp);
                }
            }
        } 
        // pressure drop spot 1 (bjg-tpn)
        else {
            $avg1 = Pressure::where('idSpot', 1)
                    ->where('timestamp', '>', $this->minuteAgoBJG)
                    ->avg('psiValue');

            if ($avg1 < 131 && $avg1 > 100){
                $normal1 = Pressure::where('idSpot', 1)
                        // ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->where('psiValue', '>=', 131)
                        ->orderBy('timestamp', 'desc')
                        ->limit(10)
                        ->avg('psiValue');
                        
                if ($normal1 == null){
                    $normal1 = 131;
                }

                $x1 = $normal1 - $avg1;

                $normal2 = Pressure::where('idSpot', 2)
                        // ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->where('psiValue', '>=', 79)
                        ->orderBy('timestamp', 'desc')
                        ->limit(10)
                        ->avg('psiValue');

                if ($normal2 == null){
                    $normal2 = 79;
                }

                $avg2 = Pressure::where('idSpot', 2)
                        ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->avg('psiValue');

                if ($avg2 < 79 && $avg2 >60){
                    $x2 = $normal2 - $avg2;

                    try {
                        $a = is_numeric($x1) ? floatval($x1) : null;
                        $b = is_numeric($x2) ? floatval($x2) : null;
                        $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
                        $output = $prediksi_lokasi;
                        
                        $carbonDate = Carbon::parse($timestamp);

                        $namaHari = $carbonDate->translatedFormat('l');
                        $tanggal = $carbonDate->format('d-m-Y');
                        $waktu = $carbonDate->format('H:i:s');
                        
                        if ($output[1] == true){
                            $message = "⚠️ *Trunkline MGS BJG - BOOSTER*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        } else {
                            $message = "*Trunkline MGS BJG - BOOSTER*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        }

                        $this->sendNotifWA($message);
                    } catch (\Exception $e) {
                        return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                        $output = 'gagal dapat data lokasi';
                    }
                }
            }
        }
    }

    public function pred2($psiValue, $timestamp){
        if ($psiValue >= 79 xor $psiValue <= 60){
            $data['output'] = "id 2 normal";
        }else{
            $avg2 = Pressure::where('idSpot', 2)
                    ->where('timestamp', '>', $this->minuteAgoBJG)
                    ->avg('psiValue');

            if ($avg2 < 79 && $avg2 > 60){
                $normal2 = Pressure::where('idSpot', 2)
                        // ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->where('psiValue', '>=', 79)
                        ->orderBy('timestamp', 'desc')
                        ->limit(10)
                        ->avg('psiValue');
                if ($normal2 == null){
                    $normal2 = 79;
                }

                $x2 = $normal2 - $avg2;

                $normal1 = Pressure::where('idSpot', 1)
                        // ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->where('psiValue', '>=', 131)
                        ->orderBy('timestamp', 'desc')
                        ->limit(10)
                        ->avg('psiValue');

                if ($normal1 == null){
                    $normal1 = 131;
                }

                $avg1 = Pressure::where('idSpot', 1)
                        ->where('timestamp', '>', $this->minuteAgoBJG)
                        ->avg('psiValue');

                if ($avg1 < 131 && $avg1 > 100){
                    $x1 = $normal1 - $avg1;

                    try {
                        $a = is_numeric($x1) ? floatval($x1) : null;
                        $b = is_numeric($x2) ? floatval($x2) : null;
                        $prediksi_lokasi = Predictions::predictLoc1($a, $b);
            
                        $output = $prediksi_lokasi;
                        
                        $carbonDate = Carbon::parse($timestamp);

                        $namaHari = $carbonDate->translatedFormat('l');
                        $tanggal = $carbonDate->format('d-m-Y');
                        $waktu = $carbonDate->format('H:i:s');
                        
                        if ($output[1] == true){
                            $message = "⚠️ *Trunkline MGS BJG - BOOSTER*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        } else {
                            $message = "*Trunkline MGS BJG - BOOSTER*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        }

                        $this->sendNotifWA($message);
                    } catch (\Exception $e) {
                        return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                        $output = 'gagal dapat data lokasi';
                    }
                }
            }
        }
    }

    public function pred3($psiValue, $timestamp){
        if ($psiValue >= 127 xor $psiValue <= 20){
            if ($psiValue >= 127){
                $notif = Pressure::where('idSpot', 3)
                    ->where('psiValue', '>', 110)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🟢 2 _MGS KAS - MOS TPN_
    *START POMPA*";
                    $this->sendNotif($notifWA, 3, 'start', $timestamp);
                }
            } else {
                $notif = Pressure::where('idSpot', 3)
                    ->where('psiValue', '<', 20)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🔴 2 _MGS KAS - MOS TPN_
    *STOP POMPA*";
                    $this->sendNotif($notifWA, 3, 'stop', $timestamp);
                }
            }
        } else {
            $avg3 = Pressure::where('idSpot', 3)
                    ->where('timestamp', '>', $this->minuteAgo)
                    ->avg('psiValue');

            if ($avg3 < 127 && $avg3 > 100){
                $normal3 = Pressure::where('idSpot', 3)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->where('psiValue', '>=', 127)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');
                if ($normal3 == null){
                    $normal3 = 127;
                }

                $x3 = $normal3 - $avg3;

                $normal4 = Pressure::where('idSpot', 4)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->where('psiValue', '>=', 10)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');

                if ($normal4 == null){
                    $normal4 = 10;
                }

                $avg4 = Pressure::where('idSpot', 4)
                        ->where('timestamp', '>', $this->minuteAgo)
                        ->avg('psiValue');

                if ($avg4 < 10){
                    $x4 = $normal4 - $avg4;

                    try {
                        $a = is_numeric($x3) ? floatval($x3) : null;
                        $b = is_numeric($x4) ? floatval($x4) : null;
                        $prediksi_lokasi = Predictions::predictLoc2($a, $b);
            
                        $output = $prediksi_lokasi;
                        
                        $carbonDate = Carbon::parse($timestamp);

                        $namaHari = $carbonDate->translatedFormat('l');
                        $tanggal = $carbonDate->format('d-m-Y');
                        $waktu = $carbonDate->format('H:i:s');
                        
                        if ($output[1] == true){
                            $message = "⚠️ *Trunkline MGS KAS - MOS TPN*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        } else {
                            $message = "*Trunkline MGS KAS - MOS TPN*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        }

                        $this->sendNotifWA($message);
                    } catch (\Exception $e) {
                        return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                        $output = 'gagal dapat data lokasi';
                    }
                }
            }
        }
    }

    public function pred4($psiValue, $timestamp){
        if ($psiValue >= 10 xor $psiValue <= 8){
            $data['output'] = "id 4 normal";
        } else {            
            $avg4 = Pressure::where('idSpot', 4)
                    ->where('timestamp', '>', $this->minuteAgo)
                    ->avg('psiValue');

            if ($avg4 < 10 && $avg4 >= 8){
                $normal4 = Pressure::where('idSpot', 4)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->where('psiValue', '>=', 10)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');
                if ($normal4 == null){
                    $normal4 = 10;
                }

                $x4 = $normal4 - $avg4;

                $normal3 = Pressure::where('idSpot', 3)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->where('psiValue', '>=', 127)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');

                if ($normal3 == null){
                    $normal3 = 127;
                }

                $avg3 = Pressure::where('idSpot', 3)
                        ->where('timestamp', '>', $this->minuteAgo)
                        ->avg('psiValue');

                if ($avg3 < 127 && $avg3 > 100){
                    $x3 = $normal3 - $avg3;

                    try {
                        $a = is_numeric($x3) ? floatval($x3) : null;
                        $b = is_numeric($x4) ? floatval($x4) : null;
                        $prediksi_lokasi = Predictions::predictLoc2($a, $b);
            
                        $output = $prediksi_lokasi;
                        
                        $carbonDate = Carbon::parse($timestamp);

                        $namaHari = $carbonDate->translatedFormat('l');
                        $tanggal = $carbonDate->format('d-m-Y');
                        $waktu = $carbonDate->format('H:i:s');
                        
                        if ($output[1] == true){
                            $message = "⚠️ *Trunkline MGS KAS - MOS TPN*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        } else {
                            $message = "*Trunkline MGS KAS - MOS TPN*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                        }

                        $this->sendNotifWA($message);
                    } catch (\Exception $e) {
                        return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                        $output = 'gagal dapat data lokasi';
                    }
                }
            }
        }
    }

    public function pred6($psiValue, $timestamp){
        if ($psiValue >= 150 xor $psiValue <= 35){
            if ($psiValue >= 150){
                $notif = Pressure::where('idSpot', 6)
                    ->where('psiValue', '>', 150)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🟢 4 _GS 01 KTT - MGS KAS_
    *START POMPA*";
                    $this->sendNotif($notifWA, 6, 'start', $timestamp);
                }
            } else {
                $notif = Pressure::where('idSpot', 6)
                    ->where('psiValue', '<', 35)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🔴 4 _GS 01 KTT - MGS KAS_
    *STOP POMPA*";
                    $this->sendNotif($notifWA, 6, 'stop', $timestamp);
                }
            }
        }
        if ($psiValue <= 200 and $psiValue >= 180){
            $avg6 = Pressure::where('idSpot', 6)
                    ->where('timestamp', '>', $this->minuteAgo)
                    ->avg('psiValue');

            // if ($avg6 < 200 && $avg6 > 180){
                $normal6 = Pressure::where('idSpot', 6)
                        ->where('psiValue', '>=', 200)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');

                if ($normal6 == null){
                    $normal6 = 200;
                }

                $x6 = $normal6 - $avg6;

                try {
                    $a = is_numeric($x6) ? floatval($x6) : null;
                    $prediksi_lokasi = Predictions::predictLoc4($a);
            
                    $output = $prediksi_lokasi;
                    
                    $carbonDate = Carbon::parse($timestamp);

                    $namaHari = $carbonDate->translatedFormat('l');
                    $tanggal = $carbonDate->format('d-m-Y');
                    $waktu = $carbonDate->format('H:i:s');
                    
                    if ($output[1] == true){
                        $message = "⚠️ *Trunkline GS 01 KTT - MGS KAS*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                    } else {
                        $message = "*Trunkline GS 01 KTT - MGS KAS*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                    }
                    
                    // $this->sendNotifWA($message);
                    $data['output'] = $output;
                    
                } catch (\Exception $e) {
                    return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                    $output = 'gagal dapat data lokasi';
                }
            // }
        } else {                
            // aman
        }
    }

    public function pred5($psiValue, $timestamp){
        if ($psiValue >= 90 xor $psiValue <= 50){
            if ($psiValue >= 90){
                $notif = Pressure::where('idSpot', 5)
                    ->where('psiValue', '>', 90)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🟢 3 _GS 02 SGL - MGS KAS_
    *START POMPA*";
                    $this->sendNotif($notifWA, 5, 'start', $timestamp);
                }
            } else {
                $notif = Pressure::where('idSpot', 5)
                    ->where('psiValue', '<', 50)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🔴 3 _GS 02 SGL - MGS KAS_
    *STOP POMPA*";
                    $this->sendNotif($notifWA, 5, 'stop', $timestamp);
                }
            }
        }
        if ($psiValue <= 80 and $psiValue >= 60){
            $avg5 = Pressure::where('idSpot', 5)
                    ->where('timestamp', '>', $this->minuteAgo)
                    ->avg('psiValue');

            if ($avg5 < 80 && $avg5 > 60){
                $normal5 = Pressure::where('idSpot', 5)
                        ->where('psiValue', '>=', 80)
                        // ->where('timestamp', '>', $this->minuteAgo)
                        ->orderBy('timestamp', 'desc')
                        ->limit(5)
                        ->avg('psiValue');

                if ($normal5 == null){
                    $normal5 = 80;
                }

                $x5 = $normal5 - $avg5;

                try {
                    $a = is_numeric($x5) ? floatval($x5) : null;
                    // $b = is_numeric($delta5) ? floatval($delta5) : null;
                    $prediksi_lokasi = Predictions::predictLoc3($a);
            
                    $output = $prediksi_lokasi;
                    
                    $carbonDate = Carbon::parse($timestamp);

                    $namaHari = $carbonDate->translatedFormat('l');
                    $tanggal = $carbonDate->format('d-m-Y');
                    $waktu = $carbonDate->format('H:i:s');
                    
                    if ($output[1] == true){
                        $message = "⚠️ *Trunkline GS 02 SGL - MGS KAS*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                    } else {
                        $message = "*Trunkline GS 02 SGL - MGS KAS*
- Terjadi Penurunan Tekanan
- Hari {$namaHari}
tanggal {$tanggal}
jam {$waktu}
{$output[0]}
";
                    }
                    
                    $this->sendNotifWA($message);
                    $data['output'] = $output;
                    
                } catch (\Exception $e) {
                    return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
                    $output = 'gagal dapat data lokasi';
                }
            }            
        } else {
            // aman
        }
    }    

    public function pred7($psiValue, $timestamp){
        if ($psiValue >= 30 xor $psiValue <= 15){
            if ($psiValue >= 30){
                $notif = Pressure::where('idSpot', 7)
                    ->where('psiValue', '>', 30)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🟢 5 _MGS TPN - MOS TPN_
    *START POMPA*";
                    $this->sendNotif($notifWA, 7, 'start', $timestamp);
                }
            } else {
                $notif = Pressure::where('idSpot', 7)
                    ->where('psiValue', '<', 15)
                    ->where('timestamp', '>', $this->cekNotif)
                    ->get();

                if (count($notif) == 10){
                    $notifWA = "🔴 5 _MGS TPN - MOS TPN_
    *STOP POMPA*";
                    $this->sendNotif($notifWA, 7, 'stop', $timestamp);
                }
            }
        }
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
                    'target' => '120363306996925103@g.us',
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
    
//     public function dailyReminder($count){
//         $timestamp = Carbon::now('Asia/Jakarta');
//         $date = $timestamp->format('Y-m-d');
        
//         $dateFull = $timestamp->translatedFormat('l, d F Y');

//         $history = HistOnOff::where('idSpot', 0)
//                 ->where('timestamp', '>=', $date)
//                 ->where('count', $count)
//                 ->first();
                
//         $spots = Spot::get();
        
//         foreach($spots as $s){
//             $countOn[$s->id] = HistOnOff::where('idSpot', $s->id)
//                 ->where('timestamp', '>', $date)
//                 ->where('ket', 'start')
//                 ->count('id');
                
//             $countOff[$s->id] = HistOnOff::where('idSpot', $s->id)
//                 ->where('timestamp', '>', $date)
//                 ->where('ket', 'stop')
//                 ->count('id');
//         }
                
//         if ($history == null){
//             $createHist = HistOnOff::create([
//                 'idSpot' => 0,
//                 'ket' => 'daily',
//                 'timestamp' => $timestamp,
//                 'count' => $count
//             ]);

//             $message = "*Rekap On/Off Pompa* $dateFull
// 1. MGS BJG - MOS TPN On $countOn[1]x, Off $countOff[1]x
// 2. MGS KAS - MOS TPN On $countOn[3]x, Off $countOff[3]x
// 3. GS 02 SGL - MGS KAS On $countOn[5]x, Off $countOff[5]x
// 4. GS 01 KTT - MGS KAS On $countOn[6]x, Off $countOff[6]x
// 5. MGS TPN - MOS TPN On $countOn[7]x, Off $countOff[7]x";

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
    
    public function dailyReminder($count){
        // $count = $req->count;
        $timestamp = Carbon::now('Asia/Jakarta');
        $date = $timestamp->format('Y-m-d');
        
        $dateFull = $timestamp->translatedFormat('l, d F Y');

        $history = HistOnOff::where('idSpot', 0)
                ->where('timestamp', '>=', $date)
                ->where('count', $count)
                ->first();
                
        $spots = Spot::get();
        
        foreach($spots as $s){
            $countOn[$s->id] = HistOnOff::where('idSpot', $s->id)
                ->where('timestamp', '>', $date)
                ->where('ket', 'start')
                ->count('id');
                
            $countOff[$s->id] = HistOnOff::where('idSpot', $s->id)
                ->where('timestamp', '>', $date)
                ->where('ket', 'stop')
                ->count('id');
                
            $countList[$s->id] = HistOnOff::where('idSpot', $s->id)
                ->where('timestamp', '>', $date)
                ->get();
                
            foreach($countList[$s->id] as $i => $c){
                if ($c->ket == 'start'){
                    $time = Carbon::parse($c->timestamp)->format('H:i');;
                    $rekap[$s->id][$i] = "On: $time";
                } elseif ($c->ket == 'stop'){
                    $time = Carbon::parse($c->timestamp)->format('H:i');;
                    $rekap[$s->id][$i] = "Off: $time";
                }
            }
        }
        
        foreach ($rekap as $id => $events) {
            $previousTime = null;
            $previousState = null;
            $result = '';
        
            foreach ($events as $i => $event) {
                $split = explode(': ', $event);
                $state = $split[0];
                $time = $split[1];
        
                if ($previousTime !== null) {
                    if ($previousState == 'On' && $state == 'Off') {
                        $result .= "    On: $previousTime - $time\n";
                    } elseif ($previousState == 'Off' && $state == 'On') {
                        $result .= "    Off: $previousTime - $time\n";
                    }
                }
        
                $previousTime = $time;
                $previousState = $state;
            }
        
            if ($previousState == 'Off') {
                $result .= "    Off: $previousTime - now";
            } elseif ($previousState == 'On') {
                $result .= "    On: $previousTime - now";
            }
            
            $rekapFinal[$id] = $result;
        }
        
        if ($history == null){
            $createHist = HistOnOff::create([
                'idSpot' => 0,
                'ket' => 'daily',
                'timestamp' => $timestamp,
                'count' => $count
            ]);

            $message = "*Rekap On/Off Pompa* 
$dateFull
*1. MGS BJG - MOS TPN* 
    On $countOn[1]x, Off $countOff[1]x
$rekapFinal[1]
*2. MGS KAS - MOS TPN* 
    On $countOn[3]x, Off $countOff[3]x
$rekapFinal[3]
*3. GS 02 SGL - MGS KAS* 
    On $countOn[5]x, Off $countOff[5]x
$rekapFinal[5]
*4. GS 01 KTT - MGS KAS* 
    On $countOn[6]x, Off $countOff[6]x
$rekapFinal[6]
*5. MGS TPN - MOS TPN* 
    On $countOn[7]x, Off $countOff[7]x
$rekapFinal[7]";

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
                    'target' => '120363306996925103@g.us',
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
                'target' => '120363287006483582@g.us',
                // 'target' => '082289002445',
                'message' => $message),
            CURLOPT_HTTPHEADER => array(
                'Authorization: _v-ovBD!PVaxjDhc2Li2'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
