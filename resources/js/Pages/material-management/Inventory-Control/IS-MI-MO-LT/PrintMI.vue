<template>
  <AppLayout :header="'Print MI (Material Issue)'">
    <Head title="Print MI (Material Issue)" />
    
    <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-print mr-4 text-purple-200"></i>
              Print MI (Material Issue)
            </h1>
            <p class="text-purple-100 text-xl font-medium">Print material issue transactions</p>
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
              {{ isPrinting ? 'Printing...' : 'Print MI' }}
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
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
          Ready to Process
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Available Transactions -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-list mr-3 text-purple-600"></i>
            Available MI Transactions
          </h2>

          <!-- Search and Filters -->
          <div class="mb-6 space-y-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Search</label>
              <input
                v-model="searchQuery"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="Search by number, SKU, or description"
              />
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Status Filter</label>
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

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">From Date</label>
                <input
                  v-model="dateFrom"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">To Date</label>
                <input
                  v-model="dateTo"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
            </div>

            <button
              @click="loadTransactions"
              class="w-full bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
            >
              <i class="fas fa-search mr-2"></i>
              Search Transactions
            </button>
          </div>

          <!-- Transactions List -->
          <div class="space-y-3 max-h-96 overflow-y-auto">
            <div
              v-for="transaction in filteredTransactions"
              :key="transaction.id"
              @click="selectTransaction(transaction)"
              :class="[
                'p-4 border rounded-lg cursor-pointer transition-all duration-200',
                selectedTransaction?.id === transaction.id
                  ? 'border-purple-500 bg-purple-50'
                  : 'border-gray-200 hover:border-purple-300 hover:bg-purple-50'
              ]"
            >
              <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-sm text-gray-800">{{ transaction.transaction_number }}</div>
                <span :class="getStatusBadgeClass(transaction.status)" class="text-xs px-2 py-1 rounded-full">
                  {{ transaction.status }}
                </span>
              </div>
              <div class="text-xs text-gray-600 mb-1">{{ transaction.sku_code }}</div>
              <div class="text-xs text-gray-600 mb-2">{{ transaction.description }}</div>
              <div class="flex justify-between items-center">
                <span class="text-xs font-medium text-gray-700">{{ formatCurrency(transaction.total_amount) }}</span>
                <span class="text-xs text-gray-500">{{ transaction.formatted_transaction_date }}</span>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
              Showing {{ paginationInfo.from }} to {{ paginationInfo.to }} of {{ paginationInfo.total }} results
            </div>
            <div class="flex space-x-2">
              <button
                @click="changePage(paginationInfo.current_page - 1)"
                :disabled="paginationInfo.current_page <= 1"
                class="px-3 py-1 text-sm border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Previous
              </button>
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

      <!-- Print Preview -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-eye mr-3 text-purple-600"></i>
            Print Preview
          </h2>

          <div v-if="selectedTransaction" class="space-y-6">
            <!-- Print Options -->
            <div class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Print Options</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Paper Size</label>
                  <select
                    v-model="printOptions.paperSize"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  >
                    <option value="A4">A4</option>
                    <option value="Letter">Letter</option>
                    <option value="Legal">Legal</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Orientation</label>
                  <select
                    v-model="printOptions.orientation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  >
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Print Preview Content -->
            <div class="border border-gray-300 rounded-lg p-8 bg-white" :style="printPreviewStyle">
              <!-- Header -->
              <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">MATERIAL ISSUE TRANSACTION</h1>
                <div class="text-lg text-gray-600">{{ selectedTransaction.transaction_number }}</div>
              </div>

              <!-- Transaction Info -->
              <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                  <table class="w-full">
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Transaction Number:</td>
                      <td class="py-1">{{ selectedTransaction.transaction_number }}</td>
                    </tr>
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Transaction Date:</td>
                      <td class="py-1">{{ selectedTransaction.formatted_transaction_date }}</td>
                    </tr>
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Status:</td>
                      <td class="py-1">
                        <span :class="getStatusBadgeClass(selectedTransaction.status)" class="px-2 py-1 rounded text-sm">
                          {{ selectedTransaction.status }}
                        </span>
                      </td>
                    </tr>
                  </table>
                </div>
                <div>
                  <table class="w-full">
                    <tr>
                      <td class="font-bold text-gray-700 py-1">SKU Code:</td>
                      <td class="py-1">{{ selectedTransaction.sku_code }}</td>
                    </tr>
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Location Code:</td>
                      <td class="py-1">{{ selectedTransaction.location_code }}</td>
                    </tr>
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Category Code:</td>
                      <td class="py-1">{{ selectedTransaction.category_code }}</td>
                    </tr>
                  </table>
                </div>
              </div>

              <!-- Transaction Details Table -->
              <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Transaction Details</h3>
                <table class="w-full border border-gray-300">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border border-gray-300 px-4 py-2 text-left font-bold">Item</th>
                      <th class="border border-gray-300 px-4 py-2 text-left font-bold">Description</th>
                      <th class="border border-gray-300 px-4 py-2 text-right font-bold">Quantity</th>
                      <th class="border border-gray-300 px-4 py-2 text-right font-bold">Unit Price</th>
                      <th class="border border-gray-300 px-4 py-2 text-right font-bold">Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="border border-gray-300 px-4 py-2">{{ selectedTransaction.sku_code }}</td>
                      <td class="border border-gray-300 px-4 py-2">{{ selectedTransaction.description }}</td>
                      <td class="border border-gray-300 px-4 py-2 text-right">{{ selectedTransaction.quantity }}</td>
                      <td class="border border-gray-300 px-4 py-2 text-right">{{ formatCurrency(selectedTransaction.unit_price) }}</td>
                      <td class="border border-gray-300 px-4 py-2 text-right font-bold">{{ formatCurrency(selectedTransaction.total_amount) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Additional Information -->
              <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                  <h4 class="font-bold text-gray-700 mb-2">Reference Information</h4>
                  <table class="w-full">
                    <tr>
                      <td class="font-bold text-gray-700 py-1">Reference Number:</td>
                      <td class="py-1">{{ selectedTransaction.reference_number || 'N/A' }}</td>
                    </tr>
                  </table>
                </div>
                <div>
                  <h4 class="font-bold text-gray-700 mb-2">Notes</h4>
                  <div class="text-gray-600">{{ selectedTransaction.notes || 'No notes available' }}</div>
                </div>
              </div>

              <!-- Footer -->
              <div class="border-t border-gray-300 pt-4 mt-8">
                <div class="grid grid-cols-3 gap-4 text-center text-sm text-gray-600">
                  <div>
                    <div class="font-bold mb-2">Prepared By</div>
                    <div class="border-t border-gray-300 pt-2">_________________</div>
                  </div>
                  <div>
                    <div class="font-bold mb-2">Approved By</div>
                    <div class="border-t border-gray-300 pt-2">_________________</div>
                  </div>
                  <div>
                    <div class="font-bold mb-2">Received By</div>
                    <div class="border-t border-gray-300 pt-2">_________________</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 pt-6 border-t border-gray-200">
              <button
                @click="printSelected"
                :disabled="isPrinting"
                class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center"
              >
                <i class="fas fa-print mr-2" v-if="!isPrinting"></i>
                <i class="fas fa-spinner fa-spin mr-2" v-else></i>
                {{ isPrinting ? 'Printing...' : 'Print Transaction' }}
              </button>
              <button
                @click="exportToPDF"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center"
              >
                <i class="fas fa-file-pdf mr-2"></i>
                Export to PDF
              </button>
              <button
                @click="clearSelection"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center"
              >
                <i class="fas fa-eraser mr-2"></i>
                Clear Selection
              </button>
            </div>
          </div>

          <!-- No Selection Message -->
          <div v-else class="text-center py-12">
            <i class="fas fa-hand-pointer text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Transaction Selected</h3>
            <p class="text-gray-500">Please select a transaction from the list to view print preview.</p>
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
const isPrinting = ref(false)
const selectedTransaction = ref(null)
const transactions = ref([])
const searchQuery = ref('')
const statusFilter = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const currentPage = ref(1)
const paginationInfo = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0
})

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

  return filtered
})

