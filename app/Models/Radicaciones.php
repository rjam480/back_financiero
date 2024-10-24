<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radicaciones extends Model
{
    use HasFactory;
    protected $table = 'radicaciones';

    protected $fillable = [
        'nit',
        'razon_social',
        'anio_radicacion',
        'mes_radicacion',
        'anio_inicio_prestacion',
        'mes_inicio_prestacion',
        'tipo_factura_agrupado',
        'estado_factura_agrupado',
        'regimen_factura',
        'valor',
        'clasificacion',
    ];

    public function obtenerRadicacionesPorEstadosCSA($nit)
    {
        $anio = date('Y');
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
                    AND anio_radicacion = $anio
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

    public function obtenerTotalCorrienteTotalRadicacion($nit)
    {
        //Query Sección - Total Corriente / Total Radicación
        $anioActual = date('Y');
      
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


    public function radicacionPorModalidadContrato($nit)
    {
        $anioActual = date('Y');

        $result = \DB::select("SELECT 
            (
                CASE 
                    WHEN tipo_factura_agrupado = 'EVENTO' THEN 'Evento'
                    WHEN tipo_factura_agrupado='PAGOS_GLOBALES' THEN 'Cápita y Pago Prospectivo'
                END
            ) AS titulo,
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
            FROM financiero.radicaciones
            where nit ='$nit'
            AND anio_radicacion = $anioActual
            group by (
                CASE 
                    WHEN tipo_factura_agrupado = 'EVENTO' THEN 'Evento'
                    WHEN tipo_factura_agrupado='PAGOS_GLOBALES' THEN 'Cápita y Pago Prospectivo'
                END
            )
        ");

        return $result;
    }

   
}
