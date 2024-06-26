<?php

namespace App\Http\Controllers\Component\Login;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as Auth;


class LoginController extends Controller
{
    public function login(String $email , String $password){
        if(!Auth::attempt(['email' => $email , 'password'=> $password])){
            return false;
        };
        $user = Auth::user();
        $token = $user->createToken($user['email'],[$user->getRoleNames()])->plainTextToken;
        return $token;
    }

    public function check_role(){
        $roles = Auth::user()->getRoleNames()->toArray();
        if(in_array("admin",$roles)){
            return "admin";
        }
        return $roles;
    }

    public function logout(){
        if(Auth::check() == false){
            return false;
        }
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return true;
    }
}
