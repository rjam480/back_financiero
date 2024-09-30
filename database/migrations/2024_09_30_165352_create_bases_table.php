<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('bases', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 11);
            $table->string('razon_social', 100);
            $table->string('tipo_prestador', 15);
            $table->string('zonal', 20);
            $table->string('grupo', 15);
            $table->string('habitacion', 2);
            $table->string('red', 25);
            $table->double('cxp', 16,2)->default(0);
            $table->double('en_proceso', 16,2)->default(0);
            $table->double('devolucion', 16,2)->default(0);
            $table->double('glosas', 16,2)->default(0);
            $table->double('radicacion_mensual', 16,2)->default(0);
            $table->string('bloqueo', 50);
            $table->double('pago_gd_subsidiado', 16,2)->default(0);
            $table->double('gd_contr_i', 16,2)->default(0);
            $table->double('gd_contr_ii', 16,2)->default(0);
            $table->double('gd_contr_iii', 16,2)->default(0);
            $table->double('gd_contr_iv', 16,2)->default(0);
            $table->double('otros_giros', 16,2)->default(0);
            $table->double('pago_por_tesoreria', 16,2)->default(0);
            $table->double('total_giros', 16,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};
