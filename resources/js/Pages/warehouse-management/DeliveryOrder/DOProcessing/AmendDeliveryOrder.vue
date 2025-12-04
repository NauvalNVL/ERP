<template>
  <AppLayout header="Amend Delivery Order">
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
          <!-- Header with controls -->
          <div class="bg-blue-600 px-4 py-3 sm:px-6 border-b border-blue-700">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center">
                  <i class="fas fa-edit text-lg text-orange-100"></i>
                </div>
                <div>
                  <h1 class="text-lg sm:text-xl font-semibold text-white">Amend Delivery Order</h1>
                  <p class="text-xs text-blue-100">F2: DO Lookup • F3: Items • F4: Calendar • Ctrl+S: Save • F5: Refresh</p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <button 
                  @click="exitPage" 
                  class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/40 bg-white/10 hover:bg-white/20 text-red-100 transition-colors"
                  title="Exit"
                >
                  <i class="fas fa-power-off text-sm"></i>
                </button>
                <button 
                  @click="saveAmendedDeliveryOrder" 
                  class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/40 bg-white/10 hover:bg-white/20 text-green-100 transition-colors"
                  title="Save Changes"
                >
                  <i class="fas fa-save text-sm"></i>
                </button>
                <button 
                  @click="refreshPage" 
                  class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/40 bg-white/10 hover:bg-white/20 text-white transition-colors"
                  title="Refresh (F5)"
                >
                  <i class="fas fa-sync-alt text-sm"></i>
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

            <!-- Delivery To -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Delivery To:</label>
              <div class="flex items-start gap-2">
                <div class="flex-1">
                  <input
                    v-model="deliveryLocation.shipToName"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white"
                    placeholder="Select delivery location from table"
                    readonly
                  >
                  <textarea
                    v-model="deliveryLocation.shipToAddress"
                    rows="2"
                    class="w-full mt-2 px-3 py-2 border border-gray-300 rounded text-sm bg-gray-50"
                    readonly
                  ></textarea>
                </div>
                <button
                  @click="openDeliveryLocationModal"
                  class="p-2 text-orange-600 hover:bg-orange-100 rounded transition-colors"
                  title="Delivery Location Table"
                >
                  <i class="fas fa-th"></i>
                </button>
              </div>
            </div>

            <!-- Vehicle Information -->
            <div class="grid grid-cols-1 gap-4">
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
        </div>

        <!-- Notes Section -->
        <div v-if="selectedDeliveryOrder.doNumber" class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Notes</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>• Only Draft and Saved delivery orders can be amended.</p>
            <p>• Cancelled delivery orders cannot be amended.</p>
            <p>• Changes will be logged for audit purposes.</p>
            <p>• <strong>Bulk Update:</strong> All DO records with the same DO number will be updated together.</p>
            <p>• Only Main component records are shown in the lookup for amendment.</p>
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
  </div>
</div>

    <!-- Delivery Order Lookup Modal -->
    <DeliveryOrderLookupModal 
      :show="showDeliveryOrderModal" 
      context="amend"
      @close="showDeliveryOrderModal = false" 
      @select="selectDeliveryOrder"
    />

    <!-- Vehicle Lookup Modal -->
    <VehicleLookupModal 
      :is-open="showVehicleModal" 
      @close="showVehicleModal = false" 
      @select="selectVehicle"
    />

    <!-- Delivery Location Modal -->
    <DeliveryLocationModal
      :show="showDeliveryLocationModal"
      @close="showDeliveryLocationModal = false"
      @save="saveDeliveryLocation"
      :customer="{ code: selectedDeliveryOrder.customerCode, name: selectedDeliveryOrder.customerName }"
      :order-details="orderDetails"
      :use-order-details-delivery-location="true"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import VehicleLookupModal from '@/Components/VehicleTableModal.vue'
import DeliveryOrderLookupModal from '@/Components/DeliveryOrderLookupModal.vue'
import DeliveryLocationModal from '@/Components/DeliveryLocationModal.vue'
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
const showDeliveryLocationModal = ref(false)

// Selected Vehicle
const selectedVehicle = reactive({
  id: null,
  vehicleNo: '',
  driverName: '',
  driverPhone: '',
  vehicleClass: '',
  vehicleCompany: ''
})

// Delivery location state
const deliveryLocation = reactive({
  code: '',
  shipToName: '',
  shipToAddress: ''
})

