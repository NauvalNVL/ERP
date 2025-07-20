<template>
  <AppLayout :header="'Define SKU Price'">
    <Head title="Define SKU Price" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-dollar-sign mr-3 text-blue-200"></i> Define SKU Price
      </h2>
      <p class="text-blue-100">Manage pricing for Stock Keeping Units (SKUs)</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by SKU, name, or category..."
                  class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none py-2.5">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printSkus" class="btn-info flex-1 md:flex-none py-2.5">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-md">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                  <tr>
                    <th @click="sortBy('sku')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        SKU# <i class="fas fa-sort ml-1" :class="getSortIconClass('sku')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('sts')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        STS <i class="fas fa-sort ml-1" :class="getSortIconClass('sts')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('sku_name')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        SKU Name <i class="fas fa-sort ml-1" :class="getSortIconClass('sku_name')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('category_code')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        CAT <i class="fas fa-sort ml-1" :class="getSortIconClass('category_code')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('type')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Type <i class="fas fa-sort ml-1" :class="getSortIconClass('type')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('uom')" class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        UOM <i class="fas fa-sort ml-1" :class="getSortIconClass('uom')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('price')" class="px-6 py-3.5 text-right text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center justify-end">
                        Price <i class="fas fa-sort ml-1" :class="getSortIconClass('price')"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading SKUs...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="filteredSkus.length === 0" class="hover:bg-gray-50">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex flex-col items-center py-4">
                        <i class="fas fa-search text-gray-400 text-3xl mb-2"></i>
                        <span>No SKUs found. Try adjusting your search.</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-for="sku in paginatedSkus" :key="sku.sku" 
                      @click="selectSku(sku)" 
                      @dblclick="openEditModal(sku)"
                      :class="{'bg-blue-50 border-l-4 border-blue-500': selectedSku && selectedSku.sku === sku.sku}"
                      class="hover:bg-gray-50 cursor-pointer transition-all duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <span class="px-2 py-1 rounded-full text-xs" 
                            :class="sku.sts === 'ACT' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ sku.sts }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.sku_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.category_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.uom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right" 
                        :class="sku.price > 0 ? 'text-green-600' : 'text-gray-600'">
                      {{ formatCurrency(sku.price) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
            <div class="mb-2 sm:mb-0">
              <span class="font-medium">Showing {{ paginatedSkus.length }} of {{ filteredSkus.length }} SKUs</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <div class="flex items-center border rounded overflow-hidden shadow-sm">
                <button :disabled="currentPage === 1" @click="currentPage--" class="px-3 py-1.5 hover:bg-gray-200 disabled:opacity-50 disabled:bg-gray-100 border-r">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <span class="px-4 py-1.5 bg-white">{{ currentPage }} / {{ totalPages }}</span>
                <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-3 py-1.5 hover:bg-gray-200 disabled:opacity-50 disabled:bg-gray-100 border-l">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details and Price Update -->
        <div class="w-full md:w-96 bg-white rounded-lg shadow-md border border-gray-200">
          <div v-if="selectedSku" class="flex flex-col h-full">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg">
              <h3 class="text-lg font-semibold flex items-center">
                <i class="fas fa-info-circle mr-2"></i> SKU Details
              </h3>
            </div>
            
            <!-- Content -->
            <div class="p-4 flex-grow">
              <!-- Main Info Section -->
              <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4">
                <div class="text-xl font-semibold text-blue-800 mb-1">{{ selectedSku.sku }}</div>
                <div class="text-gray-700">{{ selectedSku.sku_name }}</div>
                <div class="mt-1 flex items-center">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ selectedSku.type }}</span>
                </div>
              </div>
              
              <!-- Details Grid -->
              <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">Category</div>
                  <div class="font-medium">{{ selectedSku.category_code }}</div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">UOM</div>
                  <div class="font-medium">{{ selectedSku.uom }}</div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">BOH</div>
                  <div class="font-medium">{{ selectedSku.boh }}</div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">FPO</div>
                  <div class="font-medium">{{ selectedSku.fpo }}</div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">ROL</div>
                  <div class="font-medium">{{ selectedSku.rol }}</div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200">
                  <div class="text-xs text-gray-500">Status</div>
                  <div class="font-medium">{{ selectedSku.sts }}</div>
                </div>
              </div>
              
              <!-- Price Section -->
              <div class="bg-green-50 border border-green-100 rounded-lg p-3 mb-4">
                <div class="flex justify-between items-center">
                  <span class="text-sm font-medium text-gray-700">Current Price</span>
                  <span class="text-xl font-bold text-green-600">{{ formatCurrency(selectedSku.price) }}</span>
                </div>
                <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                  <span>Min Qty: <span class="font-medium">{{ selectedSku.min_qty || 0 }}</span></span>
                  <span>Max Qty: <span class="font-medium">{{ selectedSku.max_qty || 0 }}</span></span>
                </div>
              </div>
              
              <!-- Additional Info -->
              <div v-if="selectedSku.additional_name || selectedSku.part_number1 || selectedSku.part_number2 || selectedSku.part_number3" 
                   class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Information</h4>
                
                <div v-if="selectedSku.additional_name" class="mb-2">
                  <div class="text-xs text-gray-500">Additional Name</div>
                  <div class="text-sm">{{ selectedSku.additional_name }}</div>
                </div>
                
                <div v-if="selectedSku.part_number1 || selectedSku.part_number2 || selectedSku.part_number3">
                  <div class="text-xs text-gray-500 mb-1">Part Numbers</div>
                  <div v-if="selectedSku.part_number1" class="flex items-center mb-1">
                    <span class="text-xs bg-gray-200 text-gray-600 px-1 rounded mr-2">1</span>
                    <span class="text-sm">{{ selectedSku.part_number1 }}</span>
                  </div>
                  <div v-if="selectedSku.part_number2" class="flex items-center mb-1">
                    <span class="text-xs bg-gray-200 text-gray-600 px-1 rounded mr-2">2</span>
                    <span class="text-sm">{{ selectedSku.part_number2 }}</span>
                  </div>
                  <div v-if="selectedSku.part_number3" class="flex items-center">
                    <span class="text-xs bg-gray-200 text-gray-600 px-1 rounded mr-2">3</span>
                    <span class="text-sm">{{ selectedSku.part_number3 }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Footer with action button -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
              <button @click="openEditModal(selectedSku)" class="w-full btn-blue py-2.5">
                <i class="fas fa-edit mr-2"></i> Edit Price
              </button>
              <div class="mt-2 text-xs text-center text-gray-500">
                <i class="fas fa-info-circle mr-1"></i> Double-click on a row to edit price
              </div>
            </div>
          </div>
          
          <!-- Empty state -->
          <div v-else class="flex flex-col items-center justify-center h-96 text-center p-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-dollar-sign text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select a SKU from the table to view details and update its price</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
      <div class="flex flex-wrap justify-between gap-3">
        <div class="flex flex-wrap gap-3">
          <button class="btn-secondary py-2.5">
            <i class="fas fa-list mr-2"></i>More Options
          </button>
          <button class="btn-secondary py-2.5">
            <i class="fas fa-box mr-2"></i>SKU Part#
          </button>
          <button class="btn-secondary py-2.5">
            <i class="fas fa-dollar-sign mr-2"></i>Floating PO
          </button>
        </div>
        <div class="flex flex-wrap gap-3">
          <button class="btn-secondary py-2.5">
            <i class="fas fa-balance-scale mr-2"></i>SKU Balance
          </button>
          <button class="btn-secondary py-2.5">
            <i class="fas fa-exchange-alt mr-2"></i>Alt Unit
          </button>
          <button class="btn-secondary py-2.5">
            <i class="fas fa-sticky-note mr-2"></i>Add Note
          </button>
          <button class="btn-secondary py-2.5">
            <i class="fas fa-history mr-2"></i>Process Log
          </button>
        </div>
      </div>
    </div>

    <!-- SKU Price Edit Modal -->
    <SkuPriceModal
      :show="showEditModal"
      :sku="editingSku || null"
      @close="closeEditModal"
      @update="handleUpdatePrice"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { route } from '@/ziggy';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';
import SkuPriceModal from '@/Components/SkuPriceModal.vue';
import { debounce } from 'lodash';

const props = defineProps({
  skus: Object,
});

// State variables
const selectedSku = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
  field: 'sku',
  direction: 'asc'
});

