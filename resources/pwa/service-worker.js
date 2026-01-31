const CACHE_NAME = 'sndp-dashboard-v1';
const DYNAMIC_CACHE = 'sndp-dynamic-v1';
const urlsToCache = [
    '/offline.html',
    '/images/default-avatar.jpeg',
    'https://cdn.tailwindcss.com',
    'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap'
];

// Install Event: Cache Static Assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[Service Worker] Caching static assets');
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting();
});

// Activate Event: Clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME && cache !== DYNAMIC_CACHE) {
                        console.log('[Service Worker] Clearing old cache');
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch Event: Cache First for Assets, Network First for HTML
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);

    // 1. Handle API Requests (Network Only - don't cache deeply to avoid stale data)
    if (url.pathname.startsWith('/api') || url.pathname.startsWith('/settings')) {
        // Optionally implement Background Sync here if offline
        return;
    }

    // 2. Handle HTML Pages (Network First -> Offline Fallback)
    if (request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    // Cache the latest copy
                    const resClone = response.clone();
                    caches.open(DYNAMIC_CACHE).then(cache => {
                        cache.put(request, resClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then(response => {
                        return response || caches.match('/offline.html');
                    });
                })
        );
        return;
    }

    // 3. Handle Static Assets (Cache First -> Network)
    event.respondWith(
        caches.match(request).then(response => {
            return response || fetch(request).then(fetchRes => {
                return caches.open(DYNAMIC_CACHE).then(cache => {
                    cache.put(request, fetchRes.clone());
                    return fetchRes;
                });
            });
        })
    );
});

// Background Sync (Stub)
self.addEventListener('sync', event => {
    if (event.tag === 'sync-settings') {
        console.log('[Service Worker] Syncing settings in background...');
        // Implement queued requests replay logic here
    }
});

// Push Notifications (Stub)
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'New Notification';
    const options = {
        body: data.body || 'You have a new update!',
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/icon-72x72.png'
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

// Notification Click
self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/dashboard')
    );
});
