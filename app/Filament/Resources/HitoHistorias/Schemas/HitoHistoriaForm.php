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
                                    ->label('Ano')
                                    ->helperText('Ejemplo: 2026.')
                                    ->required()
                                    ->maxLength(4),
                                TextInput::make('titulo')
                                    ->label('Titulo del momento')
                                    ->helperText('Ejemplo: Nueva etapa, certificacion, apertura de instalaciones.')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('orden')
                                    ->label('Orden en la linea del tiempo')
                                    ->helperText('Usa el ano o un numero consecutivo. Menor aparece primero.')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Textarea::make('texto')
                            ->label('Descripcion del momento')
                            ->helperText('Cuenta en pocas lineas que paso y por que es importante.')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Imagenes del momento')
                    ->description('Estas imagenes aparecen junto al texto en la linea del tiempo. Si se dejan vacias, el sitio usara Imagenes del sitio o los respaldos de /videosyfotos.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('imagen_url')
                                    ->label('Imagen principal')
                                    ->helperText('Foto principal del momento. Maximo 6 MB.')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->disk('public')
                                    ->directory('historia')
                                    ->visibility('public')
                                    ->maxSize(6144),
                                FileUpload::make('imagen_secundaria_url')
                                    ->label('Imagen secundaria')
                                    ->helperText('Opcional. Sirve para mostrar una segunda foto del mismo momento. Maximo 6 MB.')
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
