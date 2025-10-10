<template>
  <AppLayout header="Amend Delivery Order">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-edit text-2xl text-orange-600"></i>
            <div>
              <h1 class="text-xl font-semibold text-gray-800">Amend Delivery Order</h1>
              <p class="text-xs text-gray-500">F2: DO Lookup • F3: Items • F4: Calendar • Ctrl+S: Save • F5: Refresh</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="exitPage" 
              class="p-2 text-red-600 hover:bg-red-100 rounded-full transition-colors"
              title="Exit"
            >
              <i class="fas fa-power-off"></i>
            </button>
            <button 
              @click="saveAmendedDeliveryOrder" 
              class="p-2 text-green-600 hover:bg-green-100 rounded-full transition-colors"
              title="Save Changes"
            >
              <i class="fas fa-save"></i>
            </button>
            <button 
              @click="refreshPage" 
              class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
              title="Refresh (F5)"
            >
              <i class="fas fa-sync-alt"></i>
            </button>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">D/O Number:</label>
            <div class="flex items-center space-x-2">
              <input 
                v-model="selectedDeliveryOrder.doNumber" 
                type="text"
                class="w-40 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="Enter DO number"
                @blur="validateDeliveryOrder"
              >
              <button 
                @click="openDeliveryOrderLookup"
                class="p-2 text-orange-600 hover:bg-orange-100 rounded transition-colors"
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code:</label>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                  {{ selectedDeliveryOrder.customerCode }}
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name:</label>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                  {{ selectedDeliveryOrder.customerName }}
                </div>
              </div>
            </div>

            <!-- Vehicle Information -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle#:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="deliveryOrder.vehicleNumber"
                    type="text"
                    class="w-32 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    placeholder="Enter vehicle number"
                  >
                  <button 
                    @click="openVehicleLookup"
                    class="p-2 text-orange-600 hover:bg-orange-100 rounded transition-colors"
                    title="Vehicle Lookup"
                  >
                    <i class="fas fa-th"></i>
                  </button>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Class:</label>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700">
                  {{ selectedVehicle.vehicleClass }}
                </div>
              </div>
            </div>

            <!-- Vehicle Information Display -->
            <div v-if="selectedVehicle.vehicleNo" class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                  <span class="font-medium text-blue-800">Driver:</span>
                  <span class="text-blue-700">{{ selectedVehicle.driverName }}</span>
                </div>
                <div>
                  <span class="font-medium text-blue-800">Phone:</span>
                  <span class="text-blue-700">{{ selectedVehicle.driverPhone }}</span>
                </div>
                <div>
                  <span class="font-medium text-blue-800">Class:</span>
                  <span class="text-blue-700">{{ selectedVehicle.vehicleClass }}</span>
                </div>
                <div>
                  <span class="font-medium text-blue-800">Company:</span>
                  <span class="text-blue-700">{{ selectedVehicle.vehicleCompany }}</span>
                </div>
              </div>
            </div>

            <!-- D/Order Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">D/Order Date:</label>
              <div class="flex items-center space-x-2">
                <input 
                  v-model="deliveryOrder.orderDate"
                  type="date"
                  class="w-40 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
                <button 
                  @click="openDatePicker"
                  class="p-2 text-orange-600 hover:bg-orange-100 rounded transition-colors"
                  title="Date Picker"
                >
                  <i class="fas fa-calendar"></i>
                </button>
                <span class="text-sm text-gray-600">{{ dayOfWeek }}</span>
              </div>
            </div>

            <!-- Unapply F/G -->
            <div class="flex items-center space-x-2">
              <input 
                v-model="deliveryOrder.unapplyFG"
                type="checkbox"
                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
              >
              <label class="text-sm text-gray-700">Tick for Y-Yes</label>
            </div>

            <!-- Remarks -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Remark1:</label>
                <input 
                  v-model="deliveryOrder.remark1"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  placeholder="Enter remark 1"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Remark2:</label>
                <input 
                  v-model="deliveryOrder.remark2"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  placeholder="Enter remark 2"
                >
              </div>
            </div>

            <!-- Cancelled Reason (if status is Cancelled) -->
            <div v-if="selectedDeliveryOrder.status === 'Cancelled'">
              <label class="block text-sm font-medium text-gray-700 mb-2">Cancelled Reason:</label>
              <input 
                v-model="deliveryOrder.cancelledReason"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="Enter cancellation reason"
              >
            </div>
          </div>

          <!-- TT Activated Status -->
          <div class="absolute top-4 right-4">
            <span class="text-sm text-red-600 font-medium">TT Activated: No</span>
          </div>
        </div>

        <!-- Notes Section -->
        <div v-if="selectedDeliveryOrder.doNumber" class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Notes</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>• Only Draft and Saved delivery orders can be amended.</p>
            <p>• Cancelled delivery orders cannot be amended.</p>
            <p>• Changes will be logged for audit purposes.</p>
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
            @click="saveAmendedDeliveryOrder" 
            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center"
            :disabled="!canAmend"
          >
            <i class="fas fa-save mr-2"></i>
            Save Changes
          </button>
        </div>

        <!-- Form validation summary -->
        <div v-if="!selectedDeliveryOrder.doNumber" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <span class="text-sm text-yellow-800">
              Please select a delivery order to amend.
            </span>
          </div>
        </div>

        <!-- Error message for non-amendable orders -->
        <div v-if="selectedDeliveryOrder.doNumber && !canAmend" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <span class="text-sm text-red-800">
              This delivery order cannot be amended. Only Draft and Saved orders can be modified.
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Delivery Order Lookup Modal -->
    <DeliveryOrderLookupModal 
      :show="showDeliveryOrderModal" 
      @close="showDeliveryOrderModal = false" 
      @select="selectDeliveryOrder"
    />

    <!-- Vehicle Lookup Modal -->
    <VehicleLookupModal 
      :is-open="showVehicleModal" 
      @close="showVehicleModal = false" 
      @select="selectVehicle"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import VehicleLookupModal from '@/Components/VehicleLookupModal.vue'
