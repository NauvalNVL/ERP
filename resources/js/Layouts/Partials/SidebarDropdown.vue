<template>
  <div class="relative group">
    <button 
      @click="toggleMenu" 
      class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
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
    <div v-show="isMenuOpen" class="pl-4 mt-2 space-y-1">
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
          :href="'/'+item.route" 
          class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
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
import { Link } from '@inertiajs/vue3';
import NestedDropdown from './NestedDropdown.vue';
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
  }
});

const menuId = computed(() => props.menuId || props.title.toLowerCase().replace(/\s+/g, '-'));
const isMenuOpen = computed(() => sidebarStore.isOpen(menuId.value));

const toggleMenu = () => {
  sidebarStore.toggle(menuId.value);
};
</script> 