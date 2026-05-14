<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $recordTitleAttribute = 'alumno_nombre';

    public static function form(Schema $schema): Schema
    {
        return PedidoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PedidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosTable::configure($table);
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
            'index' => ListPedidos::route('/'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }
}
