<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Discovery®</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    @include('components.navbar')

    <main class="p-6">
        @yield('content')
    </main>

    @include('components.footer')

    <x-explorer-help-button />
    <x-whatsapp-button />

    <script>
        (function () {
            var lastTouch = 0;

            function closest(element, selector) {
                while (element && element !== document) {
                    if (element.matches && element.matches(selector)) {
                        return element;
                    }

                    element = element.parentNode;
                }

                return null;
            }

            function setMobileMenuOpen(isOpen) {
                var toggle = document.querySelector('[data-mobile-menu-toggle]');
                var menu = document.querySelector('[data-mobile-menu]');
                var openIcon = document.querySelector('[data-mobile-menu-open-icon]');
                var closeIcon = document.querySelector('[data-mobile-menu-close-icon]');

                if (! toggle || ! menu) {
                    return;
                }

                toggle.setAttribute('aria-expanded', String(isOpen));
                menu.classList.toggle('hidden', ! isOpen);

                if (openIcon) {
                    openIcon.classList.toggle('hidden', isOpen);
                }

                if (closeIcon) {
                    closeIcon.classList.toggle('hidden', ! isOpen);
                }
            }

            function setExplorerHelpOpen(isOpen) {
                var toggle = document.querySelector('[data-explorer-help-toggle]');
                var bubble = document.querySelector('[data-explorer-help-bubble]');

                if (! toggle || ! bubble) {
                    return;
                }

                toggle.setAttribute('aria-expanded', String(isOpen));
                bubble.hidden = ! isOpen;
                bubble.classList.toggle('pointer-events-none', ! isOpen);
                bubble.classList.toggle('pointer-events-auto', isOpen);
                bubble.classList.toggle('translate-y-2', ! isOpen);
                bubble.classList.toggle('translate-y-0', isOpen);
                bubble.classList.toggle('scale-95', ! isOpen);
                bubble.classList.toggle('scale-100', isOpen);
                bubble.classList.toggle('opacity-0', ! isOpen);
                bubble.classList.toggle('opacity-100', isOpen);
            }

            function handlePress(event) {
                var target = event.target;
                var mobileToggle = closest(target, '[data-mobile-menu-toggle]');
                var submenuToggle = closest(target, '[data-mobile-submenu-toggle]');
                var explorerToggle = closest(target, '[data-explorer-help-toggle]');
                var explorerClose = closest(target, '[data-explorer-help-close]');

                if (mobileToggle) {
                    event.preventDefault();
                    event.stopPropagation();
                    setMobileMenuOpen(mobileToggle.getAttribute('aria-expanded') !== 'true');
                    return;
                }

                if (submenuToggle) {
                    var submenu = submenuToggle.parentNode ? submenuToggle.parentNode.querySelector('[data-mobile-submenu]') : null;
                    var isOpen = submenuToggle.getAttribute('aria-expanded') === 'true';

                    event.preventDefault();
                    event.stopPropagation();
                    submenuToggle.setAttribute('aria-expanded', String(! isOpen));

                    if (submenu) {
                        submenu.classList.toggle('hidden', isOpen);
                    }

                    return;
                }

                if (explorerToggle) {
                    event.preventDefault();
                    event.stopPropagation();
                    setExplorerHelpOpen(explorerToggle.getAttribute('aria-expanded') !== 'true');
                    return;
                }

                if (explorerClose) {
                    event.preventDefault();
                    event.stopPropagation();
                    setExplorerHelpOpen(false);
                }
            }

            document.addEventListener('touchend', function (event) {
                lastTouch = Date.now();
                handlePress(event);
            }, true);

            document.addEventListener('click', function (event) {
                if (Date.now() - lastTouch < 500) {
                    return;
                }

                handlePress(event);
            }, true);
        })();
    </script>
</body>
</html>
