self.addEventListener('install', event => {

    self.skipWaiting();

    console.log('SW instalado');
});


self.addEventListener('activate', event => {

    self.clients.claim();

    console.log('SW ativo');
});


self.addEventListener('fetch', event => {

});