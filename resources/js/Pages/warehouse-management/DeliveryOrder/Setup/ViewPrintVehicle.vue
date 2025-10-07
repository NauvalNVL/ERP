<template>
  <AppLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="mb-8">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600">
          <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_top_right,white,transparent_50%)]"></div>
          <div class="flex items-center justify-between p-5">
            <div class="flex items-center gap-3 text-white">
              <i class="fas fa-truck text-2xl"></i>
              <div>
                <h1 class="text-2xl font-semibold">View & Print Vehicle</h1>
                <p class="text-white/80 text-sm">Filter, print or export vehicle information</p>
              </div>
            </div>
            <div class="hidden sm:flex items-center gap-2">
              <button
                @click="printReport"
                class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors backdrop-blur-sm"
              >
                <i class="fas fa-print"></i>
                Print
              </button>
              <button
                @click="exportToPDF"
                class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors backdrop-blur-sm"
              >
                <i class="fas fa-file-pdf"></i>
                PDF
              </button>
              <button
                @click="exportToExcel"
                class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors backdrop-blur-sm"
              >
                <i class="fas fa-file-csv"></i>
                CSV
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search vehicles..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              @input="debouncedSearch"
            />
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="filters.status"
              @change="fetchVehicles"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">All Status</option>
              <option value="A">Active</option>
              <option value="O">Obsolete</option>
            </select>
          </div>

          <!-- Company Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
            <select
              v-model="filters.company"
              @change="fetchVehicles"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">All Companies</option>
              <option value="KIM">KIM</option>
              <option value="CUSTOMER">CUSTOMER</option>
              <option value="MBI">MBI</option>
            </select>
          </div>

          <!-- Vehicle Class Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Class</label>
            <select
              v-model="filters.vehicleClass"
              @change="fetchVehicles"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">All Classes</option>
              <option v-for="vc in vehicleClasses" :key="vc.VEHICLE_CLASS_CODE" :value="vc.VEHICLE_CLASS_CODE">
                {{ vc.VEHICLE_CLASS_CODE }} - {{ vc.DESCRIPTION }}
              </option>
            </select>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 mt-4">
          <button
            @click="fetchVehicles"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <i class="fas fa-search"></i>
            Search
          </button>
          <button
            @click="printReport"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <i class="fas fa-print"></i>
            Print Report
          </button>
          <button
            @click="exportToExcel"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <i class="fas fa-file-excel"></i>
            Export Excel
          </button>
          <button
            @click="clearFilters"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <i class="fas fa-times"></i>
            Clear Filters
          </button>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-truck text-blue-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Vehicles</p>
              <p class="text-2xl font-semibold text-gray-900">{{ summary.total }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Active Vehicles</p>
              <p class="text-2xl font-semibold text-gray-900">{{ summary.active }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-times-circle text-red-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Obsolete Vehicles</p>
              <p class="text-2xl font-semibold text-gray-900">{{ summary.obsolete }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-users text-purple-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Drivers</p>
              <p class="text-2xl font-semibold text-gray-900">{{ summary.drivers }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Vehicle Report Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Vehicle Report</h3>
          <p class="text-sm text-gray-500">Generated on {{ new Date().toLocaleDateString() }}</p>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  No.
                </th>
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
                  Description
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
                  Driver ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Driver Phone
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Note
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Created Date
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="12" class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                    Loading vehicles...
                  </div>
                </td>
              </tr>
              <tr v-else-if="vehicles.length === 0">
                <td colspan="12" class="px-6 py-4 text-center text-gray-500">
                  No vehicles found
                </td>
              </tr>
              <tr v-else v-for="(vehicle, index) in vehicles" :key="vehicle.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ vehicle.VEHICLE_NO }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="vehicle.VEHICLE_STATUS === 'A' 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-red-100 text-red-800'"
                    class="px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ vehicle.VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ vehicle.VEHICLE_CLASS }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ vehicle.VEHICLE_DESCRIPTION || '-' }}
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
                  {{ vehicle.DRIVER_ID }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ vehicle.DRIVER_PHONE }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ vehicle.NOTE || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ new Date(vehicle.created_at).toLocaleDateString() }}
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
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'

const { addToast } = useToast()

// Reactive data
const vehicles = ref([])
const vehicleClasses = ref([])
const loading = ref(false)
const pagination = ref(null)
const currentPage = ref(1)

// Filters
const filters = reactive({
  search: '',
  status: '',
  company: '',
  vehicleClass: ''
})

// Summary data
const summary = ref({
  total: 0,
  active: 0,
  obsolete: 0,
  drivers: 0
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
const fetchVehicles = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      page: currentPage.value,
      search: filters.search,
      status: filters.status,
      company: filters.company,
      vehicle_class: filters.vehicleClass
    })
    
    const response = await fetch(`/api/vehicles?${params}`)
    const data = await response.json()
    
    if (data.success) {
      vehicles.value = data.data.data
      pagination.value = {
        current_page: data.data.current_page,
        last_page: data.data.last_page,
        from: data.data.from,
        to: data.data.to,
        total: data.data.total
      }
      vehicleClasses.value = data.vehicle_classes
      
      // Calculate summary
      calculateSummary(data.data.data)
    } else {
      addToast('Error fetching vehicles: ' + data.message, 'error')
    }
  } catch (error) {
    addToast('Error fetching vehicles: ' + error.message, 'error')
  } finally {
    loading.value = false
  }
}

