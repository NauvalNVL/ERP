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
                        <button class="p-1 text-blue-600 hover:bg-blue-100 rounded">
                          <i class="fas fa-search text-xs"></i>
                        </button>
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
                        <button class="p-1 text-gray-600 hover:bg-gray-100 rounded">
                          <i class="fas fa-th text-xs"></i>
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
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Model:</label>
              <input 
                v-model="bottomInfo.model"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter model"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">S/O Ins1:</label>
              <input 
                v-model="bottomInfo.soIns1"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter S/O instruction 1"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">S/O Ins2:</label>
              <input 
                v-model="bottomInfo.soIns2"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter S/O instruction 2"
              >
            </div>
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
  customerData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save'])

// Reactive data
const selectedEntryIndex = ref(0)

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

const handlePowerOff = () => {
  info('Power off functionality will be implemented')
}

const handleSave = () => {
  const data = {
    orderInfo,
    salesOrderEntries: salesOrderEntries.value,
    itemDetails: itemDetails.value,
    bottomInfo,
    selectedEntryIndex: selectedEntryIndex.value
  }
  
  emit('save', data)
  success('Sales order data saved successfully')
}

const handleRefresh = () => {
  // Reset form data
  Object.assign(orderInfo, {
    orderGroup: '',
    orderMode: ''
  })
  
  salesOrderEntries.value.forEach(entry => {
    entry.sOrder = ''
    entry.sOrder2 = '0'
    entry.sOrder3 = '0'
  })
  
  itemDetails.value.forEach(item => {
    item.pDesign = ''
    item.pcs = ''
    item.unit = ''
    item.partNumber = ''
    item.doQty = ''
    item.doKg = ''
  })
  
  Object.assign(bottomInfo, {
    model: '',
    soIns1: '',
    soIns2: ''
  })
  
  selectedEntryIndex.value = 0
  success('Form refreshed successfully')
}

// Initialize with customer data if available
onMounted(() => {
  if (props.customerData && props.customerData.code) {
    orderInfo.orderGroup = props.customerData.code
  }
})
</script>

<style scoped>
/* Custom scrollbar for better UX */
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
</style>
