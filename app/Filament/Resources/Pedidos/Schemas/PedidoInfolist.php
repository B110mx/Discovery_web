<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('alumno_nombre')
                ->label('Alumno'),
            TextEntry::make('alumno_nivel')
                ->label('Nivel'),
            TextEntry::make('alumno_grado')
                ->label('Grado o grupo')
                ->placeholder('-'),
            TextEntry::make('padre_nombre')
                ->label('Padre o tutor'),
            TextEntry::make('padre_telefono')
                ->label('Telefono'),
            TextEntry::make('padre_email')
                ->label('Correo electronico')
                ->placeholder('-'),
            TextEntry::make('estado'),
            TextEntry::make('total')
                ->money('MXN'),
            TextEntry::make('productos_resumen')
                ->label('Productos')
                ->columnSpanFull(),
            TextEntry::make('notas')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('created_at')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->dateTime()
                ->placeholder('-'),
        ]);
    }
}
