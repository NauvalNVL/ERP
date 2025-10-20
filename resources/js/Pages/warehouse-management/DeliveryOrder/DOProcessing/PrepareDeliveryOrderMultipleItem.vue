<template>
  <AppLayout header="Prepare Delivery Order (Multiple Item)">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-truck text-2xl text-blue-600"></i>
            <div>
              <h1 class="text-xl font-semibold text-gray-800">Prepare Delivery Order (Multiple Item)</h1>
              <p class="text-xs text-gray-500">F2: Customer • F3: Items • F4: Calendar • F8: Next • F5: Refresh</p>
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
              @click="openSalesOrderScreenNext" 
              class="p-2 text-green-600 hover:bg-green-100 rounded-full transition-colors"
              title="Next"
            >
              <i class="fas fa-arrow-right"></i>
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

          <!-- Customer Code -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code:</label>
            <div class="flex items-center space-x-2">
              <input 
                v-model="selectedCustomer.code" 
                type="text"
                class="w-32 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter customer code"
                @blur="validateCustomer"
              >
              <div v-if="selectedCustomer.name" class="px-3 py-2 bg-gray-100 border border-gray-300 rounded text-sm text-gray-700 min-w-0 flex-1">
                {{ selectedCustomer.name }}
              </div>
              <button 
                @click="openCustomerLookup"
                class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                title="Customer Lookup"
              >
                <i class="fas fa-th"></i>
              </button>
            </div>
          </div>

          <!-- Delivery Order Details Container (shown when customer is selected) -->
          <div v-if="selectedCustomer.code" class="space-y-4">
            <!-- Cust. Remark -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cust. Remark:</label>
              <input 
                v-model="deliveryOrder.custRemark"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter customer remark"
              >
            </div>

            <!-- Vehicle# -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle#:</label>
              <div class="flex items-center space-x-2">
                <input 
                  v-model="deliveryOrder.vehicleNumber"
                  type="text"
                  class="w-32 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter vehicle number"
                  readonly
                >
                <button 
                  @click="openVehicleLookup"
                  class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                  title="Vehicle Lookup"
                >
                  <i class="fas fa-th"></i>
                </button>
              </div>
              <!-- Vehicle Information Display -->
              <div v-if="selectedVehicle.vehicleNo" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
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
            </div>

            <!-- D/Order Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">D/Order Date:</label>
              <div class="flex items-center space-x-2">
                <input 
                  v-model="deliveryOrder.orderDate"
                  type="date"
                  class="w-40 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                <button 
                  @click="openDatePicker"
                  class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors"
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
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              >
              <label class="text-sm text-gray-700">Tick for Y-Yes</label>
            </div>

            <!-- Remark1 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Remark1:</label>
              <input 
                v-model="deliveryOrder.remark1"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter remark 1"
              >
            </div>

            <!-- Remark2 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Remark2:</label>
              <input 
                v-model="deliveryOrder.remark2"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter remark 2"
              >
            </div>

            <!-- Selected SO Information -->
            <div v-if="deliveryOrder.soNumber" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
              <h4 class="text-sm font-medium text-green-800 mb-2">Selected Sales Order:</h4>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                  <span class="font-medium text-green-700">SO Number:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.soNumber }}</span>
                </div>
                <div>
                  <span class="font-medium text-green-700">M/Card Seq:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.mcardSeq }}</span>
                </div>
                <div class="col-span-2">
                  <span class="font-medium text-green-700">P/Order Ref:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.pOrderRef }}</span>
                </div>
                <div class="col-span-2">
                  <span class="font-medium text-green-700">Items:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.itemDetails.filter(item => item.toDeliver).length }} item(s) to deliver</span>
                </div>
                <div v-if="deliveryOrder.packingItems && deliveryOrder.packingItems.length > 0" class="col-span-2">
                  <span class="font-medium text-green-700">Packing:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.packingItems.filter(item => item.rolls || item.qty).length }} item(s) packed</span>
                </div>
                <div v-if="deliveryOrder.offsetItems && deliveryOrder.offsetItems.length > 0" class="col-span-2">
                  <span class="font-medium text-green-700">Offsets:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.offsetItems.filter(item => item.toOffset).length }} item(s) with offsets</span>
                </div>
                <div v-if="deliveryOrder.salesOrderData && deliveryOrder.salesOrderData.length > 0" class="col-span-2">
                  <span class="font-medium text-green-700">SO Records:</span>
                  <span class="text-green-600 ml-2">{{ deliveryOrder.salesOrderData.length }} SO record(s)</span>
                </div>
              </div>
            </div>
          </div>

        </div>


        <!-- Notes Section -->
        <div v-if="selectedCustomer.code" class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Notes</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>Unapplied F/G is for D/Order without F/G Inventory. It can be reconciled at the later stage once the F/G is available.</p>
            <p>Do not issue Invoice before reconciliation.</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div v-if="selectedCustomer.code" class="mt-6 flex items-center justify-end">
          <!-- Right side buttons -->
          <div class="flex items-center space-x-4">
            <button 
              @click="refreshPage" 
              class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center"
            >
              <i class="fas fa-sync-alt mr-2"></i>
              Refresh
            </button>
            <button 
              @click="openSalesOrderScreenNext" 
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center"
            >
              <i class="fas fa-arrow-right mr-2"></i>
              Next
            </button>
          </div>
        </div>

        <!-- Form validation summary -->
        <div v-if="!selectedCustomer.code" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <span class="text-sm text-yellow-800">
              Please select a customer to continue with delivery order preparation.
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Lookup Modal -->
    <CustomerAccountModal 
      :show="showCustomerModal" 
      @close="showCustomerModal = false" 
      @select="selectCustomer"
      :initial-sort-by="'customer_code'"
    />

    <!-- Vehicle Lookup Modal -->
    <VehicleLookupModal 
      :is-open="showVehicleModal" 
      @close="showVehicleModal = false" 
      @select="selectVehicle"
    />

    <!-- Sales Order Screen Modal -->
    <SalesOrderScreenModal 
      :is-open="showSalesOrderModal" 
      :customer-data="selectedCustomer"
      @close="showSalesOrderModal = false" 
      @save="handleSalesOrderSave"
      @save-delivery-order="handleSalesOrderDeliveryOrderSave"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue'
