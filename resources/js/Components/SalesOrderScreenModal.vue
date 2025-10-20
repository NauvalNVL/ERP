<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-6xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
          <h3 class="text-lg font-semibold text-gray-900">Sales Order Screen</h3>
          <div class="flex items-center space-x-2">
            <!-- Control Buttons -->
            <button 
              @click="handlePowerOff"
              class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors"
              title="Power Off"
            >
              <i class="fas fa-power-off"></i>
            </button>
            <button 
              @click="closeModal"
              class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors"
              title="Close"
            >
              <i class="fas fa-times"></i>
            </button>
            <button 
              @click="handleSave"
              class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors"
              title="Save"
            >
              <i class="fas fa-save"></i>
            </button>
            <button 
              @click="handleRefresh"
              class="p-2 text-green-600 hover:bg-green-100 rounded transition-colors"
              title="Refresh"
            >
              <i class="fas fa-arrow-left"></i>
            </button>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 space-y-6">
          <!-- Order Information Section -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Order Group:</label>
              <input 
                v-model="orderInfo.orderGroup"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter order group"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Order Mode:</label>
              <input 
                v-model="orderInfo.orderMode"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter order mode"
              >
            </div>
          </div>

          <!-- Sales Order Entry Section -->
          <div class="space-y-4">
            <h4 class="text-md font-semibold text-gray-800">Sales Order Entry</h4>
            
            <!-- Sales Order Table -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No.</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/Order#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M/Card Seq#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Order Ref#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Set Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Un.FG</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="(entry, index) in salesOrderEntries" 
                    :key="index"
                    @click="selectEntry(index)"
                    :class="[
                      'cursor-pointer hover:bg-gray-50',
                      selectedEntryIndex === index ? 'bg-blue-100 border-l-4 border-blue-500' : ''
                    ]"
                  >
                    <td class="px-3 py-2 text-sm text-gray-900">{{ String(index + 1).padStart(2, '0') }}</td>
                    <td class="px-3 py-2">
                      <div class="flex items-center space-x-1">
                        <input 
                          v-model="entry.sOrder"
                          type="text"
                          class="w-16 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="S/Order"
                        >
                        <input 
                          v-model="entry.sOrder2"
                          type="text"
                          class="w-12 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="0"
                        >
                        <input 
                          v-model="entry.sOrder3"
                          type="text"
                          class="w-12 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                          placeholder="0"
                        >
                        <button 
                          @click="openSalesOrderTable"
                          class="p-1 text-blue-600 hover:bg-blue-100 rounded"
                          title="Sales Order Table"
                        >
                          <i class="fas fa-table text-xs"></i>
                        </button>
                      </div>
                    </td>
                    <td class="px-3 py-2">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-200 h-8 rounded flex items-center px-2">
                        <span class="text-sm text-blue-800">Selected Entry Details</span>
                      </div>
                      <div v-else class="h-8"></div>
                    </td>
                    <td class="px-3 py-2">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-200 h-8 rounded flex items-center px-2">
                        <span class="text-sm text-blue-800">P/Order Ref</span>
                      </div>
                      <div v-else class="h-8"></div>
                    </td>
                    <td class="px-3 py-2">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-200 h-8 rounded flex items-center px-2">
                        <span class="text-sm text-blue-800">Set Qty</span>
                      </div>
                      <div v-else class="h-8"></div>
                    </td>
                    <td class="px-3 py-2">
                      <div v-if="selectedEntryIndex === index" class="bg-blue-200 h-8 rounded flex items-center px-2">
                        <span class="text-sm text-blue-800">Un.FG</span>
                      </div>
                      <div v-else class="h-8"></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Item Details Table -->
          <div class="space-y-4">
            <h4 class="text-md font-semibold text-gray-800">Item Details</h4>
            
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pcs</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DO Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DO KG</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(item, index) in itemDetails" :key="index">
                    <td class="px-3 py-2 text-sm text-gray-900">{{ item.name }}</td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.pDesign"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="P/Design"
                      >
                    </td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.pcs"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="Pcs"
                      >
                    </td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.unit"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="Unit"
                      >
                    </td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.partNumber"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="Part#"
                      >
                    </td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.doQty"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="DO Qty"
                      >
                    </td>
                    <td class="px-3 py-2">
                      <input 
                        v-model="item.doKg"
                        type="text"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="DO KG"
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Bottom Information Section -->
          <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl p-5 border border-orange-200">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-orange-600 rounded-lg">
                <i class="fas fa-clipboard-list text-white"></i>
              </div>
              <h4 class="ml-3 text-md font-semibold text-gray-800">Additional Information</h4>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model:</label>
                <input 
                  v-model="bottomInfo.model"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-white shadow-sm"
                  placeholder="Enter model"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">S/O Ins1:</label>
                <input 
                  v-model="bottomInfo.soIns1"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-white shadow-sm"
                  placeholder="Enter S/O instruction 1"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">S/O Ins2:</label>
                <input 
                  v-model="bottomInfo.soIns2"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-white shadow-sm"
                  placeholder="Enter S/O instruction 2"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
          <div class="text-sm text-gray-600">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Select sales order and configure items for delivery
          </div>
          <div class="flex items-center space-x-3">
            <button 
              @click="closeModal"
              class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all shadow-sm"
            >
              <i class="fas fa-times mr-2"></i>
              Cancel
            </button>
            <button 
              @click="handleSave"
              class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-lg"
            >
              <i class="fas fa-check mr-2"></i>
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
import { ref, reactive, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import SalesOrderTableModal from './SalesOrderTableModal.vue'
import SalesOrderDetailModal from './SalesOrderDetailModal.vue'

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

const handleSalesOrderSelect = (selectedOrder) => {
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
  // Combine all data and emit to parent
  const completeData = {
    salesOrderData: currentSalesOrderData.value,
    detailData: detailData,
    selectedEntryIndex: selectedEntryIndex.value
  }
  
  emit('save', completeData)
  showSalesOrderDetailModal.value = false
  success('Sales order detail saved successfully')
}

const handleSalesOrderDeliveryOrderSave = (detailData) => {
  const completeData = {
    salesOrderData: currentSalesOrderData.value,
    detailData: detailData,
    selectedEntryIndex: selectedEntryIndex.value
  }
  
  emit('save-delivery-order', completeData)
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
/* Modal Animation */
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

/* Custom scrollbar for better UX */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: linear-gradient(to bottom, #f1f5f9, #e2e8f0);
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #94a3b8, #64748b);
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #64748b, #475569);
}

/* Input focus effects */
input:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Table row hover effect */
tr:hover {
  transform: scale(1.001);
}

/* Button hover effects */
button {
  transition: all 0.2s ease-in-out;
}

button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}
</style>
