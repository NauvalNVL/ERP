<template>
  <AppLayout :header="'Run Stock-Take New Batch'">
    <Head title="Run Stock-Take New Batch" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-3 rounded-lg">
              <i class="fas fa-play-circle text-white text-2xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Run Stock-Take New Batch</h1>
              <p class="text-gray-600">Create a new stock-take batch for inventory verification</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Current Time</div>
            <div class="text-lg font-semibold text-gray-800">{{ currentTime }}</div>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">System: Online</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">Database: Connected</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">Batch Engine: Ready</span>
            </div>
          </div>
          <div class="text-right">
            <span class="text-sm text-gray-500">Last Updated: {{ lastUpdated }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Period Information -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Current IC Period
          </h2>
          
          <div class="space-y-4">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
              <div class="text-sm text-gray-600 mb-1">Current IC Period</div>
              <div class="text-2xl font-bold text-blue-600">{{ currentPeriod }}</div>
            </div>
          </div>
        </div>

        <!-- Last Batch Information -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-purple-500 mr-2"></i>
            Last Batch Information
          </h2>
          
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Run Period:</span>
              <span class="font-semibold text-gray-800">{{ lastBatch.runPeriod }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Run Date:</span>
              <span class="font-semibold text-gray-800">{{ lastBatch.runDate }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Run Time:</span>
              <span class="font-semibold text-gray-800">{{ lastBatch.runTime }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Run UID:</span>
              <span class="font-semibold text-gray-800">{{ lastBatch.runUid }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Run Ref#:</span>
              <span class="font-semibold text-gray-800">{{ lastBatch.runRef }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- This Batch Configuration -->
      <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-cog text-green-500 mr-2"></i>
          This Batch Configuration
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Run Period</label>
            <input 
              v-model="thisBatch.runPeriod" 
              type="text" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              readonly
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Run Ref#</label>
            <input 
              v-model="thisBatch.runRef" 
              type="text" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter reference number"
            >
          </div>
        </div>
      </div>

      <!-- Action Button -->
      <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex justify-center">
          <button 
            @click="runBatch" 
            :disabled="!thisBatch.runRef"
            class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center text-lg">
            <i class="fas fa-play mr-2"></i>
            OK to Run
          </button>
        </div>
      </div>

      <!-- Warning Section -->
      <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg p-6 mt-6">
        <div class="flex items-start">
          <div class="bg-red-500 p-2 rounded-lg mr-4">
            <i class="fas fa-exclamation-triangle text-white"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-red-800 mb-3">Note</h3>
            <p class="text-sm text-gray-700">
              Please make sure no one is using Material Management System first.
            </p>
          </div>
        </div>
      </div>

      <!-- Confirmation Modal -->
      <div v-if="showConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <div class="text-center">
            <div class="bg-blue-500 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
              <i class="fas fa-play text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirm New Batch</h3>
            <div class="space-y-2 mb-6">
              <div class="text-sm text-gray-600">
                <span class="font-medium">Run Period:</span> {{ thisBatch.runPeriod }}
              </div>
              <div class="text-sm text-gray-600">
                <span class="font-medium">Run Ref#:</span> {{ thisBatch.runRef }}
              </div>
            </div>
            <div class="bg-blue-100 border border-blue-300 rounded-lg p-3 mb-6">
              <div class="text-blue-800 font-semibold text-sm">Ready to create new stock-take batch?</div>
            </div>
            <div class="flex space-x-3">
              <button @click="confirmRunBatch" 
                      class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                OK
              </button>
              <button @click="cancelRunBatch" 
                      class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import Swal from 'sweetalert2'

const { success, error, warning } = useToast()

// Reactive data
const currentTime = ref('')
const lastUpdated = ref('')
const currentPeriod = ref('6 2025')
const showConfirmationModal = ref(false)

// Last batch information from the image
const lastBatch = ref({
  runPeriod: '5 2025',
  runDate: '26/05/2025',
  runTime: '09:13',
  runUid: 'acc03',
  runRef: 'STOCK OPNAME 05/2025'
})

// This batch configuration
const thisBatch = ref({
  runPeriod: '6 2025',
  runRef: ''
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('en-US', { 
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
  lastUpdated.value = now.toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

const runBatch = () => {
  if (!thisBatch.value.runRef.trim()) {
    warning('Please enter a Run Ref# to proceed')
    return
  }

  showConfirmationModal.value = true
}

const confirmRunBatch = async () => {
  try {
    showConfirmationModal.value = false
    
    // Show loading
    Swal.fire({
      title: 'Processing...',
      text: 'Creating new stock-take batch',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    })

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Update last batch info
    lastBatch.value = {
      runPeriod: thisBatch.value.runPeriod,
      runDate: new Date().toLocaleDateString('en-GB'),
      runTime: new Date().toLocaleTimeString('en-US', { 
        hour12: false,
        hour: '2-digit',
        minute: '2-digit'
      }),
      runUid: 'user2',
      runRef: thisBatch.value.runRef
    }

    // Clear current batch ref
    thisBatch.value.runRef = ''

    Swal.fire({
      icon: 'success',
      title: 'Batch Created Successfully',
      text: 'New stock-take batch has been created and is ready for data input.',
      confirmButtonColor: '#10B981'
    })

    success('Stock-take batch created successfully')
  } catch (err) {
    console.error('Error creating batch:', err)
    error('Failed to create stock-take batch')
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to create stock-take batch. Please try again.',
      confirmButtonColor: '#EF4444'
    })
  }
}

const cancelRunBatch = () => {
  showConfirmationModal.value = false
}

// Lifecycle hooks
let timeInterval

onMounted(() => {
  updateCurrentTime()
  timeInterval = setInterval(updateCurrentTime, 1000)
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style>
