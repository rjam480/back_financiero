<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bases;
use App\Models\Radicaciones;
use App\Models\Giros;
class FinancieroController extends Controller
{

    protected $bases,$radicaciones,$giros; 
    public function __construct(Bases $bases,Radicaciones $radicaciones,Giros $giros)
    {
        $this->bases = $bases;
        $this->radicaciones = $radicaciones;
        $this->giros = $giros;
    }
    public function consultaFinanciero(Request $request, $nit)
    {


        $cabeceraInforme =  $this->bases->obtenerInfomarcionBaseCabecera($nit);
        $primeraSeccionInforme = $this->radicaciones->obtenerRadicacionesPorEstadosCSA($nit);
        $segundaSeccionInforme = $this->radicaciones->obtenerTotalCorrienteTotalRadicacion($nit);
        $terceraSeccionInforme = $this->giros->obtenerGiros($nit);
        
        $dataResponse = [
            'cabecera' => $this->agregarValoresaCabecera($cabeceraInforme, $segundaSeccionInforme, $terceraSeccionInforme),
            'primer_seccion' => $primeraSeccionInforme,
            'segunda_seccion' => $this->combinarSeccionConTercerSeccion($segundaSeccionInforme, $terceraSeccionInforme),
            'tercer_seccion' => $this->girosCorrienteRadicadoPorcentaje($segundaSeccionInforme, $terceraSeccionInforme),
            'total_2024' => $this->totalAnioCorrienteRadicacion($segundaSeccionInforme, $terceraSeccionInforme),
            'intervencion_mes' => $this->totalIntervencionMes($segundaSeccionInforme, $terceraSeccionInforme),
        ];

        return response()->json($dataResponse, 200);
    }


    public function agregarValoresaCabecera($cabecera, $segundaSeccion, $terceraSeccion)
    {

        $mesActual = date('m');
        $mes = [
            "01" => "ENE",
            "02" => "FEB",
            "03" => "MAR",
            "04" => "ABR",
            "05" => "MAY",
            "06" => "JUN",
            "07" => "JUL",
            "08" => "AGO",
            "09" => "SEP",
            "10" => "OCT",
            "11" => "NOV",
            "12" => "DIC",
        ];
        // calculamos el total de radicacion


        $arrayReferenceSegundaSeccion =  unserialize(serialize($segundaSeccion));
        $arrayReferenceTerceraSeccion =  unserialize(serialize($terceraSeccion));
        $totalRadicacion = $arrayReferenceSegundaSeccion[1];
        unset($totalRadicacion->Clasificacion);
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion = array_sum($totalRadicacion);
        // tomamos el ultimo mes en curso
        $girosMesActual = (array) $arrayReferenceTerceraSeccion[0];
        $selecionMes = $mes[$mesActual];
        $girosMesActual = $girosMesActual[$selecionMes];
        $cabecera[0]->promedio_radicacion = $totalRadicacion;
        $cabecera[0]->giro_mes_actual = $girosMesActual;
       
        return $cabecera;
    }

    public function totalAnioCorrienteRadicacion($segundaSeccion, $terceraSeccion)
    {

        
    
        $arrayReferenceSegundaSeccion =  unserialize(serialize($segundaSeccion));
        $arrayReferenceTerceraSeccion =  unserialize(serialize($terceraSeccion));

        $totalCorriente = $this->eliminarMesAnteriorYPosteriores($arrayReferenceSegundaSeccion[0]);
       
        $totalRadicacion = $this->eliminarMesAnteriorYPosteriores($arrayReferenceSegundaSeccion[1]);
        // sumatoria total de datos total corriente
      
        /* if ($mesActual != '01') {
           

            foreach ($totalCorriente as $keyT => $valueT) {
                # code...
                $mesActual = (int) $mesActual;
                if ((int) $mesActual <= 12) {
                    $mesActual = ($mesActual <=9) ? "0$mesActual":$mesActual;
                    $keyMesremove = $mes[$mesActual];
                    
                    $mesActual ++;
                   
                    unset($totalCorriente->$keyMesremove);
                    
                }
            }
        } */
        
        unset($totalCorriente->Clasificacion);
        $totalCorriente = (array) $totalCorriente;
        $totalCorriente =  array_sum($totalCorriente);
        // sumatoria total de datos total radicacion
       /*  $mesActual = date('m');
        if ($mesActual != '01') {
           

            foreach ($totalRadicacion as $keyR => $valueR) {
                # code...
                if ((int) $mesActual <= 12) {
                  
                    $mesActual = ($mesActual <=9) ? "0$mesActual":$mesActual;
                    $keyMesremove = $mes[$mesActual];
                    $mesActual ++;
                    unset($totalRadicacion->$keyMesremove);
                    
                }
            }
        } */
       
        unset($totalRadicacion->Clasificacion);
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion =  array_sum($totalRadicacion);
        // calcular el total de giros
        $totalGiros = $arrayReferenceTerceraSeccion[0];
        $totalGiros = (array) $totalGiros;
        $totalGiros = array_sum($totalGiros);
        // porcentaje giros
        $porcentajeGiros = 0;
        if ($totalCorriente != 0) {
            $porcentajeGiros =  ($totalGiros / $totalCorriente) * 100;
        }

        $objeto = (object) [
            'radicacion' => $totalRadicacion,
            'prestacion' => $totalCorriente,
            'giros' => $totalGiros,
            'porcentaje_giros' => $porcentajeGiros
        ];

        $result[] = $objeto;
        
        return $result;
    }

