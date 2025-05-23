import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import AppLayout from "./Layouts/AppLayout.vue";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
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
