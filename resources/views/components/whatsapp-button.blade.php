@props([
    'label' => 'Atencion a clientes',
])

@php
    $numeroVisible = config('colegio.contacto.whatsapp_numero');
    $numero = preg_replace('/\D+/', '', $numeroVisible);
    $mensaje = rawurlencode(config('colegio.contacto.whatsapp_mensaje'));
    $url = "https://wa.me/{$numero}?text={$mensaje}";
    $telefonoUrl = 'tel:+' . $numero;
@endphp

<div
    class="group"
    style="position: fixed !important; right: 20px !important; bottom: 20px !important; z-index: 9999 !important;"
>
    <div class="absolute bottom-full right-0 mb-3 w-72 translate-y-2 opacity-0 transition duration-200 pointer-events-none group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:translate-y-0 group-focus-within:opacity-100">
        <div class="overflow-hidden rounded-lg bg-white shadow-2xl ring-1 ring-gray-200">
            <div class="bg-green-600 px-5 py-4 text-white">
                <p class="text-xs font-bold uppercase tracking-wide text-green-100">WhatsApp</p>
                <p class="mt-1 text-lg font-extrabold">{{ $label }}</p>
            </div>

            <div class="space-y-3 p-4">
                <p class="text-sm leading-6 text-gray-600">
                    Escríbenos y con gusto te orientamos sobre informes, admisiones o ubicación.
                </p>
                <a
                    href="{{ $url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between rounded-lg bg-green-600 px-4 py-3 font-bold text-white hover:bg-green-700"
                >
                    Abrir chat
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ $telefonoUrl }}" class="block rounded-lg border border-gray-200 px-4 py-3 text-sm font-bold text-gray-800 hover:bg-gray-50">
                    {{ $numeroVisible }}
                </a>
            </div>
        </div>
    </div>

    <a
        href="{{ $url }}"
        target="_blank"
        rel="noopener noreferrer"
        class="flex h-16 w-16 items-center justify-center rounded-full bg-green-600 text-xl font-black text-white shadow-2xl ring-4 ring-white transition duration-200 hover:scale-105 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200"
        aria-label="Abrir WhatsApp de {{ $label }}"
    >
        <span class="sr-only">{{ $label }}</span>
        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
        </svg>
    </a>
</div>
