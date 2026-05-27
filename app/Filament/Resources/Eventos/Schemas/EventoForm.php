<?php

namespace App\Filament\Resources\Eventos\Schemas;

use App\Support\VideoFotoOptions;
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
                Section::make('Texto del evento en Inicio')
                    ->description('Edita el texto que aparece en el carrusel de la pagina de inicio.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('titulo')
                                    ->label('Titulo que ve el visitante')
                                    ->helperText('Ejemplo: Evento Preescolar, Feria de universidades, DKMUN.')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('orden')
                                    ->label('Orden de aparicion')
                                    ->helperText('Numero menor aparece primero.')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Textarea::make('descripcion')
                            ->label('Descripcion breve')
                            ->helperText('Texto corto que acompana el evento en la pagina de inicio.')
                            ->rows(4)
                            ->maxLength(500),
                        Toggle::make('activo')
                            ->label('Mostrar este evento en el carrusel')
                            ->default(true),
                    ]),

                Section::make('Imagen del evento')
                    ->description('Elige una imagen existente de /videosyfotos o sube una nueva. Si ambos campos tienen imagen, se usa la imagen subida manualmente.')
                    ->schema([
                        Select::make('imagen_media_path')
                            ->label('Elegir imagen desde /videosyfotos')
                            ->helperText('Recomendado: usa esta opcion si la imagen ya esta en la carpeta videosyfotos.')
                            ->options(fn () => VideoFotoOptions::images())
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpanFull(),

                        FileUpload::make('imagen_url')
                            ->label('O subir imagen nueva')
                            ->helperText('Usalo solo si la imagen no existe en /videosyfotos. Formato recomendado: horizontal o cartel legible.')
                            ->image()
                            ->disk('public')
                            ->directory('eventos')
                            ->visibility('public')
                            ->maxSize(8192)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
