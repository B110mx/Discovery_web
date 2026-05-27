<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\ProtagonistasResource\Pages\EditProtagonistas;
use App\Filament\Resources\Paginas\ProtagonistasResource\Pages\ListProtagonistas;

class ProtagonistasResource extends BasePaginaResource
{
    protected static ?string $slug = 'pagina-protagonistas';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Comunidad / Protagonistas';

    protected static string $pageSlug = 'protagonistas';

    public static function getPages(): array
    {
        return [
            'index' => ListProtagonistas::route('/'),
            'edit' => EditProtagonistas::route('/{record}/edit'),
        ];
    }
}
