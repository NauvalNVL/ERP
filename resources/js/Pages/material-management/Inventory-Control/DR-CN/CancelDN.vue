<template>
  <AppLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-ban mr-3"></i>
            Cancel Debit Note (DN)
          </h1>
          <p class="text-red-100 mt-2 text-lg">Search and cancel existing debit note transactions</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="cancelSelectedNotes"
            :disabled="selectedNotes.length === 0 || isCancelling"
            class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-ban mr-2" v-if="!isCancelling"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isCancelling ? 'Cancelling...' : `Cancel Selected (${selectedNotes.length})` }}
          </button>
        </div>
      </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-search text-red-600 mr-2"></i>
        Search Debit Notes to Cancel
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
          <input
            v-model="searchForm.note_number"
            type="text"
            @input="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            placeholder="Search by note number"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Vendor Name</label>
          <input
            v-model="searchForm.vendor_name"
            type="text"
            @input="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            placeholder="Search by vendor name"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
          <select
            v-model="searchForm.date_range"
            @change="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
          >
            <option value="">All Dates</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="quarter">This Quarter</option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="clearSearch"
            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Search
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <i class="fas fa-file-invoice text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.cancellable }}</div>
            <div class="text-sm text-gray-500">Cancellable</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-red-100 text-red-600">
            <i class="fas fa-ban text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.cancelled }}</div>
            <div class="text-sm text-gray-500">Cancelled</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 text-green-600">
            <i class="fas fa-check text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ selectedNotes.length }}</div>
            <div class="text-sm text-gray-500">Selected</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <i class="fas fa-dollar-sign text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalSelectedAmount) }}</div>
            <div class="text-sm text-gray-500">Selected Amount</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notes Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-table text-red-600 mr-2"></i>
            Cancellable Debit Notes ({{ cancellableNotes.length }} records)
          </h3>
          <div class="flex items-center space-x-4">
            <label class="flex items-center">
              <input
                type="checkbox"
                :checked="isAllSelected"
                @change="toggleSelectAll"
                class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
              />
              <span class="ml-2 text-sm text-gray-700">Select All</span>
            </label>
          </div>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Select
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Note Number
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Vendor
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Amount
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Description
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr 
              v-for="note in paginatedNotes" 
              :key="note.id"
              :class="selectedNotes.includes(note.id) ? 'bg-red-50' : 'hover:bg-gray-50'"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <input
                  type="checkbox"
                  :value="note.id"
                  v-model="selectedNotes"
                  class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ note.note_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(note.note_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>
                  <div class="font-medium">{{ note.vendor_code }}</div>
                  <div class="text-gray-500">{{ note.vendor_name }}</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatCurrency(note.amount) }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                {{ note.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(note.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                  {{ note.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button
                  @click="viewNote(note)"
                  class="text-blue-600 hover:text-blue-900 transition-colors duration-150"
                  title="View Details"
                >
                  <i class="fas fa-eye"></i>
                </button>
                <button
                  @click="cancelSingleNote(note)"
                  class="text-red-600 hover:text-red-900 transition-colors duration-150"
                  title="Cancel Note"
                >
                  <i class="fas fa-ban"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-6 py-4 rounded-lg shadow-lg mt-6">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ ((currentPage - 1) * pageSize) + 1 }} to {{ Math.min(currentPage * pageSize, cancellableNotes.length) }} of {{ cancellableNotes.length }} results
        </div>
        <div class="flex items-center space-x-2">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="goToPage(page)"
            :class="page === currentPage ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-700'"
            class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">Confirm Cancellation</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Are you sure you want to cancel the selected debit note(s)? This action cannot be undone.
            </p>
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
              <textarea
                v-model="cancellationReason"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Enter reason for cancellation..."
              ></textarea>
            </div>
          </div>
          <div class="flex justify-center space-x-3 mt-4">
            <button
              @click="showConfirmModal = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              @click="confirmCancellation"
              :disabled="!cancellationReason.trim()"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:bg-gray-400"
            >
              Confirm Cancellation
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'

const toast = useToast()

// Search form
const searchForm = ref({
  note_number: '',
  vendor_name: '',
  date_range: ''
})

// Loading states
const isCancelling = ref(false)
const isLoading = ref(false)

// Modal states
const showConfirmModal = ref(false)

// Data
const notes = ref([])
const selectedNotes = ref([])
const cancellationReason = ref('')
const statistics = ref({
  cancellable: 0,
  cancelled: 0
})

// Pagination
const currentPage = ref(1)
const pageSize = ref(10)

// Computed properties
const cancellableNotes = computed(() => {
  return notes.value.filter(note => {
    const isCancellable = ['Draft', 'Pending'].includes(note.status)
    const matchesSearch = (!searchForm.value.note_number || note.note_number.toLowerCase().includes(searchForm.value.note_number.toLowerCase())) &&
                         (!searchForm.value.vendor_name || (note.vendor_name && note.vendor_name.toLowerCase().includes(searchForm.value.vendor_name.toLowerCase())))
    return isCancellable && matchesSearch
  })
})

const totalPages = computed(() => Math.ceil(cancellableNotes.value.length / pageSize.value))

const paginatedNotes = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  const end = start + pageSize.value
  return cancellableNotes.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(totalPages.value, currentPage.value + 2)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

const isAllSelected = computed(() => {
  return cancellableNotes.value.length > 0 && selectedNotes.value.length === cancellableNotes.value.length
})

const totalSelectedAmount = computed(() => {
  return notes.value
    .filter(note => selectedNotes.value.includes(note.id))
    .reduce((sum, note) => sum + note.amount, 0)
})

// Methods
const searchNotes = async () => {
  await loadNotes()
}

const clearSearch = () => {
  searchForm.value = {
    note_number: '',
    vendor_name: '',
    date_range: ''
  }
  searchNotes()
}

const loadNotes = async () => {
  isLoading.value = true
  try {
    const params = new URLSearchParams()
    if (searchForm.value.note_number) params.append('search', searchForm.value.note_number)
    if (searchForm.value.vendor_name) params.append('search', searchForm.value.vendor_name)

    const response = await fetch(`/api/dr-cn/dn-transactions?${params}`)
    const result = await response.json()
    
    if (result.success) {
      notes.value = result.data.data || []
      updateStatistics()
    }
  } catch (error) {
    console.error('Error loading notes:', error)
    toast.error('Error loading debit notes')
  } finally {
    isLoading.value = false
  }
}

const updateStatistics = () => {
  statistics.value = {
    cancellable: notes.value.filter(n => ['Draft', 'Pending'].includes(n.status)).length,
    cancelled: notes.value.filter(n => n.status === 'Cancelled').length
  }
}

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedNotes.value = []
  } else {
    selectedNotes.value = cancellableNotes.value.map(note => note.id)
  }
}

const cancelSelectedNotes = () => {
  if (selectedNotes.value.length === 0) {
    toast.warning('Please select notes to cancel')
    return
  }
  showConfirmModal.value = true
}

const cancelSingleNote = (note) => {
  selectedNotes.value = [note.id]
  showConfirmModal.value = true
}

const confirmCancellation = async () => {
  if (!cancellationReason.value.trim()) {
    toast.error('Please provide a cancellation reason')
    return
  }

  isCancelling.value = true
  try {
    const promises = selectedNotes.value.map(async (noteId) => {
      const response = await fetch(`/api/dr-cn/transactions/${noteId}/cancel`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ notes: cancellationReason.value })
      })
      return response.json()
    })

    const results = await Promise.all(promises)
    const successCount = results.filter(r => r.success).length
    
    if (successCount > 0) {
      toast.success(`${successCount} debit note(s) cancelled successfully!`)
      selectedNotes.value = []
      cancellationReason.value = ''
      showConfirmModal.value = false
      loadNotes()
    } else {
      toast.error('Failed to cancel debit notes')
    }
  } catch (error) {
    console.error('Error cancelling notes:', error)
    toast.error('Error cancelling debit notes')
  } finally {
    isCancelling.value = false
  }
}

const viewNote = (note) => {
  alert(`Note Details:\n\nNumber: ${note.note_number}\nVendor: ${note.vendor_name}\nAmount: ${formatCurrency(note.amount)}\nDescription: ${note.description}\nStatus: ${note.status}`)
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

const goToPage = (page) => {
  currentPage.value = page
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
  loadNotes()
})
</script>
