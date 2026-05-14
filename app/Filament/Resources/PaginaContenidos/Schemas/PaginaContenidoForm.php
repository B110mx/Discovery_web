<?php

namespace App\Filament\Resources\PaginaContenidos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaginaContenidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')
                ->label('Identificador')
                ->helperText('Ejemplo: contacto')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('titulo')
                ->required(),

            TextInput::make('subtitulo'),

            Textarea::make('descripcion')
                ->columnSpanFull(),

            FileUpload::make('imagen_principal')
                ->label('Imagen principal')
                ->image()
                ->disk('public')
                ->directory('paginas')
                ->columnSpanFull(),

            FileUpload::make('imagen_secundaria')
                ->label('Imagen secundaria')
                ->image()
                ->disk('public')
                ->directory('paginas')
                ->columnSpanFull(),

            TextInput::make('direccion')
                ->columnSpanFull(),

            TextInput::make('telefono_principal'),

            TextInput::make('telefono_secundario'),

            TextInput::make('correo')
                ->email(),

            Textarea::make('mapa_url')
                ->label('URL o iframe de mapa')
                ->columnSpanFull(),
        ]);
    }
}
