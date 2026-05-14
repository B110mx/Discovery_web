<?php

namespace App\Filament\Resources\Eventos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class EventoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Evento')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('titulo')
                                    ->label('Título')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('orden')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(500),
                    ]),
                Section::make('Imagen del Evento')
                    ->schema([
                        FileUpload::make('imagen_url')
                            ->label('Imagen')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('eventos')
                            ->visibility('public')
                            ->maxSize(5120),
                    ]),
                Section::make('Estado')
                    ->schema([
                        Toggle::make('activo')
                            ->label('Evento Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
