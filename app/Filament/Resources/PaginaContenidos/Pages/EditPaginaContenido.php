<?php

namespace App\Filament\Resources\PaginaContenidos\Pages;

use App\Filament\Resources\PaginaContenidos\PaginaContenidoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaginaContenido extends EditRecord
{
    protected static string $resource = PaginaContenidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
