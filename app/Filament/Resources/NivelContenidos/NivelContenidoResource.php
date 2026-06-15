<?php

namespace App\Filament\Resources\NivelContenidos;

use App\Filament\Resources\NivelContenidos\Pages\EditNivelContenido;
use App\Filament\Resources\NivelContenidos\Pages\ListNivelContenidos;
use App\Filament\Resources\NivelContenidos\Schemas\NivelContenidoForm;
use App\Filament\Resources\NivelContenidos\Tables\NivelContenidosTable;
use App\Models\NivelContenido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NivelContenidoResource extends Resource
{
    protected static ?string $model = NivelContenido::class;

    protected static ?string $slug = 'contenido-de-niveles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Páginas del sitio';

    protected static ?int $navigationSort = 35;

    protected static ?string $navigationLabel = 'Contenido de niveles';

    protected static ?string $modelLabel = 'contenido de nivel';

    protected static ?string $pluralModelLabel = 'contenidos de niveles';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return NivelContenidoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NivelContenidosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNivelContenidos::route('/'),
            'edit' => EditNivelContenido::route('/{record}/edit'),
        ];
    }
}
