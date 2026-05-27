<?php

namespace App\Filament\Resources\TestimonioVideos;

use App\Filament\Resources\TestimonioVideos\Pages\CreateTestimonioVideo;
use App\Filament\Resources\TestimonioVideos\Pages\EditTestimonioVideo;
use App\Filament\Resources\TestimonioVideos\Pages\ListTestimonioVideos;
use App\Filament\Resources\TestimonioVideos\Schemas\TestimonioVideoForm;
use App\Filament\Resources\TestimonioVideos\Tables\TestimonioVideosTable;
use App\Models\TestimonioVideo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TestimonioVideoResource extends Resource
{
    protected static ?string $model = TestimonioVideo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 15;

    protected static ?string $navigationLabel = 'Carrusel de videos';

    protected static ?string $modelLabel = 'video de testimonio';

    protected static ?string $pluralModelLabel = 'videos del carrusel de testimonios';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return TestimonioVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestimonioVideosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonioVideos::route('/'),
            'create' => CreateTestimonioVideo::route('/create'),
            'edit' => EditTestimonioVideo::route('/{record}/edit'),
        ];
    }
}
