<template>
  <AppLayout :header="'Define Vehicle'">
    <Head title="Define Vehicle" />
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto space-y-4">
        <!-- Header -->
        <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700">
          <div class="flex items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-blue-500/80 flex items-center justify-center">
                <i class="fas fa-truck text-lg"></i>
              </div>
              <div>
                <h1 class="text-lg sm:text-xl font-semibold">Define Vehicle</h1>
                <p class="text-xs sm:text-sm text-blue-100">Manage vehicle information and driver details</p>
              </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs text-blue-100">
              <i class="fas fa-info-circle"></i>
              <span>Search existing vehicles or add a new one.</span>
            </div>
          </div>
        </div>

        <!-- Main Panel: Define Vehicle + Info/Links -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
          <!-- Left Column - Define Vehicle Card -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
              <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                <div class="p-2.5 bg-blue-50 rounded-lg mr-3 text-blue-600">
                  <i class="fas fa-edit"></i>
                </div>
                <div>
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900">Define Vehicle</h3>
                  <p class="text-xs sm:text-sm text-gray-500">Search, create, and maintain your vehicles</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle #</label>
                  <div class="relative flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">
                      <i class="fas fa-truck"></i>
                    </span>
                    <input
                      v-model="searchQuery"
                      type="text"
                      placeholder="Search or type vehicle number"
                      class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-400 text-sm sm:text-base transition-colors"
                      @input="debouncedSearch"
                    />
                    <button
                      type="button"
                      @click="showVehicleModal = true"
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
                      @click="openAddModal"
                      class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm"
                    >
                      <i class="fas fa-plus-circle"></i>
                      Add New
                    </button>
                  </div>
                </div>
              </div>

              <!-- Filters removed to match Define Color UI (no status/company filters shown here) -->
            </div>
          </div>

          <!-- Right Column - Info & Quick Links -->
          <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-4 sm:p-5">
              <div class="flex items-center mb-3 pb-2 border-b border-blue-100">
                <div class="p-2.5 bg-blue-500 rounded-lg mr-3">
                  <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-blue-900">Vehicle Information</h3>
              </div>
              <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Vehicle number should be unique per vehicle.</li>
                  <li>Use the table button to select existing vehicles.</li>
                  <li>Assign the correct vehicle class and company.</li>
                  <li>Ensure driver phone is reachable for tracking.</li>
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
                  href="/warehouse-management/delivery-order/setup/vehicle-class"
                  class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100"
                >
                  <div class="p-2.5 bg-blue-500 rounded-full mr-3">
                    <i class="fas fa-layer-group text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-blue-900 text-sm">Vehicle Class</p>
                    <p class="text-xs text-blue-700">Manage vehicle classes</p>
                  </div>
                </Link>

                <Link
                  href="/warehouse-management/delivery-order/setup/vehicle/view-print"
                  class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100"
                >
                  <div class="p-2.5 bg-blue-500 rounded-full mr-3">
                    <i class="fas fa-print text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-blue-900 text-sm">View &amp; Print Vehicle</p>
                    <p class="text-xs text-blue-700">Print vehicle list</p>
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

    <!-- Vehicle Table (hidden in UI as requested) -->
    <div
      v-if="false"
      class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 sticky top-0 z-10">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Vehicle #
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Class
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Company
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Driver Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Driver Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Driver Phone
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Note
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="9" class="px-6 py-4 text-center">
                <div class="flex items-center justify-center">
                  <RefreshIcon class="w-5 h-5 text-blue-600 animate-spin mr-2" />
                  Loading vehicles...
                </div>
              </td>
            </tr>
            <tr v-else v-for="vehicle in vehicles" :key="vehicle.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ vehicle.VEHICLE_NO }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="vehicle.VEHICLE_STATUS === 'A'
                    ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200'
                    : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200'"
                  class="px-2.5 py-1 text-xs font-medium rounded-full inline-flex items-center gap-1"
                >
                  <span
                    class="inline-block w-1.5 h-1.5 rounded-full"
                    :class="vehicle.VEHICLE_STATUS === 'A' ? 'bg-emerald-500' : 'bg-rose-500'"
                  />
                  {{ vehicle.VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.VEHICLE_CLASS }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.VEHICLE_COMPANY }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.DRIVER_CODE }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.DRIVER_NAME }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.DRIVER_PHONE }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ vehicle.NOTE || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center gap-2">
                  <button
                    @click="openEditModal(vehicle)"
                    class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded hover:bg-blue-50"
                    title="Edit Vehicle"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button
                    @click="deleteVehicle(vehicle)"
                    class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50"
                    title="Delete Vehicle"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Previous
            </button>
            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ pagination.from }}</span>
                to
                <span class="font-medium">{{ pagination.to }}</span>
                of
                <span class="font-medium">{{ pagination.total }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <button
                  v-for="page in visiblePages"
                  :key="page"
                  @click="changePage(page)"
                  :class="[
                    page === pagination.current_page
                      ? 'bg-blue-50 border-blue-500 text-blue-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                >
                  {{ page }}
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

      <!-- Add/Edit Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4 sm:px-6"
        @click="closeModal"
      >
        <div
          class="relative w-full max-w-2xl md:max-w-3xl bg-white rounded-xl shadow-2xl transform transition-transform duration-200 max-h-[90vh]"
          @click.stop
        >
          <div class="flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-t-xl">
              <div class="flex items-center">
                <div class="p-2 bg-white/20 rounded-lg mr-3">
                  <i class="fas fa-truck"></i>
                </div>
                <h3 class="text-lg font-semibold">
                  {{ isEditing ? 'Edit Vehicle' : 'Add New Vehicle' }}
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
              <form @submit.prevent="saveVehicle" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Vehicle Number -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vehicle # <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.VEHICLE_NO"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter vehicle number"
                  />
                </div>

                <!-- Vehicle Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vehicle Status <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.VEHICLE_STATUS"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                    <option value="A">A - Active</option>
                    <option value="O">O - Obsolete</option>
                  </select>
                </div>

                <!-- Vehicle Class -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vehicle Class <span class="text-red-500">*</span>
                  </label>
                  <div class="relative flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-slate-50 text-slate-500">
                      <i class="fas fa-layer-group"></i>
                    </span>
                    <input
                      v-model="form.VEHICLE_CLASS"
                      type="text"
                      required
                      class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                      placeholder="Select or type vehicle class code"
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

                <!-- Vehicle Company -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vehicle Company <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.VEHICLE_COMPANY"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="KIM">KIM</option>
                    <option value="CUSTOMER">CUSTOMER</option>
                    <option value="MBI">MBI</option>
                  </select>
                </div>

                <!-- Driver Code -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Driver Code <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.DRIVER_CODE"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter driver code"
                  />
                </div>

                <!-- Driver Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Driver Name <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.DRIVER_NAME"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter driver name"
                  />
                </div>

                <!-- Driver ID -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Driver ID # <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.DRIVER_ID"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter driver ID"
                  />
                </div>

                <!-- Driver Phone -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Driver Phone # <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.DRIVER_PHONE"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter driver phone"
                  />
                </div>
              </div>

              <!-- Note -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Note
                </label>
                <textarea
                  v-model="form.NOTE"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter notes (optional)"
                ></textarea>
              </div>

              <!-- Guide -->
              <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                      <strong>Guide:</strong> Driver's Phone must be Internet based phone so that can integrated with Transport Tracking Dashboard to know the Driver Geo Location via Google Map.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100 mt-4">
                <button
                  v-if="isEditing && editingVehicle"
                  type="button"
                  @click="deleteVehicle(editingVehicle, true)"
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
                    <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
                    <i v-else class="fas fa-save mr-2"></i>
                    {{ isEditing ? 'Update' : 'Save' }} Vehicle
                  </button>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>

    <VehicleTableModal
      :is-open="showVehicleModal"
      @close="showVehicleModal = false"
      @select="onVehicleSelected"
    />

    <VehicleClassTableModal
      :is-open="showVehicleClassModal"
      @close="showVehicleClassModal = false"
      @select="onVehicleClassSelected"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import VehicleTableModal from '@/Components/VehicleTableModal.vue'
import VehicleClassTableModal from '@/Components/VehicleClassTableModal.vue'
// Updated to Heroicons v2
import { ArrowPathIcon as RefreshIcon } from '@heroicons/vue/24/outline'

const { addToast } = useToast()

// Reactive data
const vehicles = ref([])
const vehicleClasses = ref([])
const loading = ref(false)
const saving = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const companyFilter = ref('')
const pagination = ref(null)
const currentPage = ref(1)

// Modal state
const showModal = ref(false)
const isEditing = ref(false)
const editingVehicle = ref(null)
const showVehicleModal = ref(false)
const showVehicleClassModal = ref(false)

// Form data
const form = reactive({
  VEHICLE_NO: '',
  VEHICLE_STATUS: 'A',
  VEHICLE_CLASS: '',
  VEHICLE_COMPANY: '',
  DRIVER_CODE: '',
  DRIVER_NAME: '',
  DRIVER_ID: '',
  DRIVER_PHONE: '',
  NOTE: ''
})

// Computed properties
const visiblePages = computed(() => {
  if (!pagination.value) return []

  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 2

  let start = Math.max(1, current - delta)
  let end = Math.min(last, current + delta)

  if (end - start < delta * 2) {
    if (start === 1) {
      end = Math.min(last, start + delta * 2)
    } else {
      start = Math.max(1, end - delta * 2)
    }
  }

  const pages = []
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Methods
// Helper to build API URLs that work in both dev (Vite proxy strips one /api) and production
const isDev = typeof window !== 'undefined' && window.location.port === '5173'
const buildApiUrl = (pathWithNoApiPrefix, queryParams = '') => {
  // Always target Laravel's /api routes
  const basePath = `/api${pathWithNoApiPrefix.startsWith('/') ? pathWithNoApiPrefix : '/' + pathWithNoApiPrefix}`
  const withQuery = queryParams ? `${basePath}?${queryParams}` : basePath
  // In Vite dev, prepend an extra /api so after proxy rewrite it still reaches /api/... on backend
  return isDev ? `/api${withQuery}` : withQuery
}
const fetchVehicles = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      page: currentPage.value,
      search: searchQuery.value,
      status: statusFilter.value,
      company: companyFilter.value,
      plain: '1'
    })

    const response = await fetch(buildApiUrl('/vehicles', params.toString()), {
      headers: {
        'Accept': 'application/json'
      }
    })

    if (!response.ok) {
      const text = await response.text()
      throw new Error(text || `Request failed with status ${response.status}`)
    }

    const data = await response.json()

    if (data && data.success) {
      // Prefer explicit rows array if provided, else support paginator object and plain array
      const list = Array.isArray(data.rows)
        ? data.rows
        : (Array.isArray(data.data)
            ? data.data
            : (data.data?.data ?? []))

      vehicles.value = list

      const paginator = Array.isArray(data.data) || Array.isArray(data.rows)
        ? {
            current_page: 1,
            last_page: 1,
            from: list.length > 0 ? 1 : 0,
            to: list.length,
            total: list.length
          }
        : {
            current_page: data.data?.current_page ?? 1,
            last_page: data.data?.last_page ?? 1,
            from: data.data?.from ?? (list.length > 0 ? 1 : 0),
            to: data.data?.to ?? list.length,
            total: data.data?.total ?? list.length
          }

      pagination.value = paginator
      vehicleClasses.value = data.vehicle_classes ?? vehicleClasses.value
    } else {
      addToast('Error fetching vehicles' + (data?.message ? `: ${data.message}` : ''), 'error')
      vehicles.value = []
      pagination.value = { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 }
    }
  } catch (error) {
    addToast('Error fetching vehicles: ' + (error?.message || error), 'error')
    vehicles.value = []
    pagination.value = { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 }
  } finally {
    loading.value = false
  }
}

