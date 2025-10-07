<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <i class="fas fa-truck text-2xl"></i>
            <div>
              <h2 class="text-xl font-semibold">Vehicle Lookup</h2>
              <p class="text-blue-100 text-sm">Select a vehicle for delivery order</p>
            </div>
          </div>
          <button 
            @click="$emit('close')"
            class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors"
          >
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Vehicle</label>
            <div class="relative">
              <input 
                v-model="searchQuery"
                type="text"
                placeholder="Search by vehicle number, driver name, or class..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="searchVehicles"
              >
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select 
              v-model="statusFilter"
              @change="searchVehicles"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Status</option>
              <option value="A">Active</option>
              <option value="O">Obsolete</option>
            </select>
          </div>

          <!-- Company Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
            <select 
              v-model="companyFilter"
              @change="searchVehicles"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Companies</option>
              <option v-for="company in companies" :key="company" :value="company">
                {{ company }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="text-center">
          <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
          <p class="text-gray-600">Loading vehicles...</p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="errorMessage" class="p-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
            <span class="text-red-800">{{ errorMessage }}</span>
          </div>
        </div>
      </div>

      <!-- Vehicle Table -->
      <div v-else class="p-6 overflow-auto max-h-96 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 sticky top-0 z-20 shadow-sm">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Vehicle No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Class</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Description</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Company</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Driver</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Phone</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Status</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="vehicles.length === 0">
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                <i class="fas fa-truck text-4xl mb-2 text-gray-300"></i>
                <p>No vehicles found</p>
              </td>
            </tr>
            <tr 
              v-for="vehicle in vehicles" 
              :key="vehicle.id"
              class="hover:bg-gray-50 cursor-pointer"
              :class="{ 'bg-blue-50': selectedVehicle?.id === vehicle.id }"
              @click="selectVehicle(vehicle)"
            >
              <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 table-cell">
                {{ vehicle.VEHICLE_NO }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 table-cell">
                {{ vehicle.VEHICLE_CLASS }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 table-cell">
                {{ vehicle.VEHICLE_DESCRIPTION || '-' }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 table-cell">
                {{ vehicle.VEHICLE_COMPANY }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 table-cell">
                {{ vehicle.DRIVER_NAME }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 table-cell">
                {{ vehicle.DRIVER_PHONE }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap table-cell">
                <span 
                  :class="vehicle.VEHICLE_STATUS === 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="px-2 py-1 rounded-full text-xs font-medium"
                >
                  {{ vehicle.VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete' }}
                </span>
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 table-cell">
                <button 
                  @click.stop="selectVehicle(vehicle)"
                  class="text-blue-600 hover:text-blue-800 font-medium"
                >
                  Select
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
          </div>
          <div class="flex space-x-2">
            <button 
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Previous
            </button>
            <span class="px-3 py-1 text-sm text-gray-700">
              Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button 
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
        <button 
          @click="$emit('close')"
          class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="confirmSelection"
          :disabled="!selectedVehicle"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Select Vehicle
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

// Reactive data
const vehicles = ref([])
const selectedVehicle = ref(null)
const loading = ref(false)
const errorMessage = ref('')
const searchQuery = ref('')
const statusFilter = ref('')
const companyFilter = ref('')
const companies = ref([])
const pagination = ref(null)

// Search vehicles
const searchVehicles = async () => {
  loading.value = true
  errorMessage.value = ''
  
  try {
    const response = await axios.get('/api/vehicles', {
      params: {
        search: searchQuery.value,
        status: statusFilter.value,
        company: companyFilter.value,
        plain: false
      }
    })
    
    if (response.data.success) {
      vehicles.value = response.data.rows || []
      pagination.value = response.data.data?.pagination || null
      companies.value = response.data.companies || []
    } else {
      errorMessage.value = 'Failed to load vehicles'
      vehicles.value = []
    }
  } catch (error) {
    console.error('Error fetching vehicles:', error)
    errorMessage.value = 'Error loading vehicles. Please try again.'
    vehicles.value = []
  } finally {
    loading.value = false
  }
}

// Select vehicle
const selectVehicle = (vehicle) => {
  selectedVehicle.value = vehicle
}

// Confirm selection
const confirmSelection = () => {
  if (selectedVehicle.value) {
    emit('select', selectedVehicle.value)
    emit('close')
  }
}

// Change page
const changePage = async (page) => {
  if (page < 1 || (pagination.value && page > pagination.value.last_page)) return
  
  loading.value = true
  try {
    const response = await axios.get('/api/vehicles', {
      params: {
        search: searchQuery.value,
        status: statusFilter.value,
        company: companyFilter.value,
        page: page,
        plain: false
      }
    })
    
    if (response.data.success) {
      vehicles.value = response.data.rows || []
      pagination.value = response.data.data?.pagination || null
    }
  } catch (error) {
    console.error('Error fetching vehicles:', error)
    errorMessage.value = 'Error loading vehicles. Please try again.'
  } finally {
    loading.value = false
  }
}

// Watch for modal open
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    searchVehicles()
  } else {
    // Reset when modal closes
    selectedVehicle.value = null
    searchQuery.value = ''
    statusFilter.value = ''
    companyFilter.value = ''
    errorMessage.value = ''
  }
})

// Load initial data
onMounted(() => {
  if (props.isOpen) {
    searchVehicles()
  }
})
</script>

<style scoped>
/* Custom scrollbar styling */
.scrollbar-thin {
  scrollbar-width: thin;
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Ensure table cells don't break layout */
.table-cell {
  min-width: 120px;
}

/* Fix sticky header positioning */
thead {
  position: sticky;
  top: 0;
  z-index: 20;
  background-color: #f9fafb;
}

/* Ensure header cells have proper background and positioning */
thead th {
  background-color: #f9fafb !important;
  position: sticky;
  top: 0;
  z-index: 21;
  border-bottom: 1px solid #e5e7eb;
}

/* Add shadow to sticky header for better visual separation */
thead::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(to right, transparent, #d1d5db, transparent);
  z-index: 22;
}

/* Ensure table body rows are behind header */
tbody tr {
  position: relative;
  z-index: 1;
}

/* Responsive table adjustments */
@media (max-width: 768px) {
  .table-cell {
    min-width: 100px;
  }
}
</style>
