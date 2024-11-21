<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperarPassword;
use App\Jobs\SendMail;
use Carbon\Carbon;
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
        $aceptaTerminos = $request->get('acepta_terminos');


        $user = $this->user->where('nit', '=', $nit)->first();

        // verificamos primero si la fecha de expiracion esta vigente

        $expira  = $this->user->validarFechaExpedicion($user->expira_password);
        if ($expira) {
            return response()->json([
                'data'          => [],
                'access_token'  => '',
                'token_type'    => '',
                'msg'           => 'La contraseña ha expirado, por favor genera una nueva',
                'code_error'    => '1'
            ], 400);
        } else {

            if ($aceptaTerminos == 0) {
                $user->fill([
                    'acepta_terminos' => $aceptaTerminos
                ]);
                $user->save();

                return response()->json([
                    'data'          => [],
                    'access_token'  => '',
                    'token_type'    => '',
                    'msg'           => 'Recuerde que al no aceptar los terminos y condiciones, usted no podra acceder a la información dispuesta en el portal.',
                    'code_error'    => '2'
                ], 400);
            } else {
                $user->fill([
                    'acepta_terminos' => $aceptaTerminos
                ]);
                $user->save();
                if ($user) {
                    $validandoHas = \Hash::check($password, $user->password);
                    $user->auditEvent('login');
                    if ($validandoHas) {
                        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
                        return response()->json([
                            'data'          => $user,
                            'access_token'  => $token,
                            'token_type'    => 'Bearer',
                            'msg'           => 'Login',
                            'code_error'    => ''
                        ], 200);
                    } else {
                        return response()->json([
                            'data'          => [],
                            'access_token'  => '',
                            'token_type'    => '',
                            'msg'           => 'Credenciales incorrectas',
                            'code_error'    => ''
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'data'          => [],
                        'access_token'  => '',
                        'token_type'    => '',
                        'msg'           => 'Credenciales incorrectas',
                        'code_error'    => ''
                    ], 400);
                }
            }
        }
    }

    public function enviarEmail(Request $request)
    {
        $nit = $request->get('nit');
        $user = $this->user->where('nit', '=', $nit)->first();
        $url_front= env('FRONT_FINACNIERA_URL');
       
        if ($user) {
            $token = str()->random(25);
            $user->fill([
                'remember_token' => $token
            ]);
            $user->save();
            if ($user->email) {
                
                $link = $url_front."recuperarContrasena?"."token=".$token."&nit=".$nit;
                
                
                Mail::to($user->email)->send(new RecuperarPassword([
                    'link' => $link,
                ]));
                $mail  = $this->obfuscateEmail($user->email);
                
                return response()->json([
                    'data'          => [],
                    'access_token'  => '',
                    'token_type'    => '',
                    'msg'           => "Hemos envido una notificacioón al email $mail, en donde usted podrá realizar el respectivo cambio de contraseña",
                    'code_error'    => ''
                ], 200);
            } else {
                return response()->json([
                    'data'          => [],
                    'access_token'  => '',
                    'token_type'    => '',
                    'msg'           => 'Email no registrado comunicate con el administrador',
                    'code_error'    => ''
                ], 400);
            }
        } else {
            return response()->json([
                'data'          => [],
                'access_token'  => '',
                'token_type'    => '',
                'msg'           => 'Prestador no registrado',
                'code_error'    => ''
            ], 400);
        }
    }

    public function recuperarPassword(Request $request)
    {
        $timpo = (int) env('TIEMPO_EXPIRA');
        $hoy = new Carbon();
        $hoy = $hoy->addMonth($timpo);
        $hoy = $hoy->format('Y-m-d');
        $nit = $request->get('nit');
        $token = $request->get('token');
        $password = $request->get('password');
        $user = $this->user->where('nit', '=', $nit)->first();

        if ($token == $user->remember_token) {

            $user->fill([
                'remember_token' => null,
                'password' => $password,
                'expira_password'=>$hoy
            ]);
            $user->save();

            return response()->json([
                'data'          => [],
                'access_token'  => '',
                'token_type'    => '',
                'msg'           => 'Su contraseña  ha sido actualizada satisfactoriamente',
                'code_error'    => ''
            ], 200);
        } else {
            return response()->json([
                'data'          => [],
                'access_token'  => '',
                'token_type'    => '',
                'msg'           => 'Token expiro',
                'code_error'    => ''
            ], 400);
        }
    }


    public function authLogout()
    {
        auth()->user()->auditEvent('logout');
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logged out"
        ]);
    }

    public function creacionCuentaEmail(Request $request)
    {
        
        SendMail::dispatch($request->get('data'));

        return response()->json([
            'data'          => [],
            'access_token'  => '',
            'token_type'    => '',
            'msg'           => 'Tarea de envio de correos exitosa',
            'code_error'    => ''
        ],200);
    }

    private function obfuscateEmail(string $email)
    {
        $em = explode("@", $email);
        $name = implode(array_slice($em, 0, count($em)-1));
        $len = floor(strlen($name) / 2);
        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}
