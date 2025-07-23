<template>
  <AppLayout :header="'Define Control Period'">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
          <!-- Header -->
          <div class="bg-blue-600 px-4 py-4 sm:px-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Define Control Period</h2>
            <div class="flex space-x-2">
              <button @click="saveSettings" class="bg-white hover:bg-gray-100 text-blue-700 font-semibold py-2 px-4 border border-blue-500 rounded shadow-sm flex items-center">
                <i class="fas fa-save mr-2"></i>
                Save
              </button>
              <button @click="resetForm" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded shadow-sm flex items-center">
                <i class="fas fa-undo mr-2"></i>
                Reset
              </button>
            </div>
          </div>

          <!-- Content with Loading State -->
          <div v-if="loading" class="p-8 flex justify-center items-center">
            <div class="flex flex-col items-center">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              <p class="mt-3 text-gray-600">Loading configuration...</p>
            </div>
          </div>

          <div v-else class="divide-y divide-gray-200">
            <!-- P/Requisition Section -->
            <div class="px-4 py-5 sm:p-6 bg-gray-50">
              <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">P/Requisition</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Current Period:</label>
                  <input 
                    type="text" 
                    v-model="formData.prRequisition.currentPeriod" 
                    class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md"
                    placeholder="Same as P/Order Period"
                    readonly
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Forward Period:</label>
                  <select v-model="formData.prRequisition.forwardPeriod" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="0">0-Not Allowed</option>
                    <option value="1">1-1 Month</option>
                    <option value="2">2-2 Months</option>
                    <option value="3">3-3 Months</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Control Date:</label>
                  <select v-model="formData.prRequisition.controlDate" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="1">U-Date Under Current Period</option>
                    <option value="2">F-Date Under/Forward Current Period</option>
                    <option value="3">B-Date Under/Backward Current Period</option>
                    <option value="4">O-Open Date</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- P/Order Section -->
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">P/Order</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Current Period:</label>
                  <div class="flex">
                    <input 
                      type="number" 
                      v-model="formData.pOrder.currentPeriodMonth" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/4 shadow-sm sm:text-sm rounded-md rounded-r-none"
                      min="1"
                      max="12"
                    >
                    <input 
                      type="number" 
                      v-model="formData.pOrder.currentPeriodYear" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/3 shadow-sm sm:text-sm rounded-md rounded-l-none"
                      min="2020"
                      max="2100"
                    >
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Forward Period:</label>
                  <select v-model="formData.pOrder.forwardPeriod" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="0">0-Not Allowed</option>
                    <option value="1">1-1 Month</option>
                    <option value="2">2-2 Months</option>
                    <option value="3">3-3 Months</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Control Date:</label>
                  <select v-model="formData.pOrder.controlDate" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="1">U-Date Under Current Period</option>
                    <option value="2">F-Date Under/Forward Current Period</option>
                    <option value="3">B-Date Under/Backward Current Period</option>
                    <option value="4">O-Open Date</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Min Allow %:</label>
                  <div class="flex items-center">
                    <input 
                      type="number" 
                      v-model="formData.pOrder.minAllowPercentage" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/4 shadow-sm sm:text-sm rounded-md"
                      min="0"
                      max="100"
                      step="0.01"
                    >
                    <span class="ml-2">% of Order Qty</span>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Max Allow %:</label>
                  <div class="flex items-center">
                    <input 
                      type="number" 
                      v-model="formData.pOrder.maxAllowPercentage" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/4 shadow-sm sm:text-sm rounded-md"
                      min="0"
                      max="100"
                      step="0.01"
                    >
                    <span class="ml-2">% of Order Qty</span>
                  </div>
                </div>

                <div class="col-span-2">
                  <h4 class="font-medium text-gray-700 mb-2 bg-yellow-100 px-2 py-1">Period-End Closing</h4>
                  <div class="flex items-center space-x-6 mt-2">
                    <label class="text-sm font-medium text-gray-700">Zero PO:</label>
                    <div class="flex items-center space-x-4">
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="formData.pOrder.zeroPO" value="Y" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2">Y-Allow</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="formData.pOrder.zeroPO" value="N" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2">N-Not Allow</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Inventory Section -->
            <div class="px-4 py-5 sm:p-6 bg-gray-50">
              <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Inventory</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Current Period:</label>
                  <div class="flex">
                    <input 
                      type="number" 
                      v-model="formData.inventory.currentPeriodMonth" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/4 shadow-sm sm:text-sm rounded-md rounded-r-none"
                      min="1"
                      max="12"
                    >
                    <input 
                      type="number" 
                      v-model="formData.inventory.currentPeriodYear" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/3 shadow-sm sm:text-sm rounded-md rounded-l-none"
                      min="2020"
                      max="2100"
                    >
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Backward Period:</label>
                  <select v-model="formData.inventory.backwardPeriod" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="0">0-Not Allowed</option>
                    <option value="1">1-1 Month</option>
                    <option value="2">2-2 Months</option>
                    <option value="3">3-3 Months</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Control Date:</label>
                  <select v-model="formData.inventory.controlDate" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="1">U-Date Under Current Period</option>
                    <option value="2">F-Date Under/Forward Current Period</option>
                    <option value="3">B-Date Under/Backward Current Period</option>
                    <option value="4">O-Open Date</option>
                  </select>
                </div>

                <div class="col-span-2">
                  <h4 class="font-medium text-gray-700 mb-2 bg-yellow-100 px-2 py-1">Period-End Closing</h4>
                  <div class="flex items-center space-x-6 mt-2">
                    <label class="text-sm font-medium text-gray-700">Zero Tran:</label>
                    <div class="flex items-center space-x-4">
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="formData.inventory.zeroTran" value="Y" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2">Y-Allow</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="formData.inventory.zeroTran" value="N" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2">N-Not Allow</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Costing Section -->
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Costing</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Current Period:</label>
                  <div class="flex">
                    <input 
                      type="number" 
                      v-model="formData.costing.currentPeriodMonth" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/4 shadow-sm sm:text-sm rounded-md rounded-r-none"
                      min="1"
                      max="12"
                    >
                    <input 
                      type="number" 
                      v-model="formData.costing.currentPeriodYear" 
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-1/3 shadow-sm sm:text-sm rounded-md rounded-l-none"
                      min="2020"
                      max="2100"
                    >
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Control Date:</label>
                  <select v-model="formData.costing.controlDate" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md">
                    <option value="1">U-Date Under Current Period</option>
                    <option value="2">F-Date Under/Forward Current Period</option>
                    <option value="3">B-Date Under/Backward Current Period</option>
                    <option value="4">O-Open Date</option>
                  </select>
                </div>

                <div class="col-span-2">
                  <h4 class="font-medium text-gray-700 mb-2 bg-yellow-100 px-2 py-1">Period-End Closing</h4>
                  <div class="flex items-center space-x-6 mt-2">
                    <label class="inline-flex items-center">
                      <input type="checkbox" v-model="formData.costing.yAllowAfterPeriod" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                      <span class="ml-2">Y-Allow after Inventory Period more than 1 month</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Error Messages -->
          <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">
                  {{ errorMessage }}
                </p>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-700">
                  {{ successMessage }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios'; // Added axios import

const toast = useToast();
const loading = ref(true);
const errorMessage = ref('');
const successMessage = ref('');

// Form data structure
const initialFormData = ref(null);

const formData = ref({
  prRequisition: {
    currentPeriod: 'Same as P/Order Period',
    forwardPeriod: '0',
    controlDate: '1',
  },
  pOrder: {
    currentPeriodMonth: new Date().getMonth() + 1,
    currentPeriodYear: new Date().getFullYear(),
    forwardPeriod: '1',
    controlDate: '1',
    minAllowPercentage: 0.00,
    maxAllowPercentage: 0.00,
    zeroPO: 'N',
  },
  inventory: {
    currentPeriodMonth: new Date().getMonth() + 1,
    currentPeriodYear: new Date().getFullYear(),
    backwardPeriod: '0',
    controlDate: '1',
    zeroTran: 'Y',
  },
  costing: {
    currentPeriodMonth: new Date().getMonth() + 1,
    currentPeriodYear: new Date().getFullYear(),
    controlDate: '1',
    yAllowAfterPeriod: true,
  }
});

// Fetch settings from the API
const fetchSettings = async () => {
  loading.value = true;
  errorMessage.value = '';
  
  try {
    const response = await axios.get('/api/material-management/control-period');
    
    // Directly update form data with API response
    formData.value = {
      ...formData.value,
      ...response.data,
    };
    
    // Update the initialFormData backup after fetching
    initialFormData.value = JSON.parse(JSON.stringify(formData.value));
    
  } catch (error) {
    console.error('Error fetching control period settings:', error);
    errorMessage.value = `Failed to fetch configuration: ${error.response?.data?.error || error.message}`;
    toast.error('Error loading settings. Please try again.');
  } finally {
    loading.value = false;
  }
};

// Save settings to the API
const saveSettings = async () => {
  loading.value = true;
  errorMessage.value = '';
  successMessage.value = '';
  
  try {
    const response = await axios.post('/api/material-management/control-period', formData.value);
    
    successMessage.value = response.data.message || 'Control period settings saved successfully!';
    toast.success(successMessage.value);
    
    // Update the initialFormData backup
    initialFormData.value = JSON.parse(JSON.stringify(formData.value));

  } catch (error) {
    console.error('Error saving control period settings:', error);
    if (error.response?.data?.errors) {
      // Handle validation errors
      const firstError = Object.values(error.response.data.errors)[0][0];
      errorMessage.value = `Error saving settings: ${firstError}`;
    } else {
      errorMessage.value = `Error saving settings: ${error.response?.data?.error || error.message}`;
    }
    toast.error(errorMessage.value);
  } finally {
    loading.value = false;
  }
};

// Reset form to initial state
const resetForm = () => {
  if (initialFormData.value) {
    formData.value = JSON.parse(JSON.stringify(initialFormData.value));
    toast.info('Form has been reset to the last saved state');
  }
};

// Fetch data when component mounts
onMounted(() => {
  fetchSettings();
});
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
