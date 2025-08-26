<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">Setup Inventory SKU Accounts</h1>
              <p class="text-purple-100 mt-1">Configure account mappings for inventory SKUs</p>
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Setup Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Inventory SKU Account Configuration</h3>
              </div>
              
              <div class="p-6">
                <form @submit.prevent="saveConfiguration">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">SKU Code</label>
                      <input
                        v-model="form.sku_code"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter SKU code"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Account Code</label>
                      <input
                        v-model="form.account_code"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter account code"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                      <input
                        v-model="form.account_name"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter account name"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                      <select
                        v-model="form.category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                      >
                        <option value="">Select Category</option>
                        <option value="Raw Materials">Raw Materials</option>
                        <option value="Finished Goods">Finished Goods</option>
                        <option value="WIP">Work in Progress</option>
                        <option value="Consumables">Consumables</option>
                      </select>
                    </div>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="clearForm"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                      Clear
                    </button>
                    <button
                      type="submit"
                      :disabled="loading"
                      class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 disabled:opacity-50"
                    >
                      <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                      Save Configuration
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
              </div>
              
              <div class="p-6">
                <div class="space-y-4">
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKUs</span>
                    <span class="text-lg font-semibold text-gray-900">5</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Configured</span>
                    <span class="text-lg font-semibold text-green-600">5</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pending</span>
                    <span class="text-lg font-semibold text-yellow-600">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Configured Inventory SKU Accounts</h3>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in inventorySkuAccounts" :key="item.sku_code" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.sku_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.sku_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.account_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.account_name }}</td>
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
import { ref, reactive, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()
const header = 'Setup Inventory SKU Accounts'
const title = 'Setup Inventory SKU Accounts - ERP System'

const loading = ref(false)
const inventorySkuAccounts = ref([])

const form = reactive({
  sku_code: '',
  account_code: '',
  account_name: '',
  category: ''
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

const saveConfiguration = async () => {
  if (!form.sku_code || !form.account_code) {
    toast.error('Please fill in all required fields')
    return
  }

  loading.value = true
  try {
    const response = await fetch('/api/material-management/account/setup-account/inventory-sku-accounts', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form)
    })

    const data = await response.json()
    
    if (data.success) {
      toast.success(data.message)
      await fetchInventorySkuAccounts()
      clearForm()
    } else {
      toast.error(data.message || 'Failed to save configuration')
    }
  } catch (error) {
    console.error('Error saving configuration:', error)
    toast.error('Failed to save configuration')
  } finally {
    loading.value = false
  }
}

const clearForm = () => {
  form.sku_code = ''
  form.account_code = ''
  form.account_name = ''
  form.category = ''
}

onMounted(async () => {
  await fetchInventorySkuAccounts()
})
</script>
