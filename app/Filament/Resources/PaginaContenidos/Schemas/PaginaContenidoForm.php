<?php

namespace App\Filament\Resources\PaginaContenidos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Formulario compartido por los editores de páginas.
 *
 * Los textos y ayudas cambian según el slug fijo para describir la sección
 * exacta que se modifica. Las imágenes se remiten al recurso centralizado
 * SeccionImagenResource para no mantener dos fuentes de verdad.
 */
class PaginaContenidoForm
{
    public static function configure(Schema $schema, ?string $fixedSlug = null): Schema
    {
        $isFixedPage = $fixedSlug !== null;
        $isFixedContactPage = $fixedSlug === 'contacto';
        $isFixedHomePage = $fixedSlug === 'inicio';

        // Este mapa es documentación visible para el administrador. Al cambiar
        // la función de una página, actualiza también su texto aquí.
        $pageSettings = match ($fixedSlug) {
            'inicio' => [
                'section' => 'Bloque para familias de Inicio',
                'description' => 'Estos textos aparecen junto a la imagen, debajo de los niveles educativos. Los banners se administran en Contenido del sitio → Banners de Inicio.',
                'subtitle_label' => 'Etiqueta sobre el título',
                'subtitle_help' => 'Texto breve sobre el encabezado. Ejemplo: Una comunidad que acompaña.',
                'title_label' => 'Título del bloque',
                'title_help' => 'Encabezado dirigido a madres, padres y tutores.',
                'description_label' => 'Texto de la sección',
                'description_help' => 'Párrafo que explica cómo Discovery® acompaña a los hijos y a sus familias.',
            ],
            'nosotros' => [
                'section' => 'Hero de Conócenos / Nosotros',
                'description' => 'Edita el encabezado superior de la página Conócenos. Sus imágenes se administran en Contenido del sitio → Imágenes del sitio.',
                'subtitle_label' => 'Etiqueta del hero',
                'subtitle_help' => 'Texto breve que aparece arriba del encabezado.',
                'title_label' => 'Título del hero',
                'title_help' => 'Encabezado principal de la página Conócenos.',
                'description_label' => 'Descripción del hero',
                'description_help' => 'Texto introductorio que acompaña al encabezado.',
            ],
            'oferta-academica' => [
                'section' => 'Encabezado de Oferta Educativa',
                'description' => 'Edita únicamente el encabezado superior de Oferta Educativa. Los niveles y sus imágenes se administran en sus apartados correspondientes.',
                'subtitle_label' => 'Etiqueta del encabezado',
                'subtitle_help' => 'Texto breve que aparece arriba del título.',
                'title_label' => 'Título del encabezado',
                'title_help' => 'Encabezado principal de Oferta Educativa.',
                'description_label' => 'Texto introductorio',
                'description_help' => 'Descripción que aparece debajo del título.',
            ],
            'protagonistas' => [
                'section' => 'Hero de Comunidad / Protagonistas',
                'description' => 'Edita el encabezado superior de la página Comunidad. Las imágenes se administran en Contenido del sitio → Imágenes del sitio.',
                'subtitle_label' => 'Etiqueta del hero',
                'subtitle_help' => 'Texto breve que aparece arriba del encabezado.',
                'title_label' => 'Título del hero',
                'title_help' => 'Encabezado principal de la página Comunidad.',
                'description_label' => 'Descripción del hero',
                'description_help' => 'Texto introductorio que acompaña al encabezado.',
            ],
            'contacto' => [
                'section' => 'Hero de Contacto',
                'description' => 'Edita el encabezado superior de Contacto. Las imágenes se administran en Contenido del sitio → Imágenes del sitio.',
                'subtitle_label' => 'Etiqueta del hero',
                'subtitle_help' => 'Texto pequeño arriba del título. Ejemplo: Informes y admisiones.',
                'title_label' => 'Título del hero',
                'title_help' => 'Encabezado grande de la vista Contacto.',
                'description_label' => 'Descripción del hero',
                'description_help' => 'Texto introductorio que acompaña al título.',
            ],
            default => [
                'section' => 'Texto principal de la página',
                'description' => 'Edita los títulos y textos visibles. Las imágenes de secciones específicas se cambian en Imágenes del sitio.',
                'subtitle_label' => 'Etiqueta pequeña',
                'subtitle_help' => 'Texto corto que aparece arriba del título.',
                'title_label' => 'Título principal',
                'title_help' => 'Encabezado grande de la página.',
                'description_label' => 'Descripción principal',
                'description_help' => 'Párrafo introductorio que acompaña al título.',
            ],
        };

        return $schema->components([
            Section::make($pageSettings['section'])
                ->description($pageSettings['description'])
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
                        ->label($pageSettings['subtitle_label'])
                        ->helperText($pageSettings['subtitle_help']),

                    TextInput::make('titulo')
                        ->label($pageSettings['title_label'])
                        ->helperText($pageSettings['title_help'])
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('descripcion')
                        ->label($pageSettings['description_label'])
                        ->helperText($pageSettings['description_help'])
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Imágenes de la página')
                ->description('Los archivos visuales se administran en un solo lugar para evitar imágenes duplicadas.')
                ->visible($isFixedPage)
                ->schema([
                    TextEntry::make('image_management_notice')
                        ->hiddenLabel()
                        ->state($isFixedHomePage
                            ? 'Ve a Contenido del sitio → Imágenes del sitio → Inicio para cambiar “sobre_nosotros”. El carrusel se administra en Contenido del sitio → Banners de Inicio.'
                            : 'Ve a Contenido del sitio → Imágenes del sitio y filtra la vista correspondiente a esta página.'
                        )
                        ->columnSpanFull(),
                ]),

            Section::make('Imágenes principales de esta página')
                ->description('Estos campos pertenecen al editor general. En las páginas individuales, usa Imágenes del sitio.')
                ->visible(! $isFixedPage)
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
                        ->visible(fn (Get $get): bool => $get('slug') !== 'inicio')
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
                ->visible(fn (Get $get): bool => $isFixedContactPage || ($fixedSlug === null && $get('slug') === 'contacto'))
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
