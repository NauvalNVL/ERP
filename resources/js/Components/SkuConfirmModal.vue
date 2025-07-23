<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn">
        <!-- Header -->
        <div :class="action === 'obsolete' ? 'bg-gradient-to-r from-red-600 to-red-700' : 'bg-gradient-to-r from-green-600 to-green-700'" class="px-6 py-4 relative flex-shrink-0 rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i :class="action === 'obsolete' ? 'fas fa-ban' : 'fas fa-check-circle'" class="text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">
              {{ action === 'obsolete' ? 'Confirm Making SKUs Obsolete' : 'Confirm Reactivating SKUs' }}
            </h3>
          </div>
          <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="bg-white px-6 py-4">
          <div class="mb-4 text-sm text-gray-600">
            <p>You are about to {{ action === 'obsolete' ? 'make obsolete' : 'reactivate' }} the following {{ skus.length }} SKU(s):</p>
            <div class="max-h-40 overflow-y-auto mt-2 border border-gray-200 rounded bg-gray-50 p-2">
              <ul class="list-disc list-inside">
                <li v-for="sku in displaySkus" :key="sku.sku" class="text-gray-700">
                  {{ sku.sku }} - {{ sku.sku_name }}
                </li>
                <li v-if="skus.length > maxSkusToDisplay" class="text-gray-500 italic">
                  ...and {{ skus.length - maxSkusToDisplay }} more
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Warning for SKUs with inventory balance -->
          <div v-if="action === 'obsolete' && skusWithInventory.length > 0" class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded-md">
            <p class="text-yellow-800 font-medium">
              <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
              Warning: {{ skusWithInventory.length }} SKU(s) have inventory balance
            </p>
            <p class="text-sm text-yellow-700 mt-1">
              Making SKUs with inventory balance obsolete may affect inventory reports and transactions.
            </p>
          </div>
          
          <div class="mb-4">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
            <textarea id="reason" v-model="mutableReason"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              rows="3" placeholder="Enter reason for status change..."></textarea>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3 flex-shrink-0 rounded-b-lg">
          <button @click="confirm" 
            :class="action === 'obsolete' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
            class="text-white font-bold py-2 px-6 rounded-lg transition-colors">
            {{ action === 'obsolete' ? 'Make Obsolete' : 'Reactivate' }}
          </button>
          <button @click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  skus: {
    type: Array,
    default: () => []
  },
  action: {
    type: String,
    default: 'obsolete' // 'obsolete' or 'reactivate'
  },
  modelValue: {
    type: String,
    default: ''
  },
  maxSkusToDisplay: {
    type: Number,
    default: 10
  }
});

const emit = defineEmits(['close', 'confirm', 'update:modelValue']);

const mutableReason = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
  mutableReason.value = newValue;
});

watch(mutableReason, (newValue) => {
  emit('update:modelValue', newValue);
});

// Computed properties
const displaySkus = computed(() => {
  return props.skus.slice(0, props.maxSkusToDisplay);
});

// Identify SKUs with inventory balance
const skusWithInventory = computed(() => {
  return props.skus.filter(sku => {
    const boh = parseFloat(sku.boh || 0);
    return boh > 0;
  });
});

// Methods
const confirm = () => {
  if (props.action === 'obsolete' && skusWithInventory.value.length > 0) {
    if (!confirm(`WARNING: ${skusWithInventory.value.length} SKU(s) have inventory balance. Are you sure you want to make them obsolete?`)) {
      return;
    }
  }
  
  emit('confirm', {
    skus: props.skus,
    action: props.action,
    reason: mutableReason.value // Use mutableReason for the emitted value
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
</style> 