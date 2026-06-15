<?php

namespace App\Filament\Resources\Eventos\Schemas;

use App\Models\Evento;
use App\Support\VideoFotoOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del evento')
                    ->description('Este registro alimenta el calendario mensual y, mientras esté vigente, el carrusel de Inicio.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('titulo')
                                    ->label('Título que ve el visitante')
                                    ->helperText('Ejemplo: Festival de primavera, junta informativa o DKMUN.')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('orden')
                                    ->label('Orden de aparición')
                                    ->helperText('En una misma fecha, el número menor aparece primero.')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('fecha_evento')
                                    ->label('Fecha del evento')
                                    ->helperText('Después de las 3:00 pm de ese día dejará el carrusel, pero seguirá visible en el calendario del mes.')
                                    ->required()
                                    ->native(false),
                                Select::make('nivel')
                                    ->label('Nivel o público')
                                    ->helperText('Define el color con el que se identifica en el calendario.')
                                    ->options(Evento::levelOptions())
                                    ->default('general')
                                    ->required()
                                    ->native(false),
                            ]),
                        Textarea::make('descripcion')
                            ->label('Descripción breve')
                            ->helperText('Texto corto que acompaña al evento en Inicio y en el resumen de próximas fechas.')
                            ->rows(4)
                            ->maxLength(500),
                        Toggle::make('activo')
                            ->label('Publicar en el sitio')
                            ->helperText('Al desactivarlo se oculta tanto del calendario como del carrusel de Inicio.')
                            ->default(true),
                    ]),

                Section::make('Imagen del evento')
                    ->description('Elige una imagen existente de /videosyfotos o sube una nueva. Si ambos campos tienen imagen, se usa la imagen subida manualmente.')
                    ->schema([
                        Select::make('imagen_media_path')
                            ->label('Elegir imagen desde /videosyfotos')
                            ->helperText('Recomendado si la imagen ya está en la carpeta videosyfotos.')
                            ->options(fn () => VideoFotoOptions::images())
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpanFull(),

                        FileUpload::make('imagen_url')
                            ->label('O subir imagen nueva')
                            ->helperText('Úsalo solo si la imagen no existe en /videosyfotos. Máximo 6 MB.')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->disk('public')
                            ->directory('eventos')
                            ->visibility('public')
                            ->maxSize(6144)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
