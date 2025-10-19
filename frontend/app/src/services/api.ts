import axios from 'axios'

// During development, Vite proxy will forward /api to the nginx container.
// In production, set baseURL via env (e.g., import.meta.env.VITE_API_BASE_URL)
export const api = axios.create({
  baseURL: '/api',
})

export default api
