<template>
  <AppLayout :header="'Product Design'">
    <Head title="Define Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-drafting-compass mr-3"></i> Define Product Design
      </h2>
      <p class="text-blue-100">Manage product designs for your manufacturing process</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by design code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newDesign" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Design
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printDesigns" class="btn-info flex-1 md:flex-none">
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
                    <th @click="sortBy('pd_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product Design <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('pd_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product Design Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading product designs...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedDesigns.length === 0" class="hover:bg-gray-50">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      No product designs found. Try adjusting your search or create a new design.
                    </td>
                  </tr>
                  <tr v-for="design in paginatedDesigns" :key="design.pd_code" 
                      @click="selectDesign(design)" 
                      :class="{'bg-blue-50': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ design.pd_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.pd_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(design)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[design.pd_code]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[design.pd_code]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="design.compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!toggleLoading[design.pd_code]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
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
              <span>Showing {{ paginatedDesigns.length }} of {{ filteredDesigns.length }} designs</span>
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
          <div v-if="selectedDesign" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Design Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Code:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Name:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Type:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_design_type }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">IDC:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.idc }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ selectedDesign.product }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint === 'Yes', 'text-red-600': selectedDesign.joint !== 'Yes'}">
                  {{ selectedDesign.joint }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint to Print:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint_to_print === 'Yes', 'text-red-600': selectedDesign.joint_to_print !== 'Yes'}">
                  {{ selectedDesign.joint_to_print }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PCS to Joint:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pcs_to_joint }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Score:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.score === 'Yes', 'text-red-600': selectedDesign.score !== 'Yes'}">
                  {{ selectedDesign.score }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Slot:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.slot === 'Yes', 'text-red-600': selectedDesign.slot !== 'Yes'}">
                  {{ selectedDesign.slot }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Style:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.flute_style }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Print Flute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.print_flute === 'Yes', 'text-red-600': selectedDesign.print_flute !== 'Yes'}">
                  {{ selectedDesign.print_flute }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Input Weight:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.input_weight === 'Yes', 'text-red-600': selectedDesign.input_weight !== 'Yes'}">
                  {{ selectedDesign.input_weight }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.compute, 'text-red-600': !selectedDesign.compute}">
                  {{ selectedDesign.compute ? 'Yes' : 'No' }}
                </span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editDesign(selectedDesign)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedDesign)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-drafting-compass text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Design Selected</h3>
            <p class="text-gray-500 mb-4">Select a design from the table to view details</p>
            <button @click="newDesign" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Design
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Design Modal -->
    <ProductDesignModal
      :show="showModal"
      :designs="designs"
      :products="products"
      :is-creating="isCreating"
      :design-to-edit="designToEdit"
      @data-changed="refreshData"
      @close="showModal = false"
    />

    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showConfirmation = false"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete the product design <span class="font-semibold">{{ designToDelete?.pd_code }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteDesign" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductDesignModal from '@/Components/product-design-modal.vue';
import { useToast } from '@/Composables/useToast';

// State variables
const designs = ref([]);
const products = ref([]);
const selectedDesign = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const showModal = ref(false);
const showConfirmation = ref(false);
const designToDelete = ref(null);
const designToEdit = ref(null);
const isCreating = ref(false);

const sortOrder = ref({
  field: 'pd_code',
  direction: 'asc'
});
const toast = useToast();
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Computed properties
const filteredDesigns = computed(() => {
  let result = designs.value;

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(design => (
      (design.pd_code && design.pd_code.toLowerCase().includes(query)) ||
      (design.pd_name && design.pd_name.toLowerCase().includes(query))
    ));
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
const paginatedDesigns = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDesigns.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredDesigns.value.length / itemsPerPage.value);
});

// Watch for changes in filtered designs to update pagination
watch(filteredDesigns, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Function to fetch designs from API
const fetchDesigns = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/product-designs');
    
    // Ensure compute field is a boolean
    designs.value = response.data.map(design => ({
      ...design,
      compute: !!design.compute
    }));
    
    console.log('Loaded designs:', designs.value);
  } catch (error) {
    console.error('Error fetching designs:', error);
    toast.error('Failed to load product designs');
  } finally {
    loading.value = false;
  }
};

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
    toast.error('Failed to load products.');
  }
};

// Function to refresh data
const refreshData = () => {
  selectedDesign.value = null;
  fetchDesigns();
};

// Function to select a design
const selectDesign = (design) => {
  selectedDesign.value = design;
};

// Function to open the new design form modal
const newDesign = () => {
  isCreating.value = true;
  designToEdit.value = null;
  showModal.value = true;
};

// Function to open the edit design form modal
const editDesign = (design) => {
  isCreating.value = false;
  designToEdit.value = { ...design };
  showModal.value = true;
};

// Function to confirm deletion of a design
const confirmDelete = (design) => {
  designToDelete.value = design;
  showConfirmation.value = true;
};

// Function to delete a design
const deleteDesign = async () => {
  if (!designToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/product-designs/${designToDelete.value.pd_code}`);
    
    toast.success('Design deleted successfully');
    
    // Remove design from the local array
    designs.value = designs.value.filter(d => d.pd_code !== designToDelete.value.pd_code);
    
    // Clear selection if the deleted design was selected
    if (selectedDesign.value?.pd_code === designToDelete.value.pd_code) {
      selectedDesign.value = null;
    }
    
    showConfirmation.value = false;
    designToDelete.value = null;
  } catch (error) {
    console.error('Error deleting design:', error);
    toast.error('Failed to delete product design');
  } finally {
    loading.value = false;
  }
};

// State for tracking which design is being toggled
const toggleLoading = ref({});

// Function to toggle compute status directly from the table
const toggleCompute = async (design) => {
  // Prevent multiple clicks
  if (toggleLoading.value[design.pd_code]) return;
  
  // Set loading state for this specific design
  toggleLoading.value[design.pd_code] = true;
  const newComputeValue = !design.compute;
  
  try {
    // Create a copy of the design object with only the required fields for the update
    const designUpdate = {
      ...design,
      compute: newComputeValue
    };
    
    const response = await axios.put(`/api/product-designs/${design.pd_code}`, designUpdate);
    
    if (response.data.success) {
      // Update the design in the local array
      const updatedDesignFromServer = response.data.data;
      const index = designs.value.findIndex(d => d.pd_code === design.pd_code);
      if (index !== -1) {
        designs.value[index].compute = !!updatedDesignFromServer.compute;
        
        // Also update selected design if it's the same one
        if (selectedDesign.value?.pd_code === design.pd_code) {
          selectedDesign.value.compute = !!updatedDesignFromServer.compute;
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
    toggleLoading.value[design.pd_code] = false;
  }
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

// Function to print designs
const printDesigns = () => {
  window.location.href = '/product-design/view-print';
};

// Load data when component is mounted
onMounted(() => {
  fetchDesigns();
  fetchProducts();
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
