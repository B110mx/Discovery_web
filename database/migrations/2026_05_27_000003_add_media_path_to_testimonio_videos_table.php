<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonio_videos', function (Blueprint $table) {
            $table->string('video_media_path')->nullable()->after('video');
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('testimonio_videos', function (Blueprint $table) {
                $table->string('video')->nullable()->change();
            });
        }

        $directory = base_path('videosyfotos/Testimonios Alumni');

        if (! is_dir($directory)) {
            return;
        }

        $now = now();
        $extensions = ['mp4', 'mov', 'webm', 'm4v'];
        $files = collect(scandir($directory))
            ->filter(function (string $file) use ($directory, $extensions) {
                return is_file($directory . DIRECTORY_SEPARATOR . $file)
                    && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $extensions, true);
            })
            ->sort()
            ->values();

        foreach ($files as $index => $file) {
            $mediaPath = 'Testimonios Alumni/' . $file;

            DB::table('testimonio_videos')->updateOrInsert(
                ['video_media_path' => $mediaPath],
                [
                    'titulo' => pathinfo($file, PATHINFO_FILENAME),
                    'video' => $mediaPath,
                    'orden' => ($index + 1) * 10,
                    'activo' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('testimonio_videos')
            ->whereNotNull('video_media_path')
            ->delete();

        Schema::table('testimonio_videos', function (Blueprint $table) {
            $table->dropColumn('video_media_path');
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('testimonio_videos', function (Blueprint $table) {
                $table->string('video')->nullable(false)->change();
            });
        }
    }
};
