<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full mx-4 max-h-[90vh] flex flex-col overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <i class="fas fa-truck text-xl"></i>
            </div>
            <div>
              <h2 class="text-xl font-semibold">Vehicle Table</h2>
              <p class="text-blue-100 text-sm">Browse and select a vehicle for delivery order</p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="text-white hover:text-gray-100 rounded-full p-2 hover:bg-white/10 transition-colors"
          >
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="p-5 border-b border-gray-200 bg-slate-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Search Vehicle</label>
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by vehicle number, driver name, or class..."
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm text-sm"
                @input="searchVehicles"
              >
              <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
          </div>

          <!-- Status Filter (only Active vehicles are available in this modal) -->
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
            <select
              v-model="statusFilter"
              @change="searchVehicles"
              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm shadow-sm"
            >
              <option value="A">Active</option>
            </select>
          </div>

          <!-- Company Filter -->
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Company</label>
            <select
              v-model="companyFilter"
              @change="searchVehicles"
              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm shadow-sm"
            >
              <option value="">All Companies</option>
              <option v-for="company in companies" :key="company" :value="company">
                {{ company }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Main content (scrollable): loading / error / table + pagination -->
      <div class="flex-1 overflow-y-auto bg-white">
        <!-- Loading State -->
        <div v-if="loading" class="flex items-center justify-center py-12">
          <div class="text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
            <p class="text-gray-600">Loading vehicles...</p>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="errorMessage" class="p-6">
          <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
              <span class="text-red-800">{{ errorMessage }}</span>
            </div>
          </div>
        </div>

        <!-- Vehicle Table -->
        <div v-else class="p-5 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
          <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-slate-50 sticky top-0 z-20 shadow-sm sticky-header">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Vehicle No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Class</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Description</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Company</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Driver</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Phone</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider table-cell">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="vehicles.length === 0">
                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                  <div class="flex flex-col items-center">
                    <i class="fas fa-truck text-4xl mb-2 text-gray-300"></i>
                    <p class="text-sm">No vehicles found</p>
                  </div>
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
                    :class="vehicle.VEHICLE_STATUS === 'A' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200'"
                    class="px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1"
                  >
                    <span class="inline-block w-1.5 h-1.5 rounded-full" :class="vehicle.VEHICLE_STATUS === 'A' ? 'bg-emerald-500' : 'bg-rose-500'" />
                    {{ vehicle.VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-slate-50">
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
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 bg-slate-50 flex justify-end space-x-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm font-medium"
        >
          <span class="flex items-center">
            <i class="fas fa-times mr-2"></i>
            Cancel
          </span>
        </button>
        <button
          @click="confirmSelection"
          :disabled="!selectedVehicle"
          class="px-4 py-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium"
        >
          <span class="flex items-center justify-center">
            <i class="fas fa-check-circle mr-2"></i>
            Select Vehicle
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'

defineOptions({
  name: 'VehicleTableModal'
})

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
const statusFilter = ref('A')
const companyFilter = ref('')
const companies = ref([])
const pagination = ref(null)

// Helper to ensure only active vehicles are shown in this modal
const filterActiveVehicles = (rows) => {
  if (!Array.isArray(rows)) return []
  return rows.filter(vehicle => {
    const status = (vehicle.VEHICLE_STATUS || 'A').toString().toUpperCase()
    return status === 'A'
  })
}

// Search vehicles
const searchVehicles = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get('/api/vehicles', {
      params: {
        search: searchQuery.value,
        status: statusFilter.value || 'A',
        company: companyFilter.value,
        plain: false
      }
    })

    if (response.data.success) {
      const rows = filterActiveVehicles(response.data.rows || [])
      vehicles.value = rows
      pagination.value = response.data.data?.pagination || null
      const defaultCompanies = ['KIM', 'CUSTOMER', 'MBI', 'MMI']
      const apiCompanies = Array.isArray(response.data.companies) ? response.data.companies : []
      companies.value = Array.from(new Set([
        ...defaultCompanies,
        ...apiCompanies
      ])).filter(Boolean)
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
  // Extra safety: prevent selecting obsolete vehicles if they ever appear
  const status = (vehicle.VEHICLE_STATUS || 'A').toString().toUpperCase()
  if (status !== 'A') {
    return
  }

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
        status: statusFilter.value || 'A',
        company: companyFilter.value,
        page: page,
        plain: false
      }
    })

    if (response.data.success) {
      const rows = filterActiveVehicles(response.data.rows || [])
      vehicles.value = rows
      pagination.value = response.data.data?.pagination || null
      const defaultCompanies = ['KIM', 'CUSTOMER', 'MBI', 'MMI']
      const apiCompanies = Array.isArray(response.data.companies) ? response.data.companies : []
      companies.value = Array.from(new Set([
        ...defaultCompanies,
        ...apiCompanies
      ])).filter(Boolean)
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
    statusFilter.value = 'A'
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
thead.sticky-header {
  position: sticky;
  top: 0;
  z-index: 20;
  background-color: #f9fafb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
