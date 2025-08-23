<template>
  <AppLayout :header="header">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
              <i class="fas fa-list-alt text-orange-500 mr-3"></i>
              View & Print RT Log
            </h1>
            <p class="text-gray-600 mt-2">View and print return transaction logs</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="exportToExcel"
              :disabled="isExporting"
              class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-file-excel mr-2" v-if="!isExporting"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isExporting ? 'Exporting...' : 'Export Excel' }}
            </button>
            <button
              @click="printLog"
              :disabled="isPrinting"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-print mr-2" v-if="!isPrinting"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isPrinting ? 'Printing...' : 'Print Log' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Search Filters -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-search text-blue-500 mr-2"></i>
          Search Filters
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Number</label>
            <input
              type="text"
              v-model="filters.transaction_number"
              placeholder="Search by transaction number..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input
              type="date"
              v-model="filters.date_from"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input
              type="date"
              v-model="filters.date_to"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="filters.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Status</option>
              <option value="Draft">Draft</option>
              <option value="Posted">Posted</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
            <input
              type="text"
              v-model="filters.supplier"
              placeholder="Search by supplier..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
            <input
              type="text"
              v-model="filters.sku"
              placeholder="Search by SKU..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
            <input
              type="text"
              v-model="filters.location"
              placeholder="Search by location..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
        
        <div class="flex space-x-3">
          <button
            @click="searchTransactions"
            :disabled="isLoading"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-search mr-2" v-if="!isLoading"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isLoading ? 'Searching...' : 'Search' }}
          </button>
          <button
            @click="clearFilters"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Filters
          </button>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-blue-600">Total Transactions</p>
              <p class="text-2xl font-bold text-blue-800">{{ statistics.total_transactions }}</p>
            </div>
            <i class="fas fa-file-alt text-blue-400 text-2xl"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-green-600">Total Amount</p>
              <p class="text-2xl font-bold text-green-800">{{ formatCurrency(statistics.total_amount) }}</p>
            </div>
            <i class="fas fa-money-bill-wave text-green-400 text-2xl"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-purple-600">Posted Transactions</p>
              <p class="text-2xl font-bold text-purple-800">{{ statistics.posted_count }}</p>
            </div>
            <i class="fas fa-check-circle text-purple-400 text-2xl"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-orange-600">Draft Transactions</p>
              <p class="text-2xl font-bold text-orange-800">{{ statistics.draft_count }}</p>
            </div>
            <i class="fas fa-edit text-orange-400 text-2xl"></i>
          </div>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-table text-orange-500 mr-2"></i>
            RT Transactions Log
          </h3>
          <div class="text-sm text-gray-500">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.transaction_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.transaction_date) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.supplier_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.po_number || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.sku_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatNumber(transaction.quantity) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(transaction.unit_price) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(transaction.total_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.location_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusBadgeClass(transaction.status)">
                    {{ transaction.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.created_by }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="viewTransaction(transaction)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                    title="View Details"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button
                    @click="printTransaction(transaction)"
                    class="text-green-600 hover:text-green-900"
                    title="Print Transaction"
                  >
                    <i class="fas fa-print"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6">
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-700">Show</span>
            <select
              v-model="pagination.per_page"
              @change="loadTransactions"
              class="px-2 py-1 border border-gray-300 rounded text-sm"
            >
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-700">entries</span>
          </div>
          
          <div class="flex items-center space-x-2">
            <button
              @click="previousPage"
              :disabled="pagination.current_page === 1"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Previous
            </button>
            <span class="text-sm text-gray-700">
              Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button
              @click="nextPage"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Transaction Detail Modal -->
      <div v-if="showDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-bold text-gray-800">Transaction Details</h3>
              <button
                @click="showDetailModal = false"
                class="text-gray-400 hover:text-gray-600"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
            
            <div v-if="selectedTransaction" class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Transaction Number</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.transaction_number }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Transaction Date</label>
                  <p class="text-sm text-gray-900">{{ formatDate(selectedTransaction.transaction_date) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Supplier</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.supplier_name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">PO Number</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.po_number || '-' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">SKU</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.sku_name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Quantity</label>
                  <p class="text-sm text-gray-900">{{ formatNumber(selectedTransaction.quantity) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                  <p class="text-sm text-gray-900">{{ formatCurrency(selectedTransaction.unit_price) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                  <p class="text-sm text-gray-900">{{ formatCurrency(selectedTransaction.total_amount) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Location</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.location_name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Status</label>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusBadgeClass(selectedTransaction.status)">
                    {{ selectedTransaction.status }}
                  </span>
                </div>
              </div>
              
              <div v-if="selectedTransaction.remarks">
                <label class="block text-sm font-medium text-gray-700">Remarks</label>
                <p class="text-sm text-gray-900">{{ selectedTransaction.remarks }}</p>
              </div>
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Created By</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.created_by }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Created At</label>
                  <p class="text-sm text-gray-900">{{ formatDateTime(selectedTransaction.created_at) }}</p>
                </div>
                <div v-if="selectedTransaction.posted_by">
                  <label class="block text-sm font-medium text-gray-700">Posted By</label>
                  <p class="text-sm text-gray-900">{{ selectedTransaction.posted_by }}</p>
                </div>
                <div v-if="selectedTransaction.posted_at">
                  <label class="block text-sm font-medium text-gray-700">Posted At</label>
                  <p class="text-sm text-gray-900">{{ formatDateTime(selectedTransaction.posted_at) }}</p>
                </div>
              </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
              <button
                @click="printTransaction(selectedTransaction)"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
              >
                <i class="fas fa-print mr-2"></i>
                Print
              </button>
              <button
                @click="showDetailModal = false"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const props = defineProps({
  header: String
})

const toast = useToast()

// Reactive data
const filters = ref({
  transaction_number: '',
  date_from: '',
  date_to: '',
  status: '',
  supplier: '',
  sku: '',
  location: ''
})

const transactions = ref([])
const statistics = ref({
  total_transactions: 0,
  total_amount: 0,
  posted_count: 0,
  draft_count: 0
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 25,
  total: 0,
  from: 0,
  to: 0
})

const selectedTransaction = ref(null)
const showDetailModal = ref(false)

const isLoading = ref(false)
const isExporting = ref(false)
const isPrinting = ref(false)

// Methods
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const formatNumber = (number) => {
  return new Intl.NumberFormat('id-ID').format(number)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('id-ID')
}

const getStatusBadgeClass = (status) => {
  return {
    'Draft': 'bg-gray-100 text-gray-800',
    'Posted': 'bg-green-100 text-green-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }[status] || 'bg-gray-100 text-gray-800'
}

const loadTransactions = async () => {
  try {
    isLoading.value = true
    
    const params = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      transaction_type: 'RT',
      ...filters.value
    }
    
    const response = await axios.get('/api/rc-rt/rt-transactions', { params })
    
    if (response.data.success) {
      transactions.value = response.data.data.data || []
      pagination.value = {
        current_page: response.data.data.current_page,
        last_page: response.data.data.last_page,
        per_page: response.data.data.per_page,
        total: response.data.data.total,
        from: response.data.data.from,
        to: response.data.data.to
      }
      
      // Update statistics
      if (response.data.statistics) {
        statistics.value = response.data.statistics
      }
    }
  } catch (error) {
    console.error('Error loading transactions:', error)
    toast.error('Error loading transactions')
  } finally {
    isLoading.value = false
  }
}

const searchTransactions = () => {
  pagination.value.current_page = 1
  loadTransactions()
}

const clearFilters = () => {
  filters.value = {
    transaction_number: '',
    date_from: '',
    date_to: '',
    status: '',
    supplier: '',
    sku: '',
    location: ''
  }
  searchTransactions()
}

const previousPage = () => {
  if (pagination.value.current_page > 1) {
    pagination.value.current_page--
    loadTransactions()
  }
}

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    pagination.value.current_page++
    loadTransactions()
  }
}

const viewTransaction = (transaction) => {
  selectedTransaction.value = transaction
  showDetailModal.value = true
}

const printTransaction = async (transaction) => {
  try {
    isPrinting.value = true
    
    const response = await axios.post('/api/rc-rt/print-transaction', {
      transaction_id: transaction.id
    })
    
    if (response.data.success) {
      // Handle print response
      toast.success('Transaction sent to printer successfully!')
    }
  } catch (error) {
    console.error('Error printing transaction:', error)
    toast.error('Error printing transaction')
  } finally {
    isPrinting.value = false
  }
}

const printLog = async () => {
  try {
    isPrinting.value = true
    
    const response = await axios.post('/api/rc-rt/print-log', {
      transaction_type: 'RT',
      ...filters.value
    })
    
    if (response.data.success) {
      toast.success('Log sent to printer successfully!')
    }
  } catch (error) {
    console.error('Error printing log:', error)
    toast.error('Error printing log')
  } finally {
    isPrinting.value = false
  }
}

const exportToExcel = async () => {
  try {
    isExporting.value = true
    
    const response = await axios.post('/api/rc-rt/export-excel', {
      transaction_type: 'RT',
      ...filters.value
    })
    
    if (response.data.success) {
      // Create and download Excel file
      const blob = new Blob([response.data.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' })
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `RT_Log_${new Date().toISOString().split('T')[0]}.xlsx`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      window.URL.revokeObjectURL(url)
      
      toast.success('Log exported successfully!')
    }
  } catch (error) {
    console.error('Error exporting log:', error)
    toast.error('Error exporting log')
  } finally {
    isExporting.value = false
  }
}

// Initialize
onMounted(() => {
  loadTransactions()
})
</script>

<style scoped>
/* Custom styles */
.transition-all {
  transition: all 0.3s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
