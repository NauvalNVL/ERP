<template>
  <AppLayout :header="'Roll Trim By Product Design'">
    <Head title="Define Roll Trim By Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-crop-alt mr-3"></i> Define Roll Trim By Product Design
      </h2>
      <p class="text-blue-100">Manage roll trim specifications for each product design combination</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by product code, design name, or flute code..."
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
                    <th @click="sortBy('product_design_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        P/Design <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('product_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
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
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading roll trims...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedItems.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      No roll trims found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="item in paginatedItems" :key="item.id" 
                      @click="selectItem(item)" 
                      :class="{'bg-blue-50': selectedItem && selectedItem.id === item.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.product_design_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.product_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.flute_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(item)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[item.id]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[item.id]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="item.compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!toggleLoading[item.id]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                      </div>
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" 
                             :value="item.min_trim" 
                             @input="item.min_trim = Number($event.target.value)" 
                             @blur="updateTrim(item, 'min_trim')"
                             class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                             placeholder="Min">
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" 
                             :value="item.max_trim" 
                             @input="item.max_trim = Number($event.target.value)" 
                             @blur="updateTrim(item, 'max_trim')"
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
              <span>Showing {{ paginatedItems.length }} of {{ filteredItems.length }} results</span>
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
          <div v-if="selectedItem" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Trim Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product Design:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.product_design_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.product_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.flute_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedItem.compute, 'text-red-600': !selectedItem.compute}">
                  {{ selectedItem.compute ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Min (mm):</span>
                <span class="font-medium text-gray-900 block">{{ selectedItem.min_trim || 'N/A' }}</span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Max (mm):</span>
                <span class="font-medium text-gray-900 block">{{ selectedItem.max_trim || 'N/A' }}</span>
              </div>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-crop-alt text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Item Selected</h3>
            <p class="text-gray-500 mb-4">Select an item from the table to view its trim details</p>
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
const items = ref([]);
const selectedItem = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const sortOrder = ref({ field: 'product_design_name', direction: 'asc' });
const toast = useToast();
const currentPage = ref(1);
const itemsPerPage = ref(10);
const toggleLoading = ref({});
const savingStatus = ref({}); // To manage saving status for inline edits

// Computed Properties
const filteredItems = computed(() => {
  let result = items.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      (item.product_design_name && item.product_design_name.toLowerCase().includes(query)) ||
      (item.product_code && item.product_code.toLowerCase().includes(query)) ||
      (item.flute_code && item.flute_code.toLowerCase().includes(query))
    );
  }

  return [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    // Handle null/undefined values for sorting numerical fields
    const valA = a[field] ?? (typeof a[field] === 'number' ? -Infinity : '');
    const valB = b[field] ?? (typeof b[field] === 'number' ? -Infinity : '');

    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;
    return 0;
  });
});

const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value));

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredItems.value.slice(start, end);
});

// Watchers
watch(filteredItems, () => { currentPage.value = 1; });
watch(itemsPerPage, () => { currentPage.value = 1; });

