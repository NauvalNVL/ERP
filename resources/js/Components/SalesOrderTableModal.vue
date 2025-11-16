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
            <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-gray-100 p-6 text-left align-middle shadow-xl transition-all flex flex-col max-h-[calc(100vh-4rem)]">
              <!-- Header -->
              <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl -mx-6 -mt-6 mb-4">
                <DialogTitle as="h3" class="text-lg font-semibold text-white">
                  {{ props.customerData?.code === 'ALL' ? 'Outstanding Sales Orders [Sorted by S/Order#]' : 'Sales Order Table [Sorted by S/Order#]' }}
                </DialogTitle>
                <div class="flex items-center space-x-2">
                  <button @click="closeModal" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors" title="Close">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <div class="flex-1 overflow-y-auto space-y-4">
                <!-- Main Sales Order Table -->
                <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                  <div class="bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-200">
                    Sales Orders
                    <span v-if="isLoading" class="ml-2 text-blue-600">
                      <i class="fas fa-spinner fa-spin"></i> Loading...
                    </span>
                    <span v-else-if="salesOrders.length > 0" class="ml-2 text-green-600">
                      ({{ salesOrders.length }} order(s) found)
                    </span>
                  </div>
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
                        <template v-if="isLoading">
                          <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-blue-500">
                              <i class="fas fa-spinner fa-spin mr-2"></i> Loading sales orders...
                            </td>
                          </tr>
                        </template>
                        <template v-else-if="salesOrders.length === 0">
                          <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                              No sales orders found for this customer
                            </td>
                          </tr>
                        </template>
                        <template v-else>
                          <tr v-for="(order, index) in salesOrders" :key="index" 
                              :class="{'bg-blue-100': selectedOrderIndex === index, 'hover:bg-gray-50': selectedOrderIndex !== index}"
                              @click="selectOrder(index)">
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.soNumber || '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.customerPo || '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.acNumber || '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.mcsNumber || '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.statusLocation || '-' }}</td>
                          </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Search Section -->
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:space-x-4">
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
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <input type="text" v-model="orderInfo.salespersonCode" class="w-24 px-2 py-1 border border-gray-300 rounded text-sm" readonly placeholder="Code" />
                        <input type="text" v-model="orderInfo.salespersonName" class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly placeholder="Name" />
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
              </div>

              <!-- Action Buttons (Footer) -->
              <div class="flex justify-center space-x-4 mt-4 px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-b-xl -mx-6 -mb-6">
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
import { ref, reactive, computed, watch, onMounted, watchEffect } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'

const { success, error, info } = useToast()

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
const isLoading = ref(false)

const orderInfo = reactive({
  customerName: '',
  model: '',
  orderMode: '',
  salespersonCode: '',
  salespersonName: '',
  orderGroup: '',
  orderType: ''
})

const itemDetails = ref([
  { name: 'PD', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'PCS', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'UNIT', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'ORDER', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'NET DELIVERY', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' },
  { name: 'BALANCE', main: '', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' }
])

// Default BALANCE to ORDER only when balance is empty (avoid overriding API-computed balance)
watchEffect(() => {
  if (Array.isArray(itemDetails.value) && itemDetails.value.length >= 6) {
    const orderRow = itemDetails.value[3]
    const balanceRow = itemDetails.value[5]
    if (orderRow && balanceRow) {
      const isEmpty = (v) => v === '' || v === null || v === undefined
      if (isEmpty(balanceRow.main)) balanceRow.main = orderRow.main
      // For F1..F9, only fill if empty
      for (let i = 1; i <= 9; i++) {
        const key = `f${i}`
        if (orderRow[key] !== undefined && isEmpty(balanceRow[key])) {
          balanceRow[key] = orderRow[key]
        }
      }
    }
  }
})

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
    // Fetch and update order info when selecting
    updateOrderInfo()
  } else {
    // Select button clicked
    if (selectedOrder.value) {
      // Create complete order data with all necessary information
      const completeOrderData = {
        ...selectedOrder.value,
        // Add item details for Sales Order Detail Screen
        items: itemDetails.value.map(item => ({
          name: item.name,
          pDesign: item.main,
          pcs: item.main,
          unit: item.name === 'UNIT' ? item.main : 'KG',
          order: item.main,
          delivery: '',
          reject: '',
          balance: item.main,
          available: item.main,
          maxDO: ''
        })),
        // Add order information
        orderInfo: {
          ...orderInfo
        },
        // Ensure mcardSeq and pOrderRef are available
        mcardSeq: selectedOrder.value.mcardSeq || selectedOrder.value.mcsNumber || '',
        pOrderRef: selectedOrder.value.pOrderRef || selectedOrder.value.customerPo || ''
      }
      
      emit('select', completeOrderData)
      closeModal()
    }
  }
}

