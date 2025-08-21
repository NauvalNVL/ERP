<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Vendor Profile Table
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

        <!-- Body -->
        <div class="px-4 py-5 sm:p-6">
          <!-- Search Bar -->
          <div class="mb-4 flex gap-2">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search by Vendor Name or AP AC#..."
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
              @click="searchVendors"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Search
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AP AC#</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GL AP Control</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GL Bank Control</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    <div class="flex justify-center items-center space-x-2">
                      <i class="fas fa-spinner fa-spin"></i>
                      <span>Loading vendors...</span>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="filteredVendors.length === 0" class="hover:bg-gray-50">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    No vendors found. Try adjusting your search.
                  </td>
                </tr>
                <tr 
                  v-for="vendor in filteredVendors" 
                  :key="vendor.id" 
                  @click="selectVendor(vendor)"
                  :class="{'bg-blue-50': selectedVendor && selectedVendor.id === vendor.id}"
                  class="hover:bg-gray-50 cursor-pointer"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ vendor.vendor_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ vendor.ap_ac_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(vendor.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ vendor.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ vendor.ac_type }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ vendor.currency }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ vendor.gl_ap_control }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ vendor.gl_bank_control }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
          <div class="flex space-x-2">
            <button
              @click="showMoreOptions"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              More Option
            </button>
            <button
              @click="showContactList"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Contact List
            </button>
          </div>
          <div class="flex space-x-2">
            <button
              @click="selectCurrentVendor"
              :disabled="!selectedVendor"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Select
            </button>
            <button
              @click="closeModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Exit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  currentVendorId: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close', 'select'])

const vendors = ref([])
const selectedVendor = ref(null)
const loading = ref(false)
const searchQuery = ref('')

// Mock vendor data - replace with actual API call
const mockVendors = [
  {
    id: 1,
    vendor_name: 'ABADI KARYA MAKMUR',
    ap_ac_number: '2100201421',
    status: 'A-Act',
    ac_type: 'Local',
    currency: 'IDR',
    gl_ap_control: 'HUTANG',
    gl_bank_control: 'RP PERMATA'
  },
  {
    id: 2,
    vendor_name: 'ABADI PRATAMA INDONESIA PT.',
    ap_ac_number: '2100201514',
    status: 'A-Act',
    ac_type: 'Local',
    currency: 'IDR',
    gl_ap_control: 'HUTANG',
    gl_bank_control: 'RP PERMATA'
  },
  {
    id: 3,
    vendor_name: 'ACCURA SOLIDTECH ADISEJAHTERA, PT.',
    ap_ac_number: '2100201198',
    status: 'A-Act',
    ac_type: 'Local',
    currency: 'IDR',
    gl_ap_control: 'HUTANG B',
    gl_bank_control: 'RP PERMATA'
  },
  {
    id: 4,
    vendor_name: 'ACEN JAYA ELEKTRIK, UD.',
    ap_ac_number: '2100201199',
    status: 'A-Act',
    ac_type: 'Local',
    currency: 'IDR',
    gl_ap_control: 'HUTANG',
    gl_bank_control: 'RP PERMATA'
  }
]

const filteredVendors = computed(() => {
  if (!searchQuery.value) {
    return vendors.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return vendors.value.filter(vendor =>
    vendor.vendor_name.toLowerCase().includes(query) ||
    vendor.ap_ac_number.toLowerCase().includes(query)
  )
})

const getStatusClass = (status) => {
  const statusClasses = {
    'A-Act': 'bg-green-100 text-green-800',
    'I-Inact': 'bg-red-100 text-red-800',
    'P-Pending': 'bg-yellow-100 text-yellow-800'
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-800'
}

const searchVendors = async () => {
  loading.value = true
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))
    vendors.value = mockVendors.filter(vendor =>
      vendor.vendor_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      vendor.ap_ac_number.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  } catch (error) {
    console.error('Error searching vendors:', error)
  } finally {
    loading.value = false
  }
}

const selectVendor = (vendor) => {
  selectedVendor.value = vendor
}

const selectCurrentVendor = () => {
  if (selectedVendor.value) {
    emit('select', selectedVendor.value)
    closeModal()
  }
}

const showMoreOptions = () => {
  // Implement more options functionality
  console.log('More options clicked')
}

const showContactList = () => {
  // Implement contact list functionality
  console.log('Contact list clicked')
}

const closeModal = () => {
  selectedVendor.value = null
  searchQuery.value = ''
  emit('close')
}

// Watch for show prop changes
watch(() => props.show, (newVal) => {
  if (newVal) {
    vendors.value = mockVendors
    if (props.currentVendorId) {
      selectedVendor.value = vendors.value.find(v => v.ap_ac_number === props.currentVendorId)
    }
  }
})
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style>
