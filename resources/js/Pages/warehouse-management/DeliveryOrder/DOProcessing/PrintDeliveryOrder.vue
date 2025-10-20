<template>
  <AppLayout header="Print Delivery Order">
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <div class="bg-white/20 p-3 rounded-lg">
                    <span class="text-3xl">🖨️</span>
                  </div>
                  <div>
                    <h1 class="text-2xl font-bold text-white">Print Delivery Order</h1>
                    <p class="text-blue-100 text-sm mt-1">Generate and print delivery order documents</p>
                  </div>
                </div>
                <div class="hidden md:flex items-center space-x-2 text-blue-100 text-sm">
                  <span class="bg-white/20 px-3 py-1 rounded-full">F2: Lookup</span>
                  <span class="bg-white/20 px-3 py-1 rounded-full">F5: Refresh</span>
                  <span class="bg-white/20 px-3 py-1 rounded-full">Ctrl+P: Print</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <!-- Form Header -->
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
              <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
              Print Configuration
            </h2>
          </div>
          
          <!-- Form Content -->
          <div class="p-6 space-y-8">
            <!-- Current Period Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
              <div class="flex items-center mb-4">
                <div class="bg-blue-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">📅</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Current Period</h3>
                  <p class="text-sm text-gray-600">Set the current working period</p>
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                  <input 
                    v-model="currentPeriod.month" 
                    type="number" 
                    min="1" 
                    max="12" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                    placeholder="MM"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                  <input 
                    v-model="currentPeriod.year" 
                    type="number" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                    placeholder="YYYY"
                  >
                </div>
                <div class="text-center">
                  <div class="bg-white px-4 py-3 rounded-lg border border-gray-200 text-sm text-gray-600 font-medium">
                    Format: MM/YYYY
                  </div>
                </div>
              </div>
            </div>

            <!-- Delivery Order Range Card -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
              <div class="flex items-center mb-4">
                <div class="bg-green-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">📋</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Delivery Order Range</h3>
                  <p class="text-sm text-gray-600">Specify the range of delivery orders to print</p>
                </div>
              </div>
              
              <div class="space-y-6">
                <!-- From Range -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-3">From DO Number</label>
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Month</label>
                      <input 
                        v-model="doRange.fromMonth" 
                        type="number" 
                        min="1" 
                        max="12" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="MM"
                      >
                    </div>
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Year</label>
                      <input 
                        v-model="doRange.fromYear" 
                        type="number" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="YYYY"
                      >
                    </div>
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Number</label>
                      <input 
                        v-model="doRange.fromNumber" 
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="00000"
                      >
                    </div>
                    <div class="flex items-end">
                      <button 
                        @click="openDOModal"
                        class="w-full px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium"
                        title="DO Lookup"
                      >
                        🔍 Lookup
                      </button>
                    </div>
                  </div>
                </div>
                
                <!-- To Range -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-3">To DO Number</label>
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Month</label>
                      <input 
                        v-model="doRange.toMonth" 
                        type="number" 
                        min="1" 
                        max="12" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="MM"
                      >
                    </div>
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Year</label>
                      <input 
                        v-model="doRange.toYear" 
                        type="number" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="YYYY"
                      >
                    </div>
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">Number</label>
                      <input 
                        v-model="doRange.toNumber" 
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                        placeholder="99999"
                      >
                    </div>
                    <div class="flex items-end">
                      <button 
                        @click="openToDOModal"
                        class="w-full px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium"
                        title="DO Lookup"
                      >
                        🔍 Lookup
                      </button>
                    </div>
                  </div>
                </div>
                
                <!-- Format Info -->
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                  <div class="flex items-center text-sm text-gray-600">
                    <span class="text-green-500 mr-2">ℹ️</span>
                    <span class="font-medium">Format:</span>
                    <span class="ml-2 font-mono bg-gray-100 px-2 py-1 rounded">MM-YYYY-NNNNN</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Selection Card -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
              <div class="flex items-center mb-4">
                <div class="bg-purple-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">👤</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Customer Selection</h3>
                  <p class="text-sm text-gray-600">Filter by specific customer (optional)</p>
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code</label>
                  <input 
                    v-model="customer.code" 
                    type="text" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                    placeholder="Enter customer code"
                  >
                </div>
                <div class="flex items-end">
                  <button 
                    @click="openCustomerModal"
                    class="w-full px-4 py-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors font-medium"
                    title="Customer Lookup"
                  >
                    🔍 Search Customer
                  </button>
                </div>
                <div v-if="customer.name" class="flex items-end">
                  <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700">
                    {{ customer.name }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Print Options Card -->
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-100">
              <div class="flex items-center mb-4">
                <div class="bg-orange-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">⚙️</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Print Options</h3>
                  <p class="text-sm text-gray-600">Configure print settings</p>
                </div>
              </div>
              
              <div class="space-y-4">
                <label class="flex items-center p-4 bg-white rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                  <input 
                    v-model="newEntryMode" 
                    type="radio" 
                    value="print_only" 
                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                  >
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">Print Only New Entry</div>
                    <div class="text-xs text-gray-500">Only print delivery orders that haven't been printed before</div>
                  </div>
                </label>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center text-sm text-gray-600">
              <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
              Ready to print delivery orders
            </div>
            
            <div class="flex gap-3">
              <button 
                @click="refreshForm" 
                class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center font-medium"
              >
                <span class="mr-2">🔄</span>
                Reset Form
              </button>
              <button 
                @click="proceedToPrint" 
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!hasValidRange"
              >
                <span class="mr-2">🖨️</span>
                Generate Print
              </button>
            </div>
          </div>
        </div>

        <!-- Status Messages -->
        <div v-if="!hasValidRange || message" class="mt-6 space-y-4">
          <!-- Validation Message -->
          <div v-if="!hasValidRange" class="bg-white rounded-xl border border-yellow-200 overflow-hidden">
            <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200">
              <div class="flex items-center">
                <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">⚠️</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Validation Required</h3>
                  <p class="text-sm text-gray-600">Please complete the required fields</p>
                </div>
              </div>
            </div>
            <div class="p-6">
              <p class="text-sm text-gray-700">Please specify a valid delivery order range to proceed with printing.</p>
            </div>
          </div>

          <!-- Status Message -->
          <div v-if="message" class="bg-white rounded-xl border border-green-200 overflow-hidden">
            <div class="bg-green-50 px-6 py-4 border-b border-green-200">
              <div class="flex items-center">
                <div class="bg-green-500 p-2 rounded-lg mr-3">
                  <span class="text-white text-lg">✅</span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Status Update</h3>
                  <p class="text-sm text-gray-600">Operation completed</p>
                </div>
              </div>
            </div>
            <div class="p-6">
              <p class="text-sm text-gray-700">{{ message }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Account Modal -->
    <CustomerAccountModal
      :show="showCustomerModal"
      @close="closeCustomerModal"
      @select="handleCustomerSelect"
    />

    <!-- Delivery Order Table Modal (From DO) -->
    <DeliveryOrderTableModal
      :open="showDOModal"
      :customerCode="customer.code"
      :customerName="customer.name"
      :periodMonth="currentPeriod.month.toString()"
      :periodYear="currentPeriod.year.toString()"
      @close="closeDOModal"
      @select="handleDOSelect"
    />

    <!-- Delivery Order Table Modal (To DO) -->
    <DeliveryOrderTableModal
      :open="showToDOModal"
      :customerCode="customer.code"
      :customerName="customer.name"
      :periodMonth="currentPeriod.month.toString()"
      :periodYear="currentPeriod.year.toString()"
      @close="closeToDOModal"
      @select="handleToDOSelect"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue'
import DeliveryOrderTableModal from '@/Components/DeliveryOrderTableModal.vue'

const currentPeriod = reactive({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
})

const doRange = reactive({
  fromMonth: '',
  fromYear: '',
  fromNumber: '',
  toMonth: '',
  toYear: '',
  toNumber: ''
})

const customer = reactive({
  code: '',
  name: ''
})

const newEntryMode = ref('print_only')
const message = ref('')

// Modal states
const showCustomerModal = ref(false)
const showDOModal = ref(false)
const showToDOModal = ref(false)

// Computed properties
const hasValidRange = computed(() => {
  return (doRange.fromMonth && doRange.fromYear && doRange.fromNumber) ||
         (doRange.toMonth && doRange.toYear && doRange.toNumber)
})

// Functions
const refreshForm = () => {
  Object.assign(doRange, {
    fromMonth: '',
    fromYear: '',
    fromNumber: '',
    toMonth: '',
    toYear: '',
    toNumber: ''
  })
  
  Object.assign(customer, {
    code: '',
    name: ''
  })
  
  newEntryMode.value = 'print_only'
  message.value = 'Form refreshed successfully'
  
  setTimeout(() => {
    message.value = ''
  }, 3000)
}

const proceedToPrint = () => {
  if (!hasValidRange.value) {
    message.value = 'Please specify a valid delivery order range'
    return
  }
  
  const fromDO = doRange.fromMonth && doRange.fromYear && doRange.fromNumber 
    ? `${doRange.fromMonth.toString().padStart(2, '0')}-${doRange.fromYear}-${doRange.fromNumber.toString().padStart(5, '0')}`
    : 'Not specified'
    
  const toDO = doRange.toMonth && doRange.toYear && doRange.toNumber 
    ? `${doRange.toMonth.toString().padStart(2, '0')}-${doRange.toYear}-${doRange.toNumber.toString().padStart(5, '0')}`
    : 'Not specified'
  
  message.value = `Print range: ${fromDO} to ${toDO}${customer.code ? ` for customer ${customer.code}` : ''}`
}

// Modal functions
const openCustomerModal = () => {
  showCustomerModal.value = true
}

const closeCustomerModal = () => {
  showCustomerModal.value = false
}

const handleCustomerSelect = (selectedCustomer) => {
  if (selectedCustomer) {
    customer.code = selectedCustomer.customer_code
    customer.name = selectedCustomer.customer_name
    message.value = `Customer selected: ${selectedCustomer.customer_code} - ${selectedCustomer.customer_name}`
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
  closeCustomerModal()
}

// DO Modal functions
const openDOModal = () => {
  showDOModal.value = true
}

const closeDOModal = () => {
  showDOModal.value = false
}

const handleDOSelect = (selectedDOs) => {
  if (selectedDOs && selectedDOs.length > 0) {
    const firstDO = selectedDOs[0]
    
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = firstDO.do_number || firstDO.doNumber
    if (doNumber) {
      const parts = doNumber.split('-')
      if (parts.length === 3) {
        doRange.fromMonth = parts[0]
        doRange.fromYear = parts[1]
        doRange.fromNumber = parts[2]
        
        message.value = `DO selected: ${doNumber}`
        
        setTimeout(() => {
          message.value = ''
        }, 3000)
      }
    }
  }
  closeDOModal()
}

// To DO Modal functions
const openToDOModal = () => {
  showToDOModal.value = true
}

const closeToDOModal = () => {
  showToDOModal.value = false
}

const handleToDOSelect = (selectedDOs) => {
  if (selectedDOs && selectedDOs.length > 0) {
    const firstDO = selectedDOs[0]
    
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = firstDO.do_number || firstDO.doNumber
    if (doNumber) {
      const parts = doNumber.split('-')
      if (parts.length === 3) {
        doRange.toMonth = parts[0]
        doRange.toYear = parts[1]
        doRange.toNumber = parts[2]
        
        message.value = `To DO selected: ${doNumber}`
        
        setTimeout(() => {
          message.value = ''
        }, 3000)
      }
    }
  }
  closeToDOModal()
}
</script>

<style scoped>
/* Custom styles if needed */
</style>