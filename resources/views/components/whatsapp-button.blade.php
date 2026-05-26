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
        W
    </a>
</div>
