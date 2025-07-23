<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="flex flex-col align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full animate-modalScaleIn max-h-[90vh]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 relative flex-shrink-0 rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i class="fas fa-history text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">
              SKU Process Log: {{ sku ? sku.sku : 'Loading...' }}
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
                </div>
              </div>
              <div>
                <h4 class="text-lg font-semibold mb-2">Current Status</h4>
                <div class="grid grid-cols-1 gap-2">
                  <div class="flex">
                    <span class="font-medium w-32">Status:</span>
                    <span :class="sku && sku.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                      {{ sku ? (sku.is_active ? 'Active' : 'Obsolete') : '—' }}
                    </span>
                  </div>
                  <div class="flex">
                    <span class="font-medium w-32">Last Updated:</span>
                    <span>{{ sku && sku.updated_at ? formatDate(sku.updated_at) : '—' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Process Log Table -->
          <h4 class="text-lg font-semibold mb-3">Process History</h4>
          <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading">
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Loading process log data...
                  </td>
                </tr>
                <tr v-else-if="processLogs.length === 0">
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    No process log data available for this SKU.
                  </td>
                </tr>
                <template v-else>
                  <tr v-for="(log, index) in processLogs" :key="index" class="hover:bg-gray-50">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ log.date }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ log.time }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ log.userId }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                      <span :class="{
                        'text-green-600 font-medium': log.process === 'New',
                        'text-blue-600 font-medium': log.process === 'Amend',
                        'text-red-600 font-medium': log.process === 'Obsolete',
                        'text-purple-600 font-medium': log.process === 'Reactivate'
                      }">{{ log.process }}</span>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          
          <!-- Change Details Section -->
          <div class="mt-6">
            <h4 class="text-lg font-semibold mb-3">Change Details</h4>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
              <div class="flex flex-col space-y-2">
                <div class="flex">
                  <span class="font-medium w-32">Selected Log:</span>
                  <span class="text-blue-600">
                    <template v-if="selectedLog">{{ selectedLog.date }} {{ selectedLog.time }} by {{ selectedLog.userId }}</template>
                    <template v-else>No log selected</template>
                  </span>
                </div>
                <div class="flex">
                  <span class="font-medium w-32">Description:</span>
                  <span>{{ selectedLog ? selectedLog.description : '—' }}</span>
                </div>
                <div class="flex">
                  <span class="font-medium w-32">Changes:</span>
                  <span>{{ selectedLog ? selectedLog.changes : '—' }}</span>
                </div>
                <div class="flex">
                  <span class="font-medium w-32">Reason:</span>
                  <span>{{ selectedLog ? selectedLog.reason : '—' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3 flex-shrink-0 rounded-b-lg">
          <button @click="printLog" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center">
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
import { ref, onMounted } from 'vue';

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
const loading = ref(true);
const processLogs = ref([]);
const selectedLog = ref(null);

// Mock data (in a real app this would come from an API)
const mockProcessLogs = [
  { 
    date: '03/19/2021',
    time: '15:44:37',
    userId: 'msi02',
    process: 'New',
    description: 'Created new SKU record',
    changes: 'Initial creation',
    reason: 'New product introduction'
  },
  { 
    date: '13/12/2020',
    time: '15:07:13',
    userId: 'msi02',
    process: 'Amend',
    description: 'Updated SKU details',
    changes: 'Modified description and category',
    reason: 'Product reclassification'
  },
  { 
    date: '09/03/2021',
    time: '16:59:37',
    userId: 'msi04',
    process: 'Amend',
    description: 'Updated stock parameters',
    changes: 'Changed BOH and ROL values',
    reason: 'Inventory adjustment'
  }
];

// Fetch process log data when the modal is opened
onMounted(() => {
  if (props.show && props.sku) {
    fetchProcessLogData();
  }
});

const fetchProcessLogData = async () => {
  loading.value = true;
  try {
    // In a real application, you would fetch data from an API
    // const response = await axios.get(`/api/material-management/skus/${props.sku.sku}/process-log`);
    // processLogs.value = response.data;
    
    // Simulate API delay with mock data
    setTimeout(() => {
      processLogs.value = mockProcessLogs;
      if (mockProcessLogs.length > 0) {
        selectedLog.value = mockProcessLogs[0]; // Select first log by default
      }
      loading.value = false;
    }, 800);
  } catch (error) {
    console.error('Error fetching SKU process log:', error);
    processLogs.value = [];
  } finally {
    loading.value = false;
  }
};

// Utility functions
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
};

const printLog = () => {
  // In a real app, this would open a print dialog or generate a PDF
  console.log('Printing SKU process log for:', props.sku?.sku);
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