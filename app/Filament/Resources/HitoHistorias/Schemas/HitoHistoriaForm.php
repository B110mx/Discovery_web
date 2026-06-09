<?php

namespace App\Filament\Resources\HitoHistorias\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
                    ->description('Administra aquí todas las imágenes del hito. Si eliges una imagen de /videosyfotos y también subes una, se usará la imagen subida.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('imagen_media_path')
                                    ->label('Imagen principal desde /videosyfotos')
                                    ->options(fn () => VideoFotoOptions::images())
                                    ->searchable()
                                    ->preload()
                                    ->native(false),
                                FileUpload::make('imagen_url')
                                    ->label('O subir imagen principal')
                                    ->helperText('Máximo 6 MB.')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->disk('public')
                                    ->directory('historia')
                                    ->visibility('public')
                                    ->maxSize(6144),
                                Select::make('imagen_secundaria_media_path')
                                    ->label('Imagen secundaria desde /videosyfotos')
                                    ->options(fn () => VideoFotoOptions::images())
                                    ->searchable()
                                    ->preload()
                                    ->native(false),
                                FileUpload::make('imagen_secundaria_url')
                                    ->label('O subir imagen secundaria')
                                    ->helperText('Opcional. Máximo 6 MB.')
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
