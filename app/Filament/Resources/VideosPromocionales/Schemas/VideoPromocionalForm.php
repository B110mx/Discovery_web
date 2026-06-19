<?php

namespace App\Filament\Resources\VideosPromocionales\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VideoPromocionalForm
{
    public static function levelOptions(): array
    {
        return [
            'preescolar' => 'Kindergarten',
            'primaria' => 'Elementary',
            'secundaria' => 'Middle School',
            'bachillerato' => 'High School',
            'pop-del-ib' => 'POP del IB®',
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ubicación y presentación')
                ->description('Asigna el video a una página, controla su orden y decide si se muestra en el sitio.')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('nivel')
                            ->label('Página del nivel')
                            ->options(self::levelOptions())
                            ->required()
                            ->native(false),
                        TextInput::make('titulo')
                            ->label('Título visible')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('orden')
                            ->label('Orden de aparición')
                            ->numeric()
                            ->default(0),
                        Toggle::make('activo')
                            ->label('Mostrar en el sitio')
                            ->default(true),
                    ]),
                ]),

            Section::make('Video')
                ->description('Selecciona un archivo existente o sube uno nuevo. El archivo subido tendrá prioridad.')
                ->schema([
                    Select::make('video_media_path')
                        ->label('Elegir video desde /videosyfotos')
                        ->options(fn () => VideoFotoOptions::videos())
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->requiredWithout('video'),
                    FileUpload::make('video')
                        ->label('O subir video nuevo')
                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                        ->disk('public')
                        ->directory('videos-promocionales')
                        ->visibility('public')
                        ->maxSize(204800)
                        ->requiredWithout('video_media_path'),
                ]),

            Section::make('Portada')
                ->description('La portada evita descargar el video hasta que el visitante decide reproducirlo.')
                ->schema([
                    Select::make('portada_media_path')
                        ->label('Elegir portada desde /videosyfotos')
                        ->options(fn () => VideoFotoOptions::images())
                        ->searchable()
                        ->preload()
                        ->native(false),
                    FileUpload::make('portada')
                        ->label('O subir portada nueva')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('portadas-videos-promocionales')
                        ->visibility('public')
                        ->maxSize(4096),
                ]),
        ]);
    }
}
