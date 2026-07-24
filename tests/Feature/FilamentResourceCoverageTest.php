<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Resources\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentResourceCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<class-string<Resource>>
     */
    private function resources(): array
    {
        return [
            \App\Filament\Resources\BannersInicio\BannerInicioResource::class,
            \App\Filament\Resources\Contactos\ContactoResource::class,
            \App\Filament\Resources\Eventos\EventoResource::class,
            \App\Filament\Resources\GaleriaImagenes\GaleriaImagenResource::class,
            \App\Filament\Resources\HitoHistorias\HitoHistoriaResource::class,
            \App\Filament\Resources\ListaUtiles\ListaUtilResource::class,
            \App\Filament\Resources\NivelContenidos\NivelContenidoResource::class,
            \App\Filament\Resources\PaginaContenidos\PaginaContenidoResource::class,
            \App\Filament\Resources\Paginas\ContactoPaginaResource::class,
            \App\Filament\Resources\Paginas\InicioResource::class,
            \App\Filament\Resources\Paginas\NosotrosResource::class,
            \App\Filament\Resources\Paginas\OfertaAcademicaResource::class,
            \App\Filament\Resources\Paginas\ProtagonistasResource::class,
            \App\Filament\Resources\SeccionImagenes\SeccionImagenResource::class,
            \App\Filament\Resources\TestimonioVideos\TestimonioVideoResource::class,
            \App\Filament\Resources\Usuarios\UsuarioResource::class,
            \App\Filament\Resources\VideosPromocionales\VideoPromocionalResource::class,
        ];
    }

    public function test_primary_super_admin_can_render_every_resource_index_and_create_page(): void
    {
        $admin = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $this->actingAs($admin);

        foreach ($this->resources() as $resource) {
            $pages = $resource::getPages();

            foreach (['index', 'create'] as $page) {
                if (array_key_exists($page, $pages)) {
                    $this->get($resource::getUrl($page))
                        ->assertOk();
                }
            }
        }
    }
}
