<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-chart-line mr-2 text-orange-600"></i>
              Rough Cut Capacity Check
            </h3>
            <div class="flex items-center space-x-2">
              <button 
                @click="refreshData"
                class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                title="Refresh Data"
              >
                <i class="fas fa-sync-alt"></i>
              </button>
              <button 
                @click="$emit('close')"
                class="text-gray-400 hover:text-gray-600 transition-colors"
                title="Close"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <!-- Input Fields -->
          <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Entry Date:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="entryDate" 
                    type="date"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  >
                  <button 
                    @click="openCalendar"
                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    title="Calendar"
                  >
                    <i class="fas fa-calendar-alt text-sm"></i>
                  </button>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Entry LM:</label>
                <input 
                  v-model="entryLM" 
                  type="number"
                  min="0"
                  step="0.01"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  placeholder="Enter LM value"
                >
              </div>
            </div>
          </div>

          <!-- Capacity Table -->
          <div class="mb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
              <!-- Table Header -->
              <div class="grid grid-cols-6 bg-orange-100 border-b border-gray-200 text-xs font-medium text-gray-600">
                <div class="px-3 py-3 border-r border-gray-200 text-center">Due Date</div>
                <div class="px-3 py-3 border-r border-gray-200 text-center">Target LM</div>
                <div class="px-3 py-3 border-r border-gray-200 text-center">Loaded LM</div>
                <div class="px-3 py-3 border-r border-gray-200 text-center">Taken LM</div>
                <div class="px-3 py-3 border-r border-gray-200 text-center">%</div>
                <div class="px-3 py-3 text-center">Free LM</div>
              </div>

              <!-- Capacity Rows -->
              <div 
                v-for="(row, index) in capacityData" 
                :key="index"
                class="grid grid-cols-6 border-b border-gray-200 hover:bg-gray-50"
              >
                <div class="px-3 py-2 border-r border-gray-200 text-sm font-medium">{{ row.dueDate }}</div>
                <div class="px-3 py-2 border-r border-gray-200 text-sm text-right">{{ formatNumber(row.targetLM) }}</div>
                <div class="px-3 py-2 border-r border-gray-200 text-sm text-right">{{ formatNumber(row.loadedLM) }}</div>
                <div class="px-3 py-2 border-r border-gray-200 text-sm text-right">{{ formatNumber(row.takenLM) }}</div>
                <div class="px-3 py-2 border-r border-gray-200 text-sm text-right">
                  <span :class="getPercentageClass(row.percentage)">
                    {{ row.percentage }}%
                  </span>
                </div>
                <div class="px-3 py-2 text-sm text-right">
                  <span :class="getFreeLMClass(row.freeLM)">
                    {{ formatNumber(row.freeLM) }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Information -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Capacity Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ totalTargetLM }}</div>
                <div class="text-xs text-gray-600">Total Target LM</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ totalLoadedLM }}</div>
                <div class="text-xs text-gray-600">Total Loaded LM</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold" :class="totalFreeLM < 0 ? 'text-red-600' : 'text-green-600'">
                  {{ totalFreeLM }}
                </div>
                <div class="text-xs text-gray-600">Total Free LM</div>
              </div>
            </div>
          </div>

          <!-- Capacity Status -->
          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Capacity Status</h4>
            <div class="space-y-2">
              <div v-if="hasOverCapacity" class="flex items-center text-red-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="text-sm font-medium">Warning: Some dates have over-capacity (negative Free LM)</span>
              </div>
              <div v-if="hasFullCapacity" class="flex items-center text-yellow-600">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="text-sm font-medium">Info: Some dates are at full capacity (100%)</span>
              </div>
              <div v-if="hasAvailableCapacity" class="flex items-center text-green-600">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm font-medium">Good: Capacity is available for scheduling</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="$emit('close')"
            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="acceptCapacity"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
            :disabled="hasOverCapacity"
          >
            Accept
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  entryDate: {
    type: String,
    default: () => new Date().toISOString().split('T')[0]
  },
  entryLM: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['close', 'accept'])

const { success, error, info } = useToast()

// Reactive data
const entryDate = ref(props.entryDate)
const entryLM = ref(props.entryLM)

