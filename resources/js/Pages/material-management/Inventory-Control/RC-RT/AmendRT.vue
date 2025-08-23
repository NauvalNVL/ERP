<template>
  <AppLayout :header="header">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
              <i class="fas fa-edit text-orange-500 mr-3"></i>
              Amend Return (RT)
            </h1>
            <p class="text-gray-600 mt-2">Search and amend return transactions</p>
          </div>
        </div>
      </div>

      <!-- Search Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-search text-blue-500 mr-2"></i>
          Search RT Transactions
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

      <!-- RT Transactions Table -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-table text-orange-500 mr-2"></i>
            RT Transactions
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(transaction.total_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.location_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusBadgeClass(transaction.status)">
                    {{ transaction.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    v-if="transaction.status === 'Draft'"
                    @click="editTransaction(transaction)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                    title="Edit"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button
                    v-if="transaction.status === 'Draft'"
                    @click="postTransaction(transaction)"
                    class="text-green-600 hover:text-green-900 mr-3"
                    title="Post"
                  >
                    <i class="fas fa-check"></i>
                  </button>
                  <button
                    v-if="transaction.status === 'Draft'"
                    @click="cancelTransaction(transaction)"
                    class="text-red-600 hover:text-red-900 mr-3"
                    title="Cancel"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                  <button
                    @click="viewTransaction(transaction)"
                    class="text-indigo-600 hover:text-indigo-900"
                    title="View"
                  >
                    <i class="fas fa-eye"></i>
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

      <!-- Edit RT Transaction Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-bold text-gray-800">Edit RT Transaction</h3>
              <button
                @click="showEditModal = false"
                class="text-gray-400 hover:text-gray-600"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
            
            <div v-if="editingTransaction" class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Transaction Number</label>
                  <input
                    type="text"
                    v-model="editingTransaction.transaction_number"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Transaction Date *</label>
                  <input
                    type="date"
                    v-model="editingTransaction.transaction_date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    required
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">PO Number</label>
                  <input
                    type="text"
                    v-model="editingTransaction.po_number"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Supplier *</label>
                  <div class="relative">
                    <input
                      type="text"
                      v-model="editingTransaction.supplier_name"
                      readonly
                      @click="showSupplierModal = true"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                      required
                    />
                    <button
                      type="button"
                      @click="showSupplierModal = true"
                      class="absolute right-2 top-2 text-gray-400 hover:text-gray-600"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">SKU *</label>
                  <div class="relative">
                    <input
                      type="text"
                      v-model="editingTransaction.sku_name"
                      readonly
                      @click="showSkuModal = true"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                      required
                    />
                    <button
                      type="button"
                      @click="showSkuModal = true"
                      class="absolute right-2 top-2 text-gray-400 hover:text-gray-600"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                  <input
                    type="number"
                    v-model="editingTransaction.quantity"
                    min="0"
                    step="0.01"
                    @input="calculateEditTotal"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    required
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Unit Price *</label>
                  <input
                    type="number"
                    v-model="editingTransaction.unit_price"
                    min="0"
                    step="0.01"
                    @input="calculateEditTotal"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    required
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                  <input
                    type="text"
                    :value="formatCurrency(editingTransaction.total_amount)"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Location *</label>
                  <div class="relative">
                    <input
                      type="text"
                      v-model="editingTransaction.location_name"
                      readonly
                      @click="showLocationModal = true"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                      required
                    />
                    <button
                      type="button"
                      @click="showLocationModal = true"
                      class="absolute right-2 top-2 text-gray-400 hover:text-gray-600"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700">Remarks</label>
                <textarea
                  v-model="editingTransaction.remarks"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                ></textarea>
              </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
              <button
                @click="updateTransaction"
                :disabled="isUpdating"
                class="bg-orange-600 hover:bg-orange-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
              >
                <i class="fas fa-save mr-2" v-if="!isUpdating"></i>
                <i class="fas fa-spinner fa-spin mr-2" v-else></i>
                {{ isUpdating ? 'Updating...' : 'Update RT' }}
              </button>
              <button
                @click="showEditModal = false"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Cancel Transaction Modal -->
      <div v-if="showCancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-bold text-gray-800">Cancel Transaction</h3>
              <button
                @click="showCancelModal = false"
                class="text-gray-400 hover:text-gray-600"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
            
            <div class="space-y-4">
              <p class="text-gray-700">Are you sure you want to cancel this transaction?</p>
              <p class="text-sm text-gray-600">Transaction: <strong>{{ cancellingTransaction?.transaction_number }}</strong></p>
              
              <div>
                <label class="block text-sm font-medium text-gray-700">Cancellation Reason *</label>
                <textarea
                  v-model="cancellationReason"
                  rows="3"
                  placeholder="Enter cancellation reason..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                  required
                ></textarea>
              </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
              <button
                @click="confirmCancel"
                :disabled="!cancellationReason || isCancelling"
                class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
              >
                <i class="fas fa-times mr-2" v-if="!isCancelling"></i>
                <i class="fas fa-spinner fa-spin mr-2" v-else></i>
                {{ isCancelling ? 'Cancelling...' : 'Cancel Transaction' }}
              </button>
              <button
                @click="showCancelModal = false"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
              >
                No, Keep
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Supplier Lookup Modal -->
      <VendorProfileModal
        v-if="showSupplierModal"
        @close="showSupplierModal = false"
        @select="selectSupplier"
      />

      <!-- SKU Lookup Modal -->
      <SkuLookupModal
        v-if="showSkuModal"
        @close="showSkuModal = false"
        @select="selectSku"
      />

      <!-- Location Lookup Modal -->
          <LocationTableModal
      v-if="showLocationModal"
      @close="showLocationModal = false"
      @select="selectLocation"
    />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import LocationTableModal from '@/Components/LocationTableModal.vue'
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
  status: ''
})

const transactions = ref([])
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 25,
  total: 0,
  from: 0,
  to: 0
})