// Main Methods
const loadData = async () => {
  loading.value = true;
  try {
    // Fetch all necessary base data first
    const [designsResponse, productsResponse, flutesResponse] = await Promise.all([
      axios.get('/api/product-designs'),
      axios.get('/api/products'),
      axios.get('/api/paper-flutes'),
    ]);

    const allProductDesigns = designsResponse.data;
    const allProducts = productsResponse.data;
    const allFlutes = flutesResponse.data;

    // Try to load existing roll trims
    const trimResponse = await axios.get('/api/roll-trim-by-product-design');
    let existingTrims = [];

    if (trimResponse.data && trimResponse.data.status === 'success' && Array.isArray(trimResponse.data.data)) {
      existingTrims = trimResponse.data.data;
    }

    // Create a map for quick lookup of existing trims
    const existingTrimsMap = new Map();
    existingTrims.forEach(trim => {
      existingTrimsMap.set(`${trim.product_design_id}-${trim.product_id}-${trim.flute_id}`, trim);
    });

    // Generate all combinations and merge with existing data
    const combinations = [];
    let idCounter = 1; // For unique frontend IDs if no backend ID exists yet

    for (const design of allProductDesigns) {
      for (const product of allProducts) {
        for (const flute of allFlutes) {
          const key = `${design.id}-${product.id}-${flute.id}`;
          const existingTrim = existingTrimsMap.get(key);

          combinations.push({
            id: existingTrim ? existingTrim.id : `new-${idCounter++}`, // Use backend ID if exists, otherwise generate temp ID
            product_id: product.id,
            product_code: product.product_code || product.name || 'N/A',
            product_design_id: design.id,
            product_design_name: design.pd_name || design.pd_alt_name || 'N/A',
            flute_id: flute.id,
            flute_code: flute.code || 'N/A',
            compute: existingTrim ? (existingTrim.compute === 1 || existingTrim.compute === true) : false,
            min_trim: existingTrim ? existingTrim.min_trim : 0,
            max_trim: existingTrim ? existingTrim.max_trim : 0,
          });
        }
      }
    }

    items.value = combinations;
    filteredItems.value = [...items.value];
  } catch (error) {
    console.error('Error loading data:', error);
    toast.error(error.response?.data?.message || 'Failed to load roll trim by product design data.');
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  selectedItem.value = null;
  searchQuery.value = '';
  loadData();
};

const selectItem = (item) => {
  selectedItem.value = item;
};

// Helper function to save trim data
async function _saveTrimData(trimData) {
  const payload = {
    product_id: trimData.product_id,
    product_design_id: trimData.product_design_id,
    flute_id: trimData.flute_id,
    compute: trimData.compute,
    min_trim: trimData.min_trim === '' || trimData.min_trim === null ? 0 : Number(trimData.min_trim),
    max_trim: trimData.max_trim === '' || trimData.max_trim === null ? 0 : Number(trimData.max_trim),
  };

  // Send as batch update/create. The backend should handle upsert logic based on combination.
  return axios.post('/api/roll-trim-by-product-design/batch', [payload]);
}

const updateTrim = async (item, field) => {
  const statusKey = `${item.id}-${field}`;
  if (savingStatus.value[statusKey]) return; // Prevent double saving

  savingStatus.value[statusKey] = true;
  
  try {
    const updatedItemData = { ...item };
    
    const response = await _saveTrimData(updatedItemData);
    
    if (response.data.status === 'success') {
      const updatedResult = response.data.results && response.data.results.length > 0 ? response.data.results[0] : null;

      if (updatedResult) {
        const index = items.value.findIndex(i => i.id === item.id);
        if (index !== -1) {
          // Update the specific fields that might have been changed by the backend 
          items.value[index].min_trim = updatedResult.min_trim;
          items.value[index].max_trim = updatedResult.max_trim;
          
          // If it was a new item and now has a real ID from backend, update the frontend ID
          if (updatedResult.id && typeof items.value[index].id === 'string' && items.value[index].id.startsWith('new-')) {
            items.value[index].id = updatedResult.id; 
          }

          if (selectedItem.value && selectedItem.value.id === item.id) {
            selectedItem.value = { ...selectedItem.value, ...items.value[index] };
          }
        }
        
        toast.success(`${item.product_design_name} - ${item.flute_code} ${field.replace(/_/g, ' ')} updated.`);
      } else {
        // If updatedResult is null, it means the update failed on the backend for this specific item
        toast.error(response.data.message || `Failed to update ${field}. No data returned for this item.`);
        // Revert the local changes for this item by reloading data
        loadData(); 
      }
    } else {
      // Handle backend errors
      if (response.data.errors && response.data.errors.length > 0) {
        response.data.errors.forEach(err => {
          toast.error(`Error for ${err.product_design_id}-${err.product_id}-${err.flute_id}: ${err.error}`);
        });
      } else {
        toast.error(response.data.message || `Failed to update ${field}.`);
      }
      // Re-fetch data to revert to previous state if save failed
      loadData(); 
    }
  } catch (error) {
    console.error(`Error updating ${field}:`, error);
    toast.error(error.response?.data?.message || `Failed to update ${field}.`);
    // Re-fetch data to revert to previous state if save failed
    loadData(); 
  } finally {
    savingStatus.value[statusKey] = false;
  }
};

const toggleCompute = async (item) => {
  if (toggleLoading.value[item.id]) return;
  toggleLoading.value[item.id] = true;
  
  try {
    const updatedItemData = { ...item, compute: !item.compute };
    const response = await _saveTrimData(updatedItemData);
    
    if (response.data.status === 'success') {
      const updatedResult = response.data.results && response.data.results.length > 0 ? response.data.results[0] : null;

      if (updatedResult) {
        const index = items.value.findIndex(i => i.id === item.id);
        if (index !== -1) {
          items.value[index].compute = !item.compute;
          // If it was a new item and now has a real ID from backend, update the frontend ID
          if (updatedResult.id && typeof items.value[index].id === 'string' && items.value[index].id.startsWith('new-')) {
            items.value[index].id = updatedResult.id;
          }
          if (selectedItem.value && selectedItem.value.id === item.id) {
            selectedItem.value = items.value[index];
          }
        }
        
        toast.success(`Compute for ${item.product_design_name} - ${item.flute_code} updated.`);
      } else {
        // If updatedResult is null, it means the update failed on the backend for this specific item
        toast.error(response.data.message || 'Failed to update compute status. No data returned for this item.');
        const index = items.value.findIndex(i => i.id === item.id);
        if (index !== -1) {
          items.value[index].compute = item.compute; // Revert on error
        }
      }
    } else {
      // Handle backend errors
      if (response.data.errors && response.data.errors.length > 0) {
        response.data.errors.forEach(err => {
          toast.error(`Error for ${err.product_design_id}-${err.product_id}-${err.flute_id}: ${err.error}`);
        });
      } else {
        toast.error(response.data.message || 'Failed to update compute status.');
      }
      const index = items.value.findIndex(i => i.id === item.id);
      if (index !== -1) {
        items.value[index].compute = item.compute; // Revert on error
      }
    }
  } catch (error) {
    console.error('Error toggling compute:', error);
    toast.error(error.response?.data?.message || 'Failed to update compute status.');
    const index = items.value.findIndex(i => i.id === item.id);
    if (index !== -1) {
      items.value[index].compute = item.compute; // Revert on error
    }
  } finally {
    toggleLoading.value[item.id] = false;
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
  window.location.href = '/standard-formula/setup-roll-trim-by-product-design/view-print';
};

// Lifecycle
onMounted(loadData);
</script>

<style scoped>
/* Button styles */
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
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
</style>
