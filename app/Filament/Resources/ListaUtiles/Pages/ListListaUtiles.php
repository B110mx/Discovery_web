<?php

namespace App\Filament\Resources\ListaUtiles\Pages;

use App\Filament\Resources\ListaUtiles\ListaUtilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListListaUtiles extends ListRecords
{
    protected static string $resource = ListaUtilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar lista'),
        ];
    }
}