const printPreviewStyle = computed(() => {
  const styles = {
    maxWidth: '100%',
    margin: '0 auto'
  }
  
  if (printOptions.value.paperSize === 'A4') {
    styles.width = '210mm'
    styles.minHeight = '297mm'
  } else if (printOptions.value.paperSize === 'Letter') {
    styles.width = '216mm'
    styles.minHeight = '279mm'
  } else if (printOptions.value.paperSize === 'Legal') {
    styles.width = '216mm'
    styles.minHeight = '356mm'
  }
  
  return styles
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString()
}

const loadTransactions = async () => {
  try {
    let url = `/api/is-mi-mo-lt/mi-transactions?page=${currentPage.value}&per_page=10`
    
    if (dateFrom.value) url += `&date_from=${dateFrom.value}`
    if (dateTo.value) url += `&date_to=${dateTo.value}`
    if (statusFilter.value) url += `&status=${statusFilter.value}`
    if (searchQuery.value) url += `&search=${searchQuery.value}`

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
      }
    }
  } catch (error) {
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
    // Show confirmation dialog
    const result = await Swal.fire({
      title: 'Confirm Print',
      text: `Print transaction ${selectedTransaction.value.transaction_number}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#8B5CF6',
      cancelButtonColor: '#6B7280',
      confirmButtonText: 'Yes, Print It!',
      cancelButtonText: 'Cancel'
    })

    if (result.isConfirmed) {
      // Generate print report
      const response = await fetch('/api/is-mi-mo-lt/generate-report', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          transaction_id: selectedTransaction.value.id,
          report_type: 'print',
          paper_size: printOptions.value.paperSize,
          orientation: printOptions.value.orientation
        })
      })

      if (response.ok) {
        const data = await response.json()
        if (data.success) {
          // Open print window
          const printWindow = window.open('', '_blank')
          printWindow.document.write(data.html)
          printWindow.document.close()
          printWindow.print()
          
          toast.success('Print job sent successfully')
        }
      } else {
        toast.error('Error generating print report')
      }
    }
  } catch (error) {
    toast.error('Error printing transaction')
  } finally {
    isPrinting.value = false
  }
}

const exportToPDF = async () => {
  if (!selectedTransaction.value) {
    toast.error('Please select a transaction to export')
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
        transaction_id: selectedTransaction.value.id,
        report_type: 'pdf',
        paper_size: printOptions.value.paperSize,
        orientation: printOptions.value.orientation
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `MI_${selectedTransaction.value.transaction_number}.pdf`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('PDF exported successfully')
    } else {
      toast.error('Error generating PDF')
    }
  } catch (error) {
    toast.error('Error exporting PDF')
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= paginationInfo.value.last_page) {
    currentPage.value = page
    loadTransactions()
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
  window.printMiInterval = interval
  
  loadTransactions()
})

onUnmounted(() => {
  if (window.printMiInterval) {
    clearInterval(window.printMiInterval)
  }
})
</script>
