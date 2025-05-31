<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-palette"></i>
          </div>
          <h3 class="text-xl font-semibold">Color Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-5">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search colors..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('color_id')">Color#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('color_name')">Color Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('origin')">Origin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('color_group_id')">CG#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('cg_type')">CG Type</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="color in filteredColors" :key="color.color_id"
                :class="['hover:bg-blue-50 cursor-pointer', selectedColor && selectedColor.color_id === color.color_id ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(color)"
                @dblclick="selectAndClose(color)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ color.color_id }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ color.color_name }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ color.origin }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ color.color_group_id }}</span>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ color.cg_type }}</td>
              </tr>
              <tr v-if="filteredColors.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No color data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('color_id')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Color#
          </button>
          <button type="button" @click="sortByCGAndColor()" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By CG# + Color#
          </button>
          <button type="button" @click="sortByCGTypeAndColor()" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By CG Type + Color#
          </button>
          <button type="button" @click="selectAndClose(selectedColor)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-edit mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-times mr-1"></i>Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  colors: {
    type: Array,
    default: () => []
  },
  colorGroups: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select', 'refresh']);

const searchQuery = ref('');
const selectedColor = ref(null);
const sortKey = ref('color_id');
const sortAsc = ref(true);

// Compute filtered colors based on search query
const filteredColors = computed(() => {
  let colors = props.colors;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    colors = colors.filter(color =>
      color.color_id.toLowerCase().includes(q) ||
      color.color_name.toLowerCase().includes(q) ||
      color.origin.toLowerCase().includes(q) ||
      color.color_group_id.toLowerCase().includes(q) ||
      (color.cg_type && color.cg_type.toLowerCase().includes(q))
    );
  }
  
  // Apply sorting
  return [...colors].sort((a, b) => {
    if (sortKey.value === 'color_id') {
      if (a.color_id < b.color_id) return sortAsc.value ? -1 : 1;
      if (a.color_id > b.color_id) return sortAsc.value ? 1 : -1;
      return 0;
    }
    
    if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1;
    if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(color) {
  selectedColor.value = color;
}

// Select and close modal
function selectAndClose(color) {
  if (color) {
    emit('select', color);
    emit('close');
  }
}

// Sort table by the given key
function sortTable(key) {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
}

// Sort by color group and color
function sortByCGAndColor() {
  sortKey.value = 'color_group_id';
  sortAsc.value = true;
  
  filteredColors.value.sort((a, b) => {
    if (a.color_group_id < b.color_group_id) return -1;
    if (a.color_group_id > b.color_group_id) return 1;
    
    // If color groups are the same, sort by color_id
    if (a.color_id < b.color_id) return -1;
    if (a.color_id > b.color_id) return 1;
    return 0;
  });
}

// Sort by CG type and color
function sortByCGTypeAndColor() {
  sortKey.value = 'cg_type';
  sortAsc.value = true;
  
  filteredColors.value.sort((a, b) => {
    const typeA = a.cg_type || '';
    const typeB = b.cg_type || '';
    
    if (typeA < typeB) return -1;
    if (typeA > typeB) return 1;
    
    // If CG types are the same, sort by color_id
    if (a.color_id < b.color_id) return -1;
    if (a.color_id > b.color_id) return 1;
    return 0;
  });
}

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedColor.value = null;
    searchQuery.value = '';
    
    // Emit event to parent component to refresh colors data
    emit('refresh');
  }
});

// Apply blue highlighting to certain rows (similar to the original)
function highlightBlueRows() {
  const blueGroups = ['01', '04', '06', '08'];
  filteredColors.value = filteredColors.value.map(color => {
    return {
      ...color,
      isBlue: blueGroups.includes(color.color_group_id)
    };
  });
}
</script>
