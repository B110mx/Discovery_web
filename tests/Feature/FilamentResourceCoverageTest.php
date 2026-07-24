<?php

namespace Tests\Feature;

use App\Filament\Resources\BannersInicio\BannerInicioResource;
use App\Filament\Resources\Contactos\ContactoResource;
use App\Filament\Resources\Eventos\EventoResource;
use App\Filament\Resources\GaleriaImagenes\GaleriaImagenResource;
use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use App\Filament\Resources\ListaUtiles\ListaUtilResource;
use App\Filament\Resources\NivelContenidos\NivelContenidoResource;
use App\Filament\Resources\PaginaContenidos\PaginaContenidoResource;
use App\Filament\Resources\Paginas\ContactoPaginaResource;
use App\Filament\Resources\Paginas\InicioResource;
use App\Filament\Resources\Paginas\NosotrosResource;
use App\Filament\Resources\Paginas\OfertaAcademicaResource;
use App\Filament\Resources\Paginas\ProtagonistasResource;
use App\Filament\Resources\SeccionImagenes\SeccionImagenResource;
use App\Filament\Resources\TestimonioVideos\TestimonioVideoResource;
use App\Filament\Resources\Usuarios\UsuarioResource;
use App\Filament\Resources\VideosPromocionales\VideoPromocionalResource;
use App\Models\User;
use Filament\Resources\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentResourceCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<class-string<resource>>
     */
    private function resources(): array
    {
        return [
            BannerInicioResource::class,
            ContactoResource::class,
            EventoResource::class,
            GaleriaImagenResource::class,
            HitoHistoriaResource::class,
            ListaUtilResource::class,
            NivelContenidoResource::class,
            PaginaContenidoResource::class,
            ContactoPaginaResource::class,
            InicioResource::class,
            NosotrosResource::class,
            OfertaAcademicaResource::class,
            ProtagonistasResource::class,
            SeccionImagenResource::class,
            TestimonioVideoResource::class,
            UsuarioResource::class,
            VideoPromocionalResource::class,
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
