<template>
  <AppLayout header="Prepare Invoice by D/Order (Current Period)">
    <div class="space-y-6" v-page-transition>
      <!-- Simple heading -->
      <div class="px-2 sm:px-0">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-base sm:text-lg font-semibold text-gray-800">Prepare Invoice by D/Order</h2>
            <p class="text-xs text-gray-500">Current period processing and customer-scoped filtering</p>
          </div>
          <div class="hidden sm:block text-xs text-gray-400">Today {{ new Date().toLocaleDateString('en-GB') }}</div>
        </div>
      </div>

      <!-- Modern form card -->
      <div class="mx-auto w-full max-w-5xl">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-black/5">
          <div class="px-6 py-5 border-b flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
              <i class="fa fa-receipt text-blue-600"></i>
              <span class="font-semibold text-gray-800">Current Settings</span>
            </div>
            <div class="text-xs text-gray-500">All fields are required</div>
          </div>

          <div class="p-6">
            <!-- Modern Attractive Period Selection -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 pb-8 border-b border-gradient-to-r from-blue-100 to-purple-100">
              
              <!-- Current Period Card -->
              <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                <div class="relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:border-blue-300">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                      <i class="fa fa-calendar-alt text-white text-sm"></i>
                    </div>
                    <div>
                      <h3 class="text-sm font-bold text-gray-800">Current Period</h3>
                      <p class="text-xs text-gray-500">Active posting period</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-center space-x-2 bg-white/70 backdrop-blur rounded-xl border border-blue-100 p-4 shadow-inner">
                    <div class="relative">
                      <input 
                        v-model="currentMonth" 
                        type="text" 
                        readonly
                        class="w-12 h-12 text-center text-lg font-bold bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg cursor-not-allowed"
                        placeholder="MM" 
                      />
                      <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 text-xs text-blue-400 font-medium">Month</div>
                      <div v-if="currentMonth && currentPeriodValid" class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fa fa-check text-white text-xs"></i>
                      </div>
                    </div>
                    
                    <div :class="[
                      'w-8 h-1 rounded-full',
                      currentPeriodValid ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-blue-400 to-indigo-500'
                    ]"></div>
                    
                    <div class="relative">
                      <input 
                        v-model="currentYear" 
                        type="text" 
                        readonly
                        class="w-16 h-12 text-center text-lg font-bold bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg cursor-not-allowed"
                        placeholder="YYYY" 
                      />
                      <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 text-xs text-blue-400 font-medium">Year</div>
                      <div v-if="currentYear && currentPeriodValid" class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fa fa-check text-white text-xs"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Update Period Card -->
              <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                <div class="relative bg-gradient-to-br from-purple-50 via-white to-pink-50 border-2 border-purple-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:border-purple-300">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-md">
                      <i class="fa fa-sync-alt text-white text-sm"></i>
                    </div>
                    <div>
                      <h3 class="text-sm font-bold text-gray-800">Update Period</h3>
                      <p class="text-xs text-gray-500">Invoice processing period</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-center space-x-2 bg-white/70 backdrop-blur rounded-xl border border-purple-100 p-4 shadow-inner">
                    <div class="relative">
                      <input 
                        v-model="updateMonth" 
                        type="text" 
                        readonly
                        class="w-12 h-12 text-center text-lg font-bold bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg cursor-not-allowed"
                        placeholder="MM" 
                      />
                      <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 text-xs text-purple-400 font-medium">Month</div>
                      <div v-if="updateMonth && updatePeriodValid" class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fa fa-check text-white text-xs"></i>
                      </div>
                    </div>
                    
                    <div :class="[
                      'w-8 h-1 rounded-full',
                      updatePeriodValid ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-purple-400 to-pink-500'
                    ]"></div>
                    
                    <div class="relative">
                      <input 
                        v-model="updateYear" 
                        type="text" 
                        readonly
                        class="w-16 h-12 text-center text-lg font-bold bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg cursor-not-allowed"
                        placeholder="YYYY" 
                      />
                      <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 text-xs text-purple-400 font-medium">Year</div>
                      <div v-if="updateYear && updatePeriodValid" class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fa fa-check text-white text-xs"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Customer Selection Card -->
              <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                <div class="relative bg-gradient-to-br from-emerald-50 via-white to-teal-50 border-2 border-emerald-200 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:border-emerald-300">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                      <i class="fa fa-building text-white text-sm"></i>
                    </div>
                    <div>
                      <h3 class="text-sm font-bold text-gray-800">Customer</h3>
                      <p class="text-xs text-gray-500">{{ hasCustomer ? 'Customer selected' : 'Select target customer' }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-center bg-white/70 backdrop-blur rounded-xl border border-emerald-100 p-4 shadow-inner">
                    <div class="relative w-full max-w-none">
                      <div class="flex items-stretch bg-white rounded-lg border-2 border-emerald-200 shadow-sm h-12">
                        <!-- Icon Section -->
                        <div class="flex items-center justify-center w-12 bg-gradient-to-r from-emerald-100 to-teal-100 border-r border-emerald-200">
                          <i :class="[
                            'text-lg transition-colors duration-200',
                            hasCustomer ? 'fa fa-check-circle text-green-600' : 'fa fa-user text-emerald-600'
                          ]"></i>
                        </div>
                        
                        <!-- Input Field -->
                        <input 
                          v-model="customerCode" 
                          type="text" 
                          :class="[
                            'flex-grow min-w-0 px-4 text-sm font-medium outline-none border-0 bg-white transition-colors duration-200',
                            hasCustomer ? 'text-green-700' : 'text-gray-900'
                          ]"
                          placeholder="Type customer code (e.g. 000004)"
                          @input="onCustomerCodeInput"
                        />
                        
                        <!-- Search Button -->
                        <button 
                          @click="showCustomerModal = true" 
                          type="button"
                          class="flex items-center justify-center w-12 bg-gradient-to-r from-emerald-500 to-teal-600 text-white hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 border-l border-emerald-400"
                        >
                          <i class="fa fa-search text-sm"></i>
                        </button>
                      </div>
                      
                      <!-- Success indicator -->
                      <div v-if="hasCustomer" class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center shadow-md">
                        <i class="fa fa-check text-white text-xs"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Additional Fields Grid -->
            <div class="grid grid-cols-12 gap-5">

            <!-- Customer name (readonly) -->
            <div v-if="hasCustomer" class="col-span-12 lg:col-span-8">
              <label class="block text-xs font-medium text-gray-700 mb-1">Customer Name</label>
              <input v-model="customerName" type="text" readonly class="w-full px-3 py-2 text-sm rounded-md bg-gray-50 ring-1 ring-inset ring-gray-200 cursor-not-allowed" />
            </div>

            <!-- Currency (readonly) -->
            <div v-if="hasCustomer" class="col-span-12 md:col-span-4">
              <label class="block text-xs font-medium text-gray-700 mb-1">Currency</label>
              <input v-model="currency" type="text" readonly class="w-full px-3 py-2 text-sm rounded-md bg-gray-50 ring-1 ring-inset ring-gray-200 cursor-not-allowed" />
            </div>

            <!-- Tax Index No. -->
            <div v-if="hasCustomer" class="col-span-12 md:col-span-4">
              <label class="block text-xs font-medium text-gray-700 mb-1">
                Tax Index No. <span class="text-red-600">*</span>
              </label>
              <div class="flex rounded-md shadow-sm ring-1 ring-inset overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 transition-all duration-200" :class="taxIndexNo ? 'ring-gray-300' : 'ring-red-300 bg-red-50'">
                <input v-model="taxIndexNo" type="text" class="flex-1 px-3 py-2 text-sm outline-none border-0 bg-transparent" placeholder="Select tax from table..." />
                <button @click="taxModalOpen = true" class="px-3 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700" title="Browse Tax">
                  <i class="fa fa-search"></i>
                </button>
              </div>
              <!-- Fixed height container to prevent layout jump -->
              <div class="h-5 mt-1">
                <p v-show="!taxIndexNo" class="text-xs text-red-600 transition-opacity duration-200" :class="!taxIndexNo ? 'opacity-100' : 'opacity-0'">
                  <i class="fa fa-exclamation-circle"></i> Required: Please select a tax
                </p>
              </div>
            </div>

            <!-- Invoice Date with day of week -->
            <div v-if="hasCustomer" class="col-span-12 md:col-span-4">
              <label class="block text-xs font-medium text-gray-700 mb-1">Invoice Date</label>
              <div class="flex items-center gap-2">
                <input v-model="invoiceDate" type="date" class="px-3 py-2 text-sm rounded-md ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-500" />
                <span class="text-xs text-gray-500 min-w-[40px]">{{ invoiceDay }}</span>
              </div>
            </div>

            <!-- 2nd Reference -->
            <div v-if="hasCustomer" class="col-span-12 md:col-span-4">
              <label class="block text-xs font-medium text-gray-700 mb-1">2nd Reference#</label>
              <input v-model="secondRef" type="text" class="w-full px-3 py-2 text-sm rounded-md ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Remark -->
            <div v-if="hasCustomer" class="col-span-12">
              <label class="block text-xs font-medium text-gray-700 mb-1">Remark</label>
              <input v-model="remark" type="text" class="w-full px-3 py-2 text-sm rounded-md ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Actions -->
            <div class="col-span-12 flex flex-wrap items-center gap-3 pt-2">
              <button
                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!hasCustomer"
                @click="refreshPeriodData"
              >
                <i class="fa fa-sync"></i>
                Refresh Period Data
              </button>
              <button
                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!hasCustomer || preparing"
                @click="openFlow"
                @keyup.enter.stop.prevent="openFlow"
              >
                <i v-if="!preparing" class="fa fa-arrow-right"></i>
                <i v-else class="fa fa-spinner fa-spin"></i>
                {{ preparing ? 'Preparing...' : 'Continue to Prepare' }}
              </button>
              <div class="flex-1"></div>
              <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md border text-sm hover:bg-gray-50" @click="showCancelModal = true">
                <i class="fa fa-ban text-red-600"></i>
                Cancel Active Invoice
              </button>
              <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md border text-sm hover:bg-gray-50" @click="showLogModal = true">
                <i class="fa fa-print text-emerald-600"></i>
                View & Print Invoice Log
              </button>
            </div>
            <div v-if="hasCustomer" class="col-span-12 flex items-center justify-end gap-3 pt-4 border-t">
              <button @click="refreshPeriodData" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fa fa-sync-alt"></i>
                Refresh
              </button>
              <button
                @click="openFlow"
                :disabled="preparing"
                class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
              >
                <i v-if="!preparing" class="fa fa-arrow-right"></i>
                <i v-else class="fa fa-spinner fa-spin"></i>
                {{ preparing ? 'Processing...' : 'Continue to Prepare' }}
              </button>
            </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Customer Account Modal -->
      <CustomerAccountModal
        :show="showCustomerModal"
        @close="showCustomerModal = false"
        @select="selectCustomer"
      />

      <!-- Sales Tax Index Modal -->
      <SalesTaxIndexModal
        :open="taxModalOpen"
        @close="taxModalOpen = false"
        @select="selectTaxIndex"
      />

      <!-- Modal 1: Check Sales Tax Screen (CPS-style validation) -->
      <CheckSalesTaxModal
        :open="checkTaxModalOpen"
        :customerCode="customerCode"
        :preselectedTaxCode="taxIndexNo"
        @close="checkTaxModalOpen = false"
        @confirm="onTaxConfirmed"
      />

      <!-- Modal 2: Delivery Order Screen (Modern simple modal) -->
      <DeliveryOrderScreenModal
        :open="doListModalOpen"
        :customerCode="customerCode"
        :customerName="customerName"
        :taxIndexNo="taxIndexNo"
        :invoiceDate="invoiceDate"
        :selectedDeliveryOrders="selectedDOs"
        @close="doListModalOpen = false"
        @select="onDOsSelected"
        @browse="openDetailedDOView"
        @viewItems="openSalesOrderItems"
      />

      <!-- Modal 2b: Delivery Order Selection (CPS-style detailed table - opened from Zoom) -->
      <DeliveryOrderSelectionModal
        :open="doSelectionModalOpen"
        :customerCode="customerCode"
        :customerName="customerName"
        :periodMonth="currentMonth"
        :periodYear="currentYear"
        @close="handleDetailedDOClose"
        @select="onDOsSelectedFromTable"
      />

      <!-- Modal 3: Final Tax Calculation -->
      <FinalTaxCalculationModal
        :open="finalTaxModalOpen"
        :totalAmount="totalAmount"
        :taxCode="taxIndexNo"
        :taxOptions="taxOptions"
        @close="finalTaxModalOpen = false"
        @confirm="onFinalTaxConfirmed"
      />

      <!-- Modal 4: Sales Order Items Screen -->
      <SalesOrderItemsModal
        :open="salesOrderItemsModalOpen"
        :doNumber="selectedOrderForItems?.do_number || ''"
        :doDate="selectedOrderForItems?.do_date || ''"
        :soNumber="selectedOrderForItems?.so_number || ''"
        :mcSeq="selectedOrderForItems?.mcs_num || ''"
        :totalAmount="selectedOrderForItems?.amount || 0"
        @close="salesOrderItemsModalOpen = false"
      />

      <!-- Modal 5: Invoice Number Option -->
      <InvoiceNumberOptionModal
        :open="invoiceNumberModalOpen"
        @close="invoiceNumberModalOpen = false"
        @confirm="onInvoiceNumberConfirmed"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed, watch } from 'vue'
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue'
import SalesTaxIndexModal from '@/Components/SalesTaxIndexModal.vue'
import CheckSalesTaxModal from '@/Components/CheckSalesTaxModal.vue'
import DeliveryOrderScreenModal from '@/Components/DeliveryOrderScreenModal.vue'
import DeliveryOrderSelectionModal from '@/Components/DeliveryOrderTableModal.vue'
import FinalTaxCalculationModal from '@/Components/FinalTaxCalculationModal.vue'
import SalesOrderItemsModal from '@/Components/SalesOrderItemsModal.vue'
import InvoiceNumberOptionModal from '@/Components/InvoiceNumberOptionModal.vue'

