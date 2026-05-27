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
            Section::make('Texto principal de la pagina')
                ->description('Edita los titulos y textos visibles. Las imagenes se cambian en Imagenes del sitio.')
                ->schema([
                    $fixedSlug
                        ? Hidden::make('slug')->default($fixedSlug)->dehydrated()
                        : Select::make('slug')
                            ->label('Pagina')
                            ->helperText('Selecciona la vista donde se mostrara este contenido.')
                            ->options([
                                'inicio' => 'Inicio',
                                'nosotros' => 'Conocenos / Nosotros',
                                'oferta-academica' => 'Oferta Educativa',
                                'protagonistas' => 'Comunidad / Protagonistas',
                                'contacto' => 'Contacto',
                            ])
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->unique(ignoreRecord: true),

                    TextInput::make('subtitulo')
                        ->label('Etiqueta pequena')
                        ->helperText('Texto corto que aparece arriba del titulo. Ejemplo: Conocenos.'),

                    TextInput::make('titulo')
                        ->label('Titulo principal')
                        ->helperText('Encabezado grande de la pagina.')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('descripcion')
                        ->label('Descripcion principal')
                        ->helperText('Parrafo introductorio que acompana el titulo.')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Imagenes principales de esta pagina')
                ->description('Usa estos campos para las imagenes principales de la vista. Carruseles, linea del tiempo y videos se editan en sus propios apartados.')
                ->schema([
                    FileUpload::make('imagen_principal')
                        ->label('Imagen principal')
                        ->helperText('Imagen destacada o de apoyo principal de esta pagina.')
                        ->image()
                        ->disk('public')
                        ->directory('paginas')
                        ->visibility('public')
                        ->maxSize(8192)
                        ->columnSpanFull(),

                    FileUpload::make('imagen_secundaria')
                        ->label('Imagen secundaria')
                        ->helperText('Imagen adicional de apoyo, si la pagina la utiliza.')
                        ->image()
                        ->disk('public')
                        ->directory('paginas')
                        ->visibility('public')
                        ->maxSize(8192)
                        ->columnSpanFull(),
                ]),

            Section::make('Datos de contacto')
                ->description('Estos campos solo se usan en la pagina Contacto.')
                ->schema([
                    TextInput::make('direccion')
                        ->label('Direccion')
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
