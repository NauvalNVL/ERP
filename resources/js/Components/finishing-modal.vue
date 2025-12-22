<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl flex flex-col max-h-[90vh]">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-tools"></i>
          </div>
          <h3 class="text-xl font-semibold">Finishing Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-5 flex-1 flex flex-col min-h-0">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search finishing methods..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="flex-1 min-h-0">
          <div v-if="loading" class="flex flex-col items-center justify-center flex-1 border border-dashed border-emerald-300 rounded-lg py-10">
            <div class="flex items-center space-x-3 text-emerald-600">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
              <span class="text-sm font-medium">Loading finishing data...</span>
            </div>
            <p class="text-xs text-emerald-500 mt-2">Please wait, fetching the latest records.</p>
          </div>
          <div v-else class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
            <table class="w-full divide-y divide-gray-200 table-fixed">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('code')">Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/4 cursor-pointer" @click="sortTable('description')">Description</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-xs">
                <tr v-for="finishing in filteredFinishings" :key="finishing.code"
                  :class="['hover-bg-emerald-50 cursor-pointer', selectedFinishing && selectedFinishing.code === finishing.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                  @click="selectRow(finishing)"
                  @dblclick="selectAndClose(finishing)">
                  <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ finishing.code }}</td>
                  <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ finishing.description }}</td>
                </tr>
                <tr v-if="filteredFinishings.length === 0">
                  <td colspan="2" class="px-6 py-4 text-center text-gray-500">No finishing data available.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('description')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Description
          </button>
          <div class="flex gap-2">
            <button type="button" @click="selectAndClose(selectedFinishing)" class="flex-1 py-2 px-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-edit mr-1"></i>Select
            </button>
            <button type="button" @click="$emit('close')" class="flex-1 py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-times mr-1"></i>Exit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  finishings: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedFinishing = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);
const loading = computed(() => props.loading);

// Filter finishings based on search query
const filteredFinishings = computed(() => {
  let items = props.finishings;

  if (!Array.isArray(items)) {
    return [];
  }

  items = items.filter(item => !item.status || item.status === 'Act');

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    items = items.filter(item =>
      item.code.toLowerCase().includes(q) ||
      item.description.toLowerCase().includes(q)
    );
  }
  
  // Sort the filtered items
  return [...items].sort((a, b) => {
    const valueA = a[sortKey.value]?.toLowerCase() || '';
    const valueB = b[sortKey.value]?.toLowerCase() || '';
    
    if (sortAsc.value) {
      return valueA.localeCompare(valueB);
    } else {
      return valueB.localeCompare(valueA);
    }
  });
});

// Select a row
function selectRow(finishing) {
  selectedFinishing.value = finishing;
}

// Select and close modal
function selectAndClose(finishing) {
  if (finishing) {
    emit('select', finishing);
    emit('close');
  }
}

// Toggle sort direction or change sort key
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
    selectedFinishing.value = null;
    searchQuery.value = '';
  }
});
</script>
