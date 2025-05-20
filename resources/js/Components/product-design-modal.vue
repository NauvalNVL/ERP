<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-drafting-compass"></i>
          </div>
          <h3 class="text-xl font-semibold">Product Design Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search product designs..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('design_code')">Design#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('design_name')">Design Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('product_code')">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('dimension')">Dimension</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('idc')">IDC</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="design in filteredDesigns" :key="design.design_code"
                :class="['hover:bg-blue-50 cursor-pointer', selectedDesign && selectedDesign.design_code === design.design_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(design)"
                @dblclick="selectAndClose(design)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ design.design_code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.design_name }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ design.product_code }}</span>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.dimension }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.idc }}</td>
              </tr>
              <tr v-if="filteredDesigns.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No product design data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('design_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Design#
          </button>
          <button type="button" @click="sortByProductAndDesign()" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Product + Design#
          </button>
          <button type="button" @click="sortByProductName()" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Design Name
          </button>
          <button type="button" @click="selectAndClose(selectedDesign)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
const sortKey = ref('design_code');
const sortAsc = ref(true);

// Compute filtered designs based on search query
const filteredDesigns = computed(() => {
  let designs = props.designs;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    designs = designs.filter(design =>
      design.design_code.toLowerCase().includes(q) ||
      design.design_name.toLowerCase().includes(q) ||
      design.product_code.toLowerCase().includes(q) ||
      design.dimension.toLowerCase().includes(q) ||
      (design.idc && design.idc.toLowerCase().includes(q))
    );
  }
  
  // Apply sorting
  return [...designs].sort((a, b) => {
    if (sortKey.value === 'design_code') {
      if (a.design_code < b.design_code) return sortAsc.value ? -1 : 1;
      if (a.design_code > b.design_code) return sortAsc.value ? 1 : -1;
      return 0;
    }
    
    if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1;
    if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1;
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
  sortKey.value = 'product_code';
  sortAsc.value = true;
  
  filteredDesigns.value = [...props.designs].sort((a, b) => {
    if (a.product_code < b.product_code) return -1;
    if (a.product_code > b.product_code) return 1;
    
    // If products are the same, sort by design_code
    if (a.design_code < b.design_code) return -1;
    if (a.design_code > b.design_code) return 1;
    return 0;
  });
}

// Sort by design name
function sortByProductName() {
  sortKey.value = 'design_name';
  sortAsc.value = true;
  
  filteredDesigns.value = [...props.designs].sort((a, b) => {
    if (a.design_name < b.design_name) return -1;
    if (a.design_name > b.design_name) return 1;
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
