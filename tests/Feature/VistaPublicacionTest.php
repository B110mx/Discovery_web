<?php

namespace Tests\Feature;

use App\Filament\Widgets\VistaPublicacionWidget;
use App\Models\User;
use App\Models\VistaPublicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VistaPublicacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_views_are_published_by_default(): void
    {
        $this->assertSame(
            count(config('publicacion.vistas')),
            VistaPublicacion::query()->where('publicada', true)->count(),
        );

        $this->get('/oferta-academica/pop-del-ib')->assertOk();
    }

    public function test_guest_sees_maintenance_page_when_view_is_disabled(): void
    {
        VistaPublicacion::query()
            ->where('clave', 'pop-del-ib')
            ->firstOrFail()
            ->update(['publicada' => false]);

        $this->get('/oferta-academica/pop-del-ib')
            ->assertStatus(503)
            ->assertSee('POP del IB')
            ->assertSee('Estamos preparando esta sección');
    }

    public function test_admin_can_preview_a_disabled_view(): void
    {
        VistaPublicacion::query()
            ->where('clave', 'pop-del-ib')
            ->firstOrFail()
            ->update(['publicada' => false]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/oferta-academica/pop-del-ib')
            ->assertOk()
            ->assertSee('Vista administrativa')
            ->assertSee('no es visible para el público');
    }

    public function test_disabling_offer_page_does_not_block_its_children(): void
    {
        VistaPublicacion::query()
            ->where('clave', 'oferta-academica')
            ->firstOrFail()
            ->update(['publicada' => false]);

        $this->get('/oferta-academica')->assertStatus(503);
        $this->get('/oferta-academica/preescolar')->assertOk();
        $this->get('/oferta-academica/pop-del-ib')->assertOk();
        $this->get('/nosotros')->assertOk();
    }

    public function test_disabling_a_level_does_not_block_the_offer_page_or_other_levels(): void
    {
        VistaPublicacion::query()
            ->where('clave', 'preescolar')
            ->firstOrFail()
            ->update(['publicada' => false]);

        $this->get('/oferta-academica/preescolar')->assertStatus(503);
        $this->get('/oferta-academica')->assertOk();
        $this->get('/oferta-academica/primaria')->assertOk();
    }

    public function test_view_is_available_immediately_after_republishing(): void
    {
        $vista = VistaPublicacion::query()
            ->where('clave', 'nosotros')
            ->firstOrFail();

        $vista->update(['publicada' => false]);
        $this->get('/nosotros')->assertStatus(503);

        $vista->update(['publicada' => true]);
        $this->get('/nosotros')->assertOk();
    }

    public function test_dashboard_switch_tracks_the_admin_who_changed_the_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(VistaPublicacionWidget::class)
            ->call('alternar', 'nosotros')
            ->assertSee('En mantenimiento');

        $this->assertDatabaseHas('vistas_publicacion', [
            'clave' => 'nosotros',
            'publicada' => false,
            'actualizada_por' => $admin->id,
        ]);
    }
}
