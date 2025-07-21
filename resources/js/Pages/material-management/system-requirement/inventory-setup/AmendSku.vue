<template>
  <AppLayout :header="'Amend SKU#'">
    <Head title="Amend SKU#" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-tag mr-3"></i> Amend SKU#
      </h2>
      <p class="text-blue-100">Change the SKU code of an existing product</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Search Section -->
      <div class="mb-6 border border-gray-200 rounded-lg p-6 bg-gray-50">
        <div class="flex flex-col md:flex-row items-center gap-4">
          <div class="w-full md:w-2/3">
            <label for="skuCode" class="block text-sm font-medium text-gray-700 mb-1">SKU#:</label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input
                type="text"
                name="skuCode"
                id="skuCode"
                v-model="searchSkuCode"
                class="min-w-0 flex-1 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Enter SKU code"
                @keyup.enter="searchSku"
              />
              <button
                type="button"
                @click="openSkuLookupModal"
                class="ml-2 inline-flex items-center rounded bg-gray-200 px-2.5 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                title="Search SKU"
              >
                <i class="fas fa-search"></i>
              </button>
              <button
                type="button"
                @click="searchSku"
                class="ml-2 inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                :disabled="isSearching"
              >
                <i v-if="isSearching" class="fas fa-spinner fa-spin mr-1"></i>
                Search
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- SKU Details Section -->
      <div v-if="selectedSku" class="border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Current SKU Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">SKU#:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.sku }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.sts || 'N/A' }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">SKU Name:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.sku_name }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Category:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.category?.name || selectedSku.category_code }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Type:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.type || 'N/A' }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">UOM:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.uom || 'N/A' }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">BOH:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.boh }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">FPO:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.fpo }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">ROL:</label>
            <div class="mt-1 bg-gray-100 p-2 rounded-md border border-gray-300">
              {{ selectedSku.rol }}
            </div>
          </div>
        </div>

        <!-- New SKU Code Section -->
        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Change SKU# Code</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="newSkuCode" class="block text-sm font-medium text-gray-700">New SKU#:</label>
              <input
                type="text"
                name="newSkuCode"
                id="newSkuCode"
                v-model="newSkuCode"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Enter new SKU code"
                @input="validateSkuFormat"
              />
              <div v-if="skuFormatError" class="mt-1 text-sm text-red-600">
                {{ skuFormatError }}
              </div>
              <p v-else class="mt-2 text-sm text-gray-500">
                The new SKU code must be unique and follow the standard format (e.g., XXX-XXXXXX).
              </p>
            </div>
            
            <div>
              <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Change:</label>
              <select
                id="reason"
                v-model="reason"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              >
                <option value="">Select a reason</option>
                <option value="correction">Data Correction</option>
                <option value="standardization">Format Standardization</option>
                <option value="merge">SKU Merge</option>
                <option value="restructure">Catalog Restructuring</option>
              </select>
              
              <!-- Reason explanation -->
              <div v-if="reason" class="mt-2 text-sm">
                <div v-if="reason === 'correction'" class="text-blue-600">
                  <i class="fas fa-info-circle mr-1"></i> Use this option to fix incorrectly entered SKU codes.
                </div>
                <div v-else-if="reason === 'standardization'" class="text-green-600">
                  <i class="fas fa-check-circle mr-1"></i> Use this option to update SKU codes to match company naming standards.
                </div>
                <div v-else-if="reason === 'merge'" class="text-purple-600">
                  <i class="fas fa-object-group mr-1"></i> Use this option to combine two SKUs into a single new SKU.
                </div>
                <div v-else-if="reason === 'restructure'" class="text-orange-600">
                  <i class="fas fa-sitemap mr-1"></i> Use this option when reorganizing product catalog structure.
                </div>
              </div>
            </div>
            
            <div v-if="reason === 'merge'" class="col-span-1 md:col-span-2">
              <label for="mergeFrom" class="block text-sm font-medium text-gray-700">Merge From SKU:</label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  type="text"
                  name="mergeFrom"
                  id="mergeFrom"
                  v-model="mergeFrom"
                  class="min-w-0 flex-1 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  placeholder="Enter SKU to merge from"
                />
                <button
                  type="button"
                  @click="openMergeSkuLookupModal"
                  class="ml-2 inline-flex items-center rounded bg-gray-200 px-2.5 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  title="Search SKU to merge"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                Select another SKU to merge with the current one. Both SKUs will be replaced with the new SKU.
              </p>
            </div>
            
            <div class="col-span-1 md:col-span-2">
              <label for="notes" class="block text-sm font-medium text-gray-700">Notes:</label>
              <textarea
                id="notes"
                v-model="notes"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Enter notes about this SKU code change"
              ></textarea>
            </div>
          </div>

          <!-- Warning Message -->
          <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-yellow-700">
                  <strong>Warning:</strong> Changing the SKU code will affect all related records in the system. This action cannot be easily reversed.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-4">
        <button
          type="button"
          @click="clearForm"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Clear
        </button>
        <button
          type="button"
          @click="updateSkuCode"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          :disabled="!canSubmit || isProcessing"
        >
          <i v-if="isProcessing" class="fas fa-spinner fa-spin mr-2"></i>
          <i v-else class="fas fa-save mr-2"></i>
          {{ isProcessing ? 'Updating...' : 'Update SKU Code' }}
        </button>
      </div>
    </div>

    <!-- SKU Lookup Modal -->
    <SkuLookupModal
      :show="isSkuLookupModalOpen"
      @close="closeSkuLookupModal"
      @skuSelected="handleSkuSelected"
    />
    
    <!-- Merge SKU Lookup Modal -->
    <SkuLookupModal
      :show="isMergeSkuLookupModalOpen"
      @close="closeMergeSkuLookupModal"
      @skuSelected="handleMergeSkuSelected"
    />

    <!-- Confirmation Modal -->
    <Modal :show="showConfirmModal" @close="showConfirmModal = false">
      <template #header>
        <h3 class="text-lg font-medium text-gray-900">Confirm SKU Code Change</h3>
      </template>
      <template #body>
        <div class="p-4 mb-4 rounded-md" :class="{
          'bg-blue-50': reason === 'correction',
          'bg-green-50': reason === 'standardization',
          'bg-purple-50': reason === 'merge',
          'bg-orange-50': reason === 'restructure'
        }">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="mr-2" :class="{
                'fas fa-info-circle text-blue-500': reason === 'correction',
                'fas fa-check-circle text-green-500': reason === 'standardization',
                'fas fa-object-group text-purple-500': reason === 'merge',
                'fas fa-sitemap text-orange-500': reason === 'restructure'
              }"></i>
            </div>
            <div>
              <p class="text-sm font-medium" :class="{
                'text-blue-700': reason === 'correction',
                'text-green-700': reason === 'standardization',
                'text-purple-700': reason === 'merge',
                'text-orange-700': reason === 'restructure'
              }">
                You are about to change the SKU code from <strong>{{ selectedSku?.sku }}</strong> to <strong>{{ newSkuCode }}</strong>.
              </p>
              <p class="mt-1 text-sm" :class="{
                'text-blue-600': reason === 'correction',
                'text-green-600': reason === 'standardization',
                'text-purple-600': reason === 'merge',
                'text-orange-600': reason === 'restructure'
              }">
                <strong>Reason:</strong> {{ reasonText }}
              </p>
            </div>
          </div>
        </div>
        
        <p class="text-sm text-gray-500 mb-4">
          This action will affect all related records in the system and cannot be easily reversed.
        </p>
        
        <div v-if="reason === 'merge'" class="mt-2 p-3 bg-purple-50 border border-purple-200 rounded-md">
          <p class="text-sm text-purple-700">
            <i class="fas fa-object-group mr-1"></i> <strong>Merge Operation:</strong> This will combine data from SKU <strong>{{ selectedSku?.sku }}</strong> and 
            <strong>{{ mergeFrom }}</strong> into a new SKU <strong>{{ newSkuCode }}</strong>.
            Both original SKUs will be removed from the system.
          </p>
        </div>
        
        <div v-if="notes" class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-md">
          <p class="text-sm text-gray-700">
            <i class="fas fa-sticky-note mr-1"></i> <strong>Notes:</strong> {{ notes }}
          </p>
        </div>
        
        <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400">
          <p class="text-sm font-medium text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-1"></i> Are you sure you want to proceed?
          </p>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-3">
          <button
            type="button"
            @click="showConfirmModal = false"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="confirmUpdateSku"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <i class="fas fa-exclamation-triangle mr-2"></i> Yes, Change SKU Code
          </button>
        </div>
      </template>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';
