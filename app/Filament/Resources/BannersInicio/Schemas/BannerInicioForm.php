<?php

namespace App\Filament\Resources\BannersInicio\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerInicioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información del banner')
                ->description('La imagen ya puede incluir su mensaje. Estos campos ayudan a identificarla, hacerla accesible y enlazarla opcionalmente.')
                ->schema([
                    TextInput::make('titulo')
                        ->label('Título interno')
                        ->helperText('Sirve para reconocer el banner dentro del panel.')
                        ->maxLength(255),
                    TextInput::make('texto_alternativo')
                        ->label('Descripción de la imagen')
                        ->helperText('Describe brevemente lo que muestra el banner para accesibilidad.')
                        ->maxLength(255),
                    TextInput::make('enlace')
                        ->label('Enlace opcional')
                        ->helperText('Usa una ruta como /contacto o una dirección completa https://...')
                        ->rules(['nullable', 'regex:/^(https?:\/\/|\/)/i'])
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('orden')
                        ->label('Orden')
                        ->helperText('También puedes reordenar arrastrando desde el listado.')
                        ->numeric()
                        ->default(0),
                    Toggle::make('activo')
                        ->label('Mostrar en el carrusel')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Imagen del banner')
                ->description('Selecciona un archivo existente o sube uno nuevo. La imagen subida tiene prioridad.')
                ->schema([
                    Select::make('imagen_media_path')
                        ->label('Elegir desde /videosyfotos')
                        ->options(fn () => VideoFotoOptions::images())
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->requiredWithout('imagen'),
                    FileUpload::make('imagen')
                        ->label('O subir imagen nueva')
                        ->helperText('Formato recomendado: 1916 × 657 px o una proporción horizontal equivalente.')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('banners-inicio')
                        ->visibility('public')
                        ->maxSize(10240)
                        ->requiredWithout('imagen_media_path'),
                ]),
        ]);
    }
}
