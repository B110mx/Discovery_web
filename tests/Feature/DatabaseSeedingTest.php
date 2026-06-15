<?php

namespace Tests\Feature;

use App\Models\NivelContenido;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\NivelContenidoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeedingTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_create_structure_without_optional_content(): void
    {
        $this->assertSame(0, NivelContenido::query()->count());
        $this->assertSame(0, User::query()->count());
    }

    public function test_required_content_seeder_is_idempotent(): void
    {
        $this->seed(NivelContenidoSeeder::class);
        NivelContenido::query()
            ->where('slug', 'preescolar')
            ->update(['titulo' => 'Kinder editado']);
        $this->seed(NivelContenidoSeeder::class);

        $this->assertSame(7, NivelContenido::query()->count());
        $this->assertSame(
            7,
            NivelContenido::query()->distinct()->count('slug'),
        );
        $this->assertSame(
            'Kinder editado',
            NivelContenido::query()->where('slug', 'preescolar')->value('titulo'),
        );
    }

    public function test_default_database_seeder_does_not_create_demo_users(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(7, NivelContenido::query()->count());
        $this->assertSame(0, User::query()->count());
    }
}
