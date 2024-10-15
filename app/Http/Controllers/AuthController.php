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
      
        $user = $this->user->where('nit','=',$nit)->first();

        if ($user) {
            $validandoHas = \Hash::check($password,$user->password);
           
            if ($validandoHas) {
                $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
                return response()->json([
                    'data'          => $user,
                    'access_token'  => $token,
                    'token_type'    => 'Bearer',
                    'msg'           => 'Login'
                ]);
            }else{
                return response()->json([
                    'data'          => [],
                    'access_token'  => '',
                    'token_type'    => '',
                    'msg'           => 'Credenciales incorrectas'
                ]);
            }
        }else{
            return response()->json([
                'data'          => [],
                'access_token'  => '',
                'token_type'    => '',
                'msg'           => 'Credenciales incorrectas'
            ]);
        }
    }

    public function authLogout(){
        auth()->user()->tokens()->delete();
    
        return response()->json([
          "message"=>"logged out"
        ]);
    }
}
