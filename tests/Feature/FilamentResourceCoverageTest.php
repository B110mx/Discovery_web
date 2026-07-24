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
use App\Filament\Resources\Usuarios\Pages\CreateUsuario;
use App\Filament\Resources\Usuarios\UsuarioResource;
use App\Filament\Resources\VideosPromocionales\VideoPromocionalResource;
use App\Models\Contacto;
use App\Models\Evento;
use App\Models\HitoHistoria;
use App\Models\ListaUtil;
use App\Models\PaginaContenido;
use App\Models\TestimonioVideo;
use App\Models\User;
use App\Models\VideoPromocional;
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

    public function test_primary_super_admin_can_render_contact_and_history_view_and_edit_pages(): void
    {
        $admin = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $this->actingAs($admin);

        $contact = Contacto::query()->create([
            'nombre' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'mensaje' => 'Solicitud de informes',
        ]);
        $history = HitoHistoria::query()->create([
            'anio' => '2026',
            'titulo' => 'Nuevo hito',
            'texto' => 'Un momento importante para la comunidad.',
            'orden' => 1,
        ]);

        foreach ([
            [ContactoResource::class, $contact],
            [HitoHistoriaResource::class, $history],
        ] as [$resource, $record]) {
            $this->get($resource::getUrl('view', ['record' => $record]))
                ->assertOk()
                ->assertSee($record->titulo ?? $record->nombre);

            $this->get($resource::getUrl('edit', ['record' => $record]))
                ->assertOk();
        }
    }

    public function test_primary_super_admin_can_render_the_remaining_resource_edit_pages(): void
    {
        $admin = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $this->actingAs($admin);

        $records = [
            [EventoResource::class, Evento::query()->create([
                'titulo' => 'Evento editable',
                'descripcion' => 'Descripción',
                'imagen_url' => 'eventos/editable.jpg',
                'orden' => 1,
                'activo' => true,
            ])],
            [ListaUtilResource::class, ListaUtil::query()->create([
                'ciclo_escolar' => '2026-2027',
                'nivel' => 'Elementary',
                'grado' => '1°',
                'titulo' => 'Lista editable',
                'archivo_pdf' => 'listas/editable.pdf',
                'orden' => 1,
                'activo' => true,
            ])],
            [PaginaContenidoResource::class, PaginaContenido::query()->firstOrCreate(
                ['slug' => 'cobertura'],
                ['titulo' => 'Página editable'],
            )],
            [TestimonioVideoResource::class, TestimonioVideo::query()->create([
                'titulo' => 'Testimonio editable',
                'video' => 'testimonios/video.mp4',
                'video_media_path' => 'testimonios/video.mp4',
                'orden' => 1,
                'activo' => true,
            ])],
            [VideoPromocionalResource::class, VideoPromocional::query()->create([
                'nivel' => 'general',
                'titulo' => 'Video editable',
                'video_media_path' => 'videos/promocional.mp4',
                'orden' => 1,
                'activo' => true,
            ])],
            [UsuarioResource::class, User::factory()->create(['role' => 'admin'])],
        ];

        foreach ($records as [$resource, $record]) {
            $this->get($resource::getUrl('edit', ['record' => $record]))
                ->assertOk();
        }

        $secondarySuperAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->get(UsuarioResource::getUrl('edit', ['record' => $admin]))
            ->assertOk();
        $this->get(UsuarioResource::getUrl('edit', ['record' => $secondarySuperAdmin]))
            ->assertOk();
    }

    public function test_user_creation_forces_admin_role_unless_the_actor_is_primary(): void
    {
        $page = new class extends CreateUsuario
        {
            public function transform(array $data): array
            {
                return $this->mutateFormDataBeforeCreate($data);
            }
        };

        $primary = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $this->actingAs($primary);

        $this->assertSame(
            'super_admin',
            $page->transform(['role' => 'super_admin'])['role'],
        );

        $secondary = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($secondary);

        $this->assertSame(
            'admin',
            $page->transform(['role' => 'super_admin'])['role'],
        );
    }
}
