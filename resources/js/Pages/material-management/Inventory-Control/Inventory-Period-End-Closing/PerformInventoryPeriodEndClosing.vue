<template>
  <AppLayout :header="'Perform Inventory Period-End Closing'">
    <Head title="Perform Inventory Period-End Closing" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-3 rounded-lg">
              <i class="fas fa-calendar-alt text-white text-2xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Perform Inventory Period-End Closing</h1>
              <p class="text-gray-600">Close current inventory period and advance to next period</p>
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
              <span class="text-sm font-medium text-gray-700">Report Engine: Ready</span>
            </div>
          </div>
          <div class="text-right">
            <span class="text-sm text-gray-500">Last Updated: {{ lastUpdated }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Period Information -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
              <i class="fas fa-info-circle text-blue-500 mr-2"></i>
              Current Period Information
            </h2>
            
            <div class="space-y-4">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                <div class="text-sm text-gray-600 mb-1">Current I/C Period</div>
                <div class="text-2xl font-bold text-blue-600">{{ currentPeriod }}</div>
              </div>
              
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                <div class="text-sm text-gray-600 mb-1">Next Period</div>
                <div class="text-2xl font-bold text-green-600">{{ nextPeriod }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Transaction Counts -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
              <i class="fas fa-list-ol text-purple-500 mr-2"></i>
              Transaction Counts
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-for="(count, index) in transactionCounts" :key="index" 
                   class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                <div class="text-sm text-gray-600 mb-1">{{ count.label }}</div>
                <div class="text-2xl font-bold" :class="getCountClass(count.value)">
                  {{ count.value }}
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ count.description }}</div>
              </div>
            </div>

            <!-- Zero Transaction Status -->
            <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
              <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                <span class="text-sm font-medium text-gray-700">Zero Tran: {{ zeroTranStatus }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ready to Close Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          Ready to Close
        </h2>
        
        <div class="flex items-center space-x-6">
          <div class="flex items-center space-x-4">
            <label class="flex items-center">
              <input type="radio" v-model="readyToClose" value="Y" 
                     class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
              <span class="ml-2 text-sm font-medium text-gray-700">Y-Yes</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="readyToClose" value="N" 
                     class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
              <span class="ml-2 text-sm font-medium text-gray-700">N-No</span>
            </label>
          </div>
          
          <button @click="performClosing" 
                  :disabled="readyToClose !== 'Y'"
                  class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center">
            <i class="fas fa-play mr-2"></i>
            Perform Closing
          </button>
        </div>
      </div>

      <!-- Attention Section -->
      <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg p-6 mt-6">
        <div class="flex items-start">
          <div class="bg-red-500 p-2 rounded-lg mr-4">
            <i class="fas fa-exclamation-triangle text-white"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-red-800 mb-3">Attention</h3>
            <ul class="space-y-2">
              <li class="flex items-start">
                <i class="fas fa-check-circle text-red-500 mr-2 mt-1"></i>
                <span class="text-sm text-gray-700">Ensure nobody is using the Inventory Control module.</span>
              </li>
              <li class="flex items-start">
                <i class="fas fa-check-circle text-red-500 mr-2 mt-1"></i>
                <span class="text-sm text-gray-700">Ensure that you have already backup the data.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Confirmation Modal -->
      <div v-if="showConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <div class="text-center">
            <div class="bg-red-500 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
              <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Final Confirmation</h3>
            <div class="space-y-2 mb-6">
              <div class="text-sm text-gray-600">
                <span class="font-medium">Current Period:</span> {{ currentPeriod }}
              </div>
              <div class="text-sm text-gray-600">
                <span class="font-medium">New Period:</span> {{ nextPeriod }}
              </div>
            </div>
            <div class="bg-red-100 border border-red-300 rounded-lg p-3 mb-6">
              <div class="text-red-800 font-semibold text-sm">Warning! Closing to future Period!</div>
              <div class="text-red-700 text-sm mt-1">Confirm to proceed?</div>
            </div>
            <div class="flex space-x-3">
              <button @click="confirmClosing" 
                      class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                OK
              </button>
              <button @click="cancelClosing" 
                      class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                Exit
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
const nextPeriod = ref('7 2025')
const readyToClose = ref('N')
const showConfirmationModal = ref(false)

// Transaction counts from the image
const transactionCounts = ref([
  { label: 'RC Count', value: 441, description: 'Receive Count' },
  { label: 'RT Count', value: 3, description: 'Return Count' },
  { label: 'IS Count', value: 1432, description: 'Issue Count' },
  { label: 'MI Count', value: 3, description: 'Material Issue Count' },
  { label: 'MO Count', value: 0, description: 'Material Order Count' },
  { label: 'DN Count', value: 0, description: 'Debit Note Count' },
  { label: 'CN Count', value: 0, description: 'Credit Note Count' },
  { label: 'LT Count', value: 0, description: 'Location Transfer Count' }
])

const zeroTranStatus = ref('Zero Tran is Allowed to Close')

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

const getCountClass = (value) => {
  if (value === 0) return 'text-gray-400'
  if (value > 100) return 'text-red-600'
  if (value > 10) return 'text-orange-600'
  return 'text-green-600'
}

const performClosing = () => {
  if (readyToClose.value !== 'Y') {
    warning('Please select "Y-Yes" to proceed with closing')
    return
  }

  // Check if there are any pending transactions
  const pendingCounts = transactionCounts.value.filter(count => count.value > 0)
  if (pendingCounts.length > 0) {
    warning(`There are ${pendingCounts.length} transaction types with pending items. Please review before closing.`)
    return
  }

  showConfirmationModal.value = true
}

const confirmClosing = async () => {
  try {
    showConfirmationModal.value = false
    
    // Show loading
    Swal.fire({
      title: 'Processing...',
      text: 'Closing inventory period',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    })

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Update periods
    currentPeriod.value = nextPeriod.value
    const [month, year] = nextPeriod.value.split(' ')
    const nextMonth = parseInt(month) + 1
    const nextYear = nextMonth > 12 ? parseInt(year) + 1 : parseInt(year)
    const newNextMonth = nextMonth > 12 ? 1 : nextMonth
    nextPeriod.value = `${newNextMonth} ${nextYear}`

    // Reset transaction counts
    transactionCounts.value.forEach(count => {
      count.value = 0
    })

    // Reset ready to close
    readyToClose.value = 'N'

    Swal.fire({
      icon: 'success',
      title: 'Period Closed Successfully',
      text: `Inventory period has been closed. Current period is now ${currentPeriod.value}`,
      confirmButtonColor: '#10B981'
    })

    success('Inventory period closed successfully')
  } catch (err) {
    console.error('Error closing period:', err)
    error('Failed to close inventory period')
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to close inventory period. Please try again.',
      confirmButtonColor: '#EF4444'
    })
  }
}

const cancelClosing = () => {
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