// Sample capacity data (in real implementation, this would come from API)
const capacityData = ref([
  {
    dueDate: '23/09 Tue',
    targetLM: 0.00,
    loadedLM: 489223.00,
    takenLM: 4.00,
    percentage: 100,
    freeLM: -489227.00
  },
  {
    dueDate: '24/09 Wed',
    targetLM: 0.00,
    loadedLM: 340862.00,
    takenLM: 0.00,
    percentage: 100,
    freeLM: -340862.00
  },
  {
    dueDate: '25/09 Thu',
    targetLM: 0.00,
    loadedLM: 199890.00,
    takenLM: 0.00,
    percentage: 100,
    freeLM: -199890.00
  },
  {
    dueDate: '26/09 Fri',
    targetLM: 0.00,
    loadedLM: 150000.00,
    takenLM: 0.00,
    percentage: 100,
    freeLM: -150000.00
  },
  {
    dueDate: '27/09 Sat',
    targetLM: 0.00,
    loadedLM: 0.00,
    takenLM: 0.00,
    percentage: 0,
    freeLM: 0.00
  },
  {
    dueDate: '28/09 Sun',
    targetLM: 0.00,
    loadedLM: 0.00,
    takenLM: 0.00,
    percentage: 0,
    freeLM: 0.00
  },
  {
    dueDate: '29/09 Mon',
    targetLM: 0.00,
    loadedLM: 88505.00,
    takenLM: 0.00,
    percentage: 100,
    freeLM: -88505.00
  },
  {
    dueDate: '30/09 Tue',
    targetLM: 0.00,
    loadedLM: 21628.00,
    takenLM: 0.00,
    percentage: 100,
    freeLM: -21628.00
  }
])

// Computed properties
const totalTargetLM = computed(() => {
  return capacityData.value.reduce((sum, row) => sum + row.targetLM, 0)
})

const totalLoadedLM = computed(() => {
  return capacityData.value.reduce((sum, row) => sum + row.loadedLM, 0)
})

const totalFreeLM = computed(() => {
  return capacityData.value.reduce((sum, row) => sum + row.freeLM, 0)
})

const hasOverCapacity = computed(() => {
  return capacityData.value.some(row => row.freeLM < 0)
})

const hasFullCapacity = computed(() => {
  return capacityData.value.some(row => row.percentage === 100)
})

const hasAvailableCapacity = computed(() => {
  return capacityData.value.some(row => row.freeLM > 0)
})

// Methods
const formatNumber = (number) => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(number)
}

const getPercentageClass = (percentage) => {
  if (percentage === 100) return 'text-red-600 font-bold'
  if (percentage >= 80) return 'text-yellow-600 font-medium'
  return 'text-green-600'
}

const getFreeLMClass = (freeLM) => {
  if (freeLM < 0) return 'text-red-600 font-bold'
  if (freeLM === 0) return 'text-yellow-600 font-medium'
  return 'text-green-600'
}

const openCalendar = () => {
  const dateInput = document.querySelector('input[type="date"]')
  if (dateInput) {
    dateInput.showPicker()
  }
}

const refreshData = async () => {
  try {
    // In real implementation, this would fetch data from API
    info('Refreshing capacity data...')
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    success('Capacity data refreshed successfully')
  } catch (err) {
    error('Error refreshing capacity data')
  }
}

const acceptCapacity = () => {
  if (hasOverCapacity.value) {
    error('Cannot accept capacity with over-capacity dates')
    return
  }
  
  const capacityInfo = {
    entryDate: entryDate.value,
    entryLM: entryLM.value,
    capacityData: capacityData.value,
    totalTargetLM: totalTargetLM.value,
    totalLoadedLM: totalLoadedLM.value,
    totalFreeLM: totalFreeLM.value
  }
  
  emit('accept', capacityInfo)
  success('Capacity check accepted')
}

// Initialize component
onMounted(() => {
  // Initialize with current date if not provided
  if (!entryDate.value) {
    entryDate.value = new Date().toISOString().split('T')[0]
  }
})
</script>

<style scoped>
/* Grid layout for 6 columns */
.grid-cols-6 {
  display: grid;
  grid-template-columns: repeat(6, minmax(0, 1fr));
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Input focus styles */
input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
}

/* Print styles */
@media print {
  .fixed {
    position: static !important;
  }
  
  .shadow-xl {
    box-shadow: none !important;
  }
  
  button {
    display: none !important;
  }
}
</style>