// Modal state
const showEditModal = ref(false);
const editingSku = ref(null);

// Initialize toast
const toast = useToast();

// Form for updating SKU price
const form = useForm({
  price: 0,
  min_qty: 0,
  max_qty: 0,
  additional_name: '',
  part_number1: '',
  part_number2: '',
  part_number3: ''
});

// Computed properties for filtering and pagination
const filteredSkus = computed(() => {
  let result = props.skus?.data || [];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(sku =>
      (sku.sku && sku.sku.toLowerCase().includes(query)) ||
      (sku.sku_name && sku.sku_name.toLowerCase().includes(query)) ||
      (sku.category_code && sku.category_code.toLowerCase().includes(query))
    );
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
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

// Get sort icon class based on current sort field and direction
const getSortIconClass = (field) => {
  if (sortOrder.value.field === field) {
    return sortOrder.value.direction === 'asc' ? 'text-blue-600 fa-sort-up' : 'text-blue-600 fa-sort-down';
  }
  return 'text-gray-400';
};

// Format currency for display
const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2
  }).format(value || 0);
};

// Watch for changes in filtered results to adjust current page
watch(filteredSkus, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Function to select a SKU and populate the form
const selectSku = (sku) => {
  selectedSku.value = sku;
  form.price = sku.price;
  form.min_qty = sku.min_qty;
  form.max_qty = sku.max_qty;
  form.additional_name = sku.additional_name;
  form.part_number1 = sku.part_number1;
  form.part_number2 = sku.part_number2;
  form.part_number3 = sku.part_number3;
};

// Function to open the edit modal
const openEditModal = (sku) => {
  editingSku.value = sku;
  showEditModal.value = true;
};

// Function to close the edit modal
const closeEditModal = () => {
  showEditModal.value = false;
};

// Function to handle price updates from the modal
const handleUpdatePrice = (updatedData) => {
  form.price = updatedData.price;
  form.min_qty = updatedData.min_qty;
  form.max_qty = updatedData.max_qty;
  form.additional_name = updatedData.additional_name;
  form.part_number1 = updatedData.part_number1;
  form.part_number2 = updatedData.part_number2;
  form.part_number3 = updatedData.part_number3;
  
  updatePrice(updatedData.sku);
  closeEditModal();
};

// Function to update the SKU price
const updatePrice = (skuId) => {
  if (!skuId && !selectedSku.value) return;
  
  const targetSku = skuId || selectedSku.value.sku;
  
  form.put(route('sku-price.update', { sku: targetSku }), {
    onSuccess: () => {
      // Update the selected SKU data if it's the one being updated
      if (selectedSku.value && selectedSku.value.sku === targetSku) {
        selectedSku.value.price = form.price;
        selectedSku.value.min_qty = form.min_qty;
        selectedSku.value.max_qty = form.max_qty;
        selectedSku.value.additional_name = form.additional_name;
        selectedSku.value.part_number1 = form.part_number1;
        selectedSku.value.part_number2 = form.part_number2;
        selectedSku.value.part_number3 = form.part_number3;
      }
      
      // Update the SKU in the list
      const skuIndex = props.skus.data.findIndex(s => s.sku === targetSku);
      if (skuIndex !== -1) {
        props.skus.data[skuIndex].price = form.price;
        props.skus.data[skuIndex].min_qty = form.min_qty;
        props.skus.data[skuIndex].max_qty = form.max_qty;
        props.skus.data[skuIndex].additional_name = form.additional_name;
        props.skus.data[skuIndex].part_number1 = form.part_number1;
        props.skus.data[skuIndex].part_number2 = form.part_number2;
        props.skus.data[skuIndex].part_number3 = form.part_number3;
      }
      
      toast.success('SKU price updated successfully');
    },
    onError: () => {
      toast.error('Failed to update SKU price');
    }
  });
};

// Function to refresh data
const refreshData = () => {
  selectedSku.value = null;
  searchQuery.value = '';
  router.get(route('sku-price.index'), {}, { preserveState: true });
};

// Function to sort data
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Function to print SKUs
const printSkus = () => {
  router.get(route('sku-price.view-print'));
};

onMounted(() => {
  // Initial setup
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
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Selected row styling */
tbody tr.bg-blue-50 {
  box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
}

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Form field styles */
.form-field {
  @apply bg-gray-50 p-3 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-1 flex items-center;
}

.form-input {
  @apply w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}
</style>
