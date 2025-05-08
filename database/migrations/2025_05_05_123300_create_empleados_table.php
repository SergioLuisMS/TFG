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
    Schema::create('empleados', function (Blueprint $table) {
        $table->id(); // Este es el Nº Empleado autoincremental
        $table->string('alias')->nullable();
        $table->string('nif')->nullable();
        $table->string('nombre');
        $table->string('primer_apellido')->nullable();
        $table->string('segundo_apellido')->nullable();
        $table->string('telefono')->nullable();
        $table->string('telefono_movil')->nullable();
        $table->string('direccion')->nullable();
        $table->string('codigo_postal')->nullable();
        $table->string('poblacion')->nullable();
        $table->string('provincia')->nullable();
        $table->unsignedTinyInteger('cumple_dia')->nullable();
        $table->unsignedTinyInteger('cumple_mes')->nullable();
        $table->string('email')->nullable();
        $table->boolean('bloqueado')->default(false);
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
