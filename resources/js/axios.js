
import axios from 'axios'
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'https://logistics-and-fleet-tracker.test/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json', // also set Accept header to json
  },
})

export default api