<?php

namespace App\Filament\Resources\HitoHistorias\Pages;

use App\Filament\Resources\HitoHistorias\HitoHistoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHitoHistorias extends ListRecords
{
    protected static string $resource = HitoHistoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
