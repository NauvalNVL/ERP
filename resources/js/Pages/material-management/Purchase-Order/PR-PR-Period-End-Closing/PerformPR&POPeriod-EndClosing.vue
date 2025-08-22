<template>
  <AppLayout :header="'Perform PR & PO Period-End Closing'">
    <div class="max-w-4xl mx-auto">
      <!-- Current Period Information Card -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-calendar-alt text-blue-500 mr-3"></i>
            Current Period Information
          </h2>
          <div class="text-sm text-gray-500">
            <i class="fas fa-clock mr-1"></i>
            Last Updated: {{ lastUpdated }}
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Current P/O Period -->
          <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-blue-600">Current P/O Period</p>
                <p class="text-2xl font-bold text-blue-800">{{ currentPeriod }}</p>
              </div>
              <i class="fas fa-calendar text-blue-400 text-2xl"></i>
            </div>
          </div>
          
          <!-- PR Count -->
          <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-green-600">PR Count</p>
                <p class="text-2xl font-bold text-green-800">{{ prCount }}</p>
              </div>
              <i class="fas fa-clipboard-list text-green-400 text-2xl"></i>
            </div>
          </div>
          
          <!-- PO Count -->
          <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-purple-600">PO Count</p>
                <p class="text-2xl font-bold text-purple-800">{{ poCount }}</p>
              </div>
              <i class="fas fa-shopping-cart text-purple-400 text-2xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Zero PO Warning -->
      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
          <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 text-xl"></i>
          <div>
            <p class="font-medium text-yellow-800">Zero PO not Allowed to Close</p>
            <p class="text-sm text-yellow-700 mt-1">
              Please ensure all Purchase Orders are properly processed before closing the period.
            </p>
          </div>
        </div>
      </div>

      <!-- Ready to Close Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          Ready to Close
        </h3>
        
        <div class="space-y-4">
          <div class="flex items-center space-x-4">
            <label class="flex items-center">
              <input
                type="radio"
                v-model="readyToClose"
                value="Y"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm font-medium text-gray-700">Y - Yes</span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                v-model="readyToClose"
                value="N"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm font-medium text-gray-700">N - No</span>
            </label>
          </div>
          
          <div class="text-sm text-gray-600">
            <p>Select "Y - Yes" when you are ready to close the current period.</p>
          </div>
        </div>
      </div>

      <!-- Attention Section -->
      <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
          <i class="fas fa-exclamation-circle text-red-600 mr-3 text-xl mt-1"></i>
          <div>
            <h3 class="text-lg font-bold text-red-800 mb-3">Attention</h3>
            <div class="space-y-3">
              <div class="flex items-start">
                <i class="fas fa-check text-red-500 mr-2 mt-1"></i>
                <p class="text-red-700">
                  <strong>Ensure nobody is using the Purchase Order module.</strong>
                  <br>
                  <span class="text-sm">All users must log out or complete their current transactions.</span>
                </p>
              </div>
              <div class="flex items-start">
                <i class="fas fa-check text-red-500 mr-2 mt-1"></i>
                <p class="text-red-700">
                  <strong>Ensure that you have already backup the data.</strong>
                  <br>
                  <span class="text-sm">Create a complete backup before proceeding with period closure.</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4">
        <button
          @click="performPeriodClosing"
          :disabled="!canClose || isProcessing"
          class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
        >
          <i class="fas fa-play mr-2" v-if="!isProcessing"></i>
          <i class="fas fa-spinner fa-spin mr-2" v-else></i>
          {{ isProcessing ? 'Processing...' : 'Perform Period Closing' }}
        </button>
        
        <button
          @click="refreshData"
          :disabled="isProcessing"
          class="flex-1 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
        >
          <i class="fas fa-sync-alt mr-2"></i>
          Refresh Data
        </button>
        
        <button
          @click="exportReport"
          :disabled="isProcessing"
          class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
        >
          <i class="fas fa-download mr-2"></i>
          Export Report
        </button>
      </div>

      <!-- Status Messages -->
      <div v-if="statusMessage" class="mt-6 p-4 rounded-lg" :class="statusMessageClass">
        <div class="flex items-center">
          <i :class="statusMessageIcon" class="mr-2"></i>
          <p class="font-medium">{{ statusMessage }}</p>
        </div>
      </div>

      <!-- Processing Modal -->
      <div v-if="showProcessingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
          <div class="text-center">
            <i class="fas fa-spinner fa-spin text-blue-600 text-4xl mb-4"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Processing Period Closing</h3>
            <p class="text-gray-600 mb-4">Please wait while the system processes the period closure...</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: progress + '%' }"></div>
            </div>
            <p class="text-sm text-gray-500 mt-2">{{ progress }}% Complete</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()

