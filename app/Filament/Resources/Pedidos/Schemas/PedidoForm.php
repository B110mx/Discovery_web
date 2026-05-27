<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use App\Models\Pedido;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del alumno')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('alumno_nombre')
                                ->label('Nombre del alumno')
                                ->required(),

                            Select::make('alumno_nivel')
                                ->label('Nivel')
                                ->options(Pedido::nivelesDisponibles())
                                ->required(),

                            TextInput::make('alumno_grado')
                                ->label('Grado o grupo'),
                        ]),
                ]),

            Section::make('Datos del padre, madre o tutor')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('padre_nombre')
                                ->label('Nombre del tutor')
                                ->required(),

                            TextInput::make('padre_telefono')
                                ->label('Telefono')
                                ->required(),

                            TextInput::make('padre_email')
                                ->label('Correo electronico')
                                ->email(),
                        ]),
                ]),

            Section::make('Estado del pedido')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('estado')
                                ->label('Estado')
                                ->helperText('Usa completado cuando el pedido ya fue atendido.')
                                ->options(Pedido::estadosDisponibles())
                                ->required(),

                            TextInput::make('total')
                                ->label('Total')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ]),
                ]),

            Section::make('Productos solicitados')
                ->schema([
                    Repeater::make('productos')
                        ->label('Lista de productos')
                        ->schema([
                            TextInput::make('nombre')
                                ->label('Producto')
                                ->required(),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->minValue(1)
                                ->required(),

                            TextInput::make('precio')
                                ->label('Precio')
                                ->numeric()
                                ->prefix('$')
                                ->required(),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ])
                        ->columns(4)
                        ->reorderable(false)
                        ->columnSpanFull(),

                    Textarea::make('notas')
                        ->label('Notas internas')
                        ->helperText('Comentarios para seguimiento del pedido.')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
