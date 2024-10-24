<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bases extends Model
{
    use HasFactory;
    protected $table = 'bases';
    protected $fillable = [
        'nit',
        'razon_social',
        'tipo_prestador',
        'zonal',
        'grupo',
        'habitacion',
        'red',
        'cxp',
        'en_proceso',
        'devolucion',
        'glosas',
        'radicacion_mensual',
        'bloqueo',
        'pago_gd_subsidiado',
        'gd_contr_i',
        'gd_contr_ii',
        'gd_contr_iii',
        'gd_contr_iv',
        'otros_giros',
        'pago_por_tesoreria',
        'total_giros',
    ];


    public function obtenerInfomarcionBaseCabecera($nit)
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

    public function pagosProveedor($nit)
    {
        $result = \DB::select("SELECT pago_gd_subsidiado,
        gd_contr_i,
        gd_contr_ii,
        gd_contr_iii,
        gd_contr_iv,
        otros_giros,
        pago_por_tesoreria,
        total_giros
        FROM bases WHERE nit ='$nit' ");

        return $result;
    }
}
