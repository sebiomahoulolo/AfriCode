var staticCacheName = "africode-pwa-v" + new Date().getTime();
var offlineUrl = "/offline";

// Réception d'une notification push depuis le serveur
self.addEventListener("push", function (event) {
    var data = {};
    try { data = event.data ? event.data.json() : {}; } catch (e) {}

    var title   = data.title   || "AfriCode";
    var options = {
        body:    data.body    || "Vous avez une nouvelle notification",
        icon:    "/images/icons/africode-192.png",
        badge:   "/images/icons/africode-192.png",
        vibrate: [200, 100, 200],
        tag:     data.tag     || "africode-notif",
        data:    { url: data.url || "/" },
        actions: data.actions || []
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Clic sur une notification
self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    var targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : "/";

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then(function (clientList) {
            for (var i = 0; i < clientList.length; i++) {
                if (clientList[i].url === targetUrl && "focus" in clientList[i]) {
                    return clientList[i].focus();
                }
            }
            if (clients.openWindow) return clients.openWindow(targetUrl);
        })
    );
});

var filesToCache = [
    offlineUrl,
    "/",
    "/courses",
    "/nos-services",
    "/Contactez-nous",
    "/logo.png",
    "/images/icons/africode-192.png",
    "/images/icons/africode-512.png"
];

// Installation : mettre en cache la page offline et les icônes
self.addEventListener("install", function (event) {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then(function (cache) {
            return cache.addAll(filesToCache);
        }).catch(function (err) {
            console.warn("Laravel PWA: cache addAll failed", err);
        })
    );
});

// Activation : supprimer les anciens caches PWA
self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames
                    .filter(function (name) { return name.startsWith("africode-pwa-") || name.startsWith("pwa-"); })
                    .filter(function (name) { return name !== staticCacheName; })
                    .map(function (name) { return caches.delete(name); })
            );
        }).then(function () { return self.clients.claim(); })
    );
});

// Stratégie : navigation → réseau d’abord puis page offline ; le reste → cache puis réseau
self.addEventListener("fetch", function (event) {
    if (event.request.method !== "GET") return;

    var requestUrl = new URL(event.request.url);
    if (requestUrl.origin !== self.location.origin) return;

    // Requête vers la page offline : répondre depuis le cache
    if (requestUrl.pathname === "/offline" || requestUrl.pathname === "/offline/") {
        event.respondWith(caches.match(offlineUrl).then(function (cached) {
            return cached || fetch(event.request);
        }));
        return;
    }

    // Navigation (page HTML) : réseau d’abord, sinon cache, sinon page offline
    if (event.request.mode === "navigate") {
        event.respondWith(
            fetch(event.request)
                .then(function (response) {
                    if (response && response.status === 200 && response.type === "basic") {
                        var clone = response.clone();
                        caches.open(staticCacheName).then(function (cache) {
                            cache.put(event.request, clone);
                        });
                    }
                    return response;
                })
                .catch(function () {
                    return caches.match(event.request).then(function (cached) {
                        return cached || caches.match(offlineUrl);
                    });
                })
        );
        return;
    }

    // Autres ressources : cache d’abord, puis réseau, puis page offline pour same-origin
    event.respondWith(
        caches.match(event.request).then(function (cached) {
            return cached || fetch(event.request).then(function (response) {
                if (response && response.status === 200 && response.type === "basic") {
                    var clone = response.clone();
                    caches.open(staticCacheName).then(function (cache) {
                        cache.put(event.request, clone);
                    });
                }
                return response;
            }).catch(function () {
                return caches.match(offlineUrl);
            });
        })
    );
});
