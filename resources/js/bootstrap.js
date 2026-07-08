import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Laravel Echo は Reverb / Pusher 設定がある場合に有効化します。
 * BROADCAST_CONNECTION=log の開発時はそのまま動作します。
 */
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    import('laravel-echo').then(({ default: Echo }) => {
        import('pusher-js').then(({ default: Pusher }) => {
            window.Pusher = Pusher;

            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: reverbKey,
                wsHost: import.meta.env.VITE_REVERB_HOST,
                wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
                wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
                forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
                enabledTransports: ['ws', 'wss'],
            });
        });
    });
}
