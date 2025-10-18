<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-5xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-100">
          <h3 class="text-lg font-semibold text-gray-900">Packing Details</h3>
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
          <!-- Packing Details Table -->
          <div class="border border-gray-300 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Item</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">P/Design</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Pcs/Rolls</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">To Del</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Rolls + Qty + Loose Qty/Remark</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in packingItems" :key="index" class="hover:bg-gray-50">
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
                      v-model="item.pcsRolls"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-center"
                      :placeholder="item.name === 'Main' ? '5' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.toDel"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '500' : ''"
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2">
                    <div class="flex items-center space-x-1">
                      <input 
                        v-model="item.rolls"
                        type="text"
                        class="w-12 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        :placeholder="item.name === 'Main' ? '100' : ''"
                      >
                      <span class="text-xs text-gray-600">x</span>
                      <input 
                        v-model="item.qty"
                        type="text"
                        class="w-8 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        :placeholder="item.name === 'Main' ? '5' : ''"
                      >
                      <span class="text-xs text-gray-600">x</span>
                      <input 
                        v-model="item.looseQty"
                        type="text"
                        class="w-12 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        placeholder=""
                      >
                      <input 
                        v-model="item.remark"
                        type="text"
                        class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="Remark"
                      >
                    </div>
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

    <!-- Finished Goods Offsets Modal -->
    <FinishedGoodsOffsetsModal 
      :is-open="showFinishedGoodsOffsetsModal"
      :packing-details-data="currentPackingData"
      :sales-order-detail-data="salesOrderDetailData"
      @close="showFinishedGoodsOffsetsModal = false"
      @save="handleFinishedGoodsOffsetsSave"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import FinishedGoodsOffsetsModal from './FinishedGoodsOffsetsModal.vue'

const { success, error, info } = useToast()

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  salesOrderDetailData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save'])

// Modal state
const showFinishedGoodsOffsetsModal = ref(false)
const currentPackingData = ref({})

// Reactive data

const packingItems = ref([
  { 
    name: 'Main', 
    pDesign: 'B1', 
    pcsRolls: '5', 
    toDel: '500', 
    rolls: '100', 
    qty: '5', 
    looseQty: '', 
    remark: '' 
  },
  { name: 'Fit1', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit2', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit3', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit4', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit5', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit6', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit7', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit8', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit10', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' }
])


// Functions
const closeModal = () => {
  emit('close')
}

const handlePowerOff = () => {
  info('Power off functionality will be implemented')
}

const handlePrint = () => {
  info('Print functionality will be implemented')
}

const handleSave = () => {
  const data = {
    packingItems: packingItems.value
  }
  
  // Store current data for finished goods offsets modal
  currentPackingData.value = data
  
  // Open Finished Goods Offsets Modal
  showFinishedGoodsOffsetsModal.value = true
  success('Opening Finished Goods Offsets Screen')
}

const handleFinishedGoodsOffsetsSave = (offsetsData) => {
  // Combine all data and emit to parent
  const completeData = {
    packingDetails: currentPackingData.value,
    finishedGoodsOffsets: offsetsData
  }
  
  emit('save', completeData)
  showFinishedGoodsOffsetsModal.value = false
  success('Finished goods offsets saved successfully')
}

// Initialize with sales order detail data if available
onMounted(() => {
  if (props.salesOrderDetailData && props.salesOrderDetailData.itemRows) {
    // Populate packing items from sales order detail data
    props.salesOrderDetailData.itemRows.forEach((item, index) => {
      if (index < packingItems.value.length && item.toDeliver) {
        const packingItem = packingItems.value[index]
        packingItem.pDesign = item.pDesign || ''
        packingItem.pcsRolls = item.pcs || ''
        packingItem.toDel = item.toDeliver || ''
        
        // Set default values for Main item
        if (item.name === 'Main' && item.toDeliver) {
          packingItem.rolls = '100'
          packingItem.qty = '5'
        }
      }
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

/* Delivery schedule table styling */
.divide-y > * + * {
  border-top-width: 1px;
}

.divide-gray-200 > * + * {
  border-color: #e5e7eb;
}
</style>
