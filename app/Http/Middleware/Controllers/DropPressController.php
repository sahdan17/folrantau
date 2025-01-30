<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Line;
use App\Helpers\Predictions;

class DropPressController extends Controller
{
    public function predictLoss(){
        $user = Auth::user();
        if ($user->role == 'admin'){
            return view('admin.predLoss', ['user' => $user]);
        }else{
            return view('user.predLoss', ['user' => $user]);
        }
    }

    public function predictLoc(){
        $user = Auth::user();
        $line = Line::join('spot as s1', 'line.spot1', '=', 's1.id')
                ->join('spot as s2', 'line.spot2', '=', 's2.id')
                ->select('line.id', 's1.codeSpot as spot1', 's2.codeSpot as spot2')
                ->get();

        if ($user->role == 'admin'){
            return view('admin.predLoc', ['user' => $user, 'line' => $line]);
        }else{
            return view('user.predLoc', ['user' => $user, 'line' => $line]);
        }
    }
    
    public function predictLossProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timeB1' => 'nullable|numeric',
            'angkaBJG' => 'nullable|numeric',
            'rateB1' => 'nullable|numeric',
            'rateB2' => 'nullable|numeric',
            'rateB3' => 'nullable|numeric',
            'rateB4' => 'nullable|numeric',
            'rateB5' => 'nullable|numeric',
            'rateB6' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $output = '';

        $TimeB1 = $request->input('timeB1', null);
        $AngkaBJG = $request->input('angkaBJG', null);
        $RateB1 = $request->input('rateB1', null);
        $RateB2 = $request->input('rateB2', null);
        $RateB3 = $request->input('rateB3', null);
        $RateB4 = $request->input('rateB4', null);
        $RateB5 = $request->input('rateB5', null);
        $RateB6 = $request->input('rateB6', null);

        $command = "python ../app/Python/cekLoss.py {$TimeB1} {$AngkaBJG} {$RateB1} {$RateB2} {$RateB3} {$RateB4} {$RateB5} {$RateB6}";
        $output = shell_exec($command);

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dimasukkan',
            'data' => $output
        ]);
    }

    // public function predictLocProcess(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'psiValue1' => 'nullable|numeric',
    //         'psiValue2' => 'nullable|numeric',
    //         'line' => 'nullable|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $output = '';

    //     $line = $request->line;
    //     $psiValue1 = $request->input('psiValue1', null);
    //     $psiValue2 = $request->input('psiValue2', null);

    //     $command = "python ../domains/folpertaminafieldjambi.com/app/Python/pred{$line}/Stream_dataLM.py {$psiValue1} {$psiValue2}";
    //     $output = shell_exec($command);
        
    //     // dd($command);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'data berhasil dimasukkan',
    //         'data' => $output
    //     ]);
    // }
    
    public function predictLocProcess(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'psiValue1' => 'nullable|numeric',
            'psiValue2' => 'nullable|numeric',
            'line' => 'nullable|numeric',
        ]);

        $output = '';

        $line = $request->line;
        $psiValue1 = $request->input('psiValue1', null);
        $psiValue2 = $request->input('psiValue2', null);

        try {
            // Prediksi lokasi kebocoran
            $a = is_numeric($psiValue1) ? floatval($psiValue1) : null;
            $b = is_numeric($psiValue2) ? floatval($psiValue2) : null;
            $prediksi_lokasi = Predictions::{"predictLoc{$line}"}($a, $b);

            $output = $prediksi_lokasi;
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diproses',
            'data' => $output
        ]);
    }
}
