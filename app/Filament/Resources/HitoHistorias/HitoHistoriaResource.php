<?php

namespace App\Filament\Resources\HitoHistorias;

use App\Filament\Resources\HitoHistorias\Pages\CreateHitoHistoria;
use App\Filament\Resources\HitoHistorias\Pages\EditHitoHistoria;
use App\Filament\Resources\HitoHistorias\Pages\ListHitoHistorias;
use App\Filament\Resources\HitoHistorias\Pages\ViewHitoHistoria;
use App\Filament\Resources\HitoHistorias\Schemas\HitoHistoriaForm;
use App\Filament\Resources\HitoHistorias\Schemas\HitoHistoriaInfolist;
use App\Filament\Resources\HitoHistorias\Tables\HitoHistoriasTable;
use App\Models\HitoHistoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HitoHistoriaResource extends Resource
{
    protected static ?string $model = HitoHistoria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Nosotros - Linea del tiempo';

    protected static ?string $modelLabel = 'hito de historia';

    protected static ?string $pluralModelLabel = 'linea del tiempo de Nosotros';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return HitoHistoriaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HitoHistoriaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HitoHistoriasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHitoHistorias::route('/'),
            'create' => CreateHitoHistoria::route('/create'),
            'view' => ViewHitoHistoria::route('/{record}'),
            'edit' => EditHitoHistoria::route('/{record}/edit'),
        ];
    }
}
