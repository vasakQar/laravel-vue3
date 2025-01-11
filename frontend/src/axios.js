import axios from 'axios';

export const backendUrl = 'http://localhost:8000';

const api = axios.create({
    baseURL: backendUrl + '/api',
    headers: {
        'Content-Type': 'application/json',
    },

});

axios.defaults.withCredentials = true;

api.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});


export default api;
