<template>
  <AppLayout :header="'Inquire SKU Account'">
    <Head title="Inquire SKU Account" />
    
    <div class="min-h-screen bg-gray-50">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-teal-600 via-teal-700 to-teal-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold flex items-center mb-3">
                <i class="fas fa-search mr-4 text-teal-200"></i>
                Inquire SKU Account
              </h1>
              <p class="text-teal-100 text-xl font-medium">Real-time SKU account inquiry and analysis tool</p>
              <div class="flex items-center mt-4 space-x-6">
                <div class="flex items-center">
                  <i class="fas fa-calendar-alt text-teal-200 mr-2"></i>
                  <span class="text-sm">Period: {{ currentPeriod }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-user-circle text-teal-200 mr-2"></i>
                  <span class="text-sm">User: {{ currentUser }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-clock text-teal-200 mr-2"></i>
                  <span class="text-sm">{{ currentTime }}</span>
                </div>
              </div>
            </div>
            <div class="flex space-x-4">
              <button
                @click="searchSku"
                :disabled="isSearching"
                class="bg-teal-500 hover:bg-teal-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-search mr-3" v-if="!isSearching"></i>
                <i class="fas fa-spinner fa-spin mr-3" v-else></i>
                {{ isSearching ? 'Searching...' : 'Search SKU' }}
              </button>
              <button
                @click="exportToExcel"
                :disabled="!skuData || isExporting"
                class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-file-excel mr-3"></i>
                Export Excel
              </button>
              <button
                @click="printReport"
                :disabled="!skuData || isPrinting"
                class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-print mr-3"></i>
                Print Report
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
              <span class="text-sm font-medium text-gray-700">Inquiry Engine: Ready</span>
            </div>
          </div>
          <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
            Ready to Search SKU Accounts
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Search Parameters Section -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
              <i class="fas fa-cogs mr-3 text-teal-600"></i>
              Search Parameters
            </h2>

            <div class="space-y-6">
              <!-- SKU Code -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  SKU Code *
                </label>
                <div class="relative">
                  <input
                    v-model="form.sku_code"
                    type="text"
                    placeholder="Enter SKU code"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent pr-10"
                    @keyup.enter="searchSku"
                  />
                  <button
                    @click="searchSku"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-teal-600 hover:text-teal-800"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <!-- SKU Name -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  SKU Name
                </label>
                <input
                  v-model="form.sku_name"
                  type="text"
                  placeholder="Enter SKU name (optional)"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                />
              </div>

              <!-- SKU Category -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  SKU Category
                </label>
                <select
                  v-model="form.category_code"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                >
                  <option value="">All Categories</option>
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.code }} - {{ category.name }}
                  </option>
                </select>
              </div>

              <!-- Date Range -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  From Date
                </label>
                <input
                  v-model="form.from_date"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                  :max="maxDate"
                />
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  To Date
                </label>
                <input
                  v-model="form.to_date"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                  :max="maxDate"
                />
              </div>

              <!-- Include Transactions -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.include_transactions"
                    type="checkbox"
                    class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Include Transaction History</span>
                </label>
              </div>

              <!-- Include Aging -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.include_aging"
                    type="checkbox"
                    class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">Include Aging Analysis</span>
                </label>
              </div>

              <!-- Sort By -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort By
                </label>
                <select
                  v-model="form.sort_by"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                >
                  <option value="transaction_date">Transaction Date</option>
                  <option value="reference">Reference Number</option>
                  <option value="type">Transaction Type</option>
                  <option value="quantity">Quantity</option>
                  <option value="value">Value</option>
                </select>
              </div>

              <!-- Sort Order -->
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Sort Order
                </label>
                <select
                  v-model="form.sort_order"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                >
                  <option value="desc">Newest First</option>
                  <option value="asc">Oldest First</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- SKU Account Details Section -->
        <div class="lg:col-span-3">
          <div v-if="!skuData" class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="text-gray-400 mb-6">
              <i class="fas fa-search text-6xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-4">Search for SKU Account</h3>
            <p class="text-gray-500 mb-8">Enter a SKU code above to view detailed account information, transaction history, and aging analysis.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
              <div class="bg-gray-50 rounded-lg p-4">
                <i class="fas fa-info-circle text-teal-600 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Account Details</h4>
                <p class="text-gray-500">View SKU information, current balance, and account status</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-4">
                <i class="fas fa-history text-teal-600 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Transaction History</h4>
                <p class="text-gray-500">Browse all transactions and movement history</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-4">
                <i class="fas fa-chart-line text-teal-600 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Aging Analysis</h4>
                <p class="text-gray-500">Analyze inventory aging and stock movement patterns</p>
              </div>
            </div>
          </div>

          <div v-else class="space-y-8">
            <!-- SKU Summary Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-info-circle mr-3 text-teal-600"></i>
                SKU Account Summary
              </h2>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg p-6 text-white">
                  <div class="flex items-center">
                    <i class="fas fa-boxes text-3xl mr-4"></i>
                    <div>
                      <p class="text-sm opacity-90">Current Balance</p>
                      <p class="text-2xl font-bold">{{ formatNumber(skuData.current_balance) }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                  <div class="flex items-center">
                    <i class="fas fa-dollar-sign text-3xl mr-4"></i>
                    <div>
                      <p class="text-sm opacity-90">Total Value</p>
                      <p class="text-2xl font-bold">{{ formatCurrency(skuData.total_value) }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                  <div class="flex items-center">
                    <i class="fas fa-level-up-alt text-3xl mr-4"></i>
                    <div>
                      <p class="text-sm opacity-90">Min Level</p>
                      <p class="text-2xl font-bold">{{ formatNumber(skuData.min_level) }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                  <div class="flex items-center">
                    <i class="fas fa-level-down-alt text-3xl mr-4"></i>
                    <div>
                      <p class="text-sm opacity-90">Max Level</p>
                      <p class="text-2xl font-bold">{{ formatNumber(skuData.max_level) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SKU Details Table -->
              <div class="bg-gray-50 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                    <div class="space-y-3">
                      <div class="flex justify-between">
                        <span class="text-gray-600">SKU Code:</span>
                        <span class="font-medium">{{ skuData.sku_code }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">SKU Name:</span>
                        <span class="font-medium">{{ skuData.sku_name }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Category:</span>
                        <span class="font-medium">{{ skuData.category_code }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium">{{ skuData.type }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Unit:</span>
                        <span class="font-medium">{{ skuData.unit }}</span>
                      </div>
                    </div>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Levels</h3>
                    <div class="space-y-3">
                      <div class="flex justify-between">
                        <span class="text-gray-600">Reorder Level:</span>
                        <span class="font-medium">{{ formatNumber(skuData.reorder_level) }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Unit Price:</span>
                        <span class="font-medium">{{ formatCurrency(skuData.unit_price) }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium">{{ formatDate(skuData.last_updated) }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span :class="getStatusBadgeClass(skuData.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                          {{ skuData.status }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Transaction History -->
            <div v-if="form.include_transactions && transactions.length > 0" class="bg-white rounded-xl shadow-lg p-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-history mr-3 text-teal-600"></i>
                Transaction History
              </h2>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Out</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(transaction.transaction_date) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.reference_number }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getTypeBadgeClass(transaction.transaction_type)" class="px-2 py-1 text-xs font-medium rounded-full">
                          {{ transaction.transaction_type }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ transaction.in_quantity ? formatNumber(transaction.in_quantity) : '-' }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ transaction.out_quantity ? formatNumber(transaction.out_quantity) : '-' }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ formatNumber(transaction.running_balance) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ formatCurrency(transaction.total_value) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Aging Analysis -->
            <div v-if="form.include_aging && agingData" class="bg-white rounded-xl shadow-lg p-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chart-line mr-3 text-teal-600"></i>
                Aging Analysis
              </h2>

              <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white text-center">
                  <p class="text-sm opacity-90">Current</p>
                  <p class="text-xl font-bold">{{ formatNumber(agingData.current) }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white text-center">
                  <p class="text-sm opacity-90">31-60 Days</p>
                  <p class="text-xl font-bold">{{ formatNumber(agingData.days_31_60) }}</p>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white text-center">
                  <p class="text-sm opacity-90">61-90 Days</p>
                  <p class="text-xl font-bold">{{ formatNumber(agingData.days_61_90) }}</p>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white text-center">
                  <p class="text-sm opacity-90">91-180 Days</p>
                  <p class="text-xl font-bold">{{ formatNumber(agingData.days_91_180) }}</p>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white text-center">
                  <p class="text-sm opacity-90">Over 180 Days</p>
                  <p class="text-xl font-bold">{{ formatNumber(agingData.over_180) }}</p>
                </div>
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
const isSearching = ref(false)
const isExporting = ref(false)
const isPrinting = ref(false)
const categories = ref([])
const skuData = ref(null)
const transactions = ref([])
const agingData = ref(null)

const form = ref({
  sku_code: '',
  sku_name: '',
  category_code: '',
  from_date: '',
  to_date: '',
  include_transactions: true,
  include_aging: true,
  sort_by: 'transaction_date',
  sort_order: 'desc'
})

// Computed properties
const maxDate = computed(() => {
  return new Date().toISOString().split('T')[0]
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

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US')
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Active':
      return 'bg-green-100 text-green-800'
    case 'Inactive':
      return 'bg-red-100 text-red-800'
    case 'Discontinued':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getTypeBadgeClass = (type) => {
  switch (type) {
    case 'RC':
      return 'bg-green-100 text-green-800'
    case 'RT':
      return 'bg-red-100 text-red-800'
    case 'IS':
      return 'bg-blue-100 text-blue-800'
    case 'MI':
      return 'bg-yellow-100 text-yellow-800'
    case 'MO':
      return 'bg-purple-100 text-purple-800'
    case 'LT':
      return 'bg-indigo-100 text-indigo-800'
    case 'DN':
      return 'bg-pink-100 text-pink-800'
    case 'CN':
      return 'bg-orange-100 text-orange-800'
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

const searchSku = async () => {
  if (!form.value.sku_code) {
    toast.error('Please enter SKU code')
    return
  }

  isSearching.value = true
  try {
    const params = new URLSearchParams({
      ...form.value
    })

    const response = await fetch(`/api/material-management/inventory-reports/inquire-sku-account?${params}`)
    if (response.ok) {
      const data = await response.json()
      skuData.value = data.sku
      transactions.value = data.transactions || []
      agingData.value = data.aging || null
      toast.success('SKU account found')
    } else {
      toast.error('SKU not found')
      skuData.value = null
      transactions.value = []
      agingData.value = null
    }
  } catch (error) {
    toast.error('Error searching SKU')
  } finally {
    isSearching.value = false
  }
}

const exportToExcel = async () => {
  if (!skuData.value) {
    toast.error('No SKU data to export')
    return
  }

  isExporting.value = true
  try {
    const response = await fetch('/api/material-management/inventory-reports/inquire-sku-account/export', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        sku_code: form.value.sku_code,
        ...form.value
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `SKU_Account_${form.value.sku_code}.xlsx`
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
    isExporting.value = false
  }
}

const printReport = async () => {
  if (!skuData.value) {
    toast.error('No SKU data to print')
    return
  }

  isPrinting.value = true
  try {
    const response = await fetch('/api/material-management/inventory-reports/inquire-sku-account/print', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        sku_code: form.value.sku_code,
        ...form.value
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `SKU_Account_${form.value.sku_code}.pdf`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      toast.success('Report printed successfully')
    } else {
      toast.error('Error printing report')
    }
  } catch (error) {
    toast.error('Error printing report')
  } finally {
    isPrinting.value = false
  }
}

// Lifecycle
let timeInterval

onMounted(() => {
  updateCurrentTime()
  timeInterval = setInterval(updateCurrentTime, 1000)
  loadCategories()
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>