import SkuLookupModal from '@/Components/SkuLookupModal.vue';
import Modal from '@/Components/Modal.vue';

// Toast
const toast = useToast();

// State variables
const searchSkuCode = ref('');
const selectedSku = ref(null);
const newSkuCode = ref('');
const reason = ref('');
const notes = ref('');
const mergeFrom = ref('');
const isProcessing = ref(false);
const showConfirmModal = ref(false);
const isSkuLookupModalOpen = ref(false);
const isMergeSkuLookupModalOpen = ref(false);
const isSearching = ref(false); // New state variable for search loading
const skuFormatError = ref(''); // New state variable for SKU format error

// Computed properties
const reasonText = computed(() => {
  switch (reason.value) {
    case 'correction': return 'Data Correction';
    case 'standardization': return 'Format Standardization';
    case 'merge': return 'SKU Merge';
    case 'restructure': return 'Catalog Restructuring';
    default: return '';
  }
});

const canSubmit = computed(() => {
  if (!selectedSku.value || !newSkuCode.value || !reason.value) {
    return false;
  }
  
  // If reason is merge, require mergeFrom
  if (reason.value === 'merge' && !mergeFrom.value) {
    return false;
  }
  
  return true;
});

// Modal handlers
const openSkuLookupModal = () => {
  isSkuLookupModalOpen.value = true;
};

