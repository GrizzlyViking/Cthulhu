import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // Pages/Components holds shared building blocks, not Inertia pages; keeping
    // them out of the glob stops them becoming build entry points of their own
    // (which broke rollup's chunk facades and left real pages such as
    // Character.vue without a manifest entry).
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob(['./Pages/**/*.vue', '!./Pages/Components/**'])),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.config.errorHandler = (err, instance, info) => {
            console.error('Vue errorHandler:', err);
            console.error('Info:', info);
            console.error('Component:', instance?.type?.name || instance);
        };

        return vueApp
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
