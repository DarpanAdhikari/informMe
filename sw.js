const CACHE_NAME = 'my-cache-v1';

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return cache.addAll([
        './',
        './additional/css/header.css',
        './additional/script/header.js',
        './manifest.json',
        './additional/addition/logo192x192.png',
      ]);
    })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (cacheNames) {
      return Promise.all(
        cacheNames
          .filter(function (name) {
            return name !== CACHE_NAME;
          })
          .map(function (name) {
            return caches.delete(name);
          })
      );
    })
  );
});

self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request).then(function (cachedResponse) {
      if (navigator.onLine) {
        return fetchAndUpdateCache(event.request);
      } else {
        return cachedResponse || fetch(event.request);
      }
    })
  );
});

function fetchAndUpdateCache(request) {
  return fetch(request)
    .then(function (response) {
      if (response && response.status === 200) {
        const responseClone = response.clone();
        caches.open(CACHE_NAME).then(function (cache) {
          cache.put(request, responseClone);
        });
      }
      return response;
    })
}
