<?php

namespace App\Filament\Resources\GaleriaImagenes;

use App\Filament\Resources\GaleriaImagenes\Pages\CreateGaleriaImagen;
use App\Filament\Resources\GaleriaImagenes\Pages\EditGaleriaImagen;
use App\Filament\Resources\GaleriaImagenes\Pages\ListGaleriaImagenes;
use App\Filament\Resources\GaleriaImagenes\Schemas\GaleriaImagenForm;
use App\Filament\Resources\GaleriaImagenes\Tables\GaleriaImagenesTable;
use App\Models\GaleriaImagen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GaleriaImagenResource extends Resource
{
    protected static ?string $model = GaleriaImagen::class;

    protected static ?string $slug = 'galerias';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 18;

    protected static ?string $navigationLabel = 'Galerías';

    protected static ?string $modelLabel = 'imagen de galería';

    protected static ?string $pluralModelLabel = 'imágenes de galerías';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return GaleriaImagenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GaleriaImagenesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGaleriaImagenes::route('/'),
            'create' => CreateGaleriaImagen::route('/create'),
            'edit' => EditGaleriaImagen::route('/{record}/edit'),
        ];
    }
}
