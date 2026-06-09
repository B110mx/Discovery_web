<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\PaginaContenidos\Schemas\PaginaContenidoForm;
use App\Filament\Resources\PaginaContenidos\Tables\PaginaContenidosTable;
use App\Models\PaginaContenido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/**
 * Base de los editores individuales de "Páginas del sitio".
 *
 * Cada subclase define pageSlug y reutiliza el mismo formulario y tabla,
 * limitando la consulta para que nunca edite accidentalmente otra página.
 */
abstract class BasePaginaResource extends Resource
{
    protected static ?string $model = PaginaContenido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Páginas del sitio';

    protected static ?string $modelLabel = 'página del sitio';

    protected static ?string $pluralModelLabel = 'página del sitio';

    protected static ?string $recordTitleAttribute = 'titulo';

    protected static string $pageSlug = '';

    public static function form(Schema $schema): Schema
    {
        return PaginaContenidoForm::configure($schema, static::$pageSlug);
    }

    public static function table(Table $table): Table
    {
        return PaginaContenidosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('slug', static::$pageSlug);
    }
}
