<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Spot;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $user = Auth::user();
        $spots = Spot::all();
        
        if ($user->role == 'admin'){
            // return view('admin.welcome', ['user' => $user]);
            return view('admin.welcome', compact('user', 'spots'));
        }elseif($user->role == 'user'){
            return view('user.welcome', ['user' => $user]);
        }elseif($user->role == 'bsnw'){
            return view('operatorbsnw.input', ['user' => $user]);
        }
    }
}
