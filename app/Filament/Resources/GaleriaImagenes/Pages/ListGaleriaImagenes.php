<?php

namespace App\Filament\Resources\GaleriaImagenes\Pages;

use App\Filament\Resources\GaleriaImagenes\GaleriaImagenResource;
use App\Filament\Resources\GaleriaImagenes\Schemas\GaleriaImagenForm;
use App\Models\GaleriaImagen;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListGaleriaImagenes extends ListRecords
{
    protected static string $resource = GaleriaImagenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('subirVarias')
                ->label('Subir varias imágenes')
                ->icon('heroicon-o-arrow-up-tray')
                ->schema([
                    Select::make('nivel')
                        ->label('Nivel académico')
                        ->options(GaleriaImagenForm::levelOptions())
                        ->required()
                        ->native(false),
                    FileUpload::make('imagenes')
                        ->label('Imágenes')
                        ->helperText('Puedes seleccionar varios archivos y reordenarlos antes de guardar.')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('galerias')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $nextOrder = (int) GaleriaImagen::query()
                        ->where('nivel', $data['nivel'])
                        ->max('orden');

                    foreach ($data['imagenes'] as $path) {
                        GaleriaImagen::query()->create([
                            'nivel' => $data['nivel'],
                            'titulo' => pathinfo($path, PATHINFO_FILENAME),
                            'texto_alternativo' => GaleriaImagenForm::levelOptions()[$data['nivel']].' en Discovery®',
                            'imagen' => $path,
                            'orden' => ++$nextOrder,
                            'activo' => true,
                        ]);
                    }
                })
                ->successNotificationTitle('Imágenes agregadas a la galería'),

            CreateAction::make()
                ->label('Agregar una imagen'),
        ];
    }
}
