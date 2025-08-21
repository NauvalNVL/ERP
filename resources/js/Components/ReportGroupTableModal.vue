<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
          Report Group Table
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-500 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Search Bar -->
      <div class="mb-4">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
            <i class="fas fa-search"></i>
          </span>
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search by code or name..." 
            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-y-auto max-h-96 border rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 sticky top-0">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Action
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                <div class="flex justify-center items-center space-x-2">
                  <i class="fas fa-spinner fa-spin"></i>
                  <span>Loading report groups...</span>
                </div>
              </td>
            </tr>
            <tr v-else-if="filteredReportGroups.length === 0">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                No report groups found
              </td>
            </tr>
            <tr v-for="reportGroup in filteredReportGroups" 
                :key="reportGroup.code" 
                :class="{'bg-blue-50': selectedReportGroup && selectedReportGroup.code === reportGroup.code}"
                @click="selectReportGroup(reportGroup)"
                class="hover:bg-gray-50 cursor-pointer">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ reportGroup.code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ reportGroup.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <button 
                  @click.stop="selectAndClose(reportGroup)"
                  class="text-blue-600 hover:text-blue-900 font-medium"
                >
                  Select
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <div class="flex justify-end space-x-3 mt-6">
        <button 
          @click="close" 
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
        >
          Exit
        </button>
        <button 
          @click="confirmSelection" 
          :disabled="!selectedReportGroup"
          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Select
        </button>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'


const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'reportGroupSelected'])

// State
const reportGroups = ref([])
const searchQuery = ref('')
const selectedReportGroup = ref(null)
const loading = ref(false)

// Computed
const filteredReportGroups = computed(() => {
  if (!searchQuery.value) {
    return reportGroups.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return reportGroups.value.filter(reportGroup => 
    (reportGroup.code && reportGroup.code.toLowerCase().includes(query)) ||
    (reportGroup.name && reportGroup.name.toLowerCase().includes(query))
  )
})

// Methods
const fetchReportGroups = async () => {
  loading.value = true
  try {
    const response = await axios.get('/material-management/system-requirement/report-groups/list')
    
    // Handle response format consistent with ReportGroup.vue
    if (response.data.status === 'success') {
      reportGroups.value = response.data.data || []
    } else {
      console.error('Failed to load report groups:', response.data.message)
      reportGroups.value = []
    }
  } catch (error) {
    console.error('Error fetching report groups:', error)
    // Fallback data if API fails
    reportGroups.value = []
  } finally {
    loading.value = false
  }
}

const selectReportGroup = (reportGroup) => {
  selectedReportGroup.value = reportGroup
}

const selectAndClose = (reportGroup) => {
  selectedReportGroup.value = reportGroup
  confirmSelection()
}

const confirmSelection = () => {
  if (selectedReportGroup.value) {
    emit('reportGroupSelected', selectedReportGroup.value)
    close()
  }
}

const close = () => {
  emit('close')
  searchQuery.value = ''
  selectedReportGroup.value = null
}

// Watch for modal open
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchReportGroups()
  }
})

onMounted(() => {
  if (props.show) {
    fetchReportGroups()
  }
})
</script>

<style scoped>
/* Sticky header */
thead th {
  position: sticky;
  top: 0;
  z-index: 10;
}

/* Scrollbar styling */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
