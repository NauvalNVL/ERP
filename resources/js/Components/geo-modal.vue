<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto px-4 sm:px-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>
    
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl z-110 relative max-h-[90vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-globe-asia"></i>
          </div>
          <h3 class="text-xl font-semibold">Geographical Location Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search geo codes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="flex-1 min-h-0">
          <div v-if="loading" class="flex flex-col items-center justify-center flex-1 border border-dashed border-emerald-300 rounded-lg py-10">
            <div class="flex items-center space-x-3 text-emerald-600">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
              <span class="text-sm font-medium">Loading geo data...</span>
            </div>
            <p class="text-xs text-emerald-500 mt-2">Please wait while we fetch the latest records.</p>
          </div>
          <div v-else class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
            <table class="min-w-[720px] w-full divide-y divide-gray-200 table-fixed">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[12%] cursor-pointer" @click="sortTable('code')">Geo Code</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[22%] cursor-pointer" @click="sortTable('country')">Country</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] cursor-pointer" @click="sortTable('state')">State</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[18%] cursor-pointer" @click="sortTable('town')">Town</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[18%] cursor-pointer" @click="sortTable('town_section')">Town Section</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] cursor-pointer" @click="sortTable('area')">Area</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-xs">
                <tr v-for="geo in filteredGeos" :key="geo.code"
                  :class="['hover:bg-emerald-50 cursor-pointer', selectedGeo && selectedGeo.code === geo.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                  @click="selectRow(geo)"
                  @dblclick="selectAndClose(geo)">
                  <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ geo.code }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[160px]" :title="geo.country">{{ geo.country }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[140px]" :title="geo.state">{{ geo.state }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[140px]" :title="geo.town">{{ geo.town }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[160px]" :title="geo.town_section">{{ geo.town_section }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-left">
                    <span class="px-2 py-1 text-xs font-medium rounded-full truncate max-w-[80px] inline-flex items-center justify-start align-middle text-left" 
                      :class="{
                        'bg-blue-100 text-blue-800': geo.country === 'Indonesia',
                        'bg-red-100 text-red-800': geo.country === 'Malaysia',
                        'bg-green-100 text-green-800': geo.country === 'Singapore',
                        'bg-yellow-100 text-yellow-800': geo.country === 'Thailand',
                        'bg-purple-100 text-purple-800': geo.country === 'Vietnam',
                        'bg-gray-100 text-gray-800': !['Indonesia', 'Malaysia', 'Singapore', 'Thailand', 'Vietnam'].includes(geo.country)
                      }">
                      {{ geo.area }}
                    </span>
                  </td>
                </tr>
                <tr v-if="filteredGeos.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-gray-500">No geo data available.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('country')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Country
          </button>
          <button type="button" @click="sortTable('area')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Area
          </button>
          <button type="button" @click="selectAndClose(selectedGeo)" class="py-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
import { ref, computed, watch, onMounted } from 'vue';
// defineProps and defineEmits are compiler macros and don't need to be imported

const props = defineProps({
  show: Boolean,
  geos: {
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
const selectedGeo = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

const isActiveGeo = (geo) => {
  const status = geo?.status ?? geo?.STATUS;
  if (status === undefined || status === null || String(status).trim() === '') return true;

  const s = String(status).trim().toLowerCase();
  if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
  if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

  return true;
};

// Compute filtered geos based on search query
const filteredGeos = computed(() => {
  let geos = props.geos;
  
  if (!Array.isArray(geos)) {
    console.error('Modal - geos is not an array:', geos);
    return [];
  }
  
  // Only show active geos (hide obsolete ones)
  geos = geos.filter(isActiveGeo);
  
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    geos = geos.filter(geo =>
      geo.code?.toLowerCase().includes(q) ||
      geo.country?.toLowerCase().includes(q) ||
      geo.state?.toLowerCase().includes(q) ||
      geo.town?.toLowerCase().includes(q) ||
      geo.town_section?.toLowerCase().includes(q) ||
      geo.area?.toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...geos].sort((a, b) => {
    const valA = a[sortKey.value]?.toLowerCase() || '';
    const valB = b[sortKey.value]?.toLowerCase() || '';
    
    if (valA < valB) return sortAsc.value ? -1 : 1;
    if (valA > valB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(geo) {
  selectedGeo.value = geo;
}

// Select and close modal
function selectAndClose(geo) {
  if (geo) {
    emit('select', geo);
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
    selectedGeo.value = null;
    searchQuery.value = '';
  }
});

// Make sure props.show watcher is trigger initially
onMounted(() => {
  if (props.show) {
    selectedGeo.value = null;
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
