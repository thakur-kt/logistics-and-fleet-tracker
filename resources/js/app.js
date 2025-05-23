import "./bootstrap";

import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./components/App.vue";
import router from "./router";
import "../css/app.css"; // Import Tailwind CSS styles
import 'leaflet/dist/leaflet.css'; // Import Leaflet map styles
import api from "./axios"; // Import configured axios instance
import { useAuthStore } from "@/stores/auth"; // Pinia store for authentication
import { vHasRole, vHasPermission } from './directives/permission.js'; // Custom permission directives
import piniaPersistedstate from "pinia-plugin-persistedstate";

// Create Pinia store and enable persisted state plugin
const pinia = createPinia();
pinia.use(piniaPersistedstate);

// Create Vue app, register router and Pinia
const app = createApp(App).use(router).use(pinia);

// Register global custom directives for role/permission checks
app.directive('has-role', vHasRole);
app.directive('has-permission', vHasPermission);

// Mount the Vue app to the DOM
app.mount("#app")

// Axios request interceptor to attach Bearer token if available
api.interceptors.request.use(
    (config) => {
        const auth = useAuthStore();
        if (auth.token) {
            // Attach Authorization header with Bearer token
            config.headers.Authorization = `Bearer ${auth.token}`;
        }
        return config;
    },
    (error) => Promise.reject(error) // Forward any request errors
);
