<template>
  <div class="h-screen flex overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    <div
      :class="{ 'translate-x-0': sidebarStore.mobileOpen, '-translate-x-full': !sidebarStore.mobileOpen && !isDesktop }"
      class="fixed inset-y-0 left-0 z-30 w-3/4 sm:w-64 transform transition-transform duration-300 ease-in-out shadow-lg lg:relative lg:translate-x-0 lg:flex-shrink-0 lg:h-screen">
      <Sidebar />
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
      <!-- Top Navigation -->
      <nav class="bg-white shadow-sm sticky top-0 z-10">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16">
            <div class="flex items-center min-w-0">
              <button
                @click="toggleSidebar"
                class="p-2 -ml-2 mr-2 text-gray-500 rounded-full lg:hidden hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-300"
                :class="{ 'rotate-90': sidebarStore.mobileOpen }"
              >
                <span class="sr-only">{{ sidebarStore.mobileOpen ? 'Close sidebar' : 'Open sidebar' }}</span>
                <i class="fas text-xl" :class="sidebarStore.mobileOpen ? 'fa-times' : 'fa-bars'"></i>
              </button>
              <h1 class="text-xl font-semibold text-gray-800 truncate">
                {{ header }}
              </h1>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto" @click="closeSidebarOnMobile">
          <slot></slot>
      </main>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div v-show="sidebarStore.mobileOpen"
      class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden backdrop-blur-sm transition-opacity duration-300"
      :class="{'opacity-100': sidebarStore.mobileOpen, 'opacity-0': !sidebarStore.mobileOpen}"
      @click="closeSidebar"></div>

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
import { ref, onMounted, onUnmounted, watch } from 'vue';
import Sidebar from './Partials/Sidebar.vue';
import sidebarStore from './Partials/sidebarStore';
import ToastContainer from '@/Components/ToastContainer.vue';

defineProps({
  header: {
    type: String,
    default: 'Dashboard'
  }
});

const isDesktop = ref(window.innerWidth >= 1024);

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

watch(() => window.location.pathname, () => {
  if (!isDesktop.value) {
    sidebarStore.closeMobile();
  }
});

onMounted(() => {
  window.addEventListener('resize', handleResize);
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !isDesktop.value && sidebarStore.mobileOpen) {
      sidebarStore.closeMobile();
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}
</style>
