<template>
  <AppLayout :header="'View & Print Purchaser'">
    <Head title="View & Print Purchaser" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> View & Print Purchaser
      </h2>
      <p class="text-blue-100">Monitor and print the complete list of purchasers and requesters in the system</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Toolbar -->
      <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div class="flex-1 w-full">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Search by Purchaser ID, Name, or Email..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              @input="debouncedSearch"
            >
          </div>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
          <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
            <i class="fas fa-sync-alt mr-2"></i> Refresh
          </button>
          <button @click="printData" class="btn-info flex-1 md:flex-none">
            <i class="fas fa-print mr-2"></i> Print
          </button>
          <button @click="exportToExcel" class="btn-success flex-1 md:flex-none">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
          </button>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select v-model="filters.type" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Types</option>
              <option value="PU">Purchaser</option>
              <option value="RQ">Requestor</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
            <select v-model="sortBy" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="purchaser_id">Purchaser ID</option>
              <option value="purchaser_name">Purchaser Name</option>
              <option value="type">Type</option>
              <option value="email">Email</option>
            </select>
          </div>
        </div>
      </div>

            <!-- Statistics Cards -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-users text-blue-500 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-blue-800">Total Records</p>
              <p class="text-2xl font-bold text-blue-600">{{ totalPurchasers }}</p>
            </div>
          </div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-shopping-cart text-green-500 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">Purchasers</p>
              <p class="text-2xl font-bold text-green-600">{{ purchaserCount }}</p>
            </div>
          </div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-clipboard-list text-purple-500 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-purple-800">Requestors</p>
              <p class="text-2xl font-bold text-purple-600">{{ requestorCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th 
                  v-for="column in columns" 
                  :key="column.key"
                  @click="sortByColumn(column.key)"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                >
                  <div class="flex items-center">
                    {{ column.label }}
                    <i 
                      v-if="sortBy === column.key" 
                      :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"
                      class="ml-1"
                    ></i>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="purchaser in filteredPurchasers" 
                :key="purchaser.purchaser_id"
                class="hover:bg-gray-50"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ purchaser.purchaser_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ purchaser.purchaser_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span 
                    :class="getTypeBadgeClass(purchaser.type)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getTypeText(purchaser.type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ purchaser.email }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredPurchasers.length === 0" class="text-center py-12">
          <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No purchasers found</h3>
          <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
          </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="mt-6 flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button 
            @click="previousPage" 
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Previous
          </button>
          <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalPurchasers }}</span> results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button 
                @click="previousPage" 
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <i class="fas fa-chevron-left"></i>
              </button>
              <button 
                v-for="page in visiblePages" 
                :key="page"
                @click="goToPage(page)"
                :class="page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              >
                {{ page }}
              </button>
              <button 
                @click="nextPage" 
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <i class="fas fa-chevron-right"></i>
              </button>
            </nav>
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
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const purchasers = ref([])
const loading = ref(false)
const searchQuery = ref('')
const filters = ref({
  type: ''
})
const sortBy = ref('purchaser_id')
const sortDirection = ref('asc')
const currentPage = ref(1)
const perPage = ref(15)

// Table columns configuration
const columns = ref([
  { key: 'purchaser_id', label: 'Purchaser ID' },
  { key: 'purchaser_name', label: 'Purchaser Name' },
  { key: 'type', label: 'Type' },
  { key: 'email', label: 'Email' }
])

// Computed properties
const filteredPurchasers = computed(() => {
  let filtered = purchasers.value

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(purchaser => 
      purchaser.purchaser_id.toLowerCase().includes(query) ||
      purchaser.purchaser_name.toLowerCase().includes(query) ||
      purchaser.email.toLowerCase().includes(query)
    )
  }

  // Apply type filter
  if (filters.value.type) {
    filtered = filtered.filter(purchaser => purchaser.type === filters.value.type)
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let aValue = a[sortBy.value]
    let bValue = b[sortBy.value]

    // Handle null/undefined values
    if (aValue === null || aValue === undefined) aValue = ''
    if (bValue === null || bValue === undefined) bValue = ''

    // Convert to string for comparison
    aValue = String(aValue).toLowerCase()
    bValue = String(bValue).toLowerCase()

    if (sortDirection.value === 'asc') {
      return aValue.localeCompare(bValue)
    } else {
      return bValue.localeCompare(aValue)
    }
  })

  return filtered
})

const totalPurchasers = computed(() => filteredPurchasers.value.length)
const purchaserCount = computed(() => filteredPurchasers.value.filter(p => p.type === 'PU').length)
const requestorCount = computed(() => filteredPurchasers.value.filter(p => p.type === 'RQ').length)

const totalPages = computed(() => Math.ceil(filteredPurchasers.value.length / perPage.value))
const startIndex = computed(() => (currentPage.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, filteredPurchasers.value.length))

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)

  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

// Methods
const fetchPurchasers = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/purchasers/for-print')
    if (!response.ok) {
      throw new Error('Failed to fetch purchasers')
    }
    const data = await response.json()
    purchasers.value = data
  } catch (err) {
    error('Failed to load purchasers: ' + err.message)
  } finally {
    loading.value = false
  }
}

const debouncedSearch = () => {
  clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(() => {
    currentPage.value = 1
  }, 300)
}

const sortByColumn = (column) => {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDirection.value = 'asc'
  }
}

const getTypeText = (type) => {
  switch (type) {
    case 'PU':
      return 'Purchaser'
    case 'RQ':
      return 'Requestor'
    default:
      return type
  }
}

const getTypeBadgeClass = (type) => {
  switch (type) {
    case 'PU':
      return 'bg-blue-100 text-blue-800'
    case 'RQ':
      return 'bg-purple-100 text-purple-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const refreshData = () => {
  fetchPurchasers()
}

const printData = () => {
  const printWindow = window.open('', '_blank')
  const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Purchaser List - ${new Date().toLocaleDateString()}</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Purchaser List</h1>
        <p>Generated on: ${new Date().toLocaleString()}</p>
        <p>Total Records: ${filteredPurchasers.value.length}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>Purchaser ID</th>
            <th>Purchaser Name</th>
            <th>Type</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          ${filteredPurchasers.value.map(purchaser => `
            <tr>
              <td>${purchaser.purchaser_id}</td>
              <td>${purchaser.purchaser_name}</td>
              <td>${getTypeText(purchaser.type)}</td>
              <td>${purchaser.email}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
      <div class="footer">
        <p>PT. Multibox Indah - CPS ENTERPRISE 2020</p>
      </div>
    </body>
    </html>
  `
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const exportToExcel = () => {
  // Create CSV content
  const headers = ['Purchaser ID', 'Purchaser Name', 'Type', 'Email']
  const csvContent = [
    headers.join(','),
    ...filteredPurchasers.value.map(purchaser => [
      purchaser.purchaser_id,
      `"${purchaser.purchaser_name}"`,
      getTypeText(purchaser.type),
      purchaser.email
    ].join(','))
  ].join('\n')

  // Create and download file
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  const url = URL.createObjectURL(blob)
  link.setAttribute('href', url)
  link.setAttribute('download', `purchasers_${new Date().toISOString().split('T')[0]}.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const goToPage = (page) => {
  currentPage.value = page
}

// Watchers
watch([filters, sortBy, sortDirection], () => {
  currentPage.value = 1
})

// Lifecycle
onMounted(() => {
  fetchPurchasers()
})

// Debounced search timeout
const searchTimeout = ref(null)
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-secondary {
  @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-info {
  @apply bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}

.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
}
</style>
