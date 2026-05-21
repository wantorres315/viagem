import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({

    plugins: [

        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),

        VitePWA({

            registerType: 'autoUpdate',

            includeAssets: [
                'favicon.ico',
                'robots.txt',
                'apple-touch-icon.png'
            ],

            manifest: {

                name: 'Viagem Wan Motta',

                short_name: 'Viagens',

                description: 'Aplicação de viagens',

                theme_color: '#ffffff',

                background_color: '#f70ca1',

                display: 'standalone',

                scope: '/',

                start_url: '/',

                orientation: 'portrait',

                icons: [
                    {
                        src: '/images/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ]
            },

            workbox: {

                globPatterns: ['**/*.{js,css,html,png,svg,ico}'],

                runtimeCaching: [
                    {
                        urlPattern: ({ request }) =>
                            request.destination === 'document',

                        handler: 'NetworkFirst',

                        options: {
                            cacheName: 'pages-cache'
                        }
                    },
                    {
                        urlPattern: ({ request }) =>
                            ['style', 'script'].includes(request.destination),

                        handler: 'StaleWhileRevalidate',

                        options: {
                            cacheName: 'assets-cache'
                        }
                    },
                    {
                        urlPattern: ({ request }) =>
                            request.destination === 'image',

                        handler: 'CacheFirst',

                        options: {
                            cacheName: 'images-cache'
                        }
                    }
                ]
            }
        })
    ]
});
