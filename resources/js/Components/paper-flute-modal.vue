<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-layer-group"></i>
          </div>
          <h3 class="text-xl font-semibold">Paper Flute Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search flutes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('code')">Flute#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('name')">Flute Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('description')">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('flute_height')">Flute Height</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('is_active')">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="flute in filteredFlutes" :key="flute.code"
                :class="['hover:bg-blue-50 cursor-pointer', selectedFlute && selectedFlute.code === flute.code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(flute)"
                @dblclick="selectAndClose(flute)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ flute.code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ flute.name }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ flute.description }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ flute.flute_height }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', flute.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                    {{ flute.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
              <tr v-if="filteredFlutes.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No paper flute data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-4 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Flute#
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Name
          </button>
          <button type="button" @click="selectAndClose(selectedFlute)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
  flutes: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedFlute = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

// Compute filtered flutes based on search query
const filteredFlutes = computed(() => {
  let flutes = props.flutes;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    flutes = flutes.filter(f =>
      f.code.toLowerCase().includes(q) ||
      f.name.toLowerCase().includes(q) ||
      f.description.toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...flutes].sort((a, b) => {
    let valA, valB;
    
    if (sortKey.value === 'flute_height') {
      valA = parseFloat(a.flute_height);
      valB = parseFloat(b.flute_height);
    } else if (sortKey.value === 'is_active') {
      valA = a.is_active ? 1 : 0;
      valB = b.is_active ? 1 : 0;
    } else {
      valA = a[sortKey.value];
      valB = b[sortKey.value];
    }
    
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
