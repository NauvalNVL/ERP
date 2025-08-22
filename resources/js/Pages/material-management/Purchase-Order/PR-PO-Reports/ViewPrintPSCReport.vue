<template>
  <AppLayout :header="'View & Print PSC Report'">
    <div class="bg-white rounded-lg shadow-md p-6">
      <!-- Filter Section -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Options</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Report Period -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.reportPeriod"
                type="text"
                placeholder="MM/YYYY"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="setCurrentPeriod"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
              >
                Current
              </button>
            </div>
          </div>

          <!-- Date Range -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.dateFrom"
                type="date"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <span class="text-gray-500 self-center">to</span>
              <input
                v-model="filters.dateTo"
                type="date"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <!-- PSC Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">PSC Code</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.pscCode"
                type="text"
                placeholder="PSC Code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openPSCModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.category"
                type="text"
                placeholder="Category Code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openCategoryModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- Purchaser -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Purchaser</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.purchaser"
                type="text"
                placeholder="Purchaser Code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openPurchaserModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.sku"
                type="text"
                placeholder="SKU Code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openSkuModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Status Filters -->
        <div class="mt-6">
          <h4 class="text-md font-medium text-gray-700 mb-3">Status Filters</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- PSC Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">PSC Status</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.pscStatus"
                    type="checkbox"
                    value="Active"
                    class="mr-2"
                  />
                  <span class="text-sm">Active</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.pscStatus"
                    type="checkbox"
                    value="Inactive"
                    class="mr-2"
                  />
                  <span class="text-sm">Inactive</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.pscStatus"
                    type="checkbox"
                    value="Pending"
                    class="mr-2"
                  />
                  <span class="text-sm">Pending</span>
                </label>
              </div>
            </div>

            <!-- Report Options -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Options</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.includeDetails"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Item Details</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includePricing"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Pricing Information</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includeStatistics"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Statistics</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includeHistory"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include History</span>
                </label>
              </div>
            </div>

            <!-- Sort Options -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Sort Options</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="psc_code"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by PSC Code</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="category"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Category</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="purchaser"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Purchaser</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="last_activity"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Last Activity</span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
          <button
            @click="generateReport"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-file-excel mr-2"></i>
            {{ loading ? 'Generating...' : 'Generate Report' }}
          </button>
          
          <button
            @click="previewReport"
            :disabled="loading"
            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors disabled:opacity-50"
          >
            <i class="fas fa-eye mr-2"></i>
            Preview
          </button>
          
          <button
            @click="clearFilters"
            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Filters
          </button>
        </div>

        <div class="text-sm text-gray-600">
          <i class="fas fa-info-circle mr-1"></i>
          PSC = Purchase Sub Control
        </div>
      </div>

      <!-- Report Summary -->
      <div v-if="reportSummary" class="bg-gray-50 rounded-lg p-4 mb-6">
        <h4 class="text-md font-medium text-gray-800 mb-3">Report Summary</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ reportSummary.totalPSC }}</div>
            <div class="text-sm text-gray-600">Total PSC</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ reportSummary.activePSC }}</div>
            <div class="text-sm text-gray-600">Active PSC</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-orange-600">{{ reportSummary.totalItems }}</div>
            <div class="text-sm text-gray-600">Total Items</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ reportSummary.avgValue }}</div>
            <div class="text-sm text-gray-600">Avg Value</div>
          </div>
        </div>
      </div>

      <!-- PSC Preview -->
      <div v-if="pscData.length > 0" class="bg-white border rounded-lg">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">PSC Preview</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PSC Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchaser</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="psc in pscData" :key="psc.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.psc_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.purchaser }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.sku_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(psc.total_value) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getStatusColor(psc.status)">
                    {{ psc.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.last_activity }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Reports -->
      <div v-if="recentReports.length > 0" class="bg-white border rounded-lg mt-6">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">Recent Reports</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="report in recentReports" :key="report.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.generatedDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.period }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.recordCount }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.generatedBy }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="downloadReport(report.id)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                  <button
                    @click="deleteReport(report.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <PurchaseSubControlLookupModal
      :show="showPSCModal"
      @close="showPSCModal = false"
      @select="selectPSC"
    />
    
    <CategoryTableModal
      :show="showCategoryModal"
      @close="showCategoryModal = false"
      @select="selectCategory"
    />
    
    <PurchaserTableModal
      :show="showPurchaserModal"
      @close="showPurchaserModal = false"
      @select="selectPurchaser"
    />
    
    <SkuLookupModal
      :show="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import PurchaseSubControlLookupModal from '@/Components/PurchaseSubControlLookupModal.vue'
