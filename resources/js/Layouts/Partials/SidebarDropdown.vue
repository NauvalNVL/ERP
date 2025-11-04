<template>
  <div class="relative group">
    <button 
      @click="toggleMenu" 
      class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
      :class="{ 'bg-gray-700': hasActiveChild, 'text-sm': !isTopLevel, 'text-base': isTopLevel }"
    >
      <div class="flex items-center">
        <i :class="[icon, 'mr-3', isTopLevel ? 'w-5 h-5' : 'w-4 h-4', 'text-current']" style="display: inline-block !important;"></i>
        <span>{{ title }}</span>
      </div>
      <i 
        class="fas fa-chevron-down transition-transform" 
        :class="{ 'transform rotate-180': isMenuOpen }"
      ></i>
    </button>

    <!-- Dropdown Menu -->
    <div 
      v-show="isMenuOpen" 
      class="pl-4 mt-2 space-y-1 dropdown-menu"
    >
      <template v-for="(item, index) in items" :key="index">
        <!-- Item with children (nested dropdown) -->
        <div v-if="item.children && item.children.length > 0" class="relative">
          <sidebar-dropdown 
            :title="item.title" 
            :icon="item.icon" 
            :items="item.children"
            :menu-id="`${menuId}-${index}`"
            :is-top-level="false"
            :has-permission="hasPermission"
          />
        </div>
        
        <!-- Simple item with route -->
        <Link 
          v-else-if="item.route" 
          :href="item.route" 
          @click="handleLinkClick"
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{
            'bg-blue-600 text-white font-medium': isActive(item.route),
          }"
        >
          <i :class="[item.icon, 'w-4 h-4 mr-3', 'text-current']" style="display: inline-block !important;"></i>
          <span>{{ item.title }}</span>
        </Link>
        
        <!-- Simple item without route -->
        <a 
          v-else 
          href="#" 
          @click.prevent 
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
        >
          <i :class="[item.icon, 'w-4 h-4 mr-3', 'text-current']" style="display: inline-block !important;"></i>
          <span>{{ item.title }}</span>
        </a>
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import sidebarStore from './sidebarStore';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  items: {
    type: Array,
    required: true
  },
  menuId: {
    type: String,
    default: ''
  },
  isTopLevel: {
    type: Boolean,
    default: true
  },
  hasPermission: {
    type: Function,
    default: () => () => true
  }
});

const page = usePage();
const currentPath = computed(() => page.url.toLowerCase().replace(/\/+$/, ''));

const menuId = computed(() => props.menuId || props.title.toLowerCase().replace(/\s+/g, '-'));

const isActive = (route) => {
  if (!route) return false;
  const normalizedRoute = route.toLowerCase().replace(/\/+$/, '');
  return currentPath.value === normalizedRoute;
};

const hasActiveChild = computed(() => {
  const checkItems = (items) => {
    return items.some(item => {
      if (item.route && isActive(item.route)) {
        return true;
      }
      if (item.children) {
        return checkItems(item.children);
      }
      return false;
    });
  };
  return checkItems(props.items);
});

const isMenuOpen = computed(() => sidebarStore.isOpen(menuId.value));

const toggleMenu = () => {
  sidebarStore.toggle(menuId.value);
};

const handleLinkClick = () => {
  // Close mobile sidebar when a link is clicked
  if (window.innerWidth < 1024) {
    sidebarStore.closeMobile();
  }
};

// Watch for changes in active child status to auto-open the menu
watch(hasActiveChild, (isActive) => {
  if (isActive) {
    sidebarStore.setOpen(menuId.value, true);
  }
}, { immediate: true });
</script>

<script>
// For recursive components
export default {
  name: 'SidebarDropdown'
}
</script>

<style scoped>
.dropdown-menu {
  overflow: visible;
  transition: all 0.3s ease-in-out;
}

.dropdown-menu:not([style*="display: none"]) {
  animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style> 