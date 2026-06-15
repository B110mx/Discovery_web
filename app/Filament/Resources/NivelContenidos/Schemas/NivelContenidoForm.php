<?php

namespace App\Filament\Resources\NivelContenidos\Schemas;

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
            Section::make('Identificación')
                ->description('El nivel y su URL son técnicos y no se modifican desde el panel.')
                ->schema([
                    TextEntry::make('slug')
                        ->label('URL del nivel')
                        ->badge(),
                ]),

            Section::make('Página del nivel')
                ->description('Textos principales del hero y de la introducción interior.')
                ->schema([
                    TextInput::make('titulo')
                        ->label('Nombre visible del nivel')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('descripcion')
                        ->label('Descripción del hero')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    TextInput::make('contenido_titulo')
                        ->label('Título de la sección principal')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('contenido_intro')
                        ->label('Introducción de la sección principal')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Tarjeta de Oferta Educativa')
                ->description('Resumen que aparece en la página general de Oferta Educativa.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('oferta_subtitulo')
                                ->label('Subtítulo')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('oferta_edad')
                                ->label('Etapa o edades')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Textarea::make('oferta_descripcion')
                        ->label('Descripción breve')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    TagsInput::make('oferta_puntos')
                        ->label('Puntos destacados')
                        ->helperText('Presiona Enter después de cada punto.')
                        ->required()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
