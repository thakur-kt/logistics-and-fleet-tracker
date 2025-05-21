import "./bootstrap";

import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./components/App.vue";
import router from "./router";
import "../css/app.css"; // Import Tailwind
import 'leaflet/dist/leaflet.css';
import api from "./axios";
import { useAuthStore } from "@/stores/auth";
import { vHasRole, vHasPermission } from './directives/permission.js';
import piniaPersistedstate from "pinia-plugin-persistedstate";
const pinia = createPinia();
pinia.use(piniaPersistedstate);
const app = createApp(App).use(router).use(pinia);
// Register global custom directives
app.directive('has-role', vHasRole);
app.directive('has-permission', vHasPermission);
app.mount("#app")
api.interceptors.request.use(
    (config) => {
        const auth = useAuthStore();
        if (auth.token) {
            config.headers.Authorization = `Bearer ${auth.token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);
