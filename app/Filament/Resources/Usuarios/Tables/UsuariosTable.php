<?php

namespace App\Filament\Resources\Usuarios\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                Action::make('resetPassword')
                    ->label('Restablecer contraseña')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->schema([
                        TextInput::make('password')
                            ->label('Nueva contraseña temporal')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText('No se muestra la contraseña anterior. Escribe una nueva y compártela de forma segura.'),
                    ])
                    ->modalHeading(fn (User $record): string => 'Restablecer contraseña de ' . $record->name)
                    ->modalSubmitActionLabel('Guardar nueva contraseña')
                    ->action(function (array $data, User $record): void {
                        $record->forceFill([
                            'password' => $data['password'],
                        ])->save();

                        Notification::make()
                            ->title('Contraseña restablecida')
                            ->body('La cuenta ya puede entrar con la nueva contraseña.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => self::canResetPassword($record)),
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

        if ($record->role === 'super_admin') {
            return $user->isPrimarySuperAdmin();
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }

    private static function canResetPassword(User $record): bool
    {
        $user = auth()->user();

        if (! ($user?->isSuperAdmin() ?? false)) {
            return false;
        }

        if ($record->role === 'super_admin') {
            return $user->isPrimarySuperAdmin();
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }
}