// Period defaults
const now = new Date()
const currentMonth = ref(String(now.getMonth()+1).padStart(2,'0'))
const currentYear = ref(String(now.getFullYear()))
const updateMonth = ref(String(now.getMonth()+1).padStart(2,'0'))
const updateYear = ref(String(now.getFullYear()))

// Validation status tracking
const currentPeriodValid = computed(() => {
  const month = parseInt(currentMonth.value)
  const year = parseInt(currentYear.value)
  return month >= 1 && month <= 12 && year >= 2000 && year <= new Date().getFullYear() + 5
})

const updatePeriodValid = computed(() => {
  const month = parseInt(updateMonth.value)
  const year = parseInt(updateYear.value)
  return month >= 1 && month <= 12 && year >= 2000 && year <= new Date().getFullYear() + 5
})

// Customer
const customerCode = ref('')
const showCustomerModal = ref(false)
async function selectCustomer(customer){
  customerCode.value = customer?.customer_code || customer?.code || ''
  customerName.value = customer?.customer_name || ''

  // Store the currency from modal selection (this is the correct currency)
  const modalCurrency = customer?.currency || customer?.currency_code || 'IDR'

  // Extract only currency code (remove full names like "INDONESIA")
  currency.value = extractCurrencyCode(modalCurrency)
  
  // Track the selected customer code to detect manual changes later
  lastSelectedCustomerCode.value = customerCode.value

  showCustomerModal.value = false

  // Try to fetch more details from API (optional enhancement)
  if (customerCode.value) {
    try {
      const res = await fetch(`/api/invoices/customer-details?customer_code=${encodeURIComponent(customerCode.value)}`, {
        headers: { 'Accept': 'application/json' }
      })
      if (res.ok) {
        const data = await res.json()
        // Only update if API returns data AND it's different from modal selection
        if (data.customer_name && data.customer_name !== customerName.value) {
          customerName.value = data.customer_name
        }
        // PRESERVE currency from modal selection - only update if API returns valid currency data
        if (data.currency && data.currency !== modalCurrency && data.currency !== null) {
          // Extract currency code and update
          const apiCurrencyCode = extractCurrencyCode(data.currency)
          const modalCurrencyCode = extractCurrencyCode(modalCurrency)
          
          if (apiCurrencyCode !== modalCurrencyCode) {
            currency.value = apiCurrencyCode
            console.log(`Currency updated from API: ${modalCurrencyCode} -> ${apiCurrencyCode}`)
          } else {
            console.log(`Currency codes match: ${modalCurrencyCode}`)
          }
        } else {
          console.log(`Keeping modal currency code: ${extractCurrencyCode(modalCurrency)}`)
        }
        // Update other fields that don't conflict
        if (data.tax_index_no) taxIndexNo.value = data.tax_index_no
      }
    } catch (e) {
      console.error('Failed to fetch customer details from API, using modal data:', e)
      // Already populated from modal data above, so no problem
    }
  }

  console.log(`Customer selected: ${customerName.value}, Currency: ${currency.value}`)
}

