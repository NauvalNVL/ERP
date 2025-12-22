<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>
    
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl z-110 relative">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-industry"></i>
          </div>
          <h3 class="text-xl font-semibold">Industry Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search industries..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="flex-1 min-h-0">
          <div v-if="loading" class="flex flex-col items-center justify-center flex-1 border border-dashed border-emerald-300 rounded-lg py-10">
            <div class="flex items-center space-x-3 text-emerald-600">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
              <span class="text-sm font-medium">Loading industry data...</span>
            </div>
            <p class="text-xs text-emerald-500 mt-2">Please wait, fetching the latest records.</p>
          </div>
          <div v-else class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
            <table class="w-full divide-y divide-gray-200 table-fixed" id="industryDataTable">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th @click="sortTable('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer">
                    Code <i class="fas fa-sort ml-1"></i>
                  </th>
                  <th @click="sortTable('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/4 cursor-pointer">
                    Industry Name <i class="fas fa-sort ml-1"></i>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-xs">
                <tr v-for="industry in filteredIndustries" :key="industry.code"
                  :class="['hover:bg-emerald-50 cursor-pointer', selectedIndustry && selectedIndustry.code === industry.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                  @click="selectRow(industry)"
                  @dblclick="selectAndClose(industry)">
                  <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ industry.code }}</td>
                  <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ industry.name }}</td>
                </tr>
                <tr v-if="filteredIndustries.length === 0">
                  <td colspan="2" class="px-6 py-4 text-center text-gray-500">No industry data available.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select, double-click to edit the industry.</p>
        </div>
        <div class="mt-4 grid grid-cols-4 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>Sort by Code
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>Sort by Name
          </button>
          <button type="button" @click="selectAndClose(selectedIndustry)" class="py-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg transform active:translate-y-px" :disabled="!selectedIndustry">
            <i class="fas fa-check mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-times mr-1"></i>Close
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
  industries: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'select', 'edit']);

const searchQuery = ref('');
const selectedIndustry = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

const isActiveIndustry = (industry) => {
  const status = industry?.status ?? industry?.STATUS;
  if (status === undefined || status === null || String(status).trim() === '') return true;

  const s = String(status).trim().toLowerCase();
  if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
  if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

  return true;
};

// Compute filtered industries based on search query
const filteredIndustries = computed(() => {
  let industries = props.industries || [];
  
  industries = industries.filter(isActiveIndustry);
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    industries = industries.filter(industry =>
      (industry.code && industry.code.toLowerCase().includes(query)) ||
      (industry.name && industry.name.toLowerCase().includes(query))
    );
  }
  
  // Apply sorting
  return [...industries].sort((a, b) => {
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
function selectRow(industry) {
  selectedIndustry.value = industry;
}

// Select and close modal
function selectAndClose(industry) {
  if (industry) {
    emit('select', industry);
    emit('close');
  }
}

// Edit industry directly from the modal
function editIndustry(industry) {
  emit('edit', industry);
  emit('close');
}

// Edit the selected industry
function editSelected() {
  if (selectedIndustry.value) {
    editIndustry(selectedIndustry.value);
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
    selectedIndustry.value = null;
    searchQuery.value = '';
  }
});
</script>

<style scoped>
/* Add custom z-index classes */
.z-100 {
  z-index: 100;
}

.z-110 {
  z-index: 110;
}
</style>
