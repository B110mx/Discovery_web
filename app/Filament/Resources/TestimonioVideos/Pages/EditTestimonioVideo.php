<?php

namespace App\Filament\Resources\TestimonioVideos\Pages;

use App\Filament\Resources\TestimonioVideos\TestimonioVideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestimonioVideo extends EditRecord
{
    protected static string $resource = TestimonioVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
