<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EnsurePrimarySuperAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_primary_superadmin_keeps_the_current_credentials(): void
    {
        $user = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'password' => 'Current-password-123!',
            'role' => 'super_admin',
        ]);
        $originalHash = $user->getRawOriginal('password');

        $this->artisan('admin:ensure-primary')
            ->expectsOutput("El superadmin principal ya existe: {$user->email}")
            ->expectsOutput('La contraseña actual no fue modificada.')
            ->assertSuccessful();

        $this->assertSame($originalHash, $user->fresh()->getRawOriginal('password'));
        $this->assertTrue(Hash::check('Current-password-123!', $user->fresh()->password));
    }

    public function test_password_reset_requires_confirmation_and_updates_only_when_approved(): void
    {
        $user = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'password' => 'Current-password-123!',
            'role' => 'super_admin',
        ]);

        $this->artisan('admin:ensure-primary', ['--reset-password' => true])
            ->expectsConfirmation(
                "¿Confirmas que deseas restablecer la contraseña de {$user->email}?",
                'no',
            )
            ->expectsOutput('Operación cancelada. La contraseña actual no fue modificada.')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('Current-password-123!', $user->fresh()->password));
    }

    public function test_approved_password_reset_replaces_only_the_requested_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'Superadmin existente',
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'password' => 'Current-password-123!',
            'role' => 'admin',
        ]);

        $this->artisan('admin:ensure-primary', ['--reset-password' => true])
            ->expectsConfirmation(
                "¿Confirmas que deseas restablecer la contraseña de {$user->email}?",
                'yes',
            )
            ->expectsQuestion(
                'Nueva contraseña (mínimo 12 caracteres, mayúscula, minúscula, número y símbolo)',
                'New-password-456!',
            )
            ->expectsQuestion('Confirma la nueva contraseña', 'New-password-456!')
            ->expectsOutput("Contraseña restablecida para {$user->email}.")
            ->assertSuccessful();

        $user->refresh();

        $this->assertSame('Superadmin existente', $user->name);
        $this->assertSame('super_admin', $user->role);
        $this->assertTrue(Hash::check('New-password-456!', $user->password));
    }

    public function test_missing_primary_superadmin_can_be_created_interactively(): void
    {
        $this->artisan('admin:ensure-primary', ['--name' => 'Cuenta recuperada'])
            ->expectsQuestion(
                'Nueva contraseña (mínimo 12 caracteres, mayúscula, minúscula, número y símbolo)',
                'New-password-456!',
            )
            ->expectsQuestion('Confirma la nueva contraseña', 'New-password-456!')
            ->expectsOutput('Superadmin principal creado correctamente.')
            ->assertSuccessful();

        $user = User::query()
            ->where('email', User::PRIMARY_SUPER_ADMIN_EMAIL)
            ->firstOrFail();

        $this->assertSame('Cuenta recuperada', $user->name);
        $this->assertSame('super_admin', $user->role);
        $this->assertTrue(Hash::check('New-password-456!', $user->password));
    }

    public function test_invalid_recovery_password_is_rejected(): void
    {
        $this->artisan('admin:ensure-primary', ['--name' => 'Cuenta no creada'])
            ->expectsQuestion(
                'Nueva contraseña (mínimo 12 caracteres, mayúscula, minúscula, número y símbolo)',
                'weak',
            )
            ->expectsQuestion('Confirma la nueva contraseña', 'different')
            ->assertFailed();

        $this->assertDatabaseMissing('users', [
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
        ]);
    }
}
