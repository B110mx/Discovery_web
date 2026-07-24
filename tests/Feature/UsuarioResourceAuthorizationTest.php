<?php

namespace Tests\Feature;

use App\Filament\Resources\Usuarios\UsuarioResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioResourceAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_manage_user_credentials(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        $this->assertFalse(UsuarioResource::canViewAny());
        $this->assertFalse(UsuarioResource::canCreate());
        $this->assertFalse(UsuarioResource::canEdit($target));
        $this->assertFalse(UsuarioResource::canDelete($target));
    }

    public function test_secondary_super_admin_cannot_manage_primary_or_other_super_admins(): void
    {
        $primary = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $secondary = User::factory()->create(['role' => 'super_admin']);
        $otherSuperAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($secondary);

        $this->assertTrue(UsuarioResource::canViewAny());
        $this->assertTrue(UsuarioResource::canCreate());
        $this->assertFalse(UsuarioResource::canEdit($primary));
        $this->assertFalse(UsuarioResource::canDelete($primary));
        $this->assertFalse(UsuarioResource::canEdit($otherSuperAdmin));
        $this->assertFalse(UsuarioResource::canDelete($otherSuperAdmin));
        $this->assertFalse(UsuarioResource::canDelete($secondary));
        $this->assertFalse(
            UsuarioResource::getEloquentQuery()->whereKey($primary)->exists(),
        );
    }

    public function test_primary_super_admin_can_manage_other_accounts_but_not_delete_itself(): void
    {
        $primary = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'role' => 'super_admin',
        ]);
        $secondary = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($primary);

        $this->assertTrue(UsuarioResource::canEdit($secondary));
        $this->assertTrue(UsuarioResource::canDelete($secondary));
        $this->assertTrue(UsuarioResource::canEdit($admin));
        $this->assertTrue(UsuarioResource::canDelete($admin));
        $this->assertFalse(UsuarioResource::canDelete($primary));
        $this->assertTrue(
            UsuarioResource::getEloquentQuery()->whereKey($primary)->exists(),
        );
    }
}
