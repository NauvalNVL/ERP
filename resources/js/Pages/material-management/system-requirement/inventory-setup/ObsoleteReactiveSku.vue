<template>
  <AppLayout :header="'Obsolete/Reactive SKU Status'">
    <Head title="Obsolete/Reactive SKU Status" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-toggle-on mr-3"></i> Obsolete/Reactive SKU Status
      </h2>
      <p class="text-blue-100">Manage SKU activation status by marking items as active or obsolete</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area - Table Section -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by SKU# or SKU Name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
        </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newSku" class="btn-primary flex-1 md:flex-none">
            <i class="fas fa-box-open mr-2"></i> Define SKU
          </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printSkus" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print All
          </button>
        </div>
      </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="w-12 px-4 py-3">
                <input type="checkbox" v-model="selectAll" 
                  @click="toggleSelectAll"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
              </th>
                    <th @click="sortBy('sku')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      SKU# <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('sts')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      STS <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('sku_name')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      SKU Name <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('category_code')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      CAT <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('type')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Type <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('uom')" scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      UOM <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('boh')" scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      BOH <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('fpo')" scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      FPO <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortBy('rol')" scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      ROL <i class="fas fa-sort ml-1"></i>
                    </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">
                <i class="fas fa-spinner fa-spin mr-2"></i> Loading SKUs...
              </td>
            </tr>
                  <tr v-else-if="paginatedSkus.length === 0" class="hover:bg-gray-50">
              <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">
                No SKUs found. Try adjusting your search criteria.
              </td>
            </tr>
            <template v-else>
                    <tr v-for="sku in paginatedSkus" :key="sku.sku" class="hover:bg-gray-50" :class="{'bg-blue-50': isSelected(sku) || (selectedSku && selectedSku.sku === sku.sku)}"
                        @click="selectSku(sku)">
                <td class="px-4 py-2 whitespace-nowrap">
                  <input type="checkbox" :value="sku" v-model="selectedSkus" 
                    :disabled="(action === 'obsolete' && !sku.is_active) || (action === 'reactivate' && sku.is_active)"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm">
                  <span :class="sku.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                    {{ sku.sts }}
                  </span>
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.sku_name }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.category_code }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.type || 'NS' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.uom || 'PCS' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.boh) }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.fpo) }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.rol) }}</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
          
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedSkus.length }} of {{ filteredSkus.length }} SKUs</span>
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
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details and Actions -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedSku" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> SKU Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">SKU#:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.sku }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedSku.sku_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span :class="selectedSku.is_active ? 'text-green-600' : 'text-red-600'" class="font-semibold">{{ selectedSku.sts }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Category:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.category_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Type:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.type || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-200 pb-2">
                <span class="text-gray-600">UOM:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.uom || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">BOH:</span>
                <span class="font-medium text-gray-900">{{ formatNumber(selectedSku.boh) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">FPO:</span>
                <span class="font-medium text-gray-900">{{ formatNumber(selectedSku.fpo) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">ROL:</span>
                <span class="font-medium text-gray-900">{{ formatNumber(selectedSku.rol) }}</span>
              </div>
            </div>
            <div class="mt-6 flex flex-col space-y-2">
              <button @click="openConfirmModalFromSelected('obsolete')" :disabled="!selectedSku.is_active" class="btn-danger w-full">
                <i class="fas fa-ban mr-1"></i> Mark Obsolete
              </button>
              <button @click="openConfirmModalFromSelected('reactivate')" :disabled="selectedSku.is_active" class="btn-success w-full">
                <i class="fas fa-check-circle mr-1"></i> Reactivate
              </button>
              <button @click="viewSkuBalance(selectedSku)" class="btn-secondary w-full">
                <i class="fas fa-balance-scale mr-1"></i> View SKU Balance
              </button>
              <button @click="viewProcessLog(selectedSku)" class="btn-secondary w-full">
                <i class="fas fa-history mr-1"></i> View Process Log
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-toggle-on text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select an SKU from the table to view details and perform actions.</p>
            <button @click="openOptionsModal" class="btn-secondary">
              <i class="fas fa-cog mr-1"></i> Options
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals (kept as existing components) -->
    <SkuSearchModal 
      :show="showSearchModal" 
      @close="closeSearchModal"
      @search="handleSearch"
      :categories="categories"
      :initialSearchParams="searchParams"
      :initialAction="action"
      :initialReason="reason"
      :initialRecordFilters="recordFilters" />

    <!-- SkuResultsModal is no longer needed as results are displayed in the main table -->
    <!-- <SkuResultsModal 
      :show="showResultsModal" 
      @close="closeResultsModal"
      @new-search="openSearchModal(action)"
      @select="handleSelectSkus"
      @print="printSkus"
      @view-balance="viewSkuBalance"
      @view-log="viewProcessLog"
      :skus="skus"
      :loading="loading"
      :action="action" /> -->

    <SkuConfirmModal 
      :show="showConfirmModal" 
      @close="closeConfirmModal"
      @confirm="confirmProcessSkus"
      :skus="selectedSkus"
      :action="action"
      v-model:reason="reason" />

    <SkuOptionsModal 
      :show="showOptionsModal" 
      @close="closeOptionsModal"
      @apply="applyOptions"
      :initialSortOptions="sortOptions"
      :initialRecordFilters="recordFilters" />
    
    <SkuBalanceModal 
      :show="showSkuBalanceModal" 
      @close="closeSkuBalanceModal"
      :sku="selectedSkuForModal" />

    <ProcessLogModal 
      :show="showProcessLogModal" 
      @close="closeProcessLogModal"
      :sku="selectedSkuForModal" />
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';
import SkuSearchModal from '@/Components/SkuSearchModal.vue';
import SkuConfirmModal from '@/Components/SkuConfirmModal.vue';
import SkuBalanceModal from '@/Components/SkuBalanceModal.vue';
import ProcessLogModal from '@/Components/ProcessLogModal.vue';
import SkuOptionsModal from '@/Components/SkuOptionsModal.vue';
import axios from 'axios';

// Toast
const toast = useToast();

// State variables
const skus = ref([]);
const selectedSku = ref(null); // For single selected SKU in the side panel
const searchQuery = ref(''); // For the main search input
const categories = ref([]);
const loading = ref(false);
const searchParams = ref({
  sku: '',
  category: '',
  status: 'all' // all, active, obsolete
});
const action = ref('obsolete'); // obsolete, reactivate (used for confirm modal and checkbox logic)
const reason = ref(''); // Reason for status change
const selectedSkus = ref([]); // For checkbox selections in the table
const selectAll = ref(false);

// Pagination state
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Modal states
const showSearchModal = ref(false);
const showConfirmModal = ref(false);
const showOptionsModal = ref(false);
const showSkuBalanceModal = ref(false);
const showProcessLogModal = ref(false);

// New ref for SKU object passed to modals (balance/log)
const selectedSkuForModal = ref(null);

// Additional state for desktop-like functionality (used in SkuOptionsModal)
const sortOptions = ref({
  field: 'sku', // sku, cat_sku, part, name, sts, sku_name, category_code, type, uom, boh, fpo, rol
  ascending: true
});
const recordFilters = ref({
  active: true,
  obsolete: true,
  stockItem: true,
  nonStock: true
});

// Computed properties for filtering and pagination
const filteredSkus = computed(() => {
  let result = skus.value;
  
  // Apply main search query filter (SKU# or SKU Name)
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(sku =>
      (sku.sku && sku.sku.toLowerCase().includes(query)) ||
      (sku.sku_name && sku.sku_name.toLowerCase().includes(query))
    );
  }

  // Apply record type filters from options modal
  result = result.filter(sku => {
    const isStock = sku.type === 'S' || sku.type === null; // Assuming null or empty means stock item
    const isNonStock = sku.type === 'NS';

    if (recordFilters.value.stockItem && recordFilters.value.nonStock) {
      return true; // Show all if both types are checked
    } else if (recordFilters.value.stockItem && !recordFilters.value.nonStock) {
      return isStock;
    } else if (!recordFilters.value.stockItem && recordFilters.value.nonStock) {
      return isNonStock;
    }
    return false; // If neither is checked, show nothing
  });

  // Apply record status filters from options modal
  result = result.filter(sku => {
    if (recordFilters.value.active && recordFilters.value.obsolete) {
      return true; // Show all if both statuses are checked
    } else if (recordFilters.value.active && !recordFilters.value.obsolete) {
      return sku.is_active;
    } else if (!recordFilters.value.active && recordFilters.value.obsolete) {
      return !sku.is_active;
    }
    return false; // If neither is checked, show nothing
  });

  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOptions.value.field;
    const direction = sortOptions.value.ascending ? 1 : -1;
    
    const valA = a[field];
    const valB = b[field];

    if ((valA || '') < (valB || '')) return -1 * direction;
    if ((valA || '') > (valB || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedSkus = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredSkus.value.slice(start, end);
});

const totalPages = computed(() => {
    const total = filteredSkus.value.length;
    if (total === 0) return 1;
    return Math.ceil(total / itemsPerPage.value);
});

// Watchers for pagination
watch(filteredSkus, () => {
  // Reset current page if filters reduce total pages
  if (currentPage.value > totalPages.value) {
      currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1; // Reset to first page when items per page changes
});

// Check if an SKU is selected (for checkboxes)
const isSelected = (sku) => {
  return selectedSkus.value.some(selected => selected.sku === sku.sku);
};

// Select a single SKU for the side panel details
const selectSku = (sku) => {
  selectedSku.value = sku;
  // Clear checkbox selections when a single SKU is selected for detail view
  selectedSkus.value = [];
  selectAll.value = false;
};

// Toggle select all checkboxes on current page
const toggleSelectAll = () => {
  if (selectAll.value) {
    // If selectAll is true, it means the checkbox was just checked
    // Filter skus based on the current `action` to only select items that can be toggled
    if (action.value === 'obsolete') {
      selectedSkus.value = paginatedSkus.value.filter(sku => sku.is_active);
    } else if (action.value === 'reactivate') {
      selectedSkus.value = paginatedSkus.value.filter(sku => !sku.is_active);
    } else {
      // If no specific action is set (e.g., just viewing), select all on the current page
      selectedSkus.value = [...paginatedSkus.value];
    }
  } else {
    // If selectAll is false, it means the checkbox was just unchecked
    selectedSkus.value = [];
  }
};

// Fetch categories for search modal dropdown
const fetchCategories = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/material-management/skus/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    toast.error(`Failed to fetch categories: ${error.response?.status === 404 ? 'API endpoint not found' : error.message}`);
  } finally {
    loading.value = false;
  }
};

// Main function to fetch SKUs from the backend API
const fetchSkus = async () => {
  loading.value = true;
  selectedSkus.value = []; // Clear checkbox selections
  selectAll.value = false; // Uncheck select all
  selectedSku.value = null; // Clear single selection when refreshing list
  
  try {
    const params = new URLSearchParams();
    
    // Pass sorting parameters to the backend
    if (sortOptions.value.field) {
      params.append('sort_by', sortOptions.value.field);
      params.append('sort_dir', sortOptions.value.ascending ? 'asc' : 'desc');
    }

    // Pass record type filters to the backend
    if (recordFilters.value.stockItem && !recordFilters.value.nonStock) {
      params.append('type', 'S');
    } else if (!recordFilters.value.stockItem && recordFilters.value.nonStock) {
      params.append('type', 'NS');
    }
    
    // If a search query is active from the main input, pass it
    if (searchQuery.value) {
      params.append('search', searchQuery.value); // Backend needs to handle this generic search param
    }

    // If initial search parameters from SkuSearchModal are present (e.g., a specific SKU was searched)
    // These are separate from the main searchQuery to allow for more specific modal-driven searches
    if (searchParams.value.sku) { // This `searchParams` comes from SkuSearchModal
      params.append('sku', searchParams.value.sku);
    }
    if (searchParams.value.category) {
      params.append('category_code', searchParams.value.category);
    }
    if (searchParams.value.status !== 'all') {
      params.append('is_active', searchParams.value.status === 'active' ? '1' : '0');
    }
    
    const response = await axios.get(`/api/material-management/skus?${params.toString()}`);
    
    skus.value = response.data.map(sku => ({
      ...sku, 
      sts: sku.is_active ? 'ACT' : 'OBS' // Map status to ACT/OBS for display
    }));
    
    if (skus.value.length === 0) {
      toast.info('No SKUs found matching your criteria.');
    }
  } catch (error) {
    console.error('Error fetching SKUs:', error);
    toast.error(`Failed to fetch SKUs: ${error.response?.data?.message || error.message}`);
    skus.value = [];
  } finally {
    loading.value = false;
  }
};

// Main refresh function - clears search, selections, and re-fetches all data
const refreshData = () => {
  searchQuery.value = ''; 
  selectedSkus.value = []; 
  selectAll.value = false; 
  selectedSku.value = null; 
  currentPage.value = 1;
  // Reset initial search params to ensure they don't interfere with subsequent refreshes
  searchParams.value = { sku: '', category: '', status: 'all' }; 
  // Optionally reset record filters to default if refresh means "show everything"
  recordFilters.value = { active: true, obsolete: true, stockItem: true, nonStock: true };
  fetchSkus(); 
};

// Navigate to the Define SKU page
const newSku = () => {
  router.visit('/material-management/system-requirement/inventory-setup/sku');
};

// Handle column sorting
const sortBy = (field) => {
  if (sortOptions.value.field === field) {
    sortOptions.value.ascending = !sortOptions.value.ascending;
  } else {
    sortOptions.value.field = field;
    sortOptions.value.ascending = true;
  }
};

// Modal Control Functions
const openSearchModal = () => { // Removed actionType parameter
  showSearchModal.value = true;
};

const closeSearchModal = () => {
  showSearchModal.value = false;
};

const openOptionsModal = () => {
  showOptionsModal.value = true;
};

const closeOptionsModal = () => {
  showOptionsModal.value = false;
};

// Open SKU Balance modal for the currently selected SKU in the side panel
const viewSkuBalance = (sku) => {
  if (!sku) {
    toast.error('Please select a SKU from the table to view its balance.');
    return;
  }
  selectedSkuForModal.value = sku; // Set the single SKU for the modal
  showSkuBalanceModal.value = true;
};

const closeSkuBalanceModal = () => {
  showSkuBalanceModal.value = false;
};

// Open Process Log modal for the currently selected SKU in the side panel
const viewProcessLog = (sku) => {
  if (!sku) {
    toast.error('Please select a SKU from the table to view its process log.');
    return;
  }
  selectedSkuForModal.value = sku; // Set the single SKU for the modal
  showProcessLogModal.value = true;
};

const closeProcessLogModal = () => {
  showProcessLogModal.value = false;
};

// Open Confirm modal when Mark Obsolete/Reactivate buttons in side panel are clicked
const openConfirmModalFromSelected = (actionType) => {
  if (!selectedSku.value) {
    toast.error('Please select a SKU from the table to perform this action.');
    return;
  }
  action.value = actionType; // Set the action for the confirm modal
  reason.value = ''; // Clear previous reason
  selectedSkus.value = [selectedSku.value]; // Only the single selected SKU for confirmation
  showConfirmModal.value = true;
};

const closeConfirmModal = () => {
  showConfirmModal.value = false;
};

// Improve the confirmProcessSkus function with better validation and error handling
const confirmProcessSkus = async (confirmData) => {
  if (!confirmData.skus || confirmData.skus.length === 0) {
    toast.error('No SKUs selected for processing');
    return;
  }
  
  if (!confirmData.reason || confirmData.reason.trim() === '') {
    toast.error('A reason is required for changing SKU status');
    return;
  }
  
  try {
    loading.value = true;
    
    const payload = {
      skus: confirmData.skus.map(sku => sku.sku),
      set_active: confirmData.action === 'reactivate',
      reason: confirmData.reason
    };
    
    const response = await axios.post('/api/material-management/skus/bulk-toggle-active', payload);
    
    if (response.data && response.data.message) {
      toast.success(response.data.message);
    } else {
      toast.success(`Successfully ${confirmData.action === 'obsolete' ? 'made obsolete' : 'reactivated'} ${confirmData.skus.length} SKUs`);
    }
    
    // Close confirm modal
    closeConfirmModal();
    
    // Refresh the SKU data to show updated status
    fetchSkus();
    
    // Clear selections
    selectedSkus.value = [];
  } catch (error) {
    console.error('Error processing SKUs:', error);
    toast.error(error.response?.data?.message || 'Failed to process SKUs. Please try again.');
  } finally {
    loading.value = false;
  }
};

// Handler for search form submission from SkuSearchModal
const handleSearch = (searchData) => {
  // Update `searchParams` from the modal, these are distinct from `searchQuery`
  searchParams.value = searchData.searchParams;
  action.value = searchData.action; // This action might be used by the confirm modal if triggered from search
  reason.value = searchData.reason;
  // `recordFilters` are usually handled by SkuOptionsModal, but if SkuSearchModal provides them, update
  if (searchData.recordFilters) {
  recordFilters.value = searchData.recordFilters;
  }
  fetchSkus(); // Fetch SKUs based on new search params and filters
  closeSearchModal();
};

// Apply filter options from SkuOptionsModal and refresh data
const applyOptions = (options) => {
  sortOptions.value = options.sortOptions;
  recordFilters.value = options.recordFilters; // Update record filters
  fetchSkus(); // Re-fetch with new options
  closeOptionsModal();
};

// Print SKUs - prints all currently filtered SKUs in the table
const printSkus = () => {
  const skuIds = filteredSkus.value.length > 0 
    ? filteredSkus.value.map(sku => sku.sku).join(',')
    : ''; // If no SKUs after filtering, don't pass any IDs
    
  if (skuIds) {
    window.open(`/material-management/system-requirement/inventory-setup/sku/print?ids=${skuIds}`, '_blank');
  } else {
    toast.info('No SKUs to print.');
  }
};

// Utility functions
const formatNumber = (value) => {
  if (value === null || value === undefined) return '';
  return Number(value).toLocaleString(undefined, {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  });
};

// Initialize on mount: fetch categories for modals and initial SKU list
onMounted(async () => {
  await fetchCategories();
  await fetchSkus(); // Initial fetch of all SKUs
});
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}
</style> 