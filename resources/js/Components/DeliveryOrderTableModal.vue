<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl">
              <!-- Header with Gradient -->
              <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-6 py-4">
                <div class="flex items-center justify-between">
                  <DialogTitle class="text-lg font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Delivery Order Table</span>
                  </DialogTitle>
                  <button @click="handleClose" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="p-6 bg-gray-50">
                <!-- Table Container with Modern Shadow -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                  <!-- Table -->
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gradient-to-r from-gray-100 to-gray-50">
                        <tr>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">D/ORDER#</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">D/O DATE</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">CUSTOMER</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">VEHICLE#</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">ITEM#</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">P/C</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">MODE</th>
                          <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">STATUS</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-100">
                        <tr v-for="order in displayedOrders" :key="order.do_number"
                          @click="toggleSelection(order)"
                          :class="[
                            'cursor-pointer transition-all duration-150',
                            isSelected(order.do_number) 
                              ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md transform scale-[1.01]' 
                              : 'hover:bg-blue-50 hover:shadow-sm'
                          ]"
                        >
                          <td :class="['px-4 py-3 text-sm font-semibold border-r', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.do_number }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-50' : 'border-gray-200 text-gray-600']">
                            {{ formatDate(order.do_date) }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.customer_code || '-' }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-100' : 'border-gray-200 text-gray-600']">
                            {{ order.vehicle_no || '-' }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r text-center', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.item_count || 1 }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r text-center', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.pc || 1 }}
                          </td>
                          <td :class="['px-4 py-3 text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-50' : 'border-gray-200 text-gray-600']">
                            {{ order.mode || 'Multiple' }}
                          </td>
                          <td :class="['px-4 py-3 text-sm', isSelected(order.do_number) ? 'text-white' : '']">
                            <span :class="[
                              'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                              isSelected(order.do_number) 
                                ? 'bg-white bg-opacity-20 text-white' 
                                : order.status === 'Draft' 
                                  ? 'bg-yellow-100 text-yellow-800' 
                                  : 'bg-green-100 text-green-800'
                            ]">
                              {{ order.status || 'Draft' }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination Info -->
                  <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                      Showing <span class="font-semibold text-gray-900">1-{{ Math.min(displayedOrders.length, filteredOrders.length) }}</span> of <span class="font-semibold text-gray-900">{{ filteredOrders.length }}</span> records
                    </div>
                  </div>
                </div>

                <!-- Filter Section (Below Table) - Exact CPS Layout -->
                <div class="mt-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                  <!-- Row 1: D/Order# with icons + Search -->
                  <div class="mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">D/Order#:</label>
                      <input v-model="filters.doNumber" type="text" class="flex-1 max-w-xs px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                      <button class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50">📋</button>
                      <button class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50">📁</button>
                      <button class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50">🗙</button>
                      <button @click="applyFilters" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm font-medium">
                        Search
                      </button>
                    </div>
                  </div>

                  <!-- Row 2: Cust. Name (Full Width) -->
                  <div class="mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Cust. Name:</label>
                      <input readonly :value="customerName" class="flex-1 px-2 py-1 bg-gray-50 border border-gray-300 rounded text-sm"/>
                    </div>
                  </div>

                  <!-- Row 5: Salesperson, CR/Ticket#, On Hold -->
                  <div class="grid grid-cols-3 gap-3 mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-24">Salesperson:</label>
                      <input v-model="filters.salesperson" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-24">CR/Ticket#:</label>
                      <input v-model="filters.crTicket" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-20">On Hold:</label>
                      <input v-model="filters.onHold" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                  </div>

                  <!-- Row 6: Order Mode (Radio) -->
                  <div class="mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Order Mode:</label>
                      <div class="flex gap-4">
                        <label class="flex items-center gap-1.5">
                          <input type="radio" v-model="filters.orderMode" value="customer" class="text-blue-600"/>
                          <span class="text-sm text-gray-700">D-Order by Customer</span>
                        </label>
                        <label class="flex items-center gap-1.5">
                          <input type="radio" v-model="filters.orderMode" value="invoice" class="text-blue-600"/>
                          <span class="text-sm text-gray-700">Deliver & Invoice to Customer</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Row 7: Agent Cust & Sales Type -->
                  <div class="grid grid-cols-2 gap-x-4 mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Agent Cust.:</label>
                      <input v-model="filters.agentCust" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Sales Type:</label>
                      <input v-model="filters.salesType" type="text" placeholder="Sales" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                  </div>

                  <!-- Row 8: D/O Inst1 & Inst2 -->
                  <div class="grid grid-cols-2 gap-x-4 mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">D/O Inst1:</label>
                      <input v-model="filters.doInst1" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">D/O Inst2:</label>
                      <input v-model="filters.doInst2" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                  </div>

                  <!-- Row 9-10: Prepared/Cancelled & Amended/Printed (2x2 Grid) -->
                  <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Prepared by:</label>
                      <input v-model="filters.preparedBy" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                      <label class="text-xs font-semibold text-gray-700 w-12">Date:</label>
                      <input v-model="filters.preparedDate" type="date" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Amended by:</label>
                      <input v-model="filters.amendedBy" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                      <label class="text-xs font-semibold text-gray-700 w-12">Date:</label>
                      <input v-model="filters.amendedDate" type="date" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Cancelled by:</label>
                      <input v-model="filters.cancelledBy" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                      <label class="text-xs font-semibold text-gray-700 w-12">Date:</label>
                      <input v-model="filters.cancelledDate" type="date" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Printed by:</label>
                      <input v-model="filters.printedBy" type="text" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                      <label class="text-xs font-semibold text-gray-700 w-12">Date:</label>
                      <input v-model="filters.printedDate" type="date" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"/>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons in Content -->
                <div class="mt-6 flex justify-center gap-3">
                  <button class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all font-medium shadow-sm">
                    Zoom
                  </button>
                  <button 
                    @click="handleSelect"
                    :disabled="selectedCount === 0"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-400 flex items-center gap-2"
                  >
                    <span>Select</span>
                    <span v-if="selectedCount > 0" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-white bg-opacity-30 rounded-full">
                      ✓
                    </span>
                  </button>
                  <button @click="handleClose" class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all font-medium shadow-sm">
                    Exit
                  </button>
                </div>
              </div>

              <!-- Footer -->
              <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-center gap-3">
                  <button @click="handleClose" class="px-8 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all font-medium shadow-sm">
                    Cancel
                  </button>
                  <button 
                    @click="handleSelect"
                    :disabled="selectedCount === 0"
                    class="px-8 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-400"
                  >
                    OK
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  open: { type: Boolean, default: false },
  customerCode: { type: String, default: '' },
  customerName: { type: String, default: '' },
  periodMonth: { type: String, default: '' },
  periodYear: { type: String, default: '' }
})