import CategoryTableModal from '@/Components/CategoryTableModal.vue'
import PurchaserTableModal from '@/Components/PurchaserTableModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const loading = ref(false)
const showPSCModal = ref(false)
const showCategoryModal = ref(false)
const showPurchaserModal = ref(false)
const showSkuModal = ref(false)

const filters = reactive({
  reportPeriod: '',
  dateFrom: '',
  dateTo: '',
  pscCode: '',
  category: '',
  purchaser: '',
  sku: '',
  pscStatus: [],
  includeDetails: true,
  includePricing: true,
  includeStatistics: false,
  includeHistory: false,
  sortBy: 'psc_code'
})

const reportSummary = ref(null)
const pscData = ref([])
const recentReports = ref([])

// Methods
const setCurrentPeriod = () => {
  const now = new Date()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const year = now.getFullYear()
  filters.reportPeriod = `${month}/${year}`
}

const openPSCModal = () => {
  showPSCModal.value = true
}

const openCategoryModal = () => {
  showCategoryModal.value = true
}

const openPurchaserModal = () => {
  showPurchaserModal.value = true
}

const openSkuModal = () => {
  showSkuModal.value = true
}

const selectPSC = (psc) => {
  filters.pscCode = psc.psc_code
  showPSCModal.value = false
}

const selectCategory = (category) => {
  filters.category = category.code
  showCategoryModal.value = false
}

const selectPurchaser = (purchaser) => {
  filters.purchaser = purchaser.purchaser_id
  showPurchaserModal.value = false
}

const selectSku = (sku) => {
  filters.sku = sku.sku
  showSkuModal.value = false
}

const clearFilters = () => {
  Object.assign(filters, {
    reportPeriod: '',
    dateFrom: '',
    dateTo: '',
    pscCode: '',
    category: '',
    purchaser: '',
    sku: '',
    pscStatus: [],
    includeDetails: true,
    includePricing: true,
    includeStatistics: false,
    includeHistory: false,
    sortBy: 'psc_code'
  })
  reportSummary.value = null
  pscData.value = []
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const getStatusColor = (status) => {
  switch (status) {
    case 'Active':
      return 'text-green-600'
    case 'Inactive':
      return 'text-red-600'
    case 'Pending':
      return 'text-yellow-600'
    default:
      return 'text-gray-600'
  }
}

const generateReport = async () => {
  try {
    loading.value = true
    
    // Validate required fields
    if (!filters.reportPeriod && !filters.dateFrom && !filters.dateTo) {
      error('Please select a report period or date range')
      return
    }

    const response = await fetch('/api/psc-reports/generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(filters)
    })

    if (!response.ok) {
      throw new Error('Failed to generate report')
    }

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `PSC_Report_${filters.reportPeriod || 'Custom'}.xlsx`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)

    success('Report generated successfully')
    await loadRecentReports()
    
  } catch (err) {
    error('Failed to generate report: ' + err.message)
  } finally {
    loading.value = false
  }
}

const previewReport = async () => {
  try {
    loading.value = true
    
    const response = await fetch('/api/psc-reports/preview', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(filters)
    })

    if (!response.ok) {
      throw new Error('Failed to preview report')
    }

    const data = await response.json()
    reportSummary.value = data.summary
    pscData.value = data.pscData || []
    
    success('Report preview loaded')
    
  } catch (err) {
    error('Failed to preview report: ' + err.message)
  } finally {
    loading.value = false
  }
}

const downloadReport = async (reportId) => {
  try {
    const response = await fetch(`/api/psc-reports/${reportId}/download`)
    if (!response.ok) {
      throw new Error('Failed to download report')
    }

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `PSC_Report_${reportId}.xlsx`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)

    success('Report downloaded successfully')
  } catch (err) {
    error('Failed to download report: ' + err.message)
  }
}

const deleteReport = async (reportId) => {
  if (!confirm('Are you sure you want to delete this report?')) {
    return
  }

  try {
    const response = await fetch(`/api/psc-reports/${reportId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    if (!response.ok) {
      throw new Error('Failed to delete report')
    }

    success('Report deleted successfully')
    await loadRecentReports()
  } catch (err) {
    error('Failed to delete report: ' + err.message)
  }
}

const loadRecentReports = async () => {
  try {
    const response = await fetch('/api/psc-reports/recent')
    if (response.ok) {
      recentReports.value = await response.json()
    }
  } catch (err) {
    console.error('Failed to load recent reports:', err)
  }
}

// Lifecycle
onMounted(() => {
  setCurrentPeriod()
  loadRecentReports()
})
</script>
