<template>
  <AppLayout :header="'Print SKU Reorder Report'">
    <Head title="Print SKU Reorder Report" />
    
    <div class="min-h-screen bg-gray-50">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-green-600 via-green-700 to-green-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold flex items-center mb-3">
                <i class="fas fa-sort-amount-down mr-4 text-green-200"></i>
                Print SKU Reorder Report
              </h1>
              <p class="text-green-100 text-xl font-medium">Generate reorder recommendations and inventory planning reports</p>
              <div class="flex items-center mt-4 space-x-6">
                <div class="flex items-center">
                  <i class="fas fa-calendar-alt text-green-200 mr-2"></i>
                  <span class="text-sm">Period: {{ currentPeriod }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-user-circle text-green-200 mr-2"></i>
                  <span class="text-sm">User: {{ currentUser }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-clock text-green-200 mr-2"></i>
                  <span class="text-sm">{{ currentTime }}</span>
                </div>
              </div>
            </div>
            <div class="flex space-x-4">
              <button
                @click="generateReport"
                :disabled="isGenerating"
                class="bg-green-500 hover:bg-green-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-file-pdf mr-3" v-if="!isGenerating"></i>
                <i class="fas fa-spinner fa-spin mr-3" v-else></i>
                {{ isGenerating ? 'Generating...' : 'Generate Report' }}
              </button>
              <button
                @click="previewReport"
                :disabled="isGenerating"
                class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-eye mr-3"></i>
                Preview
              </button>
              <button
                @click="exportToExcel"
                :disabled="isGenerating"
                class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-file-excel mr-3"></i>
                Export Excel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-8">
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-sm font-medium text-gray-700">System: Online</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-sm font-medium text-gray-700">Database: Connected</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-sm font-medium text-gray-700">Reorder Engine: Ready</span>
            </div>
          </div>
          <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
            Ready to Generate Reorder Reports
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Report Parameters Section -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
              <i class="fas fa-cogs mr-3 text-green-600"></i>
              Report Parameters
            </h2>

            <div class="space-y-6">
              <!-- Report Date -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Report Date *
                </label>
                <input
                  v-model="form.report_date"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  :max="maxDate"
                />
              </div>

              <!-- SKU Category -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  SKU Category
                </label>
                <select
                  v-model="form.category_code"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                  <option value="">All Categories</option>
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.code }} - {{ category.name }}
                  </option>
                </select>
              </div>

              <!-- Reorder Status -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Reorder Status
                </label>
                <select
                  v-model="form.reorder_status"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                  <option value="">All Status</option>
                  <option value="below_min">Below Minimum Level</option>
                  <option value="at_reorder">At Reorder Level</option>
                  <option value="above_max">Above Maximum Level</option>
                  <option value="normal">Normal Level</option>
                </select>
              </div>

              <!-- Include Zero Balance -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.include_zero_balance"
                    type="checkbox"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Include Zero Balance Items</span>
                </label>
              </div>

              <!-- Show Only Items Needing Reorder -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.only_needing_reorder"
                    type="checkbox"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Show Only Items Needing Reorder</span>
                </label>
              </div>

              <!-- Sort By -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort By
                </label>
                <select
                  v-model="form.sort_by"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                  <option value="sku">SKU Code</option>
                  <option value="name">SKU Name</option>
                  <option value="category">Category</option>
                  <option value="reorder_level">Reorder Level</option>
                  <option value="current_balance">Current Balance</option>
                  <option value="shortage">Shortage Amount</option>
                </select>
              </div>

              <!-- Sort Order -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort Order
                </label>
                <select
                  v-model="form.sort_order"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                  <option value="asc">Ascending</option>
                  <option value="desc">Descending</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Report Preview Section -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
              <i class="fas fa-chart-bar mr-3 text-green-600"></i>
              Reorder Report Preview
            </h2>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
              <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">Items Below Min</p>
                    <p class="text-2xl font-bold">{{ summary.belowMinLevel }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-level-down-alt text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">At Reorder Level</p>
                    <p class="text-2xl font-bold">{{ summary.atReorderLevel }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-level-up-alt text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">Above Max Level</p>
                    <p class="text-2xl font-bold">{{ summary.aboveMaxLevel }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-dollar-sign text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">Total Value</p>
                    <p class="text-2xl font-bold">{{ formatCurrency(summary.totalValue) }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        SKU Code
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        SKU Name
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Category
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Current Balance
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Min Level
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reorder Level
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Max Level
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Shortage
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading" class="animate-pulse">
                      <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex justify-center items-center space-x-2">
                          <i class="fas fa-spinner fa-spin"></i>
                          <span>Loading reorder data...</span>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="reorderData.length === 0" class="hover:bg-gray-50">
                      <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                        No reorder data found. Try adjusting your filters.
                      </td>
                    </tr>
                    <tr v-for="item in reorderData" :key="item.sku" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.sku }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.sku_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.category_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatNumber(item.current_balance) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatNumber(item.min_level) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatNumber(item.reorder_level) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatNumber(item.max_level) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="getShortageClass(item.shortage)">
                        {{ formatNumber(item.shortage) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getStatusBadgeClass(item.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                          {{ item.status }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Showing {{ paginationInfo.from }} to {{ paginationInfo.to }} of {{ paginationInfo.total }} results
              </div>
              <div class="flex items-center space-x-2">
                <button
                  @click="previousPage"
                  :disabled="currentPage === 1"
                  class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                >
                  Previous
                </button>
                <span class="px-3 py-2 text-sm text-gray-700">{{ currentPage }} / {{ totalPages }}</span>
                <button
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import Swal from 'sweetalert2'

const toast = useToast()

// Reactive data
const currentTime = ref('')
const currentPeriod = ref('2024-01')
const currentUser = ref('Admin User')
const loading = ref(false)
const isGenerating = ref(false)
const categories = ref([])
const reorderData = ref([])
const currentPage = ref(1)
const itemsPerPage = ref(20)

const form = ref({
  report_date: new Date().toISOString().split('T')[0],
  category_code: '',
  reorder_status: '',
  include_zero_balance: false,
  only_needing_reorder: false,
  sort_by: 'shortage',
  sort_order: 'desc'
})

const summary = ref({
  belowMinLevel: 0,
  atReorderLevel: 0,
  aboveMaxLevel: 0,
  totalValue: 0
})

const paginationInfo = ref({
  from: 0,
  to: 0,
  total: 0
})

// Computed properties
const maxDate = computed(() => {
  return new Date().toISOString().split('T')[0]
})

const totalPages = computed(() => {
  return Math.ceil(paginationInfo.value.total / itemsPerPage.value)
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString()
}

const formatNumber = (value) => {
  return new Intl.NumberFormat('en-US').format(value)
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'IDR'
  }).format(value)
}

const getShortageClass = (shortage) => {
  if (shortage > 0) {
    return 'text-red-600'
  } else if (shortage < 0) {
    return 'text-green-600'
  }
  return 'text-gray-600'
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Below Min':
      return 'bg-red-100 text-red-800'
    case 'At Reorder':
      return 'bg-yellow-100 text-yellow-800'
    case 'Above Max':
      return 'bg-blue-100 text-blue-800'
    case 'Normal':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const loadCategories = async () => {
  try {
    const response = await fetch('/api/material-management/categories')
    if (response.ok) {
      const data = await response.json()
      categories.value = data
    }
  } catch (error) {
    toast.error('Error loading categories')
  }
}

const loadReorderData = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      ...form.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    })

    const response = await fetch(`/api/material-management/inventory-reports/sku-reorder?${params}`)
    if (response.ok) {
      const data = await response.json()
      reorderData.value = data.data
      summary.value = data.summary
      paginationInfo.value = data.pagination
    }
  } catch (error) {
    toast.error('Error loading reorder data')
  } finally {
    loading.value = false
  }
}

const generateReport = async () => {
  if (!form.value.report_date) {
    toast.error('Please select a report date')
    return
  }

  isGenerating.value = true
  try {
    const response = await fetch('/api/material-management/inventory-reports/sku-reorder/generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `SKU_Reorder_Report_${form.value.report_date}.pdf`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('Report generated successfully')
    } else {
      toast.error('Error generating report')
    }
  } catch (error) {
    toast.error('Error generating report')
  } finally {
    isGenerating.value = false
  }
}

const previewReport = async () => {
  if (!form.value.report_date) {
    toast.error('Please select a report date')
    return
  }

  await loadReorderData()
  toast.success('Report preview loaded')
}

const exportToExcel = async () => {
  if (!form.value.report_date) {
    toast.error('Please select a report date')
    return
  }

  isGenerating.value = true
  try {
    const response = await fetch('/api/material-management/inventory-reports/sku-reorder/export', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `SKU_Reorder_Report_${form.value.report_date}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('Report exported successfully')
    } else {
      toast.error('Error exporting report')
    }
  } catch (error) {
    toast.error('Error exporting report')
  } finally {
    isGenerating.value = false
  }
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    loadReorderData()
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    loadReorderData()
  }
}

// Lifecycle
let timeInterval

onMounted(() => {
  updateCurrentTime()
  timeInterval = setInterval(updateCurrentTime, 1000)
  loadCategories()
  loadReorderData()
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>
