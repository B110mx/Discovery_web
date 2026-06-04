import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';

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

    const pausePlayingVideos = () => {
        document.querySelectorAll('video').forEach((video) => {
            if (!video.paused) {
                video.pause();
            }
        });
    };

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

    const animatedElements = document.querySelectorAll('.animate-on-scroll');

    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        animatedElements.forEach(el => {
            el.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-700', 'ease-out');
            observer.observe(el);
        });
    } else {
        animatedElements.forEach(el => {
            el.classList.add('opacity-100', 'translate-y-0');
            el.classList.remove('opacity-0', 'translate-y-10');
        });
    }

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

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSiteInteractions, { once: true });
} else {
    initSiteInteractions();
}