const editingTransaction = ref(null)
const cancellingTransaction = ref(null)
const cancellationReason = ref('')

const showEditModal = ref(false)
const showCancelModal = ref(false)
const showSupplierModal = ref(false)
const showSkuModal = ref(false)
const showLocationModal = ref(false)

const isLoading = ref(false)
const isUpdating = ref(false)
const isCancelling = ref(false)

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
    status: ''
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

const editTransaction = (transaction) => {
  editingTransaction.value = { ...transaction }
  showEditModal.value = true
}

const viewTransaction = (transaction) => {
  // Navigate to View & Print RT Log page with transaction ID
  window.location.href = `/material-management/inventory-control/rc-rt/view-print-rt-log?id=${transaction.id}`
}

const postTransaction = async (transaction) => {
  try {
    const response = await axios.post(`/api/rc-rt/transactions/${transaction.id}/post`)
    
    if (response.data.success) {
      toast.success('Transaction posted successfully!')
      loadTransactions()
    } else {
      toast.error(response.data.message || 'Failed to post transaction')
    }
  } catch (error) {
    console.error('Error posting transaction:', error)
    toast.error(error.response?.data?.message || 'Error posting transaction')
  }
}

const cancelTransaction = (transaction) => {
  cancellingTransaction.value = transaction
  cancellationReason.value = ''
  showCancelModal.value = true
}

const confirmCancel = async () => {
  if (!cancellationReason.value) {
    toast.error('Please enter cancellation reason')
    return
  }

  try {
    isCancelling.value = true
    
    const response = await axios.post(`/api/rc-rt/transactions/${cancellingTransaction.value.id}/cancel`, {
      cancellation_reason: cancellationReason.value
    })
    
    if (response.data.success) {
      toast.success('Transaction cancelled successfully!')
      showCancelModal.value = false
      loadTransactions()
    } else {
      toast.error(response.data.message || 'Failed to cancel transaction')
    }
  } catch (error) {
    console.error('Error cancelling transaction:', error)
    toast.error(error.response?.data?.message || 'Error cancelling transaction')
  } finally {
    isCancelling.value = false
  }
}

const calculateEditTotal = () => {
  const quantity = parseFloat(editingTransaction.value.quantity) || 0
  const unitPrice = parseFloat(editingTransaction.value.unit_price) || 0
  editingTransaction.value.total_amount = quantity * unitPrice
}

const selectSupplier = (supplier) => {
  editingTransaction.value.supplier_code = supplier.ap_ac_number
  editingTransaction.value.supplier_name = supplier.vendor_name
  showSupplierModal.value = false
}

const selectSku = (sku) => {
  editingTransaction.value.sku_code = sku.sku
  editingTransaction.value.sku_name = sku.description
  showSkuModal.value = false
}

const selectLocation = (location) => {
  editingTransaction.value.location_code = location.code
  editingTransaction.value.location_name = location.name
  showLocationModal.value = false
}

const updateTransaction = async () => {
  try {
    isUpdating.value = true
    
    const response = await axios.put(`/api/rc-rt/transactions/${editingTransaction.value.id}`, {
      transaction_type: 'RT',
      ...editingTransaction.value
    })

    if (response.data.success) {
      toast.success('RT transaction updated successfully!')
      showEditModal.value = false
      loadTransactions()
    } else {
      toast.error(response.data.message || 'Failed to update RT transaction')
    }
  } catch (error) {
    console.error('Error updating RT transaction:', error)
    toast.error(error.response?.data?.message || 'Error updating RT transaction')
  } finally {
    isUpdating.value = false
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
</style>
