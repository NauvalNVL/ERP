<template>
  <AppLayout>
    <div class="p-6">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600">
          <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_top_right,white,transparent_50%)]"></div>
          <div class="flex items-center justify-between p-5">
            <div class="flex items-center gap-3 text-white">
              <i class="fas fa-truck text-2xl"></i>
              <div>
                <h1 class="text-2xl font-semibold">Define Vehicle Class</h1>
                <p class="text-white/80 text-sm">Manage vehicle class definitions and specifications</p>
              </div>
            </div>
            <div class="hidden sm:flex items-center gap-2">
              <button
                @click="openModal('add')"
                class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors backdrop-blur-sm"
              >
                <i class="fas fa-plus"></i>
                Add Vehicle Class
              </button>
              <button
                @click="exportData"
                class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors backdrop-blur-sm"
              >
                <i class="fas fa-download"></i>
                Export
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Vehicle Classes</label>
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by code, description, or standard class..."
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"
                @input="searchVehicleClasses"
              />
              <i class="fas fa-search absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
            </div>
          </div>
          <div class="flex items-end">
            <button
              @click="clearSearch"
              class="px-4 py-2 text-gray-700 hover:text-gray-900 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm"
            >
              Clear
            </button>
          </div>
        </div>
      </div>

      <!-- Vehicle Classes Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  No.
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Vehicle Class Code
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Description
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="4" class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 mr-2"></i>
                    Loading vehicle classes...
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredVehicleClasses.length === 0" class="text-center">
                <td colspan="4" class="px-6 py-8 text-gray-500">
                  <div class="flex flex-col items-center">
                    <i class="fas fa-truck w-12 h-12 text-gray-300 mb-4 text-2xl"></i>
                    <p class="text-lg font-medium">No vehicle classes found</p>
                    <p class="text-sm">Get started by adding your first vehicle class.</p>
                  </div>
                </td>
              </tr>
              <tr v-else v-for="(vehicleClass, index) in filteredVehicleClasses" :key="vehicleClass.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ vehicleClass.VEHICLE_CLASS_CODE }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ vehicleClass.DESCRIPTION }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button
                      @click="openModal('edit', vehicleClass)"
                      class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors"
                      title="Edit"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="deleteVehicleClass(vehicleClass)"
                      class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-colors"
                      title="Delete"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, totalItems) }} of {{ totalItems }} results
        </div>
        <div class="flex space-x-2">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
          >
            Previous
          </button>
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-3 py-2 text-sm font-medium rounded-md shadow-sm',
              page === currentPage
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50'
            ]"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Modal for Add/Edit Vehicle Class -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <!-- Modal Header -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
              {{ modalMode === 'add' ? 'Add New Vehicle Class' : 'Edit Vehicle Class' }}
            </h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <form @submit.prevent="saveVehicleClass" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Vehicle Class Code *
                </label>
                <input
                  v-model="form.VEHICLE_CLASS_CODE"
                  type="text"
                  required
                  :readonly="modalMode === 'edit'"
                  :class="[
                    'w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                    modalMode === 'edit' 
                      ? 'bg-gray-100 text-gray-600 cursor-not-allowed border-gray-200' 
                      : 'border-gray-300'
                  ]"
                  placeholder="e.g., BE, DB, GM"
                />
                <div v-if="errors.VEHICLE_CLASS_CODE" class="mt-1 text-sm text-red-600">
                  {{ errors.VEHICLE_CLASS_CODE[0] }}
                </div>
                <div v-if="modalMode === 'edit'" class="mt-1 text-xs text-gray-500">
                  Vehicle class code cannot be changed
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Description *
                </label>
                <input
                  v-model="form.DESCRIPTION"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="e.g., T. Box Engkel"
                />
                <div v-if="errors.DESCRIPTION" class="mt-1 text-sm text-red-600">
                  {{ errors.DESCRIPTION[0] }}
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
              >
                <span v-if="saving" class="flex items-center">
                  <i class="fas fa-spinner fa-spin -ml-1 mr-2"></i>
                  Saving...
                </span>
                <span v-else>{{ modalMode === 'add' ? 'Add Vehicle Class' : 'Update Vehicle Class' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <ToastContainer />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'

const { addToast } = useToast()

// Reactive data
const vehicleClasses = ref([])
const loading = ref(false)
const saving = ref(false)
const searchQuery = ref('')
const showModal = ref(false)
const modalMode = ref('add')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const errors = ref({})

// Form data
const form = ref({
  id: null,
  VEHICLE_CLASS_CODE: '',
  DESCRIPTION: ''
})

// Computed properties
const filteredVehicleClasses = computed(() => {
  let filtered = vehicleClasses.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(vc => 
      vc.VEHICLE_CLASS_CODE.toLowerCase().includes(query) ||
      vc.DESCRIPTION.toLowerCase().includes(query)
    )
  }

  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalItems = computed(() => {
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    return vehicleClasses.value.filter(vc => 
      vc.VEHICLE_CLASS_CODE.toLowerCase().includes(query) ||
      vc.DESCRIPTION.toLowerCase().includes(query)
    ).length
  }
  return vehicleClasses.value.length
})

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(totalPages.value, start + 4)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Methods
const fetchVehicleClasses = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/vehicle-classes')
    const data = await response.json()
    
    if (data.success) {
      vehicleClasses.value = data.data
    } else {
      addToast('Error fetching vehicle classes', 'error')
    }
  } catch (error) {
    addToast('Error fetching vehicle classes', 'error')
  } finally {
    loading.value = false
  }
}

