<template>
  <AppLayout header="Cancel Delivery Order">
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
          <!-- Header with controls -->
          <div class="bg-blue-600 px-4 py-3 sm:px-6 border-b border-blue-700">
            <div class="flex items-center justify-start">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center">
                  <i class="fas fa-times-circle text-lg text-red-100"></i>
                </div>
                <div>
                  <h1 class="text-lg sm:text-xl font-semibold text-white">Cancel Delivery Order</h1>
                  <p class="text-xs text-blue-100">F2: DO Lookup • F3: Items • F4: Calendar • Ctrl+S: Save • F5: Refresh</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Main Form Content -->
          <div class="p-6">
        <!-- Form Container -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
          <!-- Current Period -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Current Period:</label>
            <div class="flex items-center space-x-2">
              <input 
                v-model="currentPeriod.month" 
                type="number" 
                min="1" 
                max="12"
                class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                readonly
                disabled
              >
              <input 
                v-model="currentPeriod.year" 
                type="number"
                class="w-20 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                readonly
                disabled
              >
              <span class="text-xs text-gray-500">mm/yyyy</span>
            </div>
          </div>

          <!-- Delivery Order Number -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">D/Order#:</label>
            <div class="flex items-center space-x-2">
              <input 
                v-model="deliveryOrderParts.year" 
                type="number"
                class="w-16 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="YYYY"
                readonly
              >
              <span class="text-gray-500">-</span>
              <input 
                v-model="deliveryOrderParts.month" 
                type="number"
                class="w-12 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="MM"
                readonly
              >
              <span class="text-gray-500">-</span>
              <input 
                v-model="deliveryOrderParts.number" 
                type="number"
                class="w-20 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Number"
                readonly
              >
              <button 
                @click="openDeliveryOrderLookup"
                class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors"
                title="DO Lookup"
              >
                <i class="fas fa-th"></i>
              </button>
            </div>
            <!-- Delivery Order Status -->
            <div v-if="selectedDeliveryOrder.status" class="mt-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="getStatusClass(selectedDeliveryOrder.status)">
                {{ selectedDeliveryOrder.status }}
              </span>
            </div>
          </div>

          <!-- Delivery Order Details Container (shown when DO is selected) -->
          <div v-if="selectedDeliveryOrder.doNumber" class="space-y-4">
            <!-- Customer Information -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer:</label>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                  {{ selectedDeliveryOrder.customerCode }} - {{ selectedDeliveryOrder.customerName }}
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Mode:</label>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                  {{ orderMode }}
                </div>
              </div>
            </div>

            <!-- D/Order Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">D/Order Date:</label>
              <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                {{ selectedDeliveryOrder.orderDate }}
              </div>
            </div>

            <!-- Vehicle# -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle#:</label>
              <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                {{ selectedDeliveryOrder.vehicleNumber }}
              </div>
            </div>

            <!-- Cancellation Reason -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Reason: <span class="text-red-500">*</span>
              </label>
              <textarea 
                v-model="cancellationReason"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Enter reason for cancellation..."
                :class="{ 'border-red-500': !cancellationReason && showValidation }"
              ></textarea>
              <div v-if="!cancellationReason && showValidation" class="mt-1 text-sm text-red-600">
                Cancellation reason is required
              </div>
            </div>
          </div>
        </div>

        <!-- Notes Section -->
        <div v-if="selectedDeliveryOrder.doNumber" class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Notes</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>• Only Draft and Saved delivery orders can be cancelled.</p>
            <p>• Cancelled delivery orders cannot be amended or processed further.</p>
            <p>• Cancellation reason is mandatory and will be logged for audit purposes.</p>
            <p>• Once cancelled, the delivery order status will be changed to "Cancelled".</p>
            <p>• <strong>Bulk Cancel:</strong> All DO records with the same DO number will be cancelled together.</p>
            <p>• Only Main component records are shown in the lookup for cancellation.</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div v-if="selectedDeliveryOrder.doNumber" class="mt-6 flex items-center justify-end space-x-4">
          <button 
            @click="refreshPage" 
            class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center"
          >
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
          </button>
          <button 
            @click="cancelDeliveryOrder" 
            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center"
            :disabled="!canCancel"
          >
            <i class="fas fa-ban mr-2"></i>
            Cancel Delivery Order
          </button>
        </div>

        <!-- Form validation summary -->
        <div v-if="!selectedDeliveryOrder.doNumber" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <span class="text-sm text-yellow-800">
              Please select a delivery order to cancel.
            </span>
          </div>
        </div>

        <!-- Error message for non-cancellable orders -->
        <div v-if="selectedDeliveryOrder.doNumber && !canCancel" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <span class="text-sm text-red-800">
              This delivery order cannot be cancelled. Only Draft and Saved orders can be cancelled.
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Delivery Order Lookup Modal -->
    <DeliveryOrderLookupModal 
      :show="showDeliveryOrderModal" 
      context="cancel"
      @close="showDeliveryOrderModal = false" 
      @select="selectDeliveryOrder"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import DeliveryOrderLookupModal from '@/Components/DeliveryOrderLookupModal.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error, info } = useToast()

// Period Information
const currentPeriod = reactive({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
})

// Delivery Order Parts
const deliveryOrderParts = reactive({
  year: '',
  month: '',
  number: ''
})

