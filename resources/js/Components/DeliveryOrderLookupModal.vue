<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
      <!-- Modal Header -->
      <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <i class="fas fa-search text-xl text-orange-600"></i>
            <div>
              <h2 class="text-lg font-semibold text-gray-800">Delivery Order Lookup</h2>
              <p v-if="context === 'amend' || context === 'cancel'" class="text-xs text-orange-600">
                Showing only Main components (Draft and Saved orders)
              </p>
              <p v-else-if="context === 'print'" class="text-xs text-blue-600">
                Showing only Saved and Completed orders (printable)
              </p>
            </div>
          </div>
          <button 
            @click="$emit('close')"
            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <!-- Search Filters -->
      <div class="p-4 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- DO Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">DO Number:</label>
            <input 
              v-model="filters.doNumber"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
              placeholder="Enter DO number"
              @input="searchDeliveryOrders"
            >
          </div>

          <!-- Customer Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
            <input 
              v-model="filters.customerCode"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
              placeholder="Enter customer code"
              @input="searchDeliveryOrders"
            >
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
            <select 
              v-model="filters.status"
              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
              @change="searchDeliveryOrders"
            >
              <option value="">All Status</option>
              <option value="Draft">Draft</option>
              <option value="Saved">Saved</option>
              <option value="Cancelled">Cancelled</option>
              <option value="Completed">Completed</option>
            </select>
          </div>

          <!-- Date Range -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range:</label>
            <div class="flex space-x-1">
              <input 
                v-model="filters.fromDate"
                type="date"
                class="flex-1 px-2 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                @change="searchDeliveryOrders"
              >
              <input 
                v-model="filters.toDate"
                type="date"
                class="flex-1 px-2 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                @change="searchDeliveryOrders"
              >
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <button 
              @click="searchDeliveryOrders"
              class="px-4 py-2 bg-orange-600 text-white rounded text-sm hover:bg-orange-700 transition-colors flex items-center"
            >
              <i class="fas fa-search mr-2"></i>
              Search
            </button>
            <button 
              @click="clearFilters"
              class="px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700 transition-colors flex items-center"
            >
              <i class="fas fa-times mr-2"></i>
              Clear
            </button>
          </div>
          <div class="text-sm text-gray-600">
            {{ deliveryOrders.length }} records found
          </div>
        </div>
      </div>

      <!-- Results Table -->
      <div class="flex-1 overflow-auto">
        <div v-if="loading" class="flex items-center justify-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div>
          <span class="ml-2 text-gray-600">Loading...</span>
        </div>

        <div v-else-if="deliveryOrders.length === 0" class="flex items-center justify-center py-8">
          <div class="text-center">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
            <p class="text-gray-600">No delivery orders found</p>
          </div>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('DO_Num')">
                  DO Number
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('DO_DMY')">
                  DO Date
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('AC_Num')">
                  Customer Code
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('AC_Name')">
                  Customer Name
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('DO_VHC_Num')">
                  Vehicle#
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    @click="sortBy('Status')">
                  Status
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="deliveryOrder in deliveryOrders" 
                :key="deliveryOrder.DO_Num"
                :class="[
                  isSelectableDeliveryOrder(deliveryOrder) 
                    ? 'hover:bg-gray-50 cursor-pointer' 
                    : 'bg-gray-100 cursor-not-allowed opacity-60',
                  !isSelectableDeliveryOrder(deliveryOrder) ? 'text-gray-500' : ''
                ]"
                @click="selectDeliveryOrder(deliveryOrder)"
              >
                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                  {{ deliveryOrder.DO_Num }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.DO_DMY }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.AC_Num }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.AC_Name }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.DO_VHC_Num }}
                </td>
                <td class="px-4 py-3 text-sm">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusClass(deliveryOrder.Status)">
                    {{ deliveryOrder.Status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  <button 
                    @click.stop="selectDeliveryOrder(deliveryOrder)"
                    :disabled="!isSelectableDeliveryOrder(deliveryOrder)"
                    :class="[
                      'px-3 py-1 rounded text-xs transition-colors',
                      isSelectableDeliveryOrder(deliveryOrder)
                        ? 'bg-orange-600 text-white hover:bg-orange-700'
                        : 'bg-gray-400 text-gray-200 cursor-not-allowed'
                    ]"
                  >
                    {{ isSelectableDeliveryOrder(deliveryOrder) ? 'Select' : 'Cannot Select' }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
        <button 
          @click="$emit('close')"
          class="px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700 transition-colors"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'

const { error } = useToast()

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  context: {
    type: String,
    default: 'general', // 'amend', 'cancel', 'print', 'general'
    validator: (value) => ['amend', 'cancel', 'print', 'general'].includes(value)
  }
})

