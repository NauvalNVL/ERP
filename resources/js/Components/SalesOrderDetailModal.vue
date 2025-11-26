<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-7xl bg-white rounded-lg shadow-xl flex flex-col max-h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
          <h3 class="text-lg font-semibold text-white">Sales Order Detail Screen</h3>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
          <!-- Top Information Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                  placeholder=""
                >
                <input 
                  v-model="orderDetail.sOrderYear"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
                <input 
                  v-model="orderDetail.sOrderSeq"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-16">M/Card Seq#:</label>
                <input 
                  v-model="orderDetail.mcardSeq"
                  type="text"
                  class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
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
                  placeholder=""
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
                  placeholder=""
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">S/O Date:</label>
                <input 
                  v-model="orderDetail.soDate"
                  type="text"
                  class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">P/Order Ref#:</label>
                <input 
                  v-model="orderDetail.pOrderRef"
                  type="text"
                  class="w-32 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
              </div>
              
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 w-20">P/O Date:</label>
                <input 
                  v-model="orderDetail.poDate"
                  type="text"
                  class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                >
              </div>
            </div>

            <!-- Right Column - Product Description -->
            <div class="md:col-span-2 lg:col-span-2">
              <div class="flex items-center space-x-2 mb-3">
                <input 
                  v-model="orderDetail.productDescription"
                  type="text"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  placeholder=""
                  readonly
                >
              </div>
            </div>
          </div>

          <!-- Delivery Settings -->
          <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">
                  To Delivery Set: <span class="text-red-500">*</span>
                </label>
                <input 
                  v-model="orderDetail.toDeliverySet"
                  type="text"
                  class="w-12 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': (isToDeliverySetRequired && !orderDetail.toDeliverySet) || isToDeliverySetExceed }"
                  placeholder=""
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
          <div class="border border-gray-300 rounded-lg overflow-hidden overflow-x-auto">
            <table class="min-w-[960px] divide-y divide-gray-200">
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
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pcs"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-center"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.unit"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.order"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.delivery"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.reject"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.balance"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.available"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.maxDO"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right"
                      :placeholder="''"
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

            <!-- Exceed Warning -->
            <div v-if="isToDeliverySetExceed" class="bg-red-100 border border-red-300 rounded p-3">
              <p class="text-sm text-red-800 font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                To Delivery Set cannot exceed Balance ({{ maxDeliverable.toLocaleString() }}).
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
        <div class="flex items-center justify-end space-x-4 p-4 border-t border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
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
import axios from 'axios'

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

// Reactive data (no hardcoded defaults; will be populated from selected SO)
const orderDetail = reactive({
  itemNumber: '1',
  sOrderMonth: '',
  sOrderYear: '',
  sOrderSeq: '',
  mcardSeq: '',
  lotNumber: '',
  setOrder: '',
  soStatus: '',
  soDate: '',
  productDescription: '',
  pOrderRef: '',
  poDate: '',
  toDeliverySet: '0',
  unapplyFGoods: false,
  tickForYYes: false,
  note1: '',
  note2: ''
})

