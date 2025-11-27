<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col max-h-[calc(100vh-2rem)] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <i class="fas fa-clipboard-list text-white text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-white">Sales Order Screen</h3>
          </div>
          <button @click="closeModal" class="p-2 rounded-full hover:bg-white/20 text-white transition-colors">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
          <!-- Compact Header Information -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Order Information -->
              <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1">Order Configuration</h4>
                <div class="space-y-2 text-xs">
                  <div class="flex items-center space-x-2">
                    <span class="text-gray-600 w-20">Order Group:</span>
                    <input
                      v-model="orderInfo.orderGroup"
                      type="text"
                      class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                      placeholder="Enter order group"
                    >
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="text-gray-600 w-20">Order Mode:</span>
                    <input
                      v-model="orderInfo.orderMode"
                      type="text"
                      class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                      placeholder="Enter order mode"
                    >
                  </div>
                </div>
              </div>

              <!-- Additional Information -->
              <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1">Additional Information</h4>
                <div class="space-y-2 text-xs">
                  <div class="flex items-center space-x-2">
                    <span class="text-gray-600 w-20">Model:</span>
                    <input
                      v-model="bottomInfo.model"
                      type="text"
                      class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-orange-500"
                      placeholder="Enter model"
                    >
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="text-gray-600 w-20">S/O Ins1:</span>
                    <input
                      v-model="bottomInfo.soIns1"
                      type="text"
                      class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-orange-500"
                      placeholder="Enter S/O instruction 1"
                    >
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="text-gray-600 w-20">S/O Ins2:</span>
                    <input
                      v-model="bottomInfo.soIns2"
                      type="text"
                      class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-orange-500"
                      placeholder="Enter S/O instruction 2"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Compact Sales Order Entry Table -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-2 border-b border-gray-200">
              <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                <i class="fas fa-list text-gray-500 mr-2"></i>
                Sales Order Entry
              </h4>
            </div>
            <div class="overflow-x-auto max-h-64">
              <table class="w-full text-xs">
                <thead class="bg-gray-100 sticky top-0 z-10">
                  <tr>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300 w-12">No.</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">S/Order#</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">M/Card Seq#</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">P/Order Ref#</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">Set Qty</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700">Un.FG</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr
                    v-for="(entry, index) in salesOrderEntries"
                    :key="index"
                    @click="selectEntry(index)"
                    :class="[
                      'cursor-pointer hover:bg-blue-50 transition-colors',
                      selectedEntryIndex === index ? 'bg-blue-100 border-l-4 border-blue-500' : ''
                    ]"
                  >
                    <td class="px-2 py-1.5 font-medium text-gray-900 border-r border-gray-200">{{ String(index + 1).padStart(2, '0') }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <div class="flex items-center space-x-1">
                        <input
                          v-model="entry.sOrder"
                          type="text"
                          class="w-12 px-1 py-0.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="MM"
                        >
                        <span class="text-gray-400">-</span>
                        <input
                          v-model="entry.sOrder2"
                          type="text"
                          class="w-14 px-1 py-0.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="YYYY"
                        >
                        <span class="text-gray-400">-</span>
                        <input
                          v-model="entry.sOrder3"
                          type="text"
                          class="w-16 px-1 py-0.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="Seq"
                        >
                        <button
                          @click="openSalesOrderTable"
                          class="p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                          title="Sales Order Table"
                        >
                          <i class="fas fa-table text-xs"></i>
                        </button>
                      </div>
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-100 px-2 py-1 rounded text-xs text-blue-800 font-medium">
                        {{ entry.mcardSeq || 'M/Card Seq' }}
                      </div>
                      <div v-else class="h-6"></div>
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-100 px-2 py-1 rounded text-xs text-blue-800 font-medium">
                        {{ entry.pOrderRef || 'P/Order Ref' }}
                      </div>
                      <div v-else class="h-6"></div>
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-100 px-2 py-1 rounded text-xs text-blue-800 font-medium">
                        Set Qty
                      </div>
                      <div v-else class="h-6"></div>
                    </td>
                    <td class="px-2 py-1.5">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-100 px-2 py-1 rounded text-xs text-blue-800 font-medium">
                        Un.FG
                      </div>
                      <div v-else class="h-6"></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Compact Item Details Table -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-2 border-b border-gray-200">
              <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                <i class="fas fa-boxes text-gray-500 mr-2"></i>
                Item Details
              </h4>
            </div>
            <div class="overflow-x-auto max-h-96">
              <table class="w-full text-xs">
                <thead class="bg-gray-100 sticky top-0 z-10">
                  <tr>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">Item</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">P/Design</th>
                    <th class="px-2 py-1.5 text-center font-medium text-gray-700 border-r border-gray-300">Pcs</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">Unit</th>
                    <th class="px-2 py-1.5 text-left font-medium text-gray-700 border-r border-gray-300">Part#</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">DO Qty</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 bg-gray-50">DO KG</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="(item, index) in itemDetails" :key="index" class="hover:bg-blue-50 transition-colors">
                    <td class="px-2 py-1.5 font-medium text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <input
                        v-model="item.pDesign"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50"
                        placeholder="P/Design"
                      >
                    </td>
                    <td class="px-2 py-1.5 text-center border-r border-gray-200">
                      <input
                        v-model="item.pcs"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-center"
                        placeholder="Pcs"
                      >
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <input
                        v-model="item.unit"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50"
                        placeholder="Unit"
                      >
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <input
                        v-model="item.partNumber"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50"
                        placeholder="Part#"
                      >
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input
                        v-model="item.doQty"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right"
                        placeholder="DO Qty"
                      >
                    </td>
                    <td class="px-2 py-1.5 text-right bg-gray-50">
                      <input
                        v-model="item.doKg"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-gray-50 hover:bg-gray-100 text-right"
                        placeholder="DO KG"
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <div class="text-xs text-gray-600">
            <i class="fas fa-info-circle mr-1"></i>
            Select sales order and configure items for delivery
          </div>
          <div class="flex items-center space-x-3">
            <button
              @click="closeModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
            >
              <i class="fas fa-times mr-2"></i>
              Cancel
            </button>
            <button
              @click="handleSave"
              class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg shadow-lg hover:shadow-xl transition-all"
            >
              <i class="fas fa-arrow-right mr-2"></i>
              Proceed to Details
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sales Order Table Modal -->
    <SalesOrderTableModal
      :is-open="showSalesOrderTableModal"
      :customer-data="customerData"
      :deduplicate-by-so="true"
      @close="showSalesOrderTableModal = false"
      @select="handleSalesOrderSelect"
    />

    <!-- Sales Order Detail Modal -->
    <SalesOrderDetailModal
      :is-open="showSalesOrderDetailModal"
      :sales-order-data="currentSalesOrderData"
      @close="showSalesOrderDetailModal = false"
      @save="handleSalesOrderDetailSave"
      @save-delivery-order="handleSalesOrderDeliveryOrderSave"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, defineAsyncComponent } from 'vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const SalesOrderTableModal = defineAsyncComponent(() => import('./SalesOrderTableModal.vue'))
