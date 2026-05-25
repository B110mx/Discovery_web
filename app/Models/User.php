<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser; // 🔥 Importación de seguridad de Filament
use Filament\Panel;                         // 🔥 Importación del Panel de Filament
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser // 🔥 Agregamos la implementación aquí
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔥 SEGURIDAD: Determina si el usuario puede entrar al panel /admin
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // ⚠️ IMPORTANTE: Agrega aquí el correo con el que inicias sesión actualmente.
        // Si no lo pones, ¡el sistema te dejará fuera a ti también!
        $correosPermitidos = [
            'admin@colegiodiscovery.edu',
            'wuicho.bryan@gmail.com', 
        ];

        return in_array($this->email, $correosPermitidos);
    }
}