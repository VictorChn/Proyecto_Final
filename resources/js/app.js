// Register Service Worker for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('[PWA] Service Worker registrado con éxito. Scope:', registration.scope);
            })
            .catch((error) => {
                console.error('[PWA] Error al registrar el Service Worker:', error);
            });

        // Esperar a que el Service Worker esté activo y listo antes de inicializar la suscripción
        navigator.serviceWorker.ready.then((registration) => {
            console.log('[PWA] Service Worker activo y listo. Inicializando suscripción push.');
            initPushSubscription(registration);
        });
    });
}

// Convertir clave pública VAPID a Uint8Array
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Suscribir al usuario a las Notificaciones Push
function initPushSubscription(registration) {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.log('[PWA] Usuario no autenticado (no se detectó meta csrf-token). Omitiendo suscripción push.');
        return;
    }

    if (!('PushManager' in window)) {
        console.warn('[PWA] Las notificaciones push no están soportadas en este navegador.');
        return;
    }

    console.log('[PWA] Permiso actual de notificaciones:', Notification.permission);

    // Solicitar o validar permisos de notificación
    if (Notification.permission === 'default') {
        console.log('[PWA] Solicitando permisos de notificación al usuario...');
        Notification.requestPermission().then((permission) => {
            console.log('[PWA] Permiso respondido por el usuario:', permission);
            if (permission === 'granted') {
                subscribeUser(registration);
            } else {
                console.warn('[PWA] El usuario denegó los permisos de notificación.');
            }
        });
    } else if (Notification.permission === 'granted') {
        subscribeUser(registration);
    } else if (Notification.permission === 'denied') {
        console.warn('[PWA] Las notificaciones están bloqueadas en la configuración de este sitio en tu navegador. Debes desbloquearlas desde el candado de la barra de direcciones.');
    }
}

function subscribeUser(registration) {
    const vapidPublicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY;
    if (!vapidPublicKey) {
        console.error('[PWA] Clave pública VAPID (VITE_VAPID_PUBLIC_KEY) no disponible en Vite. Revisa tu archivo .env y reinicia el servidor de desarrollo.');
        return;
    }

    console.log('[PWA] Clave VAPID pública detectada:', vapidPublicKey);
    const applicationServerKey = urlBase64ToUint8Array(vapidPublicKey);

    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
    .then((subscription) => {
        console.log('[PWA] Suscripción de Push generada en navegador con éxito:', subscription);
        sendSubscriptionToBackend(subscription);
    })
    .catch((err) => {
        console.error('[PWA] Error al suscribir al usuario en el PushManager del navegador:', err);
    });
}

function sendSubscriptionToBackend(subscription) {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) return;

    const csrfToken = csrfMeta.getAttribute('content');
    
    console.log('[PWA] Enviando suscripción al backend de Laravel...');
    fetch('/api/push-subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(subscription)
    })
    .then((res) => {
        if (!res.ok) {
            throw new Error('HTTP error ' + res.status);
        }
        return res.json();
    })
    .then((data) => {
        console.log('[PWA] Suscripción guardada exitosamente en la base de datos de Laravel:', data);
    })
    .catch((err) => {
        console.error('[PWA] Error al enviar/guardar la suscripción push en el servidor:', err);
    });
}


// Notificación local cuando la conexión a Internet regresa (Back Online)
window.addEventListener('online', () => {
    console.log('Conexión a Internet detectada.');
    if (Notification.permission === 'granted' && 'serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then((registration) => {
            registration.showNotification('¡Conexión Restablecida!', {
                body: 'Tu conexión a internet se ha recuperado correctamente.',
                icon: '/icons/icon-192x192.png',
                badge: '/icons/icon-192x192.png',
                tag: 'connection-status',
                renotify: true
            });
        });
    }
});

