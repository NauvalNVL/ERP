<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl my-8">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-3 md:p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-2 md:mr-3">
            <i class="fas fa-layer-group text-sm md:text-base"></i>
          </div>
          <h3 class="text-lg md:text-xl font-semibold">Paper Flute Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transition-colors">
          <i class="fas fa-times text-lg md:text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-3 md:p-5">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search flutes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-[60vh]">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('Flute')">
                  <div class="flex items-center gap-1">
                    Paper Flute
                    <i class="fas fa-sort text-gray-400"></i>
                  </div>
                </th>
                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('Descr')">
                  <div class="flex items-center gap-1">
                    Description
                    <i class="fas fa-sort text-gray-400"></i>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
              <tr v-for="flute in filteredFlutes" :key="flute.Flute"
                :class="['hover:bg-emerald-50 cursor-pointer transition-colors', selectedFlute && selectedFlute.Flute === flute.Flute ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectRow(flute)"
                @dblclick="selectAndClose(flute)">
                <td class="px-3 md:px-6 py-2 md:py-3 whitespace-nowrap font-medium text-gray-900">{{ flute.Flute }}</td>
                <td class="px-3 md:px-6 py-2 md:py-3 text-gray-700">{{ flute.Descr }}</td>
              </tr>
              <tr v-if="filteredFlutes.length === 0">
                <td colspan="2" class="px-3 md:px-6 py-4 text-center text-gray-500">No paper flute data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p class="hidden md:block">Click on a row to select and edit its details. Double-click to select quickly.</p>
          <p class="md:hidden">Tap to select, double-tap to edit.</p>
        </div>
        <div class="mt-4 flex flex-col sm:flex-row gap-2 justify-center">
          <button type="button" @click="selectAndClose(selectedFlute)" :disabled="!selectedFlute" class="w-full sm:w-auto py-2 px-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm rounded-lg transition-all">
            <i class="fas fa-check mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('close')" class="w-full sm:w-auto py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg transition-colors">
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
  flutes: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedFlute = ref(null);
const sortKey = ref('Flute');
const sortAsc = ref(true);

// Compute filtered flutes based on search query
const filteredFlutes = computed(() => {
  let flutes = props.flutes;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    flutes = flutes.filter(f =>
      f.Flute?.toLowerCase().includes(q) ||
      f.Descr?.toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...flutes].sort((a, b) => {
    let valA = a[sortKey.value] || '';
    let valB = b[sortKey.value] || '';
    
    if (valA < valB) return sortAsc.value ? -1 : 1;
    if (valA > valB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(flute) {
  selectedFlute.value = flute;
}

// Select and close modal
function selectAndClose(flute) {
  if (flute) {
    emit('select', flute);
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

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedFlute.value = null;
    searchQuery.value = '';
  }
});
</script>
