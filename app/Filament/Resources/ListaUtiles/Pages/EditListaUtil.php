<?php

namespace App\Filament\Resources\ListaUtiles\Pages;

use App\Filament\Resources\ListaUtiles\ListaUtilResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

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
