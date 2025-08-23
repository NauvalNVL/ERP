<template>
  <AppLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-800 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-edit mr-3"></i>
            Amend Credit Note (CN)
          </h1>
          <p class="text-teal-100 mt-2 text-lg">Search and modify existing credit note transactions</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="saveEdit"
            :disabled="!editingNote || isSaving"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-save mr-2" v-if="!isSaving"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isSaving ? 'Saving...' : 'Save Changes' }}
          </button>
          <button
            @click="postNote"
            :disabled="!editingNote || editingNote.status !== 'Approved'"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-paper-plane mr-2"></i>
            Post Note
          </button>
          <button
            @click="cancelNote"
            :disabled="!editingNote || !canCancel(editingNote.status)"
            class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-ban mr-2"></i>
            Cancel Note
          </button>
        </div>
      </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-search text-teal-600 mr-2"></i>
        Search Credit Notes
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
          <input
            v-model="searchForm.note_number"
            type="text"
            @input="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
            placeholder="Search by note number"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
          <input
            v-model="searchForm.customer_name"
            type="text"
            @input="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
            placeholder="Search by customer name"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select
            v-model="searchForm.status"
            @change="searchNotes"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
          >
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Posted">Posted</option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="exportSearchResults"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
          >
            <i class="fas fa-download mr-2"></i>
            Export Results
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-teal-100 text-teal-600">
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
          <div class="p-3 rounded-full bg-gray-100 text-gray-600">
            <i class="fas fa-edit text-xl"></i>
          </div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">{{ statistics.draft }}</div>
            <div class="text-sm text-gray-500">Draft</div>
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
    </div>

    <!-- Notes Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
          <i class="fas fa-table text-teal-600 mr-2"></i>
          Credit Notes ({{ sortedNotes.length }} records)
        </h3>
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
                Amount
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
                  <div class="text-gray-500">{{ note.customer_name }}</div>
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
                  @click="editNote(note)"
                  class="text-teal-600 hover:text-teal-900 transition-colors duration-150"
                  title="Edit"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  @click="viewNote(note)"
                  class="text-blue-600 hover:text-blue-900 transition-colors duration-150"
                  title="View"
                >
                  <i class="fas fa-eye"></i>
                </button>
                <button
                  @click="printNote(note)"
                  class="text-green-600 hover:text-green-900 transition-colors duration-150"
                  title="Print"
                >
                  <i class="fas fa-print"></i>
                </button>
                <button
                  v-if="note.status === 'Draft'"
                  @click="deleteNote(note)"
                  class="text-red-600 hover:text-red-900 transition-colors duration-150"
                  title="Delete"
                >
                  <i class="fas fa-trash"></i>
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
          Showing {{ ((currentPage - 1) * pageSize) + 1 }} to {{ Math.min(currentPage * pageSize, sortedNotes.length) }} of {{ sortedNotes.length }} results
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
            :class="page === currentPage ? 'bg-teal-600 text-white' : 'text-gray-500 hover:text-gray-700'"
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

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
              <i class="fas fa-edit text-teal-600 mr-2"></i>
              Edit Credit Note: {{ editForm.note_number }}
            </h3>
            <button
              @click="closeEditModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Note Number (readonly) -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
              <input
                v-model="editForm.note_number"
                type="text"
                readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
              />
            </div>

            <!-- Reference Document -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Reference Document</label>
              <input
                v-model="editForm.reference_document"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              />
            </div>

            <!-- Reference Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Reference Type</label>
              <select
                v-model="editForm.reference_type"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
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

            <!-- Customer Code (readonly) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code</label>
              <input
                v-model="editForm.customer_code"
                type="text"
                readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
              />
            </div>

            <!-- Customer Name (readonly) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
              <input
                v-model="editForm.customer_name"
                type="text"
                readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
              />
            </div>

            <!-- Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
              <input
                v-model="editForm.amount"
                type="number"
                step="0.01"
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              />
            </div>

            <!-- Currency -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
              <select
                v-model="editForm.currency"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              >
                <option value="IDR">IDR - Indonesian Rupiah</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="SGD">SGD - Singapore Dollar</option>
              </select>
            </div>

            <!-- Note Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Note Date</label>
              <input
                v-model="editForm.note_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              />
            </div>

            <!-- Due Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
              <input
                v-model="editForm.due_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              />
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea
                v-model="editForm.description"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              ></textarea>
            </div>

            <!-- Reason -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
              <textarea
                v-model="editForm.reason"
                rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              ></textarea>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <button
              @click="closeEditModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              @click="saveEdit"
              :disabled="isSaving"
              class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 disabled:bg-gray-400 flex items-center"
            >
              <i class="fas fa-save mr-2" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isSaving ? 'Saving...' : 'Save Changes' }}
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
  customer_name: '',
  status: ''
})

// Edit form
const editForm = ref({
  id: null,
  note_number: '',
  reference_document: '',
  reference_type: '',
  customer_code: '',
  customer_name: '',
  amount: 0,
  description: '',
  reason: '',
  note_date: '',
  due_date: '',
  currency: 'IDR',
  exchange_rate: 1.0000
})

// Loading states
const isSaving = ref(false)

// Modal states
const showEditModal = ref(false)

// Data
const notes = ref([])
const editingNote = ref(null)
const statistics = ref({
  total: 0,
  posted: 0,
  draft: 0,
  cancelled: 0
})

// Pagination
const currentPage = ref(1)
const pageSize = ref(10)
const sortField = ref('note_date')
const sortDirection = ref('desc')

