<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-0 left-0 z-20 p-4">
      <button @click="toggleSidebar" class="text-gray-500 hover:text-gray-600 transition-transform duration-300 hover:scale-110">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div v-show="sidebarStore.mobileOpen" 
      class="fixed inset-0 z-10 bg-black bg-opacity-50 lg:hidden backdrop-blur-sm transition-opacity duration-300"
      :class="{'opacity-100': sidebarStore.mobileOpen, 'opacity-0': !sidebarStore.mobileOpen}"
      @click="toggleSidebar"></div>

    <div class="flex min-h-screen">
      <!-- Sidebar -->
      <div :class="{'translate-x-0': sidebarStore.mobileOpen || isDesktop, '-translate-x-full': !sidebarStore.mobileOpen && !isDesktop}"
        class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out bg-gray-800 overflow-y-auto lg:sticky lg:top-0 lg:translate-x-0 h-screen hide-scrollbar shadow-lg">
        <Sidebar />
      </div>

      <!-- Main Content -->
      <div class="flex-1 min-w-0 flex flex-col">
        <!-- Top Navigation -->
        <div class="bg-white shadow-sm sticky top-0 z-10 slide-in-down">
          <div class="px-4 py-4 flex items-center">
            <button @click="toggleSidebar" class="mr-4 text-gray-500 lg:hidden hover:text-gray-700 transition-transform duration-300 hover:scale-110">
              <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="text-xl font-semibold">
              {{ header }}
            </h2>
          </div>
        </div>

        <!-- Page Content -->
        <div v-page-transition class="flex-grow">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import Sidebar from './Partials/Sidebar.vue';
import sidebarStore from './Partials/sidebarStore';

const props = defineProps({
  header: {
    type: String,
    default: 'Dashboard'
  }
});

const isDesktop = ref(window.innerWidth >= 1024);

const toggleSidebar = () => {
  sidebarStore.toggleMobile();
};

const handleResize = () => {
  isDesktop.value = window.innerWidth >= 1024;
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
  
  // Add entrance animation to the main content
  const mainContent = document.querySelector('.flex-1');
  if (mainContent) {
    mainContent.classList.add('fade-in');
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
@media (min-width: 1024px) {
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
}

@media print {
  body * {
    visibility: hidden;
  }
  #flutesTable,
  #flutesTable * {
    visibility: visible;
  }
  #flutesTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}

/* Transition effects */
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}

/* Sidebar animation */
.sidebar-enter-active,
.sidebar-leave-active {
  transition: transform 0.3s ease-in-out;
}

.sidebar-enter-from,
.sidebar-leave-to {
  transform: translateX(-100%);
}
</style>
