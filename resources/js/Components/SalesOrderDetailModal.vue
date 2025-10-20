<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-7xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-100">
          <h3 class="text-lg font-semibold text-gray-900">Sales Order Detail Screen</h3>
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
          <!-- Top Information Section -->
          <div class="grid grid-cols-4 gap-4">
            <!-- Left Column -->
            <div class="space-y-3">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">Item#:</label>
                <input 
                  v-model="orderDetail.itemNumber"
                  type="text"
                  class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  value="1"
                  readonly
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">S/Order#:</label>
                <input 
                  v-model="orderDetail.sOrderMonth"
                  type="text"
                  class="w-8 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="9"
                >
                <input 
                  v-model="orderDetail.sOrderYear"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="2025"
                >
                <input 
                  v-model="orderDetail.sOrderSeq"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="2090"
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">M/Card Seq#:</label>
                <input 
                  v-model="orderDetail.mcardSeq"
                  type="text"
                  class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="282912"
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">Lot#:</label>
                <input 
                  v-model="orderDetail.lotNumber"
                  type="text"
                  class="w-32 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">Set Order:</label>
                <input 
                  v-model="orderDetail.setOrder"
                  type="text"
                  class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="2,000"
                >
              </div>
            </div>

            <!-- Middle Column -->
            <div class="space-y-3">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">S/O Status:</label>
                <input 
                  v-model="orderDetail.soStatus"
                  type="text"
                  class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="Partially"
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">S/O Date:</label>
                <input 
                  v-model="orderDetail.soDate"
                  type="text"
                  class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="17/09/2025"
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">P/Order Ref#:</label>
                <input 
                  v-model="orderDetail.pOrderRef"
                  type="text"
                  class="w-32 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="ASF/PO/25/1188"
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">P/O Date:</label>
                <input 
                  v-model="orderDetail.poDate"
                  type="text"
                  class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="17/09/2025"
                >
              </div>
            </div>

            <!-- Right Column - Product Description -->
            <div class="col-span-2">
              <div class="flex items-center space-x-2 mb-3">
                <input 
                  v-model="orderDetail.productDescription"
                  type="text"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder="SHIPPING CASE ACOSTA JUMBO"
                  readonly
                >
              </div>
            </div>
          </div>

          <!-- Delivery Settings -->
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">
                  To Delivery Set: <span class="text-red-500">*</span>
                </label>
                <input 
                  v-model="orderDetail.toDeliverySet"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': isToDeliverySetRequired && !orderDetail.toDeliverySet }"
                  placeholder="0"
                  @input="handleDeliverySetChange"
                >
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2">
                <input 
                  v-model="orderDetail.unapplyFGoods"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label class="text-sm text-gray-700">Unapply F/Goods</label>
              </div>
              
              <div class="flex items-center space-x-2">
                <input 
                  v-model="orderDetail.tickForYYes"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label class="text-sm text-gray-700">Tick for Y-Yes</label>
              </div>
            </div>
          </div>

          <!-- Main Data Table -->
          <div class="border border-gray-300 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Item</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">P/Design</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Pcs</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Unit</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Order</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Delivery</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Reject</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Balance</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Available</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Max DO</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">
                    To Deliver <span class="text-red-500">*</span>
                  </th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Deliver KG</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in itemRows" :key="index" class="hover:bg-gray-50">
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pDesign"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0"
                      :placeholder="item.name === 'Main' ? 'B1' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pcs"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-center"
                      :placeholder="item.name === 'Main' ? '1' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.unit"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0"
                      :placeholder="item.name === 'Main' ? 'Pcs' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.order"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '2,000' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.delivery"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '1,100' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.reject"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '35' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.balance"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '900' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.available"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '1,275' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.maxDO"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="item.name === 'Main' ? '22,000' : ''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.toDeliver"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right bg-yellow-50"
                      :class="{ 'border border-red-500': isToDeliverySetRequired }"
                      @input="handleToDeliverChange"
                      placeholder=""
                    >
                  </td>
                  <td class="px-3 py-2">
                    <input 
                      v-model="item.deliverKG"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right bg-gray-50"
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Notes Section -->
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Note1:</label>
              <input 
                v-model="orderDetail.note1"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder=""
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Note2:</label>
              <input 
                v-model="orderDetail.note2"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                placeholder=""
              >
            </div>
            
            <!-- Validation Warning -->
            <div v-if="isToDeliverySetRequired" class="bg-red-100 border border-red-300 rounded p-3">
              <p class="text-sm text-red-800 font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Please fill in "To Delivery Set" or at least one "To Deliver" quantity to proceed
              </p>
            </div>

            <!-- Warning Note -->
            <div class="bg-yellow-100 border border-yellow-300 rounded p-3">
              <p class="text-sm text-yellow-800 font-medium">
                Note: This Customer allows to deliver +/-1000% quantity for this DO
              </p>
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
            :disabled="isToDeliverySetRequired"
            :class="[
              'px-4 py-2 text-sm font-medium border border-transparent rounded-md focus:outline-none focus:ring-2',
              isToDeliverySetRequired 
                ? 'text-gray-400 bg-gray-300 cursor-not-allowed' 
                : 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
            ]"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>

    <!-- Packing Details Modal -->
    <PackingDetailsModal 
      :is-open="showPackingDetailsModal"
      :sales-order-detail-data="currentDetailData"
      @close="showPackingDetailsModal = false"
      @save="handlePackingDetailsSave"
      @save-delivery-order="handleSaveDeliveryOrder"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch, computed, watchEffect } from 'vue'