const searchVehicleClasses = () => {
  currentPage.value = 1
}

const clearSearch = () => {
  searchQuery.value = ''
  currentPage.value = 1
}

const openModal = (mode, vehicleClass = null) => {
  modalMode.value = mode
  showModal.value = true
  errors.value = {}
  
  if (mode === 'edit' && vehicleClass) {
    form.value = {
      id: vehicleClass.id,
      VEHICLE_CLASS_CODE: vehicleClass.VEHICLE_CLASS_CODE,
      DESCRIPTION: vehicleClass.DESCRIPTION
    }
  } else {
    form.value = {
      id: null,
      VEHICLE_CLASS_CODE: '',
      DESCRIPTION: ''
    }
  }
}

const closeModal = () => {
  showModal.value = false
  errors.value = {}
  form.value = {
    id: null,
    VEHICLE_CLASS_CODE: '',
    DESCRIPTION: ''
  }
}

const saveVehicleClass = async () => {
  saving.value = true
  errors.value = {}
  
  try {
    const url = modalMode.value === 'add' 
      ? '/api/vehicle-classes' 
      : `/api/vehicle-classes/${form.value.id}`
    
    const method = modalMode.value === 'add' ? 'POST' : 'PUT'
    
    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })
    
    const data = await response.json()
    
    if (data.success) {
      addToast(data.message, 'success')
      closeModal()
      await fetchVehicleClasses()
    } else {
      if (data.errors) {
        errors.value = data.errors
      }
      addToast(data.message || 'Error saving vehicle class', 'error')
    }
  } catch (error) {
    addToast('Error saving vehicle class', 'error')
  } finally {
    saving.value = false
  }
}

const deleteVehicleClass = async (vehicleClass) => {
  if (!confirm(`Are you sure you want to delete vehicle class "${vehicleClass.VEHICLE_CLASS_CODE}"?`)) {
    return
  }
  
  try {
    const response = await fetch(`/api/vehicle-classes/${vehicleClass.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    
    const data = await response.json()
    
    if (data.success) {
      addToast(data.message, 'success')
      await fetchVehicleClasses()
    } else {
      addToast(data.message || 'Error deleting vehicle class', 'error')
    }
  } catch (error) {
    addToast('Error deleting vehicle class', 'error')
  }
}

const exportData = () => {
  const csvContent = [
    ['No.', 'Vehicle Class Code', 'Description'],
    ...vehicleClasses.value.map((vc, index) => [
      index + 1,
      vc.VEHICLE_CLASS_CODE,
      vc.DESCRIPTION
    ])
  ].map(row => row.join(',')).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'vehicle-classes.csv'
  a.click()
  window.URL.revokeObjectURL(url)
  
  addToast('Vehicle classes exported successfully', 'success')
}

const formatNumber = (number) => {
  return parseFloat(number).toFixed(2)
}

const goToPage = (page) => {
  currentPage.value = page
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

// Lifecycle
onMounted(() => {
  fetchVehicleClasses()
})

// Watch for search changes
watch(searchQuery, () => {
  currentPage.value = 1
})
</script>

<style scoped>
/* Custom styles if needed */
</style>
