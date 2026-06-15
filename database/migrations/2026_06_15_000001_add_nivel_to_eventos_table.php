<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('nivel')->default('general')->after('fecha_evento');
            $table->index(['activo', 'fecha_evento']);
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropIndex(['activo', 'fecha_evento']);
            $table->dropColumn('nivel');
        });
    }
};
