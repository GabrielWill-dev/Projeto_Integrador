self.addEventListener('install', event => {
    console.log('Service Worker instalado');
});

self.addEventListener('activate', event => {
    console.log('Service Worker ativado');
});

self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : { title: 'Notificação sem título', body: 'Sem corpo' };

    const options = {
        body: data.body,
        icon: 'img/icon.png',
        badge: 'img/badge.png'
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});
