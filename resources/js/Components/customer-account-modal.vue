<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto p-2 sm:p-4 md:p-6">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>

    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl z-110 relative max-h-[95vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-1.5 sm:p-2 bg-white bg-opacity-30 rounded-lg mr-2 sm:mr-3">
            <i class="fas fa-user text-sm sm:text-base"></i>
          </div>
          <h3 class="text-base sm:text-xl font-semibold truncate">Customer Account Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px ml-2">
          <i class="fas fa-times text-lg sm:text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-3 sm:p-4 md:p-5 flex-1 overflow-hidden flex flex-col relative">
        <!-- Loading overlay for first-time fetch -->
        <div
          v-if="loading && allAccounts.length === 0"
          class="absolute inset-0 bg-white bg-opacity-60 flex items-center justify-center z-20"
        >
          <div class="flex flex-col items-center gap-2">
            <div class="animate-spin rounded-full h-8 w-8 border-2 border-blue-500 border-t-transparent"></div>
            <span class="text-xs sm:text-sm text-gray-600">Loading customer accounts...</span>
          </div>
        </div>

        <div class="mb-3 sm:mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search text-sm"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search customer accounts..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm sm:text-base">
        </div>
        </div>

        <!-- Filter Controls -->
        <div class="mb-3 sm:mb-4 flex flex-col sm:flex-row gap-2 sm:gap-3">
          <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Sort By:</label>
            <select v-model="sortBy" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs sm:text-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="customer_code">Customer Code</option>
              <option value="customer_name">Customer Name</option>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-auto rounded-lg border border-gray-200 flex-1 min-h-0">
          <table class="w-full divide-y divide-gray-200" id="customerAccountDataTable" style="min-width: 650px;">
            <thead class="bg-gray-50 sticky top-0">
            <tr>
                <th @click="sortTable('customer_code')" class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer whitespace-nowrap" style="width: 15%;">
                  <span class="hidden sm:inline">Code</span>
                  <span class="sm:hidden">Code</span>
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th @click="sortTable('customer_name')" class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer whitespace-nowrap" style="width: 35%;">
                  Name <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                  <span class="hidden md:inline">S/person</span>
                  <span class="md:hidden">S/P</span>
                </th>
                <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                  <span class="hidden sm:inline">Currency</span>
                  <span class="sm:hidden">Curr</span>
                </th>
                <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                  Status
                </th>
            </tr>
          </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="account in filteredAccounts" :key="account.customer_code"
                :class="[
                  'transition-colors',
                  isAccountSelectable(account) ? 'hover:bg-blue-50 cursor-pointer' : 'bg-gray-50 text-gray-400 cursor-not-allowed',
                  selectedAccount && selectedAccount.customer_code === account.customer_code ? 'bg-blue-100 border-l-4 border-blue-500' : ''
                ]"
                @click="onRowClick(account)"
                @dblclick="onRowDblClick(account)">
                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 whitespace-nowrap font-medium text-gray-900 text-xs">{{ account.customer_code }}</td>
                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 whitespace-nowrap text-gray-700 text-xs">
                  <div class="max-w-[200px] sm:max-w-none truncate" :title="account.customer_name">{{ account.customer_name }}</div>
                </td>
                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 whitespace-nowrap text-gray-700 text-xs">{{ account.salesperson_code || '-' }}</td>
                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 whitespace-nowrap text-gray-700 text-xs">{{ account.currency_code || '-' }}</td>
                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 whitespace-nowrap">
                  <span :class="(account.status === 'A' || account.status === 'Active' || !account.status) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    class="px-2 py-0.5 rounded-full text-xs">
                    {{ account.status === 'A' ? 'Active' : (account.status === 'I' ? 'Inactive' : (account.status || 'Active')) }}
                  </span>
                </td>
              </tr>
              <tr v-if="loading && allAccounts.length > 0">
                <td colspan="5" class="px-2 sm:px-4 md:px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500 text-xs">Loading...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="error">
                <td colspan="5" class="px-2 sm:px-4 md:px-6 py-4 text-center">
                  <div class="text-red-600 text-xs">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="hidden sm:inline">{{ error }}</span>
                    <span class="sm:hidden">Error loading data</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredAccounts.length === 0">
                <td colspan="5" class="px-2 sm:px-4 md:px-6 py-4 text-center text-gray-500">
                  <p class="text-xs">No accounts found.</p>
                  <div class="mt-1 text-xs hidden sm:block">
                    Total loaded: {{ allAccounts.length }}
                  </div>
                </td>
            </tr>
          </tbody>
        </table>
      </div>

        <div class="mt-2 text-xs text-gray-500 italic hidden md:block">
          <p>Click row to select, double-click to select and close.</p>
        </div>
        <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-1.5 sm:gap-2">
          <button type="button" @click="sortTable('customer_code')" class="py-1.5 sm:py-2 px-2 sm:px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-md sm:rounded-lg transform active:translate-y-px transition-all">
            <i class="fas fa-sort mr-1"></i><span class="hidden lg:inline">Sort </span>Code
          </button>
          <button type="button" @click="sortTable('customer_name')" class="py-1.5 sm:py-2 px-2 sm:px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-md sm:rounded-lg transform active:translate-y-px transition-all">
            <i class="fas fa-sort mr-1"></i><span class="hidden lg:inline">Sort </span>Name
          </button>
          <button
            type="button"
            @click="selectAndClose(selectedAccount)"
            :disabled="!selectedAccount || !isAccountSelectable(selectedAccount)"
            class="py-1.5 sm:py-2 px-2 sm:px-3 bg-blue-500 hover:bg-blue-600 disabled:bg-blue-300 disabled:cursor-not-allowed text-white text-xs rounded-md sm:rounded-lg transform active:translate-y-px transition-all"
          >
            <i class="fas fa-check mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('close')" class="py-1.5 sm:py-2 px-2 sm:px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-md sm:rounded-lg transform active:translate-y-px transition-all">
            <i class="fas fa-times mr-1"></i>Close
        </button>
        </div>
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
    },
    initialSearch: {
      type: String,
      default: ''
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
    const searchQuery = ref(props.initialSearch)
    const sortKey = ref('customer_code')
    const sortAsc = ref(true)

    const fetchCustomerAccounts = async () => {
      if (props.customerAccounts && props.customerAccounts.length > 0) {
        allAccounts.value = props.customerAccounts
        return
      }

      loading.value = true
      error.value = null

      try {
        console.log('Fetching customer accounts from API (active only)...')

        const response = await axios.get('/api/customers-with-status', {
          params: { status: 'active' }
        })
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

      // Filter based on search query
      if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase().trim()
        filtered = filtered.filter(account =>
          account.customer_code?.toLowerCase().includes(query) ||
          account.customer_name?.toLowerCase().includes(query)
        )
      }

      // Filter based on status
      if (statusFilter.value === 'active') {
        filtered = filtered.filter(account => account.status === 'A' || account.status === 'Active' || account.status === undefined)
      } else if (statusFilter.value === 'obsolete') {
        filtered = filtered.filter(account => account.status === 'I' || account.status === 'Inactive' || account.status === 'Obsolete')
      }

      // Sort based on selected field
      filtered.sort((a, b) => {
        const fieldA = a[sortBy.value]?.toString().toLowerCase() || ''
        const fieldB = b[sortBy.value]?.toString().toLowerCase() || ''
        return fieldA.localeCompare(fieldB)
      })

      return filtered
    })

    const isAccountSelectable = (account) => {
      return account && (account.status === 'A' || account.status === 'Active' || account.status === undefined || account.status === '')
    }

    const selectAccount = (account) => {
      if (!isAccountSelectable(account)) {
        return
      }
      selectedAccount.value = account
    }

    const selectAndClose = (account) => {
      if (account && isAccountSelectable(account)) {
        emit('select', account)
        emit('close')
      }
    }

    const onRowClick = (account) => {
      selectAccount(account)
    }

    const onRowDblClick = (account) => {
      selectAndClose(account)
    }

    const sortTable = (key) => {
      if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value
      } else {
        sortKey.value = key
        sortAsc.value = true
      }
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
    watch(
      () => props.show,
      (newValue) => {
        if (newValue === true) {
          // Only fetch if we don't already have data and no external customerAccounts are provided
          if (
            allAccounts.value.length === 0 &&
            !(props.customerAccounts && props.customerAccounts.length > 0)
          ) {
            fetchCustomerAccounts()
          }
        }
      },
      { immediate: true }
    )

    // Watch for changes in external customer accounts
    watch(
      () => props.customerAccounts,
      (newAccounts) => {
        if (newAccounts && newAccounts.length > 0) {
          console.log('Customer accounts prop updated:', newAccounts.length)
          allAccounts.value = newAccounts
        }
      },
      {
        deep: true,
        immediate: true
      }
    )

    // Watch for changes in initialSearch prop
    watch(() => props.initialSearch, (newSearch) => {
      searchQuery.value = newSearch
    })

    return {
      allAccounts,
      filteredAccounts,
      selectedAccount,
      loading,
      error,
      sortBy,
      statusFilter,
      searchQuery,
      sortKey,
      sortAsc,
      isAccountSelectable,
      selectAccount,
      selectAndClose,
      onRowClick,
      onRowDblClick,
      sortTable,
      handleSelect,
      fetchCustomerAccounts
    }
  }
}
</script>

<style scoped>
/* Add custom z-index classes */
.z-100 {
  z-index: 100;
}

.z-110 {
  z-index: 110;
}
</style>
