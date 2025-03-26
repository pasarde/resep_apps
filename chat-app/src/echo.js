import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: 'f6e2313499ceeb976b04', // Ganti dengan PUSHER_APP_KEY
    cluster: 'ap1', // Ganti dengan PUSHER_APP_CLUSTER
    forceTLS: true,
    authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth',
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
    },
});

export default echo;