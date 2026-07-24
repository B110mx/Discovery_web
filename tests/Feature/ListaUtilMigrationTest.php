<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ListaUtilMigrationTest extends TestCase
{
    public function test_existing_supply_list_import_is_skipped_when_media_root_is_missing(): void
    {
        $mediaRoot = base_path('videosyfotos');

        File::shouldReceive('isDirectory')
            ->once()
            ->with($mediaRoot)
            ->andReturnFalse();
        File::shouldNotReceive('directories');

        $migration = require database_path('migrations/2026_05_28_000002_seed_existing_lista_utiles.php');

        $migration->up();

        $this->assertTrue(true);
    }
}
