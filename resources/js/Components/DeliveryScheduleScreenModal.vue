<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header with toolbar icons -->
        <div class="px-6 py-3 border-b border-gray-200 bg-gray-100">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Delivery Schedule Screen</h3>
            <div class="flex items-center space-x-2">
              <!-- Toolbar icons -->
              <button class="p-1 hover:bg-gray-200 rounded" title="Stop">
                <div class="w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-stop text-white text-xs"></i>
                </div>
              </button>
              <button class="p-1 hover:bg-gray-200 rounded" title="Close">
                <div class="w-4 h-4 bg-gray-400 flex items-center justify-center">
                  <span class="text-white text-xs font-bold">×</span>
                </div>
              </button>
              <button class="p-1 hover:bg-gray-200 rounded" title="Minimize">
                <div class="w-4 h-4 bg-blue-500 flex items-center justify-center">
                  <span class="text-white text-xs">_</span>
                </div>
              </button>
              <button class="p-1 hover:bg-gray-200 rounded" title="Edit">
                <div class="w-4 h-4 bg-yellow-500 flex items-center justify-center">
                  <i class="fas fa-edit text-white text-xs"></i>
                </div>
              </button>
              <button class="p-1 hover:bg-gray-200 rounded" title="Add">
                <div class="w-4 h-4 bg-green-500 flex items-center justify-center">
                  <i class="fas fa-plus text-white text-xs"></i>
                </div>
              </button>
              <button 
                @click="$emit('close')"
                class="text-gray-400 hover:text-gray-600 transition-colors ml-4"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
          <!-- Schedule Table -->
          <div class="mb-6">
            <div class="border border-gray-400">
              <!-- Table Header -->
              <div class="bg-teal-600 text-white">
                <div class="grid grid-cols-14 text-xs font-bold">
                  <div class="px-2 py-2 border-r border-teal-500">No.</div>
                  <div class="px-2 py-2 border-r border-teal-500">Set</div>
                  <div class="px-2 py-2 border-r border-teal-500">Main</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 1</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 2</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 3</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 4</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 5</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 6</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 7</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 8</div>
                  <div class="px-2 py-2 border-r border-teal-500">Fit 9</div>
                  <div class="px-2 py-2 border-r border-teal-500">Schedule Date</div>
                  <div class="px-2 py-2 border-r border-teal-500">Time</div>
                  <div class="px-2 py-2 border-r border-teal-500">Due</div>
                  <div class="px-2 py-2">Remark</div>
                </div>
              </div>
              
              <!-- Table Body - Yellow highlighted row -->
              <div class="bg-yellow-300">
                <div class="grid grid-cols-14 text-xs">
                  <div class="px-2 py-2 border-r border-gray-300">0001</div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2 border-r border-gray-300"></div>
                  <div class="px-2 py-2"></div>
                </div>
              </div>
              
              <!-- Additional empty rows -->
              <div class="bg-black h-32"></div>
            </div>
          </div>

          <!-- Bottom Form Section -->
          <div class="grid grid-cols-12 gap-4 text-sm">
            <!-- Left side - Entry fields -->
            <div class="col-span-8">
              <div class="grid grid-cols-12 gap-2 mb-4">
                <!-- Row 1 -->
                <div class="flex items-center">
                  <label class="text-xs font-medium">No.</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Set</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Main</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 1</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 2</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 3</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 4</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 5</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 6</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 7</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 8</label>
                </div>
                <div class="flex items-center">
                  <label class="text-xs font-medium">Fit 9</label>
                </div>
              </div>
              
              <div class="grid grid-cols-12 gap-2 mb-4">
                <!-- Row 2 - Input fields -->
                <div>
                  <input v-model="scheduleForm.no" type="text" value="1" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.set" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.main" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit1" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit2" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit3" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit4" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit5" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit6" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit7" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit8" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
                <div>
                  <input v-model="scheduleForm.fit9" type="text" class="w-full px-2 py-1 border border-gray-400 text-xs">
                </div>
              </div>
              
              <!-- Entry Total and Order Total -->
              <div class="grid grid-cols-12 gap-2 mb-2">
                <div class="col-span-2">
                  <label class="text-xs font-medium">Entry Total:</label>
                </div>
                <div class="col-span-10">
                  <div class="grid grid-cols-10 gap-2">
                    <input v-model="scheduleForm.entryTotal.set" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.main" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit1" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit2" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit3" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit4" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit5" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit6" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit7" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.entryTotal.fit8" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                  </div>
                </div>
              </div>
              
              <div class="grid grid-cols-12 gap-2">
                <div class="col-span-2">
                  <label class="text-xs font-medium">Order Total:</label>
                </div>
                <div class="col-span-10">
                  <div class="grid grid-cols-10 gap-2">
                    <input v-model="scheduleForm.orderTotal.set" type="text" value="100" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.main" type="text" value="100" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit1" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit2" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit3" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit4" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit5" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit6" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit7" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                    <input v-model="scheduleForm.orderTotal.fit8" type="text" class="px-2 py-1 border border-gray-400 text-xs">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right side - Date, Time, Due, Remarks -->
            <div class="col-span-4">
              <div class="space-y-4">
                <!-- Date -->
                <div class="flex items-center space-x-2">
                  <label class="text-xs font-medium w-12">Date:</label>
                  <input v-model="scheduleForm.date" type="text" value="00/00/0000" class="px-2 py-1 border border-gray-400 text-xs w-24">
                </div>
                
                <!-- Time -->
                <div class="flex items-center space-x-2">
                  <label class="text-xs font-medium w-12">Time:</label>
                  <input 
                    v-model="scheduleForm.time.hours" 
                    type="number" 
                    min="0" 
                    max="23" 
                    placeholder="HH"
                    class="px-2 py-1 border border-gray-400 text-xs w-12 text-center"
                    @blur="validateHours"
                  >
                  <span class="text-xs font-bold">:</span>
                  <input 
                    v-model="scheduleForm.time.minutes" 
                    type="number" 
                    min="0" 
                    max="59" 
                    placeholder="MM"
                    class="px-2 py-1 border border-gray-400 text-xs w-12 text-center"
                    @blur="validateMinutes"
                  >
                  <span class="text-xs text-gray-500 ml-2">(24h)</span>
                </div>
                
                <!-- Due -->
                <div class="flex items-center space-x-2">
                  <label class="text-xs font-medium w-12">Due:</label>
                  <select v-model="scheduleForm.due" class="px-2 py-1 border border-gray-400 text-xs">
                    <option value="ETD">ETD</option>
                    <option value="ETA">ETA</option>
                    <option value="TBA">TBA</option>
                  </select>
                </div>
                
                <!-- Remarks -->
                <div>
                  <label class="block text-xs font-medium mb-1">Remarks:</label>
                  <textarea v-model="scheduleForm.remarks" rows="3" class="w-full px-2 py-1 border border-gray-400 text-xs"></textarea>
                </div>
                
                <!-- Due Legend -->
                <div class="text-xs text-gray-600 mt-4">
                  <div>Due:</div>
                  <div>ETD: Expected Delivery Date</div>
                  <div>ETA: Expected Arrival Date</div>
                  <div>TBA: To be Advised</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="saveSchedule"
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
          >
            OK
          </button>
          <button 
            @click="$emit('close')"
            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  deliveryData: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Form data
