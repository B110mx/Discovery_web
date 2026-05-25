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
                ->label('Pagina que se esta editando')
                ->helperText('Ejemplo: contacto. Este campo conecta los datos con una vista del sitio.')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('titulo')
                ->required(),

            TextInput::make('subtitulo'),

            Textarea::make('descripcion')
                ->columnSpanFull(),

            FileUpload::make('imagen_principal')
                ->label('Imagen principal de la pagina')
                ->image()
                ->disk('public')
                ->directory('paginas')
                ->columnSpanFull(),

            FileUpload::make('imagen_secundaria')
                ->label('Imagen secundaria o de apoyo')
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
                ->label('Mapa de Google: URL o iframe')
                ->columnSpanFull(),
        ]);
    }
}
