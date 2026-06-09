@props([
    'imagen',
    'alt' => 'Imagen de sección',
    'class' => 'w-full h-full object-cover',
    'placeholderClass' => 'min-h-64',
])

{{--
    Contrato de imagen compartido:
    - url: archivo resuelto por PageController;
    - titulo/referencia: contexto mostrado cuando aún falta el archivo.
    Mantener el placeholder permite publicar una sección antes de cargar su imagen.
--}}
@if (! empty($imagen['url']))
    <img
        src="{{ $imagen['url'] }}"
        alt="{{ $alt }}"
        {{ $attributes->merge(['class' => $class]) }}
    >
@else
    <div {{ $attributes->merge(['class' => $placeholderClass . ' flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center']) }}>
        <div>
            <p class="font-bold text-gray-700">Imagen pendiente</p>
            <p class="mt-2 text-sm text-gray-500">{{ $imagen['titulo'] ?? $alt }}</p>
            @if (! empty($imagen['referencia']))
                <p class="mt-3 text-xs leading-5 text-gray-400">{{ $imagen['referencia'] }}</p>
            @endif
        </div>
    </div>
@endif
