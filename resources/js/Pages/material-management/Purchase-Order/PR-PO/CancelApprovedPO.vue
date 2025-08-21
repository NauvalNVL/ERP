<template>
  <AppLayout :header="'Cancel Approved Purchase Order'">
    <Head title="Cancel Approved Purchase Order" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-excel mr-3"></i> Cancel Approved Purchase Order
      </h2>
      <p class="text-red-100">Cancel approved purchase orders that are no longer needed</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search approved PO by number or supplier..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="exportPOs" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-download mr-2"></i> Export
              </button>
            </div>
          </div>
              
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('po_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        PO Number <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('supplier')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Supplier <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('approved_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Approved Date <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('delivery_status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Delivery Status <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('total_amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Total Amount <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading approved purchase orders...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPOs.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No approved purchase orders found. Only approved POs without delivery can be cancelled.
                    </td>
                  </tr>
                  <tr v-for="po in paginatedPOs" :key="po.id" 
                      @click="selectPO(po)" 
                      :class="{'bg-blue-50': selectedPO && selectedPO.id === po.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ po.po_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ po.supplier }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(po.approved_date) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getDeliveryStatusClass(po.delivery_status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ po.delivery_status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatCurrency(po.total_amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                  
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedPOs.length }} of {{ filteredPOs.length }} approved purchase orders</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full lg:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedPO" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> PO Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PO Number:</span>
                <span class="font-medium text-gray-900">{{ selectedPO.po_number }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Supplier:</span>
                <span class="font-medium text-gray-900">{{ selectedPO.supplier }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Approved Date:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedPO.approved_date) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Delivery Status:</span>
                <span :class="getDeliveryStatusClass(selectedPO.delivery_status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ selectedPO.delivery_status }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-medium text-gray-900">{{ formatCurrency(selectedPO.total_amount) }}</span>
              </div>
            </div>
            <div class="mt-6 space-y-2">
              <button v-if="canCancelPO(selectedPO)" @click="cancelApprovedPO(selectedPO)" class="w-full btn-danger">
                <i class="fas fa-file-excel mr-1"></i> Cancel PO
              </button>
              <button v-else class="w-full btn-secondary cursor-not-allowed" disabled>
                <i class="fas fa-lock mr-1"></i> Cannot Cancel ({{ selectedPO.delivery_status }})
              </button>
              <button @click="viewPODetails(selectedPO)" class="w-full btn-blue">
                <i class="fas fa-eye mr-1"></i> View Details
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-file-excel text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No PO Selected</h3>
            <p class="text-gray-500 mb-4">Select an approved purchase order from the table to view details</p>
            <div class="text-sm text-gray-400">
              <p>Only approved POs without delivery can be cancelled</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Cancellation Confirmation Modal -->
    <div v-if="showCancelModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showCancelModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-red-600 to-red-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-exclamation-triangle text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Cancel Purchase Order</h3>
          </div>
          <button @click="showCancelModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Cancel Approved Purchase Order</h4>
            <p class="text-gray-600 mb-4">Are you sure you want to cancel PO: <strong>{{ selectedPO?.po_number }}</strong>?</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
              <p class="text-sm text-red-800">
                <i class="fas fa-info-circle mr-1"></i>
                This action cannot be undone. The PO will be marked as cancelled and removed from active orders.
              </p>
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason <span class="text-red-500">*</span></label>
            <textarea
              v-model="cancellationReason"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Please provide a reason for cancelling this PO..."
              required
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button @click="showCancelModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200">
              Keep PO
            </button>
            <button @click="confirmCancellation" :disabled="!cancellationReason.trim() || loading" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
              <span v-if="loading" class="mr-2"><i class="fas fa-spinner fa-spin"></i></span>
              Cancel PO
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

// State variables
const purchaseOrders = ref([])
const selectedPO = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const showCancelModal = ref(false)
const cancellationReason = ref('')
const sortOrder = ref({
  field: 'po_number',
  direction: 'asc'
})
const toast = useToast()
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Computed properties
const filteredPOs = computed(() => {
  // Only show approved POs that haven't been delivered
  let result = purchaseOrders.value.filter(po => 
    po.status === 'Approved' && po.delivery_status !== 'Delivered'
  )
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(po =>
      (po.po_number && po.po_number.toLowerCase().includes(query)) ||
      (po.supplier && po.supplier.toLowerCase().includes(query))
    )
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1
    
    const valA = a[field] || ''
    const valB = b[field] || ''

    if (valA < valB) return -1 * direction
    if (valA > valB) return 1 * direction
    return 0
  })

  return result
})

