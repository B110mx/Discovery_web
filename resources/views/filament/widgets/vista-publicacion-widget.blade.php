{{--
    Vista Livewire del widget de publicación.
    wire:click invoca VistaPublicacionWidget::alternar y el estado se conserva en BD.
--}}
<x-filament-widgets::widget>
    <style>
        .view-publishing-groups {
            display: grid;
            gap: 1.5rem;
        }

        .view-publishing-group-title {
            margin-bottom: .75rem;
            color: rgb(156 163 175);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .view-publishing-grid {
            display: grid;
            gap: .75rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .view-publishing-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 76px;
            padding: 1rem;
            border: 1px solid rgba(156, 163, 175, .25);
            border-radius: .75rem;
            background: rgba(255, 255, 255, .04);
        }

        .view-publishing-name {
            color: currentColor;
            font-weight: 700;
        }

        .view-publishing-status {
            margin-top: .25rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .view-publishing-status.is-published {
            color: rgb(34 197 94);
        }

        .view-publishing-status.is-maintenance {
            color: rgb(245 158 11);
        }

        .view-publishing-meta {
            margin-top: .25rem;
            color: rgb(156 163 175);
            font-size: .75rem;
        }

        .view-publishing-switch {
            position: relative;
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            width: 52px;
            height: 30px;
            padding: 0;
            border: 0;
            border-radius: 9999px;
            background: rgb(107 114 128);
            cursor: pointer;
            transition: background-color .2s ease;
        }

        .view-publishing-switch.is-published {
            background: rgb(22 163 74);
        }

        .view-publishing-switch:focus-visible {
            outline: 3px solid rgba(245, 158, 11, .45);
            outline-offset: 2px;
        }

        .view-publishing-switch:disabled {
            cursor: wait;
            opacity: .65;
        }

        .view-publishing-switch-knob {
            display: block;
            width: 22px;
            height: 22px;
            margin-left: 4px;
            border-radius: 9999px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .35);
            transform: translateX(0);
            transition: transform .2s ease;
        }

        .view-publishing-switch.is-published .view-publishing-switch-knob {
            transform: translateX(22px);
        }
    </style>

    <x-filament::section>
        <x-slot name="heading">
            Publicación y mantenimiento de vistas
        </x-slot>

        <x-slot name="description">
            Desactiva una vista para mostrar una página de mantenimiento al público. Los administradores podrán seguir viéndola para trabajar y verificar cambios.
        </x-slot>

        <div class="view-publishing-groups">
            @foreach ($grupos as $grupo => $vistas)
                <section>
                    <h3 class="view-publishing-group-title">
                        {{ $grupo }}
                    </h3>

                    <div class="view-publishing-grid">
                        @foreach ($vistas as $vista)
                            <div class="view-publishing-card">
                                <div>
                                    <p class="view-publishing-name">{{ $vista['nombre'] }}</p>
                                    <p class="view-publishing-status {{ $vista['publicada'] ? 'is-published' : 'is-maintenance' }}">
                                        {{ $vista['publicada'] ? 'Publicada' : 'En mantenimiento' }}
                                    </p>
                                    @if ($vista['actualizada_por'])
                                        <p class="view-publishing-meta">
                                            {{ $vista['actualizada_por'] }} · {{ $vista['actualizada_en']?->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>

                                <button
                                    type="button"
                                    wire:click="alternar('{{ $vista['clave'] }}')"
                                    wire:confirm="{{ $vista['publicada'] ? '¿Poner esta vista en mantenimiento? El público dejará de verla.' : '¿Publicar esta vista nuevamente?' }}"
                                    wire:loading.attr="disabled"
                                    class="view-publishing-switch {{ $vista['publicada'] ? 'is-published' : '' }}"
                                    role="switch"
                                    aria-checked="{{ $vista['publicada'] ? 'true' : 'false' }}"
                                    aria-label="Cambiar publicación de {{ $vista['nombre'] }}"
                                >
                                    <span class="view-publishing-switch-knob"></span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
