<template>
  <AppLayout>
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-file-invoice mr-4 text-red-200"></i>
              Prepare Debit Note (DN)
            </h1>
            <p class="text-red-100 text-xl font-medium">Create and manage debit note transactions for inventory adjustments</p>
            <div class="flex items-center mt-4 space-x-6">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-red-200 mr-2"></i>
                <span class="text-sm">Period: {{ currentPeriod }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-user-circle text-red-200 mr-2"></i>
                <span class="text-sm">User: {{ currentUser }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-clock text-red-200 mr-2"></i>
                <span class="text-sm">{{ currentTime }}</span>
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              @click="saveNote"
              :disabled="!isFormValid || isSaving"
              class="bg-green-500 hover:bg-green-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-save mr-3" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isSaving ? 'Saving...' : 'Save DN' }}
            </button>
            <button
              @click="clearForm"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-eraser mr-3"></i>
              Clear
            </button>
            <button
              @click="printNote"
              :disabled="!form.note_number"
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
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-green-500">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
            <span class="text-sm font-semibold text-gray-700">System Status:</span>
            <span class="text-sm text-green-600 font-bold ml-2">Ready</span>
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
          <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-200">
            <i class="fas fa-check-circle mr-2"></i>
            Ready to Process
          </span>
          <div class="text-sm text-gray-500">
            Last Updated: {{ lastUpdated }}
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
      <!-- Form Section -->
      <div class="xl:col-span-3">
        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
          <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
            <i class="fas fa-edit text-red-600 mr-3"></i>
            Debit Note Information
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
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 font-mono text-lg"
                  placeholder="Auto-generated"
                />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
              </div>
              <p class="text-xs text-gray-500">System generated unique identifier</p>
            </div>

            <!-- Note Date -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Note Date <span class="text-red-500">*</span></label>
              <input
                v-model="form.note_date"
                type="date"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                :max="new Date().toISOString().split('T')[0]"
              />
            </div>

            <!-- Reference Document -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Reference Document</label>
              <input
                v-model="form.reference_document"
                type="text"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                placeholder="e.g., PO-2024-001, Invoice #12345"
              />
            </div>

            <!-- Reference Type -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Reference Type</label>
              <select
                v-model="form.reference_type"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
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
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
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
                  class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-right"
                  placeholder="0.00"
                />
              </div>
            </div>

            <!-- Currency -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Currency</label>
              <select
                v-model="form.currency"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
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
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                placeholder="1.0000"
              />
            </div>

            <!-- Due Date -->
            <div class="space-y-2">
              <label class="block text-sm font-bold text-gray-700">Due Date</label>
              <input
                v-model="form.due_date"
                type="date"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
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
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 resize-none"
              placeholder="Enter detailed description of the debit note..."
            ></textarea>
          </div>

          <!-- Reason -->
          <div class="mt-6 space-y-2">
            <label class="block text-sm font-bold text-gray-700">Reason</label>
            <textarea
              v-model="form.reason"
              rows="3"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 resize-none"
              placeholder="Optional reason for the debit note..."
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="xl:col-span-1">
        <!-- Recent Notes -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-blue-600 mr-2"></i>
            Recent Notes
          </h3>
          <div class="space-y-3">
            <div
              v-for="note in recentNotes.slice(0, 5)"
              :key="note.id"
              class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-200 cursor-pointer"
              @click="loadNote(note)"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="font-semibold text-sm text-gray-800">{{ note.note_number }}</p>
                  <p class="text-xs text-gray-600">{{ note.vendor_name }}</p>
                </div>
                <span :class="getStatusBadgeClass(note.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ note.status }}
                </span>
              </div>
              <p class="text-xs text-gray-500 mt-1">{{ formatDate(note.note_date) }}</p>
            </div>
            <div v-if="recentNotes.length === 0" class="text-center text-gray-500 text-sm py-4">
              <i class="fas fa-inbox text-2xl mb-2"></i>
              <p>No recent notes</p>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
            Quick Actions
          </h3>
          <div class="space-y-3">
            <button
              @click="generateNoteNumber"
              class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center"
            >
              <i class="fas fa-magic mr-2"></i>
              Generate Number
            </button>
            <button
              @click="copyFromLastNote"
              class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center"
            >
              <i class="fas fa-copy mr-2"></i>
              Copy Last Note
            </button>
            <button
              @click="validateForm"
              class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center"
            >
              <i class="fas fa-check-circle mr-2"></i>
              Validate Form
            </button>
          </div>
        </div>

        <!-- Form Validation -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-6 border border-gray-100">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
            Validation Status
          </h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Note Date</span>
              <i :class="form.note_date ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Vendor</span>
              <i :class="form.vendor_code ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Amount</span>
              <i :class="form.amount > 0 ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Description</span>
              <i :class="form.description.trim() ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
            </div>
          </div>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
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
  note_date: new Date().toISOString().split('T')[0],
  due_date: '',
  currency: 'IDR',
  exchange_rate: 1.0000
})

