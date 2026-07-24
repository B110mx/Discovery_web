<?php

namespace App\Filament\Resources\HitoHistorias\Pages;

use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use App\Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

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
