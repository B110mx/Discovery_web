<?php

namespace App\Filament\Resources\Usuarios\Tables;

use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsuariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Tipo de acceso')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'Super admin',
                        default => 'Admin',
                    })
                    ->color(fn (string $state): string => $state === 'super_admin' ? 'danger' : 'info')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->visible(fn (User $record): bool => self::canManageRecord($record)),
                DeleteAction::make()
                    ->visible(fn (User $record): bool => self::canManageRecord($record) && auth()->id() !== $record->id),
            ]);
    }

    private static function canManageRecord(User $record): bool
    {
        $user = auth()->user();

        if (! ($user?->isSuperAdmin() ?? false)) {
            return false;
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }
}
