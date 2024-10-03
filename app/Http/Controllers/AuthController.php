<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class AuthController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function authLogin(Request $request)
    {

        $nit = $request->get('nit');
        $password = $request->get('password');
        // $loginUserData = $request->validate([
        //     'email'=>'required|string|email',
        //     'password'=>'required|min:8'
        // ]);
        $user = User::where('name',$nit)->first();
        $validandoHas = \Hash::check($password,$user->password);
        dd($validandoHas);
        // if(!$user || !Hash::check($loginUserData['password'],$user->password)){
        //     return response()->json([
        //         'message' => 'Invalid Credentials'
        //     ],401);
        // }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function authLogout(){
        auth()->user()->tokens()->delete();
    
        return response()->json([
          "message"=>"logged out"
        ]);
    }
}
