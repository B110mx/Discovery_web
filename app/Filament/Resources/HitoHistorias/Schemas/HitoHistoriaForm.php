<?php

namespace App\Filament\Resources\HitoHistorias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HitoHistoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('anio')
                    ->label('Ano que aparece en la linea del tiempo')
                    ->required(),
                TextInput::make('titulo')
                    ->label('Titulo del hito')
                    ->required(),
                Textarea::make('texto')
                    ->label('Texto descriptivo')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('imagen_url')
                    ->label('URL de imagen del hito')
                    ->url(),
                TextInput::make('orden')
                    ->label('Orden en la linea del tiempo')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
