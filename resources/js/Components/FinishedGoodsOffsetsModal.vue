<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-6xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-100">
          <h3 class="text-lg font-semibold text-gray-900">Finished Goods Offsets</h3>
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
              @click="handleUndo"
              class="p-2 text-gray-600 hover:bg-gray-100 rounded transition-colors"
              title="Undo"
            >
              <i class="fas fa-undo"></i>
            </button>
            <button 
              @click="handlePrint"
              class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors"
              title="Print"
            >
              <i class="fas fa-print"></i>
            </button>
            <button 
              @click="handleSave"
              class="p-2 text-green-600 hover:bg-green-100 rounded transition-colors"
              title="Save"
            >
              <i class="fas fa-save"></i>
            </button>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 space-y-6">
          <!-- SO Data Table -->
          <div class="border border-gray-300 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-blue-600 text-white">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">S/Order#</th>
                  <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">IT</th>
                  <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">Source Ref#</th>
                  <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">Tran_Date</th>
                  <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">Customer P/O Ref#</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(soItem, index) in salesOrderData" :key="index" 
                    :class="index === 0 ? 'bg-yellow-100' : 'hover:bg-gray-50'">
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ soItem.sOrder }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ soItem.it }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ soItem.sourceRef }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ soItem.tranDate }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ soItem.customerPORef }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Form Fields -->
          <div class="grid grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse:</label>
              <input 
                v-model="offsetDetails.warehouse"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder="005"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Org Source:</label>
              <input 
                v-model="offsetDetails.orgSource"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder="002.2"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">W/Order#:</label>
              <input 
                v-model="offsetDetails.wOrder"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder=""
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Lock:</label>
              <input 
                v-model="offsetDetails.lock"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder="05"
              >
            </div>
          </div>

          <!-- Main Offset Table -->
          <div class="border border-gray-300 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-3 py-2 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300" colspan="5">OFFSET ENTRY</th>
                  <th class="px-3 py-2 text-center text-xs font-medium text-gray-700 uppercase tracking-wider" colspan="3">CONTROL ENTRY</th>
                </tr>
                <tr class="bg-gray-50">
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Item</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">P/Design</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Pcs</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Balance</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">To Offset</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">D/Order</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Offset</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Balance</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in offsetItems" :key="index" class="hover:bg-gray-50">
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pDesign"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0"
                      :placeholder="item.name === 'Main' ? 'B1' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pcs"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-center"
                      :placeholder="item.name === 'Main' ? '1' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.balance"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '125' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.toOffset"
                      type="text"
                      class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-right"
                      placeholder=""
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.dOrder"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '500' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.offset"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      placeholder=""
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2">
                    <input 
                      v-model="item.controlBalance"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '500' : ''"
                      readonly
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end space-x-4 p-4 border-t border-gray-200 bg-gray-50">
          <button 
            @click="closeModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button 
            @click="handleSave"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'

const { success, error, info } = useToast()

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  packingDetailsData: {
    type: Object,
    default: () => ({})
  },
  salesOrderDetailData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save'])

// Reactive data
const offsetDetails = reactive({
  warehouse: '005',
  orgSource: '002.2',
  wOrder: '',
  lock: '05'
})

// Sales Order Data from table SO
const salesOrderData = ref([
  {
    sOrder: '09-2025-02690',
    it: 'SI',
    sourceRef: '2549-03-0310',
    tranDate: '13/10/2025',
    customerPORef: 'ASF/PO/25/1188'
  },
  {
    sOrder: '09-2025-02690',
    it: 'SI',
    sourceRef: '2516-01-7330',
    tranDate: '14/10/2025',
    customerPORef: 'ASF/PO/25/1188'
  },
  {
    sOrder: '09-2025-02690',
    it: 'SI',
    sourceRef: '2516-01-7331',
    tranDate: '14/10/2025',
    customerPORef: 'ASF/PO/25/1188'
  },
  {
    sOrder: '09-2025-02690',
    it: 'SI',
    sourceRef: '2516-01-7332',
    tranDate: '14/10/2025',
    customerPORef: 'ASF/PO/25/1188'
  }
])

const offsetItems = ref([
  { 
    name: 'Main', 
    pDesign: 'B1', 
    pcs: '1', 
    balance: '125', 
    toOffset: '', 
    dOrder: '500', 
    offset: '', 
    controlBalance: '500' 
  },
  { name: 'Fit 1', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 2', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 3', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 4', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 5', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 6', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 7', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 8', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' },
  { name: 'Fit 9', pDesign: '', pcs: '', balance: '', toOffset: '', dOrder: '', offset: '', controlBalance: '' }
])

// Functions
const closeModal = () => {
  emit('close')
}

const handlePowerOff = () => {
  info('Power off functionality will be implemented')
}

const handleUndo = () => {
  info('Undo functionality will be implemented')
}

const handlePrint = () => {
  info('Print functionality will be implemented')
}

const handleSave = () => {
  const data = {
    offsetDetails,
    offsetItems: offsetItems.value,
    salesOrderData: salesOrderData.value
  }
  
  emit('save', data)
  success('Finished goods offsets saved successfully')
}

// Initialize with data from previous screens
onMounted(() => {
  // Populate from sales order detail data
  if (props.salesOrderDetailData && props.salesOrderDetailData.itemRows) {
    props.salesOrderDetailData.itemRows.forEach((item, index) => {
      if (index < offsetItems.value.length && item.toDeliver) {
        const offsetItem = offsetItems.value[index]
        offsetItem.pDesign = item.pDesign || ''
        offsetItem.pcs = item.pcs || ''
        offsetItem.dOrder = item.toDeliver || ''
        
        // Set default values for Main item
        if (item.name === 'Main' && item.toDeliver) {
          offsetItem.balance = '125'
          offsetItem.controlBalance = item.toDeliver
        }
      }
    })
  }
  
  // Update SO data if available from sales order detail
  if (props.salesOrderDetailData && props.salesOrderDetailData.orderDetail) {
    const orderDetail = props.salesOrderDetailData.orderDetail
    const soNumber = `${orderDetail.sOrderMonth}-${orderDetail.sOrderYear}-${orderDetail.sOrderSeq}`
    
    // Update all SO entries with the selected SO number
    salesOrderData.value.forEach(item => {
      item.sOrder = soNumber
      item.customerPORef = orderDetail.pOrderRef || 'ASF/PO/25/1188'
    })
  }
})
</script>

<style scoped>
/* Custom styles for table inputs */
.border-0 {
  border: none !important;
}

.border-0:focus {
  outline: none;
  box-shadow: none;
}

/* Table cell styling */
td input {
  background: transparent;
}

td input:focus {
  background: #f0f9ff;
}

/* Header styling for grouped columns */
th[colspan] {
  text-align: center;
  font-weight: bold;
}

/* Blue header styling */
.bg-blue-600 th {
  background-color: #2563eb;
  color: white;
}

.border-blue-500 {
  border-color: #3b82f6;
}
</style>