import DeliveryOrderLookupModal from '@/Components/DeliveryOrderLookupModal.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error, info } = useToast()

// Period Information
const currentPeriod = reactive({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
})

// Selected Delivery Order Information
const selectedDeliveryOrder = reactive({
  doNumber: '',
  status: '',
  customerCode: '',
  customerName: '',
  originalData: null
})

// Delivery Order Details
const deliveryOrder = reactive({
  vehicleNumber: '',
  orderDate: '',
  unapplyFG: false,
  remark1: '',
  remark2: '',
  cancelledReason: ''
})

// Modal visibility
const showDeliveryOrderModal = ref(false)
const showVehicleModal = ref(false)

// Selected Vehicle
const selectedVehicle = reactive({
  id: null,
  vehicleNo: '',
  driverName: '',
  driverPhone: '',
  vehicleClass: '',
  vehicleCompany: ''
})

// Computed properties
const canAmend = computed(() => {
  return selectedDeliveryOrder.doNumber && 
         selectedDeliveryOrder.status && 
         ['Draft', 'Saved'].includes(selectedDeliveryOrder.status)
})

// Day of week computed property
const dayOfWeek = computed(() => {
  if (!deliveryOrder.orderDate) return ''
  const date = new Date(deliveryOrder.orderDate)
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  return days[date.getDay()]
})

// Functions
const openDeliveryOrderLookup = () => {
  showDeliveryOrderModal.value = true
}

const selectDeliveryOrder = async (doData) => {
  selectedDeliveryOrder.doNumber = doData.DO_Num
  selectedDeliveryOrder.status = doData.Status
  selectedDeliveryOrder.customerCode = doData.AC_Num
  selectedDeliveryOrder.customerName = doData.AC_Name
  selectedDeliveryOrder.originalData = doData
  
  // Load the delivery order details
  await loadDeliveryOrderDetails(doData.DO_Num)
  
  showDeliveryOrderModal.value = false
  success(`Delivery Order ${doData.DO_Num} selected successfully`)
}

const loadDeliveryOrderDetails = async (doNumber) => {
  try {
    const response = await axios.get(`/api/delivery-orders/${doNumber}`)
    
    if (response.data.success) {
      const doData = response.data.data
      
      // Populate form with existing data
      deliveryOrder.vehicleNumber = doData.DO_VHC_Num || ''
      deliveryOrder.orderDate = doData.DO_DMY ? convertDateFormat(doData.DO_DMY) : ''
      deliveryOrder.unapplyFG = doData.UNAPPLIED_FG === 'Y'
      deliveryOrder.remark1 = doData.DO_Remark1 || ''
      deliveryOrder.remark2 = doData.DO_Remark2 || ''
      deliveryOrder.cancelledReason = doData.Cancelled_Reason || ''
      
      // Load vehicle information if available
      if (doData.DO_VHC_Num) {
        await loadVehicleInfo(doData.DO_VHC_Num)
      }
    }
  } catch (err) {
    console.error('Error loading delivery order details:', err)
    error('Error loading delivery order details')
  }
}

const loadVehicleInfo = async (vehicleNumber) => {
  try {
    const response = await axios.get(`/api/vehicles/${vehicleNumber}`)
    
    if (response.data.success) {
      const vehicle = response.data.data
      selectedVehicle.id = vehicle.id
      selectedVehicle.vehicleNo = vehicle.VEHICLE_NO
      selectedVehicle.driverName = vehicle.DRIVER_NAME
      selectedVehicle.driverPhone = vehicle.DRIVER_PHONE
      selectedVehicle.vehicleClass = vehicle.VEHICLE_CLASS
      selectedVehicle.vehicleCompany = vehicle.VEHICLE_COMPANY
    }
  } catch (err) {
    console.error('Error loading vehicle info:', err)
  }
}

