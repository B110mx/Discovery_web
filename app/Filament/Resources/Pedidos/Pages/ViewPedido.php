<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPedido extends ViewRecord
{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
