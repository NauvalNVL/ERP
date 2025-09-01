<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">View & Print Purchase DN/CN Accounts</h1>
              <p class="text-orange-100 mt-1">View and generate reports for Purchase DN/CN Accounts</p>
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
                <span class="text-sm text-gray-600">2 Transaction Types Configured</span>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Last Updated: {{ new Date().toLocaleString() }}
            </div>
          </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
              <select
                v-model="filters.transaction_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
              >
                <option value="">All Transaction Types</option>
                <option value="DN">DN (Debit Note)</option>
                <option value="CN">CN (Credit Note)</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Account Code</label>
              <input
                v-model="filters.account_code"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                placeholder="Filter by account code"
              />
            </div>
            <div class="flex items-end">
              <button
                @click="applyFilters"
                class="w-full px-4 py-2 bg-orange-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-700"
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
                class="px-4 py-2 bg-orange-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-700 disabled:opacity-50"
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
            <h3 class="text-lg font-semibold text-gray-900">Purchase DN/CN Accounts</h3>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in filteredData" :key="item.transaction_type" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.transaction_type }}</td>
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
const header = 'View & Print Purchase DN/CN Accounts'
const title = 'View & Print Purchase DN/CN Accounts - ERP System'

const loading = ref(false)
const dnCnAccounts = ref([])

const filters = reactive({
  transaction_type: '',
  account_code: ''
})

const filteredData = computed(() => {
  let filtered = dnCnAccounts.value

  if (filters.transaction_type) {
    filtered = filtered.filter(item => item.transaction_type === filters.transaction_type)
  }

  if (filters.account_code) {
    filtered = filtered.filter(item => 
      item.account_code.toLowerCase().includes(filters.account_code.toLowerCase())
    )
  }

  return filtered
})

const fetchDnCnAccounts = async () => {
  try {
    const response = await fetch('/api/material-management/account/setup-account/purchase-dn-cn-accounts')
    const data = await response.json()
    dnCnAccounts.value = data
  } catch (error) {
    console.error('Error fetching DN/CN accounts:', error)
    toast.error('Failed to load DN/CN accounts')
  }
}

const applyFilters = () => {
  toast.success('Filters applied successfully')
}

const generateReport = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/material-management/account/setup-account/generate-purchase-dn-cn-report', {
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
  await fetchDnCnAccounts()
})
</script>