const emit = defineEmits(['close', 'select'])

// State
const selectedDOs = ref([])
const deliveryOrders = ref([])
const filters = ref({
  currentPeriodMonth: '10',
  currentPeriodYear: '2025',
  updatePeriodMonth: '10',
  updatePeriodYear: '2025',
  currency: 'IDR',
  taxIndexNo: '',
  invoiceDate: '',
  doNumber: '',
  secondReference: '',
  salesperson: '',
  crTicket: '',
  onHold: '',
  orderMode: 'customer',
  agentCust: '',
  salesType: 'Sales',
  doInst1: '',
  doInst2: '',
  preparedBy: '',
  preparedDate: '',
  amendedBy: '',
  amendedDate: '',
  cancelledBy: '',
  cancelledDate: '',
  printedBy: '',
  printedDate: ''
})

// Computed
const filteredOrders = computed(() => {
  return deliveryOrders.value || []
})

const displayedOrders = computed(() => {
  // Show up to 3 rows for demo
  return filteredOrders.value.slice(0, 3)
})

const selectedCount = computed(() => selectedDOs.value.length)

// Watch for prop changes
watch(() => props.open, async (isOpen) => {
  if (isOpen) {
    await fetchDeliveryOrders()
  } else {
    selectedDOs.value = []
  }
})