const SalesOrderDetailModal = defineAsyncComponent(() => import('./SalesOrderDetailModal.vue'))

const { success, error, info } = useToast()

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  customerData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save', 'save-delivery-order'])

// Reactive data
const selectedEntryIndex = ref(0)
const showSalesOrderTableModal = ref(false)
const showSalesOrderDetailModal = ref(false)

const orderInfo = reactive({
  orderGroup: '',
  orderMode: ''
})

const salesOrderEntries = ref([
  { sOrder: '', sOrder2: '0', sOrder3: '0' },
  { sOrder: '', sOrder2: '0', sOrder3: '0' },
  { sOrder: '', sOrder2: '0', sOrder3: '0' },
  { sOrder: '', sOrder2: '0', sOrder3: '0' },
  { sOrder: '', sOrder2: '0', sOrder3: '0' }
])

const itemDetails = ref([
  { name: 'Main', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit1', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit2', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit3', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit4', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit5', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit6', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit7', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit8', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' },
  { name: 'Fit9', pDesign: '', pcs: '', unit: '', partNumber: '', doQty: '', doKg: '' }
])

const bottomInfo = reactive({
  model: '',
  soIns1: '',
  soIns2: ''
})

// Functions
const closeModal = () => {
  emit('close')
}

const selectEntry = (index) => {
  selectedEntryIndex.value = index
}

const openSalesOrderTable = () => {
  showSalesOrderTableModal.value = true
}

const loadItemDetailsFromSo = async (soNumber) => {
  if (!soNumber) return
  try {
    const response = await axios.get(`/api/sales-order/${soNumber}/detail`)
    if (response.data && response.data.success) {
      const data = response.data.data || {}
      const details = data.item_details || {}

      const mainRow = itemDetails.value.find(row => row.name === 'Main')
      if (mainRow) {
        mainRow.pDesign = details.pd ?? ''
        mainRow.pcs = details.pcs ?? ''
        mainRow.unit = details.unit ?? '' // This now comes from SO table Main component
        mainRow.partNumber = data.part_number ?? ''
      }

      if (Array.isArray(data.fittings)) {
        data.fittings.forEach((fitting, index) => {
          if (index < 9) {
            const row = itemDetails.value[index + 1]
            if (row) {
              row.pDesign = fitting.design || ''
              row.pcs = fitting.pcs || ''
              row.unit = fitting.unit || '' // This now comes from SO table for each component
              row.partNumber = fitting.part_number || ''
            }
          }
        })
      }
    } else if (response.data && response.data.message) {
      error(response.data.message)
    }
  } catch (e) {
    error('Error loading sales order details')
  }
}

const handleSalesOrderSelect = async (selectedOrder) => {
  // Update the current sales order entry with selected order data
  if (selectedEntryIndex.value >= 0 && selectedEntryIndex.value < salesOrderEntries.value.length) {
    const entry = salesOrderEntries.value[selectedEntryIndex.value]

    // Parse SO number format: MM-YYYY-SEQ (e.g., "09-2025-03777")
    const soParts = selectedOrder.soNumber.split('-')

    if (soParts.length === 3) {
      // Split the SO number into parts
      const month = soParts[0]  // MM (e.g., "09")
      const year = soParts[1]   // YYYY (e.g., "2025")
      const sequence = soParts[2] // SEQ (e.g., "03777")

      // Fill the three textboxes with parsed data
      entry.sOrder = month      // First textbox: Month (MM)
      entry.sOrder2 = year      // Second textbox: Year (YYYY)
      entry.sOrder3 = sequence  // Third textbox: Sequence (SSSSS)
    } else {
      // Fallback: if format is unexpected, put full SO number in first textbox
      console.warn('Unexpected SO number format:', selectedOrder.soNumber)
      entry.sOrder = selectedOrder.soNumber
      entry.sOrder2 = ''
      entry.sOrder3 = ''
    }

    // Store the complete selected order data for use in Sales Order Detail
    entry.selectedOrderData = selectedOrder
    entry.mcardSeq = selectedOrder.mcardSeq || ''
    entry.pOrderRef = selectedOrder.pOrderRef || selectedOrder.customerPORef || ''

    if (selectedOrder.soNumber) {
      await loadItemDetailsFromSo(selectedOrder.soNumber)
    }
  }
  showSalesOrderTableModal.value = false
  success('Sales order selected successfully')
}

// Store current sales order data for detail modal
const currentSalesOrderData = ref({})

const handleSave = () => {
  // Check if any sales order entry is filled
  const hasValidEntry = salesOrderEntries.value.some(entry =>
    entry.sOrder && entry.sOrder2 && entry.sOrder3
  )

  if (!hasValidEntry) {
    error('Please select a sales order entry first')
    return
  }

  // Get the selected entry data
  const selectedEntry = salesOrderEntries.value[selectedEntryIndex.value]
  currentSalesOrderData.value = {
    soNumber: `${selectedEntry.sOrder}-${selectedEntry.sOrder2}-${selectedEntry.sOrder3}`,
    mcardSeq: selectedEntry.mcardSeq || '',
    pOrderRef: selectedEntry.pOrderRef || '',
    orderInfo,
    itemDetails: itemDetails.value,
    bottomInfo,
    selectedOrderData: selectedEntry.selectedOrderData || null
  }

  // Close current modal and open Sales Order Detail Modal
  showSalesOrderDetailModal.value = true
  success('Opening Sales Order Detail Screen')
}

const handleSalesOrderDetailSave = (detailData) => {
  // Transform payload to match parent expectations
  const payload = {
    salesOrderDetail: detailData?.salesOrderDetail || {},
    packingDetails: detailData?.packingDetails || {},
    finishedGoodsOffsets: undefined
  }

  emit('save', payload)
  showSalesOrderDetailModal.value = false
  success('Sales order detail saved successfully')
}

const handleSalesOrderDeliveryOrderSave = (detailData) => {
  // Transform payload to match parent expectations
  const payload = {
    salesOrderDetail: detailData?.salesOrderDetail || {},
    packingDetails: detailData?.packingDetails || {},
    finishedGoodsOffsets: undefined
  }

  emit('save-delivery-order', payload)
  showSalesOrderDetailModal.value = false
  success('Delivery order will be saved')
}

// Initialize with customer data if available
onMounted(() => {
  if (props.customerData && props.customerData.code) {
    orderInfo.orderGroup = props.customerData.code
  }
})
</script>

<style scoped>
/* Modern table styling */
.border-0 {
  border: none !important;
}

.border-0:focus {
  outline: none;
  box-shadow: none;
}

/* Table cell styling with hover effects */
td input {
  background: transparent;
  transition: all 0.2s ease;
}

td input:hover {
  background: #f8fafc;
  border-radius: 4px;
}

td input:focus {
  background: #e0f2fe;
  outline: 2px solid #0ea5e9;
  outline-offset: -1px;
  border-radius: 4px;
}

/* Custom scrollbar for modal content */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Table container scrollbar */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Input field focus styling */
input:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 1px;
  border-radius: 4px;
}