    public function totalIntervencionMes($segundaSeccion, $terceraSeccion)
    {

        $arrayReferenceSegundaSeccion =  unserialize(serialize($segundaSeccion));
        $arrayReferenceTerceraSeccion =  unserialize(serialize($terceraSeccion));
        // se remueven los meses de enero a mar total corriente y radicacion
        foreach ($arrayReferenceSegundaSeccion as $key => $value) {
            unset($value->Clasificacion);
            unset($value->ENE);
            unset($value->FEB);
            unset($value->MAR);
        }
        $totalCorriente = $this->eliminarMesAnteriorYPosteriores($arrayReferenceSegundaSeccion[0]);
        $totalRadicacion = $this->eliminarMesAnteriorYPosteriores($arrayReferenceSegundaSeccion[1]);
        
        // sumatoria total de datos total corriente
        $totalCorriente = (array) $totalCorriente;
        $totalCorriente =  array_sum($totalCorriente);
        // sumatoria total de datos total radicacion
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion =  array_sum($totalRadicacion);
        // se remueven los meses de enero a mar giros

        foreach ($arrayReferenceTerceraSeccion as $key => $value) {
            unset($value->ENE);
            unset($value->FEB);
            unset($value->MAR);
        }

        // calcular el total de giros
        $totalGiros = $arrayReferenceTerceraSeccion[0];
        $totalGiros = (array) $totalGiros;
        $totalGiros = array_sum($totalGiros);
        // porcentaje giros
        $porcentajeGiros=0;
        if ($totalCorriente !=0) {
            $porcentajeGiros =  ($totalGiros / $totalCorriente) * 100;
        }


        $objeto = (object) [
            'radicacion' => $totalRadicacion,
            'prestacion' => $totalCorriente,
            'giros' => $totalGiros,
            'porcentaje_giros' => $porcentajeGiros
        ];

        $result[] = $objeto;


        return $result;
    }