const itemRows = ref([
  { name: 'Main', pDesign: '', pcs: '', unit: '', order: '', delivery: '', reject: '', balance: '', available: '', maxDO: '', toDeliver: '', deliverKG: '' },
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

// Default Balance to Order only when balance is empty (do not override API-computed balance)
watchEffect(() => {
  if (Array.isArray(itemRows.value)) {
    itemRows.value.forEach((row) => {
      if (!row) return
      const isEmpty = (v) => v === '' || v === null || v === undefined
      if (!isEmpty(row.balance) || row.order === undefined) return
      row.balance = row.order
    })
  }
})

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

  // Validasi: To Delivery Set tidak boleh melebihi Balance
  if (isToDeliverySetExceed.value) {
    error('To Delivery Set cannot exceed Balance')
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

// Max deliverable (Balance) for validation
const maxDeliverable = computed(() => {
  const main = itemRows.value.find(r => r.name === 'Main') || {}
  const orderQty = Number((main.order || '0').toString().replace(/,/g, '')) || 0
  const netDel = Number((main.delivery || '0').toString().replace(/,/g, '')) || 0
  const bal = orderQty - netDel
  return bal > 0 ? bal : 0
})

// Flag: To Delivery Set exceeds Balance
const isToDeliverySetExceed = computed(() => {
  const val = Number((orderDetail.toDeliverySet || '0').toString().replace(/,/g, '')) || 0
  return val > maxDeliverable.value
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
async function hydrateFromSalesOrderData() {
  if (!props.salesOrderData) return
  const soNo = props.salesOrderData.soNumber || props.salesOrderData.selectedOrderData?.soNumber || ''
  if (soNo) {
    const soParts = soNo.split('-')
    if (soParts.length === 3) {
      orderDetail.sOrderMonth = soParts[0]
      orderDetail.sOrderYear = soParts[1]
      orderDetail.sOrderSeq = soParts[2]
    }
    // store full so number for downstream consumers
    orderDetail.soNumber = soNo
  }
  try {
    if (soNo) {
      const response = await axios.get(`/api/sales-order/${soNo}/detail`)
      if (response.data?.success) {
        const data = response.data.data
        orderDetail.mcardSeq = data.order_info?.mcard_seq || props.salesOrderData.selectedOrderData?.mcardSeq || ''
        orderDetail.pOrderRef = data.order_info?.customer_po_number || props.salesOrderData.selectedOrderData?.pOrderRef || ''
        orderDetail.productDescription = data.order_info?.model || ''
        orderDetail.soDate = data.order_info?.so_date || ''
        orderDetail.soStatus = data.order_info?.so_status || ''
        orderDetail.poDate = data.order_info?.po_date || ''
        orderDetail.setOrder = data.item_details?.order_qty || ''
        // expose MC pcs per bundle for Packing Details modal
        orderDetail.pcsPerBdl = data.item_details?.pcs_per_bdl || ''
        const main = itemRows.value.find(r => r.name === 'Main')
        if (main) {
          main.pDesign = data.item_details?.pd ?? ''
          main.pcs = data.item_details?.pcs ?? ''
          main.unit = data.item_details?.unit ?? ''
          main.order = data.item_details?.order_qty ?? ''
          main.delivery = data.item_details?.net_delivery ?? 0
          // BALANCE = ORDER - NET DELIVERY (prefer API value if provided)
          const apiBalance = data.item_details?.balance
          if (apiBalance !== undefined && apiBalance !== null && apiBalance !== '') {
            main.balance = apiBalance
          } else {
            const orderQty = Number((main.order || '0').toString().replace(/,/g, '')) || 0
            const netDel = Number((main.delivery || '0').toString().replace(/,/g, '')) || 0
            const bal = orderQty - netDel
            main.balance = Number.isFinite(bal) ? bal.toString() : main.order
          }
          // Per request: keep these empty for now
          main.reject = ''
          main.available = ''
          main.maxDO = ''
        }
        if (Array.isArray(data.fittings)) {
          data.fittings.forEach((fitting, idx) => {
            if (idx < 9) {
              const row = itemRows.value[idx + 1]
              if (row) {
                row.pDesign = fitting.design || ''
                row.pcs = fitting.pcs || ''
                row.unit = fitting.unit || ''
                row.order = fitting.order_qty ?? ''
                row.delivery = fitting.net_delivery ?? ''
                // Calculate balance per component: ORDER - DELIVERY
                const orderQty = Number((row.order || '0').toString().replace(/,/g, '')) || 0
                const netDel = Number((row.delivery || '0').toString().replace(/,/g, '')) || 0
                const bal = orderQty - netDel
                row.balance = fitting.balance !== undefined && fitting.balance !== null && fitting.balance !== '' 
                  ? fitting.balance 
                  : (Number.isFinite(bal) ? bal.toString() : row.order)
                // Keep these empty for now
                row.reject = ''
                row.available = ''
                row.maxDO = ''
              }
            }
          })
        }
        return
      }
    }
  } catch (e) {}
  hydrateFromSelectedOrder()
}

onMounted(() => {
  hydrateFromSalesOrderData()
})

watch(() => props.salesOrderData, () => {
  hydrateFromSalesOrderData()
}, { deep: true })

function hydrateFromSelectedOrder() {
  const selectedOrder = props.salesOrderData?.selectedOrderData
  if (!selectedOrder) return

  orderDetail.mcardSeq = selectedOrder.mcardSeq || ''
  orderDetail.pOrderRef = selectedOrder.pOrderRef || selectedOrder.customerPORef || ''
  orderDetail.productDescription = selectedOrder.model || ''
  // Fallbacks if API not called
  orderDetail.soStatus = selectedOrder.soStatus || ''
  orderDetail.soDate = selectedOrder.soDate || orderDetail.soDate || ''
  orderDetail.poDate = selectedOrder.poDate || orderDetail.poDate || ''
  if (selectedOrder.soNumber) {
    orderDetail.soNumber = selectedOrder.soNumber
  }

  if (Array.isArray(selectedOrder.items)) {
    const main = itemRows.value.find(r => r.name === 'Main')
    // Try to find main components by name
    const findVal = (name) => selectedOrder.items.find(i => i.name === name)?.pDesign || ''
    if (main) {
      main.pDesign = findVal('PD')
      main.pcs = findVal('PCS')
      main.unit = findVal('UNIT')
      main.order = findVal('ORDER')
      // Balance should mirror Order in fallback too
      main.balance = main.order
      // Per request: keep these empty for now
      main.reject = ''
      main.available = ''
      main.maxDO = ''
    }
  }
}
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
