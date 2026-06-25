@props(['videos'])

{{--
    Widget flotante de videos.
    Los atributos data-promotional-* son el contrato con resources/js/app.js;
    no quitarlos aunque no tengan estilos Tailwind directos.
--}}
<div data-promotional-videos>
    <button
        type="button"
        class="fixed right-0 top-[46%] z-[9990] flex items-center gap-2 rounded-l-xl bg-blue-700 px-3 py-4 font-extrabold text-white shadow-xl transition hover:bg-blue-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300"
        data-promotional-widget-toggle
        aria-controls="promotional-video-panel"
        aria-expanded="false"
        aria-label="{{ __('site.pages.level.open_video_widget') }}"
    >
        <svg class="h-6 w-6 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5v14l11-7z" />
        </svg>
        <span class="hidden text-sm sm:inline">{{ __('site.pages.level.videos') }}</span>
    </button>

    <button
        type="button"
        class="promotional-video-widget__overlay fixed inset-0 z-[10010] bg-black/55 opacity-0"
        data-promotional-widget-overlay
        aria-label="{{ __('site.pages.level.close_video_widget') }}"
        hidden
    ></button>

    <aside
        id="promotional-video-panel"
        class="promotional-video-widget__panel fixed inset-y-0 right-0 z-[10020] flex w-[min(92vw,28rem)] translate-x-full flex-col bg-gray-50 shadow-2xl"
        data-promotional-widget-panel
        role="dialog"
        aria-modal="true"
        aria-labelledby="promotional-video-panel-title"
        aria-hidden="true"
    >
        <div class="flex items-start justify-between gap-4 bg-blue-700 px-5 py-5 text-white">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-blue-100">{{ __('site.pages.level.promotional_videos') }}</p>
                <h2 id="promotional-video-panel-title" class="mt-1 text-2xl font-extrabold">{{ __('site.pages.level.promotional_videos_title') }}</h2>
            </div>
            <button
                type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15 text-2xl font-bold transition hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                data-promotional-widget-close
                aria-label="{{ __('site.pages.level.close_video_widget') }}"
            >×</button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto px-4 py-5 sm:px-5">
            {{-- Pista visual solo cuando hay carrusel horizontal. --}}
            @if (count($videos) > 1)
                <p class="mb-3 flex items-center justify-end gap-2 text-sm font-semibold text-gray-500" aria-hidden="true">
                    {{ __('site.pages.level.swipe_videos') }} <span class="text-xl">→</span>
                </p>
            @endif

            {{-- Cada botón carga el video en el <dialog>; no se precargan todos para mantener la página ligera. --}}
            <div class="promotional-video-track" role="list" aria-label="{{ __('site.pages.level.promotional_videos_title') }}">
                @foreach ($videos as $video)
                    <article class="promotional-video-card" role="listitem">
                        <button
                            type="button"
                            class="group relative block aspect-[9/16] w-full overflow-hidden rounded-xl bg-slate-900 text-left shadow-md focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300"
                            data-promotional-video-open
                            data-video-url="{{ $video['url'] }}"
                            data-video-title="{{ $video['titulo'] }}"
                            aria-label="{{ __('site.pages.level.play_video', ['title' => $video['titulo']]) }}"
                        >
                            @if (! empty($video['portada']))
                                <img
                                    src="{{ $video['portada'] }}"
                                    alt=""
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                >
                            @else
                                <span class="absolute inset-0 bg-gradient-to-br from-blue-800 via-slate-900 to-green-700"></span>
                            @endif

                            <span class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent"></span>
                            <span class="absolute inset-0 flex items-center justify-center">
                                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/95 text-blue-700 shadow-lg transition group-hover:scale-110" aria-hidden="true">
                                    <svg class="ml-1 h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z" /></svg>
                                </span>
                            </span>
                            <span class="absolute inset-x-0 bottom-0 p-5 text-lg font-extrabold leading-6 text-white">{{ $video['titulo'] }}</span>
                        </button>
                    </article>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Reproductor único reutilizable para todos los videos del widget. --}}
    <dialog class="promotional-video-dialog m-auto w-[min(92vw,430px)] overflow-visible rounded-xl bg-transparent p-0 backdrop:bg-black/80" data-promotional-video-dialog>
        <div class="relative">
            <button
                type="button"
                class="absolute -right-2 -top-12 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white text-2xl font-bold text-gray-950 shadow-lg"
                data-promotional-video-close
                aria-label="{{ __('site.pages.level.close_video') }}"
            >×</button>
            <video class="aspect-[9/16] max-h-[84vh] w-full rounded-xl bg-black" controls playsinline preload="none" data-promotional-video-player></video>
            <p class="sr-only" data-promotional-video-dialog-title></p>
        </div>
    </dialog>
</div>