import VehicleLookupModal from '@/Components/VehicleLookupModal.vue'
import SalesOrderScreenModal from '@/Components/SalesOrderScreenModal.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error, info } = useToast()

// Period Information
const currentPeriod = reactive({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
})

// Customer Information
const selectedCustomer = reactive({
  code: '',
  name: '',
  address: '',
  salesperson: '',
  currency: 'IDR'
})


// Delivery Order Details
const deliveryOrder = reactive({
  custRemark: '',
  vehicleNumber: '',
  orderDate: new Date().toISOString().split('T')[0],
  unapplyFG: false,
  remark1: '',
  remark2: '',
  soNumber: '',
  mcardSeq: '',
  pOrderRef: '',
  itemDetails: [],
  packingItems: [],
  offsetDetails: {},
  offsetItems: [],
  salesOrderData: []
})

// Modal visibility
const showCustomerModal = ref(false)
const showVehicleModal = ref(false)
const showSalesOrderModal = ref(false)

// Selected Vehicle
const selectedVehicle = reactive({
  id: null,
  vehicleNo: '',
  driverName: '',
  driverPhone: '',
  vehicleClass: '',
  vehicleCompany: ''
})

// Delivery Order Status
const deliveryOrderStatus = ref('Draft')

// Computed properties
const canProceed = computed(() => {
  return selectedCustomer.code && selectedVehicle.vehicleNo
})

// Day of week computed property
const dayOfWeek = computed(() => {
  if (!deliveryOrder.orderDate) return ''
  const date = new Date(deliveryOrder.orderDate)
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  return days[date.getDay()]
})

// Functions
const openCustomerLookup = () => {
  showCustomerModal.value = true
}

const selectCustomer = async (customer) => {
  selectedCustomer.code = customer.customer_code
  selectedCustomer.name = customer.customer_name
  selectedCustomer.address = customer.address || ''
  selectedCustomer.salesperson = customer.salesperson_code || ''
  selectedCustomer.currency = customer.currency_code || 'IDR'
  
  showCustomerModal.value = false
  success('Customer selected successfully')
}

