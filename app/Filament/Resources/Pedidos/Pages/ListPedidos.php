<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Resources\Pages\ListRecords;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
