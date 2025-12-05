<template>
  <AppLayout :header="'Define Vehicle Class'">
    <Head title="Define Vehicle Class" />
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto space-y-4">
        <!-- Header Section -->
        <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700">
          <div class="flex items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-blue-500/80 flex items-center justify-center">
                <i class="fas fa-layer-group text-lg"></i>
              </div>
              <div>
                <h1 class="text-lg sm:text-xl font-semibold">Define Vehicle Class</h1>
                <p class="text-xs sm:text-sm text-blue-100">Manage vehicle class definitions and specifications</p>
              </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs text-blue-100">
              <i class="fas fa-info-circle"></i>
              <span>Search existing classes or add a new one.</span>
            </div>
          </div>
        </div>

        <!-- Main Panel: Define Vehicle Class + Info/Links -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
          <!-- Left Column - Define Vehicle Class Card -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
              <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                <div class="p-2.5 bg-blue-50 rounded-lg mr-3 text-blue-600">
                  <i class="fas fa-layer-group"></i>
                </div>
                <div>
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900">Define Vehicle Class</h3>
                  <p class="text-xs sm:text-sm text-gray-500">Search, create, and maintain your vehicle classes</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Class Code</label>
                  <div class="relative flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">
                      <i class="fas fa-layer-group"></i>
                    </span>
                    <input
                      v-model="searchQuery"
                      type="text"
                      placeholder="Search by code or description..."
                      class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-400 text-sm sm:text-base transition-colors"
                      @input="searchVehicleClasses"
                    />
                    <button
                      type="button"
                      @click="showVehicleClassModal = true"
                      class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-500 bg-blue-500 hover:bg-blue-600 text-white rounded-r-lg text-sm transition-colors transform active:translate-y-px"
                    >
                      <i class="fas fa-table"></i>
                    </button>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Action</label>
                  <div class="flex flex-wrap items-center gap-2">
                    <button
                      type="button"
                      @click="openModal('add')"
                      class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm"
                    >
                      <i class="fas fa-plus-circle"></i>
                      Add New
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Info & Quick Links -->
          <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-4 sm:p-5">
              <div class="flex items-center mb-3 pb-2 border-b border-blue-100">
                <div class="p-2.5 bg-blue-500 rounded-lg mr-3">
                  <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-blue-900">Vehicle Class Information</h3>
              </div>
              <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Vehicle class code should be unique and readable for users.</li>
                  <li>Use clear descriptions to differentiate similar vehicle types.</li>
                  <li>Vehicle class is referenced when defining individual vehicles.</li>
                  <li>Review classes periodically to keep your master data consistent.</li>
                </ul>
              </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
              <div class="flex items-center mb-3 pb-2 border-b border-gray-100">
                <div class="p-2.5 bg-blue-500 rounded-lg mr-3">
                  <i class="fas fa-link text-white"></i>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Quick Links</h3>
              </div>

              <div class="grid grid-cols-1 gap-3">
                <Link
                  href="/warehouse-management/delivery-order/setup/vehicle"
                  class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100"
                >
                  <div class="p-2.5 bg-blue-500 rounded-full mr-3">
                    <i class="fas fa-truck text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-blue-900 text-sm">Define Vehicle</p>
                    <p class="text-xs text-blue-700">Manage vehicles linked to these classes</p>
                  </div>
                </Link>

                <Link
                  href="/warehouse-management/delivery-order/setup/vehicle-class/view-print"
                  class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100"
                >
                  <div class="p-2.5 bg-blue-500 rounded-full mr-3">
                    <i class="fas fa-print text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-blue-900 text-sm">View &amp; Print Vehicle Class</p>
                    <p class="text-xs text-blue-700">Print vehicle class list</p>
                  </div>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Modal for Add/Edit Vehicle Class -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4 sm:px-6"
      @click="closeModal"
    >
      <div
        class="relative w-full max-w-2xl md:max-w-xl bg-white rounded-xl shadow-2xl transform transition-transform duration-200 max-h-[90vh]"
        @click.stop
      >
        <div class="flex flex-col max-h-[90vh]">
          <!-- Modal Header -->
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-t-xl">
            <div class="flex items-center">
              <div class="p-2 bg-white/20 rounded-lg mr-3">
                <i class="fas fa-layer-group"></i>
              </div>
              <h3 class="text-lg font-semibold">
                {{ modalMode === 'add' ? 'Add New Vehicle Class' : 'Edit Vehicle Class' }}
              </h3>
            </div>
            <button
              @click="closeModal"
              class="text-white hover:text-gray-100 rounded-full p-1.5 hover:bg-white/10 transition-colors"
            >
              <i class="fas fa-times text-lg"></i>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-5 sm:p-6 overflow-y-auto flex-1">
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
                      'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm',
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    placeholder="e.g., T. Box Engkel"
                  />
                  <div v-if="errors.DESCRIPTION" class="mt-1 text-sm text-red-600">
                    {{ errors.DESCRIPTION[0] }}
                  </div>
                </div>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100 mt-4">
                <button
                  v-if="modalMode === 'edit' && form.id"
                  type="button"
                  @click="deleteVehicleClass(form)"
                  class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform active:translate-y-px flex items-center"
                >
                  <i class="fas fa-ban mr-2"></i>
                  Obsolete
                </button>
                <div class="flex items-center gap-3 ml-auto">
                  <button
                    type="button"
                    @click="closeModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform active:translate-y-px"
                  >
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="saving"
                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 border border-transparent rounded-lg hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transform active:translate-y-px"
                  >
                    <span v-if="saving" class="flex items-center">
                      <i class="fas fa-spinner fa-spin -ml-1 mr-2"></i>
                      Saving...
                    </span>
                    <span v-else class="flex items-center">
                      <i class="fas fa-save mr-2"></i>
                      {{ modalMode === 'add' ? 'Add Vehicle Class' : 'Update Vehicle Class' }}
                    </span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <VehicleClassTableModal
      :is-open="showVehicleClassModal"
      @close="showVehicleClassModal = false"
      @select="onVehicleClassSelected"
    />

    <!-- Toast Container -->
    <ToastContainer />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import VehicleClassTableModal from '@/Components/VehicleClassTableModal.vue'

