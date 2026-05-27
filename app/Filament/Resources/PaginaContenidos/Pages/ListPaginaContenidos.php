<?php

namespace App\Filament\Resources\PaginaContenidos\Pages;

use App\Filament\Resources\PaginaContenidos\PaginaContenidoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaginaContenidos extends ListRecords
{
    protected static string $resource = PaginaContenidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar contenido de pagina'),
        ];
    }
}
