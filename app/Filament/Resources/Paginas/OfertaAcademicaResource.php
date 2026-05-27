<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\OfertaAcademicaResource\Pages\EditOfertaAcademica;
use App\Filament\Resources\Paginas\OfertaAcademicaResource\Pages\ListOfertaAcademica;

class OfertaAcademicaResource extends BasePaginaResource
{
    protected static ?string $slug = 'pagina-oferta-academica';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Oferta Educativa';

    protected static string $pageSlug = 'oferta-academica';

    public static function getPages(): array
    {
        return [
            'index' => ListOfertaAcademica::route('/'),
            'edit' => EditOfertaAcademica::route('/{record}/edit'),
        ];
    }
}
