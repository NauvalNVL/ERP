<template>
  <AppLayout title="Define Bundling Computation Method">
    <template #header>
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
          <i class="fas fa-calculator mr-3"></i> Define Bundling Computation Method
        </h2>
        <p class="text-blue-100">Manage computation methods for bundling processes</p>
      </div>
    </template>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
          <!-- Main Content -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchTerm" placeholder="Search by product design, product or flute..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newMethod" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Method
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="seedData" class="btn-info flex-1 md:flex-none" :disabled="loading">
                <i class="fas fa-database mr-2"></i> Seed Data
              </button>
            </div>
          </div>
            
            <!-- Loading Overlay -->
            <div v-if="loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Processing...</span>
              </div>
            </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                    <th @click="sortBy('product_design')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product Design <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('product')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('flute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Flute <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('formula_pieces')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Pieces Per Bundle <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('is_compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-if="isLoading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading computation methods...</span>
                          </div>
                        </td>
                      </tr>
                  <tr v-else-if="paginatedMethods.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No computation methods found. Try adjusting your search or create a new method.
                    </td>
                      </tr>
                  <tr v-for="method in paginatedMethods" :key="method.id" 
                      @click="selectMethod(method)" 
                      :class="{'bg-blue-50': selectedMethod && selectedMethod.id === method.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ method.product_design || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ method.product || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ method.flute || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ method.formula_pieces }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(method)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[method.id]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[method.id]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="method.is_compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                          </span>
                        <i v-if="!toggleLoading[method.id]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                      </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedMethods.length }} of {{ filteredMethods.length }} methods</span>
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
          <div v-if="selectedMethod" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Method Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product Design:</span>
                <span class="font-medium text-gray-900">{{ selectedMethod.product_design || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">P/Design Name:</span>
                <span class="font-medium text-gray-900">{{ getProductDesignName(selectedMethod.product_design) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium text-gray-900">{{ selectedMethod.product || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product Name:</span>
                <span class="font-medium text-gray-900">{{ getProductName(selectedMethod.product) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute:</span>
                <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ selectedMethod.flute || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Pieces Per Bundle:</span>
                <span class="font-medium text-gray-900">{{ selectedMethod.formula_pieces }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Formula Divisor:</span>
                <span class="font-medium text-gray-900">{{ selectedMethod.formula_divisor || 1 }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Height Type:</span>
                <span class="font-medium text-gray-900 capitalize">{{ selectedMethod.height_type || 'Internal' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Rounding Type:</span>
                <span class="font-medium text-gray-900 capitalize">{{ selectedMethod.rounding_type || 'Down' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedMethod.is_compute, 'text-red-600': !selectedMethod.is_compute}">
                  {{ selectedMethod.is_compute ? 'Yes' : 'No' }}
                </span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editMethod(selectedMethod)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedMethod)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-calculator text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Method Selected</h3>
            <p class="text-gray-500 mb-4">Select a method from the table to view details</p>
            <button @click="newMethod" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Method
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Method Form Modal -->
    <ComputationMethodModal
      :show="showFormModal"
      :method="selectedMethod"
      :product-designs="productDesigns"
      :products="products"
      :flutes="flutes"
      :loading="loading"
      :errors="errors"
      @close="showFormModal = false"
      @save="saveMethod"
    />

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Computation Method</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this computation method? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteMethod" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Delete
            </button>
            <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Messages -->
    <div v-if="toast.show" class="fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50"
      :class="{
        'bg-green-500': toast.type === 'success',
        'bg-red-500': toast.type === 'error',
        'bg-blue-500': toast.type === 'info',
      }"
    >
      <div class="flex items-center text-white">
        <span class="mr-2">
          <i :class="{
            'fas fa-check-circle': toast.type === 'success',
            'fas fa-exclamation-circle': toast.type === 'error',
            'fas fa-info-circle': toast.type === 'info',
          }"></i>
        </span>
        <span>{{ toast.message }}</span>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';
import ComputationMethodModal from '@/Components/ComputationMethodModal.vue';

// State
const computationMethods = ref([]);
const productDesigns = ref([]);
const products = ref([]);
const flutes = ref([]);
const selectedMethod = ref(null);
const editingId = ref(null);
const loading = ref(false);
const isLoading = ref(true);
const showDeleteModal = ref(false);
const showFormModal = ref(false);
const methodToDelete = ref(null);
const searchTerm = ref('');
const errors = ref({});
const toggleLoading = ref({});
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
  field: 'product_design',
  direction: 'asc'
});

// Use toast composable
const toast = useToast();

// Form data
const form = reactive({
  product_name: '',
  product_design: '',
  product: '',
  flute: '',
  formula_divisor: 1,
  formula_pieces: 0,
  height_type: 'internal',
  rounding_type: 'down',
  is_compute: false
});

watch(() => form.product, (newProductCode) => {
  if (newProductCode) {
    const selectedProduct = products.value.find(p => p.product_code === newProductCode);
    if (selectedProduct) {
      form.product_name = selectedProduct.description;
    } else {
      form.product_name = '';
    }
  } else {
    form.product_name = '';
  }
});

// Filtered methods based on search term and sorting
const filteredMethods = computed(() => {
  let result = computationMethods.value;
  
  // Apply search filter
  if (searchTerm.value) {
  const term = searchTerm.value.toLowerCase();
    result = result.filter(method => 
    (method.product_design && method.product_design.toLowerCase().includes(term)) ||
    (method.product && method.product.toLowerCase().includes(term)) ||
    (method.flute && method.flute.toLowerCase().includes(term)) ||
    (method.product_name && method.product_name.toLowerCase().includes(term))
  );
  }
  
  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });
  
  return result;
});

// Pagination computed properties
const paginatedMethods = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredMethods.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.max(1, Math.ceil(filteredMethods.value.length / itemsPerPage.value));
});

// Watch for changes in filtered methods to update pagination
watch(filteredMethods, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

const getProductName = (productCode) => {
    if (!productCode) return '-';
    const product = products.value.find(p => p.product_code === productCode);
    return product ? product.description : (selectedMethod.value?.product_name || 'N/A');
};

const getProductDesignName = (pdCode) => {
    if (!pdCode) return '-';
    const design = productDesigns.value.find(d => d.pd_code === pdCode);
    return design ? design.pd_name : 'N/A';
};

// Fetch computation methods on component mount
onMounted(async () => {
  await Promise.all([
    fetchComputationMethods(),
    fetchProductDesigns(),
    fetchProducts(),
    fetchFlutes()
  ]);
});

// Fetch computation methods from API
const fetchComputationMethods = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/bundling-computation-methods');
    if (response.data.status === 'success') {
      computationMethods.value = response.data.data;
    } else {
      toast.error('Failed to load computation methods');
    }
  } catch (error) {
    console.error('Error fetching computation methods:', error);
    toast.error('Error loading computation methods');
  } finally {
    isLoading.value = false;
  }
};

// Fetch product designs
const fetchProductDesigns = async () => {
  try {
    const response = await axios.get('/api/product-designs');
    productDesigns.value = response.data;
  } catch (error) {
    console.error('Error fetching product designs:', error);
    toast.error('Error loading product designs');
  }
};

// Fetch products
const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
    toast.error('Error loading products');
  }
};

