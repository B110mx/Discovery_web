<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Caché público
    |--------------------------------------------------------------------------
    |
    | SiteCache usa estos alias. Cambiar el valor de una clave también sirve
    | para invalidar datos antiguos después de modificar su estructura.
    |
    */
    'cache' => [
        'hours' => 12,
        'keys' => [
            'inicio_eventos' => 'inicio_eventos_v2',
            'inicio_banners' => 'inicio_banners_v1',
            'inicio_testimonios' => 'inicio_testimonios_v2',
            'nosotros_historia' => 'nosotros_historia_v1',
            'protagonistas_testimonios' => 'protagonistas_testimonios_v1',
            'recursos_listas_utiles' => 'recursos_listas_utiles_v2',
            'niveles_contenido' => 'niveles_contenido_v1',
            'contacto_pagina_id' => 'contacto_pagina_id',
            'pagina_contenido.inicio' => 'pagina_contenido_inicio',
            'pagina_contenido.nosotros' => 'pagina_contenido_nosotros',
            'pagina_contenido.oferta-academica' => 'pagina_contenido_oferta_academica',
            'pagina_contenido.protagonistas' => 'pagina_contenido_protagonistas',
            'pagina_contenido.contacto' => 'pagina_contenido_contacto',
            'galeria.preescolar' => 'galeria_preescolar_actuales_v1',
            'galeria.primaria' => 'galeria_primaria_actuales_v1',
            'galeria.secundaria' => 'galeria_secundaria_actuales_v1',
            'galeria.bachillerato' => 'galeria_bachillerato_actuales_v1',
            'galeria.ib-en-discovery' => 'galeria_ib-en-discovery',
            'galeria.certificacion-de-ingles' => 'galeria_certificacion-de-ingles',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Biblioteca multimedia
    |--------------------------------------------------------------------------
    |
    | El disco se declara en config/filesystems.php y apunta a videosyfotos.
    |
    */
    'media' => [
        'disk' => 'videosyfotos',
        'video_extensions' => ['mp4', 'mov', 'webm', 'm4v'],
        'image_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],

    // Valores de respaldo cuando la página Contacto aún no está en la BD.
    'contacto' => [
        'titulo' => 'Contacto',
        'subtitulo' => 'Informes y admisiones',
        'descripcion' => 'Compártenos tus datos y nos pondremos en contacto contigo para darte más información sobre Colegio Discovery®.',
        'direccion' => 'C. Vía Puebla 3611, José Garci-crespo, Residencial Cinco Bosques, 75855 Tehuacán, Pue., México',
        'telefono_principal' => '(238) 688 11 79',
        'telefono_secundario' => '(238) 102 18 17',
        'correo' => env('CONTACTO_CORREO', 'informes@colegio-discovery.edu.mx'),
        'mapa_embed_url' => 'https://www.google.com/maps?q=Colegio%20Discovery%20Tehuacan%4018.4847219%2C-97.4150801&z=17&output=embed',
        'mapa_url' => 'https://www.google.com/maps/place/Colegio+Discovery+Tehuacan/@18.484529,-97.417467,17z/data=!4m6!3m5!1s0x85c5a32ab4097387:0x64c0781c4b44b94c!8m2!3d18.4847219!4d-97.4150801!16s%2Fg%2F1tcxk2pr?hl=es-US&entry=ttu&g_ep=EgoyMDI2MDUyMC4wIKXMDSoASAFQAw%3D%3D',
        'whatsapp_numero' => '+52 238 102 1817',
        'whatsapp_mensaje' => 'Hola, me gustaria recibir informacion sobre Colegio Discovery®.',
    ],

    // Contenido estructural de Inicio que no se edita como PaginaContenido.
    'inicio' => [
        'eventos_default' => [
            [
                'titulo' => 'Evento Kindergarten',
                'descripcion' => 'Actividades próximas para nuestras familias de Kindergarten.',
                'media_path' => 'Portadas/IMG_5775.JPG',
            ],
            [
                'titulo' => 'Evento Elementary',
                'descripcion' => 'Experiencias, proyectos y encuentros para nuestros Explorers de Elementary.',
                'media_path' => 'Elementary fotos actuales/IMG_5684.JPG',
            ],
            [
                'titulo' => 'Evento Middle School',
                'descripcion' => 'Actividades académicas y de comunidad para Middle School.',
                'media_path' => 'Middle fotos actuales/IMG_5867.JPG',
            ],
        ],
        'logos_niveles' => [
            'preescolar' => 'Logos de niveles educativos/preescolar.png',
            'primaria' => 'Logos de niveles educativos/primaria.png',
            'secundaria' => 'Logos de niveles educativos/secundaria.png',
            'bachillerato' => 'Logos de niveles educativos/bachillerato.png',
        ],
    ],

    'oferta_academica' => [
        'preescolar' => [
            'color' => 'lime',
            'logo_path' => 'Logos de niveles educativos/logo preescolar.png',
            'imagen_clave' => 'preescolar',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - Kindergarten',
                'referencia' => 'Imagen destacada para Kindergarten en la vista Oferta Educativa.',
                'media_path' => 'Portadas/IMG_5775.JPG',
            ],
        ],
        'primaria' => [
            'color' => 'red',
            'logo_path' => 'Logos de niveles educativos/logo primaria.png',
            'imagen_clave' => 'primaria',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - Elementary',
                'referencia' => 'Imagen destacada para Elementary en la vista Oferta Educativa.',
                'media_path' => 'Elementary fotos actuales/IMG_5684.JPG',
            ],
        ],
        'secundaria' => [
            'color' => 'blue',
            'logo_path' => 'Logos de niveles educativos/logo secundaria.png',
            'imagen_clave' => 'secundaria',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - Middle School',
                'referencia' => 'Imagen destacada para Middle School en la vista Oferta Educativa.',
                'media_path' => 'Middle fotos actuales/IMG_5867.JPG',
            ],
        ],
        'bachillerato' => [
            'color' => 'green',
            'logo_path' => 'Logos de niveles educativos/logo bachillerato.png',
            'imagen_clave' => 'bachillerato',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - High School',
                'referencia' => 'Imagen destacada para High School en la vista Oferta Educativa.',
                'media_path' => 'High (Prepa)/BACHILLERATO-MOSAICO-5-scaled.jpg',
            ],
        ],
        'ib-en-discovery' => [
            'color' => 'amber',
            'logo_path' => 'Logos de niveles educativos/Logo IB cl.jpeg',
            'imagen_clave' => 'ib',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - IB® en Discovery®',
                'referencia' => 'Imagen destacada para IB® en Discovery® en la vista Oferta Educativa.',
                'media_path' => 'Logos de niveles educativos/Logo IB cl.jpeg',
            ],
        ],
        'pop-del-ib' => [
            'color' => 'blue',
            'logo_path' => 'Logos de niveles educativos/Logo IB cl.jpeg',
            'imagen_clave' => 'pop_ib',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - POP del IB®',
                'referencia' => 'Imagen destacada para presentar el Programa de Orientación Profesional del IB® en la vista Oferta Educativa. Se recomienda una fotografía horizontal de Explorers de High School trabajando en un proyecto.',
            ],
        ],
        'certificacion-de-ingles' => [
            'color' => 'sky',
            'logo_path' => null,
            'imagen_clave' => 'certificacion_ingles',
            'imagen_default' => [
                'titulo' => 'Oferta Educativa - Certificación de Inglés',
                'referencia' => 'Imagen destacada para Certificación de Inglés en la vista Oferta Educativa.',
                'media_path' => 'Certificacion fotos/unicam.jpg',
            ],
        ],
    ],

    'protagonistas' => [
        'niveles' => [
            ['titulo' => 'Kindergarten', 'clave' => 'preescolar', 'color' => 'bg-lime-500', 'referencia' => 'Imagen para representar Kindergarten dentro de Comunidad.', 'media_path' => 'Portadas/IMG_5775.JPG'],
            ['titulo' => 'Elementary', 'clave' => 'primaria', 'color' => 'bg-red-600', 'referencia' => 'Imagen para representar Elementary dentro de Comunidad.', 'media_path' => 'Elementary fotos actuales/IMG_5684.JPG'],
            ['titulo' => 'Middle School', 'clave' => 'secundaria', 'color' => 'bg-blue-700', 'referencia' => 'Imagen para representar Middle School dentro de Comunidad.', 'media_path' => 'Middle fotos actuales/IMG_5867.JPG'],
            ['titulo' => 'High School', 'clave' => 'bachillerato', 'color' => 'bg-green-600', 'referencia' => 'Imagen para representar High School dentro de Comunidad.', 'media_path' => 'High fotos actuales/1234.jpg'],
        ],
        'protagonistas' => [
            'alumnos' => ['titulo' => 'Comunidad - Explorers', 'color' => 'bg-red-600', 'referencia' => 'Imagen para representar a los Explorers en la sección Quienes hacen viva nuestra comunidad.', 'media_path' => 'Comunidad Explorers/1234.jpg', 'media_directory' => 'Comunidad Explorers'],
            'docentes' => ['titulo' => 'Comunidad - Docentes', 'color' => 'bg-blue-700', 'referencia' => 'Imagen para representar a docentes en la sección Quienes hacen viva nuestra comunidad.', 'media_path' => 'Fotos Docentes/1.png', 'media_directory' => 'Fotos Docentes'],
            'padres' => ['titulo' => 'Comunidad - Familias', 'color' => 'bg-lime-500', 'referencia' => 'Imagen para representar a familias en la sección Quienes hacen viva nuestra comunidad.', 'media_path' => 'Fotos Padres de Familia/3.png', 'media_directory' => 'Fotos Padres de Familia'],
            'alumni' => ['titulo' => 'Comunidad - Alumni', 'color' => 'bg-green-600', 'referencia' => 'Imagen para representar a alumni en la sección Quienes hacen viva nuestra comunidad.', 'media_path' => 'Fotos Alumni/001.JPG', 'media_directory' => 'Fotos Alumni'],
        ],
    ],

    'niveles' => [
        'carpetas_galeria' => [
            'preescolar' => 'Kinder fotos actuales',
            'primaria' => 'Elementary fotos actuales',
            'secundaria' => 'Middle fotos actuales',
            'bachillerato' => 'High fotos actuales',
        ],
        'definiciones' => [
            'preescolar' => [
                'logo_path' => 'Logos de niveles educativos/logo preescolar.png',
                'logo_extendido_path' => 'Logos de niveles educativos/Preescolar-extendido.png',
                'hoja_informativa_path' => 'Hojas informativas/Kinder _ hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo kínder.png',
                'informacion' => [
                    'destacados' => [
                        ['titulo' => 'Acompañamiento', 'texto' => 'Grupos reducidos de 14 a 24 Explorers, bienestar emocional y seguimiento cercano en cada etapa.'],
                        ['titulo' => 'Aprendizaje', 'texto' => 'Neuromotor, Audiomotor, Spark y Bancubi fortalecen lenguaje, pensamiento lógico y madurez neurológica.'],
                        ['titulo' => 'Comunidad', 'texto' => 'Crecen en un entorno de amor, respeto y seguridad, con valores IB® y mentalidad abierta.'],
                    ],
                    'puntos' => [
                        'Programas de neuroaprendizaje: Neuromotor, Audiomotor y Spark.',
                        'Aprendizaje natural en inglés y español con estrategias multisensoriales.',
                        'Programas alineados al Bachillerato Internacional® desde los primeros años.',
                        'Grupos reducidos de 14 a 24 Explorers con atención personalizada.',
                        'Bienestar emocional, expresión oral y acompañamiento cercano.',
                    ],
                    'secciones' => [
                        ['titulo' => 'Estimulación para cuerpo y mente', 'texto' => 'Neuromotor y Audiomotor fortalecen coordinación, atención, creatividad, autodominio físico y madurez neurológica.'],
                        ['titulo' => 'Aprendizaje en dos idiomas', 'texto' => 'Spark impulsa lectoescritura, vocabulario, comprensión y expresión en inglés y español desde los primeros años.'],
                        ['titulo' => 'Pensamiento lógico jugando', 'texto' => 'Con Bancubi y pensamiento matemático, aprenden matemáticas de forma concreta: clasifican, ordenan, comparan y resuelven problemas manipulando objetos reales.'],
                        ['titulo' => 'Crecimiento en comunidad', 'texto' => 'Crecen en un entorno de amor, respeto y seguridad, con valores del perfil IB®, empatía y mentalidad abierta.'],
                    ],
                    'experiencias' => ['Convivio de Navidad', 'Clase Neuromotora', 'Visita de Pre-primary a Elementary', 'Profesiones y Oficios', 'Festival de la Primavera', 'Día del niño y la niña'],
                ],
            ],
            'primaria' => [
                'logo_path' => 'Logos de niveles educativos/logo primaria.png',
                'logo_extendido_path' => 'Logos de niveles educativos/Primaria-extendido.png',
                'hoja_informativa_path' => 'Hojas informativas/Elementary _hoja informativa.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo elementary.png',
                'informacion' => [
                    'destacados' => [
                        ['titulo' => 'Acompañamiento', 'texto' => 'Atención académica y emocional para que cada Explorer avance con confianza, curiosidad y autonomía.'],
                        ['titulo' => 'Aprendizaje', 'texto' => 'Tres bloques diarios en inglés, francés desde 5° grado, tecnología, arte y unidades de indagación IB®.'],
                        ['titulo' => 'Comunidad', 'texto' => 'Deporte diario, academias, mini olimpiadas y proyectos que fortalecen colaboración y pertenencia.'],
                    ],
                    'puntos' => [
                        'Tres bloques diarios en inglés con materias como Science y Civics.',
                        'Francés desde 5° grado de Elementary como tercer idioma.',
                        'Mock Cambridge desde 3° grado de Elementary para practicar reading, writing, listening y speaking.',
                        'Unidades de indagación del modelo IB® para investigar, cuestionar y resolver problemas reales.',
                        'Artes, deporte diario y academias vespertinas como fútbol, básquetbol, ajedrez, origami, atletismo y UrbanKids.',
                    ],
                    'secciones' => [
                        ['titulo' => 'Formación bilingüe y trilingüe', 'texto' => 'Aprenden inglés en un entorno de inmersión y suman francés para ampliar su visión como ciudadanos globales.'],
                        ['titulo' => 'Perfil IB® y pensamiento global', 'texto' => 'Trabajan atributos como ser pensadores, informados, comunicadores, solidarios y de mentalidad abierta.'],
                        ['titulo' => 'Pensamiento lógico y algorítmico', 'texto' => 'Tecnología y concursos de programación fortalecen análisis, secuenciación, creatividad y toma de decisiones.'],
                        ['titulo' => 'Cuerpo, atención y creatividad', 'texto' => 'Neuromotor continúa en Elementary junto con deporte diario y artes integradas para una formación equilibrada.'],
                    ],
                    'experiencias' => ['Mini Olimpiadas', 'Obras de teatro', 'Festival Día del Niño', 'Graduación', 'Unidades de Indagación'],
                ],
            ],
            'secundaria' => [
                'logo_path' => 'Logos de niveles educativos/logo secundaria.png',
                'logo_extendido_path' => 'Logos de niveles educativos/Secundaria-extendido.png',
                'hoja_informativa_path' => 'Hojas informativas/Secundaria _hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo middle.png',
                'informacion' => [
                    'destacados' => [
                        ['titulo' => 'Acompañamiento', 'texto' => 'Preceptoría, bienestar emocional y habilidades de autogestión para una etapa de cambios importantes.'],
                        ['titulo' => 'Aprendizaje', 'texto' => 'Inglés, español y francés a nivel básico, proyectos interdisciplinarios y enfoques de aprendizaje para pensar e investigar mejor.'],
                        ['titulo' => 'Comunidad', 'texto' => 'Deporte diario, DKMUN, WASP, arte y clanes para desarrollar iniciativa, identidad y trabajo en equipo.'],
                    ],
                    'puntos' => [
                        'Inglés y español, además de francés a nivel básico, con más de 15 bloques de idiomas a la semana.',
                        'Enfoques de aprendizaje: pensamiento, autogestión, comunicación, investigación y habilidades sociales.',
                        'Proyectos interdisciplinarios con mentalidad internacional.',
                        'Deporte diario: futbol, basquetbol, voleibol o tenis.',
                        'Acompañamiento integral con bienestar emocional y preceptoría durante toda la trayectoria escolar.',
                    ],
                    'secciones' => [
                        ['titulo' => 'Idiomas para comunicarse con el mundo', 'texto' => 'Viven el inglés todos los días e incorporan francés a nivel básico. La certificación de Lengua B del Programa del Diploma es obligatoria, mientras que la certificación Cambridge es opcional.'],
                        ['titulo' => 'Deporte diario', 'texto' => 'La práctica deportiva fortalece salud física, disciplina, autoestima, trabajo en equipo y hábitos saludables.'],
                        ['titulo' => 'Proyectos globales', 'texto' => 'Programas como DKMun y WASP desarrollan investigación, debate, diplomacia e iniciativa desde edades tempranas.'],
                        ['titulo' => 'Talentos y pasiones', 'texto' => 'Exploran arte, ciencia, ferias escolares y experiencias que conectan lo aprendido con el mundo real.'],
                    ],
                    'experiencias' => ['Día del Arte', 'Deportes', 'Aniversario', 'Clanes', 'Presentaciones'],
                ],
            ],
            'bachillerato' => [
                'logo_path' => 'Logos de niveles educativos/logo bachillerato.png',
                'logo_extendido_path' => 'Logos de niveles educativos/Bachillerato-extendido.png',
                'hoja_informativa_path' => 'Hojas informativas/High school _ hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo high.png',
                'informacion' => [
                    'destacados' => [
                        ['titulo' => 'Acompañamiento', 'texto' => 'Orientación vocacional, asesoría para admisiones y seguimiento individual rumbo a universidad.'],
                        ['titulo' => 'Aprendizaje', 'texto' => 'Programa del Diploma IB®, Teoría del Conocimiento, CAS, idiomas y pensamiento crítico aplicado.'],
                        ['titulo' => 'Comunidad', 'texto' => 'DKMUN, proyectos IB®, arte, deporte diario e impacto social para fortalecer iniciativa y expresión.'],
                    ],
                    'puntos' => [
                        'High School con Programa IB® oficial y validez internacional.',
                        'Programa del Diploma y desarrollo del perfil IB®.',
                        'Inglés y español, además de francés a nivel básico, con 15 bloques semanales en inglés.',
                        'Más del 70% de egresados obtiene becas en universidades de México y el extranjero.',
                        'Orientación vocacional, asesoría para admisiones y seguimiento individual.',
                    ],
                    'secciones' => [
                        ['titulo' => 'Pensamiento global', 'texto' => 'Teoría del Conocimiento y CAS fortalecen pensamiento crítico, visión internacional y capacidad de tomar decisiones.'],
                        ['titulo' => 'Certificaciones de inglés y formación en francés', 'texto' => 'La formación en inglés prepara para certificaciones y fortalece la comunicación académica; el francés se aprende a nivel básico como tercer idioma.'],
                        ['titulo' => 'Debates y diplomacia', 'texto' => 'DKMUN permite negociar, argumentar, proponer soluciones y representar países en asuntos internacionales.'],
                        ['titulo' => 'Impacto social y expresión', 'texto' => 'CAS, arte y deporte diario hacen que cada Explorer participe en proyectos culturales, sociales, ambientales o deportivos.'],
                    ],
                    'experiencias' => ['Expo Arte', 'Graduación', 'Día del Explorer', 'DKMUN', 'Proyectos IB®'],
                ],
            ],
            'ib-en-discovery' => [
                'logo_path' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png',
                'logo_extendido_path' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png',
                'usar_placeholder_hero' => true,
                'hero_media_path' => 'Logos principales/IB Internacional.png',
                'ocultar_galeria' => true,
                'layout' => 'ib',
                'informacion' => [
                    'aviso' => [
                        'titulo' => 'Transición al POP del IB®',
                        'texto' => '12.º grado será el último en cursar el Programa del Diploma. A partir de la siguiente generación, se implementará el Programa de Orientación Profesional (POP) del IB®.',
                        'enlace_texto' => 'Conoce el POP del IB®',
                    ],
                    'destacados' => [
                        ['titulo' => 'Habilidades', 'texto' => 'Pensamiento, autogestión, comunicación, investigación y habilidades sociales para aprender con autonomía.'],
                        ['titulo' => 'Perfil IB®', 'texto' => 'Formación de Explorers con capacidades humanas que van más allá del éxito académico.'],
                        ['titulo' => 'Programa del Diploma', 'texto' => 'Un programa riguroso y equilibrado para Explorers de 16 a 19 años, reconocido por universidades del mundo.'],
                    ],
                    'puntos' => [
                        'Desarrollo de pensamiento crítico, pensamiento creativo y transferencia del aprendizaje.',
                        'Autogestión con organización, manejo del tiempo, motivación y habilidades afectivas.',
                        'Comunicación para expresar ideas, escuchar, colaborar y construir acuerdos.',
                        'Investigación para comparar, contrastar, validar y priorizar información.',
                        'Habilidades sociales para relacionarse positivamente y resolver conflictos.',
                    ],
                    'imagen_enfoque' => [
                        'titulo' => 'Enfoque de aprendizaje Pacis',
                        'referencia' => 'Imagen de apoyo para los enfoques de aprendizaje IB®.',
                        'media_path' => 'Enfoque de aprendizaje/Enfoque de aprendizaje Pacis.png',
                    ],
                    'secciones' => [
                        ['titulo' => 'Perfil de la comunidad de aprendizaje IB®', 'texto' => 'El perfil IB® expresa capacidades y responsabilidades humanas que buscan formar Explorers respetuosos consigo mismos, con las demás personas y con el mundo que les rodea.'],
                        ['titulo' => 'Programa del Diploma (PD)', 'texto' => 'Colegio Discovery® está acreditado por la Organización del Bachillerato Internacional® para impartir el Programa del Diploma, una propuesta que favorece amplitud y profundidad académica junto con desarrollo físico, intelectual, emocional y ético.'],
                        ['titulo' => 'Qué ofrece el PD a los Explorers', 'texto' => 'El programa promueve excelencia académica, estudio de al menos dos lenguas, desarrollo integral y exploración de la naturaleza del conocimiento mediante Teoría del Conocimiento.'],
                        ['titulo' => 'Currículo del Programa del Diploma', 'texto' => 'El currículo se integra por el tronco común del PD y seis grupos de asignaturas. Sus componentes troncales amplían la experiencia educativa y retan a los Explorers a aplicar conocimientos y habilidades.'],
                        ['titulo' => 'Perfil de nuestros egresados', 'texto' => 'Nuestros egresados se desenvuelven competitivamente en inglés y español, cuentan con preparación académica para certificaciones, enfrentan desafíos con mentalidad abierta y son ciudadanos globales con visión internacional.'],
                    ],
                    'cierre' => [
                        'titulo' => 'Colegio autorizado por el Bachillerato Internacional®',
                        'texto' => 'Colegio Discovery® cuenta con autorización oficial para impartir el Programa del Diploma. La ficha institucional puede consultarse directamente en el sitio del IB®.',
                    ],
                    'certificacion_url' => 'https://ibo.org/es/programmes/find-an-ib-school/ibla/c/colegio-discovery-nuevo-millenium/',
                    'certificacion_texto' => 'Consultar autorización oficial en el sitio del IB®',
                    'nombre_registrado_ib' => 'Ante el Bachillerato Internacional®, estamos registrados como: Colegio Discovery Nuevo Millenium.',
                    'experiencias' => ['Pensamiento crítico', 'Teoría del Conocimiento', 'CAS', 'Investigación', 'Dos lenguas', 'Mentalidad internacional'],
                ],
            ],
            'pop-del-ib' => [
                'logo_path' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png',
                'logo_extendido_path' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png',
                'usar_placeholder_hero' => true,
                'ocultar_galeria' => true,
                'layout' => 'pop',
                'informacion' => [
                    'eyebrow' => 'Programa de Orientación Profesional del IB®',
                    'destacados' => [
                        ['titulo' => 'Autoconocimiento', 'texto' => 'Cada Explorer reconoce sus intereses, talentos y fortalezas para tomar decisiones más informadas sobre su futuro.'],
                        ['titulo' => 'Experiencia práctica', 'texto' => 'Los aprendizajes se conectan con proyectos, tecnología, servicio y situaciones del mundo real.'],
                        ['titulo' => 'Proyección universitaria', 'texto' => 'La trayectoria fortalece el perfil académico, personal y profesional para la siguiente etapa.'],
                    ],
                    'habilidades' => [
                        'Pensamiento crítico',
                        'Comunicación',
                        'Investigación',
                        'Liderazgo',
                        'Colaboración',
                        'Responsabilidad social',
                        'Creatividad',
                        'Toma de decisiones',
                    ],
                    'componentes' => [
                        ['titulo' => 'Aprendizaje-servicio', 'texto' => 'Participación en proyectos que responden a necesidades reales de la comunidad y fortalecen el compromiso social.'],
                        ['titulo' => 'Desarrollo de la lengua', 'texto' => 'Crecimiento de habilidades comunicativas en un contexto internacional para expresarse, comprender y conectar con otras culturas.'],
                        ['titulo' => 'Habilidades personales y profesionales', 'texto' => 'Organización, liderazgo, trabajo en equipo, comunicación asertiva y pensamiento reflexivo para la vida universitaria y profesional.'],
                        ['titulo' => 'Proyecto de reflexión', 'texto' => 'Investigación y reflexión sobre temas relacionados con el área de interés profesional de cada Explorer.'],
                    ],
                    'rutas' => [
                        [
                            'titulo' => 'Ruta de Data Science',
                            'intro' => 'Para Explorers interesados en tecnología, análisis de información, inteligencia artificial, negocios, innovación y resolución de problemas.',
                            'habilidades' => ['Análisis de datos', 'Pensamiento lógico', 'Programación', 'Interpretación de información', 'Innovación'],
                            'perfiles' => 'Ingeniería, Ciencia de Datos, Inteligencia Artificial, Economía, Finanzas, Administración, Negocios, Tecnología y Marketing Analytics.',
                        ],
                        [
                            'titulo' => 'Ruta de Diseño e Impresión 3D',
                            'intro' => 'Para Explorers interesados en creatividad, diseño, ingeniería, arquitectura, tecnología e innovación aplicada.',
                            'habilidades' => ['Diseño', 'Pensamiento espacial', 'Prototipado', 'Planeación de proyectos', 'Tecnología aplicada'],
                            'perfiles' => 'Arquitectura, Diseño Industrial, Ingeniería, Diseño de Producto, Animación, Robótica, Manufactura Digital y Arte Digital.',
                        ],
                    ],
                    'areas' => [
                        [
                            'titulo' => 'Área de Salud',
                            'texto' => 'Fortalece análisis, observación, pensamiento científico, investigación y comprensión del ser humano.',
                            'perfiles' => 'Medicina, Enfermería, Nutrición, Psicología, Fisioterapia, Odontología, Biotecnología y Ciencias Biomédicas.',
                        ],
                        [
                            'titulo' => 'Área Físico-Matemática',
                            'texto' => 'Desarrolla pensamiento lógico, razonamiento matemático, análisis de problemas y creatividad aplicada.',
                            'perfiles' => 'Ingenierías, Arquitectura, Ciencia de Datos, Actuaría, Física, Matemáticas Aplicadas, Robótica y Diseño Industrial.',
                        ],
                        [
                            'titulo' => 'Área Económico-Administrativa',
                            'texto' => 'Fortalece liderazgo, toma de decisiones, análisis de información, pensamiento estratégico y comunicación.',
                            'perfiles' => 'Administración, Finanzas, Economía, Mercadotecnia, Negocios Internacionales, Contaduría, Comercio y Emprendimiento.',
                        ],
                    ],
                    'imagenes' => [
                        'componentes' => [
                            'titulo' => 'POP del IB® - Componentes del programa',
                            'referencia' => 'Fotografía horizontal de Explorers participando en aprendizaje-servicio, trabajo colaborativo, investigación o una presentación relacionada con los componentes del POP.',
                        ],
                        'ruta_data_science' => [
                            'titulo' => 'POP del IB® - Ruta de Data Science',
                            'referencia' => 'Fotografía horizontal de Explorers trabajando con computadoras, programación, análisis de datos, inteligencia artificial o tecnología.',
                        ],
                        'ruta_diseno_3d' => [
                            'titulo' => 'POP del IB® - Ruta de Diseño e Impresión 3D',
                            'referencia' => 'Fotografía horizontal de Explorers diseñando, creando prototipos o utilizando una impresora 3D.',
                        ],
                        'areas_academicas' => [
                            'titulo' => 'POP del IB® - Áreas académicas de 11° y 12°',
                            'referencia' => 'Fotografía horizontal de Explorers de 11° y 12° en una actividad académica, laboratorio, exposición o sesión de orientación universitaria.',
                        ],
                        'cierre' => [
                            'titulo' => 'POP del IB® - Preparación universitaria',
                            'referencia' => 'Fotografía horizontal de graduación, orientación universitaria o Explorers proyectando su futuro profesional.',
                        ],
                    ],
                    'cierre' => [
                        'titulo' => 'Una preparación integral para la universidad',
                        'texto' => 'En Discovery, nuestros Explorers no solo eligen materias: construyen un camino. El POP del IB®, las rutas preuniversitarias y las áreas académicas de 11° y 12° les permiten descubrir intereses, desarrollar talentos y construir un perfil sólido, competitivo y con propósito.',
                        'frase' => 'El futuro no se espera, se construye.',
                    ],
                ],
            ],
            'certificacion-de-ingles' => [
                'usar_placeholder_hero' => true,
                'hero_media_path' => 'Certificacion fotos/unicam.jpg',
                'ocultar_galeria' => true,
                'layout' => 'ingles',
                'informacion' => [
                    'eyebrow' => 'Certificación de Inglés',
                    'puntos_titulo' => 'Preparación integral por habilidades',
                    'destacados' => [
                        ['titulo' => 'Metas claras', 'texto' => 'Los Explorers conocen las habilidades que deben fortalecer y trabajan con objetivos medibles durante el ciclo escolar.'],
                        ['titulo' => 'Práctica integral', 'texto' => 'Reading, writing, listening y speaking se practican de forma constante para usar el idioma con seguridad.'],
                        ['titulo' => 'Proyección académica', 'texto' => 'La preparación ayuda a contar con evidencias de nivel de inglés para procesos escolares, universitarios y de movilidad.'],
                    ],
                    'puntos' => [
                        'Preparación para evaluaciones de inglés con validez académica.',
                        'Práctica guiada de comprensión lectora, escritura, comprensión auditiva y expresión oral.',
                        'Seguimiento del avance individual para detectar fortalezas y áreas de mejora.',
                        'Trabajo con vocabulario, gramática y comunicación en contextos reales.',
                        'Base para certificaciones como Cambridge, TOEFL u otros exámenes de referencia internacional.',
                    ],
                    'secciones' => [
                        ['titulo' => 'Evaluación y diagnóstico', 'texto' => 'El proceso permite identificar el nivel de cada Explorer y orientar la preparación hacia metas alcanzables, de acuerdo con sus necesidades académicas.'],
                        ['titulo' => 'Habilidades comunicativas', 'texto' => 'La preparación integra lectura, escritura, escucha y expresión oral para que cada Explorer use el inglés con precisión, fluidez y confianza.'],
                        ['titulo' => 'Acompañamiento académico', 'texto' => 'Docentes y coordinación dan seguimiento al progreso, proponen práctica específica y ayudan a que cada Explorer llegue mejor preparado a sus evaluaciones.'],
                        ['titulo' => 'Certificaciones para el futuro', 'texto' => 'Contar con una certificación de inglés abre puertas en admisiones universitarias, intercambios, becas y experiencias académicas con enfoque internacional.'],
                    ],
                    'cierre' => [
                        'titulo' => 'Evidencias de dominio para nuevas oportunidades',
                        'texto' => 'La certificación permite demostrar el nivel de inglés con mayor claridad y fortalece el perfil académico de los Explorers para procesos universitarios, becas, movilidad e intercambios.',
                    ],
                    'experiencias' => ['Reading', 'Writing', 'Listening', 'Speaking', 'Cambridge', 'TOEFL', 'Seguimiento académico'],
                ],
            ],
        ],
    ],

    'temas_niveles' => [
        'preescolar' => [
            'hero' => 'bg-lime-500 text-black',
            'heading' => 'text-lime-800',
            'eyebrow' => 'text-lime-800',
            'soft' => 'bg-lime-50 border-lime-300',
            'dot' => 'bg-lime-500',
            'button' => 'bg-lime-500 text-black hover:bg-lime-600',
            'chip' => 'text-lime-800',
            'bar' => 'bg-lime-500',
            'heroText' => 'text-black',
            'heroMuted' => 'text-black',
        ],
        'primaria' => [
            'hero' => 'bg-red-600 text-white',
            'heading' => 'text-red-600',
            'eyebrow' => 'text-red-600',
            'soft' => 'bg-red-50 border-red-200',
            'dot' => 'bg-red-600',
            'button' => 'bg-red-600 text-white hover:bg-red-700',
            'chip' => 'text-red-600',
            'bar' => 'bg-red-600',
            'heroText' => 'text-white',
            'heroMuted' => 'text-red-50',
        ],
        'secundaria' => [
            'hero' => 'bg-blue-700 text-white',
            'heading' => 'text-blue-700',
            'eyebrow' => 'text-blue-700',
            'soft' => 'bg-blue-50 border-blue-100',
            'dot' => 'bg-blue-700',
            'button' => 'bg-blue-700 text-white hover:bg-blue-800',
            'chip' => 'text-blue-700',
            'bar' => 'bg-blue-700',
            'heroText' => 'text-white',
            'heroMuted' => 'text-blue-50',
        ],
        'bachillerato' => [
            'hero' => 'bg-green-500 text-white',
            'heading' => 'text-green-600',
            'eyebrow' => 'text-green-600',
            'soft' => 'bg-green-50 border-green-200',
            'dot' => 'bg-green-500',
            'button' => 'bg-green-500 text-white hover:bg-green-600',
            'chip' => 'text-green-600',
            'bar' => 'bg-green-500',
            'heroText' => 'text-white',
            'heroMuted' => 'text-green-50',
        ],
        'ib-en-discovery' => [
            'hero' => 'bg-amber-500 text-black',
            'heading' => 'text-yellow-500',
            'eyebrow' => 'text-yellow-500',
            'soft' => 'bg-amber-50 border-amber-500',
            'dot' => 'bg-amber-500',
            'button' => 'bg-amber-500 text-black hover:bg-amber-600',
            'chip' => 'text-yellow-500',
            'bar' => 'bg-amber-500',
            'heroText' => 'text-black',
            'heroMuted' => 'text-black',
        ],
        'pop-del-ib' => [
            'hero' => 'bg-blue-700 text-white',
            'heading' => 'text-blue-700',
            'eyebrow' => 'text-blue-700',
            'soft' => 'bg-blue-50 border-blue-200',
            'dot' => 'bg-yellow-500',
            'button' => 'bg-yellow-500 text-black hover:bg-amber-500',
            'chip' => 'text-blue-700',
            'bar' => 'bg-yellow-500',
            'heroText' => 'text-white',
            'heroMuted' => 'text-blue-50',
        ],
        'certificacion-de-ingles' => [
            'hero' => 'bg-sky-500 text-white',
            'heading' => 'text-sky-500',
            'eyebrow' => 'text-sky-500',
            'soft' => 'bg-sky-50 border-sky-500',
            'dot' => 'bg-sky-500',
            'button' => 'bg-sky-500 text-white hover:bg-sky-600',
            'chip' => 'text-sky-500',
            'bar' => 'bg-sky-500',
            'heroText' => 'text-white',
            'heroMuted' => 'text-sky-50',
        ],
        'default' => [
            'hero' => 'bg-blue-700 text-white',
            'heading' => 'text-blue-700',
            'eyebrow' => 'text-blue-700',
            'soft' => 'bg-blue-50 border-blue-100',
            'dot' => 'bg-blue-700',
            'button' => 'bg-blue-700 text-white hover:bg-blue-800',
            'chip' => 'text-blue-700',
            'bar' => 'bg-blue-700',
            'heroText' => 'text-white',
            'heroMuted' => 'text-blue-50',
        ],
    ],
];
