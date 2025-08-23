<template>
  <AppLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-file-medical mr-3"></i>
            Prepare Credit Note (CN)
          </h1>
          <p class="text-green-100 mt-2 text-lg">Create new credit note transactions for inventory adjustments</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="saveNote"
            :disabled="!isFormValid || isSaving"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-save mr-2" v-if="!isSaving"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isSaving ? 'Saving...' : 'Save' }}
          </button>
          <button
            @click="clearForm"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Form
          </button>
          <button
            @click="printNote"
            :disabled="!form.note_number"
            class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-print mr-2"></i>
            Print
          </button>
        </div>
      </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-6">
          <div class="flex items-center">
            <i class="fas fa-calendar text-blue-600 mr-2"></i>
            <span class="text-sm font-medium text-gray-700">Current Period:</span>
            <span class="text-sm text-gray-900 ml-1">{{ currentPeriod }}</span>
          </div>
          <div class="flex items-center">
            <i class="fas fa-user text-green-600 mr-2"></i>
            <span class="text-sm font-medium text-gray-700">User:</span>
            <span class="text-sm text-gray-900 ml-1">{{ currentUser }}</span>
          </div>
          <div class="flex items-center">
            <i class="fas fa-clock text-orange-600 mr-2"></i>
            <span class="text-sm font-medium text-gray-700">Current Time:</span>
            <span class="text-sm text-gray-900 ml-1">{{ currentTime }}</span>
          </div>
        </div>
        <div class="flex items-center">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            <i class="fas fa-check-circle mr-1"></i>
            Ready to Process
          </span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Form Section -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-edit text-green-600 mr-2"></i>
            Credit Note Information
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Note Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
              <div class="relative">
                <input
                  v-model="form.note_number"
                  type="text"
                  readonly
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  placeholder="Auto-generated"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
              </div>
            </div>

            <!-- Note Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Note Date *</label>
              <input
                v-model="form.note_date"
                type="date"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>

            <!-- Reference Document -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Reference Document</label>
              <input
                v-model="form.reference_document"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                placeholder="Invoice number, PO number, etc."
              />
            </div>

            <!-- Reference Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Reference Type</label>
              <select
                v-model="form.reference_type"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              >
                <option value="">Select Reference Type</option>
                <option value="Invoice">Invoice</option>
                <option value="Purchase Order">Purchase Order</option>
                <option value="Sales Order">Sales Order</option>
                <option value="Receipt">Receipt</option>
                <option value="Return">Return</option>
                <option value="Refund">Refund</option>
                <option value="Discount">Discount</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Customer Code -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code</label>
              <div class="relative">
                <input
                  v-model="form.customer_code"
                  type="text"
                  readonly
                  @click="openCustomerModal"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  placeholder="Click to select customer"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
              </div>
            </div>

            <!-- Customer Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
              <input
                v-model="form.customer_name"
                type="text"
                readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                placeholder="Customer name will appear here"
              />
            </div>

            <!-- Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
              <div class="relative">
                <input
                  v-model="form.amount"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent pl-12"
                  placeholder="0.00"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                  <span class="text-gray-500 sm:text-sm">IDR</span>
                </div>
              </div>
            </div>

            <!-- Currency -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
              <select
                v-model="form.currency"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              >
                <option value="IDR">IDR - Indonesian Rupiah</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="SGD">SGD - Singapore Dollar</option>
              </select>
            </div>

            <!-- Exchange Rate -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate</label>
              <input
                v-model="form.exchange_rate"
                type="number"
                step="0.0001"
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                placeholder="1.0000"
              />
            </div>

            <!-- Due Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
              <input
                v-model="form.due_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Description -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
            <textarea
              v-model="form.description"
              rows="3"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              placeholder="Enter description for this credit note..."
            ></textarea>
          </div>

          <!-- Reason -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
            <textarea
              v-model="form.reason"
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              placeholder="Enter reason for this credit note (optional)..."
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="space-y-6">
        <!-- Note Summary Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Note Summary
          </h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Note Type:</span>
              <span class="text-sm font-medium text-green-600">Credit Note</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Status:</span>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                Draft
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Amount:</span>
              <span class="text-sm font-medium text-gray-900">{{ formatCurrency(form.amount || 0) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Currency:</span>
              <span class="text-sm font-medium text-gray-900">{{ form.currency }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Created By:</span>
              <span class="text-sm font-medium text-gray-900">{{ currentUser }}</span>
            </div>
          </div>
        </div>

        <!-- Recent Notes Panel -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-purple-600 mr-2"></i>
            Recent Credit Notes
          </h3>
          <div class="space-y-3">
            <div 
              v-for="note in recentNotes" 
              :key="note.id"
              @click="loadNote(note)"
              class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-150"
            >
              <div class="flex justify-between items-start">
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ note.note_number }}</div>
                  <div class="text-xs text-gray-500">{{ note.customer_name || 'N/A' }}</div>
                  <div class="text-xs text-gray-500">{{ formatDate(note.note_date) }}</div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">{{ formatCurrency(note.amount) }}</div>
                  <span :class="getStatusBadgeClass(note.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                    {{ note.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
            Quick Actions
          </h3>
          <div class="space-y-3">
            <button
              @click="duplicateLastNote"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-copy mr-2"></i>
              Duplicate Last CN
            </button>
            <button
              @click="loadTemplate"
              class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-file-import mr-2"></i>
              Load Template
            </button>
            <button
              @click="exportNote"
              class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-download mr-2"></i>
              Export CN (CSV)
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Selection Modal (Mock) -->
    <div v-if="showCustomerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Select Customer</h3>
            <button
              @click="showCustomerModal = false"
              class="text-gray-400 hover:text-gray-600"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          
          <div class="mb-4">
            <input
              v-model="customerSearch"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              placeholder="Search customers..."
            />
          </div>

          <div class="max-h-64 overflow-y-auto">
            <div 
              v-for="customer in filteredCustomers" 
              :key="customer.code"
              @click="selectCustomer(customer)"
              class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
            >
              <div class="font-medium text-gray-900">{{ customer.code }}</div>
              <div class="text-sm text-gray-500">{{ customer.name }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'

const toast = useToast()

// Form data
const form = ref({
  note_number: '',
  note_type: 'CR',
  reference_document: '',
  reference_type: '',
  customer_code: '',
  customer_name: '',
  amount: 0,
  description: '',
  reason: '',
  note_date: new Date().toISOString().split('T')[0],
  due_date: '',
  currency: 'IDR',
  exchange_rate: 1.0000
})

// Loading states
const isSaving = ref(false)

// Modal states
const showCustomerModal = ref(false)
const customerSearch = ref('')

// Data
const recentNotes = ref([])
const customers = ref([
  { code: 'CUST001', name: 'PT. Customer Pertama' },
  { code: 'CUST002', name: 'PT. Customer Kedua' },
  { code: 'CUST003', name: 'PT. Customer Ketiga' },
])

// Time and user data
const currentPeriod = ref('January 2025')
const currentUser = ref('System Admin')
const currentTime = ref('')

// Update current time
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('en-US', { 
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

// Computed properties
const isFormValid = computed(() => {
  return form.value.note_date && 
         form.value.amount > 0 && 
         form.value.description.trim() !== ''
})

const filteredCustomers = computed(() => {
  if (!customerSearch.value) return customers.value
  return customers.value.filter(customer => 
    customer.code.toLowerCase().includes(customerSearch.value.toLowerCase()) ||
    customer.name.toLowerCase().includes(customerSearch.value.toLowerCase())
  )
})

// Methods
const openCustomerModal = () => {
  showCustomerModal.value = true
  customerSearch.value = ''
}

const selectCustomer = (customer) => {
  form.value.customer_code = customer.code
  form.value.customer_name = customer.name
  showCustomerModal.value = false
}

const saveNote = async () => {
  if (!isFormValid.value) {
    toast.error('Please fill in all required fields')
    return
  }

  isSaving.value = true
  
  try {
    const response = await fetch('/api/dr-cn/transactions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    const result = await response.json()
    
    if (result.success) {
      form.value.note_number = result.data.note_number
      toast.success('Credit note saved successfully!')
      loadRecentNotes()
    } else {
      toast.error(result.message || 'Failed to save credit note')
    }
  } catch (error) {
    console.error('Error saving note:', error)
    toast.error('Error saving credit note')
  } finally {
    isSaving.value = false
  }
}

const clearForm = () => {
  form.value = {
    note_number: '',
    note_type: 'CR',
    reference_document: '',
    reference_type: '',
    customer_code: '',
    customer_name: '',
    amount: 0,
    description: '',
    reason: '',
    note_date: new Date().toISOString().split('T')[0],
    due_date: '',
    currency: 'IDR',
    exchange_rate: 1.0000
  }
  toast.success('Form cleared')
}

const printNote = () => {
  if (!form.value.note_number) {
    toast.warning('Please save the note first')
    return
  }
  
  const printContent = `
    <div style="font-family: Arial, sans-serif; padding: 20px;">
      <h2>Credit Note</h2>
      <p><strong>Note Number:</strong> ${form.value.note_number}</p>
      <p><strong>Date:</strong> ${formatDate(form.value.note_date)}</p>
      <p><strong>Customer:</strong> ${form.value.customer_name}</p>
      <p><strong>Amount:</strong> ${formatCurrency(form.value.amount)}</p>
      <p><strong>Description:</strong> ${form.value.description}</p>
      ${form.value.reason ? `<p><strong>Reason:</strong> ${form.value.reason}</p>` : ''}
    </div>
  `
  
  const printWindow = window.open('', '_blank')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const loadNote = (note) => {
  form.value = {
    note_number: note.note_number,
    note_type: note.note_type,
    reference_document: note.reference_document || '',
    reference_type: note.reference_type || '',
    customer_code: note.customer_code || '',
    customer_name: note.customer_name || '',
    amount: note.amount,
    description: note.description,
    reason: note.reason || '',
    note_date: note.note_date,
    due_date: note.due_date || '',
    currency: note.currency,
    exchange_rate: note.exchange_rate
  }
  toast.info('Note loaded for editing')
}

const duplicateLastNote = () => {
  if (recentNotes.value.length > 0) {
    const lastNote = recentNotes.value[0]
    form.value = {
      note_number: '',
      note_type: lastNote.note_type,
      reference_document: '',
      reference_type: lastNote.reference_type || '',
      customer_code: lastNote.customer_code || '',
      customer_name: lastNote.customer_name || '',
      amount: lastNote.amount,
      description: lastNote.description,
      reason: lastNote.reason || '',
      note_date: new Date().toISOString().split('T')[0],
      due_date: '',
      currency: lastNote.currency,
      exchange_rate: lastNote.exchange_rate
    }
    toast.success('Last credit note duplicated')
  } else {
    toast.warning('No recent notes to duplicate')
  }
}

const loadTemplate = () => {
  form.value = {
    note_number: '',
    note_type: 'CR',
    reference_document: '',
    reference_type: 'Invoice',
    customer_code: '',
    customer_name: '',
    amount: 0,
    description: 'Credit note for customer refund',
    reason: 'Refund as per company policy',
    note_date: new Date().toISOString().split('T')[0],
    due_date: '',
    currency: 'IDR',
    exchange_rate: 1.0000
  }
  toast.success('Template loaded')
}

const exportNote = () => {
  if (!form.value.note_number) {
    toast.warning('Please save the note first')
    return
  }
  
  const csvContent = `Note Number,Date,Customer,Amount,Description\n${form.value.note_number},${form.value.note_date},${form.value.customer_name},${form.value.amount},${form.value.description}`
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `CN_${form.value.note_number}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
  toast.success('Note exported')
}

const loadRecentNotes = async () => {
  try {
    const response = await fetch('/api/dr-cn/cn-transactions?limit=5')
    const result = await response.json()
    
    if (result.success) {
      recentNotes.value = result.data.data || []
    }
  } catch (error) {
    console.error('Error loading recent notes:', error)
  }
}

// Utility functions
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount || 0)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusBadgeClass = (status) => {
  const classes = {
    'Draft': 'bg-gray-100 text-gray-800',
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Approved': 'bg-green-100 text-green-800',
    'Rejected': 'bg-red-100 text-red-800',
    'Posted': 'bg-blue-100 text-blue-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// Lifecycle hooks
onMounted(() => {
  updateCurrentTime()
  const interval = setInterval(updateCurrentTime, 1000)
  loadRecentNotes()
  
  // Store interval for cleanup
  window.prepareCnInterval = interval
})

onUnmounted(() => {
  if (window.prepareCnInterval) {
    clearInterval(window.prepareCnInterval)
  }
})
</script>
