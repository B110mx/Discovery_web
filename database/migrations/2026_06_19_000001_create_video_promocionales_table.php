<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_promocionales', function (Blueprint $table) {
            $table->id();
            $table->string('nivel');
            $table->string('titulo');
            $table->string('video')->nullable();
            $table->string('video_media_path')->nullable();
            $table->string('portada')->nullable();
            $table->string('portada_media_path')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['nivel', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_promocionales');
    }
};
