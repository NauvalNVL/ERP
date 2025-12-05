<template>
  <AppLayout :header="'Obsolete / Unobsolete Vehicle'">
    <Head title="Obsolete / Unobsolete Vehicle" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
          <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                <i class="fas fa-sync-alt text-white text-sm"></i>
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                  Manage Vehicle Status (Obsolete/Unobsolete)
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100">
                  Toggle the active status of vehicles.
                </p>
              </div>
            </div>
            <div class="text-xs sm:text-sm text-emerald-100 flex items-center gap-2">
              <i class="fas fa-info-circle text-sm"></i>
              <span>Use search, status, and company filters to quickly find vehicles.</span>
            </div>
          </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
          <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
            <div class="p-2 bg-emerald-500 rounded-lg mr-3 text-white">
              <i class="fas fa-truck"></i>
            </div>
            <div>
              <h3 class="text-sm sm:text-base font-semibold text-slate-800">
                Vehicle List
              </h3>
              <p class="text-xs text-slate-500">
                Search, filter, and mark vehicles as active or obsolete.
              </p>
            </div>
          </div>

          <div class="p-4 sm:p-6 space-y-4">
            <!-- Notification -->
            <div
              v-if="notification.show"
              :class="{
                'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                'px-4 py-3 rounded relative': true
              }"
            >
              <span class="block sm:inline">{{ notification.message }}</span>
            </div>

            <!-- Search and Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2">
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <i class="fas fa-search"></i>
                  </span>
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search vehicles..."
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50"
                  >
                </div>
              </div>
              <div class="flex flex-col sm:flex-row gap-3 md:justify-end">
                <select
                  v-model="statusFilter"
                  class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                >
                  <option value="all">All Statuses</option>
                  <option value="active">Active Only</option>
                  <option value="obsolete">Obsolete Only</option>
                </select>
                <select
                  v-model="companyFilter"
                  class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                >
                  <option value="all">All Companies</option>
                  <option v-for="company in allCompanies" :key="company" :value="company">
                    {{ company }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Loading Indicator -->
            <div v-if="loading" class="my-8 flex justify-center">
              <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
            </div>

            <!-- Vehicle Table -->
            <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="vehicle in paginatedVehicles"
                    :key="vehicle.id"
                    class="hover:bg-gray-50"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ vehicle.VEHICLE_NO }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ vehicle.VEHICLE_DESCRIPTION || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ vehicle.VEHICLE_CLASS }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ vehicle.VEHICLE_COMPANY }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ vehicle.DRIVER_NAME }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ vehicle.DRIVER_PHONE }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                      <span
                        v-if="vehicle.VEHICLE_STATUS === 'A'"
                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                      >
                        <i class="fas fa-check-circle mr-1"></i> Active
                      </span>
                      <span
                        v-else
                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                      >
                        <i class="fas fa-times-circle mr-1"></i> Obsolete
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                      <button
                        @click="toggleVehicleStatus(vehicle)"
                        :disabled="isToggling"
                        :class="[
                          vehicle.VEHICLE_STATUS === 'A'
                            ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                            : 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200',
                          'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center'
                        ]"
                        :style="{ minWidth: '120px' }"
                      >
                        <i
                          :class="[vehicle.VEHICLE_STATUS === 'A' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"
                        ></i>
                        {{ vehicle.VEHICLE_STATUS === 'A' ? 'Mark Obsolete' : 'Mark Active' }}
                      </button>
                    </td>
                  </tr>
                  <tr v-if="paginatedVehicles.length === 0">
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No vehicles found.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination Controls -->
            <div
              v-if="filteredVehicles.length > pagination.perPage"
              class="flex items-center justify-between pt-4 border-t border-gray-100"
            >
              <div class="flex-1 flex justify-between items-center">
                <button
                  @click="changePage(pagination.currentPage - 1)"
                  :disabled="pagination.currentPage === 1"
                  :class="[
                    pagination.currentPage === 1
                      ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                      : 'bg-green-600 text-white hover:bg-green-700',
                    'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                  ]"
                >
                  Previous
                </button>

                <span class="text-sm text-gray-700">
                  Page {{ pagination.currentPage }} of
                  {{ Math.ceil(filteredVehicles.length / pagination.perPage) || 1 }}
                </span>

                <button
                  @click="changePage(pagination.currentPage + 1)"
                  :disabled="pagination.currentPage >= Math.ceil(filteredVehicles.length / pagination.perPage)"
                  :class="[
                    pagination.currentPage >= Math.ceil(filteredVehicles.length / pagination.perPage)
                      ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                      : 'bg-green-600 text-white hover:bg-green-700',
                    'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                  ]"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="isToggling" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-4 rounded-lg shadow-lg text-center">
        <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        <p>Updating status...</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const vehicles = ref([])