// CPS-like extra fields
const customerName = ref('')
const currency = ref('')
const taxIndexNo = ref('')
const taxModalOpen = ref(false)
const invoiceDate = ref(new Date().toISOString().slice(0,10))
const secondRef = ref('')
const remark = ref('')
const invoiceDay = ref(new Date().toLocaleDateString('en-GB', { weekday:'short' }))
const showCancelModal = ref(false)
const showLogModal = ref(false)

// Complete CPS Flow State
const checkTaxModalOpen = ref(false)
const doListModalOpen = ref(false)  // Simple DO list (first screen after tax)
const doSelectionModalOpen = ref(false)  // Detailed DO table (opened from Zoom)
const salesOrderItemsModalOpen = ref(false)  // Sales Order Items screen
const finalTaxModalOpen = ref(false)
const invoiceNumberModalOpen = ref(false)
const preparing = ref(false)

// Flow data
const selectedDOs = ref([])
const totalAmount = ref(0)
const taxOptions = ref([])
const finalTaxData = ref(null)
const invoiceNumberMode = ref('auto')
const manualInvoiceNumber = ref('')
const selectedOrderForItems = ref(null)

// Toggle section visibility depending on selection
const hasCustomer = computed(() => !!(customerCode.value && String(customerCode.value).trim()))