// Fetch flutes
const fetchFlutes = async () => {
  try {
    const response = await axios.get('/api/paper-flutes');
    flutes.value = response.data;
  } catch (error) {
    console.error('Error fetching flutes:', error);
    toast.error('Error loading flutes');
  }
};

// Validate form
const validateForm = () => {
  errors.value = {};
  let isValid = true;
  
  if (!form.formula_pieces && form.formula_pieces !== 0) {
    errors.value.formula_pieces = 'Pieces per bundle is required';
    isValid = false;
  }
  
  return isValid;
};

// Function to refresh data
const refreshData = () => {
  selectedMethod.value = null;
  fetchComputationMethods();
};

// Function to select a method
const selectMethod = (method) => {
  selectedMethod.value = method;
};

// Function to open the new method form modal
const newMethod = () => {
  resetForm();
  selectedMethod.value = null; // Ensure we are in "new" mode
  showFormModal.value = true;
};

// Function to sort table by a given field
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    // Toggle direction if same field
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    // Change field and reset direction
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Reset form to default values
const resetForm = () => {
  Object.assign(form, {
    product_name: '',
    product_design: '',
    product: '',
    flute: '',
    formula_divisor: 1,
    formula_pieces: 0,
    height_type: 'internal',
    rounding_type: 'down',
    is_compute: false
  });
  editingId.value = null;
  selectedMethod.value = null;
  errors.value = {};
};

