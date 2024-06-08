import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '03bd259f1cd96dd085b2',
    cluster: 'eu',
    forceTLS: true
});
