@props(['videos', 'tema'])

<section class="mt-12" data-promotional-videos data-no-scroll-animation>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">{{ __('site.pages.level.promotional_videos') }}</p>
            <h2 class="mt-2 text-3xl font-extrabold text-gray-950">{{ __('site.pages.level.promotional_videos_title') }}</h2>
        </div>
        @if (count($videos) > 1)
            <p class="flex items-center gap-2 text-sm font-semibold text-gray-500 lg:hidden" aria-hidden="true">
                {{ __('site.pages.level.swipe_videos') }}
                <span class="text-xl">→</span>
            </p>
        @endif
    </div>

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

    @if (count($videos) > 1)
        <div class="mt-4 flex justify-center gap-2 lg:hidden" aria-hidden="true">
            @foreach ($videos as $video)
                <span class="h-2 w-2 rounded-full {{ $loop->first ? $tema['dot'] : 'bg-gray-300' }}"></span>
            @endforeach
        </div>
    @endif

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
</section>
