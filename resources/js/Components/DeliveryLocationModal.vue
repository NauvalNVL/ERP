<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
              Delivery Location: 0-Order by Customer + Deliver & Invoice to Customer
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
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <!-- Order Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Order by Section -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Order by
              </h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                  <input 
                    v-model="orderBy.customerName" 
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    readonly
                  >
                </div>
              </div>
            </div>

            <!-- Bill to Section -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-file-invoice mr-2 text-green-600"></i>
                Bill to
              </h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                  <input 
                    v-model="billTo.customerName" 
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Address:</label>
                  <textarea 
                    v-model="billTo.address" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Ship to Section -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-md font-medium text-gray-800 flex items-center">
                <i class="fas fa-shipping-fast mr-2 text-orange-600"></i>
                Ship to
              </h4>
              <div class="flex items-center space-x-4">
                <button 
                  @click="openDeliveryCodeLookup" 
                  class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                  title="Delivery Code Lookup"
                >
                  <i class="fas fa-search"></i>
                </button>
                <div class="flex items-center">
                  <input 
                    v-model="shipTo.sameAddress" 
                    type="checkbox"
                    class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  >
                  <label class="text-sm text-gray-700">Leave blank if ship to the same address</label>
                </div>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Delivery Code:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="shipTo.deliveryCode" 
                    type="text"
                    class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                  <button 
                    @click="openDeliveryCodeLookup" 
                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    title="Lookup"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                <input 
                  v-model="shipTo.customerName" 
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
            </div>
            
            <div class="mt-3">
              <label class="block text-xs font-medium text-gray-600 mb-1">Address:</label>
              <textarea 
                v-model="shipTo.address" 
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              ></textarea>
            </div>
          </div>

          <!-- Customer Alternate Delivery Location Table -->
          <div class="mb-6">
            <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
              <i class="fas fa-table mr-2 text-purple-600"></i>
              Customer Alternate Delivery Location Table
            </h4>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ship To</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Town</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">State</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tel No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fax No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="location in deliveryLocations" :key="location.delivery_code" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ location.delivery_code }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.ship_to }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.country }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.town }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.state }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.section }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.address }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.contact }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.tel_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.fax_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ location.email }}</td>
                    <td class="px-4 py-3 text-center">
                      <button 
                        @click="selectDeliveryLocation(location)"
                        class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                      >
                        Select
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Table Action Buttons -->
            <div class="flex justify-center space-x-3 mt-4">
              <button 
                @click="moreOptions"
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors"
              >
                More Options
              </button>
              <button 
                @click="zoomTable"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
              >
                Zoom
              </button>
              <button 
                @click="selectFromTable"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
              >
                Select
              </button>
              <button 
                @click="exitTable"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
              >
                Exit
              </button>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="$emit('close')"
            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="saveLocation"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
          >
            OK
          </button>
        </div>
      </div>
    </div>

    <!-- Delivery Code Lookup Modal -->
    <DeliveryCodePreviewModal 
      v-if="showDeliveryCodeModal" 
      @close="showDeliveryCodeModal = false" 
      @select="selectDeliveryCode"
      :customer-code="customer?.customer_code"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import DeliveryCodePreviewModal from '@/Components/DeliveryCodePreviewModal.vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  customer: Object,
  orderDetails: Object
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Form data
const orderBy = reactive({
  customerName: ''
})

const billTo = reactive({
  customerName: '',
  address: ''
})

const shipTo = reactive({
  deliveryCode: '',
  customerName: '',
  address: '',
  sameAddress: false
})

// Modal visibility
const showDeliveryCodeModal = ref(false)

// Delivery locations data
const deliveryLocations = ref([
  {
    delivery_code: 'P103',
    ship_to: 'ABDULLAH, BPK',
    country: 'INDONESIA',
    town: 'TANGERANG',
    state: 'BANTEN',
    section: 'TANGERANG',
    address: 'JL.YOS SUDARSO NO.61 JURUMUDI BARU-TANGERANG',
    contact: '',
    tel_no: '6191875',
    fax_no: '5407992',
    email: ''
  }
])

// Methods
const openDeliveryCodeLookup = () => {
  showDeliveryCodeModal.value = true
}

const selectDeliveryCode = (deliveryCode) => {
  shipTo.deliveryCode = deliveryCode.code
  shipTo.customerName = deliveryCode.ship_to
  shipTo.address = deliveryCode.address
  showDeliveryCodeModal.value = false
}

const selectDeliveryLocation = (location) => {
  shipTo.deliveryCode = location.delivery_code
  shipTo.customerName = location.ship_to
  shipTo.address = location.address
  success('Delivery location selected')
}

const moreOptions = () => {
  // Open more options dialog
  success('More options clicked')
}

const zoomTable = () => {
  // Zoom table functionality
  success('Table zoomed')
}

const selectFromTable = () => {
  // Select from table functionality
  if (!shipTo.deliveryCode) {
    error('Please select a delivery location first')
    return
  }
  success('Location selected from table')
}

const exitTable = () => {
  // Exit table functionality
  success('Exited table view')
}

const saveLocation = () => {
  if (!billTo.customerName || !shipTo.customerName) {
    error('Please fill in required fields')
    return
  }

  const locationData = {
    orderBy,
    billTo,
    shipTo
  }

  emit('save', locationData)
}

// Initialize component
onMounted(() => {
  if (props.customer) {
    orderBy.customerName = props.customer.customer_name || props.customer.name
    billTo.customerName = props.customer.customer_name || props.customer.name
    billTo.address = props.customer.address || 'TANGERANG'
    shipTo.customerName = props.customer.customer_name || props.customer.name
    shipTo.address = props.customer.address || 'TANGERANG'
  }

  // Load delivery locations for customer
  loadDeliveryLocations()
})

const loadDeliveryLocations = async () => {
  if (!props.customer?.customer_code) return
  
  try {
    const response = await fetch(`/api/customer-alternate-addresses/${props.customer.customer_code}`)
    const data = await response.json()
    
    if (data && Array.isArray(data)) {
      deliveryLocations.value = data.map(location => ({
        delivery_code: location.delivery_code || 'P103',
        ship_to: location.alternate_name || props.customer.customer_name,
        country: location.country || 'INDONESIA',
        town: location.town || 'TANGERANG',
        state: location.state || 'BANTEN',
        section: location.section || 'TANGERANG',
        address: location.address || '',
        contact: location.contact_person || '',
        tel_no: location.telephone_no || '',
        fax_no: location.fax_no || '',
        email: location.email || ''
      }))
    }
  } catch (err) {
    console.error('Error loading delivery locations:', err)
  }
}
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
tbody tr:hover {
  background-color: #f9fafb;
}

/* Input focus styles */
input:focus, textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
