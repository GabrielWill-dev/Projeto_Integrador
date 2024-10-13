// script.js

// Registro do Service Worker e solicitação de permissão
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
        .then(registration => {
            console.log('Service Worker registrado com sucesso:', registration);
            return registration.pushManager.getSubscription()
                .then(subscription => {
                    if (!subscription) {
                        // Se não houver assinatura, solicitar uma
                        return registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: 'BFmbASE5gxCruK3gt6won6XwqtsB6gR4NMuwbYUrL45UtzSZHLnPLaZk8xB6F_mvx3l8nKQWXM1xty0G8LpSYvY'
                        });
                    }
                    return subscription;
                });
        })
        .then(subscription => {
            console.log('Assinatura:', subscription); // Verifique a estrutura aqui
            // Enviar a assinatura para o servidor
            return fetch('send-notification.php', {
                method: 'POST',
                body: JSON.stringify(subscription),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Resposta do servidor:', data);
        })
        .catch(error => {
            console.error('Erro ao registrar o Service Worker ou enviar a notificação:', error);
        });
}

// Service Worker: Eventos de instalação e ativação
self.addEventListener('install', event => {
    console.log('Service Worker instalado');
});

self.addEventListener('activate', event => {
    console.log('Service Worker ativado');
});

// Receber notificações push
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : { title: 'Notificação sem título', body: 'Sem corpo' };

    const options = {
        body: data.body,
        icon: 'img/icon.png', // Ícone da notificação
        badge: 'img/badge.png' // Badge da notificação
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});
