<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">View & Print Inventory SKU Accounts</h1>
              <p class="text-purple-100 mt-1">View and generate reports for Inventory SKU Accounts</p>
            </div>
            <div class="text-right text-white">
              <div class="text-sm opacity-75">Current Period</div>
              <div class="text-lg font-semibold">6 2025</div>
            </div>
          </div>
        </div>

        <!-- Status Bar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">System Ready</span>
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">5 SKUs Configured</span>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Last Updated: {{ new Date().toLocaleString() }}
            </div>
          </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU Code</label>
              <input
                v-model="filters.sku_code"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="Filter by SKU code"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Account Code</label>
              <input
                v-model="filters.account_code"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="Filter by account code"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
              <select
                v-model="filters.category"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              >
                <option value="">All Categories</option>
                <option value="Raw Materials">Raw Materials</option>
                <option value="Finished Goods">Finished Goods</option>
                <option value="WIP">Work in Progress</option>
                <option value="Consumables">Consumables</option>
              </select>
            </div>
            <div class="flex items-end">
              <button
                @click="applyFilters"
                class="w-full px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700"
              >
                Apply Filters
              </button>
            </div>
          </div>

          <div class="flex justify-between items-center">
            <div class="flex space-x-2">
              <button
                @click="generateReport"
                :disabled="loading"
                class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 disabled:opacity-50"
              >
                <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                Generate Report
              </button>
              <button
                @click="exportToExcel"
                :disabled="loading"
                class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
              >
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
              </button>
              <button
                @click="printReport"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
              >
                <i class="fas fa-print mr-2"></i>
                Print
              </button>
            </div>
            <div class="text-sm text-gray-500">
              Total Records: {{ filteredData.length }}
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Inventory SKU Accounts</h3>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in filteredData" :key="item.sku_code" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.sku_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.sku_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.account_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.account_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                      Active
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()
const header = 'View & Print Inventory SKU Accounts'
const title = 'View & Print Inventory SKU Accounts - ERP System'

const loading = ref(false)
const inventorySkuAccounts = ref([])

const filters = reactive({
  sku_code: '',
  account_code: '',
  category: ''
})

const filteredData = computed(() => {
  let filtered = inventorySkuAccounts.value

  if (filters.sku_code) {
    filtered = filtered.filter(item => 
      item.sku_code.toLowerCase().includes(filters.sku_code.toLowerCase())
    )
  }

  if (filters.account_code) {
    filtered = filtered.filter(item => 
      item.account_code.toLowerCase().includes(filters.account_code.toLowerCase())
    )
  }

  if (filters.category) {
    filtered = filtered.filter(item => item.category === filters.category)
  }

  return filtered
})

const fetchInventorySkuAccounts = async () => {
  try {
    const response = await fetch('/api/material-management/account/setup-account/inventory-sku-accounts')
    const data = await response.json()
    inventorySkuAccounts.value = data
  } catch (error) {
    console.error('Error fetching inventory SKU accounts:', error)
    toast.error('Failed to load inventory SKU accounts')
  }
}

const applyFilters = () => {
  toast.success('Filters applied successfully')
}

const generateReport = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/material-management/account/setup-account/generate-inventory-sku-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(filters)
    })

    const data = await response.json()
    
    if (data.success) {
      toast.success(data.message)
    } else {
      toast.error(data.message || 'Failed to generate report')
    }
  } catch (error) {
    console.error('Error generating report:', error)
    toast.error('Failed to generate report')
  } finally {
    loading.value = false
  }
}

const exportToExcel = () => {
  toast.success('Export to Excel functionality will be implemented')
}

const printReport = () => {
  window.print()
}

onMounted(async () => {
  await fetchInventorySkuAccounts()
})
</script>