const convertDateFormat = (dateString) => {
  // Convert from DD/MM/YYYY to YYYY-MM-DD
  if (!dateString) return ''
  const parts = dateString.split('/')
  if (parts.length === 3) {
    return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`
  }
  return ''
}

const validateDeliveryOrder = async () => {
  if (!selectedDeliveryOrder.doNumber.trim()) return
  
  try {
    const response = await axios.get(`/api/delivery-orders/${selectedDeliveryOrder.doNumber}`)
    
    if (response.data.success) {
      const doData = response.data.data
      selectedDeliveryOrder.status = doData.Status
      selectedDeliveryOrder.customerCode = doData.AC_Num
      selectedDeliveryOrder.customerName = doData.AC_Name
      
      // Load the delivery order details
      await loadDeliveryOrderDetails(selectedDeliveryOrder.doNumber)
      
      success('Delivery order validated successfully')
    } else {
      error('Delivery order not found')
      resetDeliveryOrderSelection()
    }
  } catch (err) {
    console.error('Error validating delivery order:', err)
    error('Error validating delivery order')
    resetDeliveryOrderSelection()
  }
}

const resetDeliveryOrderSelection = () => {
  selectedDeliveryOrder.doNumber = ''
  selectedDeliveryOrder.status = ''
  selectedDeliveryOrder.customerCode = ''
  selectedDeliveryOrder.customerName = ''
  selectedDeliveryOrder.originalData = null
}

const openVehicleLookup = () => {
  showVehicleModal.value = true
}

const selectVehicle = (vehicle) => {
  selectedVehicle.id = vehicle.id
  selectedVehicle.vehicleNo = vehicle.VEHICLE_NO
  selectedVehicle.driverName = vehicle.DRIVER_NAME
  selectedVehicle.driverPhone = vehicle.DRIVER_PHONE
  selectedVehicle.vehicleClass = vehicle.VEHICLE_CLASS
  selectedVehicle.vehicleCompany = vehicle.VEHICLE_COMPANY
  
  // Update the delivery order vehicle number
  deliveryOrder.vehicleNumber = vehicle.VEHICLE_NO
  
  success(`Vehicle ${vehicle.VEHICLE_NO} selected successfully`)
}

const openDatePicker = () => {
  // TODO: Implement date picker modal
  info('Date picker functionality will be implemented')
}

const saveAmendedDeliveryOrder = async () => {
  if (!selectedDeliveryOrder.doNumber) {
    error('Please select a delivery order first')
    return
  }
  
  if (!canAmend.value) {
    error('This delivery order cannot be amended')
    return
  }
  
  try {
    const amendedData = {
      do_number: selectedDeliveryOrder.doNumber,
      vehicle_number: deliveryOrder.vehicleNumber,
      order_date: deliveryOrder.orderDate,
      remark1: deliveryOrder.remark1,
      remark2: deliveryOrder.remark2,
      unapply_fg: deliveryOrder.unapplyFG,
      cancelled_reason: deliveryOrder.cancelledReason
    }
    
    console.log('Saving amended delivery order:', amendedData)
    
    const response = await axios.put(`/api/delivery-orders/${selectedDeliveryOrder.doNumber}`, amendedData)
    
    if (response.data.success) {
      success(`Delivery order ${selectedDeliveryOrder.doNumber} amended successfully`)
      
      // Reload the delivery order details
      await loadDeliveryOrderDetails(selectedDeliveryOrder.doNumber)
    } else {
      error(response.data.message || 'Failed to amend delivery order')
    }
  } catch (err) {
    console.error('Error amending delivery order:', err)
    if (err.response?.data?.message) {
      error(err.response.data.message)
    } else {
      error('Error amending delivery order')
    }
  }
}

const refreshPage = () => {
  // Reset form data
  resetDeliveryOrderSelection()
  
  // Reset delivery order details
  Object.assign(deliveryOrder, {
    vehicleNumber: '',
    orderDate: '',
    unapplyFG: false,
    remark1: '',
    remark2: '',
    cancelledReason: ''
  })
  
  // Reset selected vehicle
  Object.assign(selectedVehicle, {
    id: null,
    vehicleNo: '',
    driverName: '',
    driverPhone: '',
    vehicleClass: '',
    vehicleCompany: ''
  })
  
  success('Form reset successfully')
}

const exitPage = () => {
  if (selectedDeliveryOrder.doNumber) {
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
    saveAmendedDeliveryOrder()
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
