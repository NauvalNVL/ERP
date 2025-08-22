<template>
  <AppLayout :header="'View & Print SKU Historical Price'">
    <div class="bg-white rounded-lg shadow-md p-6">
      <!-- Filter Section -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Options</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

          <!-- Vendor -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor (AP AC#)</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.vendor"
                type="text"
                placeholder="AP AC#"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openVendorModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- Price Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price Type</label>
            <select
              v-model="filters.priceType"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Types</option>
              <option value="purchase">Purchase Price</option>
              <option value="standard">Standard Price</option>
              <option value="average">Average Price</option>
              <option value="last">Last Price</option>
              <option value="contract">Contract Price</option>
            </select>
          </div>

          <!-- Currency -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
            <select
              v-model="filters.currency"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Currencies</option>
              <option value="IDR">IDR</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="SGD">SGD</option>
            </select>
          </div>
        </div>

        <!-- Additional Filters -->
        <div class="mt-6">
          <h4 class="text-md font-medium text-gray-700 mb-3">Additional Filters</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Price Range -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
              <div class="flex space-x-2">
                <input
                  v-model="filters.minPrice"
                  type="number"
                  placeholder="Min Price"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-gray-500 self-center">to</span>
                <input
                  v-model="filters.maxPrice"
                  type="number"
                  placeholder="Max Price"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>

            <!-- Report Options -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Options</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.includeVendorDetails"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Vendor Details</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includePriceHistory"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Price History</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includePriceAnalysis"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Price Analysis</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includeTrends"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Price Trends</span>
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
                    value="date"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Date</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="sku"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by SKU</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="price"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Price</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.sortBy"
                    type="radio"
                    value="vendor"
                    class="mr-2"
                  />
                  <span class="text-sm">Sort by Vendor</span>
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
          Historical price analysis and trends
        </div>
      </div>

      <!-- Report Summary -->
      <div v-if="reportSummary" class="bg-gray-50 rounded-lg p-4 mb-6">
        <h4 class="text-md font-medium text-gray-800 mb-3">Report Summary</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ reportSummary.totalRecords }}</div>
            <div class="text-sm text-gray-600">Total Records</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ reportSummary.uniqueSkus }}</div>
            <div class="text-sm text-gray-600">Unique SKUs</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-orange-600">{{ formatCurrency(reportSummary.avgPrice) }}</div>
            <div class="text-sm text-gray-600">Average Price</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ reportSummary.priceChanges }}</div>
            <div class="text-sm text-gray-600">Price Changes</div>
          </div>
        </div>
      </div>

      <!-- Price History Preview -->
      <div v-if="priceHistory.length > 0" class="bg-white border rounded-lg">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">Price History Preview</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="price in priceHistory" :key="price.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.vendor }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.price_type }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(price.price) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.currency }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getPriceChangeColor(price.price_change)">
                    {{ formatPriceChange(price.price_change) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ price.source }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Price Trends Chart -->
      <div v-if="priceTrends.length > 0" class="bg-white border rounded-lg mt-6">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">Price Trends</h4>
        </div>
        <div class="p-6">
          <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="text-center">
              <i class="fas fa-chart-line text-4xl text-gray-400 mb-2"></i>
              <p class="text-gray-500">Price trend chart will be displayed here</p>
              <p class="text-sm text-gray-400">(Chart implementation required)</p>
            </div>
          </div>
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
    <SkuLookupModal
      :show="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />
    
    <CategoryTableModal
      :show="showCategoryModal"
      @close="showCategoryModal = false"
      @select="selectCategory"
    />
    
    <VendorProfileModal
      :show="showVendorModal"
      @close="showVendorModal = false"
      @select="selectVendor"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import CategoryTableModal from '@/Components/CategoryTableModal.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const loading = ref(false)
const showSkuModal = ref(false)
const showCategoryModal = ref(false)
const showVendorModal = ref(false)

const filters = reactive({
  dateFrom: '',
  dateTo: '',
  sku: '',
  category: '',
  vendor: '',
  priceType: '',
  currency: '',
  minPrice: '',
  maxPrice: '',
  includeVendorDetails: true,
  includePriceHistory: true,
  includePriceAnalysis: false,
  includeTrends: false,
  sortBy: 'date'
})

const reportSummary = ref(null)
const priceHistory = ref([])
const priceTrends = ref([])
const recentReports = ref([])

// Methods
const openSkuModal = () => {
  showSkuModal.value = true
}

const openCategoryModal = () => {
  showCategoryModal.value = true
}

const openVendorModal = () => {
  showVendorModal.value = true
}

const selectSku = (sku) => {
  filters.sku = sku.sku
  showSkuModal.value = false
}

const selectCategory = (category) => {
  filters.category = category.code
  showCategoryModal.value = false
}

const selectVendor = (vendor) => {
  filters.vendor = vendor.ap_ac_number
  showVendorModal.value = false
}

const clearFilters = () => {
  Object.assign(filters, {
    dateFrom: '',
    dateTo: '',
    sku: '',
    category: '',
    vendor: '',
    priceType: '',
    currency: '',
    minPrice: '',
    maxPrice: '',
    includeVendorDetails: true,
    includePriceHistory: true,
    includePriceAnalysis: false,
    includeTrends: false,
    sortBy: 'date'
  })
  reportSummary.value = null
  priceHistory.value = []
  priceTrends.value = []
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const formatPriceChange = (change) => {
  if (change > 0) {
    return `+${change.toFixed(2)}%`
  } else if (change < 0) {
    return `${change.toFixed(2)}%`
  }
  return '0.00%'
}

const getPriceChangeColor = (change) => {
  if (change > 0) {
    return 'text-green-600'
  } else if (change < 0) {
    return 'text-red-600'
  }
  return 'text-gray-600'
}

const generateReport = async () => {
  try {
    loading.value = true
    
    // Validate required fields
    if (!filters.dateFrom && !filters.dateTo) {
      error('Please select a date range')
      return
    }

    const response = await fetch('/api/sku-historical-price-reports/generate', {
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
    a.download = `SKU_Historical_Price_Report_${filters.dateFrom}_${filters.dateTo}.xlsx`
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
    
    const response = await fetch('/api/sku-historical-price-reports/preview', {
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
    priceHistory.value = data.priceHistory || []
    priceTrends.value = data.priceTrends || []
    
    success('Report preview loaded')
    
  } catch (err) {
    error('Failed to preview report: ' + err.message)
  } finally {
    loading.value = false
  }
}

const downloadReport = async (reportId) => {
  try {
    const response = await fetch(`/api/sku-historical-price-reports/${reportId}/download`)
    if (!response.ok) {
      throw new Error('Failed to download report')
    }

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `SKU_Historical_Price_Report_${reportId}.xlsx`
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
    const response = await fetch(`/api/sku-historical-price-reports/${reportId}`, {
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
    const response = await fetch('/api/sku-historical-price-reports/recent')
    if (response.ok) {
      recentReports.value = await response.json()
    }
  } catch (err) {
    console.error('Failed to load recent reports:', err)
  }
}

// Lifecycle
onMounted(() => {
  // Set default date range to last 12 months
  const now = new Date()
  const oneYearAgo = new Date(now.getFullYear() - 1, now.getMonth(), now.getDate())
  filters.dateFrom = oneYearAgo.toISOString().split('T')[0]
  filters.dateTo = now.toISOString().split('T')[0]
  
  loadRecentReports()
})
</script>
