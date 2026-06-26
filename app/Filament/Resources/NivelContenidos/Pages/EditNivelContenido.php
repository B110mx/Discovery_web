<?php

namespace App\Filament\Resources\NivelContenidos\Pages;

use App\Filament\Resources\NivelContenidos\NivelContenidoResource;
use App\Filament\Resources\Pages\EditRecord;

class EditNivelContenido extends EditRecord
{
    protected static string $resource = NivelContenidoResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['slug'] ?? null) === 'pop-del-ib' && empty($data['pop_rutas_visibles'])) {
            $data['pop_rutas_visibles'] = config(
                'colegio.niveles.definiciones.pop-del-ib.informacion.rutas_visibles',
                ['data_science', 'diseno_3d'],
            );
        }

        return $data;
    }
}
