<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-globe-americas"></i>
          </div>
          <h3 class="text-xl font-semibold">Geo Location Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search geo codes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('code')">Geo Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('country')">Country</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('state')">State</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('town')">Town</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('town_section')">Town Section</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('area')">Area</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="geo in filteredGeos" :key="geo.code"
                :class="['hover:bg-blue-50 cursor-pointer', selectedGeo && selectedGeo.code === geo.code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(geo)"
                @dblclick="selectAndClose(geo)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ geo.code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ geo.country }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ geo.state }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ geo.town }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ geo.town_section }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" 
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
          <button type="button" @click="selectAndClose(selectedGeo)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
import { ref, computed, watch, onMounted } from 'vue';const props = defineProps({
  show: Boolean,
  geos: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedGeo = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

// Compute filtered geos based on search query
const filteredGeos = computed(() => {
  let geos = props.geos;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    geos = geos.filter(geo =>
      geo.code.toLowerCase().includes(q) ||
      geo.country.toLowerCase().includes(q) ||
      geo.state.toLowerCase().includes(q) ||
      geo.town.toLowerCase().includes(q) ||
      geo.town_section.toLowerCase().includes(q) ||
      geo.area.toLowerCase().includes(q)
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
