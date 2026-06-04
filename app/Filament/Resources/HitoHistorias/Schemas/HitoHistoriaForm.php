<?php

namespace App\Filament\Resources\HitoHistorias\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HitoHistoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Texto del hito')
                    ->description('Agrega o actualiza momentos de la historia del colegio.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('anio')
                                    ->label('Año')
                                    ->helperText('Ejemplo: 2026.')
                                    ->required()
                                    ->maxLength(4),
                                TextInput::make('titulo')
                                    ->label('Título del momento')
                                    ->helperText('Ejemplo: Nueva etapa, certificación, apertura de instalaciones.')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('orden')
                                    ->label('Orden en la línea del tiempo')
                                    ->helperText('Usa el año o un número consecutivo. Menor aparece primero.')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Textarea::make('texto')
                            ->label('Descripción del momento')
                            ->helperText('Cuenta en pocas líneas qué pasó y por qué es importante.')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Imágenes del momento')
                    ->description('Estas imágenes aparecen junto al texto en la línea del tiempo. Si se dejan vacías, el sitio usará Imágenes del sitio o los respaldos de /videosyfotos.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('imagen_url')
                                    ->label('Imagen principal')
                                    ->helperText('Foto principal del momento. Máximo 6 MB.')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->disk('public')
                                    ->directory('historia')
                                    ->visibility('public')
                                    ->maxSize(6144),
                                FileUpload::make('imagen_secundaria_url')
                                    ->label('Imagen secundaria')
                                    ->helperText('Opcional. Sirve para mostrar una segunda foto del mismo momento. Máximo 6 MB.')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->disk('public')
                                    ->directory('historia')
                                    ->visibility('public')
                                    ->maxSize(6144),
                            ]),
                    ]),
            ]);
    }
}
