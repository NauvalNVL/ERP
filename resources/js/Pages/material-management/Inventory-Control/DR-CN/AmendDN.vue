<template>
  <AppLayout>
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-edit mr-4 text-orange-200"></i>
              Amend Debit Note (DN)
            </h1>
            <p class="text-orange-100 text-xl font-medium">Modify existing debit note transactions</p>
            <div class="flex items-center mt-4 space-x-6">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-orange-200 mr-2"></i>
                <span class="text-sm">Period: {{ currentPeriod }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-user-circle text-orange-200 mr-2"></i>
                <span class="text-sm">User: {{ currentUser }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-clock text-orange-200 mr-2"></i>
                <span class="text-sm">{{ currentTime }}</span>
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              @click="updateNote"
              :disabled="!isFormValid || isUpdating"
              class="bg-green-500 hover:bg-green-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-save mr-3" v-if="!isUpdating"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isUpdating ? 'Updating...' : 'Update DN' }}
            </button>
            <button
              @click="resetForm"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-undo mr-3"></i>
              Reset
            </button>
            <button
              @click="printNote"
              :disabled="!selectedNote"
              class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-print mr-3"></i>
              Print
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Status Bar -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-orange-500">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3 animate-pulse"></div>
            <span class="text-sm font-semibold text-gray-700">System Status:</span>
            <span class="text-sm text-orange-600 font-bold ml-2">Ready</span>
          </div>
          <div class="flex items-center">
            <i class="fas fa-database text-blue-500 mr-2"></i>
            <span class="text-sm font-semibold text-gray-700">Database:</span>
            <span class="text-sm text-blue-600 font-bold ml-2">Connected</span>
          </div>
          <div class="flex items-center">
            <i class="fas fa-network-wired text-purple-500 mr-2"></i>
            <span class="text-sm font-semibold text-gray-700">Network:</span>
            <span class="text-sm text-purple-600 font-bold ml-2">Online</span>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-orange-100 text-orange-800 border border-orange-200">
            <i class="fas fa-edit mr-2"></i>
            Amendment Mode
          </span>
          <div class="text-sm text-gray-500">
            Last Updated: {{ lastUpdated }}
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
      <!-- Notes List Section -->
      <div class="xl:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-list text-orange-600 mr-3"></i>
            Available Notes
          </h2>

          <!-- Search and Filter -->
          <div class="mb-6 space-y-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Search Notes</label>
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                  placeholder="Search by note number or vendor..."
                />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Status Filter</label>
              <select
                v-model="statusFilter"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
              >
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
          </div>

          <!-- Notes List -->
          <div class="space-y-3 max-h-96 overflow-y-auto">
            <div
              v-for="note in filteredNotes"
              :key="note.id"
              @click="selectNote(note)"
              class="p-4 bg-gray-50 rounded-lg border-2 cursor-pointer transition-all duration-200 hover:bg-gray-100"
              :class="selectedNote?.id === note.id ? 'border-orange-500 bg-orange-50' : 'border-gray-200'"
            >
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-gray-800">{{ note.note_number }}</h3>
                <span :class="getStatusBadgeClass(note.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ note.status }}
                </span>
              </div>
              <p class="text-sm text-gray-600 mb-1">{{ note.vendor_name }}</p>
              <p class="text-sm text-gray-500">{{ formatDate(note.note_date) }}</p>
              <p class="text-sm font-semibold text-gray-800 mt-2">{{ formatCurrency(note.amount) }}</p>
            </div>
            
            <div v-if="filteredNotes.length === 0" class="text-center text-gray-500 py-8">
              <i class="fas fa-inbox text-3xl mb-3"></i>
              <p>No notes found</p>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
              Showing {{ paginationInfo.from }}-{{ paginationInfo.to }} of {{ paginationInfo.total }}
            </div>
            <div class="flex space-x-2">
              <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 rounded transition-colors duration-200"
              >
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-3 py-1 bg-orange-500 text-white rounded">{{ currentPage }}</span>
              <button
                @click="nextPage"
                :disabled="currentPage >= totalPages"
                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 rounded transition-colors duration-200"
              >
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="xl:col-span-3">
        <div v-if="selectedNote" class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
          <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
            <i class="fas fa-edit text-orange-600 mr-3"></i>
            Amend Note: {{ selectedNote.note_number }}
          </h2>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Note Number -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Note Number</label>
              <div class="relative">
                <input
                  v-model="form.note_number"
                  type="text"
                  readonly
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 font-mono text-lg"
                />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
              </div>
            </div>

            <!-- Note Date -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Note Date <span class="text-red-500">*</span></label>
              <input
                v-model="form.note_date"
                type="date"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                :max="new Date().toISOString().split('T')[0]"
              />
            </div>

            <!-- Reference Document -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Reference Document</label>
              <input
                v-model="form.reference_document"
                type="text"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                placeholder="e.g., PO-2024-001, Invoice #12345"
              />
            </div>

            <!-- Reference Type -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Reference Type</label>
              <select
                v-model="form.reference_type"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
              >
                <option value="">Select Type</option>
                <option value="PO">Purchase Order</option>
                <option value="INV">Invoice</option>
                <option value="RC">Receipt</option>
                <option value="RT">Return</option>
                <option value="ADJ">Adjustment</option>
                <option value="OTHER">Other</option>
              </select>
            </div>

            <!-- Vendor Selection -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Vendor <span class="text-red-500">*</span></label>
              <div class="relative">
                <input
                  v-model="form.vendor_name"
                  type="text"
                  readonly
                  @click="openVendorModal"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                  placeholder="Click to select vendor"
                />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
              </div>
              <input
                v-model="form.vendor_code"
                type="hidden"
              />
            </div>

            <!-- Amount -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Amount <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500 font-bold">Rp</span>
                <input
                  v-model.number="form.amount"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-right"
                  placeholder="0.00"
                />
              </div>
            </div>

            <!-- Currency -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Currency</label>
              <select
                v-model="form.currency"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
              >
                <option value="IDR">IDR - Indonesian Rupiah</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="SGD">SGD - Singapore Dollar</option>
              </select>
            </div>

            <!-- Exchange Rate -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Exchange Rate</label>
              <input
                v-model.number="form.exchange_rate"
                type="number"
                step="0.0001"
                min="0"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                placeholder="1.0000"
              />
            </div>

            <!-- Due Date -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Due Date</label>
              <input
                v-model="form.due_date"
                type="date"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                :min="form.note_date"
              />
            </div>
          </div>

          <!-- Description -->
          <div class="mt-8 space-y-2">
            <label class="block text-sm font-bold text-gray-700">Description <span class="text-red-500">*</span></label>
            <textarea
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
              placeholder="Enter detailed description of the debit note..."
            ></textarea>
          </div>

          <!-- Reason -->
          <div class="mt-6 space-y-2">
            <label class="block text-sm font-bold text-gray-700">Reason</label>
            <textarea
              v-model="form.reason"
              rows="3"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
              placeholder="Optional reason for the amendment..."
            ></textarea>
          </div>

          <!-- Amendment History -->
          <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
              <i class="fas fa-history text-gray-600 mr-2"></i>
              Amendment History
            </h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200">
                <div>
                  <p class="font-semibold text-sm text-gray-800">Original Amount</p>
                  <p class="text-sm text-gray-600">{{ formatCurrency(selectedNote.amount) }}</p>
                </div>
                <div>
                  <p class="font-semibold text-sm text-gray-800">Current Amount</p>
                  <p class="text-sm text-orange-600 font-bold">{{ formatCurrency(form.amount) }}</p>
                </div>
                <div>
                  <p class="font-semibold text-sm text-gray-800">Difference</p>
                  <p :class="getDifferenceClass()" class="text-sm font-bold">
                    {{ formatCurrency(form.amount - selectedNote.amount) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- No Note Selected -->
        <div v-else class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 text-center">
          <i class="fas fa-file-invoice text-6xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-bold text-gray-600 mb-2">No Note Selected</h3>
          <p class="text-gray-500">Please select a note from the list to begin amending</p>
        </div>
      </div>
    </div>

    <!-- Vendor Modal -->
    <VendorProfileModal
      :show="showVendorModal"
      @close="showVendorModal = false"
      @select="selectVendor"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'

const toast = useToast()

// Form data
const form = ref({
  note_number: '',
  note_type: 'DR',
  reference_document: '',
  reference_type: '',
  vendor_code: '',
  vendor_name: '',
  amount: 0,
  description: '',
  reason: '',
  note_date: '',
  due_date: '',
  currency: 'IDR',
  exchange_rate: 1.0000
})

// Loading states
const isUpdating = ref(false)
const isLoading = ref(false)

// Modal states
const showVendorModal = ref(false)

// Data
const notes = ref([])
const selectedNote = ref(null)

// Search and filter
const searchQuery = ref('')
const statusFilter = ref('')

// Pagination
const currentPage = ref(1)
const pageSize = ref(10)
const totalPages = ref(1)

// Time and user data
const currentPeriod = ref('January 2025')
const currentUser = ref('System Admin')
const currentTime = ref('')
const lastUpdated = ref('')

// Update current time
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('en-US', { 
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
  lastUpdated.value = now.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Computed properties
const isFormValid = computed(() => {
  return form.value.note_date && 
         form.value.vendor_code &&
         form.value.amount > 0 && 
         form.value.description.trim() !== ''
})

const filteredNotes = computed(() => {
  let filtered = notes.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(note => 
      note.note_number.toLowerCase().includes(query) ||
      note.vendor_name.toLowerCase().includes(query)
    )
  }

  if (statusFilter.value) {
    filtered = filtered.filter(note => note.status === statusFilter.value)
  }

  return filtered
})

const paginationInfo = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value + 1
  const end = Math.min(currentPage.value * pageSize.value, filteredNotes.value.length)
  return {
    from: filteredNotes.value.length > 0 ? start : 0,
    to: end,
    total: filteredNotes.value.length
  }
})

// Methods
const openVendorModal = () => {
  showVendorModal.value = true
}

const selectVendor = (vendor) => {
  form.value.vendor_code = vendor.code
  form.value.vendor_name = vendor.name
  showVendorModal.value = false
  toast.success(`Vendor selected: ${vendor.name}`)
}

const selectNote = (note) => {
  selectedNote.value = note
  form.value = {
    note_number: note.note_number,
    note_type: note.note_type,
    reference_document: note.reference_document || '',
    reference_type: note.reference_type || '',
    vendor_code: note.vendor_code || '',
    vendor_name: note.vendor_name || '',
    amount: note.amount || 0,
    description: note.description || '',
    reason: note.reason || '',
    note_date: note.note_date || new Date().toISOString().split('T')[0],
    due_date: note.due_date || '',
    currency: note.currency || 'IDR',
    exchange_rate: note.exchange_rate || 1.0000
  }
  toast.info(`Selected note: ${note.note_number}`)
}

const updateNote = async () => {
  if (!validateForm()) return
  if (!selectedNote.value) {
    toast.error('Please select a note to update')
    return
  }

  isUpdating.value = true
  
  try {
    const response = await fetch(`/api/dr-cn/transactions/${selectedNote.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    const result = await response.json()
    
    if (result.success) {
      toast.success('Debit note updated successfully!')
      loadNotes()
      
      // Show success modal with details
      showSuccessModal(result.data)
    } else {
      toast.error(result.message || 'Failed to update debit note')
    }
  } catch (error) {
    console.error('Error updating note:', error)
    toast.error('Error updating debit note')
  } finally {
    isUpdating.value = false
  }
}

const resetForm = () => {
  if (selectedNote.value) {
    selectNote(selectedNote.value)
    toast.info('Form reset to original values')
  } else {
    toast.warning('No note selected to reset')
  }
}

const printNote = () => {
  if (!selectedNote.value) {
    toast.warning('Please select a note to print')
    return
  }
  
  // Open print window
  const printWindow = window.open(`/material-management/inventory-control/dr-cn/print-dn/${selectedNote.value.note_number}`, '_blank')
  if (printWindow) {
    printWindow.focus()
  }
}

const validateForm = () => {
  const errors = []
  
  if (!form.value.note_date) errors.push('Note date is required')
  if (!form.value.vendor_code) errors.push('Vendor selection is required')
  if (!form.value.amount || form.value.amount <= 0) errors.push('Amount must be greater than 0')
  if (!form.value.description.trim()) errors.push('Description is required')
  
  if (errors.length > 0) {
    toast.error(`Validation failed: ${errors.join(', ')}`)
    return false
  } else {
    toast.success('Form validation passed!')
    return true
  }
}

const loadNotes = async () => {
  try {
    isLoading.value = true
    const response = await fetch('/api/dr-cn/dn-transactions')
    const result = await response.json()
    
    if (result.success) {
      notes.value = result.data.data || []
      totalPages.value = Math.ceil(notes.value.length / pageSize.value)
    }
  } catch (error) {
    console.error('Error loading notes:', error)
    toast.error('Error loading notes')
  } finally {
    isLoading.value = false
  }
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const showSuccessModal = (noteData) => {
  // Use SweetAlert2 for enhanced success modal
  if (window.Swal) {
    window.Swal.fire({
      title: 'Success!',
      html: `
        <div class="text-left">
          <p><strong>Note Number:</strong> ${noteData.note_number}</p>
          <p><strong>Vendor:</strong> ${noteData.vendor_name}</p>
          <p><strong>Amount:</strong> ${formatCurrency(noteData.amount)}</p>
          <p><strong>Status:</strong> <span class="text-green-600 font-bold">${noteData.status}</span></p>
        </div>
      `,
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#10B981'
    })
  }
}

const getDifferenceClass = () => {
  const difference = form.value.amount - selectedNote.value.amount
  if (difference > 0) return 'text-green-600'
  if (difference < 0) return 'text-red-600'
  return 'text-gray-600'
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
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

// Watchers
watch([searchQuery, statusFilter], () => {
  currentPage.value = 1
})

// Lifecycle hooks
onMounted(() => {
  updateCurrentTime()
  const interval = setInterval(updateCurrentTime, 1000)
  loadNotes()
  
  // Store interval for cleanup
  window.amendDnInterval = interval
})

onUnmounted(() => {
  if (window.amendDnInterval) {
    clearInterval(window.amendDnInterval)
  }
})
</script>
