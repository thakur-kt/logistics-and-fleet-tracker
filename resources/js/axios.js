import axios from 'axios'

// Enable sending cookies with requests (for authentication/session)
axios.defaults.withCredentials = true;

// Enable automatic XSRF token handling (for CSRF protection)
axios.defaults.withXSRFToken = true;

// Create a reusable axios instance with default config
const api = axios.create({
  // Set the base URL from environment variable or fallback to local API
  baseURL: import.meta.env.VITE_API_BASE_URL || 'https://logistics-and-fleet-tracker.test/api',
  withCredentials: true, // Always send credentials (cookies)
  headers: {
    'Content-Type': 'application/json', // Send JSON by default
    'Accept': 'application/json',       // Expect JSON responses
  },
})

export default api // Export the configured axios instance for use in your app