import { useToast } from '@/Composables/useToast'
import PackingDetailsModal from './PackingDetailsModal.vue'

const { success, error, info } = useToast()

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  salesOrderData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save', 'save-delivery-order'])

// Modal state
const showPackingDetailsModal = ref(false)
const currentDetailData = ref({})

// Reactive data
const orderDetail = reactive({
  itemNumber: '1',
  sOrderMonth: '9',
  sOrderYear: '2025',
  sOrderSeq: '2090',
  mcardSeq: '282912',
  lotNumber: '',
  setOrder: '2,000',
  soStatus: 'Partially',
  soDate: '17/09/2025',
  productDescription: 'SHIPPING CASE ACOSTA JUMBO',
  pOrderRef: 'ASF/PO/25/1188',
  poDate: '17/09/2025',
  toDeliverySet: '0',
  unapplyFGoods: false,
  tickForYYes: false,
  note1: '',
  note2: ''
})

const itemRows = ref([
  { 
    name: 'Main', 
    pDesign: 'B1', 
    pcs: '1', 
    unit: 'Pcs', 
    order: '2,000', 
    delivery: '1,100', 
    reject: '35', 
    balance: '900', 
    available: '1,275', 
    maxDO: '22,000', 
    toDeliver: '', 
    deliverKG: '' 
  },
  { name: 'Fit1', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit2', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit3', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit4', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit5', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit6', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit7', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit8', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
  { name: 'Fit9', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' }
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

const handleToDeliverChange = () => {
  // Trigger reactivity untuk watchEffect
  // Vue akan otomatis mendeteksi perubahan melalui v-model
}

const handleDeliverySetChange = () => {
  // Trigger reactivity untuk watcher
  // Vue akan otomatis mendeteksi perubahan melalui v-model
}

const handleSave = () => {
  // Validasi: Cek apakah To Delivery Set sudah diisi
  const toDeliverySetValue = parseFloat(orderDetail.toDeliverySet) || 0
  
  // Validasi: Cek apakah ada item yang memiliki To Deliver
  const hasToDeliverItems = itemRows.value.some(item => {
    const toDeliverValue = parseFloat(item.toDeliver) || 0
    return toDeliverValue > 0
  })
  
  // Validasi: Harus ada To Delivery Set atau To Deliver yang diisi
  if (toDeliverySetValue <= 0 && !hasToDeliverItems) {
    error('Please fill in "To Delivery Set" or at least one "To Deliver" quantity before proceeding')
    return
  }
  
  const data = {
    orderDetail,
    itemRows: itemRows.value
  }
  
  // Store current data for packing details modal
  currentDetailData.value = data
  
  // Open Packing Details Modal
  showPackingDetailsModal.value = true
  success('Opening Packing Details Screen')
}

const handlePackingDetailsSave = (packingData) => {
  // Combine all data and emit to parent
  const completeData = {
    salesOrderDetail: currentDetailData.value,
    packingDetails: packingData
  }
  
  emit('save', completeData)
  showPackingDetailsModal.value = false
  success('Packing details saved successfully')
}

const handleSaveDeliveryOrder = (packingData) => {
  // Combine all data and emit save-delivery-order to parent
  const completeData = {
    salesOrderDetail: currentDetailData.value,
    packingDetails: packingData
  }
  
  emit('save-delivery-order', completeData)
  showPackingDetailsModal.value = false
  success('Delivery order will be saved')
}

// Flag untuk mencegah infinite loop
const isUpdatingFromDeliverySet = ref(false)

// Computed property untuk total "To Deliver" dari semua items
const totalToDeliver = computed(() => {
  return itemRows.value.reduce((total, item) => {
    const toDeliver = parseFloat(item.toDeliver) || 0
    return total + toDeliver
  }, 0)
})

// Computed property untuk menentukan apakah To Delivery Set required
const isToDeliverySetRequired = computed(() => {
  const toDeliverySetValue = parseFloat(orderDetail.toDeliverySet) || 0
  const hasToDeliverItems = itemRows.value.some(item => {
    const toDeliverValue = parseFloat(item.toDeliver) || 0
    return toDeliverValue > 0
  })
  
  // Required jika tidak ada yang diisi
  return toDeliverySetValue <= 0 && !hasToDeliverItems
})

// Watch untuk sinkronisasi dari "To Delivery Set" ke "To Deliver" items
watch(() => orderDetail.toDeliverySet, (newValue, oldValue) => {
  // Skip jika perubahan berasal dari watchEffect
  if (isUpdatingFromDeliverySet.value) return
  
  const deliverySetValue = parseFloat(newValue) || 0
  const currentTotal = itemRows.value.reduce((sum, item) => {
    return sum + (parseFloat(item.toDeliver) || 0)
  }, 0)
  
  // Hanya update jika ada perubahan signifikan dari user input
  if (Math.abs(deliverySetValue - currentTotal) > 0.01) {
    isUpdatingFromDeliverySet.value = true
    
    // Distribusikan nilai ke item pertama yang memiliki data (biasanya Main)
    const mainItem = itemRows.value.find(item => item.name === 'Main')
    if (mainItem) {
      mainItem.toDeliver = deliverySetValue.toString()
      
      // Reset item lainnya
      itemRows.value.forEach(item => {
        if (item.name !== 'Main') {
          item.toDeliver = ''
        }
      })
    }
    
    // Reset flag setelah update selesai
    setTimeout(() => {
      isUpdatingFromDeliverySet.value = false
    }, 0)
  }
})

// Watch effect untuk monitoring semua perubahan item secara otomatis
watchEffect(() => {
  if (!isUpdatingFromDeliverySet.value) {
    // Hitung total dari semua item "To Deliver"
    const total = itemRows.value.reduce((sum, item) => {
      const value = parseFloat(item.toDeliver) || 0
      return sum + value
    }, 0)
    
    // Update "To Delivery Set" jika berbeda
    if (Math.abs(total - parseFloat(orderDetail.toDeliverySet || 0)) > 0.01) {
      orderDetail.toDeliverySet = total.toString()
    }
  }
})

// Initialize with sales order data if available
onMounted(() => {
  if (props.salesOrderData) {
    // Populate form with sales order data
    if (props.salesOrderData.soNumber) {
      const soParts = props.salesOrderData.soNumber.split('-')
      if (soParts.length === 3) {
        orderDetail.sOrderMonth = soParts[0]
        orderDetail.sOrderYear = soParts[1]
        orderDetail.sOrderSeq = soParts[2]
      }
    }
    
    // Use data from selected order if available
    if (props.salesOrderData.selectedOrderData) {
      const selectedOrder = props.salesOrderData.selectedOrderData
      
      // Update with actual data from Sales Order Table
      orderDetail.mcardSeq = selectedOrder.mcardSeq || ''
      orderDetail.pOrderRef = selectedOrder.pOrderRef || selectedOrder.customerPORef || ''
      
      // Update item rows with actual data from selected order
      if (selectedOrder.items && selectedOrder.items.length > 0) {
        selectedOrder.items.forEach((item, index) => {
          if (index < itemRows.value.length) {
            const itemRow = itemRows.value[index]
            
            // Map data based on item type
            if (item.name === 'PD') {
              itemRow.pDesign = item.pDesign || 'B1'
            } else if (item.name === 'PCS') {
              itemRow.pcs = item.pDesign || '1'
            } else if (item.name === 'UNIT') {
              itemRow.unit = item.pDesign || 'KG'
            } else if (item.name === 'ORDER') {
              itemRow.order = item.pDesign || '450'
            } else if (item.name === 'DELIVERY') {
              itemRow.delivery = item.pDesign || ''
            } else if (item.name === 'REJECT') {
              itemRow.reject = item.pDesign || ''
            } else if (item.name === 'BALANCE') {
              itemRow.balance = item.pDesign || '450'
            } else if (item.name === 'AVAILABLE') {
              itemRow.available = item.pDesign || '450'
            } else if (item.name === 'MAX DO') {
              itemRow.maxDO = item.pDesign || '22000'
            }
          }
        })
        
        // Set default values for Main item based on the data
        const mainItem = itemRows.value.find(item => item.name === 'Main')
        if (mainItem) {
          // Find corresponding data from items
          const pdItem = selectedOrder.items.find(item => item.name === 'PD')
          const pcsItem = selectedOrder.items.find(item => item.name === 'PCS')
          const unitItem = selectedOrder.items.find(item => item.name === 'UNIT')
          const orderItem = selectedOrder.items.find(item => item.name === 'ORDER')
          const balanceItem = selectedOrder.items.find(item => item.name === 'BALANCE')
          const availableItem = selectedOrder.items.find(item => item.name === 'AVAILABLE')
          const maxDoItem = selectedOrder.items.find(item => item.name === 'MAX DO')
          
          mainItem.pDesign = pdItem?.pDesign || 'B1'
          mainItem.pcs = pcsItem?.pDesign || '1'
          mainItem.unit = unitItem?.pDesign || 'KG'
          mainItem.order = orderItem?.pDesign || '450'
          mainItem.balance = balanceItem?.pDesign || '450'
          mainItem.available = availableItem?.pDesign || '450'
          mainItem.maxDO = maxDoItem?.pDesign || '22000'
        }
      }
    } else {
      // Fallback to basic data
      if (props.salesOrderData.mcardSeq) {
        orderDetail.mcardSeq = props.salesOrderData.mcardSeq
      }
      
      if (props.salesOrderData.pOrderRef) {
        orderDetail.pOrderRef = props.salesOrderData.pOrderRef
      }
    }
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
</style>
