<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giros extends Model
{
    use HasFactory;
    protected $table = 'giros';
    protected $fillable = [
        'mecanismo',
        'fecha_pagos_ips',
        'nit',
        'razon_social',
        'valor_giro',
        'regimen',
        'modalidad',
        'mes',
        'mes_numero',
        'agrupador',
    ];

    public function obtenerGiros($nit)
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

    public function girosModalidad($nit)
    {
        $result = \DB::select("SELECT 
            (
                CASE 
                    WHEN trim(modalidad) = 'CÁPITA' THEN 'CÁPITA'
                    WHEN trim(modalidad)='CÁPITA CERT' THEN 'CÁPITA CERT'
					WHEN trim(modalidad)='CÁPITA CERTIFICADA' THEN 'CÁPITA CERTIFICADA'
                    WHEN trim(modalidad)='CÁPITA MOVILIDAD ' THEN ' CÁPITA MOVILIDAD '
                    WHEN trim(modalidad)='CÁPITA MOVILIDAD CERTIFICADA ' THEN ' CÁPITA MOVILIDAD CERTIFICADA '
                    WHEN trim(modalidad)='EVENTO ' THEN ' EVENTO '
                    WHEN trim(modalidad)='EVENTO MOVILIDAD ' THEN ' EVENTO MOVILIDAD '
                    WHEN trim(modalidad)='MEDICAMENTOS IMPORTADOS ' THEN ' MEDICAMENTOS IMPORTADOS '
                    WHEN trim(modalidad)='PAC' THEN 'PAC'
                    WHEN trim(modalidad)='PAF' THEN 'PAF'
                    WHEN trim(modalidad)='PAF MOVILIDAD' THEN 'PAF MOVILIDAD '
                    WHEN trim(modalidad)='PGP' THEN 'PGP'
                    WHEN trim(modalidad)='PGP MOVILIDAD' THEN 'PGP MOVILIDAD'
                    WHEN trim(modalidad)='PRESUPUESTO MAXIMO' THEN 'PRESUPUESTO MAXIMO'
                    WHEN trim(modalidad)='TRASLADO PACIENTES' THEN 'TRASLADO PACIENTES'
                      
                END
            ) AS descripcion,
            SUM(CASE WHEN mes_numero = 1 THEN valor_giro ELSE 0.00 END) AS ENE,
            SUM(CASE WHEN mes_numero = 2 THEN valor_giro ELSE 0.00 END) AS FEB,
            SUM(CASE WHEN mes_numero = 3 THEN valor_giro ELSE 0.00 END) AS MAR,
            SUM(CASE WHEN mes_numero = 4 THEN valor_giro ELSE 0.00 END) AS ABR,
            SUM(CASE WHEN mes_numero = 5 THEN valor_giro ELSE 0.00 END) AS MAY,
            SUM(CASE WHEN mes_numero = 6 THEN valor_giro ELSE 0.00 END) AS JUN,
            SUM(CASE WHEN mes_numero = 7 THEN valor_giro ELSE 0.00 END) AS JUL,
            SUM(CASE WHEN mes_numero = 8 THEN valor_giro ELSE 0.00 END) AS AGO,
            SUM(CASE WHEN mes_numero = 9 THEN valor_giro ELSE 0.00 END) AS SEP,
            SUM(CASE WHEN mes_numero = 10 THEN valor_giro ELSE 0.00 END) AS OCT,
            SUM(CASE WHEN mes_numero = 11 THEN valor_giro ELSE 0.00 END) AS NOV,
            SUM(CASE WHEN mes_numero = 12 THEN valor_giro ELSE 0.00 END) AS DIC
            FROM financiero.giros
            where nit ='$nit'
            group by
            (
                CASE 
                    WHEN trim(modalidad) = 'CÁPITA' THEN 'CÁPITA'
                    WHEN trim(modalidad)='CÁPITA CERT' THEN 'CÁPITA CERT'
					WHEN trim(modalidad)='CÁPITA CERTIFICADA' THEN 'CÁPITA CERTIFICADA'
                    WHEN trim(modalidad)='CÁPITA MOVILIDAD ' THEN ' CÁPITA MOVILIDAD '
                    WHEN trim(modalidad)='CÁPITA MOVILIDAD CERTIFICADA ' THEN ' CÁPITA MOVILIDAD CERTIFICADA '
                    WHEN trim(modalidad)='EVENTO ' THEN ' EVENTO '
                    WHEN trim(modalidad)='EVENTO MOVILIDAD ' THEN ' EVENTO MOVILIDAD '
                    WHEN trim(modalidad)='MEDICAMENTOS IMPORTADOS ' THEN ' MEDICAMENTOS IMPORTADOS '
                    WHEN trim(modalidad)='PAC' THEN 'PAC'
                    WHEN trim(modalidad)='PAF' THEN 'PAF'
                    WHEN trim(modalidad)='PAF MOVILIDAD' THEN 'PAF MOVILIDAD '
                    WHEN trim(modalidad)='PGP' THEN 'PGP'
                    WHEN trim(modalidad)='PGP MOVILIDAD' THEN 'PGP MOVILIDAD'
                    WHEN trim(modalidad)='PRESUPUESTO MAXIMO' THEN 'PRESUPUESTO MAXIMO'
                    WHEN trim(modalidad)='TRASLADO PACIENTES' THEN 'TRASLADO PACIENTES'
                      
                END
            )
            having
				(ENE > 0 OR  FEB > 0 OR MAR > 0 OR MAY  > 0 OR JUN  > 0 OR JUL  > 0 OR AGO  > 0 OR SEP  > 0 OR OCT  > 0 OR NOV  > 0 OR DIC  > 0 )
            ");

        return $result;
    }
}
