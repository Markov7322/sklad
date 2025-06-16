const CACHE_NAME = 'getmk-cache-v1';
const URLS = [
  '/',
];
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(URLS))
  );
});
self.addEventListener('fetch', event => {
  // Skip non-GET requests and requests with unsupported schemes
  if (event.request.method !== 'GET' || !event.request.url.startsWith('http')) {
    return;
  }

  if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') {
    return;
  }

  event.respondWith(
    caches.match(event.request).then(cached => {
      if (cached) {
        return cached;
      }

      return fetch(event.request)
        .then(response => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
          return response;
        })
        .catch(() => caches.match(event.request));
    })
  );
});
self.addEventListener('push', event => {
  if (!(self.Notification && event.data)) return;
  const data = event.data.json();
  const options = {
    body: data.body,
    icon: data.icon,
    data: { url: data.url }
  };
  event.waitUntil(self.registration.showNotification(data.title, options));
});
self.addEventListener('notificationclick', event => {
  event.notification.close();
  const url = event.notification.data.url;
  if (url) {
    event.waitUntil(clients.openWindow(url));
  }
});
