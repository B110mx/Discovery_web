<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $directory = collect(File::directories(base_path('videosyfotos')))
            ->first(fn (string $path) => str_starts_with(File::basename($path), 'Listas'));

        if (! $directory) {
            return;
        }

        collect(File::files($directory))
            ->filter(fn (SplFileInfo $file) => strtolower($file->getExtension()) === 'pdf')
            ->each(function (SplFileInfo $file): void {
                $filename = $file->getFilename();
                $titulo = pathinfo($filename, PATHINFO_FILENAME);
                $gradoNumero = $this->gradoNumero($filename);
                $archivoPdf = 'listas-utiles/' . Str::slug($titulo) . '.pdf';

                Storage::disk('public')->put($archivoPdf, File::get($file->getPathname()));

                DB::table('lista_utiles')->updateOrInsert(
                    [
                        'ciclo_escolar' => $this->cicloEscolar($filename),
                        'titulo' => $titulo,
                    ],
                    [
                        'nivel' => $this->nivel($gradoNumero),
                        'grado' => $gradoNumero ? $gradoNumero . ' grado' : 'General',
                        'archivo_pdf' => $archivoPdf,
                        'orden' => $gradoNumero ?? 999,
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            });
    }

    public function down(): void
    {
        DB::table('lista_utiles')
            ->where('ciclo_escolar', '2025-2026')
            ->where('archivo_pdf', 'like', 'listas-utiles/%')
            ->delete();
    }

    private function gradoNumero(string $filename): ?int
    {
        preg_match('/\d{1,2}/', $filename, $matches);

        return isset($matches[0]) ? (int) $matches[0] : null;
    }

    private function cicloEscolar(string $filename): string
    {
        if (preg_match('/(\d{2})\s*-\s*(\d{2})/', $filename, $matches)) {
            return '20' . $matches[1] . '-20' . $matches[2];
        }

        return '2025-2026';
    }

    private function nivel(?int $grado): string
    {
        if ($grado === null) {
            return 'General';
        }

        if ($grado <= 6) {
            return 'Elementary';
        }

        if ($grado <= 9) {
            return 'Middle';
        }

        return 'High';
    }
};
