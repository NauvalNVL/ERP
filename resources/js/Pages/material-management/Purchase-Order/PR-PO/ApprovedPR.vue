<template>
  <AppLayout :header="'Approved Purchase Requisitions'">
    <Head title="Approved Purchase Requisitions" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-check mr-3"></i> Approved Purchase Requisitions
      </h2>
      <p class="text-green-100">View and manage approved purchase requisition requests</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search approved PR by number or department..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="exportPRs" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-download mr-2"></i> Export
              </button>
              <button @click="bulkConvertToPO" class="btn-primary flex-1 md:flex-none" :disabled="selectedPRIds.length === 0">
                <i class="fas fa-shopping-cart mr-2"></i> Convert to PO ({{ selectedPRIds.length }})
              </button>
            </div>
          </div>
              
          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                  <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-green-600">Total Approved</h3>
                  <p class="text-2xl font-bold text-green-900">{{ approvedStats.total }}</p>
                </div>
              </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                  <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-blue-600">Converted to PO</h3>
                  <p class="text-2xl font-bold text-blue-900">{{ approvedStats.converted }}</p>
                </div>
              </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                  <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-yellow-600">Pending PO</h3>
                  <p class="text-2xl font-bold text-yellow-900">{{ approvedStats.pending }}</p>
                </div>
              </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                  <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-purple-600">Total Value</h3>
                  <p class="text-lg font-bold text-purple-900">{{ formatCurrency(approvedStats.totalValue) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      <input type="checkbox" @change="toggleSelectAll" :checked="selectedPRIds.length === paginatedPRs.length && paginatedPRs.length > 0">
                    </th>
                    <th @click="sortBy('pr_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        PR Number <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('department')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Department <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('approved_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Approved Date <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('po_converted')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        PO Status <i class="fas fa-sort ml-1"></i>
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
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading approved purchase requisitions...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPRs.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      No approved purchase requisitions found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="pr in paginatedPRs" :key="pr.id" 
                      @click="selectPR(pr)" 
                      :class="{'bg-blue-50': selectedPR && selectedPR.id === pr.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap" @click.stop>
                      <input type="checkbox" :value="pr.id" v-model="selectedPRIds" :disabled="pr.po_converted">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ pr.pr_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pr.department }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(pr.approved_date) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getPOStatusClass(pr.po_converted)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ pr.po_converted ? 'Converted to PO' : 'Not Converted' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatCurrency(pr.total_amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                  
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedPRs.length }} of {{ filteredPRs.length }} approved purchase requisitions</span>
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
          <div v-if="selectedPR" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> PR Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PR Number:</span>
                <span class="font-medium text-gray-900">{{ selectedPR.pr_number }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Department:</span>
                <span class="font-medium text-gray-900">{{ selectedPR.department }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Approved Date:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedPR.approved_date) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Approved By:</span>
                <span class="font-medium text-gray-900">{{ selectedPR.approved_by || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PO Status:</span>
                <span :class="getPOStatusClass(selectedPR.po_converted)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ selectedPR.po_converted ? 'Converted to PO' : 'Not Converted' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-medium text-gray-900">{{ formatCurrency(selectedPR.total_amount) }}</span>
              </div>
            </div>
            <div class="mt-6 space-y-2">
              <button @click="viewPRDetails(selectedPR)" class="w-full btn-blue">
                <i class="fas fa-eye mr-1"></i> View Details
              </button>
              <button v-if="!selectedPR.po_converted" @click="convertToPO(selectedPR)" class="w-full btn-primary">
                <i class="fas fa-shopping-cart mr-1"></i> Convert to PO
              </button>
              <button @click="printPR(selectedPR)" class="w-full btn-info">
                <i class="fas fa-print mr-1"></i> Print PR
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-file-check text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No PR Selected</h3>
            <p class="text-gray-500 mb-4">Select an approved purchase requisition from the table to view details</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

// State variables
const purchaseRequisitions = ref([])
const selectedPR = ref(null)
const selectedPRIds = ref([])
const loading = ref(false)
const searchQuery = ref('')
const sortOrder = ref({
  field: 'pr_number',
  direction: 'asc'
})
const toast = useToast()
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Computed properties
const filteredPRs = computed(() => {
  // Ensure purchaseRequisitions.value is always an array
  const prs = Array.isArray(purchaseRequisitions.value) ? purchaseRequisitions.value : []
  // Only show approved PRs
  let result = prs.filter(pr => pr.status === 'Approved')
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(pr =>
      (pr.pr_number && pr.pr_number.toLowerCase().includes(query)) ||
      (pr.department && pr.department.toLowerCase().includes(query)) ||
      (pr.purpose && pr.purpose.toLowerCase().includes(query))
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

const paginatedPRs = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredPRs.value.slice(start, end)
})

const totalPages = computed(() => {
  const total = filteredPRs.value.length
  if (total === 0) return 1
  return Math.ceil(total / itemsPerPage.value)
})

const approvedStats = computed(() => {
  // Ensure purchaseRequisitions.value is always an array
  const prs = Array.isArray(purchaseRequisitions.value) ? purchaseRequisitions.value : []
  const approvedPRs = prs.filter(pr => pr.status === 'Approved')
  const converted = approvedPRs.filter(pr => pr.po_converted).length
  const pending = approvedPRs.filter(pr => !pr.po_converted).length
  const totalValue = approvedPRs.reduce((sum, pr) => sum + (pr.total_amount || 0), 0)
  
  return {
    total: approvedPRs.length,
    converted,
    pending,
    totalValue
  }
})

// Watchers
watch(filteredPRs, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

watch(itemsPerPage, () => {
  currentPage.value = 1
})

// Methods
const fetchPRs = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/purchase-requisitions')
    const data = await response.json()
    purchaseRequisitions.value = data
  } catch (error) {
    console.error('Error fetching purchase requisitions:', error)
    toast.error('Failed to load purchase requisitions')
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  selectedPR.value = null
  selectedPRIds.value = []
  searchQuery.value = ''
  fetchPRs()
}

const selectPR = (pr) => {
  selectedPR.value = pr
}

const toggleSelectAll = () => {
  if (selectedPRIds.value.length === paginatedPRs.value.filter(pr => !pr.po_converted).length) {
    selectedPRIds.value = []
  } else {
    selectedPRIds.value = paginatedPRs.value.filter(pr => !pr.po_converted).map(pr => pr.id)
  }
}

const viewPRDetails = (pr) => {
  // Navigate to detailed view or open modal
  router.get(`/material-management/purchase-order/pr-po/view-print-pr?id=${pr.id}`)
}

const convertToPO = async (pr) => {
  try {
    await fetch(`/api/purchase-requisitions/${pr.id}/convert-to-po`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    toast.success('Purchase requisition converted to PO successfully')
    fetchPRs() // Refresh data to update PO status
    
  } catch (error) {
    console.error('Error converting to PO:', error)
    toast.error('Failed to convert PR to PO')
  }
}

const bulkConvertToPO = async () => {
  if (selectedPRIds.value.length === 0) {
    toast.error('Please select at least one PR to convert to PO')
    return
  }

  try {
    await fetch('/api/purchase-requisitions/bulk-convert-to-po', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        pr_ids: selectedPRIds.value
      })
    })

    toast.success(`${selectedPRIds.value.length} purchase requisitions converted to PO successfully`)
    selectedPRIds.value = []
    fetchPRs() // Refresh data
    
  } catch (error) {
    console.error('Error bulk converting to PO:', error)
    toast.error('Failed to bulk convert PRs to PO')
  }
}

const printPR = (pr) => {
  // Navigate to print page
  router.get(`/material-management/purchase-order/pr-po/print-pr?id=${pr.id}`)
}

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortOrder.value.field = field
    sortOrder.value.direction = 'asc'
  }
}

const exportPRs = () => {
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

const getPOStatusClass = (poConverted) => {
  return poConverted 
    ? 'bg-green-100 text-green-800' 
    : 'bg-yellow-100 text-yellow-800'
}

onMounted(() => {
  fetchPRs()
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