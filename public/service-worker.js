self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open('paypark-v1').then(function(cache) {
      return cache.addAll([
        '/',
        '/css/app.css',
        '/js/app.js',
        '/website/image/logo.png',
        // Add more assets as needed
      ]);
    })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request);
    })
  );
});
