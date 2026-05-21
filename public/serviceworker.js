var staticCacheName = "pwa-v" + new Date().getTime();

var filesToCache = [
    '/',
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-512x512.png',
];


// INSTALL
self.addEventListener('install', event => {

    self.skipWaiting();

    event.waitUntil(

        caches.open(staticCacheName)
            .then(cache => {

                return cache.addAll(filesToCache);
            })
    );
});


// ACTIVATE
self.addEventListener('activate', event => {

    event.waitUntil(

        caches.keys().then(cacheNames => {

            return Promise.all(

                cacheNames
                    .filter(cacheName =>
                        cacheName.startsWith('pwa-')
                    )
                    .filter(cacheName =>
                        cacheName !== staticCacheName
                    )
                    .map(cacheName =>
                        caches.delete(cacheName)
                    )
            );
        })
    );

    self.clients.claim();
});


// FETCH
self.addEventListener('fetch', event => {

    event.respondWith(

        caches.match(event.request)
            .then(response => {

                return response || fetch(event.request);
            })
            .catch(() => {

                return caches.match('/offline');
            })
    );
});