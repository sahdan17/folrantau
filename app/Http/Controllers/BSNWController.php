<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BSNW;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonLocale;

class BSNWController extends Controller
{
    public function input(Request $request){
        $date = $request->date;
        $time = $request->time;
        $line = strtoupper($request->line);
        $percent = $request->percent;
        $tank = $request->tank;
        $structure = strtoupper($request->structure);

        $data = BSNW::create([
            'date' => $date,
            'time' => $time,
            'line' => $line,
            'percent' => $percent,
            'tank' => $tank,
            'structure' => $structure,
        ]);

        if ($percent >= 0.3) {
            $message = "*BS&W > 0.3% !!!*
            Tanggal: $date
            Jam Pengukuran: $time WIB
            Line $line: $percent%
            Asal Tangki: $tank
            Asal Struktur: $structure";      
            
            $this->sendWhatsAppMessage($message);
        }
    }

    private function dailyrekap($date){
        $lines = ['KAS', 'BJG', 'TPN'];

        foreach ($lines as $line){
            $lineData = BSNW::whereDate('date', $date)
                ->where('line', $line)
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($item) {
                    $item->time = Carbon::parse($item->time)->format('H:i');
                    $item->date = Carbon::parse($item->date)->format('d-m-Y');
                    return $item;
                });

            $average = round(BSNW::whereDate('date', $date)
                ->where('line', $line)
                ->avg('percent'), 3);
            $message = '';
            $dateFormat = Carbon::parse($date)->format('d-m-Y');
            
            $message .= "Rekap pengukuran harian BS&W\r\nMGS KAS $dateFormat\r\n\r\n";
            foreach ($lineData as $d){
                $message .= "$d->time/*$d->percent*/$d->tank/$d->structure\r\n";
            }
            $message .= "\r\nBS&W Rata-rata: $average";

            $this->sendWhatsAppMessage($message);
        }        
    }

    public function report(Request $request){
        $date = $request->date;

        $this->dailyrekap($date);

        return response()->json(['success' => true]);
    }

    private function sendWhatsAppMessage($message)
    {
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
                'target' => '082289002445',
                'message' => $message),
            CURLOPT_HTTPHEADER => array(
                'Authorization: _v-ovBD!PVaxjDhc2Li2'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function index (Request $request){
        $date = $request->date;
        $lines = ['KAS', 'BJG', 'TPN'];
        $user = Auth::user();
        $data = [];

        foreach ($lines as $line) {
            $lineData = BSNW::whereDate('date', $date)
                ->where('line', $line)
                ->orderBy('time', 'asc')
                ->get()
                ->map(function ($item) {
                    $item->time = Carbon::parse($item->time)->format('H:i');
                    return $item;
                });

            $average = round(BSNW::whereDate('date', $date)
                ->where('line', $line)
                ->avg('percent'), 3);

            $data[] = [
                'line' => $line,
                'data' => $lineData,
                'average' => $average
            ];
        }
        
        return response()->json([
            'data' => $data,
        ]);
    }

    public function get() {
        $user = Auth::user();

        return view('operatorbsnw.report', compact('user'));
    }

    public function indexAll (Request $request){
        $date = $request->date;
        $line = ['KAS', 'BJG', 'TPN'];
        $user = Auth::user();

        $data = BSNW::whereDate('date', $date)
                ->orderBy('id', 'asc')
                ->get();

        $bsnw = [];

        foreach ($data as $d){
            $datetime[$d->id] = explode(' ', $d->date);
            $bsnw[$d->id]['tgl'] = $datetime[$d->id][0];
            $bsnw[$d->id]['jam'] = $datetime[$d->id][1];
            $bsnw[$d->id]['line'] = $d->line;
            $bsnw[$d->id]['percent'] = $d->percent;
            $bsnw[$d->id]['tank'] = $d->tank;
            $bsnw[$d->id]['structure'] = $d->structure;
        }

        $average = round(BSNW::avg('percent'), 3);
        
        return $this->sendResponse($bsnw, 'Kirim data successfully!');
        // return view('operatorbsnw.report', compact('user', 'bsnw', 'average'));
    }
}
