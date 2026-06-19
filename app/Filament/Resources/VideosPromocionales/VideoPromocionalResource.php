<?php

namespace App\Filament\Resources\VideosPromocionales;

use App\Filament\Resources\VideosPromocionales\Pages\CreateVideoPromocional;
use App\Filament\Resources\VideosPromocionales\Pages\EditVideoPromocional;
use App\Filament\Resources\VideosPromocionales\Pages\ListVideosPromocionales;
use App\Filament\Resources\VideosPromocionales\Schemas\VideoPromocionalForm;
use App\Filament\Resources\VideosPromocionales\Tables\VideosPromocionalesTable;
use App\Models\VideoPromocional;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VideoPromocionalResource extends Resource
{
    protected static ?string $model = VideoPromocional::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;
    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';
    protected static ?int $navigationSort = 14;
    protected static ?string $navigationLabel = 'Videos promocionales';
    protected static ?string $modelLabel = 'video promocional';
    protected static ?string $pluralModelLabel = 'videos promocionales';
    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return VideoPromocionalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideosPromocionalesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideosPromocionales::route('/'),
            'create' => CreateVideoPromocional::route('/create'),
            'edit' => EditVideoPromocional::route('/{record}/edit'),
        ];
    }
}