const scheduleForm = reactive({
  no: '1',
  set: '',
  main: '',
  fit1: '',
  fit2: '',
  fit3: '',
  fit4: '',
  fit5: '',
  fit6: '',
  fit7: '',
  fit8: '',
  fit9: '',
  date: '00/00/0000',
  time: {
    hours: '09',
    minutes: '00'
  },
  due: 'ETD',
  remarks: '',
  entryTotal: {
    set: '',
    main: '',
    fit1: '',
    fit2: '',
    fit3: '',
    fit4: '',
    fit5: '',
    fit6: '',
    fit7: '',
    fit8: ''
  },
  orderTotal: {
    set: '100',
    main: '100',
    fit1: '',
    fit2: '',
    fit3: '',
    fit4: '',
    fit5: '',
    fit6: '',
    fit7: '',
    fit8: ''
  }
})

// Methods
const validateHours = () => {
  let hours = parseInt(scheduleForm.time.hours)
  if (isNaN(hours) || hours < 0) {
    scheduleForm.time.hours = '0'
  } else if (hours > 23) {
    scheduleForm.time.hours = '23'
  } else {
    // Pad with leading zero if needed
    scheduleForm.time.hours = hours.toString().padStart(2, '0')
  }
}

const validateMinutes = () => {
  let minutes = parseInt(scheduleForm.time.minutes)
  if (isNaN(minutes) || minutes < 0) {
    scheduleForm.time.minutes = '0'
  } else if (minutes > 59) {
    scheduleForm.time.minutes = '59'
  } else {
    // Pad with leading zero if needed
    scheduleForm.time.minutes = minutes.toString().padStart(2, '0')
  }
}

const saveSchedule = () => {
  // Validate required fields
  if (!scheduleForm.date || scheduleForm.date === '00/00/0000') {
    error('Please select a delivery date')
    return
  }

  // Validate time format (24-hour)
  const hours = parseInt(scheduleForm.time.hours)
  const minutes = parseInt(scheduleForm.time.minutes)
  if (isNaN(hours) || hours < 0 || hours > 23) {
    error('Invalid hour. Please enter 0-23 for 24-hour format')
    return
  }
  if (isNaN(minutes) || minutes < 0 || minutes > 59) {
    error('Invalid minutes. Please enter 0-59')
    return
  }

  const scheduleData = {
    ...scheduleForm,
    deliveryData: props.deliveryData
  }

  emit('save', scheduleData)
  success('Delivery schedule saved successfully')
}
</script>

<style scoped>
/* Custom grid for 14 columns */
.grid-cols-14 {
  grid-template-columns: repeat(14, minmax(0, 1fr));
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
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
input:focus,
textarea:focus,
select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
