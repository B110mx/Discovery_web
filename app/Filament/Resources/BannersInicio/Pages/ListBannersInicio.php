<?php

namespace App\Filament\Resources\BannersInicio\Pages;

use App\Filament\Resources\BannersInicio\BannerInicioResource;
use App\Models\BannerInicio;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;

class ListBannersInicio extends ListRecords
{
    protected static string $resource = BannerInicioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('subirVarios')
                ->label('Subir varios banners')
                ->icon('heroicon-o-arrow-up-tray')
                ->schema([
                    FileUpload::make('imagenes')
                        ->label('Banners')
                        ->helperText('Puedes seleccionar varios archivos y reordenarlos antes de guardar.')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('banners-inicio')
                        ->visibility('public')
                        ->maxSize(10240)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $nextOrder = (int) BannerInicio::query()->max('orden');

                    foreach ($data['imagenes'] as $path) {
                        $title = pathinfo($path, PATHINFO_FILENAME);

                        BannerInicio::query()->create([
                            'titulo' => $title,
                            'texto_alternativo' => $title,
                            'imagen' => $path,
                            'orden' => ++$nextOrder,
                            'activo' => true,
                        ]);
                    }
                })
                ->successNotificationTitle('Banners agregados al carrusel'),

            CreateAction::make()
                ->label('Agregar un banner'),
        ];
    }
}
