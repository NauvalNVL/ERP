<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
      <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Location Lookup</h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
          <i class="fas fa-times text-xl"></i>
        </button>
          </div>
      </div>

      <!-- Search Bar -->
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex space-x-4">
            <div class="flex-1">
          <input 
                v-model="searchQuery"
            type="text" 
                placeholder="Search locations..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="searchLocations"
              />
            </div>
            <button
              @click="searchLocations"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              <i class="fas fa-search mr-2"></i>
              Search
            </button>
        </div>
      </div>

      <!-- Table -->
        <div class="max-h-96 overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 sticky top-0">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="location in filteredLocations" :key="location.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.type }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getStatusColor(location.status)">
                    {{ location.status }}
                  </span>
              </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                @click="selectLocation(location)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    <i class="fas fa-check mr-1"></i>
                  Select
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
              Showing {{ filteredLocations.length }} of {{ locations.length }} locations
            </div>
            <div class="flex space-x-3">
        <button 
                @click="resetModal"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
        >
                <i class="fas fa-undo mr-2"></i>
                Reset
        </button>
        <button 
                @click="closeModal"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
        >
                <i class="fas fa-times mr-2"></i>
                Exit
        </button>
            </div>
          </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

// Reactive data
const locations = ref([])
const selectedLocation = ref(null)
const searchQuery = ref('')
const loading = ref(false)

// Computed properties
const filteredLocations = computed(() => {
  if (!searchQuery.value) {
    return locations.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return locations.value.filter(location => 
    location.code.toLowerCase().includes(query) ||
    location.name.toLowerCase().includes(query) ||
    location.type.toLowerCase().includes(query)
  )
})

// Methods
const closeModal = () => {
  emit('close')
}

const resetModal = () => {
  searchQuery.value = ''
  selectedLocation.value = null
}

const selectLocation = (location) => {
  selectedLocation.value = location
  emit('select', location)
}

const searchLocations = () => {
  // This would typically make an API call
  // For now, we're just filtering the existing data
  console.log('Searching locations with query:', searchQuery.value)
}

const getStatusColor = (status) => {
  switch (status) {
    case 'Active':
      return 'text-green-600'
    case 'Inactive':
      return 'text-red-600'
    case 'Pending':
      return 'text-yellow-600'
    default:
      return 'text-gray-600'
  }
}

const fetchLocations = async () => {
  try {
    loading.value = true
    
    // Try to fetch from API first
    const response = await fetch('/api/locations')
    if (response.ok) {
      locations.value = await response.json()
    } else {
      // Fallback to mock data
      locations.value = [
        {
          id: 1,
          code: 'WH001',
          name: 'Main Warehouse',
          type: 'Warehouse',
          status: 'Active'
        },
        {
          id: 2,
          code: 'WH002',
          name: 'Secondary Warehouse',
          type: 'Warehouse',
          status: 'Active'
        },
        {
          id: 3,
          code: 'ST001',
          name: 'Store Room 1',
          type: 'Storage',
          status: 'Active'
        },
        {
          id: 4,
          code: 'ST002',
          name: 'Store Room 2',
          type: 'Storage',
          status: 'Inactive'
        },
        {
          id: 5,
          code: 'OF001',
          name: 'Office Storage',
          type: 'Office',
          status: 'Active'
        }
      ]
    }
  } catch (error) {
    console.error('Failed to fetch locations:', error)
    // Use mock data as fallback
    locations.value = [
      {
        id: 1,
        code: 'WH001',
        name: 'Main Warehouse',
        type: 'Warehouse',
        status: 'Active'
      },
      {
        id: 2,
        code: 'WH002',
        name: 'Secondary Warehouse',
        type: 'Warehouse',
        status: 'Active'
      },
      {
        id: 3,
        code: 'ST001',
        name: 'Store Room 1',
        type: 'Storage',
        status: 'Active'
      },
      {
        id: 4,
        code: 'ST002',
        name: 'Store Room 2',
        type: 'Storage',
        status: 'Inactive'
      },
      {
        id: 5,
        code: 'OF001',
        name: 'Office Storage',
        type: 'Office',
        status: 'Active'
      }
    ]
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
    fetchLocations()
})

watch(() => props.show, (newValue) => {
  if (newValue) {
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
