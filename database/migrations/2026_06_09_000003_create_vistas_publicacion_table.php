<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vistas_publicacion', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->boolean('publicada')->default(true);
            $table->foreignId('actualizada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();

        DB::table('vistas_publicacion')->insert(
            collect(array_keys(config('publicacion.vistas', [])))
                ->map(fn (string $clave) => [
                    'clave' => $clave,
                    'publicada' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->all(),
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('vistas_publicacion');
    }
};