// Function to toggle compute status directly from the table
const toggleCompute = async (method) => {
  // Prevent multiple clicks
  if (toggleLoading.value[method.id]) return;
  
  // Set loading state for this specific method
  toggleLoading.value[method.id] = true;
  const newComputeValue = !method.is_compute;
  
  try {
    // Create a copy of the method object with only the required fields for the update
    const methodUpdate = {
      ...method,
      is_compute: newComputeValue
    };
    
    const response = await axios.put(`/api/bundling-computation-methods/${method.id}`, methodUpdate);
    
    if (response.data.status === 'success') {
      // Update the method in the local array
      const index = computationMethods.value.findIndex(m => m.id === method.id);
      if (index !== -1) {
        computationMethods.value[index].is_compute = newComputeValue;
        
        // Also update selected method if it's the same one
        if (selectedMethod.value?.id === method.id) {
          selectedMethod.value.is_compute = newComputeValue;
        }
      }
      
      toast.success(`Compute value updated to ${newComputeValue ? 'Yes' : 'No'}`);
    } else {
      throw new Error(response.data.message || 'Unknown error');
    }
  } catch (error) {
    console.error('Error updating compute status:', error);
    toast.error(error.response?.data?.message || 'Failed to update compute status');
  } finally {
    // Clear loading state
    toggleLoading.value[method.id] = false;
  }
};

// Edit a computation method
const editMethod = (method) => {
  editingId.value = method.id;
  selectedMethod.value = method;
  
  Object.assign(form, {
    product_name: method.product_name || '',
    product_design: method.product_design || '',
    product: method.product || '',
    flute: method.flute || '',
    formula_divisor: method.formula_divisor || 1,
    formula_pieces: method.formula_pieces || 0,
    height_type: method.height_type || 'internal',
    rounding_type: method.rounding_type || 'down',
    is_compute: method.is_compute || false
  });
  
  errors.value = {};
  showFormModal.value = true;
};

// Save or update a computation method
const saveMethod = async (formData) => {
  // Directly use formData passed from the modal
  const formToSave = formData;

  // Manual validation can still happen here if needed, 
  // or can be moved entirely to the modal.
  // For now, let's assume basic validation is enough.
  errors.value = {};
  let isValid = true;
  if (!formToSave.formula_pieces && formToSave.formula_pieces !== 0) {
    errors.value.formula_pieces = 'Pieces per bundle is required';
    isValid = false;
  }
  
  if (!isValid) {
    toast.error('Please correct the errors in the form');
    return;
  }
  
  try {
    loading.value = true;
    
    // The data from the modal is already in the correct format
    const methodData = formToSave;
    
    if (editingId.value) {
      // Update existing method
      const response = await axios.put(`/api/bundling-computation-methods/${editingId.value}`, methodData);
      if (response.data.status === 'success') {
        toast.success('Computation method updated successfully');
      } else {
        toast.error('Failed to update computation method');
      }
    } else {
      // Create new method
      const response = await axios.post('/api/bundling-computation-methods', methodData);
      if (response.data.status === 'success') {
        toast.success('Computation method created successfully');
      } else {
        toast.error('Failed to create computation method');
      }
    }
    
    // Refresh the data
    await fetchComputationMethods();
    
    // Reset the form and close modal
    showFormModal.value = false;
    resetForm();

  } catch (error) {
    console.error('Error saving computation method:', error);
    
    // Handle validation errors
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
      toast.error('Please correct the errors in the form');
    } else {
      toast.error('An error occurred while saving');
    }
  } finally {
    loading.value = false;
  }
};

// Confirm deletion of a computation method
const confirmDelete = (method) => {
  methodToDelete.value = method;
  selectedMethod.value = method; // Pass selected method to confirm delete
  showDeleteModal.value = true;
};

// Delete a computation method
const deleteMethod = async () => {
  if (!methodToDelete.value) return;
  
  try {
    loading.value = true;
    const response = await axios.delete(`/api/bundling-computation-methods/${methodToDelete.value.id}`);
    
    if (response.data.status === 'success') {
      toast.success('Computation method deleted successfully');
    } else {
      toast.error('Failed to delete computation method');
    }
    
    // Refresh the data
    await fetchComputationMethods();
    
    // Clear the selected method if it was the one deleted
    if (selectedMethod.value && selectedMethod.value.id === methodToDelete.value.id) {
      selectedMethod.value = null;
    }
    
    // Close the modal
    showDeleteModal.value = false;
    methodToDelete.value = null;
  } catch (error) {
    console.error('Error deleting computation method:', error);
    toast.error('Error deleting computation method');
  } finally {
    loading.value = false;
  }
};

// Seed sample data
const seedData = async () => {
  if (!confirm('Are you sure you want to seed sample data? This will add example computation methods to the database.')) {
    return;
  }
  
  try {
    loading.value = true;
    const response = await axios.post('/api/bundling-computation-methods/seed');
    
    if (response.data.success) {
      toast.success('Sample data seeded successfully');
      await fetchComputationMethods();
    } else {
      toast.error('Failed to seed sample data');
    }
  } catch (error) {
    console.error('Error seeding data:', error);
    toast.error('Error seeding sample data');
  } finally {
    loading.value = false;
  }
};
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