// Keep weekday label in sync when date changes
watch(() => invoiceDate.value, (val) => {
  try {
    invoiceDay.value = new Date(val).toLocaleDateString('en-GB', { weekday:'short' })
  } catch(_) { invoiceDay.value = '' }
})

function selectTaxIndex(row){
  taxIndexNo.value = row?.code || ''
  taxModalOpen.value = false
}

// ============================================================================
// COMPLETE CPS FLOW IMPLEMENTATION
// ============================================================================

/**
 * Step 1: User clicks "Continue to Prepare"
 * Opens Check Sales Tax Screen
 */
function openFlow(){
  if (!hasCustomer.value) return

  // CPS Validation: Tax Index No is MANDATORY
  if (!taxIndexNo.value || taxIndexNo.value.trim() === '') {
    alert('Please select Tax Index No. from Customer Sales Tax or Sales Tax Exemption Table.\n\nClick the search icon (🔍) next to Tax Index No. field to select a tax.')
    return
  }

  // Fetch tax options first
  fetchTaxOptions().then(() => {
    // Show Check Sales Tax Screen (CPS workflow)
    checkTaxModalOpen.value = true
  })
}

/**
 * Fetch tax options from API
 */
async function fetchTaxOptions(){
  try {
    const res = await fetch('/api/invoices/sales-tax-options', {
      headers: { 'Accept': 'application/json' }
    })
    if (res.ok) {
      taxOptions.value = await res.json()
    }
  } catch (e) {
    console.error('Failed to fetch tax options:', e)
  }
}

