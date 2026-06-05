<?php

namespace App\Filament\Resources\Usuarios\Schemas;

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
                        ->options([
                            'admin' => 'Admin',
                            'super_admin' => 'Super admin',
                        ])
                        ->default('admin')
                        ->required()
                        ->native(false)
                        ->helperText('Solo un super admin puede crear, editar o borrar credenciales.'),

                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->maxLength(255)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('Déjala vacía al editar si no quieres cambiarla.'),
                ])
                ->columns(2),
        ]);
    }
}
