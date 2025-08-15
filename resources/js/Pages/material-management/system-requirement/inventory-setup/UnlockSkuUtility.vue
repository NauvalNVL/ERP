<template>
  <AppLayout :header="'Unlock SKU Utility'">
    <Head title="Unlock SKU Utility" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 to-red-600 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-unlock-alt mr-3"></i> Unlock SKU Utility
      </h2>
      <p class="text-orange-100">Manage and unlock SKUs that are locked due to transactions, system failures, or user sessions</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <i class="fas fa-lock text-blue-600"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-blue-600">Total Locked</p>
              <p class="text-2xl font-bold text-blue-900">{{ statistics.total_locked || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <i class="fas fa-clock text-yellow-600"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-yellow-600">Stale Locks</p>
              <p class="text-2xl font-bold text-yellow-900">{{ statistics.stale_locks || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <i class="fas fa-history text-green-600"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-600">Recent (24h)</p>
              <p class="text-2xl font-bold text-green-900">{{ statistics.recent_locks || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <i class="fas fa-chart-pie text-purple-600"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-purple-600">Top Reason</p>
              <p class="text-lg font-bold text-purple-900">{{ topLockReason }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Bar -->
      <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div class="flex-1 w-full">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input 
              type="text" 
              v-model="searchQuery" 
              @input="debouncedSearch"
              placeholder="Search by SKU, name, locked by, or reason..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
            >
          </div>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
          <button 
            @click="unlockStaleLocks" 
            :disabled="loading || statistics.stale_locks === 0"
            class="btn-warning flex-1 md:flex-none disabled:opacity-50"
          >
            <i class="fas fa-clock mr-2"></i> Unlock Stale
          </button>
          <button 
            @click="bulkUnlock" 
            :disabled="loading || selectedSkus.length === 0"
            class="btn-danger flex-1 md:flex-none disabled:opacity-50"
          >
            <i class="fas fa-unlock mr-2"></i> Bulk Unlock ({{ selectedSkus.length }})
          </button>
          <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
            <i class="fas fa-sync-alt mr-2"></i> Refresh
          </button>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3">
                  <input 
                    type="checkbox" 
                    @change="toggleSelectAll"
                    :checked="selectedSkus.length === paginatedSkus.length && paginatedSkus.length > 0"
                    :indeterminate="selectedSkus.length > 0 && selectedSkus.length < paginatedSkus.length"
                    class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                  >
                </th>
                <th @click="sortBy('sku')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  <div class="flex items-center">
                    SKU <i class="fas fa-sort ml-1"></i>
                  </div>
                </th>
                <th @click="sortBy('sku_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  <div class="flex items-center">
                    Name <i class="fas fa-sort ml-1"></i>
                  </div>
                </th>
                <th @click="sortBy('locked_by')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  <div class="flex items-center">
                    Locked By <i class="fas fa-sort ml-1"></i>
                  </div>
                </th>
                <th @click="sortBy('locked_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  <div class="flex items-center">
                    Locked At <i class="fas fa-sort ml-1"></i>
                  </div>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Duration
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Lock Reason
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                  <div class="flex justify-center items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading locked SKUs...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="paginatedSkus.length === 0" class="hover:bg-gray-50">
                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                  <div class="flex flex-col items-center py-8">
                    <i class="fas fa-unlock text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No locked SKUs found.</p>
                    <p class="text-gray-400 text-sm">All SKUs are currently unlocked.</p>
                  </div>
                </td>
              </tr>
              <tr v-for="sku in paginatedSkus" :key="sku.sku" 
                  :class="{'bg-orange-50': sku.is_stale, 'hover:bg-gray-50': !sku.is_stale}"
                  class="hover:bg-gray-50">
                <td class="px-4 py-4">
                  <input 
                    type="checkbox" 
                    :value="sku.sku"
                    v-model="selectedSkus"
                    class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                  >
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  <div class="flex items-center">
                    <span class="font-mono">{{ sku.sku }}</span>
                    <span v-if="sku.is_stale" class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                      Stale
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.sku_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  <div class="flex items-center">
                    <i class="fas fa-user text-gray-400 mr-2"></i>
                    {{ sku.locked_by }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ formatDateTime(sku.locked_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  <span :class="getDurationClass(sku.lock_duration)">
                    {{ formatDuration(sku.lock_duration) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" :title="sku.lock_reason">
                  {{ sku.lock_reason }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(sku)">
                    <i :class="getStatusIcon(sku)" class="mr-1"></i>
                    {{ getStatusText(sku) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button 
                    @click="unlockSingleSku(sku)"
                    class="text-orange-600 hover:text-orange-900 mr-3"
                    :disabled="loading"
                  >
                    <i class="fas fa-unlock mr-1"></i> Unlock
                  </button>
                  <button 
                    @click="viewSkuDetails(sku)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    <i class="fas fa-eye mr-1"></i> View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button 
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                Previous
              </button>
              <button 
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                Next
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing 
                  <span class="font-medium">{{ pagination.from }}</span>
                  to 
                  <span class="font-medium">{{ pagination.to }}</span>
                  of 
                  <span class="font-medium">{{ pagination.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <button 
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                  >
                    <i class="fas fa-chevron-left"></i>
                  </button>
                  
                  <template v-for="page in visiblePages" :key="page">
                    <button 
                      v-if="page !== '...'"
                      @click="changePage(page)"
                      :class="page === pagination.current_page ? 'bg-orange-50 border-orange-500 text-orange-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    >
                      {{ page }}
                    </button>
                    <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                      ...
                    </span>
                  </template>
                  
                  <button 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                  >
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Unlock Confirmation Modal -->
    <Modal v-if="showUnlockModal" @close="showUnlockModal = false">
      <div class="p-6">
        <div class="flex items-center mb-4">
          <div class="flex-shrink-0">
            <i class="fas fa-unlock-alt text-2xl text-orange-600"></i>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-medium text-gray-900">Confirm Unlock</h3>
            <p class="text-sm text-gray-500">Please provide a reason for unlocking this SKU</p>
          </div>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">SKU to Unlock</label>
          <div class="bg-gray-50 p-3 rounded-lg">
            <div class="font-mono text-lg font-bold">{{ unlockModalData.sku }}</div>
            <div class="text-sm text-gray-600">{{ unlockModalData.sku_name }}</div>
          </div>
        </div>

        <div class="mb-4">
          <label for="unlock-reason" class="block text-sm font-medium text-gray-700 mb-2">
            Unlock Reason <span class="text-red-500">*</span>
          </label>
          <textarea
            id="unlock-reason"
            v-model="unlockReason"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
            placeholder="Enter the reason for unlocking this SKU..."
            required
          ></textarea>
        </div>

        <div class="flex justify-end space-x-3">
          <button 
            @click="showUnlockModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button 
            @click="confirmUnlock"
            :disabled="!unlockReason.trim() || loading"
            class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-lg hover:bg-orange-700 disabled:opacity-50"
          >
            <i class="fas fa-unlock mr-2"></i> Unlock SKU
          </button>
        </div>
      </div>
    </Modal>

    <!-- Bulk Unlock Modal -->
    <Modal v-if="showBulkUnlockModal" @close="showBulkUnlockModal = false">
      <div class="p-6">
        <div class="flex items-center mb-4">
          <div class="flex-shrink-0">
            <i class="fas fa-unlock-alt text-2xl text-red-600"></i>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-medium text-gray-900">Bulk Unlock SKUs</h3>
            <p class="text-sm text-gray-500">Unlock {{ selectedSkus.length }} selected SKU(s)</p>
          </div>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Selected SKUs</label>
          <div class="bg-gray-50 p-3 rounded-lg max-h-32 overflow-y-auto">
            <div v-for="sku in selectedSkuDetails" :key="sku.sku" class="text-sm mb-1">
              <span class="font-mono font-bold">{{ sku.sku }}</span> - {{ sku.sku_name }}
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label for="bulk-unlock-reason" class="block text-sm font-medium text-gray-700 mb-2">
            Unlock Reason <span class="text-red-500">*</span>
          </label>
          <textarea
            id="bulk-unlock-reason"
            v-model="bulkUnlockReason"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
            placeholder="Enter the reason for unlocking these SKUs..."
            required
          ></textarea>
        </div>

        <div class="flex justify-end space-x-3">
          <button 
            @click="showBulkUnlockModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button 
            @click="confirmBulkUnlock"
            :disabled="!bulkUnlockReason.trim() || loading"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 disabled:opacity-50"
          >
            <i class="fas fa-unlock mr-2"></i> Unlock {{ selectedSkus.length }} SKUs
          </button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Modal from '@/Components/Modal.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

const { success, error, warning } = useToast()

// Reactive data
const loading = ref(false)
const searchQuery = ref('')
const selectedSkus = ref([])
const paginatedSkus = ref([])
const pagination = ref(null)
const statistics = ref({
  total_locked: 0,
  stale_locks: 0,
  recent_locks: 0,
  lock_reasons: []
})

// Modal states
const showUnlockModal = ref(false)
const showBulkUnlockModal = ref(false)
const unlockModalData = ref({})
const unlockReason = ref('')
const bulkUnlockReason = ref('')

// Computed properties
const topLockReason = computed(() => {
  if (statistics.value.lock_reasons && statistics.value.lock_reasons.length > 0) {
    return statistics.value.lock_reasons[0].lock_reason || 'N/A'
  }
  return 'N/A'
})

const selectedSkuDetails = computed(() => {
  return paginatedSkus.value.filter(sku => selectedSkus.value.includes(sku.sku))
})

const visiblePages = computed(() => {
  if (!pagination.value) return []
  
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    } else if (current >= last - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = last - 4; i <= last; i++) {
        pages.push(i)
      }
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    }
  }
  
  return pages
})

// Methods
const loadData = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      search: searchQuery.value,
      per_page: 15,
      page: pagination.value?.current_page || 1
    })
    
    const response = await fetch(`/api/material-management/unlock-sku-utility/locked-skus?${params}`)
    const data = await response.json()
    
    if (response.ok) {
      paginatedSkus.value = data.data
      pagination.value = {
        current_page: data.current_page,
        last_page: data.last_page,
        per_page: data.per_page,
        total: data.total,
        from: data.from,
        to: data.to
      }
    } else {
      error(data.message || 'Failed to load locked SKUs')
    }
  } catch (err) {
    error('Failed to load locked SKUs')
    console.error(err)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await fetch('/api/material-management/unlock-sku-utility/statistics')
    const data = await response.json()
    
    if (response.ok) {
      statistics.value = data
    }
  } catch (err) {
    console.error('Failed to load statistics:', err)
  }
}

const debouncedSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      loadData()
    }, 300)
  }
})()

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    pagination.value.current_page = page
    loadData()
  }
}

const sortBy = (field) => {
  // Implement sorting if needed
  console.log('Sort by:', field)
}

const toggleSelectAll = () => {
  if (selectedSkus.value.length === paginatedSkus.value.length) {
    selectedSkus.value = []
  } else {
    selectedSkus.value = paginatedSkus.value.map(sku => sku.sku)
  }
}

const unlockSingleSku = (sku) => {
  unlockModalData.value = sku
  unlockReason.value = ''
  showUnlockModal.value = true
}

const confirmUnlock = async () => {
  if (!unlockReason.value.trim()) {
    error('Please provide a reason for unlocking')
    return
  }

  loading.value = true
  try {
    const response = await fetch(`/api/material-management/unlock-sku-utility/unlock/${unlockModalData.value.sku}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        reason: unlockReason.value
      })
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      showUnlockModal.value = false
      loadData()
      loadStatistics()
    } else {
      error(data.message || 'Failed to unlock SKU')
    }
  } catch (err) {
    error('Failed to unlock SKU')
    console.error(err)
  } finally {
    loading.value = false
  }
}

const bulkUnlock = () => {
  if (selectedSkus.value.length === 0) {
    warning('Please select SKUs to unlock')
    return
  }
  
  bulkUnlockReason.value = ''
  showBulkUnlockModal.value = true
}

const confirmBulkUnlock = async () => {
  if (!bulkUnlockReason.value.trim()) {
    error('Please provide a reason for unlocking')
    return
  }

  loading.value = true
  try {
    const response = await fetch('/api/material-management/unlock-sku-utility/bulk-unlock', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        skus: selectedSkus.value,
        reason: bulkUnlockReason.value
      })
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      showBulkUnlockModal.value = false
      selectedSkus.value = []
      loadData()
      loadStatistics()
    } else {
      error(data.message || 'Failed to perform bulk unlock')
    }
  } catch (err) {
    error('Failed to perform bulk unlock')
    console.error(err)
  } finally {
    loading.value = false
  }
}

const unlockStaleLocks = async () => {
  if (statistics.value.stale_locks === 0) {
    warning('No stale locks found')
    return
  }

  const reason = prompt('Please provide a reason for unlocking stale locks:')
  if (!reason) return

  loading.value = true
  try {
    const response = await fetch('/api/material-management/unlock-sku-utility/unlock-stale', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        reason: reason
      })
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      loadData()
      loadStatistics()
    } else {
      error(data.message || 'Failed to unlock stale locks')
    }
  } catch (err) {
    error('Failed to unlock stale locks')
    console.error(err)
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  loadData()
  loadStatistics()
}

const viewSkuDetails = (sku) => {
  // Navigate to SKU details page
  window.open(`/material-management/system-requirement/inventory-setup/sku/${sku.sku}`, '_blank')
}

// Utility functions
const formatDateTime = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleString()
}

const formatDuration = (minutes) => {
  if (minutes < 60) {
    return `${minutes}m`
  } else if (minutes < 1440) {
    const hours = Math.floor(minutes / 60)
    const mins = minutes % 60
    return `${hours}h ${mins}m`
  } else {
    const days = Math.floor(minutes / 1440)
    const hours = Math.floor((minutes % 1440) / 60)
    return `${days}d ${hours}h`
  }
}

const getDurationClass = (minutes) => {
  if (minutes < 30) return 'text-green-600'
  if (minutes < 60) return 'text-yellow-600'
  return 'text-red-600'
}

const getStatusClass = (sku) => {
  if (sku.is_stale) return 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800'
  return 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800'
}

const getStatusIcon = (sku) => {
  if (sku.is_stale) return 'fas fa-clock'
  return 'fas fa-lock'
}

const getStatusText = (sku) => {
  if (sku.is_stale) return 'Stale Lock'
  return 'Locked'
}

// Lifecycle
onMounted(() => {
  loadData()
  loadStatistics()
})

// Watch for changes
watch(selectedSkus, (newVal) => {
  // Handle selection changes if needed
})
</script>

<style scoped>
.btn-primary {
  @apply px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}

.btn-secondary {
  @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500;
}

.btn-warning {
  @apply px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500;
}

.btn-danger {
  @apply px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
}

.btn-info {
  @apply px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}
</style>
