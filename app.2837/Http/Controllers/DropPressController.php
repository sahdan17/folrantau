<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DropPressController extends Controller
{
    public function predictLoss(){
        return view('predLoss');
    }
    
    public function predictLossProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Rate1' => 'required|numeric',
            'Pressure1' => 'required|numeric',
            'Pressure2' => 'required|numeric',
            'Durasi' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $output = '';

        $Rate1 = $request->input('Rate1');
        $Pressure1 = $request->input('Pressure1');
        $Pressure2 = $request->input('Pressure2');
        $Durasi = $request->input('Durasi');

        $command = "python ../app/Python/Stream_data2.py {$Rate1} {$Pressure1} {$Pressure2} {$Durasi}";
        $output = shell_exec($command);
        
        return response()->json([
            'success' => true,
            'message' => 'data berhasil dimasukkan',
            'data' => $output
        ]);
    }
}
