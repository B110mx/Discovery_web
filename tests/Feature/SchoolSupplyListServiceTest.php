<?php

namespace Tests\Feature;

use App\Models\ListaUtil;
use App\Services\SchoolSupplyListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchoolSupplyListServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_lists_are_filtered_normalized_grouped_and_sorted(): void
    {
        ListaUtil::query()->delete();

        Storage::fake('public');
        Storage::disk('public')->put('listas/kinder.pdf', 'pdf');
        Storage::disk('public')->put('listas/high.pdf', 'pdf');

        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Bachillerato',
            'grado' => '10º grado',
            'titulo' => 'Lista 10º',
            'archivo_pdf' => 'listas/high.pdf',
            'orden' => 2,
            'activo' => true,
        ]);
        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Preescolar',
            'grado' => '3º grado',
            'titulo' => 'Lista 3º',
            'archivo_pdf' => 'listas/kinder.pdf',
            'orden' => 1,
            'activo' => true,
        ]);
        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Primaria',
            'grado' => '1º grado',
            'titulo' => 'Archivo inexistente',
            'archivo_pdf' => 'listas/missing.pdf',
            'orden' => 1,
            'activo' => true,
        ]);

        $lists = app(SchoolSupplyListService::class)->get();

        $this->assertSame(['Kindergarten', 'High School'], array_keys($lists));
        $this->assertSame('3° grado', $lists['Kindergarten'][0]['grado']);
        $this->assertSame('Lista 3°', $lists['Kindergarten'][0]['titulo']);
        $this->assertSame('2026-2027', $lists['High School'][0]['ciclo']);
    }

    public function test_media_fallback_detects_levels_and_orders_numeric_grades(): void
    {
        ListaUtil::query()->delete();

        Storage::fake('videosyfotos');
        $disk = Storage::disk('videosyfotos');
        $disk->put('Listas de útiles/7° secundaria.pdf', 'pdf');
        $disk->put('Listas de útiles/2º primaria.pdf', 'pdf');
        $disk->put('Listas de útiles/10° bachillerato.pdf', 'pdf');
        $disk->put('Listas de útiles/General.pdf', 'pdf');
        $disk->put('Listas de útiles/ignorar.txt', 'texto');

        $lists = app(SchoolSupplyListService::class)->get();

        $this->assertSame(
            ['Elementary', 'Middle School', 'High School', 'General'],
            array_keys($lists),
        );
        $this->assertSame('2° grado', $lists['Elementary'][0]['grado']);
        $this->assertSame('7° grado', $lists['Middle School'][0]['grado']);
        $this->assertSame('10° grado', $lists['High School'][0]['grado']);
        $this->assertSame('General', $lists['General'][0]['grado']);
    }

    public function test_english_media_fallback_uses_correct_ordinal_suffixes(): void
    {
        ListaUtil::query()->delete();

        app()->setLocale('en');
        Storage::fake('videosyfotos');
        $disk = Storage::disk('videosyfotos');

        foreach ([1, 2, 3, 4, 11, 12, 13, 21] as $grade) {
            $disk->put("Listas de útiles/{$grade}° lista.pdf", 'pdf');
        }

        $lists = app(SchoolSupplyListService::class)->get();
        $grades = collect($lists)->flatten(1)->pluck('grado')->all();

        $this->assertSame([
            '1st grade',
            '2nd grade',
            '3rd grade',
            '4th grade',
            '11th grade',
            '12th grade',
            '13th grade',
            '21st grade',
        ], $grades);
    }

    public function test_invalid_admin_files_fall_back_to_the_media_directory(): void
    {
        ListaUtil::query()->delete();

        Storage::fake('public');
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('Listas de útiles/1° respaldo.pdf', 'pdf');

        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Elementary',
            'grado' => '1° grado',
            'titulo' => 'Carga faltante',
            'archivo_pdf' => 'listas/missing.pdf',
            'orden' => 1,
            'activo' => true,
        ]);

        $lists = app(SchoolSupplyListService::class)->get();

        $this->assertStringStartsWith(
            '/media/Listas%20de%20%C3%BAtiles/1%C2%B0%20respaldo.pdf?v=',
            $lists['Elementary'][0]['url'],
        );
    }
}
