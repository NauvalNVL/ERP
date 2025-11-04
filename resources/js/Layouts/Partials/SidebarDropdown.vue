<template>
  <div class="relative group">
    <button 
      @click="toggleMenu" 
      class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-all duration-200"
      :class="{ 
        'bg-blue-600 text-white font-semibold shadow-lg border-l-4 border-blue-300': hasActiveChild, 
        'sidebar-active': hasActiveChild,
        'text-sm': !isTopLevel, 
        'text-base': isTopLevel 
      }"
      :style="hasActiveChild ? 'background-color: #2563eb !important; color: white !important; border-left: 4px solid #93c5fd !important;' : ''"
    >
      <div class="flex items-center">
        <i :class="[icon, 'mr-3', isTopLevel ? 'w-5 h-5' : 'w-4 h-4', 'text-current']" style="display: inline-block !important;"></i>
        <span>{{ title }}</span>
      </div>
      <div class="flex items-center">
        <!-- Active indicator dot for parent menu -->
        <div v-if="hasActiveChild" class="w-2 h-2 bg-blue-200 rounded-full animate-pulse mr-2"></div>
        <i 
          class="fas fa-chevron-down transition-transform" 
          :class="{ 'transform rotate-180': isMenuOpen }"
        ></i>
      </div>
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
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-all duration-200 relative"
          :class="{
            'bg-blue-600 text-white font-semibold shadow-lg border-l-4 border-blue-300': isActive(item.route),
            'sidebar-active': isActive(item.route)
          }"
          :style="isActive(item.route) ? 'background-color: #2563eb !important; color: white !important; border-left: 4px solid #93c5fd !important;' : ''"
          :title="`Current: ${currentPath} | Route: ${item.route} | Active: ${isActive(item.route)}`"
        >
          <i :class="[item.icon, 'w-4 h-4 mr-3', 'text-current']" style="display: inline-block !important;"></i>
          <span>{{ item.title }}</span>
          <!-- Active indicator dot -->
          <div v-if="isActive(item.route)" class="ml-auto w-2 h-2 bg-blue-200 rounded-full animate-pulse"></div>
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
const currentPath = computed(() => page.url);

const menuId = computed(() => props.menuId || props.title.toLowerCase().replace(/\s+/g, '-'));

const isActive = (route) => {
  try {
    if (!route || !currentPath.value) return false;
    
    // Normalize paths - remove trailing slashes and convert to lowercase
    const current = currentPath.value.toLowerCase().replace(/\/+$/, '') || '/';
    const target = route.toLowerCase().replace(/\/+$/, '') || '/';
    
    // Check for exact match or if current path starts with target path
    const isExactMatch = current === target;
    const isSubPath = current.startsWith(target + '/');
    const isMatch = isExactMatch || isSubPath;
    
    return isMatch;
  } catch (error) {
    return false;
  }
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

.sidebar-active {
  background-color: #2563eb !important;
  color: white !important;
  font-weight: 600 !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  border-left: 4px solid #93c5fd !important;
}
</style> 