// Selected Delivery Order Information
const selectedDeliveryOrder = reactive({
  doNumber: '',
  status: '',
  customerCode: '',
  customerName: '',
  orderDate: '',
  vehicleNumber: '',
  originalData: null
})

// Cancellation reason
const cancellationReason = ref('')
const showValidation = ref(false)

// Modal visibility
const showDeliveryOrderModal = ref(false)

// Order mode (static for now)
const orderMode = ref('0-Order by Customer + Deliver & Invoice to Customer')

// Computed properties
const canCancel = computed(() => {
  return selectedDeliveryOrder.doNumber && 
         selectedDeliveryOrder.status && 
         ['Draft', 'Saved'].includes(selectedDeliveryOrder.status) &&
         cancellationReason.value.trim().length > 0
})

// Functions
const openDeliveryOrderLookup = () => {
  showDeliveryOrderModal.value = true
}

const selectDeliveryOrder = async (deliveryOrderData) => {
  selectedDeliveryOrder.doNumber = deliveryOrderData.DO_Num
  selectedDeliveryOrder.status = deliveryOrderData.Status
  selectedDeliveryOrder.customerCode = deliveryOrderData.AC_Num
  selectedDeliveryOrder.customerName = deliveryOrderData.AC_Name
  selectedDeliveryOrder.orderDate = deliveryOrderData.DO_DMY
  selectedDeliveryOrder.vehicleNumber = deliveryOrderData.DO_VHC_Num
  selectedDeliveryOrder.originalData = deliveryOrderData
  
  // Parse DO number parts
  parseDeliveryOrderNumber(deliveryOrderData.DO_Num)
  
  // Modal will auto-close via emit('close') from the modal component
  success(`Delivery Order ${deliveryOrderData.DO_Num} selected successfully`)
}

const parseDeliveryOrderNumber = (doNumber) => {
  if (doNumber) {
    const parts = doNumber.split('-')
    if (parts.length === 3) {
      deliveryOrderParts.year = parts[0]
      deliveryOrderParts.month = parts[1]
      deliveryOrderParts.number = parts[2]
    }
  }
}

const cancelDeliveryOrder = async () => {
  if (!selectedDeliveryOrder.doNumber) {
    error('Please select a delivery order first')
    return
  }
  
  if (!canCancel.value) {
    showValidation.value = true
    if (!cancellationReason.value.trim()) {
      error('Please provide a cancellation reason')
    } else {
      error('This delivery order cannot be cancelled')
    }
    return
  }
  
  // Confirm cancellation
  if (!confirm(`Are you sure you want to cancel delivery order ${selectedDeliveryOrder.doNumber}?\n\nThis action cannot be undone.`)) {
    return
  }
  
  try {
    const cancelData = {
      do_number: selectedDeliveryOrder.doNumber,
      cancellation_reason: cancellationReason.value.trim()
    }
    
    console.log('Cancelling delivery order:', cancelData)
    
    const response = await axios.post(`/api/delivery-orders/${selectedDeliveryOrder.doNumber}/cancel`, cancelData)
    
    if (response.data.success) {
      const affectedRows = response.data.data?.affected_rows || 1
      const message = affectedRows > 1 
        ? `Delivery order ${selectedDeliveryOrder.doNumber} cancelled successfully. ${affectedRows} records updated.`
        : `Delivery order ${selectedDeliveryOrder.doNumber} cancelled successfully.`
      
      success(message)
      
      // Update the status
      selectedDeliveryOrder.status = 'Cancelled'
      
      // Clear the form
      refreshPage()
    } else {
      error(response.data.message || 'Failed to cancel delivery order')
    }
  } catch (err) {
    console.error('Error cancelling delivery order:', err)
    if (err.response?.data?.message) {
      error(err.response.data.message)
    } else {
      error('Error cancelling delivery order')
    }
  }
}

const refreshPage = () => {
  // Reset form data
  selectedDeliveryOrder.doNumber = ''
  selectedDeliveryOrder.status = ''
  selectedDeliveryOrder.customerCode = ''
  selectedDeliveryOrder.customerName = ''
  selectedDeliveryOrder.orderDate = ''
  selectedDeliveryOrder.vehicleNumber = ''
  selectedDeliveryOrder.originalData = null
  
  // Reset delivery order parts
  Object.assign(deliveryOrderParts, {
    year: '',
    month: '',
    number: ''
  })
  
  // Reset cancellation reason
  cancellationReason.value = ''
  showValidation.value = false
  
  success('Form reset successfully')
}

const exitPage = () => {
  if (selectedDeliveryOrder.doNumber || cancellationReason.value) {
    if (confirm('You have unsaved changes. Are you sure you want to exit?')) {
      window.history.back()
    }
  } else {
    window.history.back()
  }
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

// Keyboard shortcuts
const handleKeydown = (event) => {
  if (event.ctrlKey && event.key === 's') {
    event.preventDefault()
    cancelDeliveryOrder()
  } else if (event.key === 'F5') {
    event.preventDefault()
    refreshPage()
  } else if (event.key === 'F2') {
    event.preventDefault()
    openDeliveryOrderLookup()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

// Cleanup
import { onUnmounted } from 'vue'
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
/* Custom styles if needed */
</style>
