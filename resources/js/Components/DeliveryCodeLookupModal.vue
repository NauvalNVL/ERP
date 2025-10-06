<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
              Customer Alternate Delivery Location Table
            </h3>
            <button 
              @click="$emit('close')"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
          <!-- Loading State -->
          <div v-if="loading" class="flex items-center justify-center py-8">
            <div class="flex items-center space-x-2">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
              <span class="text-gray-600">Loading delivery locations...</span>
            </div>
          </div>

          <!-- Error State -->
          <div v-else-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
              <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
              <span class="text-red-700">{{ errorMessage }}</span>
            </div>
          </div>

          <!-- No Data State -->
          <div v-else-if="deliveryLocations.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
              <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
              <span class="text-yellow-700">No delivery locations found for this customer. Please add alternate addresses first.</span>
            </div>
          </div>

          <!-- Delivery Code Table -->
          <div v-else class="mb-6">
            <div class="border border-gray-400 rounded">
              <!-- Table Header -->
              <div class="bg-gray-200 border-b border-gray-400">
                <div class="grid grid-cols-2">
                  <div class="px-4 py-2 font-bold text-gray-800 border-r border-gray-400">Delivery Code</div>
                  <div class="px-4 py-2 font-bold text-gray-800">Ship To</div>
                </div>
              </div>
              
              <!-- Table Body -->
              <div class="bg-white max-h-40 overflow-y-auto">
                <div 
                  v-for="(location, index) in deliveryLocations" 
                  :key="index"
                  :class="selectedLocation?.delivery_code === location.delivery_code ? 'bg-blue-100' : 'hover:bg-gray-50'"
                  class="grid grid-cols-2 cursor-pointer border-b border-gray-200"
                  @click="selectLocation(location)"
                >
                  <div class="px-4 py-2 border-r border-gray-200">{{ location.delivery_code }}</div>
                  <div class="px-4 py-2">{{ location.ship_to }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Location Details Form -->
          <div class="space-y-4">
            <!-- Row 1: Country and Town -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Country:</label>
                <input 
                  v-model="formData.country" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Town:</label>
                <input 
                  v-model="formData.town" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
            </div>

            <!-- Row 2: State and Section -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State:</label>
                <input 
                  v-model="formData.state" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Section:</label>
                <input 
                  v-model="formData.section" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
            </div>

            <!-- Row 3: Address -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Address:</label>
              <textarea 
                v-model="formData.address" 
                rows="2"
                class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                readonly
              ></textarea>
            </div>

            <!-- Row 4: Contact -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Contact:</label>
              <input 
                v-model="formData.contact" 
                type="text" 
                class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                readonly
              >
            </div>

            <!-- Row 5: Tel No and Fax No -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tel No:</label>
                <input 
                  v-model="formData.tel_no" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fax No:</label>
                <input 
                  v-model="formData.fax_no" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                  readonly
                >
              </div>
            </div>

            <!-- Row 6: Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
              <input 
                v-model="formData.email" 
                type="text" 
                class="w-full px-3 py-2 border border-gray-400 rounded text-sm"
                readonly
              >
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-center space-x-3">
          <button 
            @click="selectDeliveryCode"
            :disabled="!selectedLocation || loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
            Select
          </button>
          <button 
            @click="$emit('close')"
            :disabled="loading"
            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
          >
            Exit
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  customerCode: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close', 'select'])

const { success, error } = useToast()

// Selected location
const selectedLocation = ref(null)

// Loading and error states
const loading = ref(false)
const errorMessage = ref('')

// Form data for displaying location details
const formData = reactive({
  country: '',
  town: '',
  state: '',
  section: '',
  address: '',
  contact: '',
  tel_no: '',
  fax_no: '',
  email: ''
})

// Delivery locations data from database
const deliveryLocations = ref([])

// Methods
const selectLocation = (location) => {
  selectedLocation.value = location
  
  // Update form data
  formData.country = location.country
  formData.town = location.town
  formData.state = location.state
  formData.section = location.section
  formData.address = location.address
  formData.contact = location.contact
  formData.tel_no = location.tel_no
  formData.fax_no = location.fax_no
  formData.email = location.email
}

const selectDeliveryCode = () => {
  if (!selectedLocation.value) {
    error('Please select a delivery location')
    return
  }
  
  // Directly emit the selected delivery code
  emit('select', {
    code: selectedLocation.value.delivery_code,
    ship_to: selectedLocation.value.ship_to,
    address: selectedLocation.value.address,
    email: selectedLocation.value.email,
    contact_person: selectedLocation.value.contact,
    country: selectedLocation.value.country,
    town: selectedLocation.value.town,
    state: selectedLocation.value.state,
    section: selectedLocation.value.section,
    tel_no: selectedLocation.value.tel_no,
    fax_no: selectedLocation.value.fax_no
  })
  
  success('Delivery location selected successfully')
}


// Load delivery locations for customer
const loadDeliveryLocations = async () => {
  if (!props.customerCode) {
    console.log('No customer code provided')
    deliveryLocations.value = []
    return
  }
  
  loading.value = true
  errorMessage.value = ''
  
  try {
    console.log('Loading delivery locations for customer:', props.customerCode)
    
    const response = await fetch(`/api/customer-alternate-addresses/${props.customerCode}`)
    const data = await response.json()
    
    if (response.ok) {
      // Transform data to match the expected format
      deliveryLocations.value = data.map(address => ({
        delivery_code: address.delivery_code,
        ship_to: address.ship_to_name || address.bill_to_name || 'N/A',
        country: address.country || '',
        town: address.town || '',
        state: address.state || '',
        section: address.town_section || '',
        address: address.ship_to_address || address.bill_to_address || '',
        contact: address.contact_person || '',
        tel_no: address.tel_no || '',
        fax_no: address.fax_no || '',
        email: address.email || ''
      }))
      
      console.log('Loaded delivery locations:', deliveryLocations.value.length)
      
      // Auto-select first location if available
      if (deliveryLocations.value.length > 0) {
        selectLocation(deliveryLocations.value[0])
      } else {
        // Clear form data if no locations found
        Object.assign(formData, {
          country: '',
          town: '',
          state: '',
          section: '',
          address: '',
          contact: '',
          tel_no: '',
          fax_no: '',
          email: ''
        })
      }
    } else {
      console.error('Error loading delivery locations:', data)
      errorMessage.value = data.error || 'Failed to load delivery locations'
      error('Failed to load delivery locations: ' + (data.error || 'Unknown error'))
    }
  } catch (err) {
    console.error('Error loading delivery locations:', err)
    errorMessage.value = 'Network error: ' + err.message
    error('Failed to load delivery locations')
  } finally {
    loading.value = false
  }
}

// Initialize
onMounted(() => {
  loadDeliveryLocations()
})

// Watch for customer code changes
watch(() => props.customerCode, () => {
  loadDeliveryLocations()
})
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Table hover effects */
.grid:hover {
  background-color: #f9fafb;
}

/* Input focus styles */
input:focus,
textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
