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
            return response()->json(['message' => 'username yang anda masukkan salah'], 401);
        }

        if (!\Hash::check($request->input('password'), $user->password)){
            return response()->json(['message' => 'password yang anda masukkan salah'], 401);
        }

        $pelanggan = $user->customer;
                
        return response()->json([
            'token'=>$user->createToken('auth')->plainTextToken,
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'nama_pelanggan' => $pelanggan->nama
        ]);
    }

}