const emit = defineEmits(['close', 'select'])

// Reactive data
const loading = ref(false)
const deliveryOrders = ref([])
const sortField = ref('DO_Num')
const sortDirection = ref('desc')

// Filters
const filters = reactive({
  doNumber: '',
  customerCode: '',
  status: '',
  fromDate: '',
  toDate: ''
})

// Computed properties
const isSelectableDeliveryOrder = computed(() => {
  return (deliveryOrder) => {
    if (props.context === 'amend') {
      // For amend context, only Draft and Saved orders can be selected
      return ['Draft', 'Saved'].includes(deliveryOrder.Status)
    } else if (props.context === 'cancel') {
      // For cancel context, only Draft and Saved orders can be selected
      return ['Draft', 'Saved'].includes(deliveryOrder.Status)
    } else if (props.context === 'print') {
      // For print context, only Saved and Completed orders can be selected
      return ['Saved', 'Completed'].includes(deliveryOrder.Status)
    }
    // For general context, all orders can be selected
    return true
  }
})

// Methods
const searchDeliveryOrders = async () => {
  loading.value = true
  
  try {
    const params = new URLSearchParams()
    
    if (filters.doNumber) params.append('do_number', filters.doNumber)
    if (filters.customerCode) params.append('customer_code', filters.customerCode)
    if (filters.status) params.append('status', filters.status)
    if (filters.fromDate) params.append('from_date', filters.fromDate)
    if (filters.toDate) params.append('to_date', filters.toDate)
    
    // Add context-based filtering
    if (props.context === 'amend' || props.context === 'cancel') {
      // Only fetch Draft and Saved orders for amend/cancel context
      if (!filters.status) {
        params.append('status_in', 'Draft,Saved')
      }
      // For amend and cancel contexts, only show Main components
      params.append('comp_main', 'true')
    } else if (props.context === 'print') {
      // Only fetch Saved and Completed orders for print context
      if (!filters.status) {
        params.append('status_in', 'Saved,Completed')
      }
    }
    
    const response = await axios.get(`/api/delivery-orders?${params.toString()}`)
    
    if (response.data.success) {
      deliveryOrders.value = response.data.data.data || response.data.data
    } else {
      deliveryOrders.value = []
    }
  } catch (error) {
    console.error('Error searching delivery orders:', error)
    deliveryOrders.value = []
  } finally {
    loading.value = false
  }
}

const clearFilters = () => {
  Object.assign(filters, {
    doNumber: '',
    customerCode: '',
    status: '',
    fromDate: '',
    toDate: ''
  })
  searchDeliveryOrders()
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  
  // Sort the delivery orders array
  deliveryOrders.value.sort((a, b) => {
    let aVal = a[field] || ''
    let bVal = b[field] || ''
    
    if (typeof aVal === 'string') {
      aVal = aVal.toLowerCase()
      bVal = bVal.toLowerCase()
    }
    
    if (sortDirection.value === 'asc') {
      return aVal > bVal ? 1 : -1
    } else {
      return aVal < bVal ? 1 : -1
    }
  })
}

const selectDeliveryOrder = (deliveryOrder) => {
  if (!isSelectableDeliveryOrder.value(deliveryOrder)) {
    // Show warning message based on context
    let message = ''
    if (props.context === 'amend') {
      message = `Cannot select delivery order ${deliveryOrder.DO_Num}. Only Draft and Saved orders can be amended.`
    } else if (props.context === 'cancel') {
      message = `Cannot select delivery order ${deliveryOrder.DO_Num}. Only Draft and Saved orders can be cancelled.`
    } else if (props.context === 'print') {
      message = `Cannot select delivery order ${deliveryOrder.DO_Num}. Only Saved and Completed orders can be printed.`
    }
    
    if (message) {
      error(message)
    }
    return
  }
  
  emit('select', deliveryOrder)
  // Auto-close modal after successful selection
  emit('close')
}

const getStatusClass = (status) => {
  switch (status) {
    case 'Draft':
      return 'bg-gray-100 text-gray-800'
    case 'Saved':
      return 'bg-blue-100 text-blue-800'
    case 'Cancelled':
      return 'bg-red-100 text-red-800'
    case 'Completed':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

// Load initial data when modal opens
onMounted(() => {
  if (props.show) {
    searchDeliveryOrders()
  }
})

// Watch for show prop changes
import { watch } from 'vue'
watch(() => props.show, (newValue) => {
  if (newValue) {
    searchDeliveryOrders()
  }
})
</script>

<style scoped>
/* Custom styles if needed */
</style>
