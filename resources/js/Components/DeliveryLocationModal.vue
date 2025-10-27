<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
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
        <div class="px-8 py-6 overflow-y-auto max-h-[calc(90vh-140px)]">
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
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Address:</label>
                  <textarea 
                    v-model="billTo.address" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    readonly
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Ship to Section -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-3">
              <h4 class="text-md font-medium text-gray-800 flex items-center">
                <i class="fas fa-shipping-fast mr-2 text-orange-600"></i>
                Ship to
              </h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Delivery Code:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="shipTo.deliveryCode" 
                    type="text"
                    :disabled="shipTo.sameAddress"
                    @keyup.enter.prevent="validateDeliveryCode"
                    class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500"
                  >
                  <button 
                    @click="openDeliveryCodeLookup" 
                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    title="Lookup"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                  <div class="flex items-center">
                    <input 
                      v-model="shipTo.sameAddress" 
                      type="checkbox"
                      class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                      @change="applySameAddress"
                    >
                    <label class="text-sm text-gray-700">Leave blank if ship to the same address</label>
                  </div>
                </div>
              </div>
              
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                <input 
                  v-model="shipTo.customerName" 
                  type="text"
                  :readonly="shipTo.sameAddress"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 read-only:bg-gray-100 read-only:text-gray-500"
                >
              </div>
            </div>
            
            <div class="mt-3">
              <label class="block text-xs font-medium text-gray-600 mb-1">Address:</label>
              <textarea 
                v-model="shipTo.address" 
                rows="3"
                :readonly="shipTo.sameAddress"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 read-only:bg-gray-100 read-only:text-gray-500"
              ></textarea>
            </div>
          </div>


        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
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
    <DeliveryCodeLookupModal 
      :show="showDeliveryCodeModal" 
      @close="showDeliveryCodeModal = false" 
      @select="selectDeliveryCode"
      :customerCode="customer?.customer_code || customer?.code"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import DeliveryCodeLookupModal from '@/Components/DeliveryCodeLookupModal.vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  customer: Object,
  orderDetails: Object
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Base customer (from Update Customer Account)
const mainCustomer = reactive({
  name: '',
  address: '',
  email: '',
  contact: ''
})

// Form data (shown in modal)
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
  email: '',
  contact: '',
  sameAddress: false
})

// Modal visibility
const showDeliveryCodeModal = ref(false)

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

const validateDeliveryCode = async () => {
  if (!shipTo.deliveryCode || shipTo.sameAddress) return
  
  const customerCode = props.customer?.customer_code || props.customer?.code
  if (!customerCode) {
    error('Customer code is required to validate delivery code')
    return
  }
  
  try {
    console.log('Validating delivery code:', shipTo.deliveryCode, 'for customer:', customerCode)
    const res = await fetch(`/api/customer-alternate-addresses/${customerCode}`)
    const data = await res.json()
    
    if (!Array.isArray(data)) {
      console.error('Invalid response format from API:', data)
      error('Failed to validate delivery code - invalid response format')
      return
    }
    
    console.log('Available delivery codes for customer:', data.map(d => d.delivery_code))
    
    const found = data.find(d => 
      (d.delivery_code || '').toUpperCase() === shipTo.deliveryCode.toUpperCase()
    )
    
    if (found) {
      // Update ship-to information from CustomerAlternateAddress
      shipTo.customerName = found.ship_to_name || found.alternate_name || shipTo.customerName
      shipTo.address = found.ship_to_address || found.address || shipTo.address
      
      console.log('Delivery code validated successfully:', {
        code: shipTo.deliveryCode,
        name: shipTo.customerName,
        address: shipTo.address
      })
      success('Delivery code validated successfully')
    } else {
      const availableCodes = data.map(d => d.delivery_code).join(', ')
      error(`Delivery code "${shipTo.deliveryCode}" not found for this customer. Available codes: ${availableCodes}`)
      console.warn('Delivery code not found:', shipTo.deliveryCode, 'Available codes:', availableCodes)
    }
  } catch (e) {
    console.error('Error validating delivery code:', e)
    error('Failed to validate delivery code: ' + (e.message || 'Network error'))
  }
}

const applySameAddress = () => {
  if (shipTo.sameAddress) {
    shipTo.deliveryCode = ''
    shipTo.customerName = mainCustomer.name
    shipTo.address = mainCustomer.address
    
    console.log('Applied same address - Ship to now matches main customer:', {
      name: shipTo.customerName,
      address: shipTo.address
    })
  } else {
    // When unchecking, clear the ship-to fields
    shipTo.customerName = ''
    shipTo.address = ''
    
    console.log('Cleared ship-to fields - ready for delivery code input')
  }
}