// Reactive data
const currentPeriod = ref('6 2025')
const prCount = ref(420)
const poCount = ref(540)
const readyToClose = ref('N')
const isProcessing = ref(false)
const showProcessingModal = ref(false)
const progress = ref(0)
const statusMessage = ref('')
const lastUpdated = ref('')

// Computed properties
const canClose = computed(() => {
  return readyToClose.value === 'Y' && prCount.value > 0 && poCount.value > 0
})

const statusMessageClass = computed(() => {
  if (statusMessage.value.includes('success')) {
    return 'bg-green-50 border border-green-200 text-green-800'
  } else if (statusMessage.value.includes('error')) {
    return 'bg-red-50 border border-red-200 text-red-800'
  } else if (statusMessage.value.includes('warning')) {
    return 'bg-yellow-50 border border-yellow-200 text-yellow-800'
  }
  return 'bg-blue-50 border border-blue-200 text-blue-800'
})

const statusMessageIcon = computed(() => {
  if (statusMessage.value.includes('success')) {
    return 'fas fa-check-circle text-green-600'
  } else if (statusMessage.value.includes('error')) {
    return 'fas fa-times-circle text-red-600'
  } else if (statusMessage.value.includes('warning')) {
    return 'fas fa-exclamation-triangle text-yellow-600'
  }
  return 'fas fa-info-circle text-blue-600'
})

// Methods
const refreshData = async () => {
  try {
    isProcessing.value = true
    statusMessage.value = 'Refreshing data...'
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Update data (in real app, this would come from API)
    prCount.value = Math.floor(Math.random() * 500) + 300
    poCount.value = Math.floor(Math.random() * 600) + 400
    lastUpdated.value = new Date().toLocaleString()
    
    statusMessage.value = 'Data refreshed successfully!'
    toast.success('Data refreshed successfully!')
    
    setTimeout(() => {
      statusMessage.value = ''
    }, 3000)
    
  } catch (error) {
    statusMessage.value = 'Error refreshing data: ' + error.message
    toast.error('Failed to refresh data')
  } finally {
    isProcessing.value = false
  }
}

const performPeriodClosing = async () => {
  if (!canClose.value) {
    toast.warning('Please ensure all conditions are met before closing the period')
    return
  }

  try {
    showProcessingModal.value = true
    isProcessing.value = true
    progress.value = 0
    
    // Simulate processing steps
    const steps = [
      'Validating period data...',
      'Checking for open transactions...',
      'Creating backup...',
      'Closing Purchase Requisitions...',
      'Closing Purchase Orders...',
      'Updating period status...',
      'Finalizing closure...'
    ]
    
    for (let i = 0; i < steps.length; i++) {
      statusMessage.value = steps[i]
      progress.value = Math.round(((i + 1) / steps.length) * 100)
      await new Promise(resolve => setTimeout(resolve, 800))
    }
    
    // Success
    statusMessage.value = 'Period closed successfully!'
    toast.success('Period closed successfully!')
    
    // Reset form
    readyToClose.value = 'N'
    
    // Close modal after delay
    setTimeout(() => {
      showProcessingModal.value = false
      isProcessing.value = false
    }, 2000)
    
  } catch (error) {
    statusMessage.value = 'Error closing period: ' + error.message
    toast.error('Failed to close period')
    showProcessingModal.value = false
    isProcessing.value = false
  }
}

const exportReport = async () => {
  try {
    isProcessing.value = true
    statusMessage.value = 'Generating report...'
    
    // Simulate report generation
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Create and download report
    const reportData = {
      period: currentPeriod.value,
      prCount: prCount.value,
      poCount: poCount.value,
      generatedAt: new Date().toISOString(),
      status: readyToClose.value === 'Y' ? 'Ready to Close' : 'Not Ready'
    }
    
    const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `PR_PO_Period_Closing_${currentPeriod.value.replace(' ', '_')}.json`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
    
    statusMessage.value = 'Report exported successfully!'
    toast.success('Report exported successfully!')
    
    setTimeout(() => {
      statusMessage.value = ''
    }, 3000)
    
  } catch (error) {
    statusMessage.value = 'Error exporting report: ' + error.message
    toast.error('Failed to export report')
  } finally {
    isProcessing.value = false
  }
}

// Initialize data
onMounted(() => {
  lastUpdated.value = new Date().toLocaleString()
})
</script>

<style scoped>
/* Custom animations */
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions */
.transition-all {
  transition: all 0.3s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
