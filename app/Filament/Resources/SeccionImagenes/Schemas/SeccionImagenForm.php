<?php

namespace App\Filament\Resources\SeccionImagenes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SeccionImagenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Donde se usara esta imagen')
                ->schema([
                    Select::make('vista')
                        ->label('Vista o pagina')
                        ->options([
                            'inicio' => 'Inicio',
                            'nosotros' => 'Nosotros',
                            'oferta-academica' => 'Oferta Educativa',
                            'protagonistas' => 'Comunidad / Protagonistas',
                            'preescolar' => 'Nivel - Preescolar',
                            'primaria' => 'Nivel - Primaria',
                            'secundaria' => 'Nivel - Secundaria',
                            'bachillerato' => 'Nivel - Bachillerato',
                            'contacto' => 'Contacto',
                        ])
                        ->searchable()
                        ->required(),

                    TextInput::make('clave')
                        ->label('Parte exacta de la vista')
                        ->helperText('Ejemplos: hero, sobre_nosotros, modelo_academico, comunidad_alumnos.')
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

            Section::make('Guia para saber que imagen subir')
                ->schema([
                    Textarea::make('referencia')
                        ->label('Referencia visible cuando falta la imagen')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Archivo que se mostrara en el sitio')
                ->schema([
                    FileUpload::make('imagen')
                        ->label('Imagen de esta seccion')
                        ->image()
                        ->disk('public')
                        ->directory('secciones')
                        ->visibility('public')
                        ->maxSize(8192)
                        ->columnSpanFull(),

                    Toggle::make('activo')
                        ->label('Usar esta imagen en el sitio')
                        ->default(true),
                ]),
        ]);
    }
}
