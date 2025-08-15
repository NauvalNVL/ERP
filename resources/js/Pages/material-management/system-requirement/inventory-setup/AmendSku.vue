<template>
  <AppLayout :header="'Amend SKU#'">
    <Head title="Amend SKU#" />

    <!-- Loading Overlay -->
    <div v-if="isProcessing" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 flex flex-col items-center">
        <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-4"></i>
        <p class="text-lg font-medium text-gray-900">Updating SKU Code...</p>
        <p class="text-sm text-gray-500 mt-2">Please wait, this may take a few moments.</p>
        
        <!-- Fallback button if processing takes too long -->
        <button 
          @click="forceResetProcessing" 
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
        >
          <i class="fas fa-times mr-2"></i>
          Cancel Update
        </button>
      </div>
    </div>

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Success Message -->
    <div v-if="successMessage" class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fas fa-check-circle text-green-400"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-700">
            <strong>Success:</strong> {{ successMessage }}
          </p>
        </div>
        <div class="ml-auto pl-3">
          <button @click="successMessage = ''" class="text-green-400 hover:text-green-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-tag mr-3"></i> Amend SKU#
      </h2>
      <p class="text-blue-100">Change the SKU code of an existing product</p>
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
                <input type="text" v-model="searchSkuCodeUpper" placeholder="Enter SKU code (e.g., 000055, 001-A01001)"
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  @keyup.enter="searchSku"
                  style="text-transform: uppercase;">
              </div>
              <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-info-circle mr-1"></i>
                Available SKUs: 000055, 000055A, 000055B, 000202, 001-A01001, etc.
              </p>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="searchSku" class="btn-primary flex-1 md:flex-none" :disabled="isSearching">
                <i v-if="isSearching" class="fas fa-spinner fa-spin mr-2"></i>
                <i v-else class="fas fa-search mr-2"></i> Search
              </button>
              <button @click="openSkuLookupModal" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-list mr-2"></i> Lookup
              </button>
              <button @click="clearForm" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Reset
              </button>
            </div>
          </div>

          <!-- No SKU Selected Message -->
          <div v-if="!selectedSku && !isSearching" class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center mb-6">
            <i class="fas fa-search text-blue-400 text-3xl mb-3"></i>
            <h3 class="text-lg font-medium text-blue-900 mb-2">Search for an SKU to Amend</h3>
            <p class="text-blue-700 mb-4">
              Enter an existing SKU code in the search field above to view its details and change its code.
            </p>
            <div class="bg-white rounded-lg p-4 text-left">
              <h4 class="font-medium text-gray-900 mb-2">Available SKU Examples:</h4>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                <span class="font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">000055</span>
                <span class="font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">000055A</span>
                <span class="font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">000055B</span>
                <span class="font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">000202</span>
                <span class="font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">001-A01001</span>
              </div>
              <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                Note: SKU codes are case-sensitive and must match exactly. Try any of these examples above.
              </p>
            </div>
          </div>

          <!-- SKU Details & New SKU Code Section -->
          <div v-if="selectedSku" class="bg-white rounded-lg overflow-hidden border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Current SKU Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
              <div class="form-detail-item">
                <span class="form-detail-label">SKU#:</span>
                <span class="form-detail-value font-mono">{{ selectedSku.sku }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">Status:</span>
                <span class="form-detail-value">
                  <span :class="{
                    'px-2 py-1 text-xs font-medium rounded-full': true,
                    'bg-green-100 text-green-800': selectedSku.is_active,
                    'bg-red-100 text-red-800': !selectedSku.is_active
                  }">
                    {{ selectedSku.sts || (selectedSku.is_active ? 'ACT' : 'OBS') }}
                  </span>
                </span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">SKU Name:</span>
                <span class="form-detail-value">{{ selectedSku.sku_name }}</span>
              </div>
              <div class="form-detail-item">
                <span class="form-detail-label">Category:</span>
                <span class="form-detail-value">{{ selectedSku.category?.name || selectedSku.category_code }}</span>
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

            <div class="border-t border-gray-200 pt-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Change SKU# Code</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-field">
                  <label for="newSkuCode" class="form-label">New SKU#:</label>
                  <div class="relative">
                    <input
                      type="text"
                      name="newSkuCode"
                      id="newSkuCode"
                      v-model="newSkuCodeUpper"
                      class="form-input"
                      placeholder="Enter new SKU code (e.g., 000055, 000055A)"
                      @input="validateSkuFormat"
                      style="text-transform: uppercase;"
                    />
                    <div v-if="isCheckingSku" class="absolute inset-y-0 right-0 flex items-center pr-3">
                      <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    </div>
                  </div>
                  <p v-if="skuFormatError" class="form-error">{{ skuFormatError }}</p>
                  <p v-else class="text-xs text-gray-500 mt-2 italic">
                    The new SKU code must be unique and follow the standard format (e.g., XXX-XXXXXX).
                  </p>
                </div>
                
                <div class="form-field">
                  <label for="reason" class="form-label">Reason for Change:</label>
                  <select
                    id="reason"
                    v-model="reason"
                    class="form-input"
                  >
                    <option value="">Select a reason</option>
                    <option value="correction">Data Correction</option>
                    <option value="standardization">Format Standardization</option>
                    <option value="merge">SKU Merge</option>
                    <option value="restructure">Catalog Restructuring</option>
                  </select>
                  
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
                
                <div v-if="reason === 'merge'" class="col-span-1 md:col-span-2 form-field">
                  <label for="mergeFrom" class="form-label">Merge From SKU:</label>
                  <div class="flex">
                    <input
                      type="text"
                      name="mergeFrom"
                      id="mergeFrom"
                      v-model="mergeFromUpper"
                      class="form-input flex-1"
                      placeholder="Enter SKU to merge from"
                      style="text-transform: uppercase;"
                    />
                    <button
                      type="button"
                      @click="openMergeSkuLookupModal"
                      class="ml-2 btn-secondary"
                      title="Search SKU to merge"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <p class="text-xs text-gray-500 mt-2 italic">
                    Select another SKU to merge with the current one. Both SKUs will be replaced with the new SKU.
                  </p>
                </div>
                
                <div class="col-span-1 md:col-span-2 form-field">
                  <label for="notes" class="form-label">Notes:</label>
                  <textarea
                    id="notes"
                    v-model="notes"
                    rows="3"
                    class="form-input"
                    placeholder="Enter notes about this SKU code change"
                  ></textarea>
                </div>
              </div>

              <!-- Warning Message -->
              <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
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

          <div v-else-if="isSearching" class="py-8 text-center bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="flex justify-center items-center space-x-2 text-blue-500">
              <i class="fas fa-spinner fa-spin text-xl"></i>
              <span>Searching SKU...</span>
            </div>
          </div>
          <div v-else-if="selectedSku === null && searchSkuCodeUpper && !isSearching" class="py-8 text-center bg-white rounded-lg overflow-hidden border border-gray-200">
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

        <!-- Side Panel (Placeholder for consistency) -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex flex-col items-center justify-center h-full text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-tag text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">SKU Amendment</h3>
            <p class="text-gray-500 mb-4">Search for an SKU to change its code. All related records will be updated.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="bg-white rounded-lg shadow-md p-6 flex justify-end gap-4 mt-6">
      <button
        type="button"
        @click="clearForm"
        class="btn-secondary"
      >
        <i class="fas fa-times mr-2"></i> Clear Form
      </button>
      <button
        type="button"
        @click="updateSkuCode"
        class="btn-primary"
        :disabled="!canSubmit || isProcessing"
      >
        <i v-if="isProcessing" class="fas fa-spinner fa-spin mr-2"></i>
        <i v-else class="fas fa-save mr-2"></i>
        {{ isProcessing ? 'Updating...' : 'Update SKU Code' }}
      </button>
    </div>

    <!-- Debug Information (temporary for troubleshooting) -->
    <div v-if="isDevelopment" class="bg-gray-100 rounded-lg p-4 mt-4 text-xs">
      <h4 class="font-bold mb-2">Debug Info:</h4>
      <div class="grid grid-cols-2 gap-2">
        <div>Selected SKU: {{ selectedSku ? 'Yes' : 'No' }}</div>
        <div>New SKU Code: {{ newSkuCodeUpper || 'Empty' }}</div>
        <div>Reason: {{ reason || 'Empty' }}</div>
        <div>Merge From: {{ reason === 'merge' ? (mergeFromUpper || 'Empty') : 'N/A' }}</div>
        <div>Format Error: {{ skuFormatError || 'None' }}</div>
        <div>Checking SKU: {{ isCheckingSku ? 'Yes' : 'No' }}</div>
        <div>Can Submit: {{ canSubmit ? 'Yes' : 'No' }}</div>
        <div>Is Processing: {{ isProcessing ? 'Yes' : 'No' }}</div>
      </div>
    </div>

    <!-- SKU Lookup Modal -->
    <SkuLookupModal
      :show="isSkuLookupModalOpen"
      @close="closeSkuLookupModal"
      @sku-selected="handleSkuSelected"
    />
    
    <!-- Merge SKU Lookup Modal -->
    <SkuLookupModal
      :show="isMergeSkuLookupModalOpen"
      @close="closeMergeSkuLookupModal"
      @sku-selected="handleMergeSkuSelected"
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
                You are about to change the SKU code from <strong>{{ selectedSku?.sku }}</strong> to <strong>{{ newSkuCodeUpper }}</strong>.
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
            <strong>{{ mergeFromUpper }}</strong> into a new SKU <strong>{{ newSkuCodeUpper }}</strong>.
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
            :disabled="isProcessing"
            class="btn-secondary"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="confirmUpdateSku"
            :disabled="isProcessing"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i v-if="isProcessing" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-exclamation-triangle mr-2"></i> 
            {{ isProcessing ? 'Updating...' : 'Yes, Change SKU Code' }}
          </button>
        </div>
      </template>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
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
const isCheckingSku = ref(false); // New state variable for checking SKU existence
const checkSkuTimeout = ref(null); // For debouncing SKU existence check
const successMessage = ref(''); // For displaying success messages

