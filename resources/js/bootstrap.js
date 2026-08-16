import axios from 'axios';
import { router } from '@inertiajs/vue3';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/*
 * Most of the character sheet saves over axios rather than through Inertia, so
 * a page whose session has lapsed gets a bare 419 that no .then() looks at, and
 * the player watches a change quietly not happen. Ask for the page again: the
 * message the server flashed comes back with it and the banner appears, and
 * because that request is a GET it passes the CSRF check and leaves a fresh
 * token in the tab — so trying again works rather than failing the same way.
 */
window.axios.interceptors.response.use(null, (error) => {
    if (error.response?.status === 419) {
        router.reload();
    }

    return Promise.reject(error);
});
