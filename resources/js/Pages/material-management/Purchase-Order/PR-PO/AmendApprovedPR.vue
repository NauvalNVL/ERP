<template>
  <AppLayout :header="'Amend Approved PR + Re-Submit'">
    <Head title="Amend Approved PR + Re-Submit" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-signature mr-3"></i> Amend Approved PR + Re-Submit
      </h2>
      <p class="text-orange-100">Modify approved purchase requisitions and re-submit for approval</p>
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
            </div>
          </div>
              
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
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
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading approved purchase requisitions...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPRs.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No approved purchase requisitions found. Only approved PRs that haven't been converted to PO can be amended.
                    </td>
                  </tr>
                  <tr v-for="pr in paginatedPRs" :key="pr.id" 
                      @click="selectPR(pr)" 
                      :class="{'bg-blue-50': selectedPR && selectedPR.id === pr.id}"
                      class="hover:bg-gray-50 cursor-pointer">
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
              <button v-if="canAmendApprovedPR(selectedPR)" @click="amendApprovedPR(selectedPR)" class="w-full btn-orange">
                <i class="fas fa-file-signature mr-1"></i> Amend & Re-Submit
              </button>
              <button v-else class="w-full btn-secondary cursor-not-allowed" disabled>
                <i class="fas fa-lock mr-1"></i> Cannot Amend (Already Converted to PO)
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-file-signature text-orange-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No PR Selected</h3>
            <p class="text-gray-500 mb-4">Select an approved purchase requisition from the table to view details</p>
            <div class="text-sm text-gray-400">
              <p>Only approved PRs that haven't been converted to PO can be amended</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Amendment Confirmation Modal -->
    <div v-if="showAmendModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showAmendModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-orange-600 to-orange-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-file-signature text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Confirm Amendment</h3>
          </div>
          <button @click="showAmendModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-exclamation-triangle text-orange-500 text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Amend Approved PR</h4>
            <p class="text-gray-600 mb-4">You are about to amend approved PR: <strong>{{ selectedPR?.pr_number }}</strong></p>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
              <p class="text-sm text-orange-800">
                <i class="fas fa-info-circle mr-1"></i>
                This will create a new version and reset the approval status to Draft.
              </p>
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Amendment Reason <span class="text-red-500">*</span></label>
            <textarea
              v-model="amendmentReason"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Please provide a reason for this amendment..."
              required
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button @click="showAmendModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200">
              Cancel
            </button>
            <button @click="confirmAmendment" :disabled="!amendmentReason.trim() || loading" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
              <span v-if="loading" class="mr-2"><i class="fas fa-spinner fa-spin"></i></span>
              Proceed to Amend
            </button>
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
const loading = ref(false)
const searchQuery = ref('')
const showAmendModal = ref(false)
const amendmentReason = ref('')
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
  // Only show approved PRs that haven't been converted to PO
  let result = prs.filter(pr => 
    pr.status === 'Approved' && !pr.po_converted
  )
  
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
  searchQuery.value = ''
  fetchPRs()
}

const selectPR = (pr) => {
  selectedPR.value = pr
}

const canAmendApprovedPR = (pr) => {
  return pr.status === 'Approved' && !pr.po_converted
}

const amendApprovedPR = (pr) => {
  if (!canAmendApprovedPR(pr)) {
    toast.error('This approved purchase requisition cannot be amended')
    return
  }
  
  selectedPR.value = pr
  amendmentReason.value = ''
  showAmendModal.value = true
}

const confirmAmendment = async () => {
  if (!amendmentReason.value.trim()) {
    toast.error('Please provide a reason for the amendment')
    return
  }

  loading.value = true
  try {
    // Record the amendment action and create new version
    await fetch(`/api/purchase-requisitions/${selectedPR.value.id}/amend-approved`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        amendment_reason: amendmentReason.value
      })
    })

    toast.success('Approved PR amendment initiated. Creating new version for editing...')
    showAmendModal.value = false
    
    // Redirect to prepare PR page with the new version for editing
    router.get(`/material-management/purchase-order/pr-po/prepare-pr?amend_from=${selectedPR.value.id}`)
    
  } catch (error) {
    console.error('Error processing amendment:', error)
    toast.error('Failed to process amendment')
  } finally {
    loading.value = false
  }
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
    : 'bg-gray-100 text-gray-800'
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

.btn-orange {
  @apply bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg shadow transition
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