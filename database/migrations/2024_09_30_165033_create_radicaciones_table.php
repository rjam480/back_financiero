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
       
        Schema::create('radicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 11);
            $table->string('razon_social', 100);
            $table->string('anio_radicacion', 4);
            $table->string('mes_radicacion', 2);
            $table->string('anio_inicio_prestacion', 4);
            $table->string('mes_inicio_prestacion', 2);
            $table->string('tipo_factura_agrupado', 13);
            $table->string('estado_factura_agrupado', 13);
            $table->string('regimen_factura', 13);
            $table->double('valor', 16,2)->default(0);
            $table->string('clasificacion', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicaciones');
    }
};
