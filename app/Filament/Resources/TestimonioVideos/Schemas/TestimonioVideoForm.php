<?php

namespace App\Filament\Resources\TestimonioVideos\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonioVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Video del carrusel')
                ->description('Estos videos aparecen en Inicio y en Comunidad / Protagonistas.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('titulo')
                                ->label('Título visible del video')
                                ->helperText('Ejemplo: Testimonio Alumni 2024.')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('orden')
                                ->label('Orden de aparición')
                                ->helperText('Número menor aparece primero.')
                                ->numeric()
                                ->default(0),
                        ]),
                    Select::make('video_media_path')
                        ->label('Elegir video desde /videosyfotos')
                        ->helperText('Recomendado si el video ya existe en la carpeta videosyfotos.')
                        ->options(fn () => VideoFotoOptions::videos())
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpanFull(),

                    FileUpload::make('video')
                        ->label('O subir video nuevo')
                        ->helperText('Si subes un archivo nuevo, ese video tendrá prioridad.')
                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])
                        ->disk('public')
                        ->directory('testimonios')
                        ->visibility('public')
                        ->maxSize(512000)
                        ->columnSpanFull(),
                    Toggle::make('activo')
                        ->label('Mostrar en el sitio')
                        ->default(true),
                ]),
        ]);
    }
}