const closeSkuLookupModal = () => {
  isSkuLookupModalOpen.value = false;
};

const openMergeSkuLookupModal = () => {
  isMergeSkuLookupModalOpen.value = true;
};

const closeMergeSkuLookupModal = () => {
  isMergeSkuLookupModalOpen.value = false;
};

const handleSkuSelected = (sku) => {
  searchSkuCode.value = sku.sku;
  closeSkuLookupModal();
  searchSku();
};

const handleMergeSkuSelected = (sku) => {
  // Don't allow selecting the same SKU
  if (sku.sku === selectedSku.value?.sku) {
    toast.error('Cannot merge a SKU with itself');
    return;
  }
  
  mergeFrom.value = sku.sku;
  closeMergeSkuLookupModal();
};

// Search for an SKU by its code
const searchSku = async () => {
  if (!searchSkuCode.value) {
    toast.error('Please enter an SKU code');
    return;
  }

  isSearching.value = true; // Start searching
  try {
    const response = await axios.get(`/api/material-management/skus/${searchSkuCode.value}`);
    selectedSku.value = response.data;
    newSkuCode.value = '';
    reason.value = '';
    notes.value = '';
    mergeFrom.value = '';
    skuFormatError.value = ''; // Clear error on successful search
  } catch (error) {
    console.error('Error fetching SKU:', error);
    toast.error('SKU not found or there was an error retrieving the data');
    selectedSku.value = null;
    skuFormatError.value = ''; // Clear error on not found
  } finally {
    isSearching.value = false; // End searching
  }
};

