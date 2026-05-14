<?php

namespace App\Filament\Resources\Contactos\Pages;

use App\Filament\Resources\Contactos\ContactoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContacto extends ViewRecord
{
    protected static string $resource = ContactoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
