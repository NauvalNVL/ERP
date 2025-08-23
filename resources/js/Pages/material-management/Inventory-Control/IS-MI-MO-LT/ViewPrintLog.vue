<template>
  <AppLayout :header="'View & Print IS/MI/MO/LT Log'">
    <Head title="View & Print IS/MI/MO/LT Log" />
    
    <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-list-alt mr-4 text-indigo-200"></i>
              View & Print IS/MI/MO/LT Log
            </h1>
            <p class="text-indigo-100 text-xl font-medium">View and print transaction logs for all inventory operations</p>
            <div class="flex items-center mt-4 space-x-6">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-indigo-200 mr-2"></i>
                <span class="text-sm">Period: {{ currentPeriod }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-user-circle text-indigo-200 mr-2"></i>
                <span class="text-sm">User: {{ currentUser }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-clock text-indigo-200 mr-2"></i>
                <span class="text-sm">{{ currentTime }}</span>
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              @click="printReport"
              :disabled="!hasData || isPrinting"
              class="bg-indigo-500 hover:bg-indigo-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-print mr-3" v-if="!isPrinting"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isPrinting ? 'Printing...' : 'Print Report' }}
            </button>
            <button
              @click="exportToExcel"
              :disabled="!hasData"
              class="bg-green-500 hover:bg-green-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-file-excel mr-3"></i>
              Export Excel
            </button>
            <button
              @click="exportToPDF"
              :disabled="!hasData"
              class="bg-red-500 hover:bg-red-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-file-pdf mr-3"></i>
              Export PDF
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">System: Online</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">Database: Connected</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">Network: Active</span>
          </div>
        </div>
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
          Ready to Process
        </div>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-filter mr-3 text-indigo-600"></i>
        Report Filters
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Transaction Type -->
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Transaction Type</label>
          <select
            v-model="filters.transactionType"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          >
            <option value="">All Types</option>
            <option value="IS">IS (Inventory Setup)</option>
            <option value="MI">MI (Material Issue)</option>
            <option value="MO">MO (Material Order)</option>
            <option value="LT">LT (Location Transfer)</option>
          </select>
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
          <select
            v-model="filters.status"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          >
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Posted">Posted</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>

        <!-- Date Range -->
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">From Date</label>
          <input
            v-model="filters.dateFrom"
            type="date"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">To Date</label>
          <input
            v-model="filters.dateTo"
            type="date"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
        </div>
      </div>

      <!-- Search and Action Buttons -->
      <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <input
            v-model="filters.search"
            type="text"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Search by transaction number, SKU, description..."
          />
        </div>
        <button
          @click="loadTransactions"
          class="bg-indigo-500 hover:bg-indigo-600 text-white py-3 px-6 rounded-lg transition-colors flex items-center justify-center"
        >
          <i class="fas fa-search mr-2"></i>
          Search
        </button>
        <button
          @click="clearFilters"
          class="bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition-colors flex items-center justify-center"
        >
          <i class="fas fa-eraser mr-2"></i>
          Clear
        </button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <i class="fas fa-list text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-900">{{ summary.total }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 text-green-600">
            <i class="fas fa-check text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Posted</p>
            <p class="text-2xl font-bold text-gray-900">{{ summary.posted }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <i class="fas fa-clock text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Draft</p>
            <p class="text-2xl font-bold text-gray-900">{{ summary.draft }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-red-100 text-red-600">
            <i class="fas fa-times text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Cancelled</p>
            <p class="text-2xl font-bold text-gray-900">{{ summary.cancelled }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
          <i class="fas fa-table mr-3 text-indigo-600"></i>
          Transaction Log
        </h2>
        <div class="text-sm text-gray-600">
          Showing {{ paginationInfo.from }} to {{ paginationInfo.to }} of {{ paginationInfo.total }} results
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('transaction_number')">
                Transaction Number
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('transaction_type')">
                Type
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('transaction_date')">
                Date
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                SKU Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Location
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Description
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('quantity')">
                Quantity
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('total_amount')">
                Total Amount
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('status')">
                Status
                <i class="fas fa-sort ml-1"></i>
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="transaction in transactions"
              :key="transaction.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ transaction.transaction_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span :class="getTypeBadgeClass(transaction.transaction_type)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ transaction.transaction_type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ transaction.formatted_transaction_date }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ transaction.sku_code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ transaction.location_code }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                {{ transaction.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                {{ transaction.quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                {{ formatCurrency(transaction.total_amount) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(transaction.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ transaction.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                <div class="flex space-x-2 justify-center">
                  <button
                    @click="viewTransaction(transaction)"
                    class="text-indigo-600 hover:text-indigo-900"
                    title="View Details"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button
                    @click="printTransaction(transaction)"
                    class="text-purple-600 hover:text-purple-900"
                    title="Print"
                  >
                    <i class="fas fa-print"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <label class="text-sm text-gray-700">Show:</label>
          <select
            v-model="perPage"
            @change="loadTransactions"
            class="border border-gray-300 rounded px-2 py-1 text-sm"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="text-sm text-gray-700">entries</span>
        </div>
        <div class="flex space-x-2">
          <button
            @click="changePage(paginationInfo.current_page - 1)"
            :disabled="paginationInfo.current_page <= 1"
            class="px-3 py-1 text-sm border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <span class="px-3 py-1 text-sm text-gray-700">
            Page {{ paginationInfo.current_page }} of {{ paginationInfo.last_page }}
          </span>
          <button
            @click="changePage(paginationInfo.current_page + 1)"
            :disabled="paginationInfo.current_page >= paginationInfo.last_page"
            class="px-3 py-1 text-sm border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()

// Reactive data
const currentTime = ref('')
const currentPeriod = ref('2024-01')
const currentUser = ref('Admin User')
const isPrinting = ref(false)
const transactions = ref([])
const perPage = ref(25)
const currentPage = ref(1)
const sortField = ref('transaction_date')
const sortDirection = ref('desc')

const filters = ref({
  transactionType: '',
  status: '',
  dateFrom: '',
  dateTo: '',
  search: ''
})

const paginationInfo = ref({
  current_page: 1,
  last_page: 1,
  per_page: 25,
  total: 0,
  from: 0,
  to: 0
})

const summary = ref({
  total: 0,
  posted: 0,
  draft: 0,
  cancelled: 0
})

// Computed properties
const hasData = computed(() => {
  return transactions.value.length > 0
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString()
}

const loadTransactions = async () => {
  try {
    let url = `/api/is-mi-mo-lt/transactions?page=${currentPage.value}&per_page=${perPage.value}&sort_field=${sortField.value}&sort_direction=${sortDirection.value}`
    
    if (filters.value.transactionType) url += `&transaction_type=${filters.value.transactionType}`
    if (filters.value.status) url += `&status=${filters.value.status}`
    if (filters.value.dateFrom) url += `&date_from=${filters.value.dateFrom}`
    if (filters.value.dateTo) url += `&date_to=${filters.value.dateTo}`
    if (filters.value.search) url += `&search=${filters.value.search}`

    const response = await fetch(url)
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        transactions.value = data.data.data
        paginationInfo.value = {
          current_page: data.data.current_page,
          last_page: data.data.last_page,
          per_page: data.data.per_page,
          total: data.data.total,
          from: data.data.from,
          to: data.data.to
        }
        summary.value = data.summary || {
          total: data.data.total,
          posted: 0,
          draft: 0,
          cancelled: 0
        }
      }
    }
  } catch (error) {
    toast.error('Error loading transactions')
  }
}

const clearFilters = () => {
  filters.value = {
    transactionType: '',
    status: '',
    dateFrom: '',
    dateTo: '',
    search: ''
  }
  currentPage.value = 1
  loadTransactions()
  toast.info('Filters cleared')
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  loadTransactions()
}

const changePage = (page) => {
  if (page >= 1 && page <= paginationInfo.value.last_page) {
    currentPage.value = page
    loadTransactions()
  }
}

const viewTransaction = (transaction) => {
  // Navigate to view page or show modal
  toast.info(`Viewing transaction ${transaction.transaction_number}`)
}

const printTransaction = (transaction) => {
  // Navigate to print page
  window.open(`/material-management/inventory-control/is-mi-mo-lt/print-${transaction.transaction_type.toLowerCase()}?transaction=${transaction.transaction_number}`, '_blank')
}

const printReport = async () => {
  if (!hasData.value) {
    toast.error('No data to print')
    return
  }

  isPrinting.value = true
  try {
    const response = await fetch('/api/is-mi-mo-lt/generate-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        report_type: 'print',
        filters: filters.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
      })
    })

    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        const printWindow = window.open('', '_blank')
        printWindow.document.write(data.html)
        printWindow.document.close()
        printWindow.print()
        
        toast.success('Report printed successfully')
      }
    } else {
      toast.error('Error generating print report')
    }
  } catch (error) {
    toast.error('Error printing report')
  } finally {
    isPrinting.value = false
  }
}

const exportToExcel = async () => {
  if (!hasData.value) {
    toast.error('No data to export')
    return
  }

  try {
    const response = await fetch('/api/is-mi-mo-lt/generate-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        report_type: 'excel',
        filters: filters.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `IS_MI_MO_LT_Log_${new Date().toISOString().split('T')[0]}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('Excel file exported successfully')
    } else {
      toast.error('Error generating Excel file')
    }
  } catch (error) {
    toast.error('Error exporting Excel file')
  }
}

const exportToPDF = async () => {
  if (!hasData.value) {
    toast.error('No data to export')
    return
  }

  try {
    const response = await fetch('/api/is-mi-mo-lt/generate-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        report_type: 'pdf',
        filters: filters.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `IS_MI_MO_LT_Log_${new Date().toISOString().split('T')[0]}.pdf`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('PDF file exported successfully')
    } else {
      toast.error('Error generating PDF file')
    }
  } catch (error) {
    toast.error('Error exporting PDF file')
  }
}

const getTypeBadgeClass = (type) => {
  switch (type) {
    case 'IS': return 'bg-blue-100 text-blue-800'
    case 'MI': return 'bg-purple-100 text-purple-800'
    case 'MO': return 'bg-green-100 text-green-800'
    case 'LT': return 'bg-orange-100 text-orange-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Draft': return 'bg-gray-100 text-gray-800'
    case 'Posted': return 'bg-green-100 text-green-800'
    case 'Cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
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

// Lifecycle hooks
onMounted(() => {
  updateCurrentTime()
  const interval = setInterval(updateCurrentTime, 1000)
  window.viewPrintLogInterval = interval
  
  loadTransactions()
})

onUnmounted(() => {
  if (window.viewPrintLogInterval) {
    clearInterval(window.viewPrintLogInterval)
  }
})
</script>
