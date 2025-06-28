<template>
  <AppLayout :header="'Corrugator Specification by Product'">
    <Head title="Define Corrugator Specification by Product" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-cogs mr-3"></i> Define Corrugator Specification by Product
      </h2>
      <p class="text-blue-100">Manage corrugator specifications for each product</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by product code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printSpecs" class="btn-info flex-1 md:flex-none">
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
                    <th @click="sortBy('product_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('min_sheet_length')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Sheet Length Min (mm) <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('max_sheet_length')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Sheet Length Max (mm) <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('min_sheet_width')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Sheet Width Min (mm) <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('max_sheet_width')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Sheet Width Max (mm) <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading specifications...</span>
                        </div>
                      </td>
                    </tr>
                  <tr v-else-if="paginatedProducts.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      No products found.
                    </td>
                  </tr>
                  <tr v-for="product in paginatedProducts" :key="product.id" 
                      @click="selectProduct(product)" 
                      :class="{'bg-blue-50': selectedProduct && selectedProduct.id === product.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ product.product_code }}</div>
                        <div class="text-sm text-gray-500">{{ product.product_name }}</div>
                      </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(product)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[product.id]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[product.id]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="product.compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!toggleLoading[product.id]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                          </div>
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" v-model.number="product.min_sheet_length" @blur="updateSheetDimensions(product, 'min_sheet_length')"
                             class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                             placeholder="Min Length">
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" v-model.number="product.max_sheet_length" @blur="updateSheetDimensions(product, 'max_sheet_length')"
                             class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                             placeholder="Max Length">
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" v-model.number="product.min_sheet_width" @blur="updateSheetDimensions(product, 'min_sheet_width')"
                             class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                             placeholder="Min Width">
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" v-model.number="product.max_sheet_width" @blur="updateSheetDimensions(product, 'max_sheet_width')"
                             class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                             placeholder="Max Width">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
             <div>
                <span>Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} results</span>
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
          <div v-if="selectedProduct" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Specification Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product Code:</span>
                <span class="font-medium text-gray-900">{{ selectedProduct.product_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product Name:</span>
                <span class="font-medium text-gray-900">{{ selectedProduct.product_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedProduct.compute, 'text-red-600': !selectedProduct.compute}">
                  {{ selectedProduct.compute ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Sheet Length (min/max):</span>
                <span class="font-medium text-gray-900 block">{{ selectedProduct.min_sheet_length || 'N/A' }} / {{ selectedProduct.max_sheet_length || 'N/A' }}</span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Sheet Width (min/max):</span>
                <span class="font-medium text-gray-900 block">{{ selectedProduct.min_sheet_width || 'N/A' }} / {{ selectedProduct.max_sheet_width || 'N/A' }}</span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <!-- Edit button removed as editing is now inline -->
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-cogs text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Product Selected</h3>
            <p class="text-gray-500 mb-4">Select a product to view its specification</p>
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
    const products = ref([]);
const selectedProduct = ref(null);
const loading = ref(false);
    const searchQuery = ref('');
const showFormModal = ref(false);
const sortOrder = ref({ field: 'product_code', direction: 'asc' });
const toast = useToast();
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
const toggleLoading = ref({});
const savingStatus = ref({});

const formSpec = ref({
  spec_id: null,
  product_code: '',
  product_name: '',
  compute: false,
  min_sheet_length: null,
  max_sheet_length: null,
  min_sheet_width: null,
  max_sheet_width: null,
});

// Computed Properties
const filteredProducts = computed(() => {
  let result = products.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(p =>
      (p.product_code && p.product_code.toLowerCase().includes(query)) ||
      (p.product_name && p.product_name.toLowerCase().includes(query))
    );
  }

  return [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    const valA = a[field] ?? (field.includes('length') || field.includes('width') ? -Infinity : '');
    const valB = b[field] ?? (field.includes('length') || field.includes('width') ? -Infinity : '');

    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;
    return 0;
  });
});

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage.value));

    const paginatedProducts = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredProducts.value.slice(start, end);
    });

// Watchers
watch(filteredProducts, () => { currentPage.value = 1; });
watch(itemsPerPage, () => { currentPage.value = 1; });