/**
 * Step 2: User confirms tax in Check Sales Tax Screen
 * Opens Delivery Order List Modal (simple list)
 */
function onTaxConfirmed(selectedTax){
  // Update tax information from confirmed selection
  taxIndexNo.value = selectedTax.code
  checkTaxModalOpen.value = false

  // Open Delivery Order List Modal (CPS workflow - simple list first)
  doListModalOpen.value = true
}

/**
 * Step 2b: User clicks Zoom/Icon in DO List Modal
 * Opens detailed Delivery Order Selection Modal for browsing
 */
function openDetailedDOView(){
  // Keep simple list modal open (CPS behavior)
  // Open detailed DO table modal on top
  doSelectionModalOpen.value = true
}

/**
 * Handle closing the detailed DO view
 * Returns to the simple list modal
 */
function handleDetailedDOClose(){
  doSelectionModalOpen.value = false
  // Simple list modal stays open
}

/**
 * Step 2c: User selects DOs from Delivery Order Table
 * Data is sent back to Delivery Order Screen (simple list)
 */
function onDOsSelectedFromTable(dos){
  console.log('📦 DOs selected from table:', dos)
  
  // Close the detailed table modal
  doSelectionModalOpen.value = false

  // Handle both single DO (object) and multiple DOs (array)
  // DeliveryOrderTableModal now emits single object for single selection
  if (Array.isArray(dos)) {
    selectedDOs.value = dos
  } else {
    // Single selection - convert to array
    selectedDOs.value = [dos]
  }
  
  console.log('✅ Selected DOs updated:', selectedDOs.value)
  console.log('🔓 Delivery Order Screen Modal remains OPEN')

  // Keep the simple list modal open to show selected data
  // User can then click Select button to continue
  // doListModalOpen stays true - screen modal will show the selected DO
}

