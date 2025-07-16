<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center">
          <i class="fas fa-list mr-3"></i>Customer Account Table
        </h3>
        <div class="flex space-x-3 items-center">
          <div class="text-white text-sm mr-2">
            <span class="mr-2">Sort:</span>
            <select v-model="sortBy" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
              <option value="customer_code">Customer Code</option>
              <option value="customer_name">Customer Name</option>
            </select>
          </div>
          <div class="text-white text-sm">
            <span class="mr-2">Status:</span>
            <select v-model="statusFilter" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
              <option value="active" selected>Active</option>
              <option value="obsolete">Obsolete</option>
              <option value="all">All</option>
            </select>
          </div>
          <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="p-2 overflow-y-auto" style="max-height: 60vh;">
        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <span class="ml-2 text-gray-600">Loading data...</span>
        </div>
        <div v-else-if="error" class="p-4 text-red-500 bg-red-50 rounded border border-red-200">
          <div class="font-bold mb-1">Error:</div>
          <div>{{ error }}</div>
          <button @click="fetchCustomerAccounts" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
            Try Again
          </button>
        </div>
        <div v-else-if="filteredAccounts.length === 0" class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200">
          No customer accounts found. Please adjust your filter criteria or add new accounts.
        </div>
        <table v-else class="min-w-full text-xs border border-gray-300">
          <thead class="bg-gray-200 sticky top-0">
            <tr>
              <th class="px-2 py-1 border border-gray-300 text-left">Customer Code</th>
              <th class="px-2 py-1 border border-gray-300 text-left">Customer Name</th>
              <th class="px-2 py-1 border border-gray-300 text-left">S/person</th>
              <th class="px-2 py-1 border border-gray-300 text-left">AC Type</th>
              <th class="px-2 py-1 border border-gray-300 text-left">Currency</th>
              <th class="px-2 py-1 border border-gray-300 text-left">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="account in filteredAccounts" 
                :key="account.customer_code"
                class="hover:bg-blue-100 cursor-pointer"
                :class="{ 'bg-blue-200': selectedAccount?.customer_code === account.customer_code }"
                @click="selectAccount(account)">
              <td class="px-2 py-1 border border-gray-300">{{ account.customer_code }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ account.customer_name }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ account.salesperson_code }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ account.account_type || account.ac_type }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ account.currency_code }}</td>
              <td class="px-2 py-1 border border-gray-300">
                <span 
                  :class="(account.status === 'Active' || !account.status) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="px-2 py-0.5 rounded-full text-xs">
                  {{ account.status || 'Active' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
        <div class="text-xs text-gray-500 mr-auto" v-if="filteredAccounts.length > 0">
          {{ filteredAccounts.length }} accounts found
        </div>
        <button 
          @click="handleSelect" 
          :disabled="!selectedAccount"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">
          Select
        </button>
        <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'

export default {
  props: {
    show: {
      type: Boolean,
      required: true
    },
    customerAccounts: {
      type: Array,
      default: () => []
    },
    initialSortBy: {
      type: String,
      default: 'customer_code'
    },
    initialStatusFilter: {
      type: Array,
      default: () => ['Active']
    }
  },
  emits: ['close', 'select', 'sort'],
  setup(props, { emit }) {
    const allAccounts = ref([])
    const selectedAccount = ref(null)
    const loading = ref(false)
    const error = ref(null)
    const sortBy = ref(props.initialSortBy)
    const statusFilter = ref(props.initialStatusFilter.includes('Active') ? 'active' : (props.initialStatusFilter.includes('Obsolete') ? 'obsolete' : 'all'))

    const fetchCustomerAccounts = async () => {
      if (props.customerAccounts && props.customerAccounts.length > 0) {
        console.log('Using provided customer accounts:', props.customerAccounts.length)
        allAccounts.value = props.customerAccounts
        return
      }
      
      loading.value = true
      error.value = null
      
      try {
        console.log('Fetching customer accounts from API...')
        
        const response = await axios.get('/api/customer-accounts')
        const data = response.data
        
        if (data.error) {
          throw new Error(data.error)
        }
        
        if (Array.isArray(data)) {
          allAccounts.value = data
          console.log(`Loaded ${data.length} accounts`)
        } else if (data.data && Array.isArray(data.data)) {
          allAccounts.value = data.data
          console.log(`Loaded ${data.data.length} accounts from data property`)
        } else {
          console.error('Unexpected data format:', data)
          throw new Error('Invalid data format returned from server')
        }
        
        // If we have accounts but none are selected, select the first one by default
        if (allAccounts.value.length > 0 && !selectedAccount.value) {
          console.log('Auto-selecting first account')
          selectedAccount.value = allAccounts.value[0]
        }
      } catch (err) {
        console.error('Error details:', err)
        error.value = `${err.message || 'Unknown error'}`
      } finally {
        loading.value = false
      }
    }

    const filteredAccounts = computed(() => {
      let filtered = [...allAccounts.value]
      
      // Filter based on status
      if (statusFilter.value === 'active') {
        filtered = filtered.filter(account => account.status === 'Active' || account.status === undefined)
      } else if (statusFilter.value === 'obsolete') {
        filtered = filtered.filter(account => account.status === 'Inactive' || account.status === 'Obsolete')
      }
      
      // Sort based on selected field
      filtered.sort((a, b) => {
        const fieldA = a[sortBy.value]?.toString().toLowerCase() || ''
        const fieldB = b[sortBy.value]?.toString().toLowerCase() || ''
        return fieldA.localeCompare(fieldB)
      })
      
      return filtered
    })

    const selectAccount = (account) => {
      selectedAccount.value = account
    }

    const handleSelect = () => {
      if (selectedAccount.value) {
        console.log('Selected customer:', selectedAccount.value)
        emit('select', selectedAccount.value)
        emit('close')
      } else {
        console.warn('No customer account selected')
        error.value = 'Please select a customer account'
      }
    }
    
    // Watch for changes in sort options and emit sort event
    watch([sortBy, statusFilter], () => {
      emit('sort', {
        sortBy: sortBy.value,
        status: statusFilter.value
      })
    })
    
    // Watch for changes in the show prop to fetch data when modal is shown
    watch(() => props.show, (newValue) => {
      if (newValue === true) {
        fetchCustomerAccounts()
      }
    }, { immediate: true })

    // Watch for changes in external customer accounts
    watch(() => props.customerAccounts, (newAccounts) => {
      if (newAccounts && newAccounts.length > 0) {
        console.log('Customer accounts prop updated:', newAccounts.length)
        allAccounts.value = newAccounts
      }
    }, { deep: true })

    return {
      allAccounts,
      filteredAccounts,
      selectedAccount,
      loading,
      error,
      sortBy,
      statusFilter,
      selectAccount,
      handleSelect,
      fetchCustomerAccounts
    }
  }
}
</script> 