<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">Cancel RC Posting Batch</h1>
              <p class="text-red-100 mt-1">Cancel prepared RC posting batches</p>
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
                <span class="text-sm text-gray-600">{{ batches.length }} Batches Available</span>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Last Updated: {{ new Date().toLocaleString() }}
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Batch Selection Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Batch Selection</h3>
              </div>
              
              <div class="p-6">
                <form @submit.prevent="cancelBatch">
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

                  <!-- Batch Number Selection -->
                  <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Batch Number</label>
                    <div class="flex space-x-2">
                      <input
                        v-model="form.batch_number"
                        type="text"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter batch number"
                      />
                      <button
                        type="button"
                        @click="showBatchModal = true"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                      >
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="clearForm"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                      Clear
                    </button>
                    <button
                      type="submit"
                      :disabled="loading || !form.batch_number"
                      class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
                    >
                      <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                      Cancel Batch
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
                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                    <span>Only New batches can be cancelled.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <span>Cancelled batches cannot be restored.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-yellow-500 mt-1 mr-2"></i>
                    <span>Posted batches cannot be cancelled.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Batches Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">RC Posting Batches</h3>
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
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="batch in batches" :key="batch.batch_number" class="hover:bg-gray-50">
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
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Batch Selection Modal -->
    <div v-if="showBatchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Select Batch</h3>
            <button @click="showBatchModal = false" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Batch Number</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="batch in newBatches" :key="batch.batch_number" class="hover:bg-gray-50">
                  <td class="px-4 py-2 text-sm text-gray-900">{{ batch.batch_number }}</td>
                  <td class="px-4 py-2">
                    <span :class="`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${batch.status_badge_class}`">
                      {{ batch.status }}
                    </span>
                  </td>
                  <td class="px-4 py-2">
                    <button
                      @click="selectBatch(batch.batch_number)"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                    >
                      Select
                    </button>
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
const header = 'Cancel RC Posting Batch'
const title = 'Cancel RC Posting Batch - ERP System'

const loading = ref(false)
const showBatchModal = ref(false)
const batches = ref([])
const currentPeriod = ref('')

const form = reactive({
  batch_number: '',
  current_ic_period: '',
  current_ap_period: '',
  current_gl_period: ''
})

const newBatches = computed(() => {
  return batches.value.filter(batch => batch.status === 'New')
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

const cancelBatch = async () => {
  if (!form.batch_number) {
    toast.error('Please select a batch to cancel')
    return
  }

  loading.value = true
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/cancel', {
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
      clearForm()
      await fetchBatches()
    } else {
      toast.error(data.message || 'Failed to cancel batch')
    }
  } catch (error) {
    console.error('Error cancelling batch:', error)
    toast.error('Failed to cancel batch')
  } finally {
    loading.value = false
  }
}

const selectBatch = (batchNumber) => {
  form.batch_number = batchNumber
  showBatchModal.value = false
}

const clearForm = () => {
  form.batch_number = ''
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

onMounted(async () => {
  await fetchCurrentPeriods()
  await fetchBatches()
})
</script>
