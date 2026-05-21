<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @laravelPWA

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine --}}
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    {{-- Theme Store --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('theme', {

                init() {

                    const savedTheme = localStorage.getItem('theme');

                    const systemTheme =
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                        ? 'dark'
                        : 'light';

                    this.theme = savedTheme || systemTheme;

                    this.updateTheme();
                },

                theme: 'light',

                toggle() {

                    this.theme = this.theme === 'light'
                        ? 'dark'
                        : 'light';

                    localStorage.setItem('theme', this.theme);

                    this.updateTheme();
                },

                updateTheme() {

                    const html = document.documentElement;

                    const body = document.body;

                    if (this.theme === 'dark') {

                        html.classList.add('dark');

                        body.classList.add('dark', 'bg-gray-900');

                    } else {

                        html.classList.remove('dark');

                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {

                isExpanded: window.innerWidth >= 1280,

                isMobileOpen: false,

                isHovered: false,

                toggleExpanded() {

                    this.isExpanded = !this.isExpanded;

                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {

                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {

                    this.isMobileOpen = val;
                },

                setHovered(val) {

                    if (
                        window.innerWidth >= 1280 &&
                        !this.isExpanded
                    ) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    {{-- Prevent dark mode flash --}}
    <script>
        (function () {

            const savedTheme = localStorage.getItem('theme');

            const systemTheme =
                window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light';

            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {

                document.documentElement.classList.add('dark');

                document.body.classList.add('dark', 'bg-gray-900');

            } else {

                document.documentElement.classList.remove('dark');

                document.body.classList.remove('dark', 'bg-gray-900');
            }

        })();
    </script>
</head>

<body
    x-data="{ loaded: true }"

    x-init="
        $store.sidebar.isExpanded = window.innerWidth >= 1280;

        const checkMobile = () => {

            if (window.innerWidth < 1280) {

                $store.sidebar.setMobileOpen(false);

                $store.sidebar.isExpanded = false;

            } else {

                $store.sidebar.isMobileOpen = false;

                $store.sidebar.isExpanded = true;
            }
        };

        window.addEventListener('resize', checkMobile);
    "
>

    {{-- BOTÃO INSTALAR APP --}}
    <button
        id="installApp"
        class="fixed bottom-5 right-5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-2xl z-50"
    >
        Instalar App
    </button>

    {{-- PRELOADER --}}
    <x-common.preloader />

    {{-- CONTEÚDO --}}
    @yield('content')

    {{-- SCRIPT PWA --}}
    <script>

        let deferredPrompt = null;

        const installButton =
            document.getElementById('installApp');


        // Android / Windows
        window.addEventListener(
            'beforeinstallprompt',
            (e) => {

                console.log('PWA instalável');

                e.preventDefault();

                deferredPrompt = e;
            }
        );


        // Clique botão
        installButton.addEventListener(
            'click',
            async () => {

                // Android / Windows
                if (deferredPrompt) {

                    deferredPrompt.prompt();

                    const { outcome } =
                        await deferredPrompt.userChoice;

                    console.log('Resultado:', outcome);

                    return;
                }

                // iPhone
                const isIos =
                    /iphone|ipad|ipod/i.test(
                        window.navigator.userAgent
                    );

                if (isIos) {

                    alert(
                        'Para instalar no iPhone:\n\n' +
                        '1. Toque em Compartilhar\n' +
                        '2. "Adicionar à Tela Principal"'
                    );

                    return;
                }

                // fallback
                alert(
                    'Instalação não disponível neste navegador.'
                );
            }
        );

    </script>

</body>

@stack('scripts')

</html>