const loading = ref(false)
const isToggling = ref(false)
const searchQuery = ref('')
const statusFilter = ref('all')
const companyFilter = ref('all')
const allCompanies = ref([])
const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const pagination = ref({
  currentPage: 1,
  perPage: 15,
  total: 0
})

const isDev = typeof window !== 'undefined' && window.location.port === '5173'

const buildApiUrl = (pathWithNoApiPrefix, queryParams = '') => {
  const basePath = `/api${pathWithNoApiPrefix.startsWith('/') ? pathWithNoApiPrefix : '/' + pathWithNoApiPrefix}`
  const withQuery = queryParams ? `${basePath}?${queryParams}` : basePath
  return isDev ? `/api${withQuery}` : withQuery
}

const fetchVehicles = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      plain: '1'
    })

    const response = await fetch(buildApiUrl('/vehicles', params.toString()), {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    if (!response.ok) {
      throw new Error(`Failed to fetch vehicles: ${response.status}`)
    }

    const data = await response.json()

    if (data && data.success) {
      const list = Array.isArray(data.rows)
        ? data.rows
        : Array.isArray(data.data)
          ? data.data
          : data.data?.data || []

      vehicles.value = list
      pagination.value = {
        currentPage: 1,
        perPage: 15,
        total: list.length
      }

      const companies = Array.from(
        new Set(
          list
            .map(v => v.VEHICLE_COMPANY)
            .filter(c => c && typeof c === 'string')
        )
      ).sort()
      allCompanies.value = companies
    } else {
      showNotification('Error loading vehicles' + (data?.message ? `: ${data.message}` : ''), 'error')
      vehicles.value = []
      pagination.value = {
        currentPage: 1,
        perPage: 15,
        total: 0
      }
    }
  } catch (error) {
    console.error('Error fetching vehicles:', error)
    showNotification('Error loading vehicles: ' + (error?.message || error), 'error')
    vehicles.value = []
    pagination.value = {
      currentPage: 1,
      perPage: 15,
      total: 0
    }
  } finally {
    loading.value = false
  }
}

const filteredVehicles = computed(() => {
  let filtered = [...vehicles.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(v => {
      const fields = [
        v.VEHICLE_NO,
        v.VEHICLE_DESCRIPTION,
        v.VEHICLE_CLASS,
        v.VEHICLE_COMPANY,
        v.DRIVER_CODE,
        v.DRIVER_NAME,
        v.DRIVER_PHONE
      ]
      return fields.some(f => f && String(f).toLowerCase().includes(query))
    })
  }

  if (statusFilter.value !== 'all') {
    const active = statusFilter.value === 'active'
    filtered = filtered.filter(v => (v.VEHICLE_STATUS === 'A') === active)
  }

  if (companyFilter.value !== 'all') {
    filtered = filtered.filter(v => v.VEHICLE_COMPANY === companyFilter.value)
  }

  return filtered
})

const paginatedVehicles = computed(() => {
  const start = (pagination.value.currentPage - 1) * pagination.value.perPage
  const end = start + pagination.value.perPage
  return filteredVehicles.value.slice(start, end)
})

const changePage = page => {
  const totalPages = Math.ceil((filteredVehicles.value.length || 1) / pagination.value.perPage)
  if (page < 1 || page > totalPages) return
  pagination.value.currentPage = page
}

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  }

  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const toggleVehicleStatus = async vehicle => {
  if (isToggling.value) return

  const confirmMessage = `Are you sure you want to change the status for vehicle "${vehicle.VEHICLE_NO}"?`
  if (!confirm(confirmMessage)) return

  isToggling.value = true

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    if (!csrfToken) {
      throw new Error('CSRF token not found')
    }

    const newStatus = vehicle.VEHICLE_STATUS === 'A' ? 'O' : 'A'

    const response = await fetch(buildApiUrl(`/vehicles/${vehicle.id}/status`), {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        VEHICLE_STATUS: newStatus
      })
    })

    if (!response.ok) {
      throw new Error('Failed to toggle vehicle status')
    }

    const result = await response.json()
    if (!result || !result.success) {
      throw new Error(result?.message || 'Unknown error from server')
    }

    vehicle.VEHICLE_STATUS = newStatus
    vehicle.STATUS = newStatus

    const statusText = newStatus === 'A' ? 'activated' : 'marked obsolete'
    showNotification(`Vehicle "${vehicle.VEHICLE_NO}" successfully ${statusText}`, 'success')
  } catch (error) {
    console.error('Error toggling vehicle status:', error)
    showNotification('Error updating status: ' + (error?.message || error), 'error')
  } finally {
    isToggling.value = false
  }
}

onMounted(() => {
  fetchVehicles()
})
</script>

