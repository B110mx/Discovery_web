<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->index(['activo', 'orden']);
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->index('estado');
            $table->index('created_at');
        });

        Schema::table('contactos', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('seccion_imagenes', function (Blueprint $table) {
            $table->index(['vista', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropIndex(['activo', 'orden']);
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('contactos', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('seccion_imagenes', function (Blueprint $table) {
            $table->dropIndex(['vista', 'activo', 'orden']);
        });
    }
};
