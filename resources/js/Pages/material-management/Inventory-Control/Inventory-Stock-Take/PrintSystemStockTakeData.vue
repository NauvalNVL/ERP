<template>
  <AppLayout :header="'Print System Stock-Take Data'">
    <Head title="Print System Stock-Take Data" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-3 rounded-lg">
              <i class="fas fa-database text-white text-2xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Print System Stock-Take Data</h1>
              <p class="text-gray-600">Generate system stock-take data reports</p>
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
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-filter text-blue-500 mr-2"></i>
          System Report Filters
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <div class="flex space-x-2">
              <input 
                v-model="filters.categoryFrom" 
                type="text" 
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="From"
              >
              <span class="flex items-center text-gray-500">to</span>
              <input 
                v-model="filters.categoryTo" 
                type="text" 
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="To"
              >
              <button @click="openCategoryLookup" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- SKU# Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU#</label>
            <div class="flex space-x-2">
              <input 
                v-model="filters.skuFrom" 
                type="text" 
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="From"
              >
              <span class="flex items-center text-gray-500">to</span>
              <input 
                v-model="filters.skuTo" 
                type="text" 
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="To"
              >
              <button @click="openSkuLookup" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-end mt-6">
          <button @click="proceedToPrint" class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 flex items-center">
            <i class="fas fa-play mr-2"></i>
            Proceed
          </button>
        </div>
      </div>

      <!-- Category Lookup Modal -->
      <div v-if="showCategoryLookup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Category Table</h3>
            <button @click="closeCategoryLookup" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="category in categoryList" :key="category.code" 
                    @click="selectCategory(category)"
                    class="hover:bg-gray-50 cursor-pointer">
                  <td class="px-3 py-2 text-sm text-gray-900">{{ category.code }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ category.name }}</td>
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
import { ref, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import Swal from 'sweetalert2'

const { success, error, warning } = useToast()

// Reactive data
const currentTime = ref('')
const lastUpdated = ref('')
const showCategoryLookup = ref(false)

// Filters
const filters = ref({
  categoryFrom: '#A1.01',
  categoryTo: '#A1.02',
  skuFrom: '',
  skuTo: ''
})

// Category list from the image
const categoryList = ref([
  { code: '#A1.01', name: 'BEARING' },
  { code: '#A1.02', name: 'BEARING' },
  { code: '#A1.03', name: 'BEARING' },
  { code: '#A1.04', name: 'BEARING' },
  { code: '#A1.05', name: 'BEARING' },
  { code: '#A1.06', name: 'BEARING' },
  { code: '#A1.07', name: 'BEARING' },
  { code: '#A1.08', name: 'BEARING' },
  { code: '#A1.09', name: 'BEARING' },
  { code: '#A1.10', name: 'BEARING' },
  { code: '#A1.11', name: 'LOCKNUT' },
  { code: '#A1.12', name: 'BEARING' },
  { code: '#A1.13', name: 'PUSH BOTOM' },
  { code: '#A1.14', name: 'FITING ANGIN' },
  { code: '#A1.15', name: 'FITING ANGIN' },
  { code: '#A1.16', name: 'FITING ANGIN' },
  { code: '#A2.01', name: 'BEARING FILLO BLOCK' },
  { code: '#A2.02', name: 'BEARING FILLO BLOCK' },
  { code: '#A2.03', name: 'BEARING FILLO BLOCK' },
  { code: '#A2.04', name: 'BEARING FILLO BLOCK' }
])

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

const openCategoryLookup = () => {
  showCategoryLookup.value = true
}

const closeCategoryLookup = () => {
  showCategoryLookup.value = false
}

const selectCategory = (category) => {
  filters.value.categoryFrom = category.code
  filters.value.categoryTo = category.code
  closeCategoryLookup()
  success(`Category ${category.code} selected`)
}

const openSkuLookup = () => {
  // Mock SKU lookup
  const skus = ['000055', '055-AT1039', '007-S01327', '004-B02520']
  const randomSku = skus[Math.floor(Math.random() * skus.length)]
  filters.value.skuFrom = randomSku
  filters.value.skuTo = randomSku
  success(`SKU ${randomSku} selected`)
}

const proceedToPrint = async () => {
  // Validate filters
  if (!filters.value.categoryFrom || !filters.value.categoryTo) {
    warning('Please select category range')
    return
  }

  try {
    // Show loading
    Swal.fire({
      title: 'Generating System Report...',
      text: 'Preparing system stock-take data report',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    })

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Mock report generation
    const reportData = {
      title: 'SYSTEM STOCK-TAKE DATA',
      date: new Date().toLocaleDateString('en-GB'),
      time: new Date().toLocaleTimeString('en-US', { hour12: false }),
      filters: filters.value,
      records: 23,
      pages: 2
    }

    Swal.fire({
      icon: 'success',
      title: 'System Report Generated Successfully',
      html: `
        <div class="text-left">
          <p><strong>Report:</strong> ${reportData.title}</p>
          <p><strong>Date:</strong> ${reportData.date}</p>
          <p><strong>Time:</strong> ${reportData.time}</p>
          <p><strong>Records:</strong> ${reportData.records}</p>
          <p><strong>Pages:</strong> ${reportData.pages}</p>
        </div>
      `,
      confirmButtonText: 'View Report',
      showCancelButton: true,
      cancelButtonText: 'Close'
    }).then((result) => {
      if (result.isConfirmed) {
        // In a real implementation, this would open the report viewer
        success('Opening system report viewer...')
      }
    })

    success('System stock-take data report generated successfully')
  } catch (err) {
    console.error('Error generating system report:', err)
    error('Failed to generate system report')
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to generate system report. Please try again.',
      confirmButtonColor: '#EF4444'
    })
  }
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
