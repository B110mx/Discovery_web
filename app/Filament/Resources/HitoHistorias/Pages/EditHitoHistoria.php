<?php

namespace App\Filament\Resources\HitoHistorias\Pages;

use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\Pages\EditRecord;

class EditHitoHistoria extends EditRecord
{
    protected static string $resource = HitoHistoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