const validateCustomer = async () => {
  if (!selectedCustomer.code.trim()) return
  
  try {
    const response = await fetch(`/api/sales-order/customer/${selectedCustomer.code}`)
    const data = await response.json()
    
    if (data.success) {
      const customer = data.data
      selectedCustomer.name = customer.customer_name
      selectedCustomer.address = customer.address || ''
      selectedCustomer.salesperson = customer.salesperson_code || ''
      selectedCustomer.currency = customer.currency_code || 'IDR'
      
      success('Customer validated successfully')
    } else {
      error('Customer not found')
      selectedCustomer.name = ''
      selectedCustomer.address = ''
    }
  } catch (err) {
    console.error('Error validating customer:', err)
    error('Error validating customer')
    selectedCustomer.name = ''
    selectedCustomer.address = ''
  }
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

const openSalesOrderScreenNext = () => {
  if (!selectedCustomer.code) {
    error('Please select a customer first')
    return
  }
  
  if (!deliveryOrder.orderDate) {
    error('Please select delivery order date')
    return
  }
  
  showSalesOrderModal.value = true
}

const handleSalesOrderSave = (salesOrderData) => {
  console.log('Complete Sales Order Data:', salesOrderData)
  
  // Process the complete data from Sales Order Detail + Packing Details
  if (salesOrderData.salesOrderDetail) {
    const detailData = salesOrderData.salesOrderDetail
    console.log('Order Detail:', detailData.orderDetail)
    console.log('Item Rows:', detailData.itemRows)
    
    // Update delivery order with SO details
    if (detailData.orderDetail.sOrderMonth && detailData.orderDetail.sOrderYear && detailData.orderDetail.sOrderSeq) {
      const soNumber = `${detailData.orderDetail.sOrderMonth}-${detailData.orderDetail.sOrderYear}-${detailData.orderDetail.sOrderSeq}`
      console.log('Processing SO Number:', soNumber)
      
      // Store the SO data for further processing
      deliveryOrder.soNumber = soNumber
      deliveryOrder.mcardSeq = detailData.orderDetail.mcardSeq
      deliveryOrder.pOrderRef = detailData.orderDetail.pOrderRef
      deliveryOrder.itemDetails = detailData.itemRows
    }
  }
  
  // Process packing details if available
  if (salesOrderData.packingDetails) {
    const packingData = salesOrderData.packingDetails
    console.log('Packing Items:', packingData.packingItems)
    
    // Store packing data
    deliveryOrder.packingItems = packingData.packingItems
  }
  
  // Process finished goods offsets if available
  if (salesOrderData.finishedGoodsOffsets) {
    const offsetsData = salesOrderData.finishedGoodsOffsets
    console.log('Offset Details:', offsetsData.offsetDetails)
    console.log('Offset Items:', offsetsData.offsetItems)
    console.log('Sales Order Data:', offsetsData.salesOrderData)
    
    // Store offsets data
    deliveryOrder.offsetDetails = offsetsData.offsetDetails
    deliveryOrder.offsetItems = offsetsData.offsetItems
    deliveryOrder.salesOrderData = offsetsData.salesOrderData
  }
  
  success('Sales order and packing details saved successfully')
  showSalesOrderModal.value = false
}

const handleSalesOrderDeliveryOrderSave = async (salesOrderData) => {
  console.log('Complete Sales Order Data for Delivery Order:', salesOrderData)
  
  // Process the complete data from Sales Order Detail + Packing Details + Finished Goods Offsets
  if (salesOrderData.salesOrderDetail) {
    const detailData = salesOrderData.salesOrderDetail
    console.log('Order Detail:', detailData.orderDetail)
    console.log('Item Rows:', detailData.itemRows)
    
    // Update delivery order with SO details
    if (detailData.orderDetail.sOrderMonth && detailData.orderDetail.sOrderYear && detailData.orderDetail.sOrderSeq) {
      const soNumber = `${detailData.orderDetail.sOrderMonth}-${detailData.orderDetail.sOrderYear}-${detailData.orderDetail.sOrderSeq}`
      console.log('Processing SO Number:', soNumber)
      
      // Store the SO data for further processing
      deliveryOrder.soNumber = soNumber
      deliveryOrder.mcardSeq = detailData.orderDetail.mcardSeq
      deliveryOrder.pOrderRef = detailData.orderDetail.pOrderRef
      deliveryOrder.itemDetails = detailData.itemRows
    }
  }
  
  // Process packing details if available
  if (salesOrderData.packingDetails) {
    const packingData = salesOrderData.packingDetails
    console.log('Packing Items:', packingData.packingItems)
    
    // Store packing data
    deliveryOrder.packingItems = packingData.packingItems
  }
  
  // Process finished goods offsets if available
  if (salesOrderData.finishedGoodsOffsets) {
    const offsetsData = salesOrderData.finishedGoodsOffsets
    console.log('Offset Details:', offsetsData.offsetDetails)
    console.log('Offset Items:', offsetsData.offsetItems)
    console.log('Sales Order Data:', offsetsData.salesOrderData)
    
    // Store offsets data
    deliveryOrder.offsetDetails = offsetsData.offsetDetails
    deliveryOrder.offsetItems = offsetsData.offsetItems
    deliveryOrder.salesOrderData = offsetsData.salesOrderData
  }
  
  // Now save the delivery order
  await saveDeliveryOrder()
  showSalesOrderModal.value = false
}

const saveDeliveryOrder = async () => {
  if (!selectedCustomer.code) {
    error('Please select a customer first')
    return
  }
  
  if (!selectedVehicle.vehicleNo) {
    error('Please select a vehicle first')
    return
  }
  
  try {
    const deliveryOrderData = {
      customer_code: selectedCustomer.code,
      vehicle_number: selectedVehicle.vehicleNo,
      order_date: deliveryOrder.orderDate,
      cust_remark: deliveryOrder.custRemark,
      remark1: deliveryOrder.remark1,
      remark2: deliveryOrder.remark2,
      unapply_fg: deliveryOrder.unapplyFG
    }
    
    console.log('Saving delivery order:', deliveryOrderData)
    
    const response = await axios.post('/api/delivery-orders', deliveryOrderData)
    
    if (response.data.success) {
      success(`Delivery order ${response.data.data.do_number} saved successfully`)
      deliveryOrderStatus.value = 'Saved'
      
      // Reset form after successful save
      refreshPage()
    } else {
      error(response.data.message || 'Failed to save delivery order')
    }
  } catch (err) {
    console.error('Error saving delivery order:', err)
    if (err.response?.data?.message) {
      error(err.response.data.message)
    } else {
      error('Error saving delivery order')
    }
  }
}

const refreshPage = () => {
  // Reset form data
  selectedCustomer.code = ''
  selectedCustomer.name = ''
  selectedCustomer.address = ''
  selectedCustomer.salesperson = ''
  selectedCustomer.currency = 'IDR'
  
  deliveryOrderStatus.value = 'Draft'
  
  // Reset delivery order details
  Object.assign(deliveryOrder, {
    custRemark: '',
    vehicleNumber: '',
    orderDate: new Date().toISOString().split('T')[0],
    unapplyFG: false,
    remark1: '',
    remark2: '',
    soNumber: '',
    mcardSeq: '',
    pOrderRef: '',
    itemDetails: [],
    packingItems: [],
    offsetDetails: {},
    offsetItems: [],
    salesOrderData: []
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
  if (selectedCustomer.code || selectedVehicle.vehicleNo) {
    if (confirm('You have unsaved changes. Are you sure you want to exit?')) {
      // Navigate back or close
      window.history.back()
    }
  } else {
    window.history.back()
  }
}

// Keyboard shortcuts
const handleKeydown = (event) => {
  if (event.key === 'F8') {
    event.preventDefault()
    openSalesOrderScreenNext()
  } else if (event.key === 'F5') {
    event.preventDefault()
    refreshPage()
  } else if (event.key === 'F2') {
    event.preventDefault()
    openCustomerLookup()
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
