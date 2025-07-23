<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full animate-modalScaleIn flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 relative flex-shrink-0 rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i class="fas fa-table text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">
              SKU Search Results
            </h3>
          </div>
          <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="bg-white px-6 py-4 flex-grow overflow-y-auto max-h-[60vh]">
          <div class="mb-4 flex justify-between items-center">
            <div class="flex items-center">
              <span class="text-sm text-gray-600 font-medium mr-2">
                {{ skus.length }} SKUs found
              </span>
              <span v-if="action" class="ml-4 px-3 py-1 rounded-full text-xs font-medium" 
                :class="action === 'obsolete' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                Action: {{ action === 'obsolete' ? 'Make Obsolete' : 'Reactivate' }}
              </span>
            </div>
            <div class="flex items-center space-x-2">
              <span v-if="selectedSkus.length > 0" class="text-sm text-blue-600 font-medium">
                {{ selectedSkus.length }} SKU(s) selected
              </span>
              <button v-if="selectedSkus.length > 0 && action" @click="confirmSelection" class="btn-blue py-2 px-4">
                <i class="fas fa-check mr-2"></i>
                {{ action === 'obsolete' ? 'Make Obsolete' : 'Reactivate' }}
              </button>
            </div>
          </div>

          <!-- Results Table -->
          <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="w-12 px-4 py-3">
                      <input type="checkbox" v-model="selectAll" 
                        @click="toggleSelectAll"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STS</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAT</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UOM</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">BOH</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">FPO</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ROL</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">
                      <i class="fas fa-spinner fa-spin mr-2"></i> Loading SKUs...
                    </td>
                  </tr>
                  <tr v-else-if="skus.length === 0" class="hover:bg-gray-50">
                    <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">
                      No SKUs found. Try adjusting your search criteria.
                    </td>
                  </tr>
                  <template v-else>
                    <tr v-for="sku in skus" :key="sku.sku" class="hover:bg-gray-50" :class="{'bg-blue-50': isSelected(sku)}">
                      <td class="px-4 py-2 whitespace-nowrap">
                        <input type="checkbox" :value="sku" v-model="selectedSkus" 
                          :disabled="(action === 'obsolete' && !sku.is_active) || (action === 'reactivate' && sku.is_active)"
                          class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                      </td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm">
                        <span :class="sku.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                          {{ sku.sts }}
                        </span>
                      </td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.sku_name }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.category_code }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.type || 'NS' }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ sku.uom || 'PCS' }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.boh) }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.fpo) }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">{{ formatNumber(sku.rol) }}</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center flex-shrink-0 rounded-b-lg">
          <div class="flex space-x-3">
            <button @click="handleNewSearch" class="btn-info py-2">
              <i class="fas fa-search mr-2"></i> New Search
            </button>
            <button @click="$emit('print')" class="btn-secondary py-2">
              <i class="fas fa-print mr-2"></i> Print
            </button>
          </div>
          <div class="flex space-x-3">
            <button @click="$emit('view-balance', selectedSkus[0])" :disabled="!selectedSkus.length" 
              :class="{'opacity-50 cursor-not-allowed': !selectedSkus.length}" class="btn-secondary py-2">
              <i class="fas fa-balance-scale mr-2"></i> SKU Balance
            </button>
            <button @click="$emit('view-log', selectedSkus[0])" :disabled="!selectedSkus.length" 
              :class="{'opacity-50 cursor-not-allowed': !selectedSkus.length}" class="btn-secondary py-2">
              <i class="fas fa-history mr-2"></i> Process Log
            </button>
            <button @click="closeModal" class="btn-gray py-2">
              <i class="fas fa-times mr-2"></i> Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  skus: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  action: {
    type: String,
    default: '' // 'obsolete', 'reactivate', or '' for view-only
  }
});

const emit = defineEmits(['close', 'new-search', 'select', 'print', 'view-balance', 'view-log']);

// State
const selectedSkus = ref([]);
const selectAll = ref(false);

// Reset selections when modal opens/closes or skus change
watch(() => [props.show, props.skus], () => {
  selectedSkus.value = [];
  selectAll.value = false;
}, { deep: true });

// Check if an SKU is selected
const isSelected = (sku) => {
  return selectedSkus.value.some(selected => selected.sku === sku.sku);
};

// Toggle select all
const toggleSelectAll = () => {
  if (selectAll.value) {
    // Filter skus based on the current action to only select items that can be toggled
    if (props.action === 'obsolete') {
      selectedSkus.value = props.skus.filter(sku => sku.is_active);
    } else if (props.action === 'reactivate') {
      selectedSkus.value = props.skus.filter(sku => !sku.is_active);
    } else {
      selectedSkus.value = [...props.skus];
    }
  } else {
    selectedSkus.value = [];
  }
};

// Watch skus prop to update selectAll state
watch(() => props.skus, () => {
  // Update selectAll based on whether all toggleable skus are selected
  if (props.skus.length > 0) {
    if (props.action === 'obsolete') {
      const toggleableSkus = props.skus.filter(sku => sku.is_active);
      selectAll.value = toggleableSkus.length > 0 && selectedSkus.value.length === toggleableSkus.length;
    } else if (props.action === 'reactivate') {
      const toggleableSkus = props.skus.filter(sku => !sku.is_active);
      selectAll.value = toggleableSkus.length > 0 && selectedSkus.value.length === toggleableSkus.length;
    } else {
      selectAll.value = selectedSkus.value.length === props.skus.length;
    }
  } else {
    selectAll.value = false;
  }
}, { deep: true });

// Confirm selection
const confirmSelection = () => {
  if (selectedSkus.value.length > 0) {
    emit('select', selectedSkus.value);
  }
};

// Start a new search
const handleNewSearch = () => {
  emit('new-search');
};

// Utility functions
const formatNumber = (value) => {
  if (value === null || value === undefined) return '';
  return Number(value).toLocaleString(undefined, {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  });
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

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-gray {
  @apply bg-gray-400 hover:bg-gray-500 text-white px-4 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}
</style> 