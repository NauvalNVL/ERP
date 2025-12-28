<template>
  <div v-if="show" class="fixed inset-0 z-50">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-slate-900/70 to-blue-900/70 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white/95 rounded-2xl shadow-2xl border border-blue-100 w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 text-white flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <i class="fas fa-map-marked-alt text-lg"></i>
            </div>
            <div>
              <p class="text-xs uppercase tracking-widest text-white/80 mb-0.5">Define Customer Alternate Address</p>
              <h3 class="text-xl font-semibold">Customer Alternate Delivery Location Table</h3>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="p-2 rounded-full hover:bg-white/20 transition-colors text-white"
          >
            <i class="fas fa-times text-lg"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto flex-1 bg-gradient-to-b from-slate-50 via-white to-slate-50">
          <!-- Loading State -->
          <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="flex items-center space-x-3 bg-white rounded-xl px-5 py-3 shadow-md border border-blue-100">
              <div class="animate-spin rounded-full h-8 w-8 border-2 border-white border-t-transparent border-b-transparent"></div>
              <span class="text-blue-700 font-medium">Loading delivery locations...</span>
            </div>
          </div>

          <!-- Error State -->
          <div v-else-if="errorMessage" class="bg-red-50/80 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
              </div>
              <span class="text-red-700 font-medium">{{ errorMessage }}</span>
            </div>
          </div>

          <!-- No Data State -->
          <div v-else-if="deliveryLocations.length === 0" class="bg-yellow-50/90 border border-yellow-200 rounded-xl p-4 mb-6 shadow-sm">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                <i class="fas fa-info text-yellow-600"></i>
              </div>
              <span class="text-yellow-700 font-medium">No delivery locations found for this customer. Please add alternate addresses first.</span>
            </div>
          </div>

          <!-- Delivery Code Table -->
          <div v-else class="mb-6 bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-3 flex items-center justify-between">
              <div>
                <p class="text-xs uppercase tracking-widest text-white/70">Saved Locations</p>
                <p class="text-sm font-semibold">Select an alternate delivery location</p>
              </div>
              <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-xs font-semibold">
                <i class="fas fa-layer-group mr-2 text-white"></i>{{ deliveryLocations.length }} entries
              </span>
            </div>

            <div class="divide-y divide-blue-50 max-h-52 overflow-y-auto">
              <div
                v-for="(location, index) in deliveryLocations"
                :key="index"
                class="flex items-stretch cursor-pointer transition group"
                :class="selectedLocation?.delivery_code === location.delivery_code ? 'bg-cyan-50/80' : 'bg-white hover:bg-blue-50/70'"
                @click="selectLocation(location)"
              >
                <div class="w-1" :class="selectedLocation?.delivery_code === location.delivery_code ? 'bg-gradient-to-b from-cyan-400 to-blue-500' : 'bg-transparent group-hover:bg-blue-200 transition'"></div>
                <div class="flex-1 grid grid-cols-2 gap-4 px-4 py-3">
                  <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400">Delivery Code</p>
                    <p class="text-base font-semibold text-gray-800">{{ location.delivery_code }}</p>
                  </div>
                  <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400">Ship To</p>
                    <p class="text-sm font-medium text-gray-700">{{ location.ship_to }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Location Details Form -->
          <div class="space-y-4 bg-white rounded-2xl border border-blue-50 shadow-sm p-5">
            <div class="flex items-center mb-2">
              <div class="w-10 h-10 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center mr-3">
                <i class="fas fa-map-pin"></i>
              </div>
              <div>
                <p class="text-xs uppercase tracking-widest text-gray-400">Location Detail</p>
                <p class="text-base font-semibold text-gray-800">Preview of the selected delivery address</p>
              </div>
            </div>

            <!-- Row 1: Country and Town -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-field">
                <label>Country</label>
                <input v-model="formData.country" type="text" readonly />
              </div>
              <div class="form-field">
                <label>Town</label>
                <input v-model="formData.town" type="text" readonly />
              </div>
            </div>

            <!-- Row 2: State and Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-field">
                <label>State</label>
                <input v-model="formData.state" type="text" readonly />
              </div>
              <div class="form-field">
                <label>Section</label>
                <input v-model="formData.section" type="text" readonly />
              </div>
            </div>

            <!-- Row 3: Address -->
            <div class="form-field">
              <label>Address</label>
              <textarea v-model="formData.address" rows="2" readonly></textarea>
            </div>

            <!-- Row 4: Contact -->
            <div class="form-field">
              <label>Contact Person</label>
              <input v-model="formData.contact" type="text" readonly />
            </div>

            <!-- Row 5: Tel No and Fax No -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-field">
                <label>Tel No</label>
                <input v-model="formData.tel_no" type="text" readonly />
              </div>
              <div class="form-field">
                <label>Fax No</label>
                <input v-model="formData.fax_no" type="text" readonly />
              </div>
            </div>

            <!-- Row 6: Email -->
            <div class="form-field">
              <label>Email</label>
              <input v-model="formData.email" type="text" readonly />
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50/80 border-t border-blue-100 flex justify-end space-x-3">
          <button
            @click="$emit('close')"
            :disabled="loading"
            class="secondary-btn"
          >
            <i class="fas fa-times mr-2"></i>Close
          </button>
          <button
            @click="selectDeliveryCode"
            :disabled="!selectedLocation || loading"
            class="primary-btn disabled:opacity-70 disabled:cursor-not-allowed"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-check mr-2"></i>
            Use this location
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
    id: selectedLocation.value.id,
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
    fax_no: selectedLocation.value.fax_no,
    raw: selectedLocation.value.raw
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
        id: address.id,
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
        email: address.email || '',
        raw: address
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

// Reload when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    loadDeliveryLocations()
  }
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

.form-field label {
  @apply block text-xs uppercase tracking-widest text-gray-500 mb-1;
}

.form-field input,
.form-field textarea {
  @apply w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm text-gray-700 transition;
}

.form-field input:focus,
.form-field textarea:focus {
  @apply outline-none border-blue-400 ring-2 ring-blue-100 bg-white;
}

.primary-btn {
  @apply inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold shadow-md transition hover:shadow-lg hover:from-blue-700 hover:to-cyan-600;
}

.secondary-btn {
  @apply inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 bg-white font-semibold shadow-sm transition hover:shadow-md hover:text-slate-800;
}
</style>