/**
 * Step 2d: User clicks on DO number in simple list to view items
 * Opens Sales Order Items screen
 */
function openSalesOrderItems(order){
  selectedOrderForItems.value = order
  salesOrderItemsModalOpen.value = true
}

/**
 * Step 3: User clicks Select button in Delivery Order Screen
 * Calculate total and open Final Tax Calculation Modal
 */
async function onDOsSelected(dos){
  // Close the simple list modal
  doListModalOpen.value = false

  if (dos.length === 0) {
    alert('No delivery orders selected')
    return
  }

  // Calculate total amount
  await calculateTotalAmount(dos)

  // Open Final Tax Calculation Modal
  finalTaxModalOpen.value = true
}

/**
 * Calculate total amount from selected DOs
 */
async function calculateTotalAmount(dos){
  try {
    const doNumbers = dos.map(d => d.do_number)
    const res = await fetch('/api/invoices/calculate-total', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({ do_numbers: doNumbers })
    })

    if (res.ok) {
      const data = await res.json()
      totalAmount.value = data.total_amount || 0
    }
  } catch (e) {
    console.error('Failed to calculate total:', e)
    totalAmount.value = 0
  }
}

/**
 * Step 4: User confirms final tax calculation
 * Opens Invoice Number Option Modal
 */
function onFinalTaxConfirmed(taxData){
  // Store final tax data
  finalTaxData.value = taxData
  finalTaxModalOpen.value = false

  // Open Invoice Number Option Modal
  invoiceNumberModalOpen.value = true
}

