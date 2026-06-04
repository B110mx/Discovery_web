<div
    data-explorer-help
    class="fixed bottom-5 left-5 z-[9998]"
>
    <div
        id="explorer-help-bubble"
        data-explorer-help-bubble
        class="pointer-events-none absolute bottom-full left-0 mb-3 w-[min(20rem,calc(100vw-2.5rem))] translate-y-2 scale-95 opacity-0 transition duration-200"
        hidden
    >
        <div class="overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-gray-200">
            <div class="flex items-start justify-between gap-4 bg-blue-700 px-5 py-4 text-white">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-100">Discovery® Tehuacán</p>
                    <h2 class="mt-1 text-xl font-extrabold">¿Qué es un Explorer?</h2>
                </div>
                <button
                    type="button"
                    data-explorer-help-close
                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded bg-white/15 font-bold text-white hover:bg-white/25"
                    aria-label="Cerrar información de Explorer"
                >
                    ×
                </button>
            </div>

            <div class="space-y-4 p-5">
                <p class="text-sm leading-7 text-gray-700">
                    En Discovery® Tehuacán, un Explorer es un estudiante que emprende un viaje de aprendizaje holístico, desarrollando habilidades lingüísticas, cognitivas y sociales a través de un enfoque inmersivo y basado en la indagación.
                </p>
                <div class="grid grid-cols-3 gap-2 text-center text-xs font-bold text-gray-700">
                    <span class="rounded bg-blue-50 px-2 py-2 text-blue-700">Indaga</span>
                    <span class="rounded bg-yellow-50 px-2 py-2 text-yellow-800">Crea</span>
                    <span class="rounded bg-red-50 px-2 py-2 text-red-700">Convive</span>
                </div>
            </div>
        </div>
    </div>

    <button
        type="button"
        data-explorer-help-toggle
        class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-700 text-white shadow-2xl ring-4 ring-white transition duration-200 hover:scale-105 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
        aria-label="Abrir información sobre qué es un Explorer"
        aria-controls="explorer-help-bubble"
        aria-expanded="false"
    >
        <span class="sr-only">¿Qué es un Explorer?</span>
        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <circle cx="12" cy="12" r="9"></circle>
            <path d="m15.5 8.5-2.1 4.9-4.9 2.1 2.1-4.9 4.9-2.1Z"></path>
            <path d="M12 3v2"></path>
            <path d="M12 19v2"></path>
            <path d="M3 12h2"></path>
            <path d="M19 12h2"></path>
        </svg>
    </button>
</div>
