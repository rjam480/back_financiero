<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuardarEntidadesController extends Controller
{
    //

    public function guardarEntidades(Request $request)
    {
       
        // $data =  json_decode($request->get('data'),true);
        dd(json_encode($request->all()));
    }
}
