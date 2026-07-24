<?php

namespace Tests\Browser;

use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use App\Models\HitoHistoria;
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CriticalJourneysTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_visitor_can_change_language_and_submit_the_contact_form(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser
                ->visit('/?lang=en')
                ->waitForText('Schedule a visit')
                ->assertScript('document.documentElement.lang', 'en')
                ->visit('/contacto?lang=es')
                ->waitFor('#aspirante_nombre')
                ->type('aspirante_nombre', 'Ada Explorer')
                ->type('tutor_nombre', 'Grace Tutor')
                ->type('email', 'grace@example.test')
                ->type('telefono', '2381234567')
                ->select('grado', 'Elementary')
                ->press('Enviar mensaje')
                ->waitForText('Gracias por comunicarte con el Colegio Discovery®')
                ->assertSee('Gracias por comunicarte con el Colegio Discovery®');
        });

        $this->assertDatabaseHas('contactos', [
            'nombre' => 'Grace Tutor',
            'email' => 'grace@example.test',
        ]);
    }

    public function test_administrator_can_log_in_and_manage_site_content(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Browser',
            'email' => 'admin-browser@example.test',
            'password' => 'browser-password',
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin): void {
            $browser
                ->visit('/admin/login')
                ->waitFor('input[type="email"]')
                ->type('input[type="email"]', $admin->email)
                ->type('input[type="password"]', 'browser-password')
                ->click('button[type="submit"]')
                ->waitForLocation('/admin')
                ->assertPathIs('/admin')
                ->assertSee($admin->name);

            $browser
                ->visit(HitoHistoriaResource::getUrl('create'))
                ->waitForText('Crear Hito De Historia');

            $this->replaceFieldByLabel($browser, 'Año', '2030');
            $this->replaceFieldByLabel($browser, 'Título del momento', 'Nueva etapa');
            $this->replaceFieldByLabel($browser, 'Orden en la línea del tiempo', '2030');
            $this->replaceFieldByLabel(
                $browser,
                'Descripción del momento',
                'El colegio inicia una nueva etapa educativa.',
                'textarea',
            );

            $browser
                ->press('Crear y volver al listado')
                ->waitForLocation(HitoHistoriaResource::getUrl())
                ->assertPathIs(parse_url(HitoHistoriaResource::getUrl(), PHP_URL_PATH))
                ->assertSee('Nueva etapa');

            $hito = HitoHistoria::query()
                ->where('titulo', 'Nueva etapa')
                ->firstOrFail();

            $browser
                ->visit(HitoHistoriaResource::getUrl('edit', ['record' => $hito]))
                ->waitForText('Editar Nueva etapa');

            $this->replaceFieldByLabel(
                $browser,
                'Título del momento',
                'Nueva etapa actualizada',
            );

            $browser
                ->press('Guardar y volver al listado')
                ->waitForText('Nueva etapa actualizada');
        });

        $this->assertDatabaseHas('hito_historias', [
            'titulo' => 'Nueva etapa actualizada',
        ]);
    }

    private function replaceFieldByLabel(
        Browser $browser,
        string $label,
        string $value,
        string $element = 'input',
    ): void {
        $field = $browser->driver->findElement(WebDriverBy::xpath(sprintf(
            '//label[contains(normalize-space(.), "%s")]/following::%s[1]',
            $label,
            $element,
        )));

        $browser->driver->executeScript(
            <<<'JS'
                arguments[0].value = arguments[1];
                arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
                arguments[0].dispatchEvent(new Event('change', { bubbles: true }));
                arguments[0].dispatchEvent(new Event('blur', { bubbles: true }));
            JS,
            [$field, $value],
        );
    }
}
