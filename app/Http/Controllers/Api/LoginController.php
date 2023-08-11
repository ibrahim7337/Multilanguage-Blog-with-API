<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $user = User::where('email' , $request->email)->first();
        if(!Hash::check($request->password , $user->password)){
            return 'Can\'t Login';
        }
        $user_status = ['admin' , 'writer'];
        if(!in_array($user->status , $user_status)){
            return 'Can\'t Login';
        }
        $token = $user->createToken($user->name);
        return response()->json(['token'=>$token->plainTextToken , 'user'=>$user]);
    }

    public function logout(Request $request){
        $user = User::where('id' , $request->id)->first();
        $user->tokens()->delete();
        return $user;
    }
}