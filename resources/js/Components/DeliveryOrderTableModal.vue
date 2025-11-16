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
            <DialogPanel class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all w-full max-w-6xl max-h-[85vh] flex flex-col sm:my-8">
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
              <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <!-- Table Container with Modern Shadow -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                  <!-- Table -->
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gradient-to-r from-gray-100 to-gray-50">
                        <tr>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">D/ORDER#</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">D/O DATE</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">CUSTOMER</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">VEHICLE#</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">ITEM#</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">P/C</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">MODE</th>
                          <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">STATUS</th>
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
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm font-semibold border-r', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.do_number }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-50' : 'border-gray-200 text-gray-600']">
                            {{ formatDate(order.do_date) }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.customer_code || '-' }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-100' : 'border-gray-200 text-gray-600']">
                            {{ order.vehicle_no || '-' }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r text-center', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.item_count || 1 }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r text-center', isSelected(order.do_number) ? 'border-blue-500 text-white' : 'border-gray-200 text-gray-900']">
                            {{ order.pc || 1 }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm border-r', isSelected(order.do_number) ? 'border-blue-500 text-blue-50' : 'border-gray-200 text-gray-600']">
                            {{ order.mode || 'Multiple' }}
                          </td>
                          <td :class="['px-2 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm', isSelected(order.do_number) ? 'text-white' : '']">
                            <div class="flex flex-col gap-1">
                              <!-- DO Status Badge -->
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
                              <!-- Invoice Status Badge (CPS-compatible) -->
                              <span v-if="order.invoice_status" :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                isSelected(order.do_number)
                                  ? 'bg-white bg-opacity-20 text-white'
                                  : order.invoice_status === 'Completed'
                                    ? 'bg-gray-100 text-gray-600'
                                    : order.invoice_status === 'Partial'
                                      ? 'bg-yellow-100 text-yellow-800'
                                      : 'bg-blue-100 text-blue-800'
                              ]">
                                <template v-if="order.invoice_status === 'Completed'">
                                  ✓ Invoiced
                                </template>
                                <template v-else-if="order.invoice_status === 'Partial'">
                                  ⚠ Partial: {{ order.invoiced_qty }}/{{ order.do_qty }}
                                </template>
                                <template v-else>
                                  ○ Open
                                </template>
                              </span>
                            </div>
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
                      <input
                        v-model="filters.doNumber"
                        type="text"
                        @keyup.enter="applyFilters"
                        placeholder="Type D/O number and press Enter"
                        class="flex-1 max-w-xs px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500"
                      />
                      <button @click="clearFormFields" class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50" title="Clear">🗙</button>
                      <button @click="applyFilters" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
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
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Salesperson:</label>
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

                  <!-- Row 6: Order Mode (Textbox like CPS) -->
                  <div class="mb-3">
                    <div class="flex items-center gap-2">
                      <label class="text-xs font-semibold text-gray-700 w-32">Order Mode:</label>
                      <input
                        v-model="filters.orderModeText"
                        type="text"
                        readonly
                        class="flex-1 px-2 py-1 bg-gray-50 border border-gray-300 rounded text-sm"
                        placeholder="D-Order by Customer + Deliver & Invoice to Customer"
                      />
                    </div>
                  </div>

                  <!-- Row 7: Agent Cust & Sales Type -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 mb-3">
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
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 mb-3">
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
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
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
                <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                  <button
                    @click="handleClose"
                    class="w-full sm:w-auto px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all font-medium shadow-sm"
                  >
                    Cancel
                  </button>
                  <button
                    @click="handleSelect"
                    :disabled="selectedCount === 0"
                    class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-400 flex items-center justify-center gap-2"
                  >
                    <span>Select</span>
                    <span v-if="selectedCount > 0" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-white bg-opacity-30 rounded-full">
                      ✓
                    </span>
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
  periodYear: { type: String, default: '' },
  openPeriod: { type: Boolean, default: false }
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
  orderModeText: '', // CPS-style textbox for order mode
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
    const params = new URLSearchParams()
    // Optional customer filter
    if (props.customerCode) params.append('customer_code', props.customerCode)

    // In openPeriod mode, do NOT restrict by month/year (no from_date/to_date)
    if (!props.openPeriod) {
      // Build from/to date (YYYY-MM-DD) for the given month/year
      const month = String(props.periodMonth || filters.value.currentPeriodMonth).padStart(2, '0')
      const year = String(props.periodYear || filters.value.currentPeriodYear)
      if (month && year) {
        const fromDate = `${year}-${month}-01`
        // Compute last day of month
        const lastDay = new Date(Number(year), Number(month), 0).getDate()
        const toDate = `${year}-${month}-${String(lastDay).padStart(2, '0')}`
        params.append('from_date', fromDate)
        params.append('to_date', toDate)
      }
    }

    const url = `/api/delivery-orders?${params.toString()}`
    console.log('Fetching delivery orders from:', url)

    const res = await fetch(url, { headers: { Accept: 'application/json' } })
    if (!res.ok) {
      const error = await res.text()
      console.error('Failed to fetch delivery orders:', error)
      deliveryOrders.value = []
      return
    }

    const json = await res.json()
    // Normalize response: support {success, data: {data: []}} or plain array
    let rows = []
    if (Array.isArray(json)) {
      rows = json
    } else if (json && Array.isArray(json.data)) {
      rows = json.data
    } else if (json && json.data && Array.isArray(json.data.data)) {
      rows = json.data.data
    } else {
      rows = []
    }

    // Map DB fields to UI fields
    deliveryOrders.value = rows.map(r => {
      const doNum = r.DO_Num || r.do_num || r.doNumber || ''
      const doDateSk = r.DODateSK || r.dodatesk
      const doDmy = r.DO_DMY || r.do_dmy || ''
      // Prefer DODateSK (YYYYMMDD) to build a proper ISO date string
      let doDate = ''
      if (doDateSk) {
        const s = String(doDateSk)
        doDate = `${s.slice(0, 4)}-${s.slice(4, 6)}-${s.slice(6, 8)}`
      } else {
        doDate = doDmy // may be d/m/Y; formatDate() will fallback to raw if invalid
      }
      return {
        do_number: doNum,
        do_date: doDate,
        customer_code: r.AC_Num || r.ac_num || r.customer_code || '',
        vehicle_no: r.DO_VHC_Num || r.vehicle_number || r.vehicle_no || '',
        item_count: 1,
        pc: r.PCS_PER_SET || r.pcs_per_set || 1,
        mode: 'Multiple',
        status: r.Status || r.status || 'Draft',
        remark1: r.DO_Remark1 || r.do_remark1 || '',
        remark2: r.DO_Remark2 || r.do_remark2 || '',
        salesperson: r.SLM || r.slm || '',
        // CPS-compatible invoice tracking (from backend calculation)
        do_qty: r.do_qty || r.DO_Qty || 0,
        invoiced_qty: r.invoiced_qty || 0,
        remaining_qty: r.remaining_qty || r.do_qty || 0,
        invoice_status: r.invoice_status || 'Open'
      }
    })

    console.log(`✅ Loaded ${deliveryOrders.value.length} delivery orders from DO table`)
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

