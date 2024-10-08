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
}