    public function girosCorrienteRadicadoPorcentaje($segundaSeccion, $terceraSeccion)
    {
        // se busca calcular el porcentaje corriente y radicado respecto al giro
        $arrayReferenceSegundaSeccion =  unserialize(serialize($segundaSeccion));
        $arrayReferenceTerceraSeccion =  unserialize(serialize($terceraSeccion));
        $object = [];
        foreach ($arrayReferenceSegundaSeccion as $key => $value) {
            if ($value->Clasificacion == 'TOTAL CORRIENTE') {
                foreach ($arrayReferenceTerceraSeccion as $keyTercera => $valueSeccionTercera) {
                    // $val = get_object_vars($valueSeccionTercera);

                    $object[] = (object)[
                        'Clasificacion' => 'Porcentaje corriente',
                        "ENE" => $this->validarDivision($valueSeccionTercera->ENE, $value->ENE),
                        "FEB" => $this->validarDivision($valueSeccionTercera->FEB, $value->FEB),
                        "MAR" => $this->validarDivision($valueSeccionTercera->MAR, $value->MAR),
                        "ABR" => $this->validarDivision($valueSeccionTercera->ABR, $value->ABR),
                        "MAY" => $this->validarDivision($valueSeccionTercera->MAY, $value->MAY),
                        "JUN" => $this->validarDivision($valueSeccionTercera->JUN, $value->JUN),
                        "JUL" => $this->validarDivision($valueSeccionTercera->JUL, $value->JUL),
                        "AGO" => $this->validarDivision($valueSeccionTercera->AGO, $value->AGO),
                        "SEP" => $this->validarDivision($valueSeccionTercera->SEP, $value->SEP),
                        "OCT" => $this->validarDivision($valueSeccionTercera->OCT, $value->OCT),
                        "NOV" => $this->validarDivision($valueSeccionTercera->NOV, $value->NOV),
                        "DIC" => $this->validarDivision($valueSeccionTercera->DIC, $value->DIC),
                    ];
                }
            }

            if ($value->Clasificacion == 'TOTAL RADICACION') {
                foreach ($terceraSeccion as $keyTercera => $valueSeccionTercera) {
                    // $val = get_object_vars($valueSeccionTercera);

                    $object[] = (object)[
                        'Clasificacion' => 'Porcentaje radicacion',
                        "ENE" => $this->validarDivision($valueSeccionTercera->ENE, $value->ENE),
                        "FEB" => $this->validarDivision($valueSeccionTercera->FEB, $value->FEB),
                        "MAR" => $this->validarDivision($valueSeccionTercera->MAR, $value->MAR),
                        "ABR" => $this->validarDivision($valueSeccionTercera->ABR, $value->ABR),
                        "MAY" => $this->validarDivision($valueSeccionTercera->MAY, $value->MAY),
                        "JUN" => $this->validarDivision($valueSeccionTercera->JUN, $value->JUN),
                        "JUL" => $this->validarDivision($valueSeccionTercera->JUL, $value->JUL),
                        "AGO" => $this->validarDivision($valueSeccionTercera->AGO, $value->AGO),
                        "SEP" => $this->validarDivision($valueSeccionTercera->SEP, $value->SEP),
                        "OCT" => $this->validarDivision($valueSeccionTercera->OCT, $value->OCT),
                        "NOV" => $this->validarDivision($valueSeccionTercera->NOV, $value->NOV),
                        "DIC" => $this->validarDivision($valueSeccionTercera->DIC, $value->DIC),
                    ];
                }
            }
        }
        // $terceraSeccion[0]->Clasificacion = 'Giros';
        // $object[] = $terceraSeccion[0];

        return $object;
    }

    public function combinarSeccionConTercerSeccion($segundaSeccion,$terceraSeccion)
    {
        $arrayReferenceSegundaSeccion =  unserialize(serialize($segundaSeccion));
        $arrayReferenceTerceraSeccion =  unserialize(serialize($terceraSeccion));
        $arrayReferenceTerceraSeccion = $arrayReferenceTerceraSeccion[0];
        $arrayReferenceTerceraSeccion->Clasificacion = 'Giros';
     
        $arrayReferenceSegundaSeccion[2] = $arrayReferenceTerceraSeccion;
        return $arrayReferenceSegundaSeccion;
    }

    public function validarDivision($value1, $value2)
    {
        if ($value1 > 0 && $value2 > 0) {
            return (($value1 / $value2) * 100);
        } else {
            return 0.0;
        }
    }

    public function eliminarMesAnteriorYPosteriores($data)
    {
        $mesActual = date('m');
        $mes = [
            "01" => "ENE",
            "02" => "FEB",
            "03" => "MAR",
            "04" => "ABR",
            "05" => "MAY",
            "06" => "JUN",
            "07" => "JUL",
            "08" => "AGO",
            "09" => "SEP",
            "10" => "OCT",
            "11" => "NOV",
            "12" => "DIC",
        ];

        if ($mesActual != '01') {
           

            foreach ($data as $keyT => $valueT) {
                # code...
                $mesActual = (int) $mesActual;
                if ((int) $mesActual <= 12) {
                    $mesActual = ($mesActual <=9) ? "0$mesActual":$mesActual;
                    $keyMesremove = $mes[$mesActual];
                    
                    $mesActual ++;
                   
                    unset($data->$keyMesremove);
                    
                }
            }
         
            return $data;
        }else{
            return $data;
        }

    }
}
