<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // $request->session()->regenerate();
            // return redirect()->intended('dashboard');
            return response()->json(['success' => true]);
        }

        return response()->json([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    
    public function logout() {
        
        Session::flush();
        Auth::logout();
    }
}
