<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\ContactoPaginaResource\Pages\EditContactoPagina;
use App\Filament\Resources\Paginas\ContactoPaginaResource\Pages\ListContactoPagina;

class ContactoPaginaResource extends BasePaginaResource
{
    protected static ?string $slug = 'pagina-contacto';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Contacto';

    protected static string $pageSlug = 'contacto';

    public static function getPages(): array
    {
        return [
            'index' => ListContactoPagina::route('/'),
            'edit' => EditContactoPagina::route('/{record}/edit'),
        ];
    }
}