const { addToast } = useToast()

// Reactive data
const vehicleClasses = ref([])
const loading = ref(false)
const saving = ref(false)
const searchQuery = ref('')
const showModal = ref(false)
const modalMode = ref('add')
const showVehicleClassModal = ref(false)
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
  if (!vehicleClasses.value.length) {
    fetchVehicleClasses()
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  currentPage.value = 1
}

const onVehicleClassSelected = (cls) => {
  if (!cls) return
  // Open edit modal with selected vehicle class, similar to onVehicleSelected in Vehicle.vue
  openModal('edit', cls)
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
  if (!confirm(`Are you sure you want to obsolete vehicle class "${vehicleClass.VEHICLE_CLASS_CODE}"?`)) {
    return
  }

  try {
    const response = await fetch(`/api/vehicle-classes/${vehicleClass.id}/status`, {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        Accept: 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        STATUS: 'O'
      })
    })

    if (!response.ok) {
      throw new Error('Failed to obsolete vehicle class')
    }

    const data = await response.json()

    if (!data || !data.success) {
      throw new Error(data?.message || 'Unknown error from server')
    }

    addToast(`Vehicle class "${vehicleClass.VEHICLE_CLASS_CODE}" obsoleted successfully`, 'success')
    closeModal()
    await fetchVehicleClasses()
  } catch (error) {
    addToast('Error obsoleting vehicle class: ' + (error?.message || error), 'error')
  }
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
  // Do not auto-load vehicle classes; data will be loaded after user actions (save/delete) if needed
})

// Watch for search changes
watch(searchQuery, () => {
  currentPage.value = 1
})
</script>

<style scoped>
/* Custom styles if needed */
</style>