const debouncedSearch = debounce(() => {
  currentPage.value = 1
  fetchVehicles()
}, 500)

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    currentPage.value = page
    fetchVehicles()
  }
}

const refreshData = () => {
  currentPage.value = 1
  fetchVehicles()
}

const openAddModal = () => {
  isEditing.value = false
  editingVehicle.value = null
  resetForm()
  showModal.value = true
}

const onVehicleSelected = (vehicle) => {
  if (!vehicle) return
  searchQuery.value = vehicle.VEHICLE_NO || ''
  showVehicleModal.value = false
  openEditModal(vehicle)
}

const onVehicleClassSelected = (cls) => {
  if (!cls) return
  form.VEHICLE_CLASS = cls.VEHICLE_CLASS_CODE || ''
}

const openEditModal = (vehicle) => {
  isEditing.value = true
  editingVehicle.value = vehicle
  Object.assign(form, {
    VEHICLE_NO: vehicle.VEHICLE_NO,
    VEHICLE_STATUS: vehicle.VEHICLE_STATUS,
    VEHICLE_CLASS: vehicle.VEHICLE_CLASS,
    VEHICLE_COMPANY: vehicle.VEHICLE_COMPANY,
    DRIVER_CODE: vehicle.DRIVER_CODE,
    DRIVER_NAME: vehicle.DRIVER_NAME,
    DRIVER_ID: vehicle.DRIVER_ID,
    DRIVER_PHONE: vehicle.DRIVER_PHONE,
    NOTE: vehicle.NOTE || ''
  })
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const resetForm = () => {
  Object.assign(form, {
    VEHICLE_NO: '',
    VEHICLE_STATUS: 'A',
    VEHICLE_CLASS: '',
    VEHICLE_COMPANY: '',
    DRIVER_CODE: '',
    DRIVER_NAME: '',
    DRIVER_ID: '',
    DRIVER_PHONE: '',
    NOTE: ''
  })
}

const saveVehicle = async () => {
  saving.value = true
  try {
    const url = isEditing.value
      ? buildApiUrl(`/vehicles/${editingVehicle.value.id}`)
      : buildApiUrl('/vehicles')

    const method = isEditing.value ? 'PUT' : 'POST'

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form)
    })

    if (!response.ok) {
      const text = await response.text()
      throw new Error(text || `Request failed with status ${response.status}`)
    }

    const data = await response.json()

    if (data.success) {
      addToast(data.message, 'success')
      closeModal()
      await fetchVehicles()
    } else {
      addToast(data.message || 'Error saving vehicle', 'error')
    }
  } catch (error) {
    addToast('Error saving vehicle: ' + (error?.message || error), 'error')
  } finally {
    saving.value = false
  }
}

const deleteVehicle = async (vehicle, fromModal = false) => {
  if (!confirm(`Are you sure you want to obsolete vehicle ${vehicle.VEHICLE_NO}?`)) {
    return
  }

  try {
    const response = await fetch(buildApiUrl(`/vehicles/${vehicle.id}/status`), {
      method: 'PUT',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        VEHICLE_STATUS: 'O'
      })
    })

    if (!response.ok) {
      const text = await response.text()
      throw new Error(text || `Request failed with status ${response.status}`)
    }

    const data = await response.json()

    if (!data || !data.success) {
      throw new Error(data?.message || 'Unknown error from server')
    }

    addToast(`Vehicle ${vehicle.VEHICLE_NO} obsoleted successfully`, 'success')
    if (fromModal) {
      closeModal()
    }
    await fetchVehicles()
  } catch (error) {
    addToast('Error obsoleting vehicle: ' + (error?.message || error), 'error')
  }
}

// Debounce utility function
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Lifecycle
onMounted(() => {
  // Do not auto-load vehicles; wait for user interaction (search/filter)
})
</script>

<style scoped>
/* Additional custom styles if needed */
</style>
