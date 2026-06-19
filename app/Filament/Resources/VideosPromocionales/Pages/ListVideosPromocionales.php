<?php

namespace App\Filament\Resources\VideosPromocionales\Pages;

use App\Filament\Resources\VideosPromocionales\VideoPromocionalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideosPromocionales extends ListRecords
{
    protected static string $resource = VideoPromocionalResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Agregar video')];
    }
}
