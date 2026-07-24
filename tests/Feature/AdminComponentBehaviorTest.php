<?php

namespace Tests\Feature;

use App\Filament\Resources\BannersInicio\Pages\ListBannersInicio;
use App\Filament\Resources\Eventos\Pages\ListEventos;
use App\Filament\Resources\GaleriaImagenes\Pages\ListGaleriaImagenes;
use App\Filament\Resources\HitoHistorias\Pages\ListHitoHistorias;
use App\Filament\Resources\PaginaContenidos\Pages\CreatePaginaContenido;
use App\Filament\Resources\SeccionImagenes\Pages\ListSeccionImagenes;
use App\Filament\Resources\Usuarios\Pages\ListUsuarios;
use App\Filament\Resources\Usuarios\Tables\UsuariosTable;
use App\Models\BannerInicio;
use App\Models\Evento;
use App\Models\GaleriaImagen;
use App\Models\HitoHistoria;
use App\Models\SeccionImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminComponentBehaviorTest extends TestCase
{
    use RefreshDatabase;

    private function signInAsPrimaryAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);

        $this->actingAs($admin);

        return $admin;
    }

    public function test_banner_bulk_upload_creates_ordered_records(): void
    {
        $this->signInAsPrimaryAdmin();
        Storage::fake('public');

        BannerInicio::query()->create([
            'titulo' => 'Existente',
            'imagen' => 'banners-inicio/existente.jpg',
            'orden' => 4,
            'activo' => true,
        ]);

        Livewire::test(ListBannersInicio::class)
            ->callAction('subirVarios', [
                'imagenes' => [
                    UploadedFile::fake()->image('bienvenida.jpg'),
                    UploadedFile::fake()->image('comunidad.png'),
                ],
            ])
            ->assertHasNoActionErrors();

        $created = BannerInicio::query()->where('orden', '>', 4)->orderBy('orden')->get();

        $this->assertCount(2, $created);
        $this->assertSame([5, 6], $created->pluck('orden')->all());
        $this->assertSame(
            $created->pluck('imagen')->map(fn (string $path) => pathinfo($path, PATHINFO_FILENAME))->all(),
            $created->pluck('titulo')->all(),
        );
    }

    public function test_gallery_bulk_upload_uses_level_order_and_accessible_text(): void
    {
        $this->signInAsPrimaryAdmin();
        Storage::fake('public');

        GaleriaImagen::query()->create([
            'nivel' => 'primaria',
            'titulo' => 'Existente',
            'imagen' => 'galerias/existente.jpg',
            'orden' => 7,
            'activo' => true,
        ]);

        Livewire::test(ListGaleriaImagenes::class)
            ->callAction('subirVarias', [
                'nivel' => 'primaria',
                'imagenes' => [
                    UploadedFile::fake()->image('laboratorio.jpg'),
                    UploadedFile::fake()->image('deportes.webp'),
                ],
            ])
            ->assertHasNoActionErrors();

        $created = GaleriaImagen::query()
            ->where('nivel', 'primaria')
            ->where('orden', '>', 7)
            ->orderBy('orden')
            ->get();

        $this->assertCount(2, $created);
        $this->assertSame([8, 9], $created->pluck('orden')->all());
        $this->assertSame(
            ['Elementary en Discovery®', 'Elementary en Discovery®'],
            $created->pluck('texto_alternativo')->all(),
        );
    }

    public function test_event_table_resolves_public_media_missing_and_empty_previews(): void
    {
        $this->signInAsPrimaryAdmin();
        Storage::fake('public');
        Storage::fake('videosyfotos');
        config()->set('colegio.media.disk', 'videosyfotos');

        Storage::disk('public')->put('eventos/publico.jpg', 'image');
        Storage::disk('videosyfotos')->put('Eventos/Foto especial.jpg', 'image');

        $public = $this->createEvent('Público', 'preescolar', 'eventos/publico.jpg');
        $media = $this->createEvent(
            'Media',
            'primaria',
            'eventos/no-existe.jpg',
            'Eventos/Foto especial.jpg',
        );
        $missing = $this->createEvent(
            'Faltante',
            'secundaria',
            'eventos/no-existe.jpg',
            'Eventos/faltante.jpg',
        );
        $empty = $this->createEvent('Vacío', 'bachillerato', 'eventos/no-existe.jpg');

        Livewire::test(ListEventos::class)
            ->assertTableColumnStateSet(
                'preview',
                Storage::disk('public')->url('eventos/publico.jpg'),
                $public,
            )
            ->assertTableColumnStateSet(
                'preview',
                '/media/Eventos/Foto%20especial.jpg',
                $media,
            )
            ->assertTableColumnStateSet('preview', null, $missing)
            ->assertTableColumnStateSet('preview', null, $empty)
            ->assertTableColumnFormattedStateSet('nivel', 'Kindergarten', $public)
            ->assertTableColumnFormattedStateSet('nivel', 'Elementary', $media)
            ->assertTableColumnFormattedStateSet('nivel', 'Middle School', $missing)
            ->assertTableColumnFormattedStateSet('nivel', 'High School', $empty);
    }

    public function test_user_table_enforces_permissions_and_resets_a_password(): void
    {
        $primary = $this->signInAsPrimaryAdmin();
        $regular = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        Livewire::test(ListUsuarios::class)
            ->assertTableActionVisible('edit', $regular)
            ->assertTableActionVisible('resetPassword', $regular)
            ->assertTableActionVisible('edit', $superAdmin)
            ->assertTableActionVisible('resetPassword', $superAdmin)
            ->assertTableActionHidden('delete', $primary)
            ->callTableAction('resetPassword', $regular, [
                'password' => 'Nueva-clave-2026',
            ])
            ->assertHasNoActionErrors();

        $this->assertTrue(Hash::check('Nueva-clave-2026', $regular->fresh()->password));
    }

    public function test_dynamic_page_form_reacts_to_home_contact_and_standard_slugs(): void
    {
        $this->signInAsPrimaryAdmin();

        $component = Livewire::test(CreatePaginaContenido::class)
            ->fillForm(['slug' => 'contacto'])
            ->assertFormFieldVisible('direccion')
            ->assertFormFieldVisible('mapa_url')
            ->assertFormFieldVisible('imagen_secundaria');

        $component
            ->fillForm(['slug' => 'inicio'])
            ->assertFormFieldHidden('direccion')
            ->assertFormFieldHidden('mapa_url')
            ->assertFormFieldHidden('imagen_secundaria');

        $component
            ->fillForm(['slug' => 'nosotros'])
            ->assertFormFieldHidden('direccion')
            ->assertFormFieldVisible('imagen_secundaria');
    }

    public function test_regular_admin_cannot_manage_or_reset_users_from_the_table(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $other = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $canManage = new \ReflectionMethod(UsuariosTable::class, 'canManageRecord');
        $canReset = new \ReflectionMethod(UsuariosTable::class, 'canResetPassword');

        $this->assertFalse($canManage->invoke(null, $other));
        $this->assertFalse($canReset->invoke(null, $other));
    }

    public function test_history_table_resolves_each_image_source(): void
    {
        $this->signInAsPrimaryAdmin();
        Storage::fake('public');
        Storage::fake('videosyfotos');
        config()->set('colegio.media.disk', 'videosyfotos');

        Storage::disk('public')->put('historia/principal.jpg', 'image');
        Storage::disk('videosyfotos')->put('Historia/Foto secundaria.jpg', 'image');

        $history = HitoHistoria::query()->create([
            'anio' => '2026',
            'titulo' => 'Hito con imágenes',
            'texto' => 'Historia',
            'imagen_url' => 'historia/principal.jpg',
            'imagen_secundaria_url' => 'historia/no-existe.jpg',
            'imagen_secundaria_media_path' => 'Historia/Foto secundaria.jpg',
            'orden' => 1,
        ]);
        $empty = HitoHistoria::query()->create([
            'anio' => '2027',
            'titulo' => 'Hito sin imágenes',
            'texto' => 'Historia',
            'imagen_media_path' => 'Historia/no-existe.jpg',
            'orden' => 2,
        ]);

        Livewire::test(ListHitoHistorias::class)
            ->assertTableColumnStateSet(
                'imagen_principal_preview',
                Storage::disk('public')->url('historia/principal.jpg'),
                $history,
            )
            ->assertTableColumnStateSet(
                'imagen_secundaria_preview',
                '/media/Historia/Foto%20secundaria.jpg',
                $history,
            )
            ->assertTableColumnStateSet('imagen_principal_preview', null, $empty)
            ->assertTableColumnStateSet('imagen_secundaria_preview', null, $empty);
    }

    public function test_site_image_table_formats_the_remaining_view_names(): void
    {
        $this->signInAsPrimaryAdmin();

        $records = collect([
            'secundaria',
            'academias-vespertinas',
            'recursos-escolares',
        ])->map(fn (string $view) => SeccionImagen::query()->create([
            'vista' => $view,
            'clave' => 'portada',
            'titulo' => $view,
            'orden' => 1,
            'activo' => true,
        ]));

        Livewire::test(ListSeccionImagenes::class)
            ->assertTableColumnFormattedStateSet('vista', 'Nivel - Middle School', $records[0])
            ->assertTableColumnFormattedStateSet('vista', 'Academias Vespertinas', $records[1])
            ->assertTableColumnFormattedStateSet('vista', 'Recursos escolares', $records[2]);
    }

    private function createEvent(
        string $title,
        string $level,
        string $image,
        ?string $mediaPath = null,
    ): Evento {
        return Evento::query()->create([
            'titulo' => $title,
            'descripcion' => 'Evento para comprobar la tabla.',
            'nivel' => $level,
            'imagen_url' => $image,
            'imagen_media_path' => $mediaPath,
            'orden' => 1,
            'activo' => true,
        ]);
    }
}
