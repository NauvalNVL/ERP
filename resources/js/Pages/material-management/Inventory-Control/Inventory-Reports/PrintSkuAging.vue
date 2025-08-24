<template>
  <AppLayout :header="'Print SKU Aging Report'">
    <Head title="Print SKU Aging Report" />
    
    <div class="min-h-screen bg-gray-50">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold flex items-center mb-3">
                <i class="fas fa-clock mr-4 text-orange-200"></i>
                Print SKU Aging Report
              </h1>
              <p class="text-orange-100 text-xl font-medium">Generate inventory aging analysis reports for stock management</p>
              <div class="flex items-center mt-4 space-x-6">
                <div class="flex items-center">
                  <i class="fas fa-calendar-alt text-orange-200 mr-2"></i>
                  <span class="text-sm">Period: {{ currentPeriod }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-user-circle text-orange-200 mr-2"></i>
                  <span class="text-sm">User: {{ currentUser }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-clock text-orange-200 mr-2"></i>
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
              <span class="text-sm font-medium text-gray-700">Aging Engine: Ready</span>
            </div>
          </div>
          <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
            Ready to Generate Aging Reports
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Report Parameters Section -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
              <i class="fas fa-cogs mr-3 text-orange-600"></i>
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
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
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
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                  <option value="">All Categories</option>
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.code }} - {{ category.name }}
                  </option>
                </select>
              </div>

              <!-- Aging Method -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Aging Method
                </label>
                <select
                  v-model="form.aging_method"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                  <option value="fifo">FIFO (First In, First Out)</option>
                  <option value="lifo">LIFO (Last In, First Out)</option>
                  <option value="average">Average Cost</option>
                  <option value="specific">Specific Identification</option>
                </select>
              </div>

              <!-- Aging Periods -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Aging Periods (Days)
                </label>
                <div class="space-y-2">
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="form.aging_periods.current"
                      type="number"
                      placeholder="30"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    />
                    <span class="text-sm text-gray-600">Current (0-30 days)</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="form.aging_periods.days_31_60"
                      type="number"
                      placeholder="60"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    />
                    <span class="text-sm text-gray-600">31-60 days</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="form.aging_periods.days_61_90"
                      type="number"
                      placeholder="90"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    />
                    <span class="text-sm text-gray-600">61-90 days</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="form.aging_periods.days_91_180"
                      type="number"
                      placeholder="180"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    />
                    <span class="text-sm text-gray-600">91-180 days</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="form.aging_periods.over_180"
                      type="number"
                      placeholder="180"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    />
                    <span class="text-sm text-gray-600">Over 180 days</span>
                  </div>
                </div>
              </div>

              <!-- Include Zero Balance -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.include_zero_balance"
                    type="checkbox"
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Include Zero Balance Items</span>
                </label>
              </div>

              <!-- Show Only Aged Items -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.show_only_aged"
                    type="checkbox"
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Show Only Aged Items</span>
                </label>
              </div>

              <!-- Sort By -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort By
                </label>
                <select
                  v-model="form.sort_by"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                  <option value="sku">SKU Code</option>
                  <option value="name">SKU Name</option>
                  <option value="category">Category</option>
                  <option value="oldest_age">Oldest Age</option>
                  <option value="total_value">Total Value</option>
                </select>
              </div>

              <!-- Sort Order -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort Order
                </label>
                <select
                  v-model="form.sort_order"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
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
              <i class="fas fa-chart-bar mr-3 text-orange-600"></i>
              Aging Report Preview
            </h2>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
              <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-check-circle text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">Current</p>
                    <p class="text-2xl font-bold">{{ formatNumber(summary.current) }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">31-60 Days</p>
                    <p class="text-2xl font-bold">{{ formatNumber(summary.days_31_60) }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-circle text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">61-90 Days</p>
                    <p class="text-2xl font-bold">{{ formatNumber(summary.days_61_90) }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-times-circle text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">91-180 Days</p>
                    <p class="text-2xl font-bold">{{ formatNumber(summary.days_91_180) }}</p>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                  <i class="fas fa-skull text-3xl mr-4"></i>
                  <div>
                    <p class="text-sm opacity-90">Over 180 Days</p>
                    <p class="text-2xl font-bold">{{ formatNumber(summary.over_180) }}</p>
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
                        Current
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        31-60 Days
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        61-90 Days
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        91-180 Days
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Over 180 Days
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Value
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading" class="animate-pulse">
                      <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex justify-center items-center space-x-2">
                          <i class="fas fa-spinner fa-spin"></i>
                          <span>Loading aging data...</span>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="agingData.length === 0" class="hover:bg-gray-50">
                      <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                        No aging data found. Try adjusting your filters.
                      </td>
                    </tr>
                    <tr v-for="item in agingData" :key="item.sku" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.sku }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.sku_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.category_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ formatNumber(item.current) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-medium">{{ formatNumber(item.days_31_60) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium">{{ formatNumber(item.days_61_90) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ formatNumber(item.days_91_180) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600 font-medium">{{ formatNumber(item.over_180) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ formatCurrency(item.total_value) }}</td>
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
const agingData = ref([])
const currentPage = ref(1)
const itemsPerPage = ref(20)

const form = ref({
  report_date: new Date().toISOString().split('T')[0],
  category_code: '',
  aging_method: 'fifo',
  aging_periods: {
    current: 30,
    days_31_60: 60,
    days_61_90: 90,
    days_91_180: 180,
    over_180: 180
  },
  include_zero_balance: false,
  show_only_aged: false,
  sort_by: 'oldest_age',
  sort_order: 'desc'
})

const summary = ref({
  current: 0,
  days_31_60: 0,
  days_61_90: 0,
  days_91_180: 0,
  over_180: 0
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

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Current':
      return 'bg-green-100 text-green-800'
    case 'Aging':
      return 'bg-yellow-100 text-yellow-800'
    case 'Old':
      return 'bg-orange-100 text-orange-800'
    case 'Very Old':
      return 'bg-red-100 text-red-800'
    case 'Obsolete':
      return 'bg-purple-100 text-purple-800'
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

const loadAgingData = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      ...form.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    })

    const response = await fetch(`/api/material-management/inventory-reports/sku-aging?${params}`)
    if (response.ok) {
      const data = await response.json()
      agingData.value = data.data
      summary.value = data.summary
      paginationInfo.value = data.pagination
    }
  } catch (error) {
    toast.error('Error loading aging data')
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
    const response = await fetch('/api/material-management/inventory-reports/sku-aging/generate', {
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
      a.download = `SKU_Aging_Report_${form.value.report_date}.pdf`
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

  await loadAgingData()
  toast.success('Report preview loaded')
}

const exportToExcel = async () => {
  if (!form.value.report_date) {
    toast.error('Please select a report date')
    return
  }

  isGenerating.value = true
  try {
    const response = await fetch('/api/material-management/inventory-reports/sku-aging/export', {
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
      a.download = `SKU_Aging_Report_${form.value.report_date}.xlsx`
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
    loadAgingData()
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    loadAgingData()
  }
}

// Lifecycle
let timeInterval

onMounted(() => {
  updateCurrentTime()
  timeInterval = setInterval(updateCurrentTime, 1000)
  loadCategories()
  loadAgingData()
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>
