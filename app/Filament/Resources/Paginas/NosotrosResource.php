<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\NosotrosResource\Pages\EditNosotros;
use App\Filament\Resources\Paginas\NosotrosResource\Pages\ListNosotros;

class NosotrosResource extends BasePaginaResource
{
    protected static ?string $slug = 'pagina-nosotros';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Conócenos / Nosotros';

    protected static string $pageSlug = 'nosotros';

    public static function getPages(): array
    {
        return [
            'index' => ListNosotros::route('/'),
            'edit' => EditNosotros::route('/{record}/edit'),
        ];
    }
}
