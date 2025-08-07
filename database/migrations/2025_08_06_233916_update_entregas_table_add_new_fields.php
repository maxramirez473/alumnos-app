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
        Schema::table('entregas', function (Blueprint $table) {
            // Renombrar columna existente
            $table->renameColumn('nombre', 'titulo');
            
            // Agregar nuevas columnas
            $table->unsignedBigInteger('concepto_id')->nullable()->after('descripcion');
            $table->timestamp('fecha_limite')->after('concepto_id');
            $table->enum('estado', ['pendiente', 'activo', 'entregado', 'revisado', 'cerrado'])->default('pendiente')->after('fecha_limite');
            $table->text('instrucciones')->nullable()->after('estado');
            $table->enum('archivo_requerido', ['pdf', 'word', 'zip', 'codigo', 'imagen', 'otro'])->nullable()->after('instrucciones');
            $table->integer('tamaño_maximo')->default(10)->after('archivo_requerido');
            
            // Agregar índices y claves foráneas
            $table->foreign('concepto_id')->references('id')->on('conceptos')->onDelete('set null');
            $table->index(['estado', 'fecha_limite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entregas', function (Blueprint $table) {
            // Eliminar clave foránea
            $table->dropForeign(['concepto_id']);
            $table->dropIndex(['estado', 'fecha_limite']);
            
            // Eliminar columnas nuevas
            $table->dropColumn([
                'concepto_id',
                'fecha_limite',
                'estado',
                'instrucciones',
                'archivo_requerido',
                'tamaño_maximo'
            ]);
            
            // Renombrar de vuelta
            $table->renameColumn('titulo', 'nombre');
        });
    }
};
