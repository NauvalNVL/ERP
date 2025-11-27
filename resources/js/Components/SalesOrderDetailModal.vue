<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-7xl bg-white rounded-xl shadow-2xl flex flex-col max-h-[calc(100vh-2rem)] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <i class="fas fa-clipboard-list text-white text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-white">Sales Order Detail Screen</h3>
          </div>
          <button @click="closeModal" class="p-2 rounded-full hover:bg-white/20 text-white transition-colors">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
          <!-- Compact Header Information -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- Order Information -->
              <div class="space-y-3">
                <h4 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1">Order Information</h4>
                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">Item#:</span>
                    <input v-model="orderDetail.itemNumber" type="text" class="w-16 px-2 py-1 text-xs border border-gray-300 rounded" value="1" readonly>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">Status:</span>
                    <input v-model="orderDetail.soStatus" type="text" class="w-20 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">S/Order#:</span>
                    <div class="flex items-center space-x-1 flex-1">
                      <input v-model="orderDetail.sOrderMonth" type="text" class="w-8 px-1 py-1 text-xs border border-gray-300 rounded" placeholder="MM">
                      <span class="text-gray-400 text-xs">-</span>
                      <input v-model="orderDetail.sOrderYear" type="text" class="w-12 px-1 py-1 text-xs border border-gray-300 rounded" placeholder="YYYY">
                      <span class="text-gray-400 text-xs">-</span>
                      <input v-model="orderDetail.sOrderSeq" type="text" class="w-16 px-1 py-1 text-xs border border-gray-300 rounded" placeholder="Seq">
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">M/Card Seq#:</span>
                    <input v-model="orderDetail.mcardSeq" type="text" class="w-24 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">S/O Date:</span>
                    <input v-model="orderDetail.soDate" type="text" class="w-24 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">Lot#:</span>
                    <input v-model="orderDetail.lotNumber" type="text" class="w-20 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                </div>
              </div>

              <!-- Product Information -->
              <div class="space-y-3">
                <h4 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1">Product Information</h4>
                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">Model:</span>
                    <input v-model="orderDetail.productDescription" type="text" class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded" readonly>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">Set Order:</span>
                    <input v-model="orderDetail.setOrder" type="text" class="w-20 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">P/Order Ref#:</span>
                    <input v-model="orderDetail.pOrderRef" type="text" class="w-28 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 w-20 flex-shrink-0">P/O Date:</span>
                    <input v-model="orderDetail.poDate" type="text" class="w-24 px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                </div>
              </div>

              <!-- Delivery Settings - Highlighted -->
              <div class="space-y-3 bg-gradient-to-br from-yellow-50 to-orange-50 p-3 rounded-lg border-2 border-yellow-300 shadow-sm">
                <div class="flex items-center space-x-2">
                  <div class="p-1 bg-yellow-400 rounded-full">
                    <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                  </div>
                  <h4 class="text-sm font-semibold text-yellow-800">Delivery Settings</h4>
                </div>
                <div class="space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-yellow-700 w-24 flex-shrink-0 font-medium">To Delivery Set:</span>
                    <div class="flex items-center space-x-1">
                      <input 
                        v-model="orderDetail.toDeliverySet"
                        type="text"
                        class="w-16 px-2 py-1 text-xs border-2 border-yellow-400 rounded focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 bg-white"
                        :class="{ 'border-red-500 bg-red-50': (isToDeliverySetRequired && !orderDetail.toDeliverySet) || isToDeliverySetExceed }"
                        @input="handleDeliverySetChange"
                      >
                      <span class="text-red-500 font-bold text-sm">*</span>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-yellow-700 w-24 flex-shrink-0">Options:</span>
                    <div class="flex items-center space-x-3">
                      <div class="flex items-center space-x-1">
                        <input v-model="orderDetail.unapplyFGoods" type="checkbox" class="w-3 h-3 text-yellow-600 border-yellow-300 rounded focus:ring-yellow-500">
                        <span class="text-xs text-gray-700">Unapply F/Goods</span>
                      </div>
                      <div class="flex items-center space-x-1">
                        <input v-model="orderDetail.tickForYYes" type="checkbox" class="w-3 h-3 text-yellow-600 border-yellow-300 rounded focus:ring-yellow-500">
                        <span class="text-xs text-gray-700">Tick for Y-Yes</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Status & Notes -->
              <div class="space-y-3">
                <h4 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1">Notes</h4>
                <div class="space-y-2">
                  <div>
                    <span class="text-xs text-gray-600 block mb-1">Note1:</span>
                    <input v-model="orderDetail.note1" type="text" class="w-full px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                  <div>
                    <span class="text-xs text-gray-600 block mb-1">Note2:</span>
                    <input v-model="orderDetail.note2" type="text" class="w-full px-2 py-1 text-xs border border-gray-300 rounded">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Compact Data Table -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-2 border-b border-gray-200">
              <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                <i class="fas fa-table text-gray-500 mr-2"></i>
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
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Order</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Delivery</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Reject</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Balance</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Available</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300">Max DO</th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 border-r border-gray-300 bg-yellow-50">
                      To Deliver <span class="text-red-500">*</span>
                    </th>
                    <th class="px-2 py-1.5 text-right font-medium text-gray-700 bg-gray-50">Deliver KG</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="(item, index) in itemRows" :key="index" class="hover:bg-blue-50 transition-colors">
                    <td class="px-2 py-1.5 font-medium text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <input v-model="item.pDesign" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50">
                    </td>
                    <td class="px-2 py-1.5 text-center border-r border-gray-200">
                      <input v-model="item.pcs" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-center">
                    </td>
                    <td class="px-2 py-1.5 border-r border-gray-200">
                      <input v-model="item.unit" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.order" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.delivery" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.reject" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.balance" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.available" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200">
                      <input v-model="item.maxDO" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-transparent hover:bg-gray-50 text-right">
                    </td>
                    <td class="px-2 py-1.5 text-right border-r border-gray-200 bg-yellow-50">
                      <input 
                        v-model="item.toDeliver"
                        type="text"
                        class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-yellow-50 hover:bg-yellow-100 text-right font-medium"
                        :class="{ 'ring-1 ring-red-500': isToDeliverySetRequired }"
                        @input="handleToDeliverManualChange(item)"
                      >
                    </td>
                    <td class="px-2 py-1.5 text-right bg-gray-50">
                      <input v-model="item.deliverKG" type="text" class="w-full px-1 py-0.5 text-xs border-0 focus:ring-0 bg-gray-50 hover:bg-gray-100 text-right">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Alert Messages -->
          <div class="space-y-2 mt-4">
            <!-- Validation Warning -->
            <div v-if="isToDeliverySetRequired" class="bg-red-50 border-l-4 border-red-400 p-3 rounded">
              <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>
                <p class="text-sm text-red-800 font-medium">
                  Please fill in "To Delivery Set" or at least one "To Deliver" quantity to proceed
                </p>
              </div>
            </div>

            <!-- Exceed Warning -->
            <div v-if="isToDeliverySetExceed" class="bg-orange-50 border-l-4 border-orange-400 p-3 rounded">
              <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-orange-400 mr-2"></i>
                <p class="text-sm text-orange-800 font-medium">
                  To Delivery Set cannot exceed Balance ({{ maxDeliverable.toLocaleString() }})
                </p>
              </div>
            </div>

            <!-- Warning Note -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
              <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                <p class="text-sm text-blue-800 font-medium">
                  This Customer allows to deliver +/-1000% quantity for this DO
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <div class="text-xs text-gray-600">
            <i class="fas fa-info-circle mr-1"></i>
            Fill delivery quantities and click Save Changes to continue
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
              :disabled="isToDeliverySetRequired"
              :class="[
                'px-4 py-2 text-sm font-medium rounded-lg focus:outline-none focus:ring-2 transition-all',
                isToDeliverySetRequired 
                  ? 'text-gray-400 bg-gray-300 cursor-not-allowed' 
                  : 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-500 shadow-lg hover:shadow-xl'
              ]"
            >
              <i class="fas fa-save mr-2"></i>
              Save Changes
            </button>
          </div>
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

