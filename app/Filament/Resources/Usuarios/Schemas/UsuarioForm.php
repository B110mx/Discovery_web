<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UsuarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos de acceso')
                ->description('Crea o actualiza las credenciales que pueden entrar al panel administrativo.')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Select::make('role')
                        ->label('Tipo de acceso')
                        ->options(fn (): array => auth()->user()?->isPrimarySuperAdmin()
                            ? [
                                'admin' => 'Admin',
                                'super_admin' => 'Super admin',
                            ]
                            : [
                                'admin' => 'Admin',
                            ])
                        ->default('admin')
                        ->required()
                        ->native(false)
                        ->helperText('Solo el super admin principal puede crear o asignar cuentas super admin.'),

                    TextInput::make('password')
                        ->label('Nueva contraseña')
                        ->password()
                        ->revealable()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->maxLength(255)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->visible(fn (?User $record): bool => self::canChangePassword($record))
                        ->helperText('No se puede ver la contraseña actual. Escribe una nueva solo si necesitas restablecerla.'),
                ])
                ->columns(2),
        ]);
    }

    private static function canChangePassword(?User $record): bool
    {
        $user = auth()->user();

        if (! ($user?->isSuperAdmin() ?? false)) {
            return false;
        }

        if (! $record) {
            return true;
        }

        if ($record->role === 'super_admin') {
            return $user->isPrimarySuperAdmin();
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }
}
