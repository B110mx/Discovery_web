<?php

namespace App\Filament\Resources\ListaUtiles;

use App\Filament\Resources\ListaUtiles\Pages\CreateListaUtil;
use App\Filament\Resources\ListaUtiles\Pages\EditListaUtil;
use App\Filament\Resources\ListaUtiles\Pages\ListListaUtiles;
use App\Filament\Resources\ListaUtiles\Schemas\ListaUtilForm;
use App\Filament\Resources\ListaUtiles\Tables\ListaUtilesTable;
use App\Models\ListaUtil;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ListaUtilResource extends Resource
{
    protected static ?string $model = ListaUtil::class;

    protected static ?string $slug = 'listas-de-utiles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido del sitio';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'Listas de utiles';

    protected static ?string $modelLabel = 'lista de utiles';

    protected static ?string $pluralModelLabel = 'listas de utiles';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return ListaUtilForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ListaUtilesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListaUtiles::route('/'),
            'create' => CreateListaUtil::route('/create'),
            'edit' => EditListaUtil::route('/{record}/edit'),
        ];
    }
}