const selectItem = (index) => {
  selectedItemIndex.value = index
}

const updateOrderInfo = async () => {
  if (selectedOrder.value) {
    try {
      // Fetch detailed sales order data from new endpoint
      const response = await axios.get(`/api/sales-order/${selectedOrder.value.soNumber}/detail`)
      
      console.log('API Response:', response.data)
      
      if (response.data.success) {
        const data = response.data.data
        
        console.log('Order Info from API:', data.order_info)
        console.log('Salesperson Code:', data.order_info.salesperson_code)
        console.log('Salesperson Name:', data.order_info.salesperson_name)
        
        // Update order information
        orderInfo.customerName = data.order_info.customer_name || ''
        orderInfo.model = data.order_info.model || ''
        orderInfo.orderMode = data.order_info.order_mode || '0-Order by Customer + Deliver & Invoice to Customer'
        orderInfo.salespersonCode = data.order_info.salesperson_code || ''
        orderInfo.salespersonName = data.order_info.salesperson_name || ''
        orderInfo.orderGroup = data.order_info.order_group || 'Sales'
        orderInfo.orderType = data.order_info.order_type || 'S1'
        
        console.log('Updated orderInfo:', orderInfo)
        
        // Update item details - Main item (PD row)
        if (data.item_details) {
          // PD (Product Design)
          itemDetails.value[0].main = data.item_details.pd ?? ''
          // PCS (Pieces per set)
          itemDetails.value[1].main = data.item_details.pcs ?? ''
          // UNIT
          itemDetails.value[2].main = data.item_details.unit ?? ''
          // ORDER (Order Quantity)
          itemDetails.value[3].main = data.item_details.order_qty ?? ''
          // NET DELIVERY
          itemDetails.value[4].main = data.item_details.net_delivery ?? 0
          // BALANCE = ORDER - NET DELIVERY (from API if provided, otherwise default later by watcher)
          itemDetails.value[5].main = (data.item_details.balance ?? '')
        }
        
        // Update fittings (F1-F9) if available
        if (data.fittings && data.fittings.length > 0) {
          data.fittings.forEach((fitting, index) => {
            if (index < 9) { // F1-F9
              const colKey = `f${index + 1}`
              // PD row - fitting design
              itemDetails.value[0][colKey] = fitting.design || ''
              // PCS row - fitting pcs
              itemDetails.value[1][colKey] = fitting.pcs || ''
              // UNIT row - fitting unit
              itemDetails.value[2][colKey] = fitting.unit || ''
            }
          })
        }
        
        success('Sales order details loaded successfully')
      } else {
        error(response.data.message || 'Failed to load sales order details')
      }
    } catch (err) {
      console.error('Error fetching sales order details:', err)
      console.error('Error response:', err.response)
      error('Error loading sales order details')
    }
  }
}

