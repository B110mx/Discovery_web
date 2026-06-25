import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';

/**
 * Ejecuta una acción una sola vez tanto con mouse como con pantalla táctil.
 *
 * Algunos navegadores emiten pointerup y después click para el mismo toque;
 * la ventana de 400 ms evita abrir y cerrar inmediatamente un control.
 */
const onPress = (element, handler) => {
    let lastPointerPress = 0;

    element.addEventListener('pointerup', (event) => {
        if (event.pointerType === 'mouse') {
            return;
        }

        lastPointerPress = Date.now();
        handler(event);
    });

    element.addEventListener('click', (event) => {
        if (Date.now() - lastPointerPress < 400) {
            return;
        }

        handler(event);
    });
};

const initSiteInteractions = () => {
    // Galerías y enlaces con la clase .glightbox.
    try {
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    } catch (error) {
        console.warn('No se pudo iniciar la galeria de imagenes.', error);
    }

    // Pausar antes de una navegación interna evita que el audio continúe
    // durante la transición o al reutilizar una página desde caché.
    const pausePlayingVideos = () => {
        document.querySelectorAll('video').forEach((video) => {
            if (!video.paused) {
                video.pause();
            }
        });
    };

    // Widget lateral de videos: el panel no altera el flujo de la página y la
    // URL del archivo se asigna únicamente cuando el visitante lo reproduce.
    document.querySelectorAll('[data-promotional-videos]').forEach((widget) => {
        const toggle = widget.querySelector('[data-promotional-widget-toggle]');
        const panel = widget.querySelector('[data-promotional-widget-panel]');
        const overlay = widget.querySelector('[data-promotional-widget-overlay]');
        const widgetClose = widget.querySelector('[data-promotional-widget-close]');
        const dialog = widget.querySelector('[data-promotional-video-dialog]');
        const player = dialog?.querySelector('[data-promotional-video-player]');
        const dialogTitle = dialog?.querySelector('[data-promotional-video-dialog-title]');
        const videoClose = dialog?.querySelector('[data-promotional-video-close]');

        if (!toggle || !panel || !overlay || !widgetClose || !dialog || !player || !videoClose) {
            return;
        }

        const closeVideo = () => {
            player.pause();
            player.removeAttribute('src');
            player.load();

            if (dialog.open) {
                dialog.close();
            }
        };

        const setWidgetOpen = (isOpen) => {
            toggle.setAttribute('aria-expanded', String(isOpen));
            panel.setAttribute('aria-hidden', String(!isOpen));
            panel.classList.toggle('is-open', isOpen);
            overlay.hidden = !isOpen;
            document.body.classList.toggle('promotional-video-widget-open', isOpen);

            requestAnimationFrame(() => {
                overlay.classList.toggle('is-open', isOpen);
            });

            if (isOpen) {
                widgetClose.focus();
            } else {
                closeVideo();
                toggle.focus();
            }
        };

        // El click nativo cubre mouse, teclado y toque. Evitar el helper de
        // pointerup aquí previene que Safari móvil pierda el toque cuando el
        // overlay aparece en el mismo instante que termina el gesto.
        toggle.addEventListener('click', () => setWidgetOpen(true));
        widgetClose.addEventListener('click', () => setWidgetOpen(false));
        overlay.addEventListener('click', () => setWidgetOpen(false));

        widget.querySelectorAll('[data-promotional-video-open]').forEach((button) => {
            button.addEventListener('click', () => {
                player.src = button.dataset.videoUrl;
                player.setAttribute('aria-label', button.dataset.videoTitle || 'Video promocional');

                if (dialogTitle) {
                    dialogTitle.textContent = button.dataset.videoTitle || '';
                }

                dialog.showModal();
                player.play().catch(() => {});
            });
        });

        videoClose.addEventListener('click', closeVideo);
        dialog.addEventListener('cancel', (event) => {
            event.preventDefault();
            closeVideo();
        });
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                closeVideo();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true' && !dialog.open) {
                setWidgetOpen(false);
            }
        });
    });

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');

        if (!link) {
            return;
        }

        const href = link.getAttribute('href') || '';
        const isModifiedClick = event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;
        const isExternalTarget = link.target && link.target !== '_self';
        const isUtilityLink = href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || link.hasAttribute('download');

        if (isModifiedClick || isExternalTarget || isUtilityLink) {
            return;
        }

        let url;

        try {
            url = new URL(link.href);
        } catch (error) {
            return;
        }

        if (url.origin !== window.location.origin) {
            return;
        }

        const hasPlayingVideo = Array.from(document.querySelectorAll('video')).some((video) => !video.paused);

        if (!hasPlayingVideo) {
            return;
        }

        event.preventDefault();
        pausePlayingVideos();
        window.location.assign(url.href);
    }, true);

    /*
     * Animación global al hacer scroll.
     *
     * Además de los elementos marcados manualmente, se incluyen las secciones
     * y artículos dentro de <main>. data-no-scroll-animation permite excluir
     * un bloque concreto si una futura interacción necesita posición estable.
     */
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animatedElements = Array.from(document.querySelectorAll(
        '.animate-on-scroll, [data-scroll-reveal], main section, main article'
    )).filter((element, index, elements) => (
        !element.closest('[data-no-scroll-animation]')
        && elements.indexOf(element) === index
    ));

    animatedElements.forEach((element) => {
        element.classList.add('scroll-reveal');

        // Escalona hasta cinco hermanos para que las cuadrículas aparezcan de
        // forma progresiva sin retrasar demasiado el último elemento.
        const siblings = Array.from(element.parentElement?.children ?? [])
            .filter((sibling) => animatedElements.includes(sibling));
        const siblingIndex = siblings.indexOf(element);

        if (siblingIndex > 0) {
            element.style.setProperty('--scroll-reveal-delay', `${Math.min(siblingIndex, 4) * 90}ms`);
        }
    });

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        animatedElements.forEach((element) => element.classList.add('is-revealed'));
    } else {
        const observerOptions = {
            root: null,
            // Activa ligeramente antes de que el bloque entre por completo.
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.08
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        animatedElements.forEach((element) => observer.observe(element));
    }

    // Franja superior de contacto: visible solo al inicio de la pagina.
    const siteTopbar = document.querySelector('[data-site-topbar]');

    if (siteTopbar) {
        let isTopbarVisible = true;
        const showTopbarAt = 8;
        const hideTopbarAt = 80;

        const setTopbarVisible = (isVisible) => {
            if (isTopbarVisible === isVisible) {
                return;
            }

            isTopbarVisible = isVisible;
            siteTopbar.style.maxHeight = isVisible ? `${siteTopbar.scrollHeight}px` : '0px';
            siteTopbar.style.opacity = isVisible ? '1' : '0';
            siteTopbar.style.pointerEvents = isVisible ? 'auto' : 'none';
        };

        const updateTopbarVisibility = () => {
            if (window.scrollY <= showTopbarAt) {
                setTopbarVisible(true);
            } else if (window.scrollY >= hideTopbarAt) {
                setTopbarVisible(false);
            }
        };

        siteTopbar.style.maxHeight = `${siteTopbar.scrollHeight}px`;
        siteTopbar.style.opacity = '1';
        siteTopbar.style.pointerEvents = 'auto';
        updateTopbarVisibility();

        window.addEventListener('scroll', updateTopbarVisibility, { passive: true });
        window.addEventListener('resize', updateTopbarVisibility);
    }

    // Menú móvil y submenús. Los atributos data-* forman el contrato con
    // resources/views/components/navbar.blade.php.
    const siteNav = document.querySelector('[data-site-nav]');
    const mobileMenuToggle = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const mobileMenuOpenIcon = document.querySelector('[data-mobile-menu-open-icon]');
    const mobileMenuCloseIcon = document.querySelector('[data-mobile-menu-close-icon]');

    if (siteNav && mobileMenuToggle && mobileMenu) {
        const setMobileMenuOpen = (isOpen) => {
            mobileMenuToggle.setAttribute('aria-expanded', String(isOpen));
            mobileMenu.classList.toggle('hidden', !isOpen);
            mobileMenuOpenIcon?.classList.toggle('hidden', isOpen);
            mobileMenuCloseIcon?.classList.toggle('hidden', !isOpen);
        };

        onPress(mobileMenuToggle, () => {
            setMobileMenuOpen(mobileMenuToggle.getAttribute('aria-expanded') !== 'true');
        });

        siteNav.querySelectorAll('[data-mobile-submenu-toggle]').forEach((button) => {
            onPress(button, () => {
                const submenu = button.parentElement?.querySelector('[data-mobile-submenu]');
                const isOpen = button.getAttribute('aria-expanded') === 'true';

                button.setAttribute('aria-expanded', String(!isOpen));
                submenu?.classList.toggle('hidden', isOpen);
            });
        });

        document.addEventListener('click', (event) => {
            if (!siteNav.contains(event.target) && mobileMenuToggle.getAttribute('aria-expanded') === 'true') {
                setMobileMenuOpen(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && mobileMenuToggle.getAttribute('aria-expanded') === 'true') {
                setMobileMenuOpen(false);
                mobileMenuToggle.focus();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                setMobileMenuOpen(false);
            }
        });
    }

    // Burbuja de ayuda flotante. Se cierra con botón, clic exterior o Escape.
    const explorerHelp = document.querySelector('[data-explorer-help]');
    const explorerHelpToggle = document.querySelector('[data-explorer-help-toggle]');
    const explorerHelpBubble = document.querySelector('[data-explorer-help-bubble]');
    const explorerHelpClose = document.querySelector('[data-explorer-help-close]');

    if (explorerHelp && explorerHelpToggle && explorerHelpBubble) {
        const setExplorerHelpOpen = (isOpen) => {
            explorerHelpToggle.setAttribute('aria-expanded', String(isOpen));

            if (isOpen) {
                explorerHelpBubble.hidden = false;
                requestAnimationFrame(() => {
                    explorerHelpBubble.classList.remove('pointer-events-none', 'translate-y-2', 'scale-95', 'opacity-0');
                    explorerHelpBubble.classList.add('pointer-events-auto', 'translate-y-0', 'scale-100', 'opacity-100');
                });
            } else {
                explorerHelpBubble.classList.remove('pointer-events-auto', 'translate-y-0', 'scale-100', 'opacity-100');
                explorerHelpBubble.classList.add('pointer-events-none', 'translate-y-2', 'scale-95', 'opacity-0');
                window.setTimeout(() => {
                    if (explorerHelpToggle.getAttribute('aria-expanded') === 'false') {
                        explorerHelpBubble.hidden = true;
                    }
                }, 200);
            }
        };

        onPress(explorerHelpToggle, () => {
            setExplorerHelpOpen(explorerHelpToggle.getAttribute('aria-expanded') !== 'true');
        });

        if (explorerHelpClose) {
            onPress(explorerHelpClose, () => {
                setExplorerHelpOpen(false);
                explorerHelpToggle.focus();
            });
        }

        document.addEventListener('click', (event) => {
            if (!explorerHelp.contains(event.target) && explorerHelpToggle.getAttribute('aria-expanded') === 'true') {
                setExplorerHelpOpen(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && explorerHelpToggle.getAttribute('aria-expanded') === 'true') {
                setExplorerHelpOpen(false);
                explorerHelpToggle.focus();
            }
        });
    }
};

// Vite puede cargar este archivo antes o después de DOMContentLoaded.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSiteInteractions, { once: true });
} else {
    initSiteInteractions();
}
