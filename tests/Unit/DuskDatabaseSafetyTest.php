<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\DuskDatabaseSafety;

class DuskDatabaseSafetyTest extends TestCase
{
    public function test_it_accepts_only_the_dedicated_sqlite_database(): void
    {
        DuskDatabaseSafety::assertSafe(
            'sqlite',
            'database/dusk.sqlite',
            'C:/project',
        );

        $this->addToAssertionCount(1);
    }

    #[DataProvider('unsafeDatabases')]
    public function test_it_rejects_an_unsafe_database(
        string $connection,
        string $database,
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Dusk fue bloqueado para proteger tus datos.');

        DuskDatabaseSafety::assertSafe($connection, $database, 'C:/project');
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function unsafeDatabases(): array
    {
        return [
            'mysql' => ['mysql', 'discovery_db'],
            'regular sqlite database' => ['sqlite', 'database/database.sqlite'],
            'in-memory sqlite database' => ['sqlite', ':memory:'],
            'lookalike path' => ['sqlite', 'database/archive/../database.sqlite'],
        ];
    }
}
