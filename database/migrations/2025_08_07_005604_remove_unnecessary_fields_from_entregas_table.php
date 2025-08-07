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
            // Eliminar clave foránea si existe
            try {
                $table->dropForeign(['concepto_id']);
            } catch (\Exception $e) {
                // La clave foránea no existe, continuar
            }
            
            // Eliminar índice si existe
            try {
                $table->dropIndex(['estado', 'fecha_limite']);
            } catch (\Exception $e) {
                // El índice no existe, continuar
            }
            
            // Eliminar columnas innecesarias
            $table->dropColumn([
                'concepto_id',
                'estado', 
                'instrucciones',
                'archivo_requerido',
                'tamaño_maximo'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entregas', function (Blueprint $table) {
            // Restaurar columnas eliminadas
            $table->unsignedBigInteger('concepto_id')->nullable()->after('descripcion');
            $table->enum('estado', ['pendiente', 'activo', 'entregado', 'revisado', 'cerrado'])->default('pendiente')->after('fecha_limite');
            $table->text('instrucciones')->nullable()->after('estado');
            $table->enum('archivo_requerido', ['pdf', 'word', 'zip', 'codigo', 'imagen', 'otro'])->nullable()->after('instrucciones');
            $table->integer('tamaño_maximo')->default(10)->after('archivo_requerido');
            
            // Restaurar índices y claves foráneas
            $table->foreign('concepto_id')->references('id')->on('conceptos')->onDelete('set null');
            $table->index(['estado', 'fecha_limite']);
        });
    }
};
