<?php

use App\Support\SiteCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('nivel_contenidos')
            ->where('slug', 'primaria')
            ->whereIn('oferta_edad', ['1 a 6 grado', '1° a 6° grado'])
            ->update(['oferta_edad' => '1° a 5° grado']);

        DB::table('nivel_contenidos')
            ->where('slug', 'secundaria')
            ->whereIn('oferta_edad', ['7 a 9 grado', '7° a 9° grado'])
            ->update(['oferta_edad' => '6° a 9° grado']);

        Cache::forget(SiteCache::key('niveles_contenido'));
    }

    public function down(): void
    {
        DB::table('nivel_contenidos')
            ->where('slug', 'primaria')
            ->where('oferta_edad', '1° a 5° grado')
            ->update(['oferta_edad' => '1° a 6° grado']);

        DB::table('nivel_contenidos')
            ->where('slug', 'secundaria')
            ->where('oferta_edad', '6° a 9° grado')
            ->update(['oferta_edad' => '7° a 9° grado']);

        Cache::forget(SiteCache::key('niveles_contenido'));
    }
};
