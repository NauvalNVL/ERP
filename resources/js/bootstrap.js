import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to get fresh CSRF token
const getCsrfToken = () => {
    let token = document.head.querySelector('meta[name="csrf-token"]');
    return token ? token.content : null;
};

// Set initial CSRF token
let token = getCsrfToken();
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Untuk keamanan: withCredentials memastikan cookies disertakan di semua request
window.axios.defaults.withCredentials = true;

// Add request interceptor to refresh CSRF token before each request
window.axios.interceptors.request.use(
    (config) => {
        // Get fresh CSRF token for each request
        const freshToken = getCsrfToken();
        if (freshToken) {
            config.headers['X-CSRF-TOKEN'] = freshToken;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor to handle 419 errors (CSRF token mismatch)
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 419) {
            console.warn('CSRF token mismatch. Session may have expired. Redirecting to login...');
            // Redirect to login page when session expires
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
