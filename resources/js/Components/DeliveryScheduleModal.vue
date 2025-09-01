<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
              Delivery Schedule Screen
            </h3>
            <div class="flex items-center space-x-2">
              <button 
                @click="$emit('close')"
                class="text-gray-400 hover:text-gray-600 transition-colors"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
              <button 
                @click="closeWindow"
                class="text-red-400 hover:text-red-600 transition-colors"
                title="Close Window"
              >
                <i class="fas fa-window-close text-xl"></i>
              </button>
              <button 
                @click="refreshScreen"
                class="text-blue-400 hover:text-blue-600 transition-colors"
                title="Refresh Screen"
              >
                <i class="fas fa-sync-alt text-xl"></i>
              </button>
              <button 
                @click="printScreen"
                class="text-green-400 hover:text-green-600 transition-colors"
                title="Print Screen"
              >
                <i class="fas fa-print text-xl"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <!-- Schedule Grid -->
          <div class="mb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
              <!-- Grid Header -->
              <div class="grid grid-cols-16 bg-gray-100 border-b border-gray-200 text-xs font-medium text-gray-600">
                <div class="px-2 py-3 border-r border-gray-200 text-center">No.</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Set</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Main</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 1</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 2</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 3</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 4</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 5</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 6</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 7</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 8</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Fit 9</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Schedule Date</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Time</div>
                <div class="px-2 py-3 border-r border-gray-200 text-center">Due</div>
                <div class="px-2 py-3 text-center">Remark</div>
              </div>

              <!-- Schedule Row -->
              <div class="grid grid-cols-16 bg-yellow-100 border-b border-gray-200">
                <div class="px-2 py-2 border-r border-gray-200 text-sm font-medium">0001</div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2"></div>
              </div>

              <!-- Empty Grid Rows -->
              <div v-for="index in 10" :key="index" class="grid grid-cols-16 border-b border-gray-200 hover:bg-gray-50">
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2 border-r border-gray-200"></div>
                <div class="px-2 py-2"></div>
              </div>
            </div>
          </div>

          <!-- Schedule Details Form -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Left Column -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Schedule Entry</h4>
                <div class="grid grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No.</label>
                    <input 
                      v-model="scheduleEntry.no" 
                      type="text"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Set</label>
                    <input 
                      v-model="scheduleEntry.set" 
                      type="text"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Main</label>
                    <input 
                      v-model="scheduleEntry.main" 
                      type="text"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                  </div>
                </div>

                <!-- Fit columns -->
                <div class="grid grid-cols-3 gap-4 mt-3">
                  <div v-for="i in 9" :key="i">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Fit {{ i }}</label>
                    <input 
                      v-model="scheduleEntry[`fit${i}`]" 
                      type="text"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Schedule Information</h4>
                <div class="space-y-3">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                      <div class="flex items-center space-x-2">
                        <input 
                          v-model="scheduleEntry.date" 
                          type="date"
                          class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        >
                        <button 
                          @click="openCalendar"
                          class="p-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                          title="Calendar"
                        >
                          <i class="fas fa-calendar-alt text-sm"></i>
                        </button>
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Time</label>
                      <input 
                        v-model="scheduleEntry.time" 
                        type="time"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                      >
                    </div>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Due</label>
                    <select 
                      v-model="scheduleEntry.due" 
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                      <option value="ETD">ETD</option>
                      <option value="ETA">ETA</option>
                      <option value="TBA">TBA</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Remark</label>
                    <textarea 
                      v-model="scheduleEntry.remark" 
                      rows="3"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Order Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Entry Total:</span>
                  <span class="font-medium">{{ entryTotal }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Order Total:</span>
                  <span class="font-medium">150</span>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Due Status:</span>
                  <div class="space-x-4">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">ETD: Expected Delivery Date</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">ETA: Expected Arrival Date</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">TBA: To be Advised</span>
                  </div>
                </div>
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
            @click="addScheduleEntry"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
          >
            Add Entry
          </button>
          <button 
            @click="saveSchedule"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors"
          >
            Save Schedule
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  orderDetails: Object
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Schedule entry data
const scheduleEntry = reactive({
  no: '1',
  set: '',
  main: '150',
  fit1: '',
  fit2: '',
  fit3: '',
  fit4: '',
  fit5: '',
  fit6: '',
  fit7: '',
  fit8: '',
  fit9: '',
  date: new Date().toISOString().split('T')[0],
  time: '09:00',
  due: 'ETD',
  remark: ''
})

// Schedule entries list
const scheduleEntries = ref([])

// Computed properties
const entryTotal = computed(() => {
  return scheduleEntries.value.reduce((total, entry) => {
    return total + (parseInt(entry.main) || 0)
  }, 0)
})

// Methods
const closeWindow = () => {
  emit('close')
}

const refreshScreen = () => {
  // Reset form
  Object.keys(scheduleEntry).forEach(key => {
    if (key === 'date') {
      scheduleEntry[key] = new Date().toISOString().split('T')[0]
    } else if (key === 'time') {
      scheduleEntry[key] = '09:00'
    } else if (key === 'due') {
      scheduleEntry[key] = 'ETD'
    } else if (key === 'no') {
      scheduleEntry[key] = '1'
    } else {
      scheduleEntry[key] = ''
    }
  })
  success('Screen refreshed')
}

const printScreen = () => {
  window.print()
}

const openCalendar = () => {
  const dateInput = document.querySelector('input[type="date"]')
  if (dateInput) {
    dateInput.showPicker()
  }
}

const addScheduleEntry = () => {
  if (!scheduleEntry.main) {
    error('Please enter main quantity')
    return
  }

  const newEntry = { ...scheduleEntry }
  scheduleEntries.value.push(newEntry)
  
  // Reset form for next entry
  scheduleEntry.no = (parseInt(scheduleEntry.no) + 1).toString()
  scheduleEntry.main = ''
  
  success('Schedule entry added')
}

const saveSchedule = () => {
  if (scheduleEntries.value.length === 0) {
    error('Please add at least one schedule entry')
    return
  }

  const scheduleData = {
    entries: scheduleEntries.value,
    entryTotal: entryTotal.value,
    orderTotal: 150
  }

  emit('save', scheduleData)
}
</script>

<style scoped>
/* Grid layout for 16 columns */
.grid-cols-16 {
  display: grid;
  grid-template-columns: repeat(16, minmax(0, 1fr));
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
input:focus, select:focus, textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
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
