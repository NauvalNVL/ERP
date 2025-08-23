<template>
  <AppLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-list-alt mr-3"></i>
            View & Print CN Log
          </h1>
          <p class="text-cyan-100 mt-2 text-lg">View and print credit note transaction logs and history</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="exportLog"
            :disabled="filteredNotes.length === 0"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-download mr-2"></i>
            Export Log
          </button>
          <button
            @click="printLog"
            :disabled="filteredNotes.length === 0"
            class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-print mr-2"></i>
            Print Log
          </button>
        </div>
      </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-filter text-cyan-600 mr-2"></i>
        Search & Filter
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
          <input
            v-model="filters.note_number"
            type="text"
            @input="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            placeholder="Search by note number"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
          <input
            v-model="filters.customer"
            type="text"
            @input="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            placeholder="Search by customer"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select
            v-model="filters.status"
            @change="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
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
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
          <select
            v-model="filters.date_range"
            @change="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
          >
            <option value="">All Dates</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="quarter">This Quarter</option>
            <option value="year">This Year</option>
          </select>
        </div>
      </div>

      <!-- Custom Date Range -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
          <input
            v-model="filters.date_from"
            type="date"
            @change="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
          <input
            v-model="filters.date_to"
            type="date"
            @change="applyFilters"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
          />
        </div>
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-cyan-100 text-cyan-600">
            <i class="fas fa-file-medical text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.total }}</div>
            <div class="text-sm text-gray-500">Total CN</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <i class="fas fa-paper-plane text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.posted }}</div>
            <div class="text-sm text-gray-500">Posted</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <i class="fas fa-clock text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.pending }}</div>
            <div class="text-sm text-gray-500">Pending</div>
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
            <i class="fas fa-dollar-sign text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(statistics.total_amount) }}</div>
            <div class="text-sm text-gray-500">Total Amount</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Log Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-table text-cyan-600 mr-2"></i>
            Credit Note Log ({{ filteredNotes.length }} records)
          </h3>
          <div class="text-sm text-gray-500">
            Last updated: {{ lastUpdated }}
          </div>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th 
                @click="sortBy('note_number')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
              >
                <div class="flex items-center">
                  Note Number
                  <i class="fas fa-sort ml-1"></i>
                </div>
              </th>
              <th 
                @click="sortBy('note_date')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
              >
                <div class="flex items-center">
                  Date
                  <i class="fas fa-sort ml-1"></i>
                </div>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Customer
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Reference
              </th>
              <th 
                @click="sortBy('amount')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
              >
                <div class="flex items-center">
                  Amount
                  <i class="fas fa-sort ml-1"></i>
                </div>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Description
              </th>
              <th 
                @click="sortBy('status')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
              >
                <div class="flex items-center">
                  Status
                  <i class="fas fa-sort ml-1"></i>
                </div>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created By
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
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ note.note_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(note.note_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>
                  <div class="font-medium">{{ note.customer_code }}</div>
                  <div class="text-gray-500 text-xs">{{ note.customer_name }}</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div>
                  <div>{{ note.reference_document || 'N/A' }}</div>
                  <div class="text-xs text-gray-400">{{ note.reference_type || '' }}</div>
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
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ note.created_by }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button
                  @click="viewDetails(note)"
                  class="text-cyan-600 hover:text-cyan-900 transition-colors duration-150"
                  title="View Details"
                >
                  <i class="fas fa-eye"></i>
                </button>
                <button
                  @click="printNote(note)"
                  class="text-green-600 hover:text-green-900 transition-colors duration-150"
                  title="Print Note"
                >
                  <i class="fas fa-print"></i>
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
          Showing {{ ((currentPage - 1) * pageSize) + 1 }} to {{ Math.min(currentPage * pageSize, filteredNotes.length) }} of {{ filteredNotes.length }} results
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
            :class="page === currentPage ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:text-gray-700'"
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

    <!-- Detail Modal -->
    <div v-if="showDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
              <i class="fas fa-file-alt text-cyan-600 mr-2"></i>
              Credit Note Details: {{ selectedNote?.note_number }}
            </h3>
            <button
              @click="closeDetailModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          
          <div v-if="selectedNote" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Note Number</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.note_number }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ formatDate(selectedNote.note_date) }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.customer_code || 'N/A' }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.customer_name || 'N/A' }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Reference Document</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.reference_document || 'N/A' }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Reference Type</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.reference_type || 'N/A' }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ formatCurrency(selectedNote.amount) }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <div class="p-2">
                <span :class="getStatusBadgeClass(selectedNote.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                  {{ selectedNote.status }}
                </span>
              </div>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.description }}</div>
            </div>
            <div class="md:col-span-2" v-if="selectedNote.reason">
              <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.reason }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ selectedNote.created_by }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
              <div class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ formatDateTime(selectedNote.created_at) }}</div>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <button
              @click="printNote(selectedNote)"
              class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
            >
              <i class="fas fa-print mr-2"></i>
              Print
            </button>
            <button
              @click="closeDetailModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
            >
              Close
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

// Filters
const filters = ref({
  note_number: '',
  customer: '',
  status: '',
  date_range: '',
  date_from: '',
  date_to: ''
})

// Modal states
const showDetailModal = ref(false)