/**
 * Step 5: User selects invoice number mode
 * Prepare invoices
 */
async function onInvoiceNumberConfirmed(option){
  invoiceNumberMode.value = option.mode
  manualInvoiceNumber.value = option.invoiceNumber || ''
  invoiceNumberModalOpen.value = false

  // Prepare invoices
  await prepareInvoices()
}

/**
 * Final Step: Prepare invoices
 */
async function prepareInvoices(){
  if (preparing.value) return

  preparing.value = true

  try {
    // Get DO numbers
    const doNumbers = selectedDOs.value.map(d => d.do_number)

    // Prepare invoices
    const prepareRes = await fetch('/api/invoices/prepare', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({
        do_numbers: doNumbers,
        customer_code: customerCode.value,
        tax_index_no: finalTaxData.value?.taxCode || taxIndexNo.value,
        invoice_date: invoiceDate.value,
        second_ref: secondRef.value,
        remark: remark.value,
        invoice_number_mode: invoiceNumberMode.value,
        manual_invoice_number: manualInvoiceNumber.value,
        year: updateYear.value,
        month: updateMonth.value,
      })
    })

    const result = await prepareRes.json()

    if (result.success) {
      const invoiceNumbers = result.data.map(d => d.invoice_number).join('\n')
      alert(`Success! ${result.data.length} invoice(s) prepared:\n${invoiceNumbers}`)

      // Reset form
      resetForm()
    } else {
      alert('Failed to prepare invoices: ' + (result.error || 'Unknown error'))
    }
  } catch (e) {
    alert('Error: ' + e.message)
  } finally {
    preparing.value = false
  }
}

/**
 * Reset form after successful invoice preparation
 */
function resetForm(){
  customerCode.value = ''
  customerName.value = ''
  currency.value = ''
  taxIndexNo.value = ''
  secondRef.value = ''
  remark.value = ''
  selectedDOs.value = []
  totalAmount.value = 0
  finalTaxData.value = null
  invoiceNumberMode.value = 'auto'
  manualInvoiceNumber.value = ''
  // Reset modal states
  checkTaxModalOpen.value = false
  doListModalOpen.value = false
  doSelectionModalOpen.value = false
  salesOrderItemsModalOpen.value = false
  finalTaxModalOpen.value = false
  invoiceNumberModalOpen.value = false
}

function refreshPeriodData(){
  if (!hasCustomer.value) return
  alert('Period data will be refreshed when you continue to prepare')
}

