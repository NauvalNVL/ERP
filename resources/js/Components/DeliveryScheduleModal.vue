<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
              Delivery Schedule Screen
            </h3>
            <div class="flex items-center space-x-2">
              <button 
                @click="saveSchedule"
                class="p-2 text-green-600 hover:bg-green-100 rounded-full transition-colors"
                title="Save (Ctrl+S)"
              >
                <i class="fas fa-save"></i>
              </button>
              <button 
                @click="deleteSelectedEntry"
                class="p-2 text-red-600 hover:bg-red-100 rounded-full transition-colors"
                title="Delete (Del)"
                :disabled="!selectedEntry"
              >
                <i class="fas fa-trash"></i>
              </button>
              <button 
                @click="editSelectedEntry"
                class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                title="Edit (F2)"
                :disabled="!selectedEntry"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button 
                @click="addNewEntry"
                class="p-2 text-green-600 hover:bg-green-100 rounded-full transition-colors"
                title="Add New (F3)"
              >
                <i class="fas fa-plus"></i>
              </button>
              <button 
                @click="refreshScreen"
                class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                title="Refresh (F5)"
              >
                <i class="fas fa-sync-alt"></i>
              </button>
              <button 
                @click="$emit('close')"
                class="text-gray-400 hover:text-gray-600 transition-colors"
                title="Close (Esc)"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-6 overflow-y-auto max-h-[calc(90vh-160px)]">
          <!-- Validation Error Popup -->
          <div v-if="showErrorPopup" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black bg-opacity-40"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-[360px]">
              <div class="px-4 py-3 border-b border-gray-200 flex items-center">
                <span class="text-sm text-gray-500">Error</span>
              </div>
              <div class="p-4 flex space-x-3 items-start">
                <div class="text-red-600 text-2xl leading-none">✖</div>
                <div class="text-sm">
                  <div class="flex space-x-2"><span class="text-gray-600">Code:</span><span class="font-medium">{{ errorInfo.code }}</span></div>
                  <div class="flex space-x-2 mt-1"><span class="text-gray-600">Type:</span><span class="font-medium">{{ errorInfo.type }}</span></div>
                </div>
              </div>
              <div class="px-4 py-3 border-t border-gray-200 flex justify-end">
                <button @click="showErrorPopup = false" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-sm">Close</button>
              </div>
            </div>
          </div>
          <!-- Schedule Grid -->
          <div class="mb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
              <!-- Grid Header -->
              <div class="grid grid-cols-16 bg-yellow-100 border-b border-gray-200 text-xs font-medium text-gray-600">
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

              <!-- Schedule Entries -->
              <div 
                v-for="(entry, index) in scheduleEntries" 
                :key="entry.id ?? index"
                @click="selectEntry(entry, index)"
                :class="[
                  'grid grid-cols-16 border-b border-gray-200 cursor-pointer transition-colors',
                  selectedIndex === index ? 'bg-blue-100' : 'bg-white hover:bg-gray-50'
                ]"
              >
                <div class="px-2 py-2 border-r border-gray-200 text-sm font-medium">{{ entry.no }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.set || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.main || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit1 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit2 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit3 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit4 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit5 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit6 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit7 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit8 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.fit9 || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ formatDate(entry.date) }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.time || '' }}</div>
                <div class="px-2 py-2 border-r border-gray-200 text-sm">{{ entry.due || '' }}</div>
                <div class="px-2 py-2 text-sm">{{ entry.remark || '' }}</div>
              </div>

              <!-- Empty Grid Rows for visual consistency -->
              <div v-for="index in Math.max(0, 10 - scheduleEntries.length)" :key="`empty-${index}`" class="grid grid-cols-16 border-b border-gray-200 hover:bg-gray-50">
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
            <!-- Left Column - Quantity Allocation -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Quantity Allocation</h4>
                <div class="grid grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No.</label>
                    <div 
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-700"
                    >
                      {{ scheduleEntry.no }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Set</label>
                    <input 
                      v-model="scheduleEntry.set" 
                      type="number"
                      min="0"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      @input="calculateTotals"
                    >
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Main</label>
                    <input 
                      v-model="scheduleEntry.main" 
                      type="number"
                      min="0"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                  </div>
                </div>

                <!-- Fit columns -->
                <div class="grid grid-cols-3 gap-4 mt-3">
                  <div v-for="i in 9" :key="i">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Fit {{ i }}</label>
                    <input 
                      v-model="scheduleEntry[`fit${i}`]" 
                      type="number"
                      min="0"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100 text-gray-600"
                      readonly
                      disabled
                      @input="calculateTotals"
                    >
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Schedule Information -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Schedule Information</h4>
                <div class="space-y-3">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                      <input 
                        v-model="scheduleEntry.date" 
                        type="date"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      >
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Time</label>
                      <input 
                        v-model="scheduleEntry.time" 
                        type="time"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      >
                    </div>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Due</label>
                    <select 
                      v-model="scheduleEntry.due" 
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="ETD">ETD - Expected Delivery Date</option>
                      <option value="ETA">ETA - Expected Arrival Date</option>
                      <option value="TBA">TBA - To Be Advised</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Remarks</label>
                    <textarea 
                      v-model="scheduleEntry.remark" 
                      rows="3"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter delivery remarks or special instructions..."
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Order Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-3">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Entry Total:</span>
                  <div class="flex space-x-4">
                    <span class="font-medium">{{ entryTotal.set || 0 }}</span>
                    <span class="font-medium">{{ entryTotal.main || 0 }}</span>
                  </div>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Order Total:</span>
                  <div class="flex space-x-4">
                    <span class="font-medium">{{ orderTotal.set || 0 }}</span>
                    <span class="font-medium">{{ orderTotal.main || 0 }}</span>
                  </div>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Remaining:</span>
                  <div class="flex space-x-4">
                    <span class="font-medium" :class="remaining.set < 0 ? 'text-red-600' : 'text-green-600'">
                      {{ remaining.set }}
                    </span>
                    <span class="font-medium" :class="remaining.main < 0 ? 'text-red-600' : 'text-green-600'">
                      {{ remaining.main }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="space-y-2">
                <div class="text-xs text-gray-500 mb-2">Due Status Legend:</div>
                <div class="space-y-1">
                  <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">ETD</span>
                    <span class="text-xs text-gray-600">Expected Delivery Date</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">ETA</span>
                    <span class="text-xs text-gray-600">Expected Arrival Date</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">TBA</span>
                    <span class="text-xs text-gray-600">To Be Advised</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
          <div class="text-xs text-gray-500">
            <span class="font-medium">Keyboard Shortcuts:</span>
            <span class="ml-2">F2: Edit</span>
            <span class="ml-2">F3: Add New</span>
            <span class="ml-2">Del: Delete</span>
            <span class="ml-2">Ctrl+S: Save</span>
            <span class="ml-2">Esc: Close</span>
          </div>
          <div class="flex space-x-4">
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
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              Save Schedule
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  orderDetails: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save'])

const { success, error, info } = useToast()

// Schedule entry data
const scheduleEntry = reactive({
  id: null,
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
  date: new Date().toISOString().split('T')[0],
  time: '09:00',
  due: 'ETD',
  remark: ''
})

// Schedule entries list
const scheduleEntries = ref([])
const selectedEntry = ref(null)
const selectedIndex = ref(-1)
const isEditing = ref(false)

// Error popup state
const showErrorPopup = ref(false)
const errorInfo = reactive({ code: '', type: '' })

// Order totals from props
const orderTotal = computed(() => {
  return {
    set: (() => {
      const val = props.orderDetails?.setQuantity
      const num = typeof val === 'string' ? parseFloat(val) : Number(val)
      return isNaN(num) ? 0 : num
    })(),
    // default main total to setQuantity when mainQuantity not provided
    main: (() => {
      const hasMain = (props.orderDetails?.mainQuantity != null && props.orderDetails?.mainQuantity !== '')
      const raw = hasMain ? props.orderDetails.mainQuantity : props.orderDetails?.setQuantity
      const num = typeof raw === 'string' ? parseFloat(raw) : Number(raw)
      return isNaN(num) ? 0 : num
    })()
  }
})

// Computed properties for totals
const entryTotal = computed(() => {
  const totals = scheduleEntries.value.reduce((acc, entry) => {
    acc.set += parseInt(entry.set) || 0
    acc.main += parseInt(entry.main) || 0
    return acc
  }, { set: 0, main: 0 })
  
  return totals
})

const remaining = computed(() => {
  return {
    set: orderTotal.value.set - entryTotal.value.set,
    main: orderTotal.value.main - entryTotal.value.main
  }
})

// Methods
const selectEntry = (entry, index) => {
  selectedEntry.value = entry
  selectedIndex.value = index
  if (entry) {
    Object.assign(scheduleEntry, { ...entry })
    isEditing.value = true
  }
}

const addNewEntry = () => {
  resetForm()
  isEditing.value = false
  selectedEntry.value = null
  scheduleEntry.no = (scheduleEntries.value.length + 1).toString()
}

const editSelectedEntry = () => {
  if (!selectedEntry.value) {
    error('Please select an entry to edit')
    return
  }
  Object.assign(scheduleEntry, { ...selectedEntry.value })
  isEditing.value = true
}

const deleteSelectedEntry = () => {
  if (!selectedEntry.value) {
    error('Please select an entry to delete')
    return
  }
  
  const index = scheduleEntries.value.findIndex(entry => entry.id === selectedEntry.value.id)
  if (index > -1) {
    scheduleEntries.value.splice(index, 1)
    // Renumber remaining entries
    scheduleEntries.value.forEach((entry, idx) => {
      entry.no = (idx + 1).toString()
    })
    success('Entry deleted successfully')
  }
  
  resetForm()
  selectedEntry.value = null
selectedIndex.value = -1
  isEditing.value = false
}

const addScheduleEntry = () => {
  // Validation
  if (!scheduleEntry.main && !scheduleEntry.set) {
    error('Please enter at least Set or Main quantity')
    return
  }
  
  if (!scheduleEntry.date) {
    error('Please select a schedule date')
    return
  }
  
  if (!scheduleEntry.due) {
    error('Please select a due status')
    return
  }

  const newEntry = {
    id: isEditing.value ? scheduleEntry.id : Date.now(),
    ...scheduleEntry,
    // Ensure Main mirrors Set and numbering is automatic
    main: scheduleEntry.set,
    no: (isEditing.value ? scheduleEntry.no : (scheduleEntries.value.length + 1).toString())
  }

  if (isEditing.value) {
    // Update existing entry
    const index = scheduleEntries.value.findIndex(entry => entry.id === newEntry.id)
    if (index > -1) {
      scheduleEntries.value[index] = newEntry
      success('Entry updated successfully')
    }
  } else {
    // Add new entry
    scheduleEntries.value.push(newEntry)
    success('Entry added successfully')
  }

  resetForm()
  isEditing.value = false
  selectedEntry.value = null
selectedIndex.value = -1
}

const resetForm = () => {
  Object.assign(scheduleEntry, {
    id: null,
    no: (scheduleEntries.value.length + 1).toString(),
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
    date: new Date().toISOString().split('T')[0],
    time: '09:00',
    due: 'ETD',
    remark: ''
  })
}

const calculateTotals = () => {
  // This method is called when quantities change
  // The computed properties will automatically update
}
// Keep Main in sync with Set input
watch(() => scheduleEntry.set, (val) => {
  scheduleEntry.main = val
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  const dayName = days[date.getDay()]
  const formattedDate = date.toLocaleDateString('en-GB')
  return `${formattedDate} ${dayName}`
}

const refreshScreen = () => {
  resetForm()
  selectedEntry.value = null
  selectedIndex.value = -1
  isEditing.value = false
  success('Screen refreshed')
}



const saveSchedule = async () => {
  if (scheduleEntries.value.length === 0) {
    error('Please add at least one schedule entry')
    return
  }

  // Validate totals
  const totalMain = Number(entryTotal.value.main)
  const requiredMain = Number(orderTotal.value.main)
  if (totalMain !== requiredMain) {
    // Show popup with code 200105
    errorInfo.code = '200105'
    errorInfo.type = 'Unmatched Order Qty with Delivery Schedule Qty'
    showErrorPopup.value = true
    return
  }

  // Validate that no per-entry or cumulative scheduled qty exceeds order qty
  let runningTotal = 0
  for (let i = 0; i < scheduleEntries.value.length; i++) {
    const e = scheduleEntries.value[i]
    const qty = Number(e.main || e.set || 0)
    if (!qty || qty <= 0) {
      error(`Entry ${e.no || i + 1}: Delivery quantity must be greater than 0`)
      return
    }
    if (qty > requiredMain) {
      error(`Entry ${e.no || i + 1}: Scheduled quantity exceeds order quantity`)
      return
    }
    runningTotal += qty
    if (runningTotal > requiredMain) {
      error(`Entry ${e.no || i + 1}: Scheduled quantity for line 1 exceeds order quantity`)
      return
    }
  }

  try {
    // Validate entries before sending
    const validatedEntries = scheduleEntries.value.map(entry => {
      const deliveryQuantity = parseFloat(entry.main) || parseFloat(entry.set) || 0
      
      // Validate required fields
      if (!entry.date) {
        throw new Error(`Entry ${entry.no}: Date is required`)
      }
      
      if (deliveryQuantity <= 0) {
        throw new Error(`Entry ${entry.no}: Delivery quantity must be greater than 0`)
      }
      
      if (!entry.due || !['ETD', 'ETA', 'TBA'].includes(entry.due)) {
        throw new Error(`Entry ${entry.no}: Due status must be ETD, ETA, or TBA`)
      }

      return {
        line_number: entry.line_number || 1,
        schedule_date: entry.date,
        schedule_time: entry.time || '00:00',
        delivery_quantity: deliveryQuantity,
        due_status: entry.due,
        remark: entry.remark || '',
        delivery_code: entry.delivery_code || '',
        delivery_location: entry.delivery_location || ''
      }
    })
    // Hand over to parent for actual saving
    emit('save', { entries: validatedEntries })
  } catch (err) {
    console.error('Error saving delivery schedule:', err)
    
    // Handle validation errors more specifically
    if (err.response && err.response.status === 422) {
      const validationErrors = err.response.data.errors
      if (validationErrors) {
        const errorMessages = Object.values(validationErrors).flat()
        error('Validation error: ' + errorMessages.join(', '))
      } else {
        error('Validation error: ' + (err.response.data.message || 'Invalid data'))
      }
    } else if (err.message) {
      error(err.message)
    } else {
      error('Failed to save delivery schedule')
    }
  }
}

// Keyboard shortcuts
const handleKeyDown = (event) => {
  if (!props.show) return
  
  // Ctrl/Cmd + S to save
  if ((event.ctrlKey || event.metaKey) && event.key === 's') {
    event.preventDefault()
    saveSchedule()
  }
  
  // F2 to edit
  if (event.key === 'F2') {
    event.preventDefault()
    editSelectedEntry()
  }
  
  // F3 to add new
  if (event.key === 'F3') {
    event.preventDefault()
    addNewEntry()
  }
  
  // Delete key to delete selected entry
  if (event.key === 'Delete' && selectedEntry.value) {
    event.preventDefault()
    deleteSelectedEntry()
  }
  
  // Esc to close
  if (event.key === 'Escape') {
    event.preventDefault()
    emit('close')
  }
}

// Watch for changes in order details
watch(() => props.orderDetails, (newDetails) => {
  if (newDetails && newDetails.setQuantity) {
    // Update order totals when props change
    console.log('Order details updated:', newDetails)
  }
}, { deep: true })

// Initialize component
onMounted(() => {
  document.addEventListener('keydown', handleKeyDown)
  resetForm()
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeyDown)
})
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