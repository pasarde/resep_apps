import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000',
    withCredentials: true,
});

let xsrfToken = null;

const fetchCsrfToken = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie', {
            withCredentials: true,
        });
        console.log('CSRF token fetched:', response);
        

        // Coba ambil XSRF-TOKEN dari response headers
        let tokenFromHeaders = response.headers['set-cookie']
            ?.find(cookie => cookie.startsWith('XSRF-TOKEN='))
            ?.split('=')[1]
            ?.split(';')[0];

        if (tokenFromHeaders) {
            xsrfToken = decodeURIComponent(tokenFromHeaders);
            console.log('XSRF-TOKEN from headers:', xsrfToken);
        } else {
            console.log('XSRF-TOKEN not found in response headers');
        }

        // Coba lagi dari cookie
        if (!xsrfToken) {
            xsrfToken = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];
            if (xsrfToken) {
                xsrfToken = decodeURIComponent(xsrfToken);
                console.log('XSRF-TOKEN after fetch:', xsrfToken);
            } else {
                console.log('XSRF-TOKEN still not found in cookies after fetch');
            }
        }

        console.log('Cookies after CSRF fetch:', document.cookie);
    } catch (error) {
        console.error('Failed to fetch CSRF token:', error);
    }
};

api.interceptors.request.use(
    async (config) => {
        const token = localStorage.getItem('token');
        console.log('Token in localStorage:', token);
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        // Cek apakah ini request POST, PUT, DELETE (yang butuh CSRF)
        if (['post', 'put', 'delete'].includes(config.method.toLowerCase())) {
            if (!xsrfToken) {
                console.log('XSRF-TOKEN not found, attempting to fetch...');
                await fetchCsrfToken();
            }

            if (xsrfToken) {
                config.headers['X-CSRF-TOKEN'] = xsrfToken;
                console.log('X-CSRF-TOKEN set in headers:', xsrfToken);
            } else {
                console.log('XSRF-TOKEN still not found after retry');
            }
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Fungsi untuk ambil CSRF token
const getCsrfToken = async () => {
    if (xsrfToken) return xsrfToken;
    await fetchCsrfToken();
    return xsrfToken;
};

// Fungsi untuk ambil data user
const fetchUser = async () => {
    return api.get('/api/user');
};

// Fungsi untuk ambil chat
const fetchChat = async () => {
    return api.get('/api/chat');
};

// Fungsi untuk kirim pesan
const sendMessage = async (message) => {
    return api.post('/api/chat', { message });
};

export { getCsrfToken, fetchUser, fetchChat, sendMessage };
export default api;