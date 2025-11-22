<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="$emit('close')"></div>
    
    <!-- Modal Container -->
    <div class="relative bg-white w-full max-w-7xl max-h-[90vh] rounded-2xl shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
      <!-- Modal Header -->
      <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-list-ol text-white text-lg"></i>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Sales Order Table</h2>
              <p class="text-blue-100 text-sm">[Sorted by S/Order#] - Select a sales order to continue</p>
            </div>
          </div>
          <button 
            @click="$emit('close')"
            class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors"
          >
            <i class="fa-solid fa-times text-white"></i>
          </button>
        </div>
      </div>

      <!-- Modal Content -->
      <div class="flex flex-col h-[calc(90vh-80px)]">
        <!-- Search Section -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
              <label class="text-sm font-semibold text-gray-700">Search:</label>
              <input 
                v-model="searchParams.month" 
                type="number" 
                min="1" 
                max="12" 
                class="modern-input w-16 text-center" 
                placeholder="MM"
              />
              <span class="text-gray-400">/</span>
              <input 
                v-model="searchParams.year" 
                type="number" 
                min="2000" 
                max="2099" 
                class="modern-input w-20 text-center" 
                placeholder="YYYY"
              />
              <span class="text-gray-400">/</span>
              <input 
                v-model="searchParams.seq" 
                type="text" 
                class="modern-input w-24" 
                placeholder="Seq"
              />
            </div>
            <button 
              @click="searchSalesOrders"
              class="modern-btn-primary"
            >
              <i class="fa-solid fa-magnifying-glass mr-2"></i>Search
            </button>
            <div class="flex items-center gap-2 ml-auto">
              <label class="text-sm font-semibold text-gray-700">S/Order#:</label>
              <input 
                v-model="quickSearch" 
                type="text" 
                class="modern-input w-32" 
                placeholder="Quick search..."
                @keyup.enter="quickSearchOrders"
              />
            </div>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex overflow-hidden">
          <!-- Sales Orders Table -->
          <div class="flex-1 flex flex-col">
            <div class="bg-white border-b border-gray-200 px-4 py-2">
              <h3 class="text-sm font-semibold text-gray-700">Sales Orders</h3>
            </div>
            <div class="flex-1 overflow-auto">
              <table class="w-full">
                <thead class="bg-gray-50 sticky top-0">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SO#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CUSTOMER PO#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">AC#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">MCS#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">D/LOCATION</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="order in salesOrders" 
                    :key="order.so_number"
                    @click="selectOrder(order)"
                    :class="[
                      'cursor-pointer transition-colors hover:bg-blue-50',
                      selectedOrder?.so_number === order.so_number ? 'bg-blue-100 border-l-4 border-blue-500' : ''
                    ]"
                  >
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ order.so_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ order.customer_po_number || '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ order.customer_code }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ order.master_card_seq || '-' }}</td>
                    <td class="px-4 py-3">
                      <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-semibold">
                        {{ getStatusText(order.status) }}
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ order.delivery_location || 'Same' }}</td>
                  </tr>
                </tbody>
              </table>
              
              <!-- Loading State -->
              <div v-if="loading" class="flex items-center justify-center py-8">
                <div class="flex items-center gap-3">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                  <span class="text-gray-600">Loading sales orders...</span>
                </div>
              </div>
              
              <!-- Empty State -->
              <div v-else-if="salesOrders.length === 0" class="flex items-center justify-center py-8">
                <div class="text-center">
                  <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-2"></i>
                  <p class="text-gray-500">No sales orders found</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Details Panel -->
          <div class="w-96 bg-gray-50 border-l border-gray-200 flex flex-col">
            <div class="bg-white border-b border-gray-200 px-4 py-2">
              <h3 class="text-sm font-semibold text-gray-700">Order Details</h3>
            </div>
            
            <div class="flex-1 overflow-auto p-4">
              <div v-if="selectedOrder" class="space-y-4">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg p-4 shadow-sm">
                  <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-500"></i>
                    Customer Information
                  </h4>
                  <div class="space-y-2 text-sm">
                    <div>
                      <span class="font-medium text-gray-600">Customer Name:</span>
                      <p class="text-gray-800">{{ selectedOrder.customer_name || 'N/A' }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-gray-600">Model:</span>
                      <p class="text-gray-800">{{ selectedOrder.master_card_model || 'N/A' }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-gray-600">Order Mode:</span>
                      <p class="text-gray-800">{{ selectedOrder.order_mode || 'N/A' }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-gray-600">Salesperson:</span>
                      <p class="text-gray-800">{{ selectedOrder.salesperson_name || 'N/A' }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-gray-600">Order Group:</span>
                      <p class="text-gray-800">{{ selectedOrder.order_group || 'Sales' }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-gray-600">Order Type:</span>
                      <p class="text-gray-800">{{ selectedOrder.order_type || 'S1' }}</p>
                    </div>
                  </div>
                </div>

              </div>
              
              <!-- No Selection State -->
              <div v-else class="flex items-center justify-center h-full">
                <div class="text-center text-gray-500">
                  <i class="fa-solid fa-hand-pointer text-4xl mb-2"></i>
                  <p>Select a sales order to view details</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-600">
            <span v-if="salesOrders.length > 0">{{ salesOrders.length }} sales order(s) found</span>
          </div>
          <div class="flex gap-3">
            <button 
              @click="$emit('zoom', selectedOrder)"
              :disabled="!selectedOrder"
              class="modern-btn-secondary"
            >
              <i class="fa-solid fa-magnifying-glass-plus mr-2"></i>Zoom
            </button>
            <button 
              @click="handleSelect"
              :disabled="!selectedOrder"
              class="modern-btn-confirm"
            >
              <i class="fa-solid fa-check mr-2"></i>Select
            </button>
            <button 
              @click="$emit('close')"
              class="modern-btn-cancel"
            >
              <i class="fa-solid fa-times mr-2"></i>Exit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  searchParams: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'select', 'zoom'])

// Reactive data
const salesOrders = ref([])
const selectedOrder = ref(null)
const loading = ref(false)
const quickSearch = ref('')

// Watch for search params changes
watch(() => props.searchParams, (newParams) => {
  if (props.isOpen && newParams) {
    searchSalesOrders()
  }
}, { immediate: true })

// Methods
function selectOrder(order) {
  selectedOrder.value = order
}

function handleSelect() {
  if (selectedOrder.value) {
    emit('select', selectedOrder.value)
  }
}

async function searchSalesOrders() {
  loading.value = true
  try {
    const response = await axios.get('/api/sales-orders', {
      params: {
        month: props.searchParams.month,
        year: props.searchParams.year,
        from_so: props.searchParams.from_so,
        to_so: props.searchParams.to_so,
        status: props.searchParams.status
      }
    })
    
    if (response.data.success) {
      const salesOrdersData = response.data.data.map(order => ({
        so_number: order.so_number,
        customer_po_number: order.customer_po_number,
        customer_code: order.customer_code,
        master_card_seq: order.master_card_seq,
        status: order.status,
        delivery_location: order.delivery_location || 'Same',
        customer_name: order.customer_name,
        master_card_model: order.master_card_model,
        order_mode: order.order_type,
        salesperson_name: order.salesperson_code,
        order_group: order.order_group,
        order_type: order.order_type,
        unit: order.uom,
        order_quantity: order.order_quantity?.toLocaleString() || '0',
        net_delivery: '',
        balance: order.order_quantity?.toLocaleString() || '0',
        amount: order.amount,
        po_date: order.customer_po_number,
        so_date: order.so_number,
        items: [
          { item_code: 'PD', quantity: '1', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '', f9: '' }
        ]
      }))
      
      salesOrders.value = salesOrdersData
      if (salesOrdersData.length > 0) {
        selectedOrder.value = salesOrdersData[0]
      }
    } else {
      salesOrders.value = []
    }
  } catch (error) {
    console.error('Error fetching sales orders:', error)
    salesOrders.value = []
  } finally {
    loading.value = false
  }
}

function quickSearchOrders() {
  if (quickSearch.value.trim()) {
    // Filter existing data or make new API call
    const filtered = salesOrders.value.filter(order => 
      order.so_number.toLowerCase().includes(quickSearch.value.toLowerCase()) ||
      order.customer_po_number?.toLowerCase().includes(quickSearch.value.toLowerCase())
    )
    salesOrders.value = filtered
  } else {
    searchSalesOrders()
  }
}

function getStatusClass(status) {
  const statusMap = {
    'Outs': 'bg-yellow-100 text-yellow-800',
    'Partial': 'bg-blue-100 text-blue-800',
    'Closed': 'bg-gray-100 text-gray-800',
    'Completed': 'bg-green-100 text-green-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }
  return statusMap[status] || 'bg-gray-100 text-gray-800'
}

function getStatusText(status) {
  const statusMap = {
    'Outs': 'Outstanding',
    'Partial': 'Partial',
    'Closed': 'Closed',
    'Completed': 'Completed',
    'Cancelled': 'Cancelled'
  }
  return statusMap[status] || status
}

// Load data when modal opens
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    searchSalesOrders()
  }
})
</script>

<style scoped>
/* Modern Input Styles */
.modern-input {
  @apply w-full px-3 py-2 border border-gray-200 rounded-lg text-sm transition-all duration-200 
         focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
         hover:border-gray-300 bg-white;
}

/* Modern Button Styles */
.modern-btn-primary {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white 
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 
         transition-all duration-200 hover:from-blue-600 hover:to-blue-700;
}

.modern-btn-secondary {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white 
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 
         transition-all duration-200 hover:from-gray-600 hover:to-gray-700 disabled:opacity-50 disabled:cursor-not-allowed;
}

.modern-btn-confirm {
  @apply inline-flex items-center px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white 
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 
         transition-all duration-200 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed;
}

.modern-btn-cancel {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white 
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 
         transition-all duration-200 hover:from-red-600 hover:to-red-700;
}

/* Animation */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.3s ease-out;
}
</style>
