<?php

namespace App\Filament\Resources\Contactos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->required(),

            TextInput::make('email')
                ->label('Correo electronico')
                ->email()
                ->required(),

            Textarea::make('mensaje')
                ->required()
                ->columnSpanFull(),
        ]);
    }
}