/**
 * Search specific DO by number and auto-populate
 */
const searchByDoNumber = async () => {
  const doNum = filters.value.doNumber?.trim()

  if (!doNum) {
    console.warn('⚠️ No D/Order# provided for search')
    return
  }

  console.log('🔍 Searching for D/Order#:', doNum)

  try {
    // Search in existing loaded orders first
    const existingOrder = deliveryOrders.value.find(o =>
      o.do_number === doNum || o.do_number.includes(doNum)
    )

    if (existingOrder) {
      console.log('✅ Found in loaded orders:', existingOrder.do_number)
      // Select and populate
      selectedDOs.value = [existingOrder]
      populateFormFields(existingOrder)
      return
    }

    // If not found, fetch from API
    console.log('🌐 Fetching from API...')
    const params = new URLSearchParams()
    params.append('do_number', doNum)

    // Add period if available
    if (props.periodMonth && props.periodYear) {
      params.append('period_month', props.periodMonth)
      params.append('period_year', props.periodYear)
    }

    const url = `/api/invoices/delivery-orders?${params.toString()}`
    const res = await fetch(url, {
      headers: { 'Accept': 'application/json' }
    })

    if (res.ok) {
      const data = await res.json()

      if (data && data.length > 0) {
        const foundOrder = data[0]
        console.log('✅ Found via API:', foundOrder.do_number)

        // Add to loaded orders if not already there
        if (!deliveryOrders.value.some(o => o.do_number === foundOrder.do_number)) {
          deliveryOrders.value.unshift(foundOrder)
        }

        // Select and populate
        selectedDOs.value = [foundOrder]
        populateFormFields(foundOrder)
      } else {
        console.warn('⚠️ D/Order# not found:', doNum)
        alert(`D/Order# "${doNum}" not found in the system.`)
      }
    } else {
      console.error('❌ API error:', res.status)
    }
  } catch (e) {
    console.error('❌ Error searching DO:', e)
  }
}

