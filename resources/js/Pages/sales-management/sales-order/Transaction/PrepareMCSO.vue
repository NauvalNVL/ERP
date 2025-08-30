<template>
  <AppLayout header="Prepare MC SO">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
            <h1 class="text-xl font-semibold text-gray-800">Prepare MC SO</h1>
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="refreshPage" 
              class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
              title="Refresh"
            >
              <i class="fas fa-sync-alt"></i>
            </button>
            <button 
              @click="printLog" 
              class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
            >
              <i class="fas fa-print mr-1"></i>
              Print SO Log
            </button>
            <button 
              @click="printJitTracking" 
              class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
            >
              <i class="fas fa-print mr-1"></i>
              Print SO JIT Tracking
            </button>
          </div>
        </div>
      </div>

      <!-- Main Form Content -->
      <div class="p-6">
        <!-- Period and Customer Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Period Information -->
          <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Period Information</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Current Period:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="currentPeriod.month" 
                      type="number" 
                      min="1" 
                      max="12" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <input 
                      v-model="currentPeriod.year" 
                      type="number" 
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Update Period:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="updatePeriod.month" 
                      type="number" 
                      min="1" 
                      max="12" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <input 
                      v-model="updatePeriod.year" 
                      type="number" 
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <span class="text-xs text-gray-500">mm/yyyy</span>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4 mt-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Forward Period:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="forwardPeriod" 
                      type="number" 
                      min="1" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <span class="text-xs text-gray-500">Months</span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Backward Period:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="backwardPeriod" 
                      type="number" 
                      min="1" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <span class="text-xs text-gray-500">Months</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Last SO Order ID -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Order Information</h3>
              <div class="flex items-center space-x-2">
                <label class="text-xs font-medium text-gray-600">Last S/Order#:</label>
                <input 
                  v-model="lastSOOrder.prefix" 
                  type="text" 
                  class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                >
                <input 
                  v-model="lastSOOrder.year" 
                  type="number" 
                  class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                >
                <input 
                  v-model="lastSOOrder.number" 
                  type="number" 
                  class="w-24 px-2 py-1 border border-gray-300 rounded text-sm"
                >
              </div>
            </div>
          </div>

          <!-- Customer Information -->
          <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Customer Information</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Customer Code:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="selectedCustomer.code" 
                      type="text" 
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter customer code"
                      @blur="validateCustomer"
                    >
                    <button 
                      @click="openCustomerLookup" 
                      class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                      title="Customer Lookup"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div v-if="selectedCustomer.name" class="mt-1 text-sm text-gray-600">
                    {{ selectedCustomer.name }}
                  </div>
                </div>
                
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">M/Card Seq#:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="selectedMasterCard.seq" 
                      type="text" 
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter master card sequence"
                      @blur="validateMasterCard"
                    >
                    <button 
                      @click="openMasterCardLookup" 
                      class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                      title="Master Card Lookup"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div v-if="selectedMasterCard.model" class="mt-1 text-sm text-gray-600">
                    {{ selectedMasterCard.model }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Order Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6" v-if="selectedCustomer.code && selectedMasterCard.seq">
          <h3 class="text-sm font-medium text-gray-700 mb-4">Sales Order Details</h3>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Order Information -->
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                  <select 
                    v-model="orderDetails.orderMode" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="0">0-Order by Customer</option>
                    <option value="1">1-Deliver & Invoice to Customer</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Product:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.product.code" 
                      type="text" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <input 
                      v-model="orderDetails.product.name" 
                      type="text" 
                      class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                      readonly
                    >
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.salesperson.code" 
                      type="text" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <input 
                      v-model="orderDetails.salesperson.name" 
                      type="text" 
                      class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                      readonly
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">A/C Currency:</label>
                  <input 
                    v-model="orderDetails.currency" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                    readonly
                  >
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Rate:</label>
                  <input 
                    v-model="orderDetails.exchangeRate" 
                    type="number" 
                    step="0.000001" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Method:</label>
                  <input 
                    v-model="orderDetails.exchangeMethod" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                  >
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cust.P/Order#:</label>
                <input 
                  v-model="orderDetails.customerPOrder" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">P/Order Date:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="orderDetails.pOrderDate" 
                    type="date" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                  <button 
                    @click="openCalendar" 
                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    title="Open Calendar"
                  >
                    <i class="fas fa-calendar-alt"></i>
                  </button>
                  <span class="text-xs text-gray-500">{{ dayOfWeek }}</span>
                </div>
              </div>
            </div>

            <!-- Order Configuration -->
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Set Quantity:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="orderDetails.setQuantity" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  >
                  <span class="text-sm text-gray-700">Leave blank for loose item order</span>
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                <div class="flex items-center space-x-4">
                  <label class="flex items-center">
                    <input 
                      v-model="orderDetails.orderGroup" 
                      type="radio" 
                      value="Sales" 
                      class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-sm text-gray-700">Sales</span>
                  </label>
                  <label class="flex items-center">
                    <input 
                      v-model="orderDetails.orderGroup" 
                      type="radio" 
                      value="Non-Sales" 
                      class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-sm text-gray-700">Non-Sales</span>
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                <select 
                  v-model="orderDetails.orderType" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="S1-Sales">S1-Sales (SO-Corr-Conv-FG-DO-IV)</option>
                  <option value="S2-Direct">S2-Direct Sales</option>
                  <option value="S3-Sample">S3-Sample</option>
                </select>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Tax:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.salesTax" 
                      type="checkbox" 
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-sm text-gray-700">Tick for Y-Yes</span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Lot Number:</label>
                  <input 
                    v-model="orderDetails.lotNumber" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remark:</label>
                <textarea 
                  v-model="orderDetails.remark" 
                  rows="2" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction1:</label>
                <textarea 
                  v-model="orderDetails.instruction1" 
                  rows="2" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction2:</label>
                <textarea 
                  v-model="orderDetails.instruction2" 
                  rows="2" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-center space-x-4 mt-6">
            <button 
              @click="openProductDesignScreen" 
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center"
              :disabled="!canProceed"
            >
              <i class="fas fa-cogs mr-2"></i>
              Continue to Product Design
            </button>
            <button 
              @click="openDeliveryLocation" 
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center"
              :disabled="!canProceed"
            >
              <i class="fas fa-truck mr-2"></i>
              Set Delivery Location
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Lookup Modal -->
    <CustomerAccountModal 
      v-if="showCustomerModal" 
      @close="showCustomerModal = false" 
      @select="selectCustomer"
      :sort-by="'customer_code'"
      :filter-active="true"
    />

    <!-- Master Card Lookup Modal -->
    <MasterCardSearchSelectModal 
      v-if="showMasterCardModal" 
      @close="showMasterCardModal = false" 
      @select="selectMasterCard"
      :customer-code="selectedCustomer.code"
      :sort-by="'mc_seq'"
      :filter-active="true"
    />

    <!-- Product Design Screen Modal -->
    <ProductDesignScreenModal 
      v-if="showProductDesignModal" 
      @close="showProductDesignModal = false" 
      @save="saveProductDesign"
      :master-card="selectedMasterCard"
      :customer="selectedCustomer"
    />

    <!-- Delivery Location Modal -->
    <DeliveryLocationModal 
      v-if="showDeliveryLocationModal" 
      @close="showDeliveryLocationModal = false" 
      @save="saveDeliveryLocation"
      :customer="selectedCustomer"
      :order-details="orderDetails"
    />

    <!-- Delivery Schedule Modal -->
    <DeliveryScheduleModal 
      v-if="showDeliveryScheduleModal" 
      @close="showDeliveryScheduleModal = false" 
      @save="saveDeliverySchedule"
      :order-details="orderDetails"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue'
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue'
import ProductDesignScreenModal from '@/Components/ProductDesignScreenModal.vue'
import DeliveryScheduleModal from '@/Components/DeliveryScheduleModal.vue'
import DeliveryLocationModal from '@/Components/DeliveryLocationModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Period Information
const currentPeriod = reactive({
  month: 8,
  year: 2025
})

const updatePeriod = reactive({
  month: 8,
  year: 2025
})

const forwardPeriod = ref(1)
const backwardPeriod = ref(1)

// Last SO Order Information
const lastSOOrder = reactive({
  prefix: '5',
  year: 2019,
  number: 640
})

// Customer Information
const selectedCustomer = reactive({
  code: '',
  name: '',
  address: '',
  salesperson: '',
  currency: 'IDR'
})

// Master Card Information
const selectedMasterCard = reactive({
  seq: '',
  model: '',
  status: '',
  partNo: '',
  compNo: '',
  pDesign: ''
})

// Order Details
const orderDetails = reactive({
  orderMode: '0',
  product: {
    code: '001',
    name: 'BOX'
  },
  salesperson: {
    code: '',
    name: ''
  },
  currency: 'IDR',
  exchangeRate: 0.000000,
  exchangeMethod: 'N/A',
  customerPOrder: '',
  pOrderDate: new Date().toISOString().split('T')[0],
  setQuantity: false,
  orderGroup: 'Sales',
  orderType: 'S1-Sales',
  salesTax: false,
  lotNumber: '',
  remark: '',
  instruction1: '',
  instruction2: ''
})

// Modal visibility
const showCustomerModal = ref(false)
const showMasterCardModal = ref(false)
const showProductDesignModal = ref(false)
const showDeliveryLocationModal = ref(false)
const showDeliveryScheduleModal = ref(false)

// Computed properties
const canProceed = computed(() => {
  return selectedCustomer.code && selectedMasterCard.seq
})

const dayOfWeek = computed(() => {
  if (!orderDetails.pOrderDate) return ''
  const date = new Date(orderDetails.pOrderDate)
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  return days[date.getDay()]
})

// Methods
const refreshPage = () => {
  window.location.reload()
}

const printLog = () => {
  // Open print dialog for SO Log
  window.open('/api/sales-order/print-log', '_blank')
}

const printJitTracking = () => {
  // Open print dialog for JIT Tracking
  window.open('/api/sales-order/print-jit-tracking', '_blank')
}

const openCustomerLookup = () => {
  showCustomerModal.value = true
}

const selectCustomer = (customer) => {
  selectedCustomer.code = customer.customer_code
  selectedCustomer.name = customer.customer_name
  selectedCustomer.address = customer.address || ''
  selectedCustomer.salesperson = customer.salesperson_code || ''
  selectedCustomer.currency = customer.currency_code || 'IDR'
  
  // Update order details
  orderDetails.salesperson.code = customer.salesperson_code || ''
  orderDetails.currency = customer.currency_code || 'IDR'
  
  showCustomerModal.value = false
  success('Customer selected successfully')
}

const validateCustomer = async () => {
  if (!selectedCustomer.code) return
  
  try {
    const response = await fetch(`/api/customer-accounts?search=${selectedCustomer.code}`)
    const data = await response.json()
    
    if (data.data && data.data.length > 0) {
      const customer = data.data[0]
      selectCustomer(customer)
    } else {
      error('Customer not found')
      selectedCustomer.name = ''
    }
  } catch (err) {
    error('Error validating customer')
  }
}

const openMasterCardLookup = () => {
  if (!selectedCustomer.code) {
    error('Please select a customer first')
    return
  }
  showMasterCardModal.value = true
}

const selectMasterCard = (masterCard) => {
  selectedMasterCard.seq = masterCard.mc_seq
  selectedMasterCard.model = masterCard.mc_model || masterCard.model
  selectedMasterCard.status = masterCard.status
  selectedMasterCard.partNo = masterCard.part_no
  selectedMasterCard.compNo = masterCard.comp_no
  selectedMasterCard.pDesign = masterCard.p_design
  
  showMasterCardModal.value = false
  success('Master card selected successfully')
}

const validateMasterCard = async () => {
  if (!selectedMasterCard.seq) return
  
  try {
    const response = await fetch(`/api/update-mc/check-mcs/${selectedMasterCard.seq}`)
    const data = await response.json()
    
    if (data.exists && data.data) {
      selectMasterCard(data.data)
    } else {
      error('Master card not found')
      selectedMasterCard.model = ''
    }
  } catch (err) {
    error('Error validating master card')
  }
}

const openCalendar = () => {
  // Open calendar widget or date picker
  const dateInput = document.querySelector('input[type="date"]')
  if (dateInput) {
    dateInput.showPicker()
  }
}

const openProductDesignScreen = () => {
  if (!canProceed.value) {
    error('Please select customer and master card first')
    return
  }
  showProductDesignModal.value = true
}

const saveProductDesign = (designData) => {
  console.log('Product design saved:', designData)
  showProductDesignModal.value = false
  success('Product design saved successfully')
  
  // Open delivery schedule after product design
  setTimeout(() => {
    showDeliveryScheduleModal.value = true
  }, 500)
}

const openDeliveryLocation = () => {
  if (!canProceed.value) {
    error('Please select customer and master card first')
    return
  }
  showDeliveryLocationModal.value = true
}

const saveDeliveryLocation = (locationData) => {
  console.log('Delivery location saved:', locationData)
  showDeliveryLocationModal.value = false
  success('Delivery location saved successfully')
}

const saveDeliverySchedule = (scheduleData) => {
  console.log('Delivery schedule saved:', scheduleData)
  showDeliveryScheduleModal.value = false
  success('Delivery schedule saved successfully')
}

// Initialize component
onMounted(() => {
  // Set default values
  const today = new Date()
  currentPeriod.month = today.getMonth() + 1
  currentPeriod.year = today.getFullYear()
  updatePeriod.month = today.getMonth() + 1
  updatePeriod.year = today.getFullYear()
})
</script>

<style scoped>
/* Custom input focus styles */
input:focus, select:focus, textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Disabled button styles */
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Grid responsive adjustments */
@media (max-width: 1024px) {
  .grid-cols-1.lg\:grid-cols-2 {
    grid-template-columns: 1fr;
  }
}

/* Date input styles */
input[type="date"]::-webkit-calendar-picker-indicator {
  background: transparent;
  bottom: 0;
  color: transparent;
  cursor: pointer;
  height: auto;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: auto;
}
</style>