// Loading states
const isSaving = ref(false)
const isLoading = ref(false)

// Modal states
const showVendorModal = ref(false)

// Data
const recentNotes = ref([])

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

const generateNoteNumber = async () => {
  try {
    const response = await fetch('/api/dr-cn/generate-number', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ note_type: 'DR' })
    })

    const result = await response.json()
    
    if (result.success) {
      form.value.note_number = result.note_number
      toast.success('Note number generated successfully!')
    } else {
      toast.error('Failed to generate note number')
    }
  } catch (error) {
    console.error('Error generating note number:', error)
    toast.error('Error generating note number')
  }
}

const copyFromLastNote = () => {
  if (recentNotes.value.length > 0) {
    const lastNote = recentNotes.value[0]
    form.value = {
      ...form.value,
      reference_document: lastNote.reference_document || '',
      reference_type: lastNote.reference_type || '',
      vendor_code: lastNote.vendor_code || '',
      vendor_name: lastNote.vendor_name || '',
      currency: lastNote.currency || 'IDR',
      exchange_rate: lastNote.exchange_rate || 1.0000,
      description: lastNote.description || '',
      reason: lastNote.reason || ''
    }
    toast.success('Copied data from last note')
  } else {
    toast.warning('No recent notes to copy from')
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

const saveNote = async () => {
  if (!validateForm()) return

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
      toast.success('Debit note saved successfully!')
      loadRecentNotes()
      
      // Show success modal with details
      showSuccessModal(result.data)
    } else {
      toast.error(result.message || 'Failed to save debit note')
    }
  } catch (error) {
    console.error('Error saving note:', error)
    toast.error('Error saving debit note')
  } finally {
    isSaving.value = false
  }
}

const clearForm = () => {
  form.value = {
    note_number: '',
    note_type: 'DR',
    reference_document: '',
    reference_type: '',
    vendor_code: '',
    vendor_name: '',
    amount: 0,
    description: '',
    reason: '',
    note_date: new Date().toISOString().split('T')[0],
    due_date: '',
    currency: 'IDR',
    exchange_rate: 1.0000
  }
  toast.info('Form cleared')
}

const printNote = () => {
  if (!form.value.note_number) {
    toast.warning('Please save the note first before printing')
    return
  }
  
  // Open print window
  const printWindow = window.open(`/material-management/inventory-control/dr-cn/print-dn/${form.value.note_number}`, '_blank')
  if (printWindow) {
    printWindow.focus()
  }
}

const loadNote = (note) => {
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
  toast.info(`Loaded note: ${note.note_number}`)
}

const loadRecentNotes = async () => {
  try {
    const response = await fetch('/api/dr-cn/dn-transactions?limit=10')
    const result = await response.json()
    
    if (result.success) {
      recentNotes.value = result.data.data || []
    }
  } catch (error) {
    console.error('Error loading recent notes:', error)
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

// Lifecycle hooks
onMounted(() => {
  updateCurrentTime()
  const interval = setInterval(updateCurrentTime, 1000)
  loadRecentNotes()
  
  // Store interval for cleanup
  window.dnInterval = interval
})

onUnmounted(() => {
  if (window.dnInterval) {
    clearInterval(window.dnInterval)
  }
})
</script>
