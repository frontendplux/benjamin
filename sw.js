self.addEventListener("push", event => {

    let data = {};

    if (event.data) {
        data = event.data.json();
    }

    event.waitUntil(

        self.registration.showNotification(data.title || "Bright Path", {

            body: data.body || "You have a new notification.",

            icon: "/icon.png",

            badge: "/badge.png",

            data: data.url || "/"

        })

    );

});

self.addEventListener("notificationclick", event => {

    event.notification.close();

    event.waitUntil(
        clients.openWindow(event.notification.data)
    );

});

// ==============================================
const CACHE = "brightpath-v1";

const FILES = [
    "/",
    "/manifest.json",
    "/icons/icon-192.png",
    "/icons/icon-512.png"
];

self.addEventListener("install", event => {

    event.waitUntil(

        caches.open(CACHE)
            .then(cache => cache.addAll(FILES))

    );

});

self.addEventListener("fetch", event => {

    event.respondWith(

        caches.match(event.request)
            .then(response => response || fetch(event.request))

    );

});