<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('faltas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('empleado_id');
        $table->date('fecha');
        $table->timestamps();

        $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
        $table->unique(['empleado_id', 'fecha']); // para evitar duplicados por día
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faltas');
    }
};
