<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-gray-100 p-6 text-left align-middle shadow-xl transition-all">
              <!-- Header -->
              <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50 rounded-t-xl -mx-6 -mt-6 mb-4">
                <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                  Sales Order Table [Sorted by S/Order#]
                </DialogTitle>
                <div class="flex items-center space-x-2">
                  <button @click="closeModal" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors" title="Close">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <!-- Main Sales Order Table -->
              <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-200">Sales Orders</div>
                <div class="overflow-x-auto max-h-80 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                      <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SO#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CUSTOMER PO#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MCS#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS D/LOCATION</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(order, index) in salesOrders" :key="index" 
                          :class="{'bg-blue-100': selectedOrderIndex === index, 'hover:bg-gray-50': selectedOrderIndex !== index}"
                          @click="selectOrder(index)">
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.soNumber }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.customerPo }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.acNumber }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.mcsNumber }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.statusLocation }}</td>
                      </tr>
                      <tr v-if="salesOrders.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No sales orders found</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Search Section -->
              <div class="mb-4 flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Search:</label>
                <input type="text" v-model="searchTerm1" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0" />
                <input type="text" v-model="searchTerm2" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0" />
                <input type="text" v-model="searchTerm3" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0" />
                <button @click="searchOrders" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                  Search
                </button>
                <div class="flex items-center space-x-2 ml-4">
                  <label class="text-sm font-medium text-gray-700">S/Order#:</label>
                  <input type="text" v-model="soNumberFilter" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Enter SO#" />
                </div>
              </div>

              <!-- Order Information Section -->
              <div class="mb-4 bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Order Information</h4>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                    <input type="text" v-model="orderInfo.customerName" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Model:</label>
                    <input type="text" v-model="orderInfo.model" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                    <input type="text" v-model="orderInfo.orderMode" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                    <div class="flex space-x-2">
                      <input type="text" v-model="orderInfo.salespersonCode" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                      <input type="text" v-model="orderInfo.salespersonName" class="w-24 px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                    <input type="text" v-model="orderInfo.orderGroup" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                    <input type="text" v-model="orderInfo.orderType" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" readonly />
                  </div>
                </div>
              </div>

              <!-- Item Details Table -->
              <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-blue-50 px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-200">Item Details</div>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                      <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ITEM</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MAIN</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F1</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F2</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F3</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F4</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F5</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F6</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F7</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F8</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F9</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(item, index) in itemDetails" :key="index" 
                          :class="{'bg-blue-100': selectedItemIndex === index, 'hover:bg-gray-50': selectedItemIndex !== index}"
                          @click="selectItem(index)">
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.main" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f1" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f2" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f3" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f4" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f5" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f6" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f7" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f8" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                          <input type="text" v-model="item.f9" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Sales Order Delivery Schedule Section -->
              <div class="mb-4 flex items-center space-x-2">
                <i class="fas fa-play text-green-600"></i>
                <span class="text-sm font-medium text-gray-700">Sales Order Delivery Schedule</span>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-center space-x-4">
                <button @click="zoomOrder" class="px-6 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition-colors">
                  Zoom
                </button>
                <button @click="selectOrder" class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                  Select
                </button>
                <button @click="closeModal" class="px-6 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import axios from 'axios'

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

const emit = defineEmits(['close', 'select'])

// Reactive data
const salesOrders = ref([])
const selectedOrderIndex = ref(-1)
const selectedItemIndex = ref(0) // Default to PD (index 0)
const searchTerm1 = ref('0')
const searchTerm2 = ref('0')
const searchTerm3 = ref('0')
const soNumberFilter = ref('')

const orderInfo = reactive({
  customerName: '',
  model: '',
  orderMode: '0-Order by Customer + Deliver & Invoice to Customer',
  salespersonCode: '',
  salespersonName: '',
  orderGroup: 'Sales',
  orderType: 'S1'
})

const itemDetails = ref([
  { name: 'PD', main: 'B1', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'PCS', main: '1', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'UNIT', main: 'Pcs', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'ORDER', main: '450', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'NET DELIVERY', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'BALANCE', main: '450', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' }
])

// Computed properties
const selectedOrder = computed(() => {
  return selectedOrderIndex.value >= 0 ? salesOrders.value[selectedOrderIndex.value] : null
})

// Methods
const closeModal = () => {
  emit('close')
}

const selectOrder = (index) => {
  if (typeof index === 'number') {
    selectedOrderIndex.value = index
    updateOrderInfo()
  } else {
    // Select button clicked
    if (selectedOrder.value) {
      emit('select', selectedOrder.value)
      closeModal()
    }
  }
}

const selectItem = (index) => {
  selectedItemIndex.value = index
}

const updateOrderInfo = () => {
  if (selectedOrder.value) {
    orderInfo.customerName = selectedOrder.value.customerName || ''
    orderInfo.model = selectedOrder.value.model || ''
    orderInfo.salespersonCode = selectedOrder.value.salespersonCode || ''
    orderInfo.salespersonName = selectedOrder.value.salespersonName || ''
    orderInfo.orderGroup = selectedOrder.value.orderGroup || 'Sales'
    orderInfo.orderType = selectedOrder.value.orderType || 'S1'
  }
}

const searchOrders = async () => {
  try {
    // Fetch sales orders from API based on customer code
    const response = await axios.get('/api/sales-orders', {
      params: {
        customer_code: props.customerData?.code,
        month: searchTerm1.value,
        year: searchTerm2.value,
        sequence: searchTerm3.value,
        so_number: soNumberFilter.value
      }
    })
    
    if (response.data.success) {
      // Map API data to component format
      salesOrders.value = response.data.data.map(order => ({
        soNumber: order.SO_Num,
        customerPo: order.PO_Num,
        acNumber: order.AC_Num,
        mcsNumber: order.MCS_Num,
        statusLocation: order.STS,
        customerName: order.AC_NAME,
        model: order.MODEL,
        salespersonCode: order.SLM,
        salespersonName: order.SLM, // Assuming SLM contains salesperson name
        orderGroup: order.GROUP_ || 'Sales',
        orderType: order.TYPE || 'S1'
      }))
      
      // Select first order by default
      if (salesOrders.value.length > 0) {
        selectedOrderIndex.value = 0
        updateOrderInfo()
      }
    } else {
      console.error('API Error:', response.data.message)
      salesOrders.value = []
    }
  } catch (error) {
    console.error('Error fetching sales orders:', error)
    salesOrders.value = []
  }
}

const zoomOrder = () => {
  if (selectedOrder.value) {
    console.log('Zooming order:', selectedOrder.value)
    // Implement zoom functionality
  }
}

// Watch for modal open to load data
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    loadSalesOrders()
  }
})

const loadSalesOrders = async () => {
  try {
    // Load sales orders for the customer
    if (props.customerData && props.customerData.code) {
      console.log('Loading sales orders for customer:', props.customerData.code)
      await searchOrders()
    } else {
      console.log('No customer selected, loading all sales orders')
      await searchOrders()
    }
  } catch (error) {
    console.error('Error loading sales orders:', error)
  }
}

onMounted(() => {
  if (props.isOpen) {
    loadSalesOrders()
  }
})
</script>

<style scoped>
.scrollbar-thin {
  scrollbar-width: thin;
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f5f9;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
