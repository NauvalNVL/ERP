import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, Link, router } from "@inertiajs/vue3";
import AppLayout from "./Layouts/AppLayout.vue";
import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy'; // Pastikan path ini benar jika ziggy.js tidak di root js
import { setupCsrfHandler } from './csrf-handler';

// Fungsi global untuk mendapatkan CSRF token
window.getCsrfToken = () => {
    // Try to get token from meta tag first
    let token = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Fallback to any token in the page
    if (!token) {
        token = document.querySelector('input[name="_token"]')?.value;
    }

    return token || '';
};

createInertiaApp({
    resolve: async (name) => {
        // Gunakan dynamic import untuk semua halaman Inertia sehingga tiap halaman menjadi chunk terpisah
        const pages = import.meta.glob("./Pages/**/*.vue");

        let loader = pages[`./Pages/${name}.vue`];

        if (typeof loader === 'undefined') {
            // Try normalized path (convert to lowercase)
            const normalizedPath = `./Pages/${name.toLowerCase()}.vue`;
            const exactMatch = Object.keys(pages).find(p => p.toLowerCase() === normalizedPath);
            loader = exactMatch ? pages[exactMatch] : undefined;

            // If still undefined, check subdirectory components
            if (typeof loader === 'undefined') {
                const parts = name.split('/');
                const componentName = parts.pop();
                const directory = parts.join('/');

                // Try exact match with directory
                const potentialPath = `./Pages/${directory}/${componentName}.vue`;
                const directMatch = Object.keys(pages).find(p => p === potentialPath);
                loader = directMatch ? pages[directMatch] : undefined;

                // Try case-insensitive match
                if (typeof loader === 'undefined') {
                    const normalizedSubPath = (`./Pages/${directory}/${componentName}`).toLowerCase() + '.vue';
                    const subMatch = Object.keys(pages).find(p => p.toLowerCase() === normalizedSubPath);
                    loader = subMatch ? pages[subMatch] : undefined;
                }
            }
        }

        if (typeof loader === 'undefined') {
            console.error(`Inertia page not found: ${name}`);
            throw new Error(`Inertia page not found: ${name}`);
        }

        const page = await loader();
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props),
            mounted() {
                // Add a class to the body when the app is mounted
                document.body.classList.add('app-loaded');
            }
        });

        app.component("AppLayout", AppLayout);
        app.component("Link", Link);

        // Global mixin to provide CSRF token to all components
        app.mixin({
            methods: {
                getCsrfToken() {
                    return window.getCsrfToken();
                },

                // Helper for making API requests with CSRF token
                async apiRequest(url, options = {}) {
                    const csrfToken = this.getCsrfToken();
                    const defaultOptions = {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    };

                    return fetch(url, { ...defaultOptions, ...options });
                }
            }
        });

        // Add global page transition directive
        app.directive('page-transition', {
            mounted(el) {
                el.classList.add('page-enter-from');
                requestAnimationFrame(() => {
                    el.classList.add('page-enter-active');
                    el.classList.remove('page-enter-from');
                });
            },
            beforeUnmount(el) {
                el.classList.add('page-leave-active', 'page-leave-to');
                el.classList.remove('page-enter-active');
            }
        });

        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        app.mount(el);

        // Setup CSRF handler after app is mounted
        setupCsrfHandler();
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
        delay: 0,
    },
});

// Global error handler for 419 responses
router.on('error', (event) => {
    const response = event.detail.response;
    if (response && response.status === 419) {
        console.log('CSRF token expired (419), reloading page...');
        router.reload({
            preserveState: false,
            preserveScroll: false,
            onSuccess: () => {
                console.log('Page reloaded with fresh CSRF token');
            },
            onError: () => {
                console.log('Failed to reload, redirecting to login');
                window.location.href = '/login';
            }
        });
    }
});