// Delivery location details for modal (matches PrepareMCSO structure)
const orderDetails = reactive({
  deliveryLocation: null
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

const openDeliveryLocationModal = () => {
  if (!selectedDeliveryOrder.customerCode) {
    info('Please select a Delivery Order first')
    return
  }
  showDeliveryLocationModal.value = true
}

const openVehicleLookup = () => {
  showVehicleModal.value = true
}

const selectDeliveryOrder = async (doData) => {
  selectedDeliveryOrder.doNumber = doData.DO_Num
  selectedDeliveryOrder.status = doData.Status
  selectedDeliveryOrder.customerCode = doData.AC_Num
  selectedDeliveryOrder.customerName = doData.AC_Name
  selectedDeliveryOrder.originalData = doData
  
  // Load the delivery order details
  await loadDeliveryOrderDetails(doData.DO_Num)
  
  // Modal will auto-close via emit('close') from the modal component
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
      
      // Load current delivery location / Delivery To information
      await loadDeliveryLocationForDO(doData)
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

// Save delivery location from modal
const saveDeliveryLocation = (locationData) => {
  try {
    orderDetails.deliveryLocation = {
      orderBy: {
        name: locationData.orderBy?.customerName || '',
        address: locationData.orderBy?.address || '',
        email: locationData.orderBy?.email || '',
        contact: locationData.orderBy?.contact || ''
      },
      billTo: {
        name: locationData.billTo?.customerName || '',
        address: locationData.billTo?.address || '',
        email: locationData.billTo?.email || '',
        contact: locationData.billTo?.contact || ''
      },
      shipTo: {
        code: locationData.shipTo?.deliveryCode || '',
        name: locationData.shipTo?.customerName || '',
        address: locationData.shipTo?.address || '',
        email: locationData.shipTo?.email || '',
        contact: locationData.shipTo?.contact || ''
      }
    }

    // Update local Delivery To display
    deliveryLocation.code = orderDetails.deliveryLocation.shipTo.code
    deliveryLocation.shipToName =
      orderDetails.deliveryLocation.shipTo.name ||
      orderDetails.deliveryLocation.billTo.name
    deliveryLocation.shipToAddress =
      orderDetails.deliveryLocation.shipTo.address ||
      orderDetails.deliveryLocation.billTo.address

    showDeliveryLocationModal.value = false
    success('Delivery location updated successfully')
  } catch (err) {
    console.error('Error saving delivery location in AmendDeliveryOrder:', err)
    error('Error saving delivery location')
  }
}

// Prepare delivery location for DO based on Area1 / Del_Code
const loadDeliveryLocationForDO = async (doData) => {
  try {
    const customerCode = selectedDeliveryOrder.customerCode || doData.AC_Num
    if (!customerCode) {
      console.warn('No customer code available for DO delivery location')
      return
    }

    // Load main customer account data
    let mainName = selectedDeliveryOrder.customerName || ''
    let mainAddress = ''
    let mainEmail = ''
    let mainContact = ''

    try {
      const customerRes = await axios.get(`/api/sales-order/customer/${customerCode}`)
      const customerPayload = customerRes.data
      if (customerPayload?.success && customerPayload.data) {
        const c = customerPayload.data
        mainName = c.customer_name || mainName
        mainAddress = c.address || ''
        mainEmail = c.co_email || c.email || ''
        mainContact = c.contact_person || c.contact || ''
      }
    } catch (e) {
      console.error('Error loading customer account for DO delivery location:', e)
    }

    const area1 = doData.Area1 || ''
    const delCode = doData.Del_Code || ''

    let shipCode = delCode
    let shipName = ''
    let shipAddress = ''
    let shipEmail = ''
    let shipContact = ''

    // When Del_Code differs from Area1, use customer alternate address as Ship To
    if (delCode && delCode !== area1) {
      try {
        const altRes = await axios.get(`/api/customer-alternate-addresses/${customerCode}`)
        const altData = altRes.data
        if (Array.isArray(altData)) {
          const alt = altData.find(a =>
            (a.delivery_code || '').toUpperCase() === delCode.toUpperCase()
          )
          if (alt) {
            shipName = alt.ship_to_name || alt.bill_to_name || mainName
            shipAddress = alt.ship_to_address || alt.bill_to_address || mainAddress
            shipEmail = alt.email || mainEmail
            shipContact = alt.contact_person || mainContact
          }
        }
      } catch (e) {
        console.error('Error loading customer alternate address for DO:', e)
      }
    }

    // Fallback to main customer when no alternate found
    if (!shipName) shipName = mainName
    if (!shipAddress) shipAddress = mainAddress
    if (!shipEmail) shipEmail = mainEmail
    if (!shipContact) shipContact = mainContact

    deliveryLocation.code = shipCode
    deliveryLocation.shipToName = shipName
    deliveryLocation.shipToAddress = shipAddress

    orderDetails.deliveryLocation = {
      orderBy: {
        name: mainName,
        address: '',
        email: mainEmail,
        contact: mainContact
      },
      billTo: {
        name: mainName,
        address: mainAddress,
        email: mainEmail,
        contact: mainContact
      },
      shipTo: {
        code: shipCode,
        name: shipName,
        address: shipAddress,
        email: shipEmail,
        contact: shipContact
      }
    }
  } catch (err) {
    console.error('Error preparing delivery location for DO:', err)
  }
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
      cancelled_reason: deliveryOrder.cancelledReason,
      delivery_code: deliveryLocation.code
    }
    
    console.log('Saving amended delivery order:', amendedData)
    
    const response = await axios.put(`/api/delivery-orders/${selectedDeliveryOrder.doNumber}`, amendedData)
    
    if (response.data.success) {
      const affectedRows = response.data.data?.affected_rows || 1
      const message = affectedRows > 1 
        ? `Delivery order ${selectedDeliveryOrder.doNumber} amended successfully. ${affectedRows} records updated.`
        : `Delivery order ${selectedDeliveryOrder.doNumber} amended successfully.`
      
      success(message)
      
      // Reset form after successful save
      refreshPage()
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
  
  // Reset delivery location
  Object.assign(deliveryLocation, {
    code: '',
    shipToName: '',
    shipToAddress: ''
  })
  orderDetails.deliveryLocation = null
  
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
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
/* Custom styles if needed */
</style>
