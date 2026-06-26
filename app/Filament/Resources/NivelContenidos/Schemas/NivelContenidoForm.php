<?php

namespace App\Filament\Resources\NivelContenidos\Schemas;

use App\Models\NivelContenido;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NivelContenidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identificacion')
                ->description('El nivel y su URL son tecnicos y no se modifican desde el panel.')
                ->schema([
                    TextEntry::make('slug')
                        ->label('URL del nivel')
                        ->badge(),
                ]),

            Section::make('Pagina del nivel')
                ->description('Textos principales del hero y de la introduccion interior.')
                ->schema([
                    TextInput::make('titulo')
                        ->label('Nombre visible del nivel')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('descripcion')
                        ->label('Descripcion del hero')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    TextInput::make('contenido_titulo')
                        ->label('Titulo de la seccion principal')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('contenido_intro')
                        ->label('Introduccion de la seccion principal')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Tarjeta de Oferta Educativa')
                ->description('Resumen que aparece en la pagina general de Oferta Educativa.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('oferta_subtitulo')
                                ->label('Subtitulo')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('oferta_edad')
                                ->label('Etapa o edades')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Textarea::make('oferta_descripcion')
                        ->label('Descripcion breve')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    TagsInput::make('oferta_puntos')
                        ->label('Puntos destacados')
                        ->helperText('Presiona Enter despues de cada punto.')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Rutas preuniversitarias POP')
                ->description('Controla cuales rutas se muestran en la seccion del POP del IB.')
                ->visible(fn (?NivelContenido $record): bool => $record?->slug === 'pop-del-ib')
                ->schema([
                    CheckboxList::make('pop_rutas_visibles')
                        ->label('Rutas visibles')
                        ->options([
                            'data_science' => 'Ruta de Data Science',
                            'diseno_3d' => 'Ruta de Diseno e Impresion 3D',
                        ])
                        ->helperText('Selecciona una o ambas rutas. La pagina usara esta seleccion al publicarse.')
                        ->columns(2)
                        ->required()
                        ->minItems(1)
                        ->dehydrated(fn (?NivelContenido $record): bool => $record?->slug === 'pop-del-ib'),
                ]),
        ]);
    }
}
