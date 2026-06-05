<?php

namespace App\Filament\Resources\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;

abstract class EditRecord extends FilamentEditRecord
{
    protected function getRedirectUrl(): ?string
    {
        return $this->getResourceUrl();
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar y volver al listado');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Volver al listado');
    }
}
