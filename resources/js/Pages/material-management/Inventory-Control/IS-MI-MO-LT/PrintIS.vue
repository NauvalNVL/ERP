<template>
  <AppLayout :header="'Print IS (Inventory Setup)'">
    <Head title="Print IS (Inventory Setup)" />
    
    <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-print mr-4 text-purple-200"></i>
              Print IS (Inventory Setup)
            </h1>
            <p class="text-purple-100 text-xl font-medium">Print inventory setup transactions</p>
            <div class="flex items-center mt-4 space-x-6">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-purple-200 mr-2"></i>
                <span class="text-sm">Period: {{ currentPeriod }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-user-circle text-purple-200 mr-2"></i>
                <span class="text-sm">User: {{ currentUser }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-clock text-purple-200 mr-2"></i>
                <span class="text-sm">{{ currentTime }}</span>
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              @click="printSelected"
              :disabled="!selectedTransaction || isPrinting"
              class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-print mr-3" v-if="!isPrinting"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isPrinting ? 'Printing...' : 'Print IS' }}
            </button>
            <button
              @click="clearSelection"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-eraser mr-3"></i>
              Clear
            </button>
            <button
              @click="exportToPDF"
              :disabled="!selectedTransaction"
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
        <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-medium">
          Print Mode
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Available Transactions -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-list mr-3 text-purple-600"></i>
            Available IS Transactions
          </h2>

          <!-- Search and Filters -->
          <div class="mb-6 space-y-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Search
              </label>
              <input
                v-model="searchQuery"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="Search by number, SKU, or description"
              />
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Status Filter
              </label>
              <select
                v-model="statusFilter"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              >
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Date Range
              </label>
              <div class="grid grid-cols-2 gap-2">
                <input
                  v-model="dateFrom"
                  type="date"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
                <input
                  v-model="dateTo"
                  type="date"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
            </div>
          </div>

          <!-- Transactions List -->
          <div class="space-y-3 max-h-96 overflow-y-auto">
            <div
              v-for="transaction in filteredTransactions"
              :key="transaction.id"
              class="p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all"
              :class="{ 'border-purple-500 bg-purple-50': selectedTransaction?.id === transaction.id }"
              @click="selectTransaction(transaction)"
            >
              <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-gray-800">{{ transaction.transaction_number }}</div>
                <span :class="getStatusBadgeClass(transaction.status)" class="text-xs px-2 py-1 rounded-full">
                  {{ transaction.status }}
                </span>
              </div>
              <div class="text-sm text-gray-600 mb-1">{{ transaction.description }}</div>
              <div class="text-xs text-gray-500">
                {{ formatCurrency(transaction.total_amount) }} | {{ transaction.formatted_transaction_date }}
              </div>
            </div>
          </div>

          <!-- Pagination Info -->
          <div class="mt-4 text-sm text-gray-600">
            {{ paginationInfo }}
          </div>
        </div>
      </div>

      <!-- Print Preview -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-eye mr-3 text-purple-600"></i>
            Print Preview
          </h2>

          <div v-if="selectedTransaction" class="space-y-6">
            <!-- Print Header -->
            <div class="bg-gray-50 rounded-lg p-6 border-2 border-gray-200">
              <div class="text-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">INVENTORY SETUP TRANSACTION</h3>
                <p class="text-gray-600">Transaction Details</p>
              </div>
              
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-1">Transaction Number</label>
                  <div class="text-lg font-semibold text-gray-800">{{ selectedTransaction.transaction_number }}</div>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                  <span :class="getStatusBadgeClass(selectedTransaction.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                    {{ selectedTransaction.status }}
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-1">Transaction Date</label>
                  <div class="text-gray-800">{{ selectedTransaction.formatted_transaction_date }}</div>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-1">Reference Number</label>
                  <div class="text-gray-800">{{ selectedTransaction.reference_number || '-' }}</div>
                </div>
              </div>
            </div>

            <!-- Transaction Details Table -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <table class="w-full">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 border-b">Field</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 border-b">Value</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">SKU Code</td>
                    <td class="px-4 py-3 text-sm text-gray-800">{{ selectedTransaction.sku_code }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">Location Code</td>
                    <td class="px-4 py-3 text-sm text-gray-800">{{ selectedTransaction.location_code }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">Category Code</td>
                    <td class="px-4 py-3 text-sm text-gray-800">{{ selectedTransaction.category_code }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">Quantity</td>
                    <td class="px-4 py-3 text-sm text-gray-800">{{ selectedTransaction.formatted_quantity }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">Unit Price</td>
                    <td class="px-4 py-3 text-sm text-gray-800">{{ formatCurrency(selectedTransaction.unit_price) }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">Total Amount</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-800">{{ formatCurrency(selectedTransaction.total_amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 rounded-lg p-4">
              <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
              <div class="text-gray-800">{{ selectedTransaction.description }}</div>
            </div>

            <!-- Notes -->
            <div v-if="selectedTransaction.notes" class="bg-gray-50 rounded-lg p-4">
              <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
              <div class="text-gray-800">{{ selectedTransaction.notes }}</div>
            </div>

            <!-- Print Options -->
            <div class="bg-blue-50 rounded-lg p-4">
              <h4 class="text-lg font-bold text-blue-800 mb-3">Print Options</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Paper Size</label>
                  <select v-model="printOptions.paperSize" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="A4">A4</option>
                    <option value="Letter">Letter</option>
                    <option value="Legal">Legal</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Orientation</label>
                  <select v-model="printOptions.orientation" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- No Selection Message -->
          <div v-else class="text-center py-12">
            <i class="fas fa-hand-pointer text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg">Please select a transaction to print</p>
          </div>
        </div>
      </div>
    </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import Swal from 'sweetalert2'

const toast = useToast()

// Reactive data
const currentTime = ref('')
const currentPeriod = ref('2024-01')
const currentUser = ref('Admin User')
const lastUpdated = ref('')
const isPrinting = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const selectedTransaction = ref(null)
const transactions = ref([])
const currentPage = ref(1)
const totalPages = ref(1)

const printOptions = ref({
  paperSize: 'A4',
  orientation: 'portrait'
})

// Computed properties
const filteredTransactions = computed(() => {
  let filtered = transactions.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(transaction => 
      transaction.transaction_number.toLowerCase().includes(query) ||
      transaction.sku_code.toLowerCase().includes(query) ||
      transaction.description.toLowerCase().includes(query)
    )
  }

  if (statusFilter.value) {
    filtered = filtered.filter(transaction => transaction.status === statusFilter.value)
  }

  if (dateFrom.value) {
    filtered = filtered.filter(transaction => transaction.transaction_date >= dateFrom.value)
  }

  if (dateTo.value) {
    filtered = filtered.filter(transaction => transaction.transaction_date <= dateTo.value)
  }

  return filtered
})

const paginationInfo = computed(() => {
  return `Showing ${filteredTransactions.value.length} of ${transactions.value.length} transactions`
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString()
  lastUpdated.value = now.toLocaleString()
}

const loadTransactions = async () => {
  try {
    const response = await fetch('/api/is-mi-mo-lt/is-transactions?per_page=50')
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        transactions.value = data.data.data
        totalPages.value = data.data.last_page
      }
    }
  } catch (error) {
    console.error('Error loading transactions:', error)
    toast.error('Error loading transactions')
  }
}

const selectTransaction = (transaction) => {
  selectedTransaction.value = transaction
  toast.info('Transaction selected for printing')
}

const clearSelection = () => {
  selectedTransaction.value = null
  toast.info('Selection cleared')
}

const printSelected = async () => {
  if (!selectedTransaction.value) {
    toast.error('Please select a transaction to print')
    return
  }

  isPrinting.value = true
  try {
    // Simulate printing process
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    toast.success('Print job sent successfully')
    
    // Show success modal
    Swal.fire({
      icon: 'success',
      title: 'Print Job Sent!',
      text: 'The IS transaction has been sent to the printer successfully.',
      confirmButtonColor: '#8B5CF6',
      confirmButtonText: 'OK'
    })
  } catch (error) {
    toast.error('Error sending print job')
  } finally {
    isPrinting.value = false
  }
}

const exportToPDF = () => {
  if (!selectedTransaction.value) {
    toast.error('Please select a transaction to export')
    return
  }

  // Simulate PDF export
  toast.info('PDF export started')
  
  // Show success modal
  Swal.fire({
    icon: 'success',
    title: 'PDF Export Complete!',
    text: 'The IS transaction has been exported to PDF successfully.',
    confirmButtonColor: '#EF4444',
    confirmButtonText: 'OK'
  })
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
  window.printIsInterval = interval
  
  loadTransactions()
  
  // Set default date range to current month
  const now = new Date()
  const firstDay = new Date(now.getFullYear(), now.getMonth(), 1)
  const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0)
  
  dateFrom.value = firstDay.toISOString().split('T')[0]
  dateTo.value = lastDay.toISOString().split('T')[0]
})

onUnmounted(() => {
  if (window.printIsInterval) {
    clearInterval(window.printIsInterval)
  }
})
</script>
