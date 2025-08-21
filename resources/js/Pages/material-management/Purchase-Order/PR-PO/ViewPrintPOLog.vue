<template>
  <AppLayout header="View & Print PO Log">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">View & Print PO Log</h1>
              <p class="text-sm text-gray-600">View purchase order audit trail and activity logs</p>
            </div>
            <div class="flex items-center space-x-2">
              <i class="fas fa-file-alt text-2xl text-blue-600"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Filter PO Logs</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">PO Number</label>
              <input
                v-model="filters.po_number"
                type="text"
                placeholder="e.g., PO202501001"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Log Type</label>
              <select
                v-model="filters.log_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">All Types</option>
                <option value="CREATION">Creation</option>
                <option value="MODIFICATION">Modification</option>
                <option value="APPROVAL">Approval</option>
                <option value="REJECTION">Rejection</option>
                <option value="CANCELLATION">Cancellation</option>
                <option value="DELIVERY">Delivery</option>
                <option value="PAYMENT">Payment</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
              <input
                v-model="filters.user"
                type="text"
                placeholder="Username or User ID"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
              <input
                v-model="filters.date_range"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
          
          <div class="flex justify-between mt-4">
            <div class="flex space-x-3">
              <button
                @click="resetFilters"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
              >
                Reset
              </button>
              <button
                @click="loadLogs"
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700"
              >
                <i class="fas fa-search mr-2"></i>
                Search Logs
              </button>
            </div>
            
            <div class="flex space-x-2">
              <button
                @click="exportLogs"
                :disabled="logs.length === 0"
                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 disabled:opacity-50"
              >
                <i class="fas fa-file-excel mr-2"></i>
                Export
              </button>
              <button
                @click="printLogs"
                :disabled="logs.length === 0"
                class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 disabled:opacity-50"
              >
                <i class="fas fa-print mr-2"></i>
                Print
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-list text-2xl text-blue-500"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Logs</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ logs.length }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-plus-circle text-2xl text-green-500"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Created</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ getLogCountByType('CREATION') }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-2xl text-blue-500"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Approved</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ getLogCountByType('APPROVAL') }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-ban text-2xl text-red-500"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Cancelled</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ getLogCountByType('CANCELLATION') }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Logs Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">PO Activity Logs</h3>
          
          <div v-if="logs.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="log in paginatedLogs" :key="log.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDateTime(log.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ log.po_number }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="getLogTypeBadgeClass(log.log_type)"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ log.log_type }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ log.user_name }} ({{ log.user_id }})
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ log.action }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" :title="log.description">
                    {{ log.description }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button
                      @click="viewLogDetails(log)"
                      class="text-blue-600 hover:text-blue-900"
                      title="View full details"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
              <div class="text-sm text-gray-700">
                Showing {{ ((currentPage - 1) * itemsPerPage) + 1 }} to {{ Math.min(currentPage * itemsPerPage, logs.length) }} of {{ logs.length }} results
              </div>
              <div class="flex space-x-2">
                <button
                  @click="currentPage--"
                  :disabled="currentPage === 1"
                  class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50"
                >
                  Previous
                </button>
                <span class="px-3 py-1 text-sm">Page {{ currentPage }} of {{ totalPages }}</span>
                <button
                  @click="currentPage++"
                  :disabled="currentPage === totalPages"
                  class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
          
          <div v-else-if="hasSearched" class="text-center py-8">
            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Logs Found</h3>
            <p class="text-gray-600">Try adjusting your filter criteria.</p>
          </div>
          
          <div v-else class="text-center py-8">
            <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Search PO Logs</h3>
            <p class="text-gray-600">Use the filters above to search for purchase order activity logs.</p>
          </div>
        </div>
      </div>

      <!-- Log Details Modal -->
      <div v-if="showDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-medium text-gray-900">Log Details</h3>
              <button
                @click="closeDetailsModal"
                class="text-gray-400 hover:text-gray-600"
              >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
            
            <div v-if="selectedLog" class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                  <p class="text-sm text-gray-900">{{ formatDateTime(selectedLog.created_at) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">PO Number</label>
                  <p class="text-sm text-gray-900">{{ selectedLog.po_number }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Log Type</label>
                  <span 
                    :class="getLogTypeBadgeClass(selectedLog.log_type)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ selectedLog.log_type }}
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">User</label>
                  <p class="text-sm text-gray-900">{{ selectedLog.user_name }} ({{ selectedLog.user_id }})</p>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700">Action</label>
                <p class="text-sm text-gray-900">{{ selectedLog.action }}</p>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <p class="text-sm text-gray-900">{{ selectedLog.description }}</p>
              </div>
              
              <div v-if="selectedLog.changes">
                <label class="block text-sm font-medium text-gray-700">Changes Made</label>
                <div class="bg-gray-50 p-3 rounded text-sm">
                  <pre>{{ JSON.stringify(selectedLog.changes, null, 2) }}</pre>
                </div>
              </div>
              
              <div v-if="selectedLog.ip_address">
                <label class="block text-sm font-medium text-gray-700">IP Address</label>
                <p class="text-sm text-gray-900">{{ selectedLog.ip_address }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Data
const loading = ref(false)
const hasSearched = ref(false)
const logs = ref([])
const showDetailsModal = ref(false)
const selectedLog = ref(null)
const currentPage = ref(1)
const itemsPerPage = ref(20)

// Filters
const filters = reactive({
  po_number: '',
  log_type: '',
  user: '',
  date_range: ''
})

// Computed
const totalPages = computed(() => Math.ceil(logs.value.length / itemsPerPage.value))

const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return logs.value.slice(start, end)
})

// Methods
const loadLogs = async () => {
  loading.value = true
  hasSearched.value = true
  
  try {
    const params = new URLSearchParams()
    
    if (filters.po_number) params.append('po_number', filters.po_number)
    if (filters.log_type) params.append('log_type', filters.log_type)
    if (filters.user) params.append('user', filters.user)
    if (filters.date_range) params.append('date', filters.date_range)

    const response = await fetch(`/api/purchase-orders/logs?${params}`)
    const result = await response.json()
    
    if (result.success) {
      logs.value = result.data
      currentPage.value = 1
    } else {
      error('Failed to load PO logs')
    }
  } catch (err) {
    error('An error occurred while loading logs')
  } finally {
    loading.value = false
  }
}

const resetFilters = () => {
  Object.keys(filters).forEach(key => {
    filters[key] = ''
  })
  logs.value = []
  hasSearched.value = false
  currentPage.value = 1
}

const getLogCountByType = (type) => {
  return logs.value.filter(log => log.log_type === type).length
}

const viewLogDetails = (log) => {
  selectedLog.value = log
  showDetailsModal.value = true
}

const closeDetailsModal = () => {
  showDetailsModal.value = false
  selectedLog.value = null
}

const exportLogs = async () => {
  try {
    const params = new URLSearchParams()
    
    if (filters.po_number) params.append('po_number', filters.po_number)
    if (filters.log_type) params.append('log_type', filters.log_type)
    if (filters.user) params.append('user', filters.user)
    if (filters.date_range) params.append('date', filters.date_range)

    const response = await fetch(`/api/purchase-orders/logs/export?${params}`)
    
    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `PO_Logs_${new Date().toISOString().split('T')[0]}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      success('PO logs exported successfully')
    } else {
      error('Failed to export logs')
    }
  } catch (err) {
    error('An error occurred while exporting')
  }
}

const printLogs = () => {
  window.print()
}

const getLogTypeBadgeClass = (type) => {
  const classMap = {
    'CREATION': 'bg-blue-100 text-blue-800',
    'MODIFICATION': 'bg-yellow-100 text-yellow-800',
    'APPROVAL': 'bg-green-100 text-green-800',
    'REJECTION': 'bg-red-100 text-red-800',
    'CANCELLATION': 'bg-red-100 text-red-800',
    'DELIVERY': 'bg-purple-100 text-purple-800',
    'PAYMENT': 'bg-indigo-100 text-indigo-800'
  }
  return classMap[type] || 'bg-gray-100 text-gray-800'
}

const formatDateTime = (date) => {
  return new Date(date).toLocaleString('id-ID')
}

// Lifecycle
onMounted(() => {
  // Auto-load recent logs
  loadLogs()
})
</script>

<style>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
