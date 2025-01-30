<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Route;
use App\Models\Line;
use App\Models\Spot;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function getSpot()
    {
        $spots = Spot::all();
        $user = Auth::user();

        return view('admin.spot', compact('spots', 'user'));
    }

    public function createSpot(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'namaSpot' => 'required',
            'lokasiSpot' => 'required',
            'codeSpot' => 'required',
            'minValue' => 'required|numeric',
            'stopValue' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Spot::create([
            'namaSpot' => $request['namaSpot'],
            'lokasiSpot' => $request['lokasiSpot'],
            'codeSpot' => $request['codeSpot'],
            'minValue' => $request['minValue'],
            'stopValue' => $request['stopValue'],
        ]);
        
        return redirect('getField');
    }
    
    public function getLine()
    {
        $lines = DB::table('line as l')->select('l.id', 's1.namaSpot as spot1', 's2.namaSpot as spot2')
        ->join('spot as s1', 'l.spot1', '=', 's1.id')
        ->join('spot as s2', 'l.spot2', '=', 's2.id')
        ->get();

        $spots = Spot::all();
        $user = Auth::user();

        return view('admin.line', compact('lines', 'spots', 'user'));
    }

    public function createLine(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'spot1' => 'required|numeric',
            'spot2' => 'required|numeric|different:spot1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $data = Line::create([
            'spot1' => $request['spot1'],
            'spot2' => $request['spot2'],
        ]);

        return redirect('getLine');
    }
}
