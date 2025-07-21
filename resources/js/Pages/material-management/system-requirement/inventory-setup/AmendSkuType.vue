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
      <!-- Search Section -->
      <div class="mb-6 border border-gray-200 rounded-lg p-6 bg-gray-50">
        <div class="flex flex-col md:flex-row items-center gap-4">
          <div class="w-full md:w-auto">
            <label for="skuSearch" class="block text-sm font-medium text-gray-700 mb-1">SKU#:</label>
            <div class="flex">
              <input 
                type="text" 
                id="skuSearch" 
                v-model="searchSku" 
                placeholder="Enter SKU code" 
                class="rounded-l-lg border-r-0 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm px-4 py-2"
              >
              <button @click="openSkuLookupModal" class="bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          
          <div class="flex space-x-4 mt-4 md:mt-0">
            <button @click="clearSearch" class="btn-secondary">
              <i class="fas fa-sync-alt mr-2"></i> Reset
            </button>
            <button @click="saveSkuChanges" :disabled="!selectedSku" class="btn-primary" :class="{'opacity-50': !selectedSku}">
              <i class="fas fa-save mr-2"></i> Save Changes
            </button>
          </div>
        </div>
      </div>

      <!-- SKU Table -->
      <div v-if="loading" class="py-8 text-center">
        <div class="flex justify-center items-center space-x-2">
          <i class="fas fa-spinner fa-spin text-blue-500 text-xl"></i>
          <span>Loading SKU data...</span>
        </div>
      </div>
      
      <div v-else-if="selectedSku" class="mb-6">
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-md">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                <tr>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SKU#</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">STS</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SKU Name</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">CAT</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">UOM</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">BOH</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">FPO</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ROL</th>
                  <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Total Part#</th>
                </tr>
              </thead>
              <tbody>
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
                    <select v-model="selectedSku.type" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                      <option value="">Select Type</option>
                      <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
                    </select>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <select v-model="selectedSku.uom" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                      <option value="">Select UOM</option>
                      <option v-for="unit in units" :key="unit.code" :value="unit.code">{{ unit.name }}</option>
                    </select>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.boh }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.fpo }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.rol }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ selectedSku.total_part }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Additional Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <!-- Left Column -->
          <div>
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
              <div class="mb-4">
                <label for="minQty" class="block text-sm font-medium text-gray-700 mb-1">Min Qty:</label>
                <input 
                  type="number"
                  id="minQty"
                  v-model="selectedSku.min_qty"
                  step="0.01"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >
              </div>

              <div class="mb-4">
                <label for="maxQty" class="block text-sm font-medium text-gray-700 mb-1">Max Qty:</label>
                <input 
                  type="number"
                  id="maxQty"
                  v-model="selectedSku.max_qty"
                  step="0.01"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >
              </div>

              <div>
                <label for="catName" class="block text-sm font-medium text-gray-700 mb-1">CAT Name:</label>
                <select 
                  id="catName" 
                  v-model="selectedSku.category_code"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.name }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div>
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
              <div class="mb-4">
                <label for="additionalName" class="block text-sm font-medium text-gray-700 mb-1">SKU Additional Name:</label>
                <input 
                  type="text"
                  id="additionalName"
                  v-model="selectedSku.additional_name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Number:</label>
                <div class="space-y-2">
                  <div class="flex items-center">
                    <span class="text-xs mr-2">1.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number1"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                  </div>
                  <div class="flex items-center">
                    <span class="text-xs mr-2">2.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number2"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                  </div>
                  <div class="flex items-center">
                    <span class="text-xs mr-2">3.</span>
                    <input 
                      type="text"
                      v-model="selectedSku.part_number3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="searchAttempted" class="py-8 text-center border rounded-lg bg-gray-50">
        <div class="text-gray-500">
          <i class="fas fa-search fa-3x mb-3"></i>
          <p class="text-lg">No SKU found with that code.</p>
          <p class="text-sm mt-2">Please check the SKU code and try again.</p>
        </div>
      </div>

      <div v-else class="py-8 text-center border rounded-lg bg-gray-50">
        <div class="text-gray-500">
          <i class="fas fa-search fa-3x mb-3"></i>
          <p class="text-lg">Enter an SKU code to begin.</p>
          <p class="text-sm mt-2">You can search by entering the SKU code in the search box.</p>
        </div>
      </div>

      <!-- Bottom Action Buttons -->
      <div class="flex flex-wrap justify-between gap-3 mt-6">
        <div class="flex flex-wrap gap-3">
          <button class="btn-secondary">
            <i class="fas fa-list mr-2"></i>More Options
          </button>
          <button class="btn-secondary">
            <i class="fas fa-box mr-2"></i>SKU Part#
          </button>
        </div>
        <div class="flex flex-wrap gap-3">
          <button class="btn-secondary">
            <i class="fas fa-balance-scale mr-2"></i>SKU Balance
          </button>
          <button class="btn-secondary">
            <i class="fas fa-exchange-alt mr-2"></i>Alt Unit
          </button>
          <button class="btn-secondary">
            <i class="fas fa-sticky-note mr-2"></i>Add Note
          </button>
          <button class="btn-secondary">
            <i class="fas fa-history mr-2"></i>Process Log
          </button>
          <button class="btn-secondary">
            <i class="fas fa-check mr-2"></i>Select
          </button>
          <button class="btn-secondary">
            <i class="fas fa-sign-out-alt mr-2"></i>Exit
          </button>
        </div>
      </div>
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
    const response = await axios.put(`/api/material-management/skus/${selectedSku.value.sku}`, selectedSku.value);
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
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-types');
    types.value = response.data;
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
  selectedSku.value = sku;
  searchSku.value = sku.sku; // Set searchSku to the selected SKU code
  searchAttempted.value = true; // Mark search as attempted
  closeSkuLookupModal();
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
</style>