// Main Methods
    const loadProducts = async () => {
  loading.value = true;
  try {
    const [productsResponse, specsResponse] = await Promise.all([
      axios.get('/api/products'),
      axios.get('/api/corrugator-specs-by-product')
    ]);

    const specsMap = new Map(specsResponse.data.map(s => [s.product_code, s]));
    
    products.value = productsResponse.data.map(product => {
      const spec = specsMap.get(product.product_code);
          return {
            id: product.id,
            product_code: product.product_code,
            product_name: product.name || product.description,
            compute: spec ? !!spec.compute : false,
        spec_id: spec ? spec.id : null,
            min_sheet_length: spec ? spec.min_sheet_length : null,
            max_sheet_length: spec ? spec.max_sheet_length : null,
            min_sheet_width: spec ? spec.min_sheet_width : null,
            max_sheet_width: spec ? spec.max_sheet_width : null,
          };
        });
      } catch (error) {
    console.error('Error loading data:', error);
    toast.error('Failed to load specifications.');
      } finally {
        loading.value = false;
      }
    };

const refreshData = () => {
  selectedProduct.value = null;
  searchQuery.value = '';
  loadProducts();
};

const selectProduct = (product) => {
  selectedProduct.value = product;
};

// Helper function to save a specification
async function _saveSpec(specData) {
  const payload = {
    product_code: specData.product_code,
    compute: specData.compute,
    min_sheet_length: specData.min_sheet_length ?? 0,
    max_sheet_length: specData.max_sheet_length ?? 0,
    min_sheet_width: specData.min_sheet_width ?? 0,
    max_sheet_width: specData.max_sheet_width ?? 0,
  };

  for (const key of ['min_sheet_length', 'max_sheet_length', 'min_sheet_width', 'max_sheet_width']) {
    if (payload[key] === '') {
      payload[key] = null;
    }
  }

  return axios.post('/api/corrugator-specs-by-product/batch', [payload]);
}

// New method to update sheet dimensions
const updateSheetDimensions = async (product, field) => {
  const statusKey = `${product.id}-${field}`;
  if (savingStatus.value[statusKey]) return;

  savingStatus.value[statusKey] = true;
  
  try {
    const updatedProductData = { ...product };
    
    const response = await _saveSpec(updatedProductData);
    const updatedResult = response.data.results[0];

    const index = products.value.findIndex(p => p.id === product.id);
    if (index !== -1) {
      products.value[index].min_sheet_length = updatedResult.min_sheet_length;
      products.value[index].max_sheet_length = updatedResult.max_sheet_length;
      products.value[index].min_sheet_width = updatedResult.min_sheet_width;
      products.value[index].max_sheet_width = updatedResult.max_sheet_width;
      
      if (updatedResult.id && !products.value[index].spec_id) {
        products.value[index].spec_id = updatedResult.id;
      }

      if (selectedProduct.value && selectedProduct.value.id === product.id) {
        selectedProduct.value = { ...selectedProduct.value, ...products.value[index] };
      }
    }
    
    toast.success(`${product.product_code} ${field.replace(/_/g, ' ')} updated.`);
  } catch (error) {
    console.error(`Error updating ${field}:`, error);
    toast.error(error.response?.data?.message || `Failed to update ${field}.`);
    loadProducts();
      } finally {
    savingStatus.value[statusKey] = false;
  }
};

const toggleCompute = async (product) => {
  if (toggleLoading.value[product.id]) return;
  toggleLoading.value[product.id] = true;
  
  try {
    const updatedProductData = { ...product, compute: !product.compute };
    const response = await _saveSpec(updatedProductData);
    
    const index = products.value.findIndex(p => p.id === product.id);
    if (index !== -1) {
      products.value[index].compute = !product.compute;
      if (response.data.results.length > 0 && !products.value[index].spec_id) {
        products.value[index].spec_id = response.data.results[0].id;
      }
      if (selectedProduct.value && selectedProduct.value.id === product.id) {
        selectedProduct.value = products.value[index];
      }
    }
    
    toast.success(`Compute for ${product.product_code} updated.`);
  } catch (error) {
    console.error('Error toggling compute:', error);
    toast.error(error.response?.data?.message || 'Failed to update compute status.');
    const index = products.value.findIndex(f => f.id === product.id);
    if (index !== -1) {
      products.value[index].compute = product.compute;
    }
  } finally {
    toggleLoading.value[product.id] = false;
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

const printSpecs = () => {
  window.location.href = '/standard-formula/setup-corrugator-specification-by-product/view-print';
};

// Lifecycle
onMounted(loadProducts);

</script>

<style scoped>
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

tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
</style>
