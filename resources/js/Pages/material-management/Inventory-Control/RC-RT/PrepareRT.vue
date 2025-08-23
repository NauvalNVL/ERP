<template>
  <AppLayout :header="header">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold flex items-center">
              <i class="fas fa-minus-circle mr-3"></i>
              Prepare Return (RT)
            </h1>
            <p class="text-red-100 mt-2 text-lg">Create new return transactions for inventory management</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="saveTransaction"
              :disabled="!isFormValid || isSaving"
              class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-save mr-2" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isSaving ? 'Saving...' : 'Save RT' }}
            </button>
            <button
              @click="clearForm"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-eraser mr-2"></i>
              Clear Form
            </button>
            <button
              @click="printTransaction"
              :disabled="!form.transaction_number"
              class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-print mr-2"></i>
              Print
            </button>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white rounded-lg shadow-lg p-4 mb-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt text-red-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">Current Period: {{ currentPeriod }}</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-user text-red-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">User: {{ currentUser }}</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-clock text-red-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">{{ currentTime }}</span>
            </div>
          </div>
          <div class="flex items-center">
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
              <i class="fas fa-check-circle mr-1"></i>
              Ready to Process
            </div>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Transaction Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Transaction Information -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-info-circle text-blue-500 mr-2"></i>
              Transaction Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Number</label>
                <input
                  type="text"
                  v-model="form.transaction_number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                  readonly
                  placeholder="Auto-generated"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                <input
                  type="date"
                  v-model="form.transaction_date"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  required
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                <input
                  type="text"
                  value="RT"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-red-50 text-red-700 font-medium"
                  readonly
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">PO Number</label>
                <input
                  type="text"
                  v-model="form.po_number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter PO number"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                <input
                  type="text"
                  v-model="form.reference_number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter reference number"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Return Note</label>
                <input
                  type="text"
                  v-model="form.return_note"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter return note"
                />
              </div>
            </div>
          </div>

          <!-- Supplier Information -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-truck text-green-500 mr-2"></i>
              Supplier Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Code *</label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="form.supplier_code"
                    @click="showSupplierModal = true"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                    placeholder="Click to select supplier"
                    readonly
                    required
                  />
                  <button
                    @click="showSupplierModal = true"
                    class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name</label>
                <input
                  type="text"
                  v-model="form.supplier_name"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                <input
                  type="text"
                  v-model="form.contact_person"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter contact person"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input
                  type="text"
                  v-model="form.phone_number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter phone number"
                />
              </div>
            </div>
          </div>

          <!-- Item Information -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-box text-orange-500 mr-2"></i>
              Item Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU Code *</label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="form.sku_code"
                    @click="showSkuModal = true"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                    placeholder="Click to select SKU"
                    readonly
                    required
                  />
                  <button
                    @click="showSkuModal = true"
                    class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU Description</label>
                <input
                  type="text"
                  v-model="form.sku_description"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input
                  type="number"
                  v-model="form.quantity"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter quantity"
                  min="0"
                  step="0.01"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                <input
                  type="number"
                  v-model="form.unit_price"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter unit price"
                  min="0"
                  step="0.01"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                <input
                  type="text"
                  :value="formatCurrency(totalAmount)"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 font-medium"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">UOM</label>
                <input
                  type="text"
                  v-model="form.uom"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>
            </div>
          </div>

          <!-- Location Information -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
              Location Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location Code *</label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="form.location_code"
                    @click="showLocationModal = true"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                    placeholder="Click to select location"
                    readonly
                    required
                  />
                  <button
                    @click="showLocationModal = true"
                    class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                <input
                  type="text"
                  v-model="form.location_name"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                <input
                  type="text"
                  v-model="form.warehouse"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter warehouse"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Zone</label>
                <input
                  type="text"
                  v-model="form.zone"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter zone"
                />
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-clipboard-list text-purple-500 mr-2"></i>
              Additional Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Return Reason</label>
                <select
                  v-model="form.return_reason"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Select return reason</option>
                  <option value="Damaged">Damaged</option>
                  <option value="Wrong Item">Wrong Item</option>
                  <option value="Quality Issue">Quality Issue</option>
                  <option value="Overstock">Overstock</option>
                  <option value="Expired">Expired</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Inspector</label>
                <input
                  type="text"
                  v-model="form.inspector"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter inspector name"
                />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea
                  v-model="form.remarks"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter any additional remarks"
                ></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Summary & Recent Transactions -->
        <div class="space-y-6">
          <!-- Summary Card -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-calculator text-green-500 mr-2"></i>
              Transaction Summary
            </h3>
            
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Transaction Number:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.transaction_number || 'Not Generated' }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Date:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.transaction_date || 'Not Set' }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Supplier:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.supplier_name || 'Not Selected' }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">SKU:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.sku_code || 'Not Selected' }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Quantity:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.quantity || '0' }} {{ form.uom }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Total Amount:</span>
                <span class="text-sm font-bold text-red-600">{{ formatCurrency(totalAmount) }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Location:</span>
                <span class="text-sm font-bold text-gray-800">{{ form.location_name || 'Not Selected' }}</span>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                  Draft
                </span>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-history text-blue-500 mr-2"></i>
              Recent RT Transactions
            </h3>
            
            <div class="space-y-3 max-h-64 overflow-y-auto">
              <div
                v-for="transaction in recentTransactions"
                :key="transaction.id"
                class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-200 cursor-pointer"
                @click="loadTransaction(transaction)"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="font-medium text-sm text-gray-800">{{ transaction.transaction_number }}</div>
                    <div class="text-xs text-gray-600">{{ transaction.supplier_name }}</div>
                    <div class="text-xs text-gray-600">{{ transaction.sku_code }} - {{ transaction.quantity }} {{ transaction.uom }}</div>
                  </div>
                  <div class="text-right">
                    <div class="text-xs text-gray-500">{{ formatDate(transaction.transaction_date) }}</div>
                    <div class="text-xs font-medium" :class="getStatusColor(transaction.status)">
                      {{ transaction.status }}
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-if="recentTransactions.length === 0" class="text-center text-gray-500 text-sm py-4">
                <i class="fas fa-inbox text-2xl mb-2"></i>
                <p>No recent transactions</p>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
              <i class="fas fa-bolt text-yellow-500 mr-2"></i>
              Quick Actions
            </h3>
            
            <div class="space-y-2">
              <button
                @click="duplicateLastTransaction"
                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
              >
                <i class="fas fa-copy mr-2"></i>
                Duplicate Last RT
              </button>
              <button
                @click="loadTemplate"
                class="w-full bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
              >
                <i class="fas fa-file-alt mr-2"></i>
                Load Template
              </button>
              <button
                @click="exportTransaction"
                :disabled="!form.transaction_number"
                class="w-full bg-purple-100 hover:bg-purple-200 text-purple-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center disabled:opacity-50"
              >
                <i class="fas fa-download mr-2"></i>
                Export RT
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <VendorProfileModal
      v-if="showSupplierModal"
      @close="showSupplierModal = false"
      @select="selectSupplier"
    />
    
    <SkuLookupModal
      v-if="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />
    
    <LocationTableModal
      v-if="showLocationModal"
      @close="showLocationModal = false"
      @select="selectLocation"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import LocationTableModal from '@/Components/LocationTableModal.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error } = useToast()

const header = 'Prepare Return (RT)'

// Form data
const form = ref({
  transaction_number: '',
  transaction_date: new Date().toISOString().split('T')[0],
  transaction_type: 'RT',
  po_number: '',
  reference_number: '',
  return_note: '',
  supplier_code: '',
  supplier_name: '',
  contact_person: '',
  phone_number: '',
  sku_code: '',
  sku_description: '',
  quantity: '',
  unit_price: '',
  uom: '',
  location_code: '',
  location_name: '',
  warehouse: '',
  zone: '',
  return_reason: '',
  inspector: '',
  remarks: ''
})

// UI state
const isSaving = ref(false)
const showSupplierModal = ref(false)
const showSkuModal = ref(false)
const showLocationModal = ref(false)
const recentTransactions = ref([])

// Computed properties
const totalAmount = computed(() => {
  const quantity = parseFloat(form.value.quantity) || 0
  const unitPrice = parseFloat(form.value.unit_price) || 0
  return quantity * unitPrice
})

const isFormValid = computed(() => {
  return form.value.transaction_date &&
         form.value.supplier_code &&
         form.value.sku_code &&
         form.value.quantity &&
         form.value.location_code
})

const currentPeriod = ref('01/2024')
const currentUser = ref('Admin User')
const currentTime = ref('')

// Methods
const selectSupplier = (supplier) => {
  form.value.supplier_code = supplier.ap_ac_number
  form.value.supplier_name = supplier.vendor_name
  showSupplierModal.value = false
}

const selectSku = (sku) => {
  form.value.sku_code = sku.sku
  form.value.sku_description = sku.sku_name
  form.value.uom = sku.uom
  showSkuModal.value = false
}

const selectLocation = (location) => {
  form.value.location_code = location.code
  form.value.location_name = location.name
  showLocationModal.value = false
}

const saveTransaction = async () => {
  if (!isFormValid.value) {
    error('Please fill in all required fields')
    return
  }

  isSaving.value = true
  try {
    const response = await axios.post('/api/rc-rt/transactions', {
      ...form.value,
      total_amount: totalAmount.value
    })

    if (response.data.success) {
      success('RT transaction saved successfully')
      form.value.transaction_number = response.data.data.transaction_number
      loadRecentTransactions()
    } else {
      error(response.data.message || 'Failed to save transaction')
    }
  } catch (err) {
    error('Error saving transaction: ' + err.message)
  } finally {
    isSaving.value = false
  }
}

const clearForm = () => {
  form.value = {
    transaction_number: '',
    transaction_date: new Date().toISOString().split('T')[0],
    transaction_type: 'RT',
    po_number: '',
    reference_number: '',
    return_note: '',
    supplier_code: '',
    supplier_name: '',
    contact_person: '',
    phone_number: '',
    sku_code: '',
    sku_description: '',
    quantity: '',
    unit_price: '',
    uom: '',
    location_code: '',
    location_name: '',
    warehouse: '',
    zone: '',
    return_reason: '',
    inspector: '',
    remarks: ''
  }
  success('Form cleared successfully')
}

const printTransaction = () => {
  if (!form.value.transaction_number) {
    error('No transaction to print')
    return
  }
  
  // Open print window
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>RT Transaction - ${form.value.transaction_number}</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          .header { text-align: center; margin-bottom: 30px; }
          .section { margin-bottom: 20px; }
          .field { margin-bottom: 10px; }
          .label { font-weight: bold; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>Return Transaction (RT)</h1>
          <h2>${form.value.transaction_number}</h2>
        </div>
        
        <div class="section">
          <h3>Transaction Details</h3>
          <div class="field">
            <span class="label">Date:</span> ${form.value.transaction_date}
          </div>
          <div class="field">
            <span class="label">PO Number:</span> ${form.value.po_number}
          </div>
          <div class="field">
            <span class="label">Reference:</span> ${form.value.reference_number}
          </div>
          <div class="field">
            <span class="label">Return Note:</span> ${form.value.return_note}
          </div>
        </div>
        
        <div class="section">
          <h3>Supplier Information</h3>
          <div class="field">
            <span class="label">Code:</span> ${form.value.supplier_code}
          </div>
          <div class="field">
            <span class="label">Name:</span> ${form.value.supplier_name}
          </div>
        </div>
        
        <div class="section">
          <h3>Item Information</h3>
          <div class="field">
            <span class="label">SKU:</span> ${form.value.sku_code}
          </div>
          <div class="field">
            <span class="label">Description:</span> ${form.value.sku_description}
          </div>
          <div class="field">
            <span class="label">Quantity:</span> ${form.value.quantity} ${form.value.uom}
          </div>
          <div class="field">
            <span class="label">Unit Price:</span> ${formatCurrency(form.value.unit_price)}
          </div>
          <div class="field">
            <span class="label">Total Amount:</span> ${formatCurrency(totalAmount.value)}
          </div>
          <div class="field">
            <span class="label">Return Reason:</span> ${form.value.return_reason}
          </div>
        </div>
        
        <div class="section">
          <h3>Location Information</h3>
          <div class="field">
            <span class="label">Location:</span> ${form.value.location_code} - ${form.value.location_name}
          </div>
        </div>
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.print()
}

const loadTransaction = (transaction) => {
  form.value = { ...transaction }
  success('Transaction loaded successfully')
}

const duplicateLastTransaction = () => {
  if (recentTransactions.value.length > 0) {
    const lastTransaction = recentTransactions.value[0]
    form.value = {
      ...lastTransaction,
      transaction_number: '',
      transaction_date: new Date().toISOString().split('T')[0],
      quantity: '',
      unit_price: '',
      remarks: ''
    }
    success('Last transaction duplicated')
  } else {
    error('No recent transactions to duplicate')
  }
}

const loadTemplate = () => {
  // Load a template transaction
  form.value = {
    ...form.value,
    supplier_code: 'VEND001',
    supplier_name: 'Sample Supplier',
    sku_code: 'SKU001',
    sku_description: 'Sample SKU',
    uom: 'PCS',
    location_code: 'LOC001',
    location_name: 'Main Warehouse',
    return_reason: 'Damaged'
  }
  success('Template loaded successfully')
}

const exportTransaction = () => {
  if (!form.value.transaction_number) {
    error('No transaction to export')
    return
  }
  
  // Create CSV export
  const csvContent = [
    ['Transaction Number', 'Date', 'Supplier', 'SKU', 'Quantity', 'Amount', 'Return Reason'],
    [form.value.transaction_number, form.value.transaction_date, form.value.supplier_name, form.value.sku_code, form.value.quantity, totalAmount.value, form.value.return_reason]
  ].map(row => row.join(',')).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `RT_${form.value.transaction_number}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
  
  success('Transaction exported successfully')
}

const loadRecentTransactions = async () => {
  try {
    const response = await axios.get('/api/rc-rt/rt-transactions?limit=5')
    if (response.data.success) {
      recentTransactions.value = response.data.data
    }
  } catch (err) {
    console.error('Error loading recent transactions:', err)
  }
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount || 0)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusColor = (status) => {
  switch (status) {
    case 'Draft': return 'text-gray-600'
    case 'Posted': return 'text-green-600'
    case 'Cancelled': return 'text-red-600'
    default: return 'text-gray-600'
  }
}

const updateCurrentTime = () => {
  currentTime.value = new Date().toLocaleTimeString('id-ID')
}

// Lifecycle
onMounted(() => {
  loadRecentTransactions()
  updateCurrentTime()
  setInterval(updateCurrentTime, 1000)
})
</script>

<style scoped>
/* Custom scrollbar for recent transactions */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
