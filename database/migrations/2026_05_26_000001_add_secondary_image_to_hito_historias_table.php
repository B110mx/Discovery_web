<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hito_historias', function (Blueprint $table) {
            $table->string('imagen_secundaria_url')->nullable()->after('imagen_url');
        });
    }

    public function down(): void
    {
        Schema::table('hito_historias', function (Blueprint $table) {
            $table->dropColumn('imagen_secundaria_url');
        });
    }
};
