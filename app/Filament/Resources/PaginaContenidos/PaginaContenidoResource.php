<?php

namespace App\Filament\Resources\PaginaContenidos;

use App\Filament\Resources\PaginaContenidos\Pages\CreatePaginaContenido;
use App\Filament\Resources\PaginaContenidos\Pages\EditPaginaContenido;
use App\Filament\Resources\PaginaContenidos\Pages\ListPaginaContenidos;
use App\Filament\Resources\PaginaContenidos\Schemas\PaginaContenidoForm;
use App\Filament\Resources\PaginaContenidos\Tables\PaginaContenidosTable;
use App\Models\PaginaContenido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaginaContenidoResource extends Resource
{
    protected static ?string $model = PaginaContenido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Contenido de paginas';

    protected static ?string $modelLabel = 'contenido de pagina';

    protected static ?string $pluralModelLabel = 'contenidos de paginas';

    public static function form(Schema $schema): Schema
    {
        return PaginaContenidoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaginaContenidosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaginaContenidos::route('/'),
            'create' => CreatePaginaContenido::route('/create'),
            'edit' => EditPaginaContenido::route('/{record}/edit'),
        ];
    }
}
