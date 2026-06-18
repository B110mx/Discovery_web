<?php

namespace App\Filament\Resources\SeccionImagenes\Schemas;

use App\Support\VideoFotoOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Editor central de posiciones de imagen del sitio.
 *
 * "vista + clave" debe coincidir con lo solicitado por PageController.
 */
class SeccionImagenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dónde se usará esta imagen')
                ->schema([
                    Select::make('vista')
                        ->label('Vista o página')
                        ->options([
                            'inicio' => 'Inicio',
                            'nosotros' => 'Nosotros',
                            'oferta-academica' => 'Oferta Educativa',
                            'preescolar' => 'Nivel - Kindergarten',
                            'primaria' => 'Nivel - Elementary',
                            'secundaria' => 'Nivel - Middle School',
                            'bachillerato' => 'Nivel - High School',
                            'ib-en-discovery' => 'Nivel - IB en Discovery®',
                            'pop-del-ib' => 'POP del IB',
                            'certificacion-de-ingles' => 'Certificación de Inglés',
                            'academias-vespertinas' => 'Academias Vespertinas',
                            'recursos-escolares' => 'Recursos escolares',
                            'contacto' => 'Contacto',
                        ])
                        ->searchable()
                        ->required(),

                    TextInput::make('clave')
                        ->label('Parte exacta de la vista')
                        ->helperText('Ejemplos: hero, logo, modelo_academico o sobre_nosotros. Carruseles, galerías, línea del tiempo, banners de Inicio y el hero dinámico de Protagonistas se administran en sus módulos especializados.')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('titulo')
                        ->label('Nombre para identificarla')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('orden')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),
                ])->columns(2),

            Section::make('Guía para saber qué imagen subir')
                ->schema([
                    Textarea::make('referencia')
                        ->label('Referencia visible cuando falta la imagen')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Archivo que se mostrará en el sitio')
                ->schema([
                    Select::make('respaldo_media_path')
                        ->label('Imagen desde /videosyfotos')
                        ->helperText('Selecciona el archivo actual de respaldo. Los grupos de imágenes se administran en sus módulos especializados.')
                        ->options(fn () => VideoFotoOptions::images())
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpanFull(),

                    FileUpload::make('imagen')
                        ->label('Subir imagen manualmente')
                        // PageController da prioridad a este archivo sobre el
                        // respaldo seleccionado en videosyfotos.
                        ->helperText('Opcional. Úsalo solo si la imagen no existe en /videosyfotos. Máximo 6 MB.')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('secciones')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->columnSpanFull(),

                    Toggle::make('activo')
                        ->label('Usar esta imagen en el sitio')
                        ->default(true),
                ]),
        ]);
    }
}