const calculateSummary = (vehicleData) => {
  summary.value.total = vehicleData.length
  summary.value.active = vehicleData.filter(v => v.VEHICLE_STATUS === 'A').length
  summary.value.obsolete = vehicleData.filter(v => v.VEHICLE_STATUS === 'O').length
  summary.value.drivers = new Set(vehicleData.map(v => v.DRIVER_CODE)).size
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

const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.company = ''
  filters.vehicleClass = ''
  currentPage.value = 1
  fetchVehicles()
}

const printReport = () => {
  window.print()
}

const exportToPDF = () => {
  try {
    const doc = new jsPDF({ orientation: 'landscape' })
    const title = 'Vehicles Report'
    doc.setFontSize(14)
    doc.text(title, 14, 14)

    const headers = [['No.', 'Vehicle #', 'Status', 'Class', 'Description', 'Company', 'Driver Code', 'Driver Name', 'Driver ID', 'Driver Phone', 'Note', 'Created Date']]
    const rows = vehicles.value.map((v, idx) => [
      idx + 1,
      v.VEHICLE_NO,
      v.VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete',
      v.VEHICLE_CLASS,
      v.VEHICLE_DESCRIPTION || '-',
      v.VEHICLE_COMPANY || '-',
      v.DRIVER_CODE || '-',
      v.DRIVER_NAME || '-',
      v.DRIVER_ID || '-',
      v.DRIVER_PHONE || '-',
      v.NOTE || '-',
      new Date(v.created_at).toLocaleDateString()
    ])

    autoTable(doc, {
      head: headers,
      body: rows,
      startY: 20,
      styles: { fontSize: 8 },
      headStyles: { fillColor: [79, 70, 229] },
      alternateRowStyles: { fillColor: [245, 245, 245] }
    })

    doc.save(`vehicles_${new Date().toISOString().split('T')[0]}.pdf`)
    addToast('PDF exported successfully', 'success')
  } catch (e) {
    addToast('Failed to export PDF', 'error')
  }
}

const exportToExcel = async () => {
  try {
    const params = new URLSearchParams({
      search: filters.search,
      status: filters.status,
      company: filters.company,
      vehicle_class: filters.vehicleClass,
      export: 'excel'
    })
    
    const response = await fetch(`/api/vehicles/export?${params}`)
    
    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `vehicles_report_${new Date().toISOString().split('T')[0]}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      addToast('Excel file exported successfully', 'success')
    } else {
      addToast('Error exporting Excel file', 'error')
    }
  } catch (error) {
    addToast('Error exporting Excel file: ' + error.message, 'error')
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
  fetchVehicles()
})
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }
  
  body {
    font-size: 12px;
  }
  
  table {
    font-size: 10px;
  }
  
  .bg-gray-50 {
    background-color: #f9fafb !important;
  }
}
</style>
