<template>
  <AppLayout :header="'Amend SKU Type'">
    <Head title="Amend SKU Type" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-edit mr-3"></i> Amend SKU Type
      </h2>
      <p class="text-blue-100">Update existing SKU types in inventory</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar (Search Section) -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input 
                  type="text" 
                  v-model="searchSku" 
                  placeholder="Enter SKU code" 
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  @keyup.enter="searchSkuData"
                >
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="searchSkuData" class="btn-primary flex-1 md:flex-none" :disabled="loading">
                <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                <i v-else class="fas fa-search mr-2"></i> Search
              </button>
              <button @click="openSkuLookupModal" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-list mr-2"></i> Lookup
              </button>
              <button @click="clearSearch" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Reset
              </button>
            </div>
          </div>

          <!-- SKU Table & Additional Fields Section -->
          <div v-if="loading" class="py-8 text-center bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="flex justify-center items-center space-x-2 text-blue-500">
              <i class="fas fa-spinner fa-spin text-xl"></i>
              <span>Loading SKU data...</span>
            </div>
          </div>
          
          <div v-else-if="selectedSku" class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-md p-6">
            <div class="overflow-x-auto mb-6">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                  <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SKU#</th>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">STS</th>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SKU Name</th>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">CAT</th>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">UOM</th>
                    <th class="px-6 py-3.5 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">BOH</th>
                    <th class="px-6 py-3.5 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">FPO</th>
                    <th class="px-6 py-3.5 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">ROL</th>
                    <th class="px-6 py-3.5 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Total Part#</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr class="hover:bg-blue-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ selectedSku.sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <span class="px-2 py-1 rounded-full text-xs" 
                            :class="selectedSku.sts === 'ACT' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ selectedSku.sts }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.sku_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.category_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <select v-model="selectedSku.type" class="form-input">
                        <option value="">Select Type</option>
                        <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
                      </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <select v-model="selectedSku.uom" class="form-input">
                        <option value="">Select UOM</option>
                        <option v-for="unit in units" :key="unit.code" :value="unit.code">{{ unit.name }}</option>
                      </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ selectedSku.boh }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ selectedSku.fpo }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ selectedSku.rol }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ selectedSku.total_part }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Additional Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label for="minQty" class="block text-sm font-medium text-gray-700 mb-1">Min Qty:</label>
                <input 
                  type="number"
                  id="minQty"
                  v-model="selectedSku.min_qty"
                  step="0.01"
                  class="form-input"
                >
              </div>

              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label for="maxQty" class="block text-sm font-medium text-gray-700 mb-1">Max Qty:</label>
                <input 
                  type="number"
                  id="maxQty"
                  v-model="selectedSku.max_qty"
                  step="0.01"
                  class="form-input"
                >
              </div>

              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label for="catName" class="block text-sm font-medium text-gray-700 mb-1">CAT Name:</label>
                <select 
                  id="catName" 
                  v-model="selectedSku.category_code"
                  class="form-input"
                >
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.name }}
                  </option>
                </select>
              </div>
              
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label for="additionalName" class="block text-sm font-medium text-gray-700 mb-1">SKU Additional Name:</label>
                <input 
                  type="text"
                  id="additionalName"
                  v-model="selectedSku.additional_name"
                  class="form-input"
                >
              </div>

              <div class="col-span-1 md:col-span-2 bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Number:</label>
                <div class="space-y-2">
                  <div class="flex items-center">
                    <span class="text-xs mr-2 text-gray-600">1.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number1"
                      class="form-input"
                    >
                  </div>
                  <div class="flex items-center">
                    <span class="text-xs mr-2 text-gray-600">2.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number2"
                      class="form-input"
                    >
                  </div>
                  <div class="flex items-center">
                    <span class="text-xs mr-2 text-gray-600">3.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number3"
                      class="form-input"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="searchAttempted" class="py-8 text-center bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="text-gray-500">
              <i class="fas fa-search fa-3x mb-3"></i>
              <p class="text-lg">No SKU found with that code.</p>
              <p class="text-sm mt-2">Please check the SKU code and try again.</p>
            </div>
          </div>

          <div v-else class="py-8 text-center bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="text-gray-500">
              <i class="fas fa-search fa-3x mb-3"></i>
              <p class="text-lg">Enter an SKU code to begin.</p>
              <p class="text-sm mt-2">You can search by entering the SKU code in the search box.</p>
            </div>
          </div>
        </div>

        <!-- Side Panel -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedSku" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Selected SKU Details
            </h3>
            <div class="space-y-3">
              <div class="form-detail-item">
                <span class="form-detail-label">SKU#:</span>
                <span class="form-detail-value">{{ selectedSku.sku }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">Status:</span>
                <span class="form-detail-value">{{ selectedSku.sts || 'N/A' }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">SKU Name:</span>
                <span class="form-detail-value">{{ selectedSku.sku_name }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">Category:</span>
                <span class="form-detail-value">{{ selectedSku.category_code }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">Type:</span>
                <span class="form-detail-value">{{ selectedSku.type || 'N/A' }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">UOM:</span>
                <span class="form-detail-value">{{ selectedSku.uom || 'N/A' }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">BOH:</span>
                <span class="form-detail-value">{{ selectedSku.boh }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">FPO:</span>
                <span class="form-detail-value">{{ selectedSku.fpo }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">ROL:</span>
                <span class="form-detail-value">{{ selectedSku.rol }}</span>
              </div>
            </div>
            <div class="mt-6 flex flex-col space-y-2">
              <button @click="saveSkuChanges" class="btn-primary w-full" :disabled="loading">
                <i class="fas fa-save mr-2"></i> Save Changes
              </button>
              <!-- <button class="btn-secondary w-full">
                <i class="fas fa-list mr-2"></i> More Options
              </button> -->
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-full text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-edit text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Search for an SKU to view and amend its type.</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Bottom Action Buttons (for consistency, moved outside the main content area) -->
    <div class="bg-white rounded-lg shadow-md p-6 flex justify-end gap-4 mt-6">
      <!-- These buttons are kept for consistency if they have global actions or navigation -->
      <button @click="clearSearch" class="btn-secondary">
        <i class="fas fa-sync-alt mr-2"></i> Reset Form
      </button>
      <button @click="saveSkuChanges" :disabled="!selectedSku || loading" class="btn-primary">
        <i class="fas fa-save mr-2"></i> Save All Changes
      </button>
    </div>

    <!-- SKU Lookup Modal -->
    <SkuLookupModal
      :show="isSkuLookupModalOpen"
      @close="closeSkuLookupModal"
      @skuSelected="handleSkuSelected"
    />
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';
import SkuLookupModal from '@/Components/SkuLookupModal.vue';

// State variables
const selectedSku = ref(null);
const searchSku = ref('');
const loading = ref(false);
const searchAttempted = ref(false);
const categories = ref([]);
const units = ref([]);
const types = ref([]);
const originalSkuData = ref(null); // To track changes

// Initialize toast
const toast = useToast();

// Fetch SKU data by code
const searchSkuData = async () => {
  if (!searchSku.value) {
    toast.warning('Please enter an SKU code first');
    return;
  }
  
  loading.value = true;
  searchAttempted.value = true;
  
  try {
    const response = await axios.get(`/api/material-management/skus/${searchSku.value}`);
    selectedSku.value = response.data;
    originalSkuData.value = { ...response.data }; // Store original data
  } catch (error) {
    console.error('Error fetching SKU:', error);
    selectedSku.value = null;
    toast.error('SKU not found');
  } finally {
    loading.value = false;
  }
};

// Clear search and reset form
const clearSearch = () => {
  searchSku.value = '';
  selectedSku.value = null;
  searchAttempted.value = false;
  originalSkuData.value = null;
};

// Save changes to SKU
const saveSkuChanges = async () => {
  if (!selectedSku.value) return;
  
  loading.value = true;
  
  try {
    // Ensure 'type' and 'uom' are sent as their respective codes, not objects/full names if they were derived
    const payload = {
      ...selectedSku.value,
      // If 'type' or 'uom' might have been enriched, send back the code expected by backend
      // Assuming selectedSku.value.type and selectedSku.value.uom already hold the codes
    };

    const response = await axios.put(`/api/material-management/skus/${selectedSku.value.sku}`, payload);
    toast.success('SKU type updated successfully');
    selectedSku.value = response.data;
    originalSkuData.value = { ...response.data }; // Update original data
  } catch (error) {
    console.error('Error updating SKU:', error);
    toast.error('Failed to update SKU');
    // Optionally revert changes if update fails
    if (originalSkuData.value) {
      selectedSku.value = { ...originalSkuData.value };
    }
  } finally {
    loading.value = false;
  }
};

// Fetch reference data
const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/material-management/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    toast.error('Failed to load categories');
  }
};

const fetchUnits = async () => {
  try {
    const response = await axios.get('/api/material-management/units');
    units.value = response.data;
  } catch (error) {
    console.error('Error fetching units:', error);
    toast.error('Failed to load units');
  }
};

const fetchTypes = async () => {
  try {
    const response = await axios.get('/api/material-management/types');
    types.value = response.data; // Assuming this returns an array of simple strings like ["S", "NS"]
  } catch (error) {
    console.error('Error fetching types:', error);
    toast.error('Failed to load types');
  }
};

// Modal state
const isSkuLookupModalOpen = ref(false);

const openSkuLookupModal = () => {
  isSkuLookupModalOpen.value = true;
};

const closeSkuLookupModal = () => {
  isSkuLookupModalOpen.value = false;
};

const handleSkuSelected = (sku) => {
  // When an SKU is selected from the lookup, set it as the current searchSku and fetch its data
  searchSku.value = sku.sku; 
  closeSkuLookupModal();
  searchSkuData();
};

onMounted(async () => {
  // Fetch reference data on component mount
  await Promise.all([
    fetchCategories(),
    fetchUnits(),
    fetchTypes()
  ]);
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

/* Form field styles based on Location.vue modal forms */
.form-field {
  @apply bg-gray-50 p-4 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-2 flex items-center;
}

.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}

/* Detail display items - new styles for consistency */
.form-detail-item {
  @apply bg-gray-100 p-3 rounded-lg border border-gray-200;
}

.form-detail-label {
  @apply block text-xs font-medium text-gray-500 mb-1;
}

.form-detail-value {
  @apply block text-sm font-semibold text-gray-800;
}

/* General Layout/Scrollbar from Location.vue */
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
</style>
