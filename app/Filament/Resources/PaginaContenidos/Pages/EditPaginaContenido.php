<?php

namespace App\Filament\Resources\PaginaContenidos\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PaginaContenidos\PaginaContenidoResource;

class EditPaginaContenido extends EditRecord
{
    protected static string $resource = PaginaContenidoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
