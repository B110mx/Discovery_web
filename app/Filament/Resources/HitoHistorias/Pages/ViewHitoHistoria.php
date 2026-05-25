<?php

namespace App\Filament\Resources\HitoHistorias\Pages;

use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHitoHistoria extends ViewRecord
{
    protected static string $resource = HitoHistoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
