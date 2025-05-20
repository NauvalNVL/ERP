<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-scroll"></i>
          </div>
          <h3 class="text-xl font-semibold">Paper Quality Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search paper qualities..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('paper_quality')">Paper Quality</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('paper_name')">Paper Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('weight_kg_m')">Weight KG/M</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('commercial_code')">Commercial</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('wet_end_code')">Wet-End</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('decc_code')">DECC</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8 cursor-pointer" @click="sortTable('status')">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="quality in filteredQualities" :key="quality.id"
                :class="['hover:bg-blue-50 cursor-pointer', selectedQuality && selectedQuality.id === quality.id ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(quality)"
                @dblclick="selectAndClose(quality)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ quality.paper_quality }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ quality.paper_name }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ quality.weight_kg_m }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ quality.commercial_code }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ quality.wet_end_code }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ quality.decc_code }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', quality.status === 'Act' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                    {{ quality.status }}
                  </span>
                </td>
              </tr>
              <tr v-if="filteredQualities.length === 0">
                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No paper quality data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 flex items-center justify-end space-x-3">
          <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg transform active:translate-y-px">
            <i class="fas fa-times mr-1"></i>Cancel
          </button>
          <button type="button" @click="selectAndClose(selectedQuality)" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transform active:translate-y-px">
            <i class="fas fa-edit mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('create')" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transform active:translate-y-px">
            <i class="fas fa-plus mr-1"></i>Create New
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
  qualities: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select', 'create']);

const searchQuery = ref('');
const selectedQuality = ref(null);
const sortKey = ref('paper_quality');
const sortAsc = ref(true);

// Compute filtered qualities based on search query
const filteredQualities = computed(() => {
  let qualities = props.qualities;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    qualities = qualities.filter(quality =>
      quality.paper_quality?.toLowerCase().includes(q) ||
      quality.paper_name?.toLowerCase().includes(q) ||
      quality.commercial_code?.toLowerCase().includes(q) ||
      quality.wet_end_code?.toLowerCase().includes(q) ||
      quality.decc_code?.toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...qualities].sort((a, b) => {
    let valA, valB;
    
    if (sortKey.value === 'weight_kg_m') {
      valA = parseFloat(a[sortKey.value] || 0);
      valB = parseFloat(b[sortKey.value] || 0);
    } else {
      valA = a[sortKey.value] || '';
      valB = b[sortKey.value] || '';
      
      // Special handling for strings
      if (typeof valA === 'string' && typeof valB === 'string') {
        return sortAsc.value 
          ? valA.localeCompare(valB) 
          : valB.localeCompare(valA);
      }
    }
    
    if (valA < valB) return sortAsc.value ? -1 : 1;
    if (valA > valB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(quality) {
  selectedQuality.value = quality;
}

// Select and close modal
function selectAndClose(quality) {
  if (quality) {
    emit('select', quality);
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
    selectedQuality.value = null;
    searchQuery.value = '';
  }
});
</script>
