<?php

namespace App\Filament\Resources\ListaUtiles\Pages;

use App\Filament\Resources\ListaUtiles\ListaUtilResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditListaUtil extends EditRecord
{
    protected static string $resource = ListaUtilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