// Methods
const fetchDeliveryOrders = async () => {
  try {
    // Build query parameters
    const params = new URLSearchParams()
    
    // Add customer code if available
    if (props.customerCode) {
      params.append('customer_code', props.customerCode)
    }
    
    // Add period from props or filters
    const month = props.periodMonth || filters.value.currentPeriodMonth
    const year = props.periodYear || filters.value.currentPeriodYear
    
    if (month && year) {
      params.append('period_month', month)
      params.append('period_year', year)
    }
    
    const url = `/api/invoices/delivery-orders?${params.toString()}`
    console.log('Fetching delivery orders from:', url)
    
    const res = await fetch(url, {
      headers: { 'Accept': 'application/json' }
    })
    
    if (res.ok) {
      const data = await res.json()
      deliveryOrders.value = data
      console.log(`✅ Loaded ${data.length} delivery orders from DO table`)
    } else {
      const error = await res.text()
      console.error('Failed to fetch delivery orders:', error)
      deliveryOrders.value = []
    }
  } catch (e) {
    console.error('Error fetching delivery orders:', e)
    deliveryOrders.value = []
  }
}

const isSelected = (doNumber) => {
  return selectedDOs.value.some(d => d.do_number === doNumber)
}

const toggleSelection = (item) => {
  const index = selectedDOs.value.findIndex(d => d.do_number === item.do_number)
  
  if (index > -1) {
    // Clicking same row - deselect
    selectedDOs.value = []
    clearFormFields()
    console.log('❌ Deselected DO:', item.do_number)
  } else {
    // Clicking different row - single selection (clear previous and select new)
    selectedDOs.value = [item]  // Replace entire array with single item
    populateFormFields(item)
    console.log('✅ Selected DO (single selection):', item.do_number)
  }
}

const applyFilters = () => {
  // Apply filter logic here
  fetchDeliveryOrders()
}

const handleClose = () => {
  emit('close')
}

const handleSelect = () => {
  if (selectedDOs.value.length > 0) {
    // Single selection - emit only the first (and only) selected item
    const selectedDO = selectedDOs.value[0]
    console.log('📤 Emitting selected DO:', selectedDO.do_number)
    emit('select', selectedDO)  // Emit single object, not array
  } else {
    console.warn('⚠️ No delivery order selected')
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'Invalid Date'
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    })
  } catch (e) {
    return dateString
  }
}

/**
 * Auto-populate form fields with selected DO data
 */
const populateFormFields = (order) => {
  console.log('🔄 Auto-populating fields with DO:', order.do_number)
  console.log('📦 Order data received:', order)
  console.log('📦 Full order object:', JSON.stringify(order, null, 2))
  
  // D/Order# - primary identifier
  filters.value.doNumber = order.do_number || ''
  
  // Salesperson - from customer table
  const salespersonValue = order.salesperson || ''
  filters.value.salesperson = salespersonValue
  
  console.log('👤 Salesperson value:', {
    raw: order.salesperson,
    type: typeof order.salesperson,
    assigned: salespersonValue,
    isEmpty: !salespersonValue,
    isUndefined: order.salesperson === undefined,
    isNull: order.salesperson === null,
    isEmptyString: order.salesperson === ''
  })
  
  // D/O Inst1 & Inst2 - from remarks
  filters.value.doInst1 = order.remark1 || ''
  filters.value.doInst2 = order.remark2 || ''
  
  // Sales Type - default to 'Sales'
  filters.value.salesType = 'Sales'
  
  console.log('✅ Form fields populated:', {
    doNumber: filters.value.doNumber,
    salesperson: filters.value.salesperson,
    doInst1: filters.value.doInst1,
    doInst2: filters.value.doInst2
  })
  
  // Warning if salesperson is empty
  if (!salespersonValue) {
    console.warn('⚠️ Salesperson is empty for customer:', order.customer_code)
  }
}

/**
 * Clear form fields when deselecting DO
 */
const clearFormFields = () => {
  console.log('Clearing form fields')
  filters.value.doNumber = ''
  filters.value.salesperson = ''
  filters.value.crTicket = ''
  filters.value.onHold = ''
  filters.value.agentCust = ''
  filters.value.doInst1 = ''
  filters.value.doInst2 = ''
  filters.value.preparedBy = ''
  filters.value.preparedDate = ''
  filters.value.amendedBy = ''
  filters.value.amendedDate = ''
  filters.value.cancelledBy = ''
  filters.value.cancelledDate = ''
  filters.value.printedBy = ''
  filters.value.printedDate = ''
}
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
