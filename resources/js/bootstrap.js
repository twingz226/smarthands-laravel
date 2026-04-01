import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

// Initialize Laravel Echo
if (process.env.MIX_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'mt1',
        forceTLS: true
    });
} else {
    // Graceful fallback for Echo if keys are missing from .env
    console.warn('Laravel Echo is not initialized: PUSHER keys are missing in .env');
    window.Echo = {
        channel: () => ({
            listen: () => {
                console.warn('Echo.listen() called, but Echo is not initialized.');
            }
        })
    };
}
