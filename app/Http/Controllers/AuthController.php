<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthController extends Controller
{
        
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(LoginRequest $request) {
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
     
            $request->session()->regenerate();
            Auth::login($user);

            return redirect()->route('customer.index');
        
        
       
    }
public function logout (){
    Auth::logout();
    return redirect()->route('login');
}    
}

