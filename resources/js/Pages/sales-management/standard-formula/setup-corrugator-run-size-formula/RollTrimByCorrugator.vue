<template>
  <AppLayout :header="'Roll Trim By Corrugator'">
    <Head title="Define Roll Trim By Corrugator" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-layer-group mr-3"></i> Define Roll Trim By Corrugator
      </h2>
      <p class="text-blue-100">Manage roll trim specifications for each flute type</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by flute code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printTrims" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                    <th @click="sortBy('flute_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Flute <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                      <th @click="sortBy('min_trim')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        <div class="flex items-center">
                          Min (mm) <i class="fas fa-sort ml-1"></i>
                        </div>
                      </th>
                      <th @click="sortBy('max_trim')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        <div class="flex items-center">
                          Max (mm) <i class="fas fa-sort ml-1"></i>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading roll trims...</span>
                        </div>
                      </td>
                    </tr>
                  <tr v-else-if="paginatedFlutes.length === 0" class="hover:bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      No flutes found.
                    </td>
                  </tr>
                  <tr v-for="flute in paginatedFlutes" :key="flute.id" 
                      @click="selectFlute(flute)" 
                      :class="{'bg-blue-50': selectedFlute && selectedFlute.id === flute.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ flute.flute_code }}</div>
                        <div class="text-sm text-gray-500">{{ flute.flute_name }}</div>
                      </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(flute)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[flute.id]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[flute.id]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="flute.compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!toggleLoading[flute.id]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                          </div>
                      </td>
                      <td class="px-2 py-1 whitespace-nowrap">
                        <input type="number" v-model.number="flute.min_trim" @blur="updateTrim(flute, 'min_trim')"
                               class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Min">
                      </td>
                      <td class="px-2 py-1 whitespace-nowrap">
                        <input type="number" v-model.number="flute.max_trim" @blur="updateTrim(flute, 'max_trim')"
                               class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Max">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
             <div>
                <span>Showing {{ paginatedFlutes.length }} of {{ filteredFlutes.length }} results</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
                  </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedFlute" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Trim Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Code:</span>
                <span class="font-medium text-gray-900">{{ selectedFlute.flute_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Name:</span>
                <span class="font-medium text-gray-900">{{ selectedFlute.flute_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedFlute.compute, 'text-red-600': !selectedFlute.compute}">
                  {{ selectedFlute.compute ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Min (mm):</span>
                <span class="font-medium text-gray-900 block">{{ selectedFlute.min_trim || 'N/A' }}</span>
              </div>
               <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Max (mm):</span>
                <span class="font-medium text-gray-900 block">{{ selectedFlute.max_trim || 'N/A' }}</span>
              </div>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-layer-group text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Flute Selected</h3>
            <p class="text-gray-500 mb-4">Select a flute to view its trim details</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';

// State
const flutes = ref([]);
const selectedFlute = ref(null);
const loading = ref(false);
    const searchQuery = ref('');
const sortOrder = ref({ field: 'flute_code', direction: 'asc' });
const toast = useToast();
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
const toggleLoading = ref({});
const savingStatus = ref({});

// Computed Properties
const filteredFlutes = computed(() => {
  let result = flutes.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(f =>
      (f.flute_code && f.flute_code.toLowerCase().includes(query)) ||
      (f.flute_name && f.flute_name.toLowerCase().includes(query))
    );
  }

  return [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });
});

const totalPages = computed(() => Math.ceil(filteredFlutes.value.length / itemsPerPage.value));

const paginatedFlutes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredFlutes.value.slice(start, end);
});

// Watchers
watch(filteredFlutes, () => { currentPage.value = 1; });
watch(itemsPerPage, () => { currentPage.value = 1; });

// Main Methods
const loadData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/roll-trim-by-corrugator');
    flutes.value = response.data;
      } catch (error) {
        console.error('Error loading data:', error);
    toast.error('Failed to load roll trims.');
      } finally {
        loading.value = false;
      }
    };

const refreshData = () => {
  selectedFlute.value = null;
  searchQuery.value = '';
  loadData();
};

const selectFlute = (flute) => {
  selectedFlute.value = flute;
};

// Helper function to save trim data
async function _saveTrimData(trimData) {
    const payload = {
        flute_id: trimData.id,
        flute_code: trimData.flute_code,
        compute: trimData.compute,
        min_trim: trimData.min_trim ?? 0,
        max_trim: trimData.max_trim,
    };

    // Ensure empty string is sent as null to the backend
    if (payload.max_trim === '') {
        payload.max_trim = null;
    }

    // Ensure min_trim is a number
    if (payload.min_trim === '') {
        payload.min_trim = 0;
    }

    // Convert to numbers
    payload.min_trim = Number(payload.min_trim);
    payload.max_trim = payload.max_trim !== null ? Number(payload.max_trim) : null;

    console.log('Sending payload to API:', [payload]);
    return axios.post('/api/roll-trim-by-corrugator/batch', [payload]);
}

const updateTrim = async (flute, field) => {
  const statusKey = `${flute.id}-${field}`;
  savingStatus.value[statusKey] = true;

  try {
    const response = await _saveTrimData(flute);
    const updatedResult = response.data.results[0];

    const index = flutes.value.findIndex(f => f.id === flute.id);
    if (index !== -1) {
      flutes.value[index].min_trim = updatedResult.min_trim;
      flutes.value[index].max_trim = updatedResult.max_trim;
      flutes.value[index].trim_id = updatedResult.id;
      
      if (selectedFlute.value && selectedFlute.value.id === flute.id) {
        selectedFlute.value = flutes.value[index];
      }
    }
    
    toast.success(`${flute.flute_code} ${field.replace('_', ' ')} updated.`);
  } catch (error) {
    console.error(`Error updating ${field}:`, error);
    const errorMessage = error.response?.data?.message || 
                        error.response?.data?.errors?.max_trim?.[0] ||
                        `Failed to update ${field}.`;
    toast.error(errorMessage);
    
    // Reload data to revert changes
    loadData();
  } finally {
    savingStatus.value[statusKey] = false;
  }
};

const toggleCompute = async (flute) => {
  if (toggleLoading.value[flute.id]) return;
  toggleLoading.value[flute.id] = true;
  
  try {
    const updatedFluteData = { ...flute, compute: !flute.compute };
    const response = await _saveTrimData(updatedFluteData);
    
    const index = flutes.value.findIndex(f => f.id === flute.id);
    if (index !== -1) {
      flutes.value[index].compute = !flute.compute;
      if (response.data.results.length > 0 && !flutes.value[index].trim_id) {
        flutes.value[index].trim_id = response.data.results[0].id;
      }
    }
    
    toast.success(`Compute for ${flute.flute_code} updated.`);
  } catch (error) {
    console.error('Error toggling compute:', error);
    const errorMessage = error.response?.data?.message || 
                        error.response?.data?.errors?.max_trim?.[0] ||
                        'Failed to update compute status.';
    toast.error(errorMessage);
    
    // Revert the change in the UI
    const index = flutes.value.findIndex(f => f.id === flute.id);
    if (index !== -1) {
      flutes.value[index].compute = flute.compute;
    }
  } finally {
    toggleLoading.value[flute.id] = false;
  }
};

// UI Methods
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

const printTrims = () => {
  window.location.href = '/standard-formula/setup-roll-trim-by-corrugator/view-print';
};

// Lifecycle
onMounted(loadData);

</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
tbody tr {
  transition: all 0.2s;
}
tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>