const handleToDeliverManualChange = (item) => {
  // Ketika user mengubah To Deliver secara manual, hitung ulang To Delivery Set
  // berdasarkan Main component
  // Trigger reactivity untuk watchEffect - Vue akan otomatis mendeteksi perubahan
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
// Logika: Ketika user mengisi "To Delivery Set", setiap komponen akan mendapat nilai = toDeliverySet * pcs komponen tersebut
watch(() => orderDetail.toDeliverySet, (newValue, oldValue) => {
  // Skip jika perubahan berasal dari watchEffect
  if (isUpdatingFromDeliverySet.value) return
  
  const deliverySetValue = parseFloat(newValue) || 0
  
  // Flag untuk mencegah infinite loop
  isUpdatingFromDeliverySet.value = true
  
  // Update setiap komponen berdasarkan pcs-nya
  itemRows.value.forEach(item => {
    // Hanya update komponen yang memiliki pDesign (komponen aktif)
    if (item.pDesign && item.pDesign.trim() !== '') {
      const pcsValue = parseFloat(item.pcs) || 1 // Default ke 1 jika pcs kosong
      const calculatedToDeliver = deliverySetValue * pcsValue
      item.toDeliver = calculatedToDeliver > 0 ? calculatedToDeliver.toString() : ''
    } else {
      // Komponen tidak aktif, set ke kosong
      item.toDeliver = ''
    }
  })
  
  // Reset flag setelah update selesai
  setTimeout(() => {
    isUpdatingFromDeliverySet.value = false
  }, 0)
})

// Watch effect untuk monitoring perubahan manual pada To Deliver items
// Jika user mengubah To Deliver manual, jangan auto-update To Delivery Set
watchEffect(() => {
  if (!isUpdatingFromDeliverySet.value) {
    // Hitung total dari Main component saja untuk To Delivery Set
    // Karena To Delivery Set adalah dalam satuan "set" bukan total pieces
    const mainItem = itemRows.value.find(item => item.name === 'Main')
    if (mainItem) {
      const mainToDeliver = parseFloat(mainItem.toDeliver) || 0
      const mainPcs = parseFloat(mainItem.pcs) || 1
      
      // To Delivery Set = Main To Deliver / Main Pcs
      const calculatedDeliverySet = mainPcs > 0 ? mainToDeliver / mainPcs : 0
      
      // Update To Delivery Set jika berbeda (dengan toleransi untuk floating point)
      const currentDeliverySet = parseFloat(orderDetail.toDeliverySet) || 0
      if (Math.abs(calculatedDeliverySet - currentDeliverySet) > 0.01) {
        orderDetail.toDeliverySet = calculatedDeliverySet > 0 ? calculatedDeliverySet.toString() : '0'
      }
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

/* Alert message animations */
.bg-red-50, .bg-orange-50, .bg-blue-50 {
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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
</style>
