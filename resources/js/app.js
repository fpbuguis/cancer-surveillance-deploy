// import './bootstrap';
// import '../css/app.css';

// import { createApp, h } from 'vue';
// import { createInertiaApp } from '@inertiajs/vue3';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// const appName = import.meta.env.VITE_APP_NAME || 'CSS';

// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
//     setup({ el, App, props, plugin }) {
//         return createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .use(ZiggyVue)
//             .mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });

import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config'; // Import PrimeVue
import 'primevue/resources/themes/saga-blue/theme.css'; // Import PrimeVue theme (choose any theme you like)
import 'primevue/resources/primevue.min.css'; // Core PrimeVue CSS
import 'primeicons/primeicons.css'; // PrimeIcons
import 'primeflex/primeflex.css'; // Optional for flex utilities

const appName = import.meta.env.VITE_APP_NAME || 'CSS';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue) // Add PrimeVue to the app
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