// Computed properties
const sortedNotes = computed(() => {
  let filtered = notes.value.filter(note => {
    return (!searchForm.value.note_number || note.note_number.toLowerCase().includes(searchForm.value.note_number.toLowerCase())) &&
           (!searchForm.value.customer_name || (note.customer_name && note.customer_name.toLowerCase().includes(searchForm.value.customer_name.toLowerCase()))) &&
           (!searchForm.value.status || note.status === searchForm.value.status)
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

const totalPages = computed(() => Math.ceil(sortedNotes.value.length / pageSize.value))

const paginatedNotes = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  const end = start + pageSize.value
  return sortedNotes.value.slice(start, end)
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
const searchNotes = async () => {
  await loadNotes()
}

const loadNotes = async () => {
  try {
    const params = new URLSearchParams()
    if (searchForm.value.note_number) params.append('search', searchForm.value.note_number)
    if (searchForm.value.customer_name) params.append('search', searchForm.value.customer_name)
    if (searchForm.value.status) params.append('status', searchForm.value.status)

    const response = await fetch(`/api/dr-cn/cn-transactions?${params}`)
    const result = await response.json()
    
    if (result.success) {
      notes.value = result.data.data || []
      updateStatistics()
    }
  } catch (error) {
    console.error('Error loading notes:', error)
    toast.error('Error loading credit notes')
  }
}

const updateStatistics = () => {
  statistics.value = {
    total: notes.value.length,
    posted: notes.value.filter(n => n.status === 'Posted').length,
    draft: notes.value.filter(n => n.status === 'Draft').length,
    cancelled: notes.value.filter(n => n.status === 'Cancelled').length
  }
}

const editNote = (note) => {
  editingNote.value = note
  editForm.value = {
    id: note.id,
    note_number: note.note_number,
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
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingNote.value = null
}

const saveEdit = async () => {
  if (!editForm.value.id) return
  
  isSaving.value = true
  try {
    const response = await fetch(`/api/dr-cn/transactions/${editForm.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(editForm.value)
    })

    const result = await response.json()
    
    if (result.success) {
      toast.success('Credit note updated successfully!')
      closeEditModal()
      loadNotes()
    } else {
      toast.error(result.message || 'Failed to update credit note')
    }
  } catch (error) {
    console.error('Error updating note:', error)
    toast.error('Error updating credit note')
  } finally {
    isSaving.value = false
  }
}

const postNote = async () => {
  if (!editingNote.value) return

  try {
    const response = await fetch(`/api/dr-cn/transactions/${editingNote.value.id}/post`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    const result = await response.json()
    
    if (result.success) {
      toast.success('Credit note posted successfully!')
      editingNote.value = null
      loadNotes()
    } else {
      toast.error(result.message || 'Failed to post credit note')
    }
  } catch (error) {
    console.error('Error posting note:', error)
    toast.error('Error posting credit note')
  }
}

const cancelNote = async () => {
  if (!editingNote.value) return

  if (!confirm('Are you sure you want to cancel this credit note?')) return

  try {
    const response = await fetch(`/api/dr-cn/transactions/${editingNote.value.id}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ notes: 'Cancelled by user' })
    })

    const result = await response.json()
    
    if (result.success) {
      toast.success('Credit note cancelled successfully!')
      editingNote.value = null
      loadNotes()
    } else {
      toast.error(result.message || 'Failed to cancel credit note')
    }
  } catch (error) {
    console.error('Error cancelling note:', error)
    toast.error('Error cancelling credit note')
  }
}

const viewNote = (note) => {
  alert(`Note Details:\n\nNumber: ${note.note_number}\nCustomer: ${note.customer_name}\nAmount: ${formatCurrency(note.amount)}\nDescription: ${note.description}`)
}

const printNote = (note) => {
  const printContent = `
    <div style="font-family: Arial, sans-serif; padding: 20px;">
      <h2>Credit Note</h2>
      <p><strong>Note Number:</strong> ${note.note_number}</p>
      <p><strong>Date:</strong> ${formatDate(note.note_date)}</p>
      <p><strong>Customer:</strong> ${note.customer_name}</p>
      <p><strong>Amount:</strong> ${formatCurrency(note.amount)}</p>
      <p><strong>Description:</strong> ${note.description}</p>
      <p><strong>Status:</strong> ${note.status}</p>
    </div>
  `
  
  const printWindow = window.open('', '_blank')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const deleteNote = async (note) => {
  if (!confirm(`Are you sure you want to delete credit note ${note.note_number}?`)) return

  try {
    const response = await fetch(`/api/dr-cn/transactions/${note.id}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ notes: 'Deleted by user' })
    })

    const result = await response.json()
    
    if (result.success) {
      toast.success('Credit note deleted successfully!')
      loadNotes()
    } else {
      toast.error(result.message || 'Failed to delete credit note')
    }
  } catch (error) {
    console.error('Error deleting note:', error)
    toast.error('Error deleting credit note')
  }
}

const exportSearchResults = () => {
  const csvContent = "Note Number,Date,Customer,Amount,Status,Description\n" + 
    sortedNotes.value.map(note => 
      `${note.note_number},${note.note_date},${note.customer_name || ''},${note.amount},${note.status},"${note.description}"`
    ).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'credit_notes_search_results.csv'
  a.click()
  window.URL.revokeObjectURL(url)
  toast.success('Search results exported')
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
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

const goToPage = (page) => {
  currentPage.value = page
}

const canCancel = (status) => {
  return ['Draft', 'Pending'].includes(status)
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
