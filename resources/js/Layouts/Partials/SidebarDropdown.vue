<template>
  <div class="relative group">
    <button 
      @click="toggleMenu" 
      class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
      :class="{ 'bg-gray-700': hasActiveChild }"
    >
      <div class="flex items-center">
        <i :class="[icon, 'w-5 h-5 mr-3']"></i>
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
        <div v-if="item.children" class="relative">
          <nested-dropdown 
            :title="item.title" 
            :icon="item.icon" 
            :items="item.children"
            :menu-id="menuId + '-' + index"
          />
        </div>
        
        <!-- Simple item with route -->
        <Link 
          v-else-if="item.route" 
          :href="item.route" 
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{
            'bg-blue-600 text-white font-medium': isActive(item.route),
            'text-gray-100': isActiveParent(item.route)
          }"
        >
          <i :class="[item.icon, 'w-4 h-4 mr-3']"></i>
          <span>{{ item.title }}</span>
        </Link>
        
        <!-- Simple item without route -->
        <a 
          v-else 
          href="#" 
          @click.prevent 
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
        >
          <i :class="[item.icon, 'w-4 h-4 mr-3']"></i>
          <span>{{ item.title }}</span>
        </a>
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import NestedDropdown from './NestedDropdown.vue';
import sidebarStore from './sidebarStore';

const page = usePage();
const currentPath = computed(() => page.url);

// Extract the base path from the URL
const getBasePath = (path) => {
  if (!path) return '';
  // Normalize the path and split into segments
  const normalizedPath = path.toLowerCase().replace(/^\/+|\/+$/g, '');
  const parts = normalizedPath.split('/');
  
  // Return the first N segments for base path comparison
  // This allows deeper paths to still match their parent routes
  const segments = Math.min(3, parts.length);
  return '/' + parts.slice(0, segments).join('/');
};

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
  }
});

const menuId = computed(() => props.menuId || props.title.toLowerCase().replace(/\s+/g, '-'));
const isMenuOpen = computed(() => sidebarStore.isOpen(menuId.value) || hasActiveChild.value);

// Check if the current route matches the given route exactly
const isActive = (route) => {
  if (!route) return false;
  
  // Normalize both paths for comparison (remove trailing slash, lowercase)
  const normalizedCurrent = currentPath.value.toLowerCase().replace(/\/+$/, '');
  const normalizedRoute = route.toLowerCase().replace(/\/+$/, '');
  
  return normalizedCurrent === normalizedRoute;
};

// For parent highlighting, check if the current path's base matches the route's base
const isActiveParent = (route) => {
  if (!route) return false;
  
  // Normalize both paths for comparison
  const normalizedCurrent = currentPath.value.toLowerCase().replace(/\/+$/, '');
  const normalizedRoute = route.toLowerCase().replace(/\/+$/, '');
  
  // Check if the current path contains the route path for deep nested routes
  if (normalizedCurrent.includes(normalizedRoute) && normalizedCurrent !== normalizedRoute) {
    return true;
  }
  
  // Check base path matching
  const currentBase = getBasePath(normalizedCurrent);
  const routeBase = getBasePath(normalizedRoute);
  return currentBase === routeBase && normalizedCurrent !== normalizedRoute;
};

// Check if any child item is active
const hasActiveChild = computed(() => {
  // Check direct children for exact matches
  const directActive = props.items.some(item => item.route && isActive(item.route));
  if (directActive) return true;
  
  // Check direct children for parent matches (same base path)
  const directParentActive = props.items.some(item => item.route && isActiveParent(item.route));
  if (directParentActive) return true;
  
  // Check nested children
  return props.items.some(item => {
    if (!item.children) return false;
    return item.children.some(child => child.route && (isActive(child.route) || isActiveParent(child.route)));
  });
});

const toggleMenu = () => {
  sidebarStore.toggle(menuId.value);
};
</script> 

<style scoped>
.dropdown-menu {
  overflow: visible;
  max-height: none;
  opacity: 1;
  transition: all 0.3s ease-in-out;
}

.dropdown-menu:not([style*="display: none"]) {
  animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
    max-height: 0;
  }
  to {
    opacity: 1;
    transform: translateY(0);
    max-height: none;
  }
}
</style> 