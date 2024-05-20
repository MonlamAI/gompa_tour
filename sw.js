// On install - caching the application shell
var staticCasheName = "pwa";
self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open(staticCasheName).then(function(cache) {
      // cache any static files that make up the application shell
      return cache.add('/');
    })
  );
});

// On network request
self.addEventListener('fetch', function(event) {
  console.log(event.request.url);
  event.respondWith(
    // Try the cache
    caches.match(event.request).then(function(response) {
      //If response found return it, else fetch again
      return response || fetch(event.request);
    })
  );
});