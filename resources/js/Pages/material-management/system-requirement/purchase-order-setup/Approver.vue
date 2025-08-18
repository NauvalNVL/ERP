<template>
  <AppLayout :header="'Define Approver'">
    <Head title="Define Approver" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-user-check mr-3"></i> Define Approver
      </h2>
      <p class="text-green-100">Manage approvers for Purchase Requisition (PR) and Purchase Order (PO)</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by ID, name, or email..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newApprover" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Approver
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="mb-4 flex flex-wrap gap-4">
            <select v-model="prFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All PR Auth</option>
              <option value="true">PR Authorized</option>
              <option value="false">PR Not Authorized</option>
            </select>
            <select v-model="poFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All PO Auth</option>
              <option value="true">PO Authorized</option>
              <option value="false">PO Not Authorized</option>
            </select>
            <select v-model="statusFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All Status</option>
              <option value="true">Active</option>
              <option value="false">Inactive</option>
            </select>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('approver_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Approver ID <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('approver_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('user_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        User ID <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Email <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      PR Auth
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      PO Auth
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      OLDA
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading approvers...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedApprovers.length === 0" class="hover:bg-gray-50">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      No approvers found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="approver in paginatedApprovers" :key="approver.approver_id" 
                      @click="selectApprover(approver)" 
                      :class="{'bg-green-50': selectedApprover && selectedApprover.approver_id === approver.approver_id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ approver.approver_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ approver.approver_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ approver.user_id || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ approver.email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span :class="approver.pr_authorized ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                            class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ approver.pr_authorized ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span :class="approver.po_authorized ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                            class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ approver.po_authorized ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span :class="approver.olda_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                            class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ approver.olda_enabled ? 'Yes' : 'No' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedApprovers.length }} of {{ filteredApprovers.length }} approvers</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
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
          <div v-if="selectedApprover" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-user-check mr-2 text-green-500"></i> Approver Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">ID:</span>
                <span class="font-medium text-gray-900">{{ selectedApprover.approver_id }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedApprover.approver_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">User ID:</span>
                <span class="font-medium text-gray-900">{{ selectedApprover.user_id || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Email:</span>
                <span class="font-medium text-gray-900 text-right break-all">{{ selectedApprover.email }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">OLDA:</span>
                <span class="font-medium" :class="selectedApprover.olda_enabled ? 'text-green-600' : 'text-gray-600'">
                  {{ selectedApprover.olda_enabled ? 'Enabled' : 'Disabled' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PR Auth:</span>
                <span class="font-medium" :class="selectedApprover.pr_authorized ? 'text-green-600' : 'text-red-600'">
                  {{ selectedApprover.pr_authorized ? 'Authorized' : 'Not Authorized' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PO Auth:</span>
                <span class="font-medium" :class="selectedApprover.po_authorized ? 'text-green-600' : 'text-red-600'">
                  {{ selectedApprover.po_authorized ? 'Authorized' : 'Not Authorized' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span class="font-medium" :class="selectedApprover.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ selectedApprover.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>

            <!-- PR Authorization Details -->
            <div v-if="selectedApprover.pr_authorized" class="mt-4 p-3 bg-blue-50 rounded-lg">
              <h4 class="text-sm font-semibold text-blue-800 mb-2">PR Authorization</h4>
              <div class="space-y-1 text-xs">
                <div class="flex justify-between">
                  <span class="text-blue-600">Level:</span>
                  <span class="font-medium">{{ selectedApprover.pr_level }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-blue-600">Style:</span>
                  <span class="font-medium">{{ selectedApprover.pr_approval_style_text }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-blue-600">Zero Price:</span>
                  <span class="font-medium">{{ selectedApprover.pr_zero_price_allowed ? 'Allowed' : 'Not Allowed' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-blue-600">Price History:</span>
                  <span class="font-medium">{{ selectedApprover.pr_price_history ? 'Yes' : 'No' }}</span>
                </div>
              </div>
            </div>

            <!-- PO Authorization Details -->
            <div v-if="selectedApprover.po_authorized" class="mt-4 p-3 bg-purple-50 rounded-lg">
              <h4 class="text-sm font-semibold text-purple-800 mb-2">PO Authorization</h4>
              <div class="space-y-1 text-xs">
                <div class="flex justify-between">
                  <span class="text-purple-600">Level:</span>
                  <span class="font-medium">{{ selectedApprover.po_level }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-purple-600">Style:</span>
                  <span class="font-medium">{{ selectedApprover.po_approval_style_text }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-purple-600">Min Limit:</span>
                  <span class="font-medium">{{ formatCurrency(selectedApprover.po_min_limit) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-purple-600">Max Limit:</span>
                  <span class="font-medium">{{ formatCurrency(selectedApprover.po_max_limit) }}</span>
                </div>
              </div>
            </div>

            <div class="mt-6 space-y-2">
              <button @click="editApprover(selectedApprover)" class="w-full btn-green">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="toggleApproverStatus(selectedApprover)" class="w-full btn-blue">
                <i class="fas fa-toggle-on mr-1"></i> Toggle Status
              </button>
              <button @click="deleteApprover(selectedApprover)" class="w-full btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-user-check text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Approver Selected</h3>
            <p class="text-gray-500">Select an approver from the list to view details</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'

const toast = useToast()

// Reactive data
const approvers = ref([])
const selectedApprover = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const prFilter = ref('')
const poFilter = ref('')
const statusFilter = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const sortByField = ref('approver_id')
const sortDirection = ref('asc')

// Computed properties
const filteredApprovers = computed(() => {
  let filtered = approvers.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(approver =>
      approver.approver_id.toLowerCase().includes(query) ||
      approver.approver_name.toLowerCase().includes(query) ||
      approver.user_id?.toLowerCase().includes(query) ||
      approver.email.toLowerCase().includes(query)
    )
  }

  if (prFilter.value !== '') {
    filtered = filtered.filter(approver => approver.pr_authorized === (prFilter.value === 'true'))
  }

  if (poFilter.value !== '') {
    filtered = filtered.filter(approver => approver.po_authorized === (poFilter.value === 'true'))
  }

  if (statusFilter.value !== '') {
    filtered = filtered.filter(approver => approver.is_active === (statusFilter.value === 'true'))
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortByField.value]
    let bVal = b[sortByField.value]
    
    if (typeof aVal === 'string') {
      aVal = aVal.toLowerCase()
      bVal = bVal.toLowerCase()
    }
    
    if (sortDirection.value === 'asc') {
      return aVal > bVal ? 1 : -1
    } else {
      return aVal < bVal ? 1 : -1
    }
  })

  return filtered
})

const totalPages = computed(() => {
  return Math.ceil(filteredApprovers.value.length / itemsPerPage.value)
})

const paginatedApprovers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredApprovers.value.slice(start, end)
})

// Methods
const fetchApprovers = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/approvers')
    approvers.value = response.data
  } catch (error) {
    console.error('Error fetching approvers:', error)
    toast.error('Failed to load approvers')
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  selectedApprover.value = null
  searchQuery.value = ''
  prFilter.value = ''
  poFilter.value = ''
  statusFilter.value = ''
  currentPage.value = 1
  fetchApprovers()
}

const selectApprover = (approver) => {
  selectedApprover.value = approver
}

const newApprover = () => {
  // Implementation for new approver
  console.log('New approver')
  toast.info('New approver functionality will be implemented')
}

const editApprover = (approver) => {
  // Implementation for edit approver
  console.log('Edit approver:', approver)
  toast.info('Edit approver functionality will be implemented')
}

const deleteApprover = (approver) => {
  // Implementation for delete approver
  console.log('Delete approver:', approver)
  toast.info('Delete approver functionality will be implemented')
}

const toggleApproverStatus = async (approver) => {
  loading.value = true
  try {
    const response = await axios.patch(`/api/approvers/${approver.approver_id}/toggle-active`)
    toast.success('Approver status updated successfully')
    
    const index = approvers.value.findIndex(a => a.approver_id === approver.approver_id)
    if (index !== -1) {
      approvers.value[index].is_active = response.data.is_active
      if (selectedApprover.value?.approver_id === approver.approver_id) {
        selectedApprover.value.is_active = response.data.is_active
      }
    }
  } catch (error) {
    console.error('Error updating approver status:', error)
    toast.error('Failed to update approver status')
  } finally {
    loading.value = false
  }
}

const sortBy = (field) => {
  if (sortByField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortByField.value = field
    sortDirection.value = 'asc'
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
  }).format(value)
}

// Lifecycle
onMounted(() => {
  fetchApprovers()
})
</script>

<style scoped>
.btn-primary {
  @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-secondary {
  @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-green {
  @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-blue {
  @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}
</style>
