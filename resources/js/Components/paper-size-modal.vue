<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-ruler-combined"></i>
          </div>
          <h3 class="text-xl font-semibold">Paper Size Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search paper sizes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-80">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('size_id')">NO.</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('millimeter')">MILLIMETER</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('inches')">INCHES</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">STATUS</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="size in filteredSizes" :key="size.id"
                :class="['hover:bg-emerald-50 cursor-pointer', selectedSize && selectedSize.id === size.id ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectRow(size)"
                @dblclick="selectAndClose(size)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ 'PS' + String(size.id).padStart(3, '0') }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ Number(size.millimeter).toFixed(2) }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ Number(size.inches).toFixed(2) }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                  <span v-if="isActiveSize(size)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                  </span>
                  <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Obsolete
                  </span>
                </td>
              </tr>
              <tr v-if="filteredSizes.length === 0">
                <td colspan="4" class="px-4 py-4 text-center text-gray-500">No paper size data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
          <button type="button" @click="sortTable('millimeter')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Size
          </button>
          <button type="button" @click="selectAndClose(selectedSize)" class="py-2 px-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs rounded-lg transform active:translate-y-px">
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
  paperSizes: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select', 'create']);

const searchQuery = ref('');
const selectedSize = ref(null);
const sortKey = ref('millimeter');
const sortAsc = ref(true);

const isActiveSize = (size) => {
  const status = size?.status ?? size?.STATUS;
  if (status === undefined || status === null || String(status).trim() === '') return true;

  const s = String(status).trim().toLowerCase();
  if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
  if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

  return true;
};

// Compute filtered sizes based on search query
const filteredSizes = computed(() => {
  let sizes = Array.isArray(props.paperSizes) ? props.paperSizes : [];

  sizes = sizes.filter(isActiveSize);

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    sizes = sizes.filter(size =>
      String(size.id).toLowerCase().includes(q) ||
      String(size.millimeter).toLowerCase().includes(q) ||
      String(size.inches).toLowerCase().includes(q)
    );
  }

  // Apply sorting
  return [...sizes].sort((a, b) => {
    let valA, valB;

    if (sortKey.value === 'size_id') {
      valA = String(a.id).padStart(3, '0');
      valB = String(b.id).padStart(3, '0');
    } else if (sortKey.value === 'millimeter') {
      valA = parseFloat(a.millimeter || 0);
      valB = parseFloat(b.millimeter || 0);
    } else if (sortKey.value === 'inches') {
      valA = parseFloat(a.inches || 0);
      valB = parseFloat(b.inches || 0);
    }

    if (sortKey.value === 'millimeter' || sortKey.value === 'inches') {
      return sortAsc.value ? valA - valB : valB - valA;
    } else {
      if (typeof valA === 'string' && typeof valB === 'string') {
        return sortAsc.value ? valA.localeCompare(valB) : valB.localeCompare(valA);
      }

      if (valA < valB) return sortAsc.value ? -1 : 1;
      if (valA > valB) return sortAsc.value ? 1 : -1;
      return 0;
    }
  });
});

// Select a row
function selectRow(size) {
  selectedSize.value = size;
}

// Select and close modal
function selectAndClose(size) {
  if (size) {
    emit('select', size);
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
    selectedSize.value = null;
    searchQuery.value = '';
  }
});
</script>
