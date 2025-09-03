<template>
    <div class="h-screen flex overflow-hidden bg-gray-100">
        <!-- Sidebar -->
        <div
            :class="{
                'translate-x-0': sidebarStore.mobileOpen,
                '-translate-x-full': !sidebarStore.mobileOpen && !isDesktop,
            }"
            class="fixed inset-y-0 left-0 z-30 w-3/4 sm:w-64 transform transition-transform duration-300 ease-in-out shadow-lg lg:relative lg:translate-x-0 lg:flex-shrink-0 lg:h-screen"
        >
            <Sidebar />
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <nav
                class="sticky top-0 z-10 transition-transform duration-300 ease-out"
                :class="
                    headerHidden
                        ? '-translate-y-full -mt-16 shadow-none'
                        : 'translate-y-0 mt-0 bg-white shadow-sm'
                "
            >
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center min-w-0">
                            <button
                                @click="toggleSidebar"
                                class="p-2 -ml-2 mr-2 text-gray-500 rounded-full lg:hidden hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-300"
                                :class="{
                                    'rotate-90': sidebarStore.mobileOpen,
                                }"
                            >
                                <span class="sr-only">{{
                                    sidebarStore.mobileOpen
                                        ? "Close sidebar"
                                        : "Open sidebar"
                                }}</span>
                                <i
                                    class="fas text-xl"
                                    :class="
                                        sidebarStore.mobileOpen
                                            ? 'fa-times'
                                            : 'fa-bars'
                                    "
                                ></i>
                            </button>
                            <h1
                                class="text-xl font-semibold text-gray-800 truncate"
                            >
                                {{ header }}
                            </h1>
                        </div>
                        <!-- Header Search -->
                        <div
                            class="hidden md:flex items-center w-full max-w-md ml-4 relative"
                        >
                            <div class="relative flex-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <i class="fas fa-search text-gray-400"></i>
                                </span>
                                <input
                                    type="text"
                                    v-model="menuQuery"
                                    @focus="
                                        openSearch = true;
                                        ensureMenuRoutes();
                                    "
                                    @input="openSearch = true"
                                    @keydown.down.prevent="highlightNext()"
                                    @keydown.up.prevent="highlightPrev()"
                                    @keydown.enter.prevent="
                                        navigateHighlighted()
                                    "
                                    placeholder="Search menu..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                />
                                <!-- Results dropdown -->
                                <div
                                    v-if="
                                        openSearch &&
                                        filteredMenuRoutes.length > 0
                                    "
                                    class="absolute mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-64 overflow-auto z-50"
                                >
                                    <ul class="py-1 text-sm text-gray-700">
                                        <li
                                            v-for="(
                                                item, idx
                                            ) in filteredMenuRoutes"
                                            :key="item.uri"
                                            @mousedown.prevent="go(item.uri)"
                                            :class="
                                                idx === highlightedIndex
                                                    ? 'bg-blue-50 text-blue-700'
                                                    : 'hover:bg-gray-50'
                                            "
                                            class="px-3 py-2 cursor-pointer flex items-center justify-between"
                                        >
                                            <span class="truncate">{{
                                                item.title
                                            }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main
                ref="scrollArea"
                class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto"
                @click="closeSidebarOnMobile"
            >
                <slot></slot>
            </main>
        </div>

        <!-- Mobile sidebar backdrop -->
        <div
            v-show="sidebarStore.mobileOpen"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden backdrop-blur-sm transition-opacity duration-300"
            :class="{
                'opacity-100': sidebarStore.mobileOpen,
                'opacity-0': !sidebarStore.mobileOpen,
            }"
            @click="closeSidebar"
        ></div>

        <!-- Fixed Mobile Close Button -->
        <button
            v-if="!isDesktop && sidebarStore.mobileOpen"
            @click="closeSidebar"
            class="fixed bottom-4 right-4 z-50 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-200 lg:hidden"
            aria-label="Close sidebar"
        >
            <i class="fas fa-times text-xl"></i>
        </button>

        <ToastContainer />
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from "vue";
import Sidebar from "./Partials/Sidebar.vue";
import sidebarStore from "./Partials/sidebarStore";
import ToastContainer from "@/Components/ToastContainer.vue";
import { router } from "@inertiajs/vue3";