/* Checkbox styling */
input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}

/* Button hover effects */
button {
  transition: all 0.2s ease;
}

/* Table row hover animation */
tr {
  transition: background-color 0.2s ease;
}

/* Modal backdrop blur effect */
.fixed.inset-0 > .bg-black {
  backdrop-filter: blur(4px);
}

/* Header icon animation */
.fas.fa-clipboard-list {
  transition: transform 0.2s ease;
}

.fas.fa-clipboard-list:hover {
  transform: scale(1.1);
}

/* Selected entry styling */
.bg-blue-100 {
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* SO number input styling */
input[type="text"]:not([readonly]):focus {
  background: #f0f9ff;
  border-color: #3b82f6;
}

/* Modal animation */
@keyframes modalAppear {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-modal-appear {
  animation: modalAppear 0.3s ease-out;
}

/* Enhanced button hover effects */
button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}

/* Sticky table header styling */
.sticky {
  position: sticky;
  top: 0;
  z-index: 10;
}

/* Card shadow effects */
.shadow-sm:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: box-shadow 0.2s ease;
}

/* Icon styling in headers */
.fas.fa-list, .fas.fa-boxes {
  color: #6b7280;
  transition: color 0.2s ease;
}

.fas.fa-list:hover, .fas.fa-boxes:hover {
  color: #3b82f6;
}
</style>
