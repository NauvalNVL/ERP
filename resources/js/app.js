import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import AppLayout from "./Layouts/AppLayout.vue";

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
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        if (typeof page === 'undefined') {
            // Check if the component is in a subdirectory
            // e.g., auth/Login.vue will be resolved if name is 'auth/Login'
            // Also handle cases where 'name' might be 'Auth/Login' due to route definition
             const parts = name.split('/');
             const componentName = parts.pop();
             const directory = parts.join('/').toLowerCase();
             if (directory) {
                page = pages[`./Pages/${directory}/${componentName}.vue`];
                if (!page) {
                    // try matching with first letter capitalized for componentName
                    const capitalizedComponentName = componentName.charAt(0).toUpperCase() + componentName.slice(1);
                    page = pages[`./Pages/${directory}/${capitalizedComponentName}.vue`];
                }
             }
        }
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
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
        delay: 0,
    },
});
