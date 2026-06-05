<?php

namespace App\Filament\Resources\Usuarios;

use App\Filament\Resources\Usuarios\Pages\CreateUsuario;
use App\Filament\Resources\Usuarios\Pages\EditUsuario;
use App\Filament\Resources\Usuarios\Pages\ListUsuarios;
use App\Filament\Resources\Usuarios\Schemas\UsuarioForm;
use App\Filament\Resources\Usuarios\Tables\UsuariosTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UsuarioResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Credenciales de acceso';

    protected static ?string $modelLabel = 'credencial';

    protected static ?string $pluralModelLabel = 'credenciales de acceso';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user instanceof User && ! $user->isPrimarySuperAdmin()) {
            $query->where('email', '!=', User::PRIMARY_SUPER_ADMIN_EMAIL);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if (! ($user?->isSuperAdmin() ?? false)) {
            return false;
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        if (! ($user?->isSuperAdmin() ?? false) || auth()->id() === $record->id) {
            return false;
        }

        return ! $record->isPrimarySuperAdmin() || $user->isPrimarySuperAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return UsuarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsuariosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsuarios::route('/'),
            'create' => CreateUsuario::route('/create'),
            'edit' => EditUsuario::route('/{record}/edit'),
        ];
    }
}
