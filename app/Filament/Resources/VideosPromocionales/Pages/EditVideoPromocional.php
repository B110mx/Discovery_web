<?php

namespace App\Filament\Resources\VideosPromocionales\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VideosPromocionales\VideoPromocionalResource;
use Filament\Actions\DeleteAction;

class EditVideoPromocional extends EditRecord
{
    protected static string $resource = VideoPromocionalResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
