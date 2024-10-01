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
        
        Schema::create('giros', function (Blueprint $table) {
            $table->id();
            $table->string('mecanismo',20);
            $table->date('fecha_pagos_ips');
            $table->string('nit',11);
            $table->string('razon_social',100);
            $table->double('valor_giro',16,2)->default(0);
            $table->string('regimen',14);
            $table->string('modalidad',40);
            $table->string('mes',10);
            $table->string('mes_numero',2);
            $table->string('agrupador',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giros');
    }
};
