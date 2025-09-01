<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">View & Print RC Posting Batch</h1>
              <p class="text-purple-100 mt-1">View and print RC posting batch reports</p>
            </div>
            <div class="text-right text-white">
              <div class="text-sm opacity-75">Current Period</div>
              <div class="text-lg font-semibold">{{ currentPeriod }}</div>
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
                <span class="text-sm text-gray-600">{{ filteredBatches.length }} Batches Found</span>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Last Updated: {{ new Date().toLocaleString() }}
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Filter Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filter Options</h3>
              </div>
              
              <div class="p-6">
                <form @submit.prevent="applyFilters">
                  <!-- Period Information -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current IC Period</label>
                      <input
                        v-model="form.current_ic_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current AP Period</label>
                      <input
                        v-model="form.current_ap_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current GL Period</label>
                      <input
                        v-model="form.current_gl_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                  </div>

                  <!-- Batch Range Selection -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Batch Start</label>
                      <input
                        v-model="form.batch_start"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter start batch number"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Batch End</label>
                      <input
                        v-model="form.batch_end"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter end batch number"
                      />
                    </div>
                  </div>

                  <!-- Status Filters -->
                  <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Post Status</label>
                    <div class="space-y-2">
                      <label class="flex items-center">
                        <input
                          v-model="form.status"
                          type="checkbox"
                          value="New"
                          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-700">N-New</span>
                      </label>
                      <label class="flex items-center">
                        <input
                          v-model="form.status"
                          type="checkbox"
                          value="Cancelled"
                          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-700">X-Cancelled</span>
                      </label>
                      <label class="flex items-center">
                        <input
                          v-model="form.status"
                          type="checkbox"
                          value="Posted"
                          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-700">P-Posted</span>
                      </label>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="clearFilters"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                      Clear Filters
                    </button>
                    <button
                      type="submit"
                      :disabled="loading"
                      class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 disabled:opacity-50"
                    >
                      <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                      Apply Filters
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
                    <span class="text-sm text-gray-600">Total Batches</span>
                    <span class="text-lg font-semibold text-gray-900">{{ batches.length }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Filtered Results</span>
                    <span class="text-lg font-semibold text-purple-600">{{ filteredBatches.length }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">New Batches</span>
                    <span class="text-lg font-semibold text-blue-600">{{ newBatchesCount }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Cancelled Batches</span>
                    <span class="text-lg font-semibold text-red-600">{{ cancelledBatchesCount }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Posted Batches</span>
                    <span class="text-lg font-semibold text-green-600">{{ postedBatchesCount }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Important Notes</h3>
              </div>
              
              <div class="p-6">
                <div class="space-y-3 text-sm text-gray-600">
                  <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <span>Posted and Cancelled batches will be skipped.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                    <span>Select status filters to view specific batches.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-print text-purple-500 mt-1 mr-2"></i>
                    <span>Use the print button to generate reports.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Batches Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">RC Posting Batches</h3>
            <button
              @click="printReport"
              :disabled="filteredBatches.length === 0"
              class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
            >
              <i class="fas fa-print mr-2"></i>
              Print Report
            </button>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Note</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Note</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Records</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Errors</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Print Count</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Accrued RC</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prepared By</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prepared Date</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="batch in filteredBatches" :key="batch.batch_number" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ batch.batch_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.start_note_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.end_note_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.total_records }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.total_errors }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.print_count }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${batch.status_badge_class}`">
                      {{ batch.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(batch.total_accrued_rc) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.prepared_by }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(batch.prepared_date) }}</td>
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
const header = 'View & Print RC Posting Batch'
const title = 'View & Print RC Posting Batch - ERP System'

const loading = ref(false)
const batches = ref([])
const currentPeriod = ref('')

const form = reactive({
  batch_start: '',
  batch_end: '',
  status: ['New'],
  current_ic_period: '',
  current_ap_period: '',
  current_gl_period: ''
})

const filteredBatches = computed(() => {
  let filtered = batches.value

  // Filter by batch range
  if (form.batch_start) {
    filtered = filtered.filter(batch => batch.batch_number >= form.batch_start)
  }
  if (form.batch_end) {
    filtered = filtered.filter(batch => batch.batch_number <= form.batch_end)
  }

  // Filter by status
  if (form.status.length > 0) {
    filtered = filtered.filter(batch => form.status.includes(batch.status))
  }

  return filtered
})

const newBatchesCount = computed(() => {
  return batches.value.filter(batch => batch.status === 'New').length
})

const cancelledBatchesCount = computed(() => {
  return batches.value.filter(batch => batch.status === 'Cancelled').length
})

const postedBatchesCount = computed(() => {
  return batches.value.filter(batch => batch.status === 'Posted').length
})

const fetchCurrentPeriods = async () => {
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/current-periods')
    const data = await response.json()
    
    form.current_ic_period = data.current_ic_period
    form.current_ap_period = data.current_ap_period
    form.current_gl_period = data.current_gl_period
    currentPeriod.value = data.current_ic_period
  } catch (error) {
    console.error('Error fetching current periods:', error)
    toast.error('Failed to load current periods')
  }
}

const fetchBatches = async () => {
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/batches')
    const data = await response.json()
    batches.value = data
  } catch (error) {
    console.error('Error fetching batches:', error)
    toast.error('Failed to load batches')
  }
}

const applyFilters = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (form.batch_start) params.append('batch_start', form.batch_start)
    if (form.batch_end) params.append('batch_end', form.batch_end)
    form.status.forEach(status => params.append('status[]', status))

    const response = await fetch(`/api/material-management/accounts/rc-posting-batch/batches?${params}`)
    const data = await response.json()
    batches.value = data
    toast.success('Filters applied successfully')
  } catch (error) {
    console.error('Error applying filters:', error)
    toast.error('Failed to apply filters')
  } finally {
    loading.value = false
  }
}

const clearFilters = () => {
  form.batch_start = ''
  form.batch_end = ''
  form.status = ['New']
  fetchBatches()
}

const printReport = () => {
  if (filteredBatches.value.length === 0) {
    toast.error('No batches to print')
    return
  }

  // Create print window
  const printWindow = window.open('', '_blank')
  const printContent = `
    <html>
      <head>
        <title>RC Posting Batch Report</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
          .header { text-align: center; margin-bottom: 20px; }
          .summary { margin-bottom: 20px; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>RC Posting Batch Report</h1>
          <p>Generated on: ${new Date().toLocaleString()}</p>
          <p>Current Period: ${currentPeriod.value}</p>
        </div>
        
        <div class="summary">
          <p><strong>Total Batches:</strong> ${filteredBatches.value.length}</p>
          <p><strong>New Batches:</strong> ${filteredBatches.value.filter(b => b.status === 'New').length}</p>
          <p><strong>Cancelled Batches:</strong> ${filteredBatches.value.filter(b => b.status === 'Cancelled').length}</p>
          <p><strong>Posted Batches:</strong> ${filteredBatches.value.filter(b => b.status === 'Posted').length}</p>
        </div>
        
        <table>
          <thead>
            <tr>
              <th>Batch Number</th>
              <th>Start Note</th>
              <th>End Note</th>
              <th>Total Records</th>
              <th>Total Errors</th>
              <th>Print Count</th>
              <th>Status</th>
              <th>Total Accrued RC</th>
              <th>Prepared By</th>
              <th>Prepared Date</th>
            </tr>
          </thead>
          <tbody>
            ${filteredBatches.value.map(batch => `
              <tr>
                <td>${batch.batch_number}</td>
                <td>${batch.start_note_number}</td>
                <td>${batch.end_note_number}</td>
                <td>${batch.total_records}</td>
                <td>${batch.total_errors}</td>
                <td>${batch.print_count}</td>
                <td>${batch.status}</td>
                <td>${formatCurrency(batch.total_accrued_rc)}</td>
                <td>${batch.prepared_by}</td>
                <td>${formatDate(batch.prepared_date)}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </body>
    </html>
  `
  
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
  toast.success('Print report generated')
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(async () => {
  await fetchCurrentPeriods()
  await fetchBatches()
})
</script>
