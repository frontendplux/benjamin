function googleTranslateElementInit2() {
    new google.translate.TranslateElement(
        {
            pageLanguage: "en",
            autoDisplay: false
        },
        "google_translate_element"
    );

    restoreLanguage();
}

function changeLang(lang) {

    const select = document.querySelector(".goog-te-combo");

    if (!select) {
        return;
    }

    localStorage.setItem("language", lang);

    select.value = lang;
    select.dispatchEvent(new Event("change"));
}

function restoreLanguage() {

    const lang = localStorage.getItem("language") || "en";

    let timer = setInterval(() => {

        const select = document.querySelector(".goog-te-combo");

        if (select) {

            clearInterval(timer);

            select.value = lang;
            select.dispatchEvent(new Event("change"));

        }

    }, 500);

}

async function registerPush() {
updateNotificationButton();
    if (!("serviceWorker" in navigator)) {
        console.log("Service Workers are not supported.");
        return;
    }

    if (!("PushManager" in window)) {
        console.log("Push Notifications are not supported.");
        return;
    }

    try {

        // Register Service Worker
        const registration = await navigator.serviceWorker.register("/sw.js");

        await navigator.serviceWorker.ready;

        // Request permission
        const permission = await Notification.requestPermission();

        if (permission !== "granted") {
            console.log("Notification permission denied.");
            return;
        }

        // Prevent duplicate subscriptions
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    "BDQZfn8Pxwu8Wrn4ltvVF6etP30ELZFSCVA8o74TBvoX4qOh5P3kkK3haZk16VssI5si-VOBeksP5do7u_6iKWM"
                )
            });

        }

        const response = await fetch("/save-push-notificaation.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(subscription)
        });

        const result = await response.json();

        console.log(result);

    } catch (err) {
        console.error("Push Registration Error:", err);
    }
}

function urlBase64ToUint8Array(base64String) {

    const padding = "=".repeat((4 - base64String.length % 4) % 4);

    const base64 = (base64String + padding)
        .replace(/-/g, "+")
        .replace(/_/g, "/");

    const rawData = atob(base64);

    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}

// document.addEventListener("DOMContentLoaded", () => {
//     registerPush();
//     updateNotificationButton()
// });

function updateNotificationButton() {

    const btn = document.getElementById("enableNotification");
      btn.style.display='none'
    if (!btn) return;

    if (!("Notification" in window)) {
        btn.style.display = "none";
        return;
    }

    if (Notification.permission === "granted") {
        btn.style.display = "none";
        
    } else {
        btn.style.display = "flex";
    }

}
