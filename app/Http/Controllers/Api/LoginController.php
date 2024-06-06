<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller

{
    public function login(Request $request){
        $user=User::whereUsername($request->input('username'))->first();

        if (!$user){
            return back()->withErrors([
                'username' => 'username yang anda masukkan salah'
            ])->onlyInput('username');
        }

        if (!\Hash::check($request->input('password'), $user->password)){
            return back()->withErrors([
                'username' => 'username yang anda masukkan salah'
            ])->onlyInput('username');
        }
                
        return response()->json([
            'token'=>$user->createToken('auth')
        ]);
    }

    }
