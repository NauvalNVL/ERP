<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-object-group"></i>
          </div>
          <h3 class="text-xl font-semibold">Product Group Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search product groups..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('code')">Group ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2 cursor-pointer" @click="sortTable('name')">Group Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('is_active')">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="group in filteredGroups" :key="group.id"
                :class="['hover:bg-blue-50 cursor-pointer', selectedGroup && selectedGroup.id === group.id ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(group)"
                @dblclick="selectAndClose(group)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ group.code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ group.name }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span 
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full', 
                      group.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ]"
                  >{{ group.is_active ? 'Active' : 'Inactive' }}</span>
                </td>
              </tr>
              <tr v-if="loading">
                <td colspan="3" class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500">Loading data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredGroups.length === 0">
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No product group data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-4 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Group ID
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Group Name
          </button>
          <button type="button" @click="selectAndClose(selectedGroup)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
  productGroups: {
    type: Array,
    default: () => []
  },
  loading: Boolean
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedGroup = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);
const loading = computed(() => props.loading);

// Compute filtered groups based on search query
const filteredGroups = computed(() => {
  let groups = props.productGroups || [];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    groups = groups.filter(group =>
      (group.code && group.code.toLowerCase().includes(query)) ||
      (group.name && group.name.toLowerCase().includes(query))
    );
  }
  
  // Apply sorting
  return [...groups].sort((a, b) => {
    if (sortKey.value === 'is_active') {
      // For boolean values, active should come first if ascending
      return sortAsc.value ? 
        (a.is_active === b.is_active ? 0 : a.is_active ? -1 : 1) : 
        (a.is_active === b.is_active ? 0 : a.is_active ? 1 : -1);
    }
    
    let valueA = a[sortKey.value] || '';
    let valueB = b[sortKey.value] || '';
    
    if (typeof valueA === 'string') valueA = valueA.toLowerCase();
    if (typeof valueB === 'string') valueB = valueB.toLowerCase();
    
    if (valueA < valueB) return sortAsc.value ? -1 : 1;
    if (valueA > valueB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(group) {
  selectedGroup.value = group;
}

// Select and close modal
function selectAndClose(group) {
  if (group) {
    emit('select', group);
    emit('close');
  } else {
    console.log('No group selected for edit');
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

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedGroup.value = null;
    searchQuery.value = '';
  }
});

// Watch for changes in product groups
watch(() => props.productGroups, (newGroups) => {
  console.log('Product groups updated in modal:', newGroups);
}, { deep: true });
</script>
