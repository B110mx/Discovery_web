<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use App\Models\Pedido;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('alumno_nombre')
                ->label('Alumno')
                ->required(),

            Select::make('alumno_nivel')
                ->label('Nivel')
                ->options(Pedido::nivelesDisponibles())
                ->required(),

            TextInput::make('alumno_grado')
                ->label('Grado o grupo'),

            TextInput::make('padre_nombre')
                ->label('Padre o tutor')
                ->required(),

            TextInput::make('padre_telefono')
                ->label('Telefono')
                ->required(),

            TextInput::make('padre_email')
                ->label('Correo electronico')
                ->email(),

            Select::make('estado')
                ->options(Pedido::estadosDisponibles())
                ->required(),

            TextInput::make('total')
                ->numeric()
                ->prefix('$')
                ->required(),

            Textarea::make('productos_resumen')
                ->label('Productos')
                ->disabled()
                ->dehydrated(false)
                ->columnSpanFull(),

            Textarea::make('notas')
                ->columnSpanFull(),
        ]);
    }
}
