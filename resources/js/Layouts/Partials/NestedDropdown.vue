<template>
  <div class="relative">
    <button 
      @click="toggleMenu" 
      class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
      :class="{ 'bg-gray-700': hasActiveChild }"
    >
      <div class="flex items-center">
        <i :class="[icon, 'w-3 h-3 mr-3']"></i>
        <span>{{ title }}</span>
      </div>
      <i 
        class="fas fa-chevron-right text-xs transition-transform" 
        :class="{ 'transform rotate-90': isMenuOpen }"
      ></i>
    </button>

    <!-- Nested Dropdown Menu -->
    <div 
      v-show="isMenuOpen" 
      class="pl-4 mt-1 space-y-1 nested-dropdown-menu"
    >
      <template v-for="(item, index) in items" :key="index">
        <!-- Item with children (further nesting) -->
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
          class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{
            'bg-blue-600 text-white font-medium': isActive(item.route),
            'text-gray-100': isActiveParent(item.route)
          }"
        >
          <i :class="[item.icon, 'w-3 h-3 mr-3']"></i>
          <span>{{ item.title }}</span>
        </Link>
        
        <!-- Simple item without route -->
        <a 
          v-else 
          href="#" 
          @click.prevent 
          class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
        >
          <i :class="[item.icon, 'w-3 h-3 mr-3']"></i>
          <span>{{ item.title }}</span>
        </a>
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import sidebarStore from './sidebarStore';

const page = usePage();
const currentPath = computed(() => page.url);

// Extract the base path from the URL
const getBasePath = (path) => {
  if (!path) return '';
  // Normalize the path and split into segments
  const normalizedPath = path.toLowerCase().replace(/^\/+|\/+$/g, '');
  const parts = normalizedPath.split('/');
  
  // Return the first segment for base path comparison
  return parts.length > 0 ? `/${parts[0]}` : '';
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

// Calculate if the menu should be open based on stored state or active child
const isMenuOpen = computed(() => {
  // Check if this menu is manually opened/closed in the store
  const isStoreOpen = sidebarStore.isOpen(menuId.value);
  
  // If this menu has an active child, it should be open regardless of stored state
  // This ensures the current page's menu hierarchy is visible
  if (hasActiveChild.value) {
    // Only save this state if it's not already open
    if (!isStoreOpen) {
      // We do this in a setTimeout to avoid modifying state during render
      setTimeout(() => {
        sidebarStore.setOpen(menuId.value, true);
      }, 0);
    }
    return true;
  }
  
  // Otherwise, respect the stored state
  return isStoreOpen;
});

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
  
  // Check if the current path contains the route path for deep nested routes
  if (currentPath.value.includes(route) && currentPath.value !== route) {
    return true;
  }
  
  // Check base path matching
  const currentBase = getBasePath(currentPath.value);
  const routeBase = getBasePath(route);
  return currentBase === routeBase && currentPath.value !== route;
};

const toggleMenu = () => {
  sidebarStore.toggle(menuId.value);
};

// On mount, automatically open menus that lead to the current page
onMounted(() => {
  if (hasActiveChild.value) {
    sidebarStore.setOpen(menuId.value, true);
  }
});
</script> 

<style scoped>
.nested-dropdown-menu {
  overflow: visible;
  max-height: none;
  opacity: 1;
  transition: all 0.25s ease-in-out;
}

.nested-dropdown-menu:not([style*="display: none"]) {
  animation: slideRight 0.25s ease-in-out;
}

@keyframes slideRight {
  from {
    opacity: 0;
    transform: translateX(-5px);
    max-height: 0;
  }
  to {
    opacity: 1;
    transform: translateX(0);
    max-height: none;
  }
}
</style> 