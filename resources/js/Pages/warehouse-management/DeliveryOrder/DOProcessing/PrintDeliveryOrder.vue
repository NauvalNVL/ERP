<template>
  <AppLayout header="Print Delivery Order">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50" v-page-transition>
      <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
            <i class="fa-solid fa-print text-white text-2xl"></i>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Print Delivery Order
          </h1>
          <p class="text-gray-600 text-lg">Generate and print delivery order reports with modern interface</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20 animate-fade-in-up">
          <!-- Card Header -->
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-print text-white text-lg"></i>
                </div>
                <div>
                  <h2 class="text-xl font-bold text-white">Print Configuration</h2>
                  <p class="text-blue-100 text-sm">Configure your delivery order printing parameters</p>
                </div>
              </div>
              <button
                @click="refreshForm"
                class="modern-btn-secondary"
              >
                <i class="fa-solid fa-rotate-left mr-2"></i>
                Reset Form
              </button>
            </div>
          </div>

          <!-- Card Content -->
          <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Left Column -->
              <div class="space-y-6">
                <!-- Current Period Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-calendar-days text-blue-500"></i>
                    <h3 class="modern-section-title">Current Period</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="flex items-center gap-3">
                      <div class="flex-1">
                        <label class="modern-label">Month</label>
                        <input 
                          v-model.number="currentPeriod.month" 
                          type="number" 
                          min="1" 
                          max="12" 
                          class="modern-input text-center"
                          placeholder="MM"
                        />
                      </div>
                      <div class="flex-1">
                        <label class="modern-label">Year</label>
                        <input 
                          v-model.number="currentPeriod.year" 
                          type="number" 
                          min="2000" 
                          max="2099" 
                          class="modern-input text-center"
                          placeholder="YYYY"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- DO Range Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-list-ol text-green-500"></i>
                    <h3 class="modern-section-title">Delivery Order Range</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-4">
                      <!-- From Range -->
                      <div>
                        <label class="modern-label">From D/Order#</label>
                        <div class="flex items-center gap-2">
                          <input v-model.number="doRange.fromMonth" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                          <input v-model.number="doRange.fromYear" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                          <span class="text-gray-400">/</span>
                          <input v-model="doRange.fromNumber" type="text" class="modern-input w-24" placeholder="Seq" />
                          <button 
                            class="modern-icon-btn" 
                            title="Lookup"
                            @click="openDOModal"
                          >
                            <i class="fa-solid fa-magnifying-glass"></i>
                          </button>
                        </div>
                      </div>
                      
                      <!-- To Range -->
                      <div>
                        <label class="modern-label">To D/Order#</label>
                        <div class="flex items-center gap-2">
                          <input v-model.number="doRange.toMonth" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                          <input v-model.number="doRange.toYear" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                          <span class="text-gray-400">/</span>
                          <input v-model="doRange.toNumber" type="text" class="modern-input w-24" placeholder="Seq" />
                          <button 
                            class="modern-icon-btn" 
                            title="Lookup"
                            @click="openToDOModal"
                          >
                            <i class="fa-solid fa-magnifying-glass"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Quick Print Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-bolt text-yellow-500"></i>
                    <h3 class="modern-section-title">Quick Print</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="flex gap-2">
                      <input 
                        v-model="quickDO" 
                        class="modern-input flex-1" 
                        placeholder="Enter DO Number (e.g. DO20250001)" 
                      />
                      <button class="modern-btn-primary" @click="quickPrint">
                        <i class="fa-solid fa-bolt mr-2"></i>Quick Print
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column -->
              <div class="space-y-6">
                <!-- Customer Selection Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-user text-purple-500"></i>
                    <h3 class="modern-section-title">Customer Filter</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-3">
                      <div>
                        <label class="modern-label">Customer Code</label>
                        <div class="flex gap-2">
                          <input 
                            v-model="customer.code" 
                            class="modern-input flex-1" 
                            placeholder="Enter customer code"
                          />
                          <button 
                            class="modern-icon-btn" 
                            title="Customer Lookup"
                            @click="openCustomerModal"
                          >
                            <i class="fa-solid fa-magnifying-glass"></i>
                          </button>
                        </div>
                      </div>
                      <div v-if="customer.name">
                        <label class="modern-label">Customer Name</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700">
                          {{ customer.name }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Print Settings Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-cog text-orange-500"></i>
                    <h3 class="modern-section-title">Print Settings</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-4">
                      <div>
                        <label class="modern-label">Number of Copies</label>
                        <select v-model.number="printCopies" class="modern-select">
                          <option v-for="n in 9" :key="n" :value="n">{{ n }} {{ n === 1 ? 'Copy' : 'Copies' }}</option>
                        </select>
                      </div>
                      <div>
                        <label class="modern-checkbox">
                          <input type="checkbox" v-model="newEntryMode" />
                          <span class="checkmark"></span>
                          <span class="label-text">Print Only New Entry</span>
                          <span class="status-badge outstanding">New</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center">
              <button class="modern-btn-confirm" @click="proceedToPrint" :disabled="!hasValidRange">
                <i class="fa-solid fa-print mr-2"></i>
                Proceed to Print
              </button>
            </div>
          </div>
        </div>

        <!-- Status Messages -->
        <div v-if="message" class="mt-6">
          <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-check text-white"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">Status Update</h3>
                  <p class="text-green-100 text-sm">Operation completed successfully</p>
                </div>
              </div>
            </div>
            <div class="p-6">
              <p class="text-gray-700">{{ message }}</p>
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

const newEntryMode = ref(true)
const message = ref('')
const quickDO = ref('')
const printCopies = ref(1)

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

const handleDOSelect = (selectedDO) => {
  console.log('📥 Received DO selection:', selectedDO)
  
  if (selectedDO) {
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = selectedDO.do_number || selectedDO.doNumber
    console.log('🔍 Parsing DO number:', doNumber)
    
    if (doNumber) {
      const parts = doNumber.split('-')
      console.log('📊 DO parts:', parts)
      
      if (parts.length === 3) {
        // Populate From DO fields
        doRange.fromMonth = parseInt(parts[0])
        doRange.fromYear = parseInt(parts[1])
        doRange.fromNumber = parts[2]
        
        console.log('✅ From DO populated:', {
          month: doRange.fromMonth,
          year: doRange.fromYear,
          number: doRange.fromNumber
        })
        
        message.value = `From DO selected: ${doNumber}`
        
        setTimeout(() => {
          message.value = ''
        }, 3000)
      } else {
        console.error('❌ Invalid DO number format:', doNumber)
        message.value = `Invalid DO format: ${doNumber}`
      }
    } else {
      console.error('❌ No DO number found in selection')
      message.value = 'No DO number found in selection'
    }
  } else {
    console.error('❌ No DO selected')
    message.value = 'No delivery order selected'
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

const handleToDOSelect = (selectedDO) => {
  console.log('📥 Received To DO selection:', selectedDO)
  
  if (selectedDO) {
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = selectedDO.do_number || selectedDO.doNumber
    console.log('🔍 Parsing To DO number:', doNumber)
    
    if (doNumber) {
      const parts = doNumber.split('-')
      console.log('📊 To DO parts:', parts)
      
      if (parts.length === 3) {
        // Populate To DO fields
        doRange.toMonth = parseInt(parts[0])
        doRange.toYear = parseInt(parts[1])
        doRange.toNumber = parts[2]
        
        console.log('✅ To DO populated:', {
          month: doRange.toMonth,
          year: doRange.toYear,
          number: doRange.toNumber
        })
        
        message.value = `To DO selected: ${doNumber}`
        
        setTimeout(() => {
          message.value = ''
        }, 3000)
      } else {
        console.error('❌ Invalid To DO number format:', doNumber)
        message.value = `Invalid To DO format: ${doNumber}`
      }
    } else {
      console.error('❌ No To DO number found in selection')
      message.value = 'No To DO number found in selection'
    }
  } else {
    console.error('❌ No To DO selected')
    message.value = 'No To delivery order selected'
  }
  
  closeToDOModal()
}

// Quick Print function
const quickPrint = () => {
  if (quickDO.value) {
    message.value = `Quick print initiated for: ${quickDO.value}`
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  } else {
    message.value = 'Please enter a DO number for quick print'
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
}
</script>

<style scoped>
/* Modern Input Styles */
.modern-input {
  @apply w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition-all duration-200 
         focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
         hover:border-gray-300 bg-white/80 backdrop-blur-sm;
}

.modern-select {
  @apply w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition-all duration-200 
         focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
         hover:border-gray-300 bg-white/80 backdrop-blur-sm cursor-pointer;
}

.modern-label {
  @apply block text-sm font-semibold text-gray-700 mb-2;
}

/* Modern Section Styles */
.modern-section {
  @apply bg-white/60 backdrop-blur-sm rounded-xl border border-white/40 shadow-lg overflow-hidden;
}

.modern-section-header {
  @apply flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100;
}

.modern-section-title {
  @apply text-lg font-bold text-gray-800;
}

.modern-section-content {
  @apply p-4;
}

/* Modern Button Styles */
.modern-btn-primary {
  @apply inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white 
         font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 
         transition-all duration-200 hover:from-blue-600 hover:to-blue-700;
}

.modern-btn-secondary {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white 
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 
         transition-all duration-200 hover:from-gray-600 hover:to-gray-700;
}

.modern-btn-confirm {
  @apply inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white 
         font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105 
         transition-all duration-200 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none;
}

.modern-icon-btn {
  @apply inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 
         text-gray-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 
         transition-all duration-200 shadow-sm hover:shadow-md;
}

/* Modern Checkbox Styles */
.modern-checkbox {
  @apply flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 
         cursor-pointer transition-all duration-200 hover:shadow-sm;
}

.modern-checkbox input[type="checkbox"] {
  @apply sr-only;
}

.checkmark {
  @apply w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center 
         transition-all duration-200;
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark {
  @apply bg-blue-500 border-blue-500;
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark::after {
  content: '✓';
  @apply text-white text-xs font-bold;
}

.label-text {
  @apply flex-1 text-sm font-medium text-gray-700;
}

/* Status Badge Styles */
.status-badge {
  @apply px-2 py-1 rounded-full text-xs font-semibold;
}

.status-badge.outstanding {
  @apply bg-green-100 text-green-800;
}

.status-badge.partial {
  @apply bg-yellow-100 text-yellow-800;
}

.status-badge.closed {
  @apply bg-gray-100 text-gray-800;
}

.status-badge.completed {
  @apply bg-blue-100 text-blue-800;
}

.status-badge.cancelled {
  @apply bg-red-100 text-red-800;
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
  animation: fade-in-up 0.5s ease-out;
}
</style>