const paginatedPOs = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredPOs.value.slice(start, end)
})

const totalPages = computed(() => {
  const total = filteredPOs.value.length
  if (total === 0) return 1
  return Math.ceil(total / itemsPerPage.value)
})

// Watchers
watch(filteredPOs, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

watch(itemsPerPage, () => {
  currentPage.value = 1
})

// Methods
const fetchPOs = async () => {
  loading.value = true
  try {
    // Mock data - replace with actual API call
    const response = await fetch('/api/purchase-orders')
    const data = await response.json()
    purchaseOrders.value = data
  } catch (error) {
    console.error('Error fetching purchase orders:', error)
    // Mock data for demo
    purchaseOrders.value = [
      {
        id: 1,
        po_number: 'PO-2024-001',
        supplier: 'PT Supplier ABC',
        approved_date: '2024-01-15',
        delivery_status: 'Pending',
        total_amount: 15000000,
        status: 'Approved'
      },
      {
        id: 2,
        po_number: 'PO-2024-002',
        supplier: 'CV Supplier XYZ',
        approved_date: '2024-01-16',
        delivery_status: 'In Transit',
        total_amount: 8500000,
        status: 'Approved'
      },
      {
        id: 3,
        po_number: 'PO-2024-003',
        supplier: 'PT Supplier DEF',
        approved_date: '2024-01-17',
        delivery_status: 'Delivered',
        total_amount: 12000000,
        status: 'Approved'
      }
    ]
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  selectedPO.value = null
  searchQuery.value = ''
  fetchPOs()
}

const selectPO = (po) => {
  selectedPO.value = po
}

const canCancelPO = (po) => {
  return po.status === 'Approved' && po.delivery_status !== 'Delivered'
}

const cancelApprovedPO = (po) => {
  if (!canCancelPO(po)) {
    toast.error('This purchase order cannot be cancelled')
    return
  }
  
  selectedPO.value = po
  cancellationReason.value = ''
  showCancelModal.value = true
}

const confirmCancellation = async () => {
  if (!cancellationReason.value.trim()) {
    toast.error('Please provide a reason for the cancellation')
    return
  }

  loading.value = true
  try {
    await fetch(`/api/purchase-orders/${selectedPO.value.id}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        cancellation_reason: cancellationReason.value
      })
    })

    toast.success('Purchase order cancelled successfully')
    showCancelModal.value = false
    
    // Update the PO status in the local array
    const index = purchaseOrders.value.findIndex(po => po.id === selectedPO.value.id)
    if (index !== -1) {
      purchaseOrders.value[index].status = 'Cancelled'
    }
    
    selectedPO.value = null
    
  } catch (error) {
    console.error('Error cancelling purchase order:', error)
    toast.error('Failed to cancel purchase order')
  } finally {
    loading.value = false
  }
}

const viewPODetails = (po) => {
  // Navigate to detailed view
  window.open(`/material-management/purchase-order/view-po?id=${po.id}`, '_blank')
}

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortOrder.value.field = field
    sortOrder.value.direction = 'asc'
  }
}

const exportPOs = () => {
  // Implementation for export functionality
  toast.info('Export functionality coming soon')
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount || 0)
}

const getDeliveryStatusClass = (status) => {
  const statusClasses = {
    'Pending': 'bg-yellow-100 text-yellow-800',
    'In Transit': 'bg-blue-100 text-blue-800',
    'Delivered': 'bg-green-100 text-green-800',
    'Cancelled': 'bg-gray-100 text-gray-800'
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  fetchPOs()
})
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>