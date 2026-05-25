<?php

namespace App\Filament\Resources\SeccionImagenes;

use App\Filament\Resources\SeccionImagenes\Pages\CreateSeccionImagen;
use App\Filament\Resources\SeccionImagenes\Pages\EditSeccionImagen;
use App\Filament\Resources\SeccionImagenes\Pages\ListSeccionImagenes;
use App\Filament\Resources\SeccionImagenes\Schemas\SeccionImagenForm;
use App\Filament\Resources\SeccionImagenes\Tables\SeccionImagenesTable;
use App\Models\SeccionImagen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SeccionImagenResource extends Resource
{
    protected static ?string $model = SeccionImagen::class;

    protected static ?string $slug = 'imagenes-por-seccion';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Imagenes de cada vista';

    protected static ?string $modelLabel = 'imagen editable de vista';

    protected static ?string $pluralModelLabel = 'imagenes editables por vista';

    public static function form(Schema $schema): Schema
    {
        return SeccionImagenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeccionImagenesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeccionImagenes::route('/'),
            'create' => CreateSeccionImagen::route('/create'),
            'edit' => EditSeccionImagen::route('/{record}/edit'),
        ];
    }
}
