<?php

namespace App\Filament\Resources\PaginaContenidos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaginaContenidoForm
{
    public static function configure(Schema $schema, ?string $fixedSlug = null): Schema
    {
        return $schema->components([
            Section::make('Texto principal de la página')
                ->description('Edita los títulos y textos visibles. Las imágenes se cambian en Imágenes del sitio.')
                ->schema([
                    $fixedSlug
                        ? Hidden::make('slug')->default($fixedSlug)->dehydrated()
                        : Select::make('slug')
                            ->label('Página')
                            ->helperText('Selecciona la vista donde se mostrará este contenido.')
                            ->options([
                                'inicio' => 'Inicio',
                                'nosotros' => 'Conócenos / Nosotros',
                                'oferta-academica' => 'Oferta Educativa',
                                'protagonistas' => 'Comunidad / Protagonistas',
                                'contacto' => 'Contacto',
                            ])
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->unique(ignoreRecord: true),

                    TextInput::make('subtitulo')
                        ->label('Etiqueta pequeña')
                        ->helperText('Texto corto que aparece arriba del título. Ejemplo: Conócenos.'),

                    TextInput::make('titulo')
                        ->label('Título principal')
                        ->helperText('Encabezado grande de la página.')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('descripcion')
                        ->label('Descripción principal')
                        ->helperText('Párrafo introductorio que acompaña el título.')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Imágenes principales de esta página')
                ->description('Usa estos campos para las imágenes principales de la vista. Carruseles, línea del tiempo y videos se editan en sus propios apartados.')
                ->schema([
                    FileUpload::make('imagen_principal')
                        ->label('Imagen principal')
                        ->helperText('Imagen destacada o de apoyo principal de esta página.')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('paginas')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->columnSpanFull(),

                    FileUpload::make('imagen_secundaria')
                        ->label('Imagen secundaria')
                        ->helperText('Imagen adicional de apoyo, si la página la utiliza.')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('paginas')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->columnSpanFull(),
                ]),

            Section::make('Datos de contacto')
                ->description('Estos campos solo se usan en la página Contacto.')
                ->schema([
                    TextInput::make('direccion')
                        ->label('Dirección')
                        ->columnSpanFull(),

                    Grid::make(3)
                        ->schema([
                            TextInput::make('telefono_principal')
                                ->label('Telefono principal'),

                            TextInput::make('telefono_secundario')
                                ->label('Telefono secundario'),

                            TextInput::make('correo')
                                ->label('Correo')
                                ->email(),
                        ]),

                    Textarea::make('mapa_url')
                        ->label('Mapa de Google')
                        ->helperText('Puedes pegar el iframe completo de Google Maps o solo la URL del atributo src.')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
