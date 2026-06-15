<?php

namespace App\Filament\Resources\GaleriaImagenes\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GaleriaImagenForm
{
    public static function levelOptions(): array
    {
        return [
            'preescolar' => 'Kinder',
            'primaria' => 'Elementary',
            'secundaria' => 'Middle',
            'bachillerato' => 'High',
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ubicación y texto')
                ->description('Elige la galería donde aparecerá la imagen y escribe una descripción breve para accesibilidad.')
                ->schema([
                    Select::make('nivel')
                        ->label('Nivel académico')
                        ->options(self::levelOptions())
                        ->required()
                        ->native(false),
                    TextInput::make('titulo')
                        ->label('Título interno')
                        ->helperText('Sirve para identificar la imagen en el panel.')
                        ->maxLength(255),
                    TextInput::make('texto_alternativo')
                        ->label('Descripción de la imagen')
                        ->helperText('Ejemplo: Explorers de Elementary trabajando en un proyecto de ciencias.')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('orden')
                        ->label('Orden')
                        ->helperText('También puedes reordenar arrastrando desde el listado.')
                        ->numeric()
                        ->default(0),
                    Toggle::make('activo')
                        ->label('Mostrar en el sitio')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Imagen')
                ->description('Selecciona una imagen existente o sube una nueva. La imagen subida tiene prioridad.')
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
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('galerias')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->requiredWithout('imagen_media_path'),
                ]),
        ]);
    }
}
