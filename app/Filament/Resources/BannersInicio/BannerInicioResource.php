<?php

namespace App\Filament\Resources\BannersInicio;

use App\Filament\Resources\BannersInicio\Pages\CreateBannerInicio;
use App\Filament\Resources\BannersInicio\Pages\EditBannerInicio;
use App\Filament\Resources\BannersInicio\Pages\ListBannersInicio;
use App\Filament\Resources\BannersInicio\Schemas\BannerInicioForm;
use App\Filament\Resources\BannersInicio\Tables\BannersInicioTable;
use App\Models\BannerInicio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BannerInicioResource extends Resource
{
    protected static ?string $model = BannerInicio::class;

    protected static ?string $slug = 'banners-de-inicio';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 17;

    protected static ?string $navigationLabel = 'Banners de Inicio';

    protected static ?string $modelLabel = 'banner de Inicio';

    protected static ?string $pluralModelLabel = 'banners de Inicio';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return BannerInicioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannersInicioTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBannersInicio::route('/'),
            'create' => CreateBannerInicio::route('/create'),
            'edit' => EditBannerInicio::route('/{record}/edit'),
        ];
    }
}
