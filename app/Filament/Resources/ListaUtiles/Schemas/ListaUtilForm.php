<?php

namespace App\Filament\Resources\ListaUtiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ListaUtilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos que verá la familia')
                ->description('Captura el ciclo escolar, nivel y grado para que la lista aparezca ordenada en Recursos escolares.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('ciclo_escolar')
                                ->label('Ciclo escolar')
                                ->helperText('Ejemplo: 2026-2027. Usa el mismo texto para todas las listas del ciclo.')
                                ->required()
                                ->maxLength(20),
                            Select::make('nivel')
                                ->label('Nivel')
                                ->options([
                                    'Kinder' => 'Kinder',
                                    'Elementary' => 'Elementary',
                                    'Middle' => 'Middle',
                                    'High' => 'High',
                                    'General' => 'General',
                                ])
                                ->searchable()
                                ->native(false)
                                ->required(),
                            TextInput::make('grado')
                                ->label('Grado o grupo')
                                ->helperText('Ejemplo: 1° grado, 2° grado, Kinder 3° o General.')
                                ->required()
                                ->maxLength(120),
                            TextInput::make('titulo')
                                ->label('Nombre de la lista')
                                ->helperText('Ejemplo: Lista de útiles Elementary 1° grado.')
                                ->required()
                                ->maxLength(255),
                        ]),
                ]),

            Section::make('PDF de la lista')
                ->description('Sube el archivo actualizado. Para cambiar de ciclo escolar, edita este registro o crea uno nuevo y desactiva el anterior.')
                ->schema([
                    FileUpload::make('archivo_pdf')
                        ->label('Archivo PDF')
                        ->helperText('Solo PDF. Al guardar, este será el archivo que podrán abrir las familias.')
                        ->disk('public')
                        ->directory('listas-utiles')
                        ->visibility('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(12288)
                        ->downloadable()
                        ->openable()
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('orden')
                                ->label('Orden de aparición')
                                ->helperText('Número menor aparece primero.')
                                ->numeric()
                                ->default(0),
                            Toggle::make('activo')
                                ->label('Mostrar esta lista en el sitio')
                                ->helperText('Apágalo para ocultar listas de ciclos anteriores sin borrarlas.')
                                ->default(true),
                        ]),
                ]),
        ]);
    }
}
