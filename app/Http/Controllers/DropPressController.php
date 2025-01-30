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

    public function predictLocation(){
        $user = Auth::user();

        if ($user->role == 'admin'){
            return view('admin.predLoc', ['user' => $user]);
        }else{
            return view('user.predLoc', ['user' => $user]);
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
    
    public function predictLocProcess(Request $request)
    {
        $output = '';

        $x1 = $request->x1;
        $x2 = $request->x2;
        $x3 = $request->x3;
        $x4 = $request->x4;
        $x5 = $request->x5;
        $x6 = $request->x6;
        $x7 = $request->x7;
        $x8 = $request->x8;
        $x9 = $request->x9;
        $type = $request->type;
        
        $data = [$x1,$x2,$x3,$x4,$x5,$x6,$x7,$x8,$x9];

        try {
            $a = floatval($x1);
            $b = floatval($x2);
            $c = floatval($x3);
            $d = floatval($x4);
            $e = floatval($x5);
            $f = floatval($x6);
            $g = floatval($x7);
            $h = floatval($x8);
            $i = floatval($x9);
            $prediksi_lokasi = Predictions::{"predictLoc"}($a, $b, $c, $d, $e, $f, $g, $h, $i, $type);

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