const applyFilters = () => {
  // If D/Order# is provided, search for it specifically
  if (filters.value.doNumber?.trim()) {
    searchByDoNumber()
  } else {
    // Otherwise, fetch all with other filters
    fetchDeliveryOrders()
  }
}

const handleClose = () => {
  emit('close')
}

const handleSelect = () => {
  if (selectedDOs.value.length > 0) {
    // Single selection - emit only the first (and only) selected item
    const selectedDO = selectedDOs.value[0]
    console.log('📤 Emitting selected DO:', selectedDO.do_number)
    console.log('📋 Table modal will close, Screen modal stays open')

    // Emit selection to parent - parent will handle closing this modal
    emit('select', selectedDO)  // Emit single object, not array

    // Note: We DO NOT call handleClose() here
    // Parent component (PrepareInvoicebyDOCurrentPeriod) will close this modal
    // via onDOsSelectedFromTable() function while keeping Screen modal open
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
 * Auto-populate form fields with selected DO data (CPS-compatible)
 */
const populateFormFields = (order) => {
  console.log('🔄 Auto-populating fields with DO:', order.do_number)
  console.log('📦 Full order data:', order)

  // D/Order# - primary identifier
  filters.value.doNumber = order.do_number || ''

  // Salesperson - from DO or Customer table (already includes code + name from API)
  filters.value.salesperson = order.salesperson || order.salesperson_code || ''

  // CR/Ticket# - from PO number or SO number
  filters.value.crTicket = order.po_number || order.so_number || ''

  // On Hold - based on status
  filters.value.onHold = (order.status === 'Hold' || order.status === 'OnHold') ? 'Yes' : 'No'

  // Order Mode - Build textbox content based on SO_Type
  let orderModeText = 'D-Order by Customer'

  if (order.order_mode === 'invoice' || order.so_type === 'invoice') {
    orderModeText = 'D-Order by Customer + Deliver & Invoice to Customer'
    filters.value.orderMode = 'invoice'
  } else if (order.order_mode === 'customer' || order.so_type === 'customer') {
    orderModeText = 'D-Order by Customer'
    filters.value.orderMode = 'customer'
  } else {
    // Default based on customer field
    orderModeText = 'D-Order by Customer'
    filters.value.orderMode = 'customer'
  }

  filters.value.orderModeText = orderModeText

  // Agent Cust - can be populated if available
  filters.value.agentCust = order.agent_customer || ''

  // Sales Type - default to 'Sales'
  filters.value.salesType = order.sales_type || 'Sales'

  // D/O Inst1 & Inst2 - from remarks
  filters.value.doInst1 = order.remark1 || ''
  filters.value.doInst2 = order.remark2 || ''

  // Audit trail fields - populate if available from backend
  filters.value.preparedBy = order.prepared_by || ''
  filters.value.preparedDate = order.prepared_date || ''
  filters.value.amendedBy = order.amended_by || ''
  filters.value.amendedDate = order.amended_date || ''
  filters.value.cancelledBy = order.cancelled_by || ''
  filters.value.cancelledDate = order.cancelled_date || ''
  filters.value.printedBy = order.printed_by || ''
  filters.value.printedDate = order.printed_date || ''

  console.log('✅ Form fields auto-populated (CPS-style):', {
    doNumber: filters.value.doNumber,
    salesperson: filters.value.salesperson,
    crTicket: filters.value.crTicket,
    onHold: filters.value.onHold,
    orderMode: filters.value.orderMode,
    orderModeText: filters.value.orderModeText,
    salesType: filters.value.salesType,
    doInst1: filters.value.doInst1,
    doInst2: filters.value.doInst2
  })

  // Warning if important fields are empty
  if (!filters.value.salesperson) {
    console.warn('⚠️ Salesperson is empty for customer:', order.customer_code)
  }
  if (!filters.value.crTicket) {
    console.warn('⚠️ CR/Ticket# is empty for DO:', order.do_number)
  }
}

/**
 * Clear form fields when deselecting DO
 */
const clearFormFields = () => {
  console.log('🧹 Clearing all form fields')
  filters.value.doNumber = ''
  filters.value.salesperson = ''
  filters.value.crTicket = ''
  filters.value.onHold = ''
  filters.value.orderMode = 'customer'
  filters.value.orderModeText = ''
  filters.value.agentCust = ''
  filters.value.salesType = 'Sales'
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

  console.log('✅ All fields cleared')
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
