<?php

namespace App\Filament\Resources\Usuarios\Pages;

use App\Filament\Resources\Usuarios\UsuarioResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecord;

class EditUsuario extends EditRecord
{
    protected static string $resource = UsuarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function (User $record): bool {
                    $user = auth()->user();

                    if (! ($user?->isSuperAdmin() ?? false) || auth()->id() === $record->id) {
                        return false;
                    }

                    if ($record->role === 'super_admin') {
                        return $user->isPrimarySuperAdmin();
                    }

                    return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
                }),
        ];
    }
}