// Computed property untuk mengkonversi SKU code ke uppercase
const newSkuCodeUpper = computed({
  get: () => newSkuCode.value,
  set: (value) => {
    newSkuCode.value = value.toUpperCase();
  }
});

// Computed property untuk mengkonversi mergeFrom ke uppercase
const mergeFromUpper = computed({
  get: () => mergeFrom.value,
  set: (value) => {
    mergeFrom.value = value.toUpperCase();
  }
});

// Computed property untuk mengkonversi searchSkuCode ke uppercase
const searchSkuCodeUpper = computed({
  get: () => searchSkuCode.value,
  set: (value) => {
    searchSkuCode.value = value.toUpperCase();
  }
});

// Watch for changes in newSkuCodeUpper to validate format
watch(newSkuCodeUpper, () => {
  validateSkuFormat();
});

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

// Development environment check
const isDevelopment = computed(() => {
  return import.meta.env.DEV;
});

const canSubmit = computed(() => {
  // Don't allow submit if currently processing
  if (isProcessing.value) {
    return false;
  }
  
  // Don't allow submit if currently searching
  if (isSearching.value) {
    return false;
  }
  
  // Don't allow submit if currently checking SKU existence
  if (isCheckingSku.value) {
    return false;
  }
  
  // Check if all required fields are filled
  if (!selectedSku.value || !newSkuCodeUpper.value || !reason.value) {
    return false;
  }
  
  // If reason is merge, require mergeFrom
  if (reason.value === 'merge' && !mergeFromUpper.value) {
    return false;
  }
  
  // Check if there's a format error
  if (skuFormatError.value) {
    return false;
  }
  
  // Don't allow submit if new SKU is the same as current
  if (selectedSku.value && newSkuCodeUpper.value === selectedSku.value.sku) {
    return false;
  }
  
  // Don't allow submit if merge from is the same as current SKU
  if (reason.value === 'merge' && mergeFromUpper.value === selectedSku.value?.sku) {
    return false;
  }
  
  // Don't allow submit if merge from is the same as new SKU
  if (reason.value === 'merge' && mergeFromUpper.value === newSkuCodeUpper.value) {
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

// Search for SKU
const searchSku = async () => {
  if (!searchSkuCodeUpper.value.trim()) {
    toast.error('Please enter an SKU code to search');
    return;
  }

  isSearching.value = true; // Start searching
  let controller = null;
  let timeoutId = null;
  
  try {
    // Add timeout to prevent hanging requests
    controller = new AbortController();
    timeoutId = setTimeout(() => {
      controller.abort();
    }, 10000); // 10 second timeout
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Fixed API endpoint to match the actual route with material-management prefix
    const response = await axios.get(`/api/material-management/skus/${encodeURIComponent(searchSkuCodeUpper.value)}`, {
      signal: controller.signal,
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      withCredentials: true,
      timeout: 10000 // 10 second timeout
    });
    
    clearTimeout(timeoutId);
    
    selectedSku.value = response.data;
    newSkuCode.value = ''; // Don't pre-fill with current SKU, let user enter new one
    reason.value = '';
    notes.value = '';
    mergeFrom.value = '';
    skuFormatError.value = ''; // Clear any previous errors
    successMessage.value = ''; // Clear any previous success messages
    
    toast.success('SKU found successfully');
  } catch (error) {
    clearTimeout(timeoutId);
    
    if (error.name === 'AbortError') {
      // Request was aborted due to timeout
      toast.error('Search request timed out. Please try again.');
    } else if (error.response && error.response.status === 404) {
      // SKU not found - this is expected when searching for non-existent SKUs
      selectedSku.value = null;
      newSkuCode.value = '';
      reason.value = '';
      notes.value = '';
      mergeFrom.value = '';
      skuFormatError.value = '';
      successMessage.value = '';
      
      toast.error(`SKU "${searchSkuCodeUpper.value}" not found. Please check the SKU code and try again.`);
    } else if (error.response && error.response.status === 500) {
      toast.error('Server error occurred. Please try again later.');
    } else if (error.code === 'NETWORK_ERROR' || error.code === 'ERR_NETWORK') {
      toast.error('Network error. Please check your connection and try again.');
    } else {
      toast.error('Error retrieving SKU data. Please try again.');
    }
  } finally {
    isSearching.value = false; // Always stop searching
  }
};

// Validate SKU format
const validateSkuFormat = () => {
  // Allow more flexible SKU format based on existing data patterns
  // Based on the database sample: "000055", "000055A", "001-A01001", "007-SRV-C27"
  
  if (!newSkuCodeUpper.value) {
    skuFormatError.value = '';
    return;
  }
  
  // Check if new SKU code is the same as current
  if (selectedSku.value && newSkuCodeUpper.value === selectedSku.value.sku) {
    skuFormatError.value = 'New SKU code must be different from the current one';
    return;
  }
  
  // More flexible validation patterns based on actual data
  const validFormats = [
    /^[A-Z0-9]{6}$/,                    // 6 alphanumeric (e.g., "000055", "000202")
    /^[A-Z0-9]{7}$/,                    // 7 alphanumeric (e.g., "0000777")
    /^[A-Z0-9]{6}[A-Z]$/,               // 6 alphanumeric + 1 letter (e.g., "000055A", "000055B")
    /^[A-Z0-9]{3}-[A-Z0-9]{6}$/,        // XXX-XXXXXX (e.g., "001-A01001")
    /^[A-Z0-9]{3}-[A-Z]{3}-[A-Z0-9]{2,3}$/, // XXX-XXX-XX(X) (e.g., "007-SRV-C27")
    /^[A-Z0-9]{3}-[A-Z0-9]{4}$/,        // XXX-XXXX (e.g., "ABC-1234")
    /^[A-Z0-9]{4}-[A-Z0-9]{3}$/,        // XXXX-XXX (e.g., "ABCD-123")
    /^[A-Z0-9]{2}-[A-Z0-9]{5}$/,        // XX-XXXXX (e.g., "AB-12345")
    /^[A-Z0-9]{1,20}$/                  // Simple alphanumeric (1-20 characters) - increased for flexibility
  ];
  
  const isValid = validFormats.some(regex => regex.test(newSkuCodeUpper.value));
  
  if (!isValid) {
    skuFormatError.value = 'SKU code must be 1-20 alphanumeric characters and may include hyphens. Examples: 000055, 000055A, 001-A01001, 007-SRV-C27';
  } else {
    skuFormatError.value = '';
    // Debounce the SKU existence check
    if (checkSkuTimeout.value) {
      clearTimeout(checkSkuTimeout.value);
    }
    checkSkuTimeout.value = setTimeout(() => {
      checkSkuExists();
    }, 500); // Wait 500ms after user stops typing
  }
};

// Check if the new SKU code already exists
const checkSkuExists = async () => {
  if (!newSkuCodeUpper.value || skuFormatError.value) {
    return;
  }
  
  // Don't check if currently processing
  if (isProcessing.value) {
    return;
  }
  
  // Don't check if it's the same as current SKU
  if (selectedSku.value && newSkuCodeUpper.value === selectedSku.value.sku) {
    return;
  }
  
  isCheckingSku.value = true;
  let controller = null;
  let timeoutId = null;
  
  try {
    // Add timeout to prevent hanging requests
    controller = new AbortController();
    timeoutId = setTimeout(() => {
      controller.abort();
    }, 10000); // 10 second timeout
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    const response = await axios.get(`/api/material-management/skus/${encodeURIComponent(newSkuCodeUpper.value)}`, {
      signal: controller.signal,
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      withCredentials: true,
      timeout: 10000 // 10 second timeout
    });
    
    clearTimeout(timeoutId);
    
    // If we get here, the SKU exists
    skuFormatError.value = 'This SKU code already exists. Please choose a different code.';
  } catch (error) {
    clearTimeout(timeoutId);
    
    if (error.name === 'AbortError') {
      // Request was aborted due to timeout
      toast.error('SKU check timed out. Please try again.');
    } else if (error.response && error.response.status === 404) {
      // SKU not found - this means the new SKU code is available
      skuFormatError.value = ''; // Clear any previous errors
    } else if (error.response && error.response.status === 500) {
      toast.error('Server error occurred while checking SKU. Please try again later.');
    } else if (error.code === 'NETWORK_ERROR' || error.code === 'ERR_NETWORK') {
      toast.error('Network error while checking SKU. Please check your connection and try again.');
    } else {
      toast.error('Error checking SKU availability. Please try again.');
    }
  } finally {
    isCheckingSku.value = false; // Always stop checking
  }
};

// Clear the form
const clearForm = () => {
  // Don't clear if currently processing
  if (isProcessing.value) {
    toast.warning('Please wait, update is in progress...');
    return;
  }
  
  // Don't clear if currently searching
  if (isSearching.value) {
    toast.warning('Please wait, search is in progress...');
    return;
  }
  
  searchSkuCode.value = '';
  selectedSku.value = null;
  newSkuCode.value = '';
  reason.value = '';
  notes.value = '';
  mergeFrom.value = '';
  skuFormatError.value = ''; // Clear error on clear
  successMessage.value = ''; // Clear success message
  
  // Clear any pending timeouts
  if (checkSkuTimeout.value) {
    clearTimeout(checkSkuTimeout.value);
    checkSkuTimeout.value = null;
  }
  
  // Reset checking state
  isCheckingSku.value = false;
  
  // Close any open modals
  showConfirmModal.value = false;
  isSkuLookupModalOpen.value = false;
  isMergeSkuLookupModalOpen.value = false;
  
  toast.success('Form cleared successfully');
};

// Global error handler
const handleGlobalError = (error) => {
  console.error('Global error caught:', error);
  
  // Force reset all processing states
  isProcessing.value = false;
  isSearching.value = false;
  isCheckingSku.value = false;
  showConfirmModal.value = false;
  
  // Clear any pending timeouts
  if (checkSkuTimeout.value) {
    clearTimeout(checkSkuTimeout.value);
    checkSkuTimeout.value = null;
  }
  
  toast.error('An unexpected error occurred. Please try again.');
};

// Setup global error handling
onMounted(() => {
  // Add global error handler
  window.addEventListener('unhandledrejection', (event) => {
    console.error('Unhandled promise rejection:', event.reason);
    handleGlobalError(event.reason);
  });
  
  window.addEventListener('error', (event) => {
    console.error('Global error:', event.error);
    handleGlobalError(event.error);
  });
  
  // Add periodic check to ensure processing state is not stuck
  const processingCheckInterval = setInterval(() => {
    if (isProcessing.value) {
      console.log('Processing state check - still processing after 30 seconds');
      // If processing has been true for more than 30 seconds, force reset
      if (Date.now() - (window.processingStartTime || 0) > 30000) {
        console.log('Force resetting stuck processing state');
        isProcessing.value = false;
        showConfirmModal.value = false;
        toast.error('Processing timeout - please try again');
      }
    }
  }, 5000); // Check every 5 seconds
  
  // Store the interval for cleanup
  window.processingCheckInterval = processingCheckInterval;
});

// Cleanup on component unmount
onUnmounted(() => {
  // Remove global error handlers
  window.removeEventListener('unhandledrejection', handleGlobalError);
  window.removeEventListener('error', handleGlobalError);
  
  // Clear processing check interval
  if (window.processingCheckInterval) {
    clearInterval(window.processingCheckInterval);
  }
  
  // Clear any pending timeouts
  if (checkSkuTimeout.value) {
    clearTimeout(checkSkuTimeout.value);
    checkSkuTimeout.value = null;
  }
  
  // Reset all states
  isProcessing.value = false;
  isSearching.value = false;
  isCheckingSku.value = false;
  showConfirmModal.value = false;
  isSkuLookupModalOpen.value = false;
  isMergeSkuLookupModalOpen.value = false;
});

// Update the SKU code
const updateSkuCode = () => {
  // Prevent multiple clicks
  if (isProcessing.value) {
    toast.warning('Please wait, update is in progress...');
    return;
  }
  
  // Validate all required fields
  if (!selectedSku.value) {
    toast.error('Please select an SKU to update');
    return;
  }
  
  if (!newSkuCodeUpper.value) {
    toast.error('Please enter a new SKU code');
    return;
  }
  
  if (!reason.value) {
    toast.error('Please select a reason for the change');
    return;
  }
  
  if (reason.value === 'merge' && !mergeFromUpper.value) {
    toast.error('Please select a SKU to merge from');
    return;
  }
  
  if (skuFormatError.value) {
    toast.error('Please fix the SKU format error: ' + skuFormatError.value);
    return;
  }
  
  if (isCheckingSku.value) {
    toast.error('Please wait while checking SKU availability');
    return;
  }

  if (newSkuCodeUpper.value === selectedSku.value.sku) {
    toast.error('The new SKU code must be different from the current one');
    return;
  }
  
  if (reason.value === 'merge' && mergeFromUpper.value === selectedSku.value.sku) {
    toast.error('Cannot merge a SKU with itself');
    return;
  }

  if (skuFormatError.value) {
    toast.error('Please enter a valid SKU code format.');
    return;
  }

  // Show confirmation modal
  showConfirmModal.value = true;
};

// Confirm and execute the SKU code update
const confirmUpdateSku = async () => {
  // Prevent multiple simultaneous requests
  if (isProcessing.value) {
    return;
  }
  
  // Record start time for processing
  window.processingStartTime = Date.now();
  console.log('Starting SKU update process at:', window.processingStartTime);
  
  isProcessing.value = true;
  let timeoutId = null;
  let controller = null;

  try {
    // Validate required fields before making request
    if (!selectedSku.value || !newSkuCodeUpper.value || !reason.value) {
      toast.error('Please complete all required fields');
      return;
    }

    if (newSkuCodeUpper.value === selectedSku.value.sku) {
      toast.error('The new SKU code must be different from the current one');
      return;
    }

    if (reason.value === 'merge' && !mergeFromUpper.value) {
      toast.error('Please select a SKU to merge from');
      return;
    }

    if (reason.value === 'merge' && mergeFromUpper.value === selectedSku.value.sku) {
      toast.error('Cannot merge a SKU with itself');
      return;
    }

    if (skuFormatError.value) {
      toast.error('Please fix the SKU format error: ' + skuFormatError.value);
      return;
    }

    const payload = {
      new_sku: newSkuCodeUpper.value,
      reason: reason.value,
      notes: notes.value || ''
    };
    
    // Add merge_from if reason is merge
    if (reason.value === 'merge') {
      payload.merge_from = mergeFromUpper.value;
    }
    
    console.log('Sending payload:', payload);
    console.log('Target SKU:', selectedSku.value.sku);
    console.log('API URL:', `/api/material-management/skus/${encodeURIComponent(selectedSku.value.sku)}/change-code`);
    
    // Add timeout to prevent hanging requests
    controller = new AbortController();
    timeoutId = setTimeout(() => {
      console.log('Request timeout - aborting');
      controller.abort();
    }, 30000); // 30 second timeout for update operation
    
    // Get CSRF token from meta tag or window object
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken && window.getCsrfToken) {
      csrfToken = window.getCsrfToken();
    }
    console.log('CSRF Token:', csrfToken);
    
    // Prepare headers
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    };
    
    // Add CSRF token if available
    if (csrfToken) {
      headers['X-CSRF-TOKEN'] = csrfToken;
    }
    
    console.log('Making API request...');
    
    // Fixed API endpoint to match the actual route with material-management prefix
    const apiUrl = `/api/material-management/skus/${encodeURIComponent(selectedSku.value.sku)}/change-code`;
    console.log('Full API URL:', window.location.origin + apiUrl);
    
    const response = await axios.post(apiUrl, payload, {
      signal: controller.signal,
      timeout: 30000, // Additional axios timeout
      headers: headers,
      withCredentials: true
    });
    
    console.log('Response received:', response.data);
    console.log('Response status:', response.status);
    console.log('Response headers:', response.headers);
    
    // Clear timeout since request completed
    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }

    // Check if the response indicates success
    if (response.data && response.data.success !== false) {
      const oldSkuCode = selectedSku.value.sku;
      const newSkuCode = payload.new_sku;
      const successMsg = response.data.message || `SKU code changed successfully from "${oldSkuCode}" to "${newSkuCode}"`;
      
      toast.success(successMsg);
      
      // Close modal first
      showConfirmModal.value = false;

      // Set success message with more details
      successMessage.value = `✅ ${successMsg}. You can now search for the new SKU code "${newSkuCode}" to verify the changes.`;

      // Update the selected SKU with the new code
      if (response.data.sku) {
        console.log('Updated SKU data:', response.data.sku);
        
        // Update the selected SKU directly with the returned data (no need to search again)
        selectedSku.value = response.data.sku;
        searchSkuCode.value = response.data.sku.sku; // Use the actual returned SKU code
        
        // Clear the form inputs after successful update
        newSkuCode.value = '';
        reason.value = '';
        notes.value = '';
        mergeFrom.value = '';
        skuFormatError.value = '';
        
        console.log('SKU update completed successfully. New SKU:', response.data.sku.sku);
        toast.info(`SKU successfully changed to: ${response.data.sku.sku}`);
      } else {
        // If no SKU data returned, clear form but don't search
        console.log('No SKU data returned from server');
        toast.warning('SKU updated but server did not return updated data. Please search for the new SKU manually.');
        clearForm();
      }
    } else {
      // Handle unsuccessful response
      const errorMessage = response.data?.message || 'Failed to change SKU code';
      toast.error(errorMessage);
    }
  } catch (error) {
    console.error('Error updating SKU:', error);
    console.error('Error response:', error.response);
    console.error('Error status:', error.response?.status);
    console.error('Error data:', error.response?.data);
    
    // Clear timeout if it exists
    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }
    
    if (error.name === 'AbortError' || error.code === 'ECONNABORTED') {
      // Request was aborted due to timeout
      toast.error('Update request timed out. Please try again.');
    } else if (error.response) {
      // The request was made and the server responded with an error status
      const responseData = error.response.data;
      console.error('Server response:', responseData);
      
      if (error.response.status === 422) {
        // Validation error or foreign key constraint violation
        const errorMessage = responseData.message || 'This SKU is being used by other records or validation failed';
        console.error('Validation error details:', responseData);
        toast.error(`Cannot change SKU code: ${errorMessage}`);
        
        // If it's a validation error, show field-specific errors if available
        if (responseData.errors) {
          Object.keys(responseData.errors).forEach(field => {
            const fieldErrors = responseData.errors[field];
            if (Array.isArray(fieldErrors)) {
              fieldErrors.forEach(error => {
                toast.error(`${field}: ${error}`);
              });
            }
          });
        }
      } else if (error.response.status === 404) {
        // SKU not found
        toast.error('The SKU you are trying to update no longer exists. It may have been deleted by another user.');
        clearForm();
      } else if (error.response.status === 409) {
        // Conflict - new SKU already exists
        toast.error('The new SKU code already exists. Please choose a different code.');
        skuFormatError.value = 'This SKU code already exists. Please choose a different code.';
      } else if (error.response.status === 419) {
        // CSRF token mismatch
        toast.error('Session expired. Please refresh the page and try again.');
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      } else if (error.response.status === 500) {
        // Server error
        const errorMessage = responseData.message || 'Internal server error occurred';
        console.error('Server error details:', responseData);
        toast.error(`Server error: ${errorMessage}`);
      } else {
        // Other server errors
        const errorMessage = responseData.message || error.message || 'Unknown server error occurred';
        console.error('Other server error:', responseData);
        toast.error(`Error (${error.response.status}): ${errorMessage}`);
      }
    } else if (error.code === 'NETWORK_ERROR' || error.code === 'ERR_NETWORK') {
      // Network error
      toast.error('Network error. Please check your connection and try again.');
    } else {
      // Something happened in setting up the request
      console.error('Error updating SKU:', error);
      toast.error(`Error: ${error.message || 'Unknown error occurred'}`);
    }
  } finally {
    // Always clean up
    console.log('Finally block executed - cleaning up...');
    console.log('Processing time:', Date.now() - (window.processingStartTime || 0), 'ms');
    
    if (timeoutId) {
      clearTimeout(timeoutId);
      console.log('Timeout cleared');
    }
    
    // Always reset processing state
    console.log('Resetting processing state from:', isProcessing.value);
    isProcessing.value = false;
    console.log('Processing state reset to:', isProcessing.value);
    
    // Always close modal if still open
    if (showConfirmModal.value) {
      console.log('Closing confirmation modal');
      showConfirmModal.value = false;
    }
    
    // Force a small delay to ensure UI updates
    setTimeout(() => {
      if (isProcessing.value) {
        console.log('Force resetting processing state again');
        isProcessing.value = false;
      }
    }, 100);
  }
};

// Force reset processing state
const forceResetProcessing = () => {
  console.log('Force resetting processing state due to user cancellation');
  
  // Reset all processing states
  isProcessing.value = false;
  isSearching.value = false;
  isCheckingSku.value = false;
  showConfirmModal.value = false;
  
  // Clear any pending timeouts
  if (checkSkuTimeout.value) {
    clearTimeout(checkSkuTimeout.value);
    checkSkuTimeout.value = null;
  }
  
  // Clear processing start time
  if (window.processingStartTime) {
    delete window.processingStartTime;
  }
  
  toast.info('Update process cancelled. You can try again.');
};
</script>

<style scoped>
/* Base Button Styles from Location.vue */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Specific to AmendSku for warning/danger buttons */
.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
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

/* Detail display items */
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