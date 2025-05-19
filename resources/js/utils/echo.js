import Echo from "laravel-echo";
import Pusher from "pusher-js";

let echo = null;

export function initEcho(token) {
    if (echo) return echo;

    window.Pusher = Pusher;

    echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
        wsHost: import.meta.env.VITE_PUSHER_HOST
            ? import.meta.env.VITE_PUSHER_HOST
            : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
        wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
        wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
        enabledTransports: ["ws", "wss"],
        authEndpoint: "/api/broadcasting/auth",
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        },
    });

    return echo;
}
