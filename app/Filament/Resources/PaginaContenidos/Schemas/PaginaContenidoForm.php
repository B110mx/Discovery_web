<?php

namespace App\Filament\Resources\PaginaContenidos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PaginaContenidoForm
{
    public static function configure(Schema $schema, ?string $fixedSlug = null): Schema
    {
        $isFixedContactPage = $fixedSlug === 'contacto';
        $isFixedHomePage = $fixedSlug === 'inicio';

        return $schema->components([
            Section::make(match (true) {
                $isFixedContactPage => 'Hero de Contacto',
                $isFixedHomePage => 'Textos de Inicio',
                default => 'Texto principal de la página',
            })
                ->description(match (true) {
                    $isFixedContactPage => 'Edita el título, etiqueta y descripción que aparecen en el encabezado de la vista Contacto.',
                    $isFixedHomePage => 'Edita los textos generales de Inicio. Los banners del hero se toman desde la carpeta Banner de inicio.',
                    default => 'Edita los títulos y textos visibles. Las imágenes de secciones específicas se cambian en Imágenes del sitio.',
                })
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
                            ->live()
                            ->required()
                            ->unique(ignoreRecord: true),

                    TextInput::make('subtitulo')
                        ->label($isFixedContactPage ? 'Etiqueta del hero' : 'Etiqueta pequeña')
                        ->helperText(match (true) {
                            $isFixedContactPage => 'Texto pequeño arriba del título. Ejemplo: Informes y admisiones.',
                            $isFixedHomePage => 'Este texto no controla los banners del hero; se conserva como texto editable de la página.',
                            default => 'Texto corto que aparece arriba del título. Ejemplo: Conócenos.',
                        }),

                    TextInput::make('titulo')
                        ->label($isFixedContactPage ? 'Título del hero' : 'Título principal')
                        ->helperText(match (true) {
                            $isFixedContactPage => 'Encabezado grande de la vista Contacto.',
                            $isFixedHomePage => 'Título general de la vista Inicio. Los textos dentro del banner dependen de la imagen subida a la carpeta.',
                            default => 'Encabezado grande de la página.',
                        })
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('descripcion')
                        ->label($isFixedContactPage ? 'Descripción del hero' : 'Descripción principal')
                        ->helperText(match (true) {
                            $isFixedContactPage => 'Texto introductorio que acompaña el título de Contacto.',
                            $isFixedHomePage => 'Descripción general de Inicio. No modifica los banners del hero.',
                            default => 'Párrafo introductorio que acompaña el título.',
                        })
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make(match (true) {
                $isFixedContactPage => 'Imágenes del hero de Contacto',
                $isFixedHomePage => 'Imagen de apoyo en Inicio',
                default => 'Imágenes principales de esta página',
            })
                ->description(match (true) {
                    $isFixedContactPage => 'La imagen principal aparece junto al texto del hero. La secundaria aparece en el bloque lateral de Contacto.',
                    $isFixedHomePage => 'El hero de Inicio usa los banners de la carpeta Banner de inicio. Aquí solo se edita la imagen lateral de la sección Sobre Nosotros.',
                    default => 'Usa estos campos para las imágenes principales de la vista. Carruseles, línea del tiempo y videos se editan en sus propios apartados.',
                })
                ->schema([
                    FileUpload::make('imagen_principal')
                        ->label(match (true) {
                            $isFixedContactPage => 'Imagen del hero',
                            $isFixedHomePage => 'Imagen lateral de Sobre Nosotros',
                            default => 'Imagen principal',
                        })
                        ->helperText(match (true) {
                            $isFixedContactPage => 'Imagen que aparece en el encabezado de Contacto.',
                            $isFixedHomePage => 'Esta imagen aparece en la sección Sobre Nosotros de Inicio, no en el hero.',
                            default => 'Imagen destacada o de apoyo principal de esta página.',
                        })
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('paginas')
                        ->visibility('public')
                        ->maxSize(6144)
                        ->columnSpanFull(),

                    FileUpload::make('imagen_secundaria')
                        ->label($isFixedContactPage ? 'Imagen secundaria de Contacto' : 'Imagen secundaria')
                        ->helperText($isFixedContactPage
                            ? 'Imagen de apoyo que aparece junto a la información de contacto.'
                            : 'Imagen adicional de apoyo, si la página la utiliza.'
                        )
                        ->visible(fn (Get $get): bool => $fixedSlug !== 'inicio' && ! ($fixedSlug === null && $get('slug') === 'inicio'))
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
                ->visible(fn (Get $get): bool => $fixedSlug === 'contacto' || ($fixedSlug === null && $get('slug') === 'contacto'))
                ->schema([
                    TextInput::make('direccion')
                        ->label('Dirección')
                        ->columnSpanFull(),

                    Grid::make(3)
                        ->schema([
                            TextInput::make('telefono_principal')
                                ->label('Teléfono principal'),

                            TextInput::make('telefono_secundario')
                                ->label('Teléfono secundario'),

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
