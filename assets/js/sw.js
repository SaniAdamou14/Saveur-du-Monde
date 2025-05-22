const CACHE_NAME = 'saveurs-du-monde-v1';

const urlsToCache = [
    '/assets/images/restaurant.jpg',
    '/assets/images/cola.jpg',
    '/assets/images/charwarma.jpg',
    '/assets/images/cheesburger.jpg'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('Cache ouvert');
            return cache.addAll(urlsToCache);
        }).catch(error => {
            console.error('Erreur lors de la mise en cache initiale:', error);
        })
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.destination === 'image') {
        event.respondWith(
            caches.match(event.request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then(fetchResponse => {
                    if (!fetchResponse || fetchResponse.status !== 200) {
                        return fetchResponse;
                    }
                    const responseToCache = fetchResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                    return fetchResponse;
                }).catch(() => {
                    return caches.match('/assets/images/restaurant.jpg');
                });
            })
        );
    }
});

self.addEventListener('activate', (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});