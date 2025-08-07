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
        Schema::table('entrega_grupo', function (Blueprint $table) {
            // Eliminar columnas obsoletas
            $table->dropColumn(['fecha', 'estado', 'observaciones']);
            
            // Agregar nuevas columnas para el sistema de entregas mejorado
            $table->timestamp('fecha_entrega')->nullable()->after('entrega_id');
            $table->decimal('calificacion', 4, 2)->nullable()->after('fecha_entrega');
            $table->text('comentarios')->nullable()->after('calificacion');
            $table->timestamp('fecha_calificacion')->nullable()->after('comentarios');
            
            // Agregar índices
            $table->index('fecha_entrega');
            $table->index('calificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrega_grupo', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['fecha_entrega']);
            $table->dropIndex(['calificacion']);
            
            // Eliminar columnas nuevas
            $table->dropColumn([
                'fecha_entrega',
                'calificacion',
                'comentarios',
                'fecha_calificacion'
            ]);
            
            // Restaurar columnas originales
            $table->datetime('fecha')->nullable();
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'retrasada'])->default('pendiente');
            $table->text('observaciones')->nullable()->comment('Observaciones específicas para este grupo');
        });
    }
};
