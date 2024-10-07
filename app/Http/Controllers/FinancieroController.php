<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancieroController extends Controller
{

    public function consultaFinanciero(Request $request, $nit)
    {
        $dataResponse = [
            'cabecera' => $this->agregarValoresaCabecera($this->cabecera($nit), $this->segundaSeccion($nit), $this->terceraSeccion($nit)),
            'primer_seccion' => $this->primerSeccion($nit),
            'segunda_seccion' => $this->combinarSeccionConTercerSeccion($this->segundaSeccion($nit), $this->terceraSeccion($nit)),
            'tercer_seccion' => $this->girosCorrienteRadicadoPorcentaje($this->segundaSeccion($nit), $this->terceraSeccion($nit)),
            'total_2024' => $this->totalAnioCorrienteRadicacion($this->segundaSeccion($nit), $this->terceraSeccion($nit)),
            'intervencion_mes' => $this->totalIntervencionMes($this->segundaSeccion($nit), $this->terceraSeccion($nit)),
        ];

        return response()->json($dataResponse, 200);
    }

    public function cabecera($nit)
    {
        // Query Sección  Encabezado Reporte  CXP - En Procesamiento - Devoluciones - Glosas -
        $result = \DB::select("SELECT cxp,en_proceso,devolucion,glosas,
            nit,
            razon_social,
            razon_social,
            zonal,
            red,
            habitacion,
            (en_proceso+devolucion+glosas+cxp) AS total_cartera
            FROM bases WHERE nit ='$nit' ");

        return $result;
    }

    public function primerSeccion($nit)
    {
        // Query Sección  Corriente/Sin Identificar/Anterior

        $result =  \DB::select("SELECT 
                    CASE 
                        WHEN clasificacion = 'vigencia actual' THEN 'CORRIENTE'
                        WHEN clasificacion = 'No identificado' THEN 'SIN IDENTIFICAR'
                        WHEN clasificacion = 'Anterior 2024' THEN 'ANTERIOR'
                        ELSE 'SIN CLASIFICAR'
                    END AS Clasificacion, 
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0.00 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0.00 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0.00 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0.00 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0.00 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0.00 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0.00 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0.00 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0.00 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0.00 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0.00 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0.00 END) AS DIC
                FROM 
                    radicaciones
                WHERE 
                    nit = '$nit' 
                    AND anio_radicacion = 2024
                    AND mes_radicacion <= MONTH(CURDATE()) - 1
                GROUP BY 
                    CASE 
                        WHEN clasificacion = 'vigencia actual' THEN 'CORRIENTE'
                        WHEN clasificacion = 'No identificado' THEN 'SIN IDENTIFICAR'
                        WHEN clasificacion = 'Anterior 2024' THEN 'ANTERIOR'
                        ELSE 'SIN CLASIFICAR'
                    END
                HAVING 
                    SUM(CASE WHEN mes_radicacion <= MONTH(CURDATE()) - 1 THEN valor ELSE 0 END) > 0
               ORDER BY
                CASE
                    WHEN clasificacion = 'vigencia actual' THEN 1
                    WHEN clasificacion = 'No identificado' THEN 2
                    WHEN clasificacion = 'Anterior 2024' THEN 3
                END");
        return $result;
    }

    public function segundaSeccion($nit)
    {
        $anioActual = date('Y');
        //Query Sección - Total Corriente / Total Radicación
        $result = \DB::select("SELECT 
                    CASE 
                        WHEN clasificacion IN ('vigencia actual', 'No identificado') THEN 'TOTAL CORRIENTE'
                    END AS Clasificacion, 
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0.00 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0.00 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0.00 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0.00 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0.00 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0.00 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0.00 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0.00 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0.00 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0.00 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0.00 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0.00 END) AS DIC
                FROM 
                    radicaciones
                WHERE 
                    nit = '$nit' 
                    AND anio_radicacion = '$anioActual'
                    AND mes_radicacion <= MONTH(CURDATE()) - 1
                    AND clasificacion IN ('vigencia actual', 'No identificado')
                GROUP BY 
                    CASE 
                        WHEN clasificacion IN ('vigencia actual', 'No identificado') THEN 'TOTAL CORRIENTE'
                    END
                HAVING 
                    SUM(CASE WHEN mes_radicacion <= MONTH(CURDATE()) - 1 THEN valor ELSE 0 END) > 0

                UNION ALL

                SELECT 
                    'TOTAL RADICACION' AS Clasificacion, 
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0.00 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0.00 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0.00 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0.00 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0.00 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0.00 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0.00 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0.00 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0.00 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0.00 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0.00 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0.00 END) AS DIC
                FROM 
                    radicaciones
                WHERE 
                    nit = '$nit' 
                    AND anio_radicacion = '$anioActual'
                    AND mes_radicacion <= MONTH(CURDATE()) - 1
                    AND clasificacion IN ('vigencia actual', 'Anterior 2024', 'No identificado')
                GROUP BY 
                    'TOTAL RADICACION'
                HAVING 
                    SUM(CASE WHEN mes_radicacion <= MONTH(CURDATE()) - 1 THEN valor ELSE 0 END) > 0");


        if (count($result) == 1) {
            $totalRadicacion  = $result[0];
            $totalCorriente = (object) [
                "Clasificacion" => "TOTAL CORRIENTE",
                "ENE" => 0.00,
                "FEB" => 0.00,
                "MAR" => 0.00,
                "ABR" => 0.00,
                "MAY" => 0.00,
                "JUN" => 0.00,
                "JUL" => 0.00,
                "AGO" => 0.00,
                "SEP" => 0.00,
                "OCT" => 0.00,
                "NOV" => 0.00,
                "DIC" => 0.00,
            ];

            $result[0] = $totalCorriente;
            $result[1] = $totalRadicacion;
        }

        if (count($result) == 0) {
            $totalRadicacion  = (object) [
                "Clasificacion" => "TOTAL RADICACION",
                "ENE" => 0.00,
                "FEB" => 0.00,
                "MAR" => 0.00,
                "ABR" => 0.00,
                "MAY" => 0.00,
                "JUN" => 0.00,
                "JUL" => 0.00,
                "AGO" => 0.00,
                "SEP" => 0.00,
                "OCT" => 0.00,
                "NOV" => 0.00,
                "DIC" => 0.00,
            ];

            $totalCorriente = (object) [
                "Clasificacion" => "TOTAL CORRIENTE",
                "ENE" => 0.00,
                "FEB" => 0.00,
                "MAR" => 0.00,
                "ABR" => 0.00,
                "MAY" => 0.00,
                "JUN" => 0.00,
                "JUL" => 0.00,
                "AGO" => 0.00,
                "SEP" => 0.00,
                "OCT" => 0.00,
                "NOV" => 0.00,
                "DIC" => 0.00,
            ];

            $result[0] = $totalCorriente;
            $result[1] = $totalRadicacion;
        }
        return $result;
    }

    public function terceraSeccion($nit)
    {
        // Query Sección Giros
        $result = \DB::select("SELECT 
                    IFNULL(SUM(CASE WHEN mes = 'enero' THEN valor_giro ELSE 0 END),0.00) AS 'ENE',
                    IFNULL(SUM(CASE WHEN mes = 'febrero' THEN valor_giro ELSE 0.00 END),0.00) AS 'FEB',
                    IFNULL(SUM(CASE WHEN mes = 'marzo' THEN valor_giro ELSE 0.00 END),0.00) AS 'MAR',
                    IFNULL(SUM(CASE WHEN mes = 'abril' THEN valor_giro ELSE 0.00 END),0.00) AS 'ABR',
                    IFNULL(SUM(CASE WHEN mes = 'mayo' THEN valor_giro ELSE 0.00 END),0.00) AS 'MAY',
                    IFNULL(SUM(CASE WHEN mes = 'junio' THEN valor_giro ELSE 0.00 END),0.00) AS 'JUN',
                    IFNULL(SUM(CASE WHEN mes = 'julio' THEN valor_giro ELSE 0.00 END),0.00) AS 'JUL',
                    IFNULL(SUM(CASE WHEN mes = 'agosto' THEN valor_giro ELSE 0.00 END),0.00) AS 'AGO',
                    IFNULL(SUM(CASE WHEN mes = 'septiembre' THEN valor_giro ELSE 0.00 END) ,0.00)AS 'SEP',
                    IFNULL(SUM(CASE WHEN mes = 'octubre' THEN valor_giro ELSE 0.00 END) ,0.00)AS 'OCT',
                    IFNULL(SUM(CASE WHEN mes = 'noviembre' THEN valor_giro ELSE 0.00 END),0.00) AS 'NOV',
                    IFNULL(SUM(CASE WHEN mes = 'diciembre' THEN valor_giro ELSE 0.00 END),0.00) AS 'DIC' 
                    FROM giros
                WHERE nit = '$nit'");

        return $result;
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


        // 
        $totalRadicacion = $segundaSeccion[1];
        unset($totalRadicacion->Clasificacion);
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion = array_sum($totalRadicacion);
        // tomamos el ultimo mes en curso
        $girosMesActual = (array) $terceraSeccion[0];
        $selecionMes = $mes[$mesActual];
        $girosMesActual = $girosMesActual[$selecionMes];
        $cabecera[0]->promedio_radicacion = $totalRadicacion;
        $cabecera[0]->giro_mes_actual = $girosMesActual;

        return $cabecera;
    }

    public function totalAnioCorrienteRadicacion($segundaSeccion, $terceraSeccion)
    {

        $totalCorriente = $segundaSeccion[0];
        $totalRadicacion = $segundaSeccion[1];
        // sumatoria total de datos total corriente
        unset($totalCorriente->Clasificacion);
        $totalCorriente = (array) $totalCorriente;
        $totalCorriente =  array_sum($totalCorriente);
        // sumatoria total de datos total radicacion
        unset($totalRadicacion->Clasificacion);
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion =  array_sum($totalRadicacion);
        // calcular el total de giros
        $totalGiros = $terceraSeccion[0];
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
        // se remueven los meses de enero a mar total corriente y radicacion
        foreach ($segundaSeccion as $key => $value) {
            unset($value->Clasificacion);
            unset($value->ENE);
            unset($value->FEB);
            unset($value->MAR);
        }
        $totalCorriente = $segundaSeccion[0];
        $totalRadicacion = $segundaSeccion[1];
        // sumatoria total de datos total corriente
        $totalCorriente = (array) $totalCorriente;
        $totalCorriente =  array_sum($totalCorriente);
        // sumatoria total de datos total radicacion
        $totalRadicacion = (array) $totalRadicacion;
        $totalRadicacion =  array_sum($totalRadicacion);
        // se remueven los meses de enero a mar giros

        foreach ($terceraSeccion as $key => $value) {
            unset($value->ENE);
            unset($value->FEB);
            unset($value->MAR);
        }

        // calcular el total de giros
        $totalGiros = $terceraSeccion[0];
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

        $object = [];
        foreach ($segundaSeccion as $key => $value) {
            if ($value->Clasificacion == 'TOTAL CORRIENTE') {
                foreach ($terceraSeccion as $keyTercera => $valueSeccionTercera) {
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
        $terceraSeccion[0]->Clasificacion = 'Giros';
        $object[] = $terceraSeccion[0];

        return $object;
    }

    public function combinarSeccionConTercerSeccion($segundaSeccion,$terceraSeccion)
    {
        $terceraSeccion = $terceraSeccion[0];
        $terceraSeccion->Clasificacion = 'Giros';
     
        $segundaSeccion[2] = $terceraSeccion;
        return $segundaSeccion;
    }

    public function validarDivision($value1, $value2)
    {
        if ($value1 > 0 && $value2 > 0) {
            return (($value1 / $value2) * 100);
        } else {
            return 0.0;
        }
    }
}
