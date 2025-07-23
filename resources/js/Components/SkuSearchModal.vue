<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full animate-modalScaleIn flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-800 to-indigo-700 px-6 py-4 relative flex-shrink-0 rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i class="fas fa-search text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">
              Search SKUs
            </h3>
          </div>
          <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="bg-white px-6 py-4">
          <!-- Search Options -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SKU#</label>
              <div class="flex">
                <input type="text" v-model="searchParams.sku" placeholder="Enter SKU code" 
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button @click="openSkuLookupModal" class="bg-blue-600 text-white px-3 py-2 rounded-r-md hover:bg-blue-700 transition">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select v-model="searchParams.category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                <option v-for="category in categories" :key="category.code" :value="category.code">
                  {{ category.code }} - {{ category.name }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                  <input type="radio" v-model="searchParams.status" value="all" class="form-radio h-4 w-4 text-blue-600">
                  <span class="ml-2 text-sm text-gray-700">All</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" v-model="searchParams.status" value="active" class="form-radio h-4 w-4 text-blue-600">
                  <span class="ml-2 text-sm text-gray-700">Active Only</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" v-model="searchParams.status" value="obsolete" class="form-radio h-4 w-4 text-blue-600">
                  <span class="ml-2 text-sm text-gray-700">Obsolete Only</span>
                </label>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <div class="flex flex-wrap gap-4">
                <label class="inline-flex items-center">
                  <input type="checkbox" v-model="recordFilters.stockItem" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                  <span class="ml-2 text-sm text-gray-700">Stock Item</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" v-model="recordFilters.nonStock" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                  <span class="ml-2 text-sm text-gray-700">Non Stock</span>
                </label>
              </div>
            </div>

            <!-- Action Section -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h4 class="font-medium text-gray-700 mb-3">Action to Perform</h4>
              <div class="flex flex-col sm:flex-row gap-4">
                <label class="action-radio" :class="{ 'action-radio-obsolete-active': action === 'obsolete' }">
                  <input type="radio" v-model="action" value="obsolete" name="action-radio" class="hidden">
                  <i class="fas fa-ban w-6 text-xl" :class="action === 'obsolete' ? 'text-red-500' : 'text-gray-400'"></i>
                  <span class="font-bold" :class="action === 'obsolete' ? 'text-red-800' : 'text-gray-700'">Make Obsolete</span>
                </label>
                <label class="action-radio" :class="{ 'action-radio-reactivate-active': action === 'reactivate' }">
                  <input type="radio" v-model="action" value="reactivate" name="action-radio" class="hidden">
                  <i class="fas fa-check-circle w-6 text-xl" :class="action === 'reactivate' ? 'text-green-500' : 'text-gray-400'"></i>
                  <span class="font-bold" :class="action === 'reactivate' ? 'text-green-800' : 'text-gray-700'">Reactivate</span>
                </label>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Status Change <span class="text-red-500">*</span></label>
              <textarea v-model="reason" rows="2" placeholder="Enter the reason for changing SKU status" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3 flex-shrink-0 rounded-b-lg">
          <button @click="handleSearch" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors flex items-center">
            <i class="fas fa-search mr-2"></i> Search
          </button>
          <button @click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition-colors">
            Cancel
          </button>
        </div>
      </div>
    </div>
    
    <!-- Nested SKU Lookup Modal -->
    <SkuLookupModal 
      :show="showSkuLookupModal" 
      @close="closeSkuLookupModal"
      @select="handleSkuSelected" />
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import SkuLookupModal from '@/Components/SkuLookupModal.vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  categories: {
    type: Array,
    required: true
  },
  initialSearchParams: {
    type: Object,
    default: () => ({
      sku: '',
      category: '',
      status: 'all' // all, active, obsolete
    })
  },
  initialAction: {
    type: String,
    default: 'obsolete' // obsolete, reactivate
  },
  initialReason: {
    type: String,
    default: ''
  },
  initialRecordFilters: {
    type: Object,
    default: () => ({
      active: true,
      obsolete: true,
      stockItem: true,
      nonStock: true
    })
  }
});

const emit = defineEmits(['close', 'search', 'sku-lookup']);

// State
const searchParams = ref({...props.initialSearchParams});
const action = ref(props.initialAction);
const reason = ref(props.initialReason);
const recordFilters = ref({...props.initialRecordFilters});
const showSkuLookupModal = ref(false);

// Reset form values when the modal is opened
watch(() => props.show, (newVal) => {
  if (newVal) {
    searchParams.value = {...props.initialSearchParams};
    action.value = props.initialAction;
    reason.value = props.initialReason;
    recordFilters.value = {...props.initialRecordFilters};
  }
});

// SKU Lookup Modal handlers
const openSkuLookupModal = () => {
  showSkuLookupModal.value = true;
};

const closeSkuLookupModal = () => {
  showSkuLookupModal.value = false;
};

const handleSkuSelected = (sku) => {
  searchParams.value.sku = sku.sku;
  closeSkuLookupModal();
};

// Search handler
const handleSearch = () => {
  // Validate inputs based on action
  if (action.value === 'obsolete' || action.value === 'reactivate') {
    if (!reason.value.trim()) {
      alert('Please provide a reason for status change');
      return;
    }
    
    // Validate reason length
    if (reason.value.trim().length < 5) {
      alert('Please provide a more detailed reason (at least 5 characters)');
      return;
    }
  }
  
  // Validate that at least one record filter is selected
  if (!recordFilters.value.stockItem && !recordFilters.value.nonStock) {
    alert('Please select at least one record type filter (Stock Item or Non Stock)');
    return;
  }
  
  emit('search', {
    searchParams: searchParams.value,
    action: action.value,
    reason: reason.value,
    recordFilters: recordFilters.value
  });
  
  closeModal();
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
@keyframes modalScaleIn {
  from { transform: scale(0.95); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

.animate-modalScaleIn {
  animation: modalScaleIn 0.3s ease-out forwards;
}

/* Action radio buttons */
.action-radio {
  @apply flex items-center space-x-2 p-3 rounded-lg border cursor-pointer transition duration-200 bg-white;
}

.action-radio-obsolete-active {
  @apply border-red-300 bg-red-50 shadow-sm;
}

.action-radio-reactivate-active {
  @apply border-green-300 bg-green-50 shadow-sm;
}
</style> 