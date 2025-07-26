const CACHE_NAME = 'paypark-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/website/css/style.css',
    '/website/js/custom.js',
    '/images/icons/icon.svg',
    'https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js',
    'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.2/dist/css/splide.min.css',
    'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.2/dist/js/splide.min.js',
    'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});
self.addEventListener('fetch', function (event) {
    const url = new URL(event.request.url);
    // 🚫 Skip login/logout/register/auth-related routes
    const excludedPaths = ['/login', '/logout', '/register', '/user_login',
        'display_parking_booking'
    ];
    if (excludedPaths.some(path => url.pathname.startsWith(path))) {
        return;
    }
    // Always use NetworkFirst for homepage
    const fetchEvery = [
        '/parking_map_list',
        '/',
        '/user_vehicle',
        '/user_vehicle_store',
        '/display_parking_booking',
        '/parking_space/',
        '/parking_slots',
        '/parkingspace_booking',
        '/checkout'
    ]
    if (fetchEvery.some(path => url.pathname.startsWith(path))) {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                    return response;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    // ✅ Proceed with caching for other safe GET requests
    event.respondWith(
        caches.match(event.request)
            .then(function (response) {
                if (response) {
                    return response;
                }

                return fetch(event.request).then(function (response) {
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME)
                        .then(function (cache) {
                            cache.put(event.request, responseToCache);
                        });

                    return response;
                });
            })
    );
});


// self.addEventListener('fetch', function(event) {
//     event.respondWith(
//         caches.match(event.request)
//             .then(function(response) {
//                 // Cache hit - return response
//                 if (response) {
//                     return response;
//                 }

//                 return fetch(event.request).then(
//                     function(response) {
//                         // Check if we received a valid response
//                         if(!response || response.status !== 200 || response.type !== 'basic') {
//                             return response;
//                         }

//                         var responseToCache = response.clone();

//                         caches.open(CACHE_NAME)
//                             .then(function(cache) {
//                                 cache.put(event.request, responseToCache);
//                             });

//                         return response;
//                     }
//                 );
//             })
//     );
// });

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
