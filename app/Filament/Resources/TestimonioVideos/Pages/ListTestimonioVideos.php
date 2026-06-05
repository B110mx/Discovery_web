<?php

namespace App\Filament\Resources\TestimonioVideos\Pages;

use App\Filament\Resources\TestimonioVideos\TestimonioVideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonioVideos extends ListRecords
{
    protected static string $resource = TestimonioVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo video'),
        ];
    }
}
