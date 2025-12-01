<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-desktop mr-2 text-blue-600"></i>
              Customer Desktop Service - Sales Order Table
            </h3>
            <button
              @click="$emit('close')"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
          <!-- Sales Order Table -->
          <div class="mb-6">
            <div class="overflow-x-auto border border-gray-300 rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-200">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800 border-r border-gray-300">S/Order#</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800 border-r border-gray-300">M/Card Seq#</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800 border-r border-gray-300">Customer PO Ref#</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(order, index) in salesOrders" :key="index"
                      :class="order.status === 'Completed' ? 'bg-blue-100' : ''"
                      class="hover:bg-gray-50 cursor-pointer"
                      @click="selectOrder(order)">
                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ order.orderNumber }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ order.masterCardSeq }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ order.customerPORef }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">
                      <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded text-xs font-medium">
                        {{ order.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Search Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Search:</label>
              <div class="flex space-x-2">
                <input v-model="searchFields.field1" type="text" placeholder="0" class="w-16 px-2 py-2 border border-gray-400 rounded text-sm">
                <input v-model="searchFields.field2" type="text" placeholder="0" class="w-16 px-2 py-2 border border-gray-400 rounded text-sm">
                <input v-model="searchFields.field3" type="text" placeholder="0" class="w-16 px-2 py-2 border border-gray-400 rounded text-sm">
                <button @click="performSearch" class="px-4 py-2 bg-gray-200 border border-gray-400 text-sm hover:bg-gray-300 transition-colors">
                  Search
                </button>
              </div>
            </div>
          </div>

          <!-- Order Details Form -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer:</label>
              <input v-model="orderDetails.customer" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm" readonly>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Model:</label>
              <input v-model="orderDetails.model" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm" readonly>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Order Mode:</label>
              <div class="flex items-center space-x-4">
                <label class="flex items-center">
                  <input v-model="orderDetails.orderMode" type="radio" value="0-Order by Customer + Deliver & Invoice to Customer" class="mr-2">
                  <span class="text-sm">D-Order by Customer + Deliver & Invoice to Customer</span>
                </label>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer's Agent:</label>
              <div class="flex space-x-2">
                <input v-model="orderDetails.customerAgent1" type="text" class="flex-1 px-3 py-2 border border-gray-400 rounded text-sm">
                <input v-model="orderDetails.customerAgent2" type="text" class="flex-1 px-3 py-2 border border-gray-400 rounded text-sm">
              </div>
            </div>
            <div class="flex space-x-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">SO Lot#:</label>
                <input v-model="orderDetails.soLot" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
              </div>
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Location:</label>
                <div class="flex space-x-2">
                  <input v-model="orderDetails.deliveryLocation" type="text" class="flex-1 px-3 py-2 border border-gray-400 rounded text-sm">
                  <button @click="copyCustomerAddress" class="px-3 py-2 bg-gray-200 border border-gray-400 text-sm hover:bg-gray-300 transition-colors">
                    Same
                  </button>
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Salesperson:</label>
              <input v-model="orderDetails.salesperson" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">CR/Ticket#:</label>
              <input v-model="orderDetails.crTicket" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">On Hold:</label>
              <select v-model="orderDetails.onHold" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Sales Type:</label>
              <select v-model="orderDetails.salesType" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
                <option value="">Select...</option>
                <option value="Regular">Regular</option>
                <option value="Sample">Sample</option>
                <option value="Urgent">Urgent</option>
              </select>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">D/O Instruction 1:</label>
              <input v-model="orderDetails.doInstruction1" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">D/O Instruction 2:</label>
              <input v-model="orderDetails.doInstruction2" type="text" class="w-full px-3 py-2 border border-gray-400 rounded text-sm">
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
          <button
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  soNumber: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close'])

const { success, error } = useToast()

// Search fields
const searchFields = reactive({
  field1: '0',
  field2: '0',
  field3: '0'
})

// Order details form
const orderDetails = reactive({
  customer: '',
  model: '',
  orderMode: '0-Order by Customer + Deliver & Invoice to Customer',
  customerAgent1: '',
  customerAgent2: '',
  soLot: '',
  deliveryLocation: '',
  salesperson: '',
  crTicket: '',
  onHold: 'No',
  salesType: '',
  doInstruction1: '',
  doInstruction2: ''
})

// Sales orders data
const salesOrders = ref([])

// Methods
const getStatusClass = (status) => {
  switch (status) {
    case 'Completed':
      return 'bg-green-100 text-green-800'
    case 'Cancelled':
      return 'bg-red-100 text-red-800'
    case 'Closed':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-blue-100 text-blue-800'
  }
}

const fetchSalesOrders = async () => {
  try {
    const response = await axios.get('/api/sales-orders')
    if (response.data && response.data.data) {
      salesOrders.value = response.data.data.map(so => ({
        orderNumber: so.so_number || so.SO_Num || 'N/A',
        masterCardSeq: so.master_card_seq || so.MCS_Num || 'N/A',
        customerPORef: so.customer_po_ref || so.Cust_PO || 'N/A',
        status: so.status || so.STS || 'Unknown'
      }))
    }
  } catch (error) {
    console.error('Error fetching sales orders:', error)
    // Fallback to empty array
    salesOrders.value = []
  }
}

const selectOrder = async (order) => {
  try {
    // Fetch detailed order information
    const response = await axios.get(`/api/sales-order/${order.orderNumber}/detail`)
    if (response.data && response.data.data) {
      const data = response.data.data

      // Populate order details form
      orderDetails.customer = data.order_info?.customer_code || 'N/A'
      orderDetails.model = data.order_info?.model || 'N/A'
      orderDetails.salesperson = data.order_info?.salesperson_name || ''
      orderDetails.orderMode = '0-Order by Customer + Deliver & Invoice to Customer' // Default value

      // Clear other fields for user input
      orderDetails.customerAgent1 = ''
      orderDetails.customerAgent2 = ''
      orderDetails.soLot = ''
      orderDetails.deliveryLocation = ''
      orderDetails.crTicket = ''
      orderDetails.onHold = 'No'
      orderDetails.salesType = ''
      orderDetails.doInstruction1 = ''
      orderDetails.doInstruction2 = ''

      success(`Order ${order.orderNumber} loaded successfully`)
    } else {
      error('Failed to load order details')
    }
  } catch (error) {
    console.error('Error fetching order details:', error)
    error('Failed to load order details')
  }
}

const performSearch = () => {
  // Build search number from fields
  const searchNumber = `${searchFields.field1}-${searchFields.field2}-${searchFields.field3}`

  // Filter sales orders based on search number
  if (searchNumber && searchNumber !== '0-0-0') {
    const filteredOrders = salesOrders.value.filter(order =>
      order.orderNumber.includes(searchNumber)
    )

    if (filteredOrders.length > 0) {
      success(`Found ${filteredOrders.length} matching orders`)
      // Update the displayed orders
      salesOrders.value = filteredOrders
    } else {
      error('No matching orders found')
      // Reload all orders
      fetchSalesOrders()
    }
  } else {
    // Reload all orders if search is empty
    fetchSalesOrders()
  }
}

const copyCustomerAddress = () => {
  // Copy customer address to delivery location
  if (orderDetails.customer) {
    orderDetails.deliveryLocation = orderDetails.customer
    success('Customer address copied to delivery location')
  } else {
    error('No customer selected')
  }
}

// Initialize
onMounted(() => {
  // Load sales orders data
  fetchSalesOrders()

  // Load data based on SO number if provided
  if (props.soNumber) {
    // Filter or highlight the specific SO number
    console.log('Loading data for SO:', props.soNumber)
  }
})
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Table hover effects */
tbody tr:hover {
  background-color: #f9fafb;
}

/* Input focus styles */
input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
