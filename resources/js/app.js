document.addEventListener('DOMContentLoaded', () => {
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

    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => {
        el.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-700', 'ease-out');
        observer.observe(el);
    });
});
