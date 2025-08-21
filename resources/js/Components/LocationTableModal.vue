<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
          Location Table
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
                  <span>Loading locations...</span>
                </div>
              </td>
            </tr>
            <tr v-else-if="filteredLocations.length === 0">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                No locations found
              </td>
            </tr>
            <tr v-for="location in filteredLocations" 
                :key="location.code" 
                :class="{'bg-blue-50': selectedLocation && selectedLocation.code === location.code}"
                @click="selectLocation(location)"
                class="hover:bg-gray-50 cursor-pointer">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ location.code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ location.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <button 
                  @click.stop="selectAndClose(location)"
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
          :disabled="!selectedLocation"
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

const emit = defineEmits(['close', 'locationSelected'])

// State
const locations = ref([])
const searchQuery = ref('')
const selectedLocation = ref(null)
const loading = ref(false)

// Computed
const filteredLocations = computed(() => {
  if (!searchQuery.value) {
    return locations.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return locations.value.filter(location => 
    location.code.toLowerCase().includes(query) ||
    location.name.toLowerCase().includes(query)
  )
})

// Methods
const fetchLocations = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/material-management/locations')
    locations.value = response.data
  } catch (error) {
    console.error('Error fetching locations:', error)
  } finally {
    loading.value = false
  }
}

const selectLocation = (location) => {
  selectedLocation.value = location
}

const selectAndClose = (location) => {
  selectedLocation.value = location
  confirmSelection()
}

const confirmSelection = () => {
  if (selectedLocation.value) {
    emit('locationSelected', selectedLocation.value)
    close()
  }
}

const close = () => {
  emit('close')
  searchQuery.value = ''
  selectedLocation.value = null
}

// Watch for modal open
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchLocations()
  }
})

onMounted(() => {
  if (props.show) {
    fetchLocations()
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