const searchOrders = async () => {
  isLoading.value = true
  try {
    console.log('searchOrders called')
    console.log('Customer data:', props.customerData)
    
    // Fetch sales orders from API based on customer code
    const params = {}
    
    // If customer code is 'ALL', fetch all outstanding sales orders
    // Otherwise, filter by specific customer
    if (props.customerData?.code && props.customerData.code !== 'ALL') {
      params.customer_code = props.customerData.code
    } else {
      // For AmendSO - get all outstanding sales orders
      params.status = 'Outstanding'
      params.all_customers = true
    }
    
    console.log('API params:', params)
    
    // Add search parameters if provided
    if (searchTerm1.value && searchTerm1.value !== '0') {
      params.month = searchTerm1.value
    }
    if (searchTerm2.value && searchTerm2.value !== '0') {
      params.year = searchTerm2.value
    }
    if (searchTerm3.value && searchTerm3.value !== '0') {
      params.sequence = searchTerm3.value
    }
    if (soNumberFilter.value) {
      params.so_number = soNumberFilter.value
    }
    
    console.log('Calling API /api/sales-orders with params:', params)
    
    // Use different endpoint for all outstanding orders
    const endpoint = params.all_customers ? '/api/sales-orders/outstanding' : '/api/sales-orders'
    const response = await axios.get(endpoint, { params })
    console.log('API response:', response.data)
    console.log('API response.data.data:', response.data.data)
    console.log('First order raw:', response.data.data[0])
    
    if (response.data.success) {
      console.log('Raw response.data.data:', response.data.data)
      console.log('Number of orders:', response.data.data.length)
      
      // Log debug information if available
      if (response.data.debug) {
        console.log('=== DEBUG INFO ===')
        console.log('Total SO records in database:', response.data.debug.total_so_records)
        console.log('All statuses found:', response.data.debug.all_statuses)
        console.log('Status counts:', response.data.debug.status_counts)
        console.log('Query result count:', response.data.debug.query_result_count)
        console.log('==================')
      }
      
      // Map API data to component format and sort by SO number
      salesOrders.value = response.data.data
        .map((order, idx) => {
          const mapped = {
            soNumber: order.so_number,
            customerPo: order.customer_po_number || '',
            acNumber: order.customer_code || '',
            mcsNumber: order.master_card_seq || '',
            statusLocation: order.status || 'Outs',
            customerName: order.customer_name || '',
            model: order.master_card_model || '',
            salespersonCode: order.salesperson_code || '',
            salespersonName: order.salesperson_code || '',
            orderGroup: order.order_group || 'Sales',
            orderType: order.order_type || 'S1'
          }
          if (idx === 0) {
            console.log('First order before mapping:', order)
            console.log('First order after mapping:', mapped)
          }
          return mapped
        })
        .sort((a, b) => {
          // Sort by SO number in descending order (newest first)
          return b.soNumber.localeCompare(a.soNumber)
        })
      
      console.log('Mapped sales orders:', salesOrders.value)
      console.log('Total orders loaded:', salesOrders.value.length)
      console.log('Sample order object keys:', salesOrders.value[0] ? Object.keys(salesOrders.value[0]) : 'none')
      console.log('Sample order values:', salesOrders.value[0])
      
      // Do NOT select first order by default - keep containers empty
      selectedOrderIndex.value = -1
      clearOrderInfo()
      
      if (salesOrders.value.length === 0) {
        if (params.all_customers) {
          info('No outstanding sales orders found')
        } else {
          info('No sales orders found for this customer')
        }
      } else {
        if (params.all_customers) {
          success(`Loaded ${salesOrders.value.length} outstanding sales order(s)`)
        } else {
          success(`Loaded ${salesOrders.value.length} sales order(s)`)
        }
      }
    } else {
      console.error('API Error:', response.data.message)
      error('Failed to load sales orders: ' + (response.data.message || 'Unknown error'))
      salesOrders.value = []
    }
  } catch (err) {
    console.error('Error fetching sales orders:', err)
    console.error('Error details:', err.response?.data)
    error('Error loading sales orders: ' + (err.message || 'Network error'))
    salesOrders.value = []
  } finally {
    isLoading.value = false
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

const clearOrderInfo = () => {
  // Clear order information
  orderInfo.customerName = ''
  orderInfo.model = ''
  orderInfo.orderMode = ''
  orderInfo.salespersonCode = ''
  orderInfo.salespersonName = ''
  orderInfo.orderGroup = ''
  orderInfo.orderType = ''
  
  // Clear item details
  itemDetails.value.forEach(item => {
    item.main = ''
    item.f1 = ''
    item.f2 = ''
    item.f3 = ''
    item.f4 = ''
    item.f5 = ''
    item.f6 = ''
    item.f7 = ''
    item.f8 = ''
    item.f9 = ''
  })
}

const loadSalesOrders = async () => {
  try {
    // Load sales orders for the customer or all outstanding orders
    if (props.customerData && props.customerData.code) {
      console.log('Loading sales orders for customer:', props.customerData.code)
      
      // If customer code is 'ALL', load all outstanding sales orders
      if (props.customerData.code === 'ALL') {
        console.log('Loading all outstanding sales orders for AmendSO')
        await searchOrders() // This will use the outstanding endpoint
      } else {
        // First, try the debug route to see raw data for specific customer
        try {
          const debugResponse = await axios.get('/api/debug/sales-orders', {
            params: { customer_code: props.customerData.code }
          })
          console.log('DEBUG ROUTE Response:', debugResponse.data)
          console.log('DEBUG: Sample order:', debugResponse.data.sample)
        } catch (debugErr) {
          console.error('Debug route error:', debugErr)
        }
        
        // Then call the actual search for specific customer
        await searchOrders()
      }
    } else {
      console.log('No customer selected')
      salesOrders.value = []
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
