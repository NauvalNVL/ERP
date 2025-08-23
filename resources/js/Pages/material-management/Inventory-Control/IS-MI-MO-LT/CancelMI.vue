<template>
  <AppLayout :header="'Cancel MI (Material Issue)'">
    <Head title="Cancel MI (Material Issue)" />
    
    <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-times-circle mr-4 text-red-200"></i>
              Cancel MI (Material Issue)
            </h1>
            <p class="text-red-100 text-xl font-medium">Cancel material issue transactions</p>
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
              @click="cancelTransaction"
              :disabled="!selectedTransaction || isCancelling"
              class="bg-red-500 hover:bg-red-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-times mr-3" v-if="!isCancelling"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isCancelling ? 'Cancelling...' : 'Cancel MI' }}
            </button>
            <button
              @click="clearSelection"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-eraser mr-3"></i>
              Clear
            </button>
            <button
              @click="printTransaction"
              :disabled="!selectedTransaction"
              class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-print mr-3"></i>
              Print
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
            <i class="fas fa-list mr-3 text-red-600"></i>
            Available MI Transactions
          </h2>

          <!-- Search and Filters -->
          <div class="mb-6 space-y-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Search</label>
              <input
                v-model="searchQuery"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Search by number, SKU, or description"
              />
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Status Filter</label>
              <select
                v-model="statusFilter"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              >
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Posted">Posted</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">From Date</label>
                <input
                  v-model="dateFrom"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">To Date</label>
                <input
                  v-model="dateTo"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>
            </div>

            <button
              @click="loadTransactions"
              class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
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
                  ? 'border-red-500 bg-red-50'
                  : 'border-gray-200 hover:border-red-300 hover:bg-red-50'
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

      <!-- Transaction Details -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-info-circle mr-3 text-red-600"></i>
            Transaction Details
          </h2>

          <div v-if="selectedTransaction" class="space-y-6">
            <!-- Transaction Header -->
            <div class="bg-gray-50 rounded-lg p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                  <div class="text-lg font-semibold text-gray-800">{{ selectedTransaction.formatted_transaction_date }}</div>
                </div>
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-1">Total Amount</label>
                  <div class="text-lg font-semibold text-gray-800">{{ formatCurrency(selectedTransaction.total_amount) }}</div>
                </div>
              </div>
            </div>

            <!-- Transaction Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">SKU Code</label>
                <input
                  :value="selectedTransaction.sku_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Location Code</label>
                <input
                  :value="selectedTransaction.location_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Category Code</label>
                <input
                  :value="selectedTransaction.category_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Reference Number</label>
                <input
                  :value="selectedTransaction.reference_number"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Quantity</label>
                <input
                  :value="selectedTransaction.quantity"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Unit Price</label>
                <input
                  :value="formatCurrency(selectedTransaction.unit_price)"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                  readonly
                />
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
              <textarea
                :value="selectedTransaction.description"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              ></textarea>
            </div>

            <!-- Notes -->
            <div v-if="selectedTransaction.notes">
              <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
              <textarea
                :value="selectedTransaction.notes"
                rows="2"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              ></textarea>
            </div>

            <!-- Cancellation Reason -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Cancellation Reason *
              </label>
              <textarea
                v-model="cancellationReason"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Enter reason for cancellation"
                required
              ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 pt-6 border-t border-gray-200">
              <button
                @click="cancelTransaction"
                :disabled="!cancellationReason || isCancelling"
                class="bg-red-500 hover:bg-red-600 disabled:bg-gray-500 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center"
              >
                <i class="fas fa-times mr-2" v-if="!isCancelling"></i>
                <i class="fas fa-spinner fa-spin mr-2" v-else></i>
                {{ isCancelling ? 'Cancelling...' : 'Cancel Transaction' }}
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
            <p class="text-gray-500">Please select a transaction from the list to view details and cancel it.</p>
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
const isCancelling = ref(false)
const selectedTransaction = ref(null)
const cancellationReason = ref('')
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
  cancellationReason.value = ''
  toast.info('Transaction selected')
}

const clearSelection = () => {
  selectedTransaction.value = null
  cancellationReason.value = ''
  toast.info('Selection cleared')
}

const cancelTransaction = async () => {
  if (!selectedTransaction.value) {
    toast.error('Please select a transaction to cancel')
    return
  }

  if (!cancellationReason.value.trim()) {
    toast.error('Please enter a cancellation reason')
    return
  }

  // Show confirmation dialog
  const result = await Swal.fire({
    title: 'Confirm Cancellation',
    text: `Are you sure you want to cancel transaction ${selectedTransaction.value.transaction_number}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, Cancel It!',
    cancelButtonText: 'No, Keep It'
  })

  if (result.isConfirmed) {
    isCancelling.value = true
    try {
      const response = await fetch(`/api/is-mi-mo-lt/transactions/${selectedTransaction.value.id}/cancel`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          cancellation_reason: cancellationReason.value
        })
      })

      if (response.ok) {
        const data = await response.json()
        if (data.success) {
          toast.success('Transaction cancelled successfully')
          
          // Show success modal
          Swal.fire({
            icon: 'success',
            title: 'Cancelled!',
            text: 'The transaction has been cancelled successfully.',
            confirmButtonColor: '#10B981',
            confirmButtonText: 'OK'
          })

          clearSelection()
          loadTransactions()
        }
      } else {
        const error = await response.json()
        toast.error(error.message || 'Error cancelling transaction')
      }
    } catch (error) {
      toast.error('Error cancelling transaction')
    } finally {
      isCancelling.value = false
    }
  }
}

const printTransaction = () => {
  if (!selectedTransaction.value) {
    toast.warning('Please select a transaction first')
    return
  }
  
  // Navigate to print route
  window.open(`/material-management/inventory-control/is-mi-mo-lt/print-mi?transaction=${selectedTransaction.value.transaction_number}`, '_blank')
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
  window.cancelMiInterval = interval
  
  loadTransactions()
})

onUnmounted(() => {
  if (window.cancelMiInterval) {
    clearInterval(window.cancelMiInterval)
  }
})
</script>
