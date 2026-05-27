<?php

namespace App\Filament\Resources\Contactos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Mensaje recibido desde el sitio')
                ->description('Datos enviados por una familia o visitante desde el formulario de contacto.')
                ->schema([
                    TextInput::make('nombre')
                        ->label('Nombre de la persona')
                        ->required(),

                    TextInput::make('email')
                        ->label('Correo electronico')
                        ->email()
                        ->required(),

                    Textarea::make('mensaje')
                        ->label('Mensaje')
                        ->rows(5)
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }
}