// Validate SKU format
const validateSkuFormat = () => {
  // Allow more flexible SKU format (common patterns in ERP systems)
  // 1. XXX-XXXXXX (e.g., ABC-123456)
  // 2. XXX-XXX-XXX (e.g., ABC-123-456)
  // 3. XXXXX (e.g., AB123)
  // 4. XXX-XXXX (e.g., ABC-1234)
  
  if (!newSkuCode.value) {
    skuFormatError.value = '';
    return;
  }
  
  const validFormats = [
    /^[A-Z0-9]{3}-\d{6}$/,      // XXX-XXXXXX
    /^[A-Z0-9]{3}-\d{3}-\d{3}$/, // XXX-XXX-XXX
    /^[A-Z0-9]{2,5}\d{2,5}$/,   // XXXXX (mix of 2-5 letters and 2-5 numbers)
    /^[A-Z0-9]{3}-\d{4}$/       // XXX-XXXX
  ];
  
  const isValid = validFormats.some(regex => regex.test(newSkuCode.value));
  
  if (!isValid) {
    skuFormatError.value = 'SKU code must follow one of these formats: XXX-XXXXXX, XXX-XXX-XXX, XXXXX, or XXX-XXXX';
  } else {
    skuFormatError.value = '';
  }
};

// Clear the form
const clearForm = () => {
  searchSkuCode.value = '';
  selectedSku.value = null;
  newSkuCode.value = '';
  reason.value = '';
  notes.value = '';
  mergeFrom.value = '';
  skuFormatError.value = ''; // Clear error on clear
};

// Update the SKU code
const updateSkuCode = () => {
  if (!canSubmit.value) {
    if (!selectedSku.value) {
      toast.error('Please select an SKU');
    } else if (!newSkuCode.value) {
      toast.error('Please enter a new SKU code');
    } else if (!reason.value) {
      toast.error('Please select a reason for the change');
    } else if (reason.value === 'merge' && !mergeFrom.value) {
      toast.error('Please select a SKU to merge from');
    }
    return;
  }

  if (newSkuCode.value === selectedSku.value.sku) {
    toast.error('The new SKU code must be different from the current one');
    return;
  }
  
  if (reason.value === 'merge' && mergeFrom.value === selectedSku.value.sku) {
    toast.error('Cannot merge a SKU with itself');
    return;
  }

  if (skuFormatError.value) {
    toast.error('Please enter a valid SKU code format.');
    return;
  }

  showConfirmModal.value = true;
};

// Confirm and execute the SKU code update
const confirmUpdateSku = async () => {
  isProcessing.value = true;

  try {
    const payload = {
      new_sku: newSkuCode.value,
      reason: reason.value,
      notes: notes.value
    };
    
    // Add merge_from if reason is merge
    if (reason.value === 'merge') {
      payload.merge_from = mergeFrom.value;
    }
    
    const response = await axios.post(`/api/material-management/skus/${selectedSku.value.sku}/change-code`, payload);

    toast.success(response.data.message || 'SKU code changed successfully');
    showConfirmModal.value = false;

    // Update the selected SKU with the new code
    if (response.data.sku) {
      selectedSku.value = response.data.sku;
      searchSkuCode.value = newSkuCode.value;
      newSkuCode.value = '';
      reason.value = '';
      notes.value = '';
      mergeFrom.value = '';
      skuFormatError.value = ''; // Clear error on successful update
    } else {
      // If no SKU data returned, reload the page to refresh
      toast.info('Please search for the new SKU code to see the updated data');
      clearForm();
    }
  } catch (error) {
    console.error('Error changing SKU code:', error);
    
    // Handle different error types
    if (error.response) {
      // The request was made and the server responded with an error status
      if (error.response.status === 422) {
        // Validation error or foreign key constraint violation
        toast.error(`Cannot change SKU code: ${error.response.data.message || 'This SKU is being used by other records'}`);
      } else if (error.response.status === 404) {
        // SKU not found
        toast.error('The SKU you are trying to update no longer exists');
        clearForm();
      } else {
        // Other server errors
        toast.error(`Server error: ${error.response.data.message || error.message}`);
      }
    } else if (error.request) {
      // The request was made but no response was received
      toast.error('No response from server. Please check your network connection');
    } else {
      // Something happened in setting up the request
      toast.error(`Error: ${error.message}`);
    }
  } finally {
    isProcessing.value = false;
    if (showConfirmModal.value) {
      showConfirmModal.value = false; // Always close modal if still open
    }
  }
};
</script>
