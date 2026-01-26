<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl my-8 flex flex-col max-h-[90vh]">
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
      <div class="p-3 md:p-5 flex-1 flex flex-col min-h-0">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search flutes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="flex-1 min-h-0 flex flex-col">
          <div v-if="loading" class="flex flex-col items-center justify-center flex-1 border border-dashed border-emerald-300 rounded-lg py-10">
            <div class="flex items-center space-x-3 text-emerald-600">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
              <span class="text-sm font-medium">Loading paper flute data...</span>
            </div>
            <p class="text-xs text-emerald-500 mt-2">Please wait, fetching the latest records.</p>
          </div>
          <div v-else class="flex-1 flex flex-col min-h-0">
            <div class="overflow-x-auto rounded-lg border border-gray-200 flex-1" style="max-height: calc(60vh - 40px);">
              <table class="w-full min-w-[960px] divide-y divide-gray-200">
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
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('DB')">
                      <div class="flex items-center gap-1 justify-end">
                        DB
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('B')">
                      <div class="flex items-center gap-1 justify-end">
                        B
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('_1L')">
                      <div class="flex items-center gap-1 justify-end">
                        1L
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('A_C_E')">
                      <div class="flex items-center gap-1 justify-end">
                        A/C/E
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('_2L')">
                      <div class="flex items-center gap-1 justify-end">
                        2L
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('Height')">
                      <div class="flex items-center gap-1 justify-end">
                        Height
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('Starch')">
                      <div class="flex items-center gap-1 justify-end">
                        Starch
                        <i class="fas fa-sort text-gray-400"></i>
                      </div>
                    </th>
                    <th class="px-3 md:px-4 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortTable('status')">
                      <div class="flex items-center gap-1">
                        Status
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
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute.DB) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute.B) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute._1L) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute.A_C_E) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute._2L) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute.Height) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ formatNumber(flute.Starch) }}</td>
                    <td class="px-3 md:px-4 py-2 md:py-3 text-gray-700">{{ getStatusValue(flute) }}</td>
                  </tr>
                  <tr v-if="filteredFlutes.length === 0">
                    <td colspan="10" class="px-3 md:px-6 py-4 text-center text-gray-500">No paper flute data available.</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Instructions placed outside the scrollable area -->
            <div class="mt-2 text-xs text-gray-500 italic pb-2">
              <p class="hidden md:block">Click on a row to select and edit its details. Double-click to select quickly.</p>
              <p class="md:hidden">Tap to select, double-tap to edit.</p>
            </div>
          </div>
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
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedFlute = ref(null);
const sortKey = ref('Flute');
const sortAsc = ref(true);
const numberColumns = ['DB', 'B', '_1L', 'A_C_E', '_2L', 'Height', 'Starch'];
const loading = computed(() => props.loading);

const formatNumber = (value) => {
  if (value === null || value === undefined || value === '') return '0.00';
  const num = Number(value);
  return Number.isNaN(num) ? '0.00' : num.toFixed(2);
};

const getStatusValue = (row) => {
  if (!row) return '';
  if (row.status) return String(row.status).trim();
  if (row.STATUS) return String(row.STATUS).trim();
  if (typeof row.is_active === 'boolean') return row.is_active ? 'Act' : 'Obs';
  return '';
};

const isActiveFlute = (flute) => {
  const status = flute?.status ?? flute?.STATUS;
  if (status === undefined || status === null || String(status).trim() === '') return true;

  const s = String(status).trim().toLowerCase();
  if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
  if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

  return true;
};

// Compute filtered flutes based on search query
const filteredFlutes = computed(() => {
  let flutes = props.flutes;
  
  flutes = (Array.isArray(flutes) ? flutes : []).filter(isActiveFlute);
  
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    flutes = flutes.filter(f =>
      f.Flute?.toLowerCase().includes(q) ||
      f.Descr?.toLowerCase().includes(q) ||
      String(formatNumber(f.DB)).toLowerCase().includes(q) ||
      String(formatNumber(f.B)).toLowerCase().includes(q) ||
      String(formatNumber(f._1L)).toLowerCase().includes(q) ||
      String(formatNumber(f.A_C_E)).toLowerCase().includes(q) ||
      String(formatNumber(f._2L)).toLowerCase().includes(q) ||
      String(formatNumber(f.Height)).toLowerCase().includes(q) ||
      String(formatNumber(f.Starch)).toLowerCase().includes(q) ||
      getStatusValue(f).toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...flutes].sort((a, b) => {
    let valA = a[sortKey.value] || '';
    let valB = b[sortKey.value] || '';
    if (numberColumns.includes(sortKey.value)) {
      valA = parseFloat(valA) || 0;
      valB = parseFloat(valB) || 0;
      return sortAsc.value ? valA - valB : valB - valA;
    }

    valA = valA.toString().toLowerCase();
    valB = valB.toString().toLowerCase();

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
