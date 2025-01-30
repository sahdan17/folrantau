<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Spot;

class SesiController extends Controller
{
    public function index(){
        return view('login');
    }

    public function viewTable(){
        $user = Auth::user();

        return view('admin.table', compact('user'));
    }

    public function viewChart(){
        $user = Auth::user();
        $spots = Spot::all();

        return view('admin.chart', compact('user', 'spots'));
    }

    public function login(Request $req){
        $req->validate([
            'email'=>'required',
            'password'=>'required'
        ],[
            'email.required'=>'Email wajib diisi',
            'password.required'=>'Password wajib diisi',
        ]);

        $infologin = [
            'email'=>$req->email,
            'password'=>$req->password,
        ];

        if (Auth::attempt($infologin)){            
            return redirect('home');
        }else{
            return redirect('login')->withErrors('Username dan password tidak sesuai')->withInput();
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('login');
    }
    
    public function tesSendData() {
        $spots = Spot::all();
        return $this->sendResponse($spots, 'Kirim data successfully!');
    }
}
