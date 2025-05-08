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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id(); // clave primaria autoincremental normal
            $table->string('numero_orden')->unique()->nullable();
            $table->timestamp('fecha_entrada')->nullable();
            $table->timestamp('fecha_salida')->nullable();
            $table->string('cliente')->nullable();
            $table->string('telefono')->nullable();
            $table->string('matricula')->nullable();
            $table->string('vehiculo')->nullable();
            $table->integer('kilometros')->nullable();
            $table->string('tipo_intervencion')->nullable();
            $table->string('numero_factura')->nullable();
            $table->string('numero_presupuesto')->nullable();
            $table->string('numero_resguardo')->nullable();
            $table->string('numero_albaran')->nullable();
            $table->string('situacion_vehiculo')->nullable();
            $table->date('proxima_itv')->nullable();
            $table->string('numero_bastidor')->nullable();
            $table->text('descripcion_revision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
