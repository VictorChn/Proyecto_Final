const CACHE_NAME = 'giosalon-cache-v1';
const OFFLINE_URL = '/offline.html';

// Assets to precache immediately
const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/img/logo.png',
    '/img/FondoHero.webp',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/favicon.ico'
];

// Install event: cache core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[Service Worker] Pre-caching offline fallback and key assets');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event: clean up older caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            return self.clients.claim().catch((err) => {
                console.warn('[Service Worker] Failed to claim clients:', err);
            });
        })
    );
});

// Fetch event: serve cached resources and network fallbacks
self.addEventListener('fetch', (event) => {
    // Only handle GET requests. Skip POST, PUT, DELETE, etc. (required for Livewire/Jetstream actions)
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // Skip Vite dev server, Livewire internal polling and updates, Sanctum CSRF cookies, and debug bar endpoints
    if (
        url.port === '5173' ||
        url.pathname.includes('/@vite/') ||
        url.pathname.includes('/livewire/') ||
        url.pathname.includes('/sanctum/') ||
        url.pathname.includes('/_debugbar/') ||
        url.pathname.includes('/stripe/')
    ) {
        return;
    }

    event.respondWith(
        (async () => {
            const cache = await caches.open(CACHE_NAME);

            // 1. Navigation requests (HTML pages): Network-First, fallback to Cache, fallback to Offline page
            if (event.request.mode === 'navigate') {
                try {
                    const networkResponse = await fetch(event.request);
                    // Save a copy of the page in cache for offline reading
                    cache.put(event.request, networkResponse.clone());
                    return networkResponse;
                } catch (error) {
                    console.log('[Service Worker] Navigation failed, attempting cache fallback:', error);
                    const cachedResponse = await cache.match(event.request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // If no cache copy exists, show the offline fallback page
                    const offlineFallback = await cache.match(OFFLINE_URL);
                    return offlineFallback;
                }
            }

            // 2. Non-navigation requests (CSS, JS, Fonts, Images): Cache-First, fallback to Network
            const cachedResponse = await cache.match(event.request);
            if (cachedResponse) {
                return cachedResponse;
            }

            try {
                const networkResponse = await fetch(event.request);
                // Cache newly fetched assets dynamically (e.g. fonts, external icons)
                if (networkResponse.status === 200) {
                    cache.put(event.request, networkResponse.clone());
                }
                return networkResponse;
            } catch (error) {
                console.log('[Service Worker] Fetch failed for resource:', event.request.url, error);
                // If it's an image, we can return a fallback placeholder if needed
                return new Response('Network error occurred', { status: 408, statusText: 'Network Error' });
            }
        })()
    );
});

// 3. Push Event: Handle incoming push notifications in the background
self.addEventListener('push', (event) => {
    if (!event.data) return;
    
    let data;
    try {
        data = event.data.json();
    } catch (e) {
        data = {
            title: 'Gio Salon & Angie Nails',
            body: event.data.text(),
            url: '/'
        };
    }
    
    const options = {
        body: data.body || 'Tienes una nueva actualización.',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-192x192.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        }
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'Gio Salon & Angie Nails', options)
    );
});

// 4. Notification Click Event: Redirect user to the corresponding URL
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    const urlToOpen = new URL(event.notification.data.url, self.location.origin).href;
    
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((windowClients) => {
            // Si la ventana/pestaña ya está abierta, hacerle foco
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            // Si no está abierta, abrir una nueva ventana
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