const saveLocation = () => {
  // If delivery code empty, ship to defaults to main customer
  if (!shipTo.deliveryCode) {
    shipTo.customerName = mainCustomer.name
    shipTo.address = mainCustomer.address
  }
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

// Watch for delivery code changes to auto-validate
watch(() => shipTo.deliveryCode, (newCode, oldCode) => {
  if (newCode && newCode !== oldCode && !shipTo.sameAddress) {
    // Debounce validation to avoid too many API calls
    clearTimeout(window.deliveryCodeValidationTimeout)
    window.deliveryCodeValidationTimeout = setTimeout(() => {
      validateDeliveryCode()
    }, 500)
  }
})

// Watch for customer prop changes
watch(() => props.customer, (newCustomer) => {
  if (newCustomer && (newCustomer.customer_code || newCustomer.code)) {
    console.log('Customer prop changed, reloading data:', newCustomer)
    loadMainCustomer()
  }
}, { deep: true })

// Initialize component
onMounted(async () => {
  await loadMainCustomer()
})

const loadMainCustomer = async () => {
  try {
    console.log('loadMainCustomer called with props:', {
      customer: props.customer,
      orderDetails: props.orderDetails
    })
    
    // First check if we have pre-populated data from orderDetails (from customer alternate delivery location table)
    if (props.orderDetails?.deliveryLocation) {
      const prePopulated = props.orderDetails.deliveryLocation
      
      // Use pre-populated data from customer alternate delivery location table
      if (prePopulated.orderBy) {
        orderBy.customerName = prePopulated.orderBy.name || ''
      }
      
      if (prePopulated.billTo) {
        billTo.customerName = prePopulated.billTo.name || ''
        billTo.address = prePopulated.billTo.address || ''
      }
      
      if (prePopulated.shipTo) {
        shipTo.deliveryCode = prePopulated.shipTo.code || ''
        shipTo.customerName = prePopulated.shipTo.name || ''
        shipTo.address = prePopulated.shipTo.address || ''
        shipTo.email = prePopulated.shipTo.email || ''
        shipTo.contact = prePopulated.shipTo.contact || ''
      }
      
      // Set main customer data from orderBy
      mainCustomer.name = orderBy.customerName
      mainCustomer.address = billTo.address
      mainCustomer.email = ''
      mainCustomer.contact = ''
      
      console.log('Using pre-populated delivery location data from customer alternate delivery location table')
      return
    }
    
    // Fetch customer account data from UpdateCustomerAccount table
    const code = props.customer?.customer_code || props.customer?.code
    if (!code) {
      console.warn('No customer code available for loading customer account data')
      console.log('Customer prop:', props.customer)
      return
    }
    
    console.log('Fetching customer account data for:', code)
    const res = await fetch(`/api/sales-order/customer/${code}`)
    const data = await res.json()
    
    if (data?.success && data.data) {
      const customer = data.data
      
      // Populate main customer data from UpdateCustomerAccount
      mainCustomer.name = customer.customer_name || ''
      mainCustomer.address = customer.address || ''
      mainCustomer.email = customer.co_email || customer.email || ''
      mainCustomer.contact = customer.contact_person || customer.contact || ''

      // Populate Order by / Bill to from main customer account data
      orderBy.customerName = mainCustomer.name

      billTo.customerName = mainCustomer.name
      billTo.address = mainCustomer.address

      // Default Ship to = main customer (when no delivery code)
      if (!shipTo.deliveryCode || shipTo.sameAddress) {
        shipTo.customerName = mainCustomer.name
        shipTo.address = mainCustomer.address
        shipTo.email = mainCustomer.email
        shipTo.contact = mainCustomer.contact
      }
      
      console.log('Customer account data loaded successfully:', {
        name: mainCustomer.name,
        address: mainCustomer.address,
        email: mainCustomer.email,
        contact: mainCustomer.contact
      })
    } else {
      console.warn('Failed to load customer account data:', data?.message || 'Unknown error')
      // Fallback to props data
      mainCustomer.name = props.customer?.customer_name || props.customer?.name || ''
      mainCustomer.address = props.customer?.address || ''
      mainCustomer.email = props.customer?.email || ''
      mainCustomer.contact = props.customer?.contact || ''
      
      orderBy.customerName = mainCustomer.name
      
      billTo.customerName = mainCustomer.name
      billTo.address = mainCustomer.address
      
      shipTo.customerName = mainCustomer.name
      shipTo.address = mainCustomer.address
      shipTo.email = mainCustomer.email
      shipTo.contact = mainCustomer.contact
    }
  } catch (e) {
    console.error('Error loading customer account data:', e)
    // Fallback to props data
    mainCustomer.name = props.customer?.customer_name || props.customer?.name || ''
    mainCustomer.address = props.customer?.address || ''
    mainCustomer.email = props.customer?.email || ''
    mainCustomer.contact = props.customer?.contact || ''
    
    orderBy.customerName = mainCustomer.name
    
    billTo.customerName = mainCustomer.name
    billTo.address = mainCustomer.address
    
    shipTo.customerName = mainCustomer.name
    shipTo.address = mainCustomer.address
    shipTo.email = mainCustomer.email
    shipTo.contact = mainCustomer.contact
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