// Handle customer code input changes
async function onCustomerCodeInput() {
  // Clear previous timeout
  if (customerLookupTimeout) {
    clearTimeout(customerLookupTimeout)
  }
  
  // Clear customer name and currency if code is changed manually
  if (customerCode.value !== lastSelectedCustomerCode.value) {
    customerName.value = ''
    currency.value = ''
  }
  
  // Auto-lookup customer details when code is entered (debounced)
  const code = customerCode.value?.trim()
  if (code && code.length >= 4) { // Check for codes with 4 or more characters (like "000004" or "000211-08")
    customerLookupTimeout = setTimeout(async () => {
      try {
        // Show loading state
        customerName.value = 'Loading...'
        
        console.log(`Attempting customer lookup for code: ${code}`)
        
        // Use the proper InvoiceController endpoint
        const res = await fetch(`/api/invoices/customer-details?customer_code=${encodeURIComponent(code)}`, {
          headers: { 
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          }
        })
        
        console.log(`API response status: ${res.status}`)
        
        if (res.ok) {
          const data = await res.json()
          console.log('API Response data:', data)
          
          // The InvoiceController returns a single customer object with debug info
          if (data) {
            console.log('Debug info from backend:', data.debug_info)
            
            if (data.customer_name && data.customer_name.trim() !== '') {
              customerName.value = data.customer_name
              // Extract only currency code (3 letters)
              currency.value = extractCurrencyCode(data.currency)
              lastSelectedCustomerCode.value = code
              
              console.log(`Auto-lookup successful: ${data.customer_name}, Currency: ${currency.value}`)
              console.log(`Found in table: ${data.debug_info?.found_in_table}`)
            } else {
              console.log(`Customer with code "${code}" not found or has empty name`)
              if (data.debug_info) {
                console.log(`Searched tables: ${data.debug_info.searched_tables?.join(', ')}`)
                console.log(`Found in table: ${data.debug_info.found_in_table || 'none'}`)
              }
              if (data.error) {
                console.error(`Backend error: ${data.error}`)
                console.error(`Error details: ${data.debug_info?.error_message}`)
              }
              customerName.value = ''
              currency.value = ''
            }
          } else {
            console.log('No data returned from API')
            customerName.value = ''
            currency.value = ''
          }
        } else {
          console.log(`API error: ${res.status} ${res.statusText}`)
          const errorText = await res.text()
          console.log('Error response:', errorText)
          customerName.value = ''
          currency.value = ''
        }
      } catch (error) {
        console.error('Customer lookup failed:', error)
        customerName.value = ''
        currency.value = ''
      }
    }, 500) // Wait 500ms after user stops typing
  } else if (code.length === 0) {
    // Clear fields when input is empty
    customerName.value = ''
    currency.value = ''
  }
}

// Track last selected customer code to detect manual changes
const lastSelectedCustomerCode = ref('')

// Debounce timeout for customer lookup
let customerLookupTimeout = null

/**
 * Extract currency code only (3-letter uppercase)
 * Handles cases where database stores "IDR" or "IDR - INDONESIA" or "INDONESIA"
 */
function extractCurrencyCode(currencyString) {
  if (!currencyString) return 'IDR' // Default
  
  const str = String(currencyString).trim().toUpperCase()
  
  // If it's already 3 letters, return it
  if (str.length === 3 && /^[A-Z]{3}$/.test(str)) {
    return str
  }
  
  // Extract first 3-letter word (currency code)
  const match = str.match(/\b([A-Z]{3})\b/)
  if (match) {
    return match[1]
  }
  
  // Map common currency names to codes
  const currencyMap = {
    'INDONESIA': 'IDR',
    'RUPIAH': 'IDR',
    'DOLLAR': 'USD',
    'SINGAPORE': 'SGD',
    'EURO': 'EUR',
    'YEN': 'JPY',
    'YUAN': 'CNY',
    'RINGGIT': 'MYR'
  }
  
  // Check if string contains any mapped currency name
  for (const [name, code] of Object.entries(currencyMap)) {
    if (str.includes(name)) {
      return code
    }
  }
  
  // If nothing matches, take first 3 characters
  return str.substring(0, 3) || 'IDR'
}

// Input validation functions for period fields
function validateMonth(event, type) {
  const value = event.target.value.replace(/\D/g, '') // Only numbers
  const numValue = parseInt(value)
  
  if (value.length <= 2 && (!numValue || (numValue >= 1 && numValue <= 12))) {
    const formattedValue = value.length === 1 && numValue > 0 ? value : (value.length === 2 ? value.padStart(2, '0') : value)
    if (type === 'current') {
      currentMonth.value = formattedValue
    } else {
      updateMonth.value = formattedValue
    }
  } else if (value === '') {
    // Allow empty value
    if (type === 'current') {
      currentMonth.value = ''
    } else {
      updateMonth.value = ''
    }
  }
}

function validateYear(event, type) {
  const value = event.target.value.replace(/\D/g, '') // Only numbers
  const thisYear = new Date().getFullYear()
  
  if (value.length <= 4 && (!value || (parseInt(value) >= 2000 && parseInt(value) <= thisYear + 5))) {
    if (type === 'current') {
      currentYear.value = value
    } else {
      updateYear.value = value
    }
  }
}
</script>