defineProps({
    header: {
        type: String,
        default: "Dashboard",
    },
});

const isDesktop = ref(window.innerWidth >= 1024);
const headerHidden = ref(false);
let lastScrollY = typeof window !== "undefined" ? window.scrollY : 0;
let ticking = false;
const scrollArea = ref(null);

const toggleSidebar = () => {
    sidebarStore.toggleMobile();
};

const closeSidebar = () => {
    sidebarStore.closeMobile();
};

const closeSidebarOnMobile = () => {
    if (!isDesktop.value && sidebarStore.mobileOpen) {
        sidebarStore.closeMobile();
    }
};

const handleResize = () => {
    isDesktop.value = window.innerWidth >= 1024;
    if (isDesktop.value) {
        sidebarStore.mobileOpen = false;
    }
};

watch(
    () => window.location.pathname,
    () => {
        if (!isDesktop.value) {
            sidebarStore.closeMobile();
        }
    }
);

onMounted(() => {
    window.addEventListener("resize", handleResize);
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !isDesktop.value && sidebarStore.mobileOpen) {
            sidebarStore.closeMobile();
        }
    });
    // Hide/Show header on scroll
    const onScroll = () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const currentY = scrollArea.value
                    ? scrollArea.value.scrollTop || 0
                    : window.scrollY || 0;
                const delta = currentY - lastScrollY;
                const threshold = 8;
                // Do not hide when search dropdown is open
                if (
                    delta > threshold &&
                    currentY > 64 &&
                    !openSearch.value &&
                    !sidebarStore.mobileOpen
                ) {
                    headerHidden.value = true;
                } else if (delta < -threshold) {
                    headerHidden.value = false;
                }
                lastScrollY = currentY;
                ticking = false;
            });
            ticking = true;
        }
    };
    // Attach to the actual scroll container
    if (scrollArea.value) {
        scrollArea.value.addEventListener("scroll", onScroll, {
            passive: true,
        });
        lastScrollY = scrollArea.value.scrollTop || 0;
    } else {
        // Fallback to window if ref not ready
        window.addEventListener("scroll", onScroll, { passive: true });
        lastScrollY = window.scrollY || 0;
    }
    // Cleanup
    onUnmounted(() => {
        if (scrollArea.value) {
            scrollArea.value.removeEventListener("scroll", onScroll);
        } else {
            window.removeEventListener("scroll", onScroll);
        }
    });
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

// Header search state
const menuRoutes = ref([]);
const menuQuery = ref("");
const openSearch = ref(false);
const highlightedIndex = ref(-1);

const ensureMenuRoutes = async () => {
    if (menuRoutes.value.length > 0) return;
    try {
        const res = await fetch("/api/menu-routes", {
            headers: { Accept: "application/json" },
        });
        if (res.ok) {
            menuRoutes.value = await res.json();
        }
    } catch (e) {
        console.error("Failed loading menu routes", e);
    }
};

const filteredMenuRoutes = computed(() => {
    const q = menuQuery.value.trim().toLowerCase();
    const list = menuRoutes.value;
    if (!q) return list.slice(0, 10);
    return list
        .filter(
            (r) =>
                r.title.toLowerCase().includes(q) ||
                r.uri.toLowerCase().includes(q) ||
                (r.name && r.name.toLowerCase().includes(q))
        )
        .slice(0, 15);
});

const go = (uri) => {
    openSearch.value = false;
    menuQuery.value = "";
    highlightedIndex.value = -1;
    router.visit(uri);
};

const highlightNext = () => {
    if (filteredMenuRoutes.value.length === 0) return;
    highlightedIndex.value =
        (highlightedIndex.value + 1) % filteredMenuRoutes.value.length;
};
const highlightPrev = () => {
    if (filteredMenuRoutes.value.length === 0) return;
    highlightedIndex.value =
        (highlightedIndex.value - 1 + filteredMenuRoutes.value.length) %
        filteredMenuRoutes.value.length;
};
const navigateHighlighted = () => {
    const item = filteredMenuRoutes.value[highlightedIndex.value];
    if (item) go(item.uri);
};
</script>

<style scoped>
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}
</style>
