<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancieroController extends Controller
{
    //

    public function consultaFinanciero(Request $request,$nit)
    {
       
        
        $dataResponse =[
            'cabecera'=> $$this->agregarValoresaCabecera($this->cabecera($nit),$this->segundaSeccion($nit),$this->terceraSeccion($nit)),
            'primer_seccion'=>$this->primerSeccion($nit),
            'segunda_seccion'=>$this->segundaSeccion($nit),
            'tercer_seccion'=>$this->terceraSeccion($nit),
        ];

        return response()->json($dataResponse,200);
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
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0 END) AS DIC
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
        //Query Sección - Total Corriente / Total Radicación
        $result = \DB::select("SELECT 
                    CASE 
                        WHEN clasificacion IN ('vigencia actual', 'No identificado') THEN 'TOTAL CORRIENTE'
                    END AS Clasificacion, 
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0 END) AS DIC
                FROM 
                    radicaciones
                WHERE 
                    nit = '$nit' 
                    AND anio_radicacion = 2024
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
                    SUM(CASE WHEN mes_radicacion = 1 THEN valor ELSE 0 END) AS ENE,
                    SUM(CASE WHEN mes_radicacion = 2 THEN valor ELSE 0 END) AS FEB,
                    SUM(CASE WHEN mes_radicacion = 3 THEN valor ELSE 0 END) AS MAR,
                    SUM(CASE WHEN mes_radicacion = 4 THEN valor ELSE 0 END) AS ABR,
                    SUM(CASE WHEN mes_radicacion = 5 THEN valor ELSE 0 END) AS MAY,
                    SUM(CASE WHEN mes_radicacion = 6 THEN valor ELSE 0 END) AS JUN,
                    SUM(CASE WHEN mes_radicacion = 7 THEN valor ELSE 0 END) AS JUL,
                    SUM(CASE WHEN mes_radicacion = 8 THEN valor ELSE 0 END) AS AGO,
                    SUM(CASE WHEN mes_radicacion = 9 THEN valor ELSE 0 END) AS SEP,
                    SUM(CASE WHEN mes_radicacion = 10 THEN valor ELSE 0 END) AS OCT,
                    SUM(CASE WHEN mes_radicacion = 11 THEN valor ELSE 0 END) AS NOV,
                    SUM(CASE WHEN mes_radicacion = 12 THEN valor ELSE 0 END) AS DIC
                FROM 
                    radicaciones
                WHERE 
                    nit = '$nit' 
                    AND anio_radicacion = 2024
                    AND mes_radicacion <= MONTH(CURDATE()) - 1
                    AND clasificacion IN ('vigencia actual', 'Anterior 2024', 'No identificado')
                GROUP BY 
                    'TOTAL RADICACION'
                HAVING 
                    SUM(CASE WHEN mes_radicacion <= MONTH(CURDATE()) - 1 THEN valor ELSE 0 END) > 0");
        return $result;
    }

    public function terceraSeccion($nit)
    {
        // Query Sección Giros
        $result = \DB::select("SELECT 
                    SUM(CASE WHEN mes = 'enero' THEN valor_giro ELSE 0 END) AS 'ENE',
                    SUM(CASE WHEN mes = 'febrero' THEN valor_giro ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN mes = 'marzo' THEN valor_giro ELSE 0 END) AS 'MAR',
                    SUM(CASE WHEN mes = 'abril' THEN valor_giro ELSE 0 END) AS 'ABR',
                    SUM(CASE WHEN mes = 'mayo' THEN valor_giro ELSE 0 END) AS 'MAY',
                    SUM(CASE WHEN mes = 'junio' THEN valor_giro ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN mes = 'julio' THEN valor_giro ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN mes = 'agosto' THEN valor_giro ELSE 0 END) AS 'AGO',
                    SUM(CASE WHEN mes = 'septiembre' THEN valor_giro ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN mes = 'octubre' THEN valor_giro ELSE 0 END) AS 'OCT',
                    SUM(CASE WHEN mes = 'noviembre' THEN valor_giro ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN mes = 'diciembre' THEN valor_giro ELSE 0 END) AS 'DIC' 
                    FROM giros
                WHERE nit = '$nit'");

        return $result;
    }


    public function agregarValoresaCabecera($cabecera,$segundaSeccion,$terceraSeccion)
    {
        $mesActual = date('m');
        $mes = [
            "01"=>"ENE",
            "02"=>"FEB",
            "03"=>"MAR",
            "04"=>"ABR",
            "05"=>"MAY",
            "06"=>"JUN",
            "07"=>"JUL",
            "08"=>"AGO",
            "09"=>"SEP",
            "10"=>"OCT",
            "11"=>"NOV",
            "12"=>"DIC",
        ];
        // calculamos el total de radicacion
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
}
