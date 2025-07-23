<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="flex flex-col align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full animate-modalScaleIn max-h-[90vh]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 relative flex-shrink-0 rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i class="fas fa-balance-scale text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">
              SKU Balance: {{ sku ? sku.sku : 'Loading...' }}
            </h3>
          </div>
          <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="bg-white px-6 py-4 flex-grow overflow-y-auto max-h-[70vh]">
          <!-- SKU Information Panel -->
          <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h4 class="text-lg font-semibold mb-2">SKU Information</h4>
                <div class="grid grid-cols-1 gap-2">
                  <div class="flex">
                    <span class="font-medium w-32">SKU#:</span>
                    <span>{{ sku ? sku.sku : '—' }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">Name:</span>
                    <span>{{ sku ? sku.sku_name : '—' }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">Category:</span>
                    <span>{{ sku ? sku.category_code : '—' }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">Status:</span>
                    <span :class="sku && sku.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                      {{ sku ? (sku.is_active ? 'ACT' : 'OBS') : '—' }}
                    </span>
                  </div>
                </div>
              </div>
              <div>
                <h4 class="text-lg font-semibold mb-2">Stock Details</h4>
                <div class="grid grid-cols-1 gap-2">
                  <div class="flex">
                    <span class="font-medium w-32">BOH:</span>
                    <span>{{ formatNumber(sku ? sku.boh : 0) }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">FPO:</span>
                    <span>{{ formatNumber(sku ? sku.fpo : 0) }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">ROL:</span>
                    <span>{{ formatNumber(sku ? sku.rol : 0) }}</span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">UOM:</span>
                    <span>{{ sku ? sku.uom : '—' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Balance Table -->
          <h4 class="text-lg font-semibold mb-3">SKU Balance Log</h4>
          <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note#</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref Date</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference No.</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STS</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance Qty</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading">
                  <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Loading SKU balance data...
                  </td>
                </tr>
                <tr v-else-if="balanceItems.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    No balance data available for this SKU.
                  </td>
                </tr>
                <template v-else>
                  <tr v-for="(item, index) in balanceItems" :key="index" class="hover:bg-gray-50">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ item.note }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ item.refDate }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ item.refNo }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ item.status }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-right text-gray-500">{{ formatNumber(item.balanceQty) }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ item.location }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          
          <!-- Transaction Details Section -->
          <div class="mt-6">
            <h4 class="text-lg font-semibold mb-3">In/Out Transactions</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex mb-2">
                  <span class="font-medium w-28">In Qty:</span>
                  <span class="text-blue-600 font-semibold">{{ formatNumber(0.000) }}</span>
                </div>
                <div class="flex">
                  <span class="font-medium w-28">Out Qty:</span>
                  <span class="text-red-600 font-semibold">{{ formatNumber(0.000) }}</span>
                </div>
              </div>
              
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex">
                  <span class="font-medium w-28">Mfg Date:</span>
                  <span>00/00/0000</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3 flex-shrink-0 rounded-b-lg">
          <button @click="printBalance" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center">
            <i class="fas fa-print mr-2"></i> Print
          </button>
          <button @click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors">
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  sku: {
    type: Object,
    default: null,
  }
});

const emit = defineEmits(['close']);

// State
const loading = ref(false); // Changed to false initially as data will be fetched on show
const balanceItems = ref([]);

// Watch for changes in the 'show' prop to fetch data when the modal opens
watch(() => props.show, (newVal) => {
  if (newVal && props.sku) {
    fetchBalanceData();
  } else if (!newVal) {
    // Clear data when modal is closed
    balanceItems.value = [];
  }
});

const fetchBalanceData = async () => {
  loading.value = true;
  balanceItems.value = []; // Clear previous data
  try {
    if (!props.sku || !props.sku.sku) {
      console.warn('SKU object or SKU ID is missing, cannot fetch balance data.');
      return;
    }
    const response = await axios.get(`/api/material-management/skus/${props.sku.sku}/balance`);
    balanceItems.value = response.data;
  } catch (error) {
    console.error('Error fetching SKU balance:', error);
    // Optionally show a toast error here
    balanceItems.value = [];
  } finally {
    loading.value = false;
  }
};

// Utility functions
const formatNumber = (value) => {
  if (value === null || value === undefined) return '';
  return Number(value).toLocaleString(undefined, {
    minimumFractionDigits: 3,
    maximumFractionDigits: 3
  });
};

const printBalance = () => {
  console.log('Printing SKU balance for:', props.sku?.sku);
  alert('Print functionality would be implemented here');
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