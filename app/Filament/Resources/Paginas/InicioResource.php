<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\InicioResource\Pages\EditInicio;
use App\Filament\Resources\Paginas\InicioResource\Pages\ListInicio;

class InicioResource extends BasePaginaResource
{
    protected static ?string $slug = 'pagina-inicio';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Inicio';

    protected static string $pageSlug = 'inicio';

    public static function getPages(): array
    {
        return [
            'index' => ListInicio::route('/'),
            'edit' => EditInicio::route('/{record}/edit'),
        ];
    }
}
