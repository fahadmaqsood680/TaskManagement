<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
      
        if (Auth::check()) {
            return redirect()->route('dashboard'); 
        }

        return view("login"); 
    }
    public function login(LoginRequest $request){
        $credentials=$request->only(['email','password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('index')->with('msg','Invalid Credentials');
        }
    }
    public function logout(){
        Auth::guard('web')->logout();
        return redirect()->route('index');
    }
}
