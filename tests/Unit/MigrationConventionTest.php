<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MigrationConventionTest extends TestCase
{
    #[Test]
    public function new_structural_migrations_do_not_seed_or_read_application_data(): void
    {
        $migrationFiles = glob(dirname(__DIR__, 2).'/database/migrations/2026_*.php');

        foreach ($migrationFiles as $file) {
            $filename = basename($file);

            if ($filename < '2026_06_15_000001' || str_contains($filename, '_data_')) {
                continue;
            }

            $contents = file_get_contents($file);

            foreach (['DB::', 'File::', 'Storage::', "database_path('data", 'database_path("data'] as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $contents,
                    "{$filename} mezcla estructura con datos. Muévelo a un seeder o nombra explícitamente la migración con _data_.",
                );
            }
        }
    }
}
