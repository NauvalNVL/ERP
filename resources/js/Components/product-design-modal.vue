<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3 shadow-inner">
            <i class="fas fa-drafting-compass"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold">Product Design Table</h3>
            <p class="text-xs text-blue-100">Select a design from the table below</p>
          </div>
        </div>
        <div class="flex space-x-2">
          <button class="px-3 py-1 bg-blue-500 hover:bg-blue-400 text-white rounded-md text-sm flex items-center shadow-sm">
            <i class="fas fa-file-export mr-1"></i> Export
          </button>
          <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px bg-red-500 hover:bg-red-600 h-8 w-8 rounded-full flex items-center justify-center">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <!-- Modal Content -->
      <div class="p-5">
        <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-sm">
          <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-grow">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input type="text" v-model="searchQuery" placeholder="Search by design code, name, type or product..."
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
            </div>
            <div class="flex space-x-2">
              <button type="button" @click="searchQuery = ''" class="py-2 px-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg flex items-center">
                <i class="fas fa-eraser mr-1"></i> Clear
              </button>
              <button type="button" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg flex items-center">
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
            </div>
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('pd_code')">Design#</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[150px]" @click="sortTable('pd_name')">Design Name</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[120px]" @click="sortTable('pd_design_type')">Design Type</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('idc')">IDC</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('product')">Product</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('joint')">Joint</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[110px]" @click="sortTable('joint_to_print')">Joint to Print</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[100px]" @click="sortTable('pcs_to_joint')">PCS to Joint</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('score')">Score</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('slot')">Slot</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[100px]" @click="sortTable('flute_style')">Flute Style</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[90px]" @click="sortTable('print_flute')">Print Flute</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[110px]" @click="sortTable('input_weight')">Input Weight</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="design in filteredDesigns" :key="design.pd_code"
                :class="['hover:bg-blue-50 cursor-pointer group', selectedDesign && selectedDesign.pd_code === design.pd_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(design)"
                @dblclick="selectAndClose(design)"
                title="Double-click to select this design">
                <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-900 relative">
                  {{ design.pd_code }}
                  <span class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity text-blue-500">
                    <i class="fas fa-mouse-pointer text-xs"></i>
                  </span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pd_name }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pd_design_type }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.idc }}</td>
                <td class="px-3 py-3 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ design.product }}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.joint }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.joint_to_print }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pcs_to_joint }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.score }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.slot }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.flute_style }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.print_flute }}</td>
                <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.input_weight }}</td>
              </tr>
              <tr v-if="filteredDesigns.length === 0">
                <td colspan="13" class="px-6 py-4 text-center text-gray-500">No product design data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 flex justify-between items-center">
          <div class="flex items-center text-sm text-gray-500">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            <p>{{ filteredDesigns.length }} designs found. Double-click a row to select.</p>
            <span v-if="selectedDesign" class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
              Selected: {{ selectedDesign.pd_code }}
            </span>
          </div>
          <div class="flex space-x-2">
            <div class="dropdown relative">
              <button type="button" class="py-2 px-3 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-sm rounded-lg flex items-center">
                <i class="fas fa-sort mr-1"></i> Sort By <i class="fas fa-chevron-down ml-1 text-xs"></i>
              </button>
              <div class="absolute right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 hidden hover:block focus:block group-hover:block z-20 w-48">
                <div class="py-1">
                  <button @click="sortTable('pd_code')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sort-alpha-down mr-2"></i> By Design#
                  </button>
                  <button @click="sortByProductAndDesign()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-layer-group mr-2"></i> By Product + Design#
                  </button>
                  <button @click="sortByProductName()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-font mr-2"></i> By Design Name
                  </button>
                </div>
              </div>
            </div>
            <button type="button" @click="selectAndClose(selectedDesign)" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg flex items-center shadow-sm" :disabled="!selectedDesign" :class="{'opacity-50 cursor-not-allowed': !selectedDesign}">
              <i class="fas fa-check-circle mr-2"></i>Select Design
            </button>
            <button type="button" @click="$emit('close')" class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg flex items-center">
              <i class="fas fa-times mr-2"></i>Cancel
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
  designs: {
    type: Array,
    default: () => []
  },
  products: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedDesign = ref(null);
const sortKey = ref('pd_code');
const sortAsc = ref(true);

// Compute filtered designs based on search query
const filteredDesigns = computed(() => {
  let designs = props.designs;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    designs = designs.filter(design =>
      (design.pd_code && design.pd_code.toLowerCase().includes(q)) ||
      (design.pd_name && design.pd_name.toLowerCase().includes(q)) ||
      (design.pd_design_type && design.pd_design_type.toLowerCase().includes(q)) ||
      (design.product && design.product.toLowerCase().includes(q)) ||
      (design.idc && design.idc.toLowerCase().includes(q)) ||
      (design.joint && design.joint.toLowerCase().includes(q))
    );
  }
  
  // Apply sorting
  return [...designs].sort((a, b) => {
    if (sortKey.value === 'pd_code') {
      if (a.pd_code < b.pd_code) return sortAsc.value ? -1 : 1;
      if (a.pd_code > b.pd_code) return sortAsc.value ? 1 : -1;
      return 0;
    }
    
    // Handle null values for sorting
    const aVal = a[sortKey.value] || '';
    const bVal = b[sortKey.value] || '';
    
    if (aVal < bVal) return sortAsc.value ? -1 : 1;
    if (aVal > bVal) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(design) {
  selectedDesign.value = design;
}

// Select and close modal
function selectAndClose(design) {
  if (design) {
    emit('select', design);
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

// Sort by product and design
function sortByProductAndDesign() {
  sortKey.value = 'product';
  sortAsc.value = true;
  
  filteredDesigns.value = [...props.designs].sort((a, b) => {
    const aProduct = a.product || '';
    const bProduct = b.product || '';
    
    if (aProduct < bProduct) return -1;
    if (aProduct > bProduct) return 1;
    
    // If products are the same, sort by pd_code
    const aCode = a.pd_code || '';
    const bCode = b.pd_code || '';
    
    if (aCode < bCode) return -1;
    if (aCode > bCode) return 1;
    return 0;
  });
}

// Sort by design name
function sortByProductName() {
  sortKey.value = 'pd_name';
  sortAsc.value = true;
  
  filteredDesigns.value = [...props.designs].sort((a, b) => {
    const aName = a.pd_name || '';
    const bName = b.pd_name || '';
    
    if (aName < bName) return -1;
    if (aName > bName) return 1;
    return 0;
  });
}

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedDesign.value = null;
    searchQuery.value = '';
  }
});
</script>

<style scoped>
/* Add custom styles for better table display */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Enhanced table styling */
table {
  border-collapse: separate;
  border-spacing: 0;
}

thead th {
  background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s;
}

thead th:hover {
  background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
}

tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  z-index: 1;
  position: relative;
}

tbody tr.bg-blue-100 {
  box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
}

/* Search input styling */
input[type="text"] {
  transition: all 0.3s;
}

input[type="text"]:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
  border-color: #3b82f6;
}

/* Button styling */
button {
  transition: all 0.2s;
}

button:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button:active {
  transform: translateY(0);
}

.bg-blue-500:hover {
  background-color: #3b82f6;
  box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
}

/* Modal styling */
.fixed.inset-0 {
  backdrop-filter: blur(2px);
}

.bg-white.rounded-lg {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  animation: modal-appear 0.3s ease-out forwards;
}

@keyframes modal-appear {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Badge styling */
.rounded-full.bg-blue-100 {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(59, 130, 246, 0.2);
}
</style>
