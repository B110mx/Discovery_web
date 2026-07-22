@props([
    'imagen',
    'alt' => __('site.images.section_default_alt'),
    'class' => 'w-full h-full object-cover',
    'placeholderClass' => 'min-h-64',
    'lightbox' => true,
    'gallery' => null,
])

{{--
    Contrato de imagen compartido:
    - url: archivo resuelto por PageController;
    - titulo/referencia: contexto mostrado cuando aun falta el archivo;
    - lightbox: permite abrir la imagen en grande con GLightbox.
--}}
@if (! empty($imagen['url']))
    @if ($lightbox)
        <a
            href="{{ $imagen['url'] }}"
            class="glightbox block h-full w-full"
            @if ($gallery) data-gallery="{{ $gallery }}" @endif
            data-title="{{ $imagen['titulo'] ?? $alt }}"
            aria-label="{{ __('site.images.view_large', ['alt' => $alt]) }}"
        >
            <img
                src="{{ $imagen['url'] }}"
                alt="{{ $alt }}"
                decoding="async"
                {{ $attributes->merge(['class' => $class]) }}
            >
        </a>
    @else
        <img
            src="{{ $imagen['url'] }}"
            alt="{{ $alt }}"
            decoding="async"
            {{ $attributes->merge(['class' => $class]) }}
        >
    @endif
@else
    <div {{ $attributes->merge(['class' => $placeholderClass . ' flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center']) }}>
        <div>
            <p class="font-bold text-gray-700">{{ __('site.images.pending') }}</p>
            <p class="mt-2 text-sm text-gray-500">{{ $imagen['titulo'] ?? $alt }}</p>
            @if (! empty($imagen['referencia']))
                <p class="mt-3 text-xs leading-5 text-gray-400">{{ $imagen['referencia'] }}</p>
            @endif
        </div>
    </div>
@endif
