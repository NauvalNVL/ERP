<template>
  <AppLayout header="View & Print Purchase Sub-Control">
    <div class="bg-white rounded-lg shadow-md p-6">
      <!-- Print Controls -->
      <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Purchase Sub-Control Report</h2>
        <div class="flex space-x-3">
          <button
            @click="printReport"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
          >
            <i class="fas fa-print mr-2"></i>
            Print Report
          </button>
          <button
            @click="exportToPDF"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
          >
            <i class="fas fa-file-pdf mr-2"></i>
            Export PDF
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by code or name..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
          <select
            v-model="filters.category"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
          <select
            v-model="filters.sortBy"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="psc_code">PSC Code</option>
            <option value="psc_name">PSC Name</option>
            <option value="category">Category</option>
            <option value="created_at">Created Date</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="ml-2 text-gray-600">Loading purchase sub-controls...</span>
      </div>

      <!-- Data Table -->
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="purchase-sub-control-table">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                No.
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                PSC Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                PSC Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Category
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created Date
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="filteredPurchaseSubControls.length === 0">
              <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                No purchase sub-controls found
              </td>
            </tr>
            <tr v-else v-for="(psc, index) in filteredPurchaseSubControls" :key="psc.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ index + 1 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ psc.psc_code }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ psc.psc_name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ psc.category || '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="psc.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ psc.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(psc.created_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Summary -->
      <div class="mt-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
          <div>
            <div class="text-2xl font-bold text-blue-600">{{ filteredPurchaseSubControls.length }}</div>
            <div class="text-sm text-gray-600">Total Records</div>
          </div>
          <div>
            <div class="text-2xl font-bold text-green-600">{{ activeCount }}</div>
            <div class="text-sm text-gray-600">Active</div>
          </div>
          <div>
            <div class="text-2xl font-bold text-red-600">{{ inactiveCount }}</div>
            <div class="text-sm text-gray-600">Inactive</div>
          </div>
          <div>
            <div class="text-2xl font-bold text-purple-600">{{ categoryCount }}</div>
            <div class="text-sm text-gray-600">Categories</div>
          </div>
        </div>
      </div>
    </div>

    <ToastContainer />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error } = useToast()

// Data
const purchaseSubControls = ref([])
const categories = ref([])
const loading = ref(false)

// Filters
const filters = ref({
  search: '',
  category: '',
  sortBy: 'psc_code'
})

// Computed
const filteredPurchaseSubControls = computed(() => {
  let filtered = [...purchaseSubControls.value]

  // Search filter
  if (filters.value.search) {
    const search = filters.value.search.toLowerCase()
    filtered = filtered.filter(psc => 
      psc.psc_code.toLowerCase().includes(search) ||
      psc.psc_name.toLowerCase().includes(search)
    )
  }

  // Category filter
  if (filters.value.category) {
    filtered = filtered.filter(psc => psc.category === filters.value.category)
  }

  // Sort
  filtered.sort((a, b) => {
    const aValue = a[filters.value.sortBy] || ''
    const bValue = b[filters.value.sortBy] || ''
    return aValue.toString().localeCompare(bValue.toString())
  })

  return filtered
})

const activeCount = computed(() => 
  filteredPurchaseSubControls.value.filter(psc => psc.is_active).length
)

const inactiveCount = computed(() => 
  filteredPurchaseSubControls.value.filter(psc => !psc.is_active).length
)

const categoryCount = computed(() => 
  new Set(filteredPurchaseSubControls.value.map(psc => psc.category).filter(Boolean)).size
)

// Methods
const fetchPurchaseSubControls = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/purchase-sub-controls/for-print')
    purchaseSubControls.value = response.data
    
    // Extract unique categories
    const uniqueCategories = [...new Set(response.data.map(psc => psc.category).filter(Boolean))]
    categories.value = uniqueCategories.sort()
  } catch (err) {
    console.error('Error fetching purchase sub-controls:', err)
    error('Failed to load purchase sub-controls')
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const printReport = () => {
  const printContent = document.getElementById('purchase-sub-control-table').outerHTML
  const printWindow = window.open('', '_blank')
  
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>Purchase Sub-Control Report</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .header { text-align: center; margin-bottom: 20px; }
        .date { text-align: right; margin-bottom: 10px; }
      </style>
    </head>
    <body>
      <div class="date">Print Date: ${new Date().toLocaleDateString('id-ID')}</div>
      <div class="header">
        <h1>Purchase Sub-Control Report</h1>
        <p>Total Records: ${filteredPurchaseSubControls.value.length}</p>
      </div>
      ${printContent}
    </body>
    </html>
  `)
  
  printWindow.document.close()
  printWindow.print()
  
  success('Report printed successfully')
}

const exportToPDF = async () => {
  try {
    // This would typically use a PDF library like jsPDF
    // For now, we'll use the print functionality
    printReport()
    success('PDF export initiated')
  } catch (err) {
    console.error('Error exporting PDF:', err)
    error('Failed to export PDF')
  }
}

// Lifecycle
onMounted(() => {
  fetchPurchaseSubControls()
})
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
