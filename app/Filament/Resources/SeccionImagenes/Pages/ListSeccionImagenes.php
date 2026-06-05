<?php

namespace App\Filament\Resources\SeccionImagenes\Pages;

use App\Filament\Resources\SeccionImagenes\SeccionImagenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeccionImagenes extends ListRecords
{
    protected static string $resource = SeccionImagenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva imagen del sitio'),
        ];
    }
}
