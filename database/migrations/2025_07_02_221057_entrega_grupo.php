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
        Schema::create('entrega_grupo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('entrega_id');
            
            // Claves foráneas con restricciones de integridad
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->foreign('entrega_id')->references('id')->on('entregas')->onDelete('cascade');
            
            // Índice único para evitar duplicados
            $table->unique(['grupo_id', 'entrega_id']);
            
            // Campos adicionales útiles para una tabla pivote
            $table->datetime('fecha')->nullable();

            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'retrasada'])->default('pendiente');
            $table->text('observaciones')->nullable()->comment('Observaciones específicas para este grupo');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_grupo');
    }
};