// Data
const notes = ref([])
const selectedNote = ref(null)
const statistics = ref({
  total: 0,
  posted: 0,
  pending: 0,
  cancelled: 0,
  total_amount: 0
})

// Pagination and sorting
const currentPage = ref(1)
const pageSize = ref(15)
const sortField = ref('note_date')
const sortDirection = ref('desc')
const lastUpdated = ref('')

// Computed properties
const filteredNotes = computed(() => {
  let filtered = notes.value.filter(note => {
    let matches = true
    
    if (filters.value.note_number) {
      matches = matches && note.note_number.toLowerCase().includes(filters.value.note_number.toLowerCase())
    }
    
    if (filters.value.customer) {
      matches = matches && (note.customer_name && note.customer_name.toLowerCase().includes(filters.value.customer.toLowerCase()))
    }
    
    if (filters.value.status) {
      matches = matches && note.status === filters.value.status
    }
    
    if (filters.value.date_from && filters.value.date_to) {
      const noteDate = new Date(note.note_date)
      const fromDate = new Date(filters.value.date_from)
      const toDate = new Date(filters.value.date_to)
      matches = matches && noteDate >= fromDate && noteDate <= toDate
    }
    
    return matches
  })

  return filtered.sort((a, b) => {
    let aVal = a[sortField.value]
    let bVal = b[sortField.value]
    
    if (sortDirection.value === 'desc') {
      return aVal > bVal ? -1 : 1
    } else {
      return aVal < bVal ? -1 : 1
    }
  })
})

const totalPages = computed(() => Math.ceil(filteredNotes.value.length / pageSize.value))

const paginatedNotes = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  const end = start + pageSize.value
  return filteredNotes.value.slice(start, end)
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

// Methods
const loadNotes = async () => {
  try {
    const response = await fetch('/api/dr-cn/cn-transactions')
    const result = await response.json()
    
    if (result.success) {
      notes.value = result.data.data || []
      updateStatistics()
      lastUpdated.value = new Date().toLocaleString('id-ID')
    }
  } catch (error) {
    console.error('Error loading notes:', error)
    toast.error('Error loading credit note log')
  }
}

const updateStatistics = () => {
  statistics.value = {
    total: notes.value.length,
    posted: notes.value.filter(n => n.status === 'Posted').length,
    pending: notes.value.filter(n => n.status === 'Pending').length,
    cancelled: notes.value.filter(n => n.status === 'Cancelled').length,
    total_amount: notes.value.reduce((sum, n) => sum + n.amount, 0)
  }
}

const applyFilters = () => {
  currentPage.value = 1
}

const clearFilters = () => {
  filters.value = {
    note_number: '',
    customer: '',
    status: '',
    date_range: '',
    date_from: '',
    date_to: ''
  }
  applyFilters()
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
}

const viewDetails = (note) => {
  selectedNote.value = note
  showDetailModal.value = true
}

const closeDetailModal = () => {
  showDetailModal.value = false
  selectedNote.value = null
}

const printNote = (note) => {
  const printContent = `
    <div style="font-family: Arial, sans-serif; padding: 20px;">
      <h2>Credit Note</h2>
      <p><strong>Note Number:</strong> ${note.note_number}</p>
      <p><strong>Date:</strong> ${formatDate(note.note_date)}</p>
      <p><strong>Customer:</strong> ${note.customer_name || 'N/A'}</p>
      <p><strong>Amount:</strong> ${formatCurrency(note.amount)}</p>
      <p><strong>Description:</strong> ${note.description}</p>
      <p><strong>Status:</strong> ${note.status}</p>
      ${note.reason ? `<p><strong>Reason:</strong> ${note.reason}</p>` : ''}
    </div>
  `
  
  const printWindow = window.open('', '_blank')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const printLog = () => {
  const printContent = `
    <div style="font-family: Arial, sans-serif; padding: 20px;">
      <h2>Credit Note Log</h2>
      <p>Generated: ${new Date().toLocaleString('id-ID')}</p>
      <p>Total Records: ${filteredNotes.value.length}</p>
      <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
          <tr style="background-color: #f9f9f9;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Note Number</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Customer</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Amount</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Status</th>
          </tr>
        </thead>
        <tbody>
          ${filteredNotes.value.map(note => `
            <tr>
              <td style="border: 1px solid #ddd; padding: 8px;">${note.note_number}</td>
              <td style="border: 1px solid #ddd; padding: 8px;">${formatDate(note.note_date)}</td>
              <td style="border: 1px solid #ddd; padding: 8px;">${note.customer_name || 'N/A'}</td>
              <td style="border: 1px solid #ddd; padding: 8px;">${formatCurrency(note.amount)}</td>
              <td style="border: 1px solid #ddd; padding: 8px;">${note.status}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    </div>
  `
  
  const printWindow = window.open('', '_blank')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const exportLog = () => {
  const csvContent = "Note Number,Date,Customer Code,Customer Name,Reference,Amount,Status,Description,Created By\n" + 
    filteredNotes.value.map(note => 
      `${note.note_number},${note.note_date},${note.customer_code || ''},${note.customer_name || ''},${note.reference_document || ''},${note.amount},${note.status},"${note.description}",${note.created_by}`
    ).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `CN_Log_${new Date().toISOString().split('T')[0]}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
  toast.success('Log exported successfully')
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

const formatDateTime = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
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
