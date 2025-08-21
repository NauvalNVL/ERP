<template>
  <AppLayout :header="'Prepare Purchase Requisition'">
    <Head title="Prepare Purchase Requisition" />

      <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-plus mr-3"></i> Prepare Purchase Requisition
          </h2>
      <p class="text-blue-100">Create new purchase requisition requests</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- PR Period Management Section -->
      <div class="mb-8 p-6 bg-gray-50 rounded-lg border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
          PR Period Management
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Current PR Period -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Current PR Period
            </label>
            <div class="flex items-center space-x-3">
              <input
                type="text"
                v-model="currentPRPeriod"
                readonly
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700"
                placeholder="6/2025"
              />
              <button
                @click="updatePRPeriod"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <i class="fas fa-edit mr-1"></i>
                Update
              </button>
            </div>
          </div>

          <!-- AP AC# Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              AP AC#
            </label>
            <div class="flex items-center space-x-3">
              <input
                type="text"
                v-model="selectedVendor.ap_ac_number"
                readonly
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700"
                placeholder="Select vendor..."
              />
              <button
                @click="showVendorModal = true"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                <i class="fas fa-search mr-1"></i>
                Lookup
              </button>
            </div>
            <div v-if="selectedVendor.vendor_name" class="mt-2 text-sm text-gray-600">
              <strong>Vendor:</strong> {{ selectedVendor.vendor_name }}
            </div>
          </div>
        </div>
      </div>

      <!-- Simple Toolbar -->
      <div class="flex justify-between items-center mb-6 p-4 bg-gray-100 rounded-lg">
        <div class="flex items-center space-x-4">
          <button
            @click="goBack"
            class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            Back
          </button>
          <button
            @click="savePR"
            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <i class="fas fa-save mr-2"></i>
            Save
          </button>
        </div>
        
        <div class="flex items-center space-x-2">
          <button
            @click="submitPR"
            :disabled="!canSubmit"
            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-paper-plane mr-2"></i>
            Submit
          </button>
          <button
            @click="printPR"
            class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
          >
            <i class="fas fa-print mr-2"></i>
            Print
          </button>
        </div>
        </div>

      <!-- PR Form -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
        <div class="space-y-6">
          <!-- Basic Information -->
          <div class="bg-white p-6 rounded-lg border">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h4>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">PR Number</label>
                <input 
                  type="text" 
                  v-model="prForm.pr_number" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Auto-generated"
                  readonly 
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select
                  v-model="prForm.department"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select Department</option>
                  <option value="IT">IT Department</option>
                  <option value="HR">HR Department</option>
                  <option value="Finance">Finance Department</option>
                  <option value="Operations">Operations Department</option>
                  </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                <textarea
                  v-model="prForm.purpose"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter purpose of purchase requisition..."
                ></textarea>
                </div>
              </div>
            </div>

          <!-- Item Details -->
          <div class="bg-white p-6 rounded-lg border">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Item Details</h4>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                <div class="flex items-center space-x-2">
                  <input 
                    type="text" 
                    v-model="prForm.sku"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter SKU or search..."
                  />
                  <button 
                    @click="showSkuModal = true"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea
                  v-model="prForm.description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter item description..."
                ></textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input 
                    type="number"
                    v-model="prForm.quantity"
                    min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="0"
                  />
              </div>

              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                  <input
                    type="number"
                    v-model="prForm.unit_price"
                    min="0"
                    step="0.01"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="0.00"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                  <input 
                    type="text" 
                  :value="formatCurrency(totalAmount)"
                  readonly
                  class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 font-semibold"
                />
                </div>
              </div>
            </div>
          </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <!-- Specifications -->
          <div class="bg-white p-6 rounded-lg border">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Specifications</h4>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Technical Specifications</label>
                <textarea
                  v-model="prForm.specifications"
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter technical specifications..."
                ></textarea>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Requirements</label>
            <textarea 
                  v-model="prForm.additional_requirements"
              rows="3" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter additional requirements..."
            ></textarea>
          </div>
        </div>
      </div>

          <!-- Approval Information -->
          <div class="bg-white p-6 rounded-lg border">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Approval Information</h4>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Requested By</label>
                <div class="flex items-center space-x-2">
                  <input
                    type="text"
                    v-model="prForm.requested_by"
                    readonly
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700"
                    placeholder="Current user"
                  />
            <button 
                    @click="showUserModal = true"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                    <i class="fas fa-user"></i>
            </button>
          </div>
        </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select
                  v-model="prForm.priority"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                  <option value="urgent">Urgent</option>
                </select>
                  </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date</label>
                  <input 
                  type="date"
                  v-model="prForm.expected_delivery_date"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
          </div>

          <!-- Status Information -->
          <div class="bg-white p-6 rounded-lg border">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Status Information</h4>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="px-3 py-2 bg-gray-100 rounded-md">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Draft
                  </span>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Created Date</label>
                  <input 
                    type="text" 
                  :value="formatDate(new Date())"
                  readonly
                  class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700"
                />
        </div>
            </div>
            </div>
          </div>
        </div>
      </div>

    <!-- Modals -->
    <VendorProfileModal
      :show="showVendorModal"
      :current-vendor-id="selectedVendor.ap_ac_number"
      @close="showVendorModal = false"
      @select="selectVendor"
    />

    <SkuLookupModal 
      :show="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />

    <UserLookupModal 
      :show="showUserModal"
      @close="showUserModal = false"
      @select="selectUser"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import UserLookupModal from '@/Components/UserLookupModal.vue'

// Reactive data
const currentPRPeriod = ref('6/2025')
const showVendorModal = ref(false)
const showSkuModal = ref(false)
const showUserModal = ref(false)

const selectedVendor = ref({
  ap_ac_number: '',
  vendor_name: ''
})

const prForm = ref({
  pr_number: '',
  department: '',
  purpose: '',
  sku: '',
  description: '',
  quantity: 1,
  unit_price: 0,
  specifications: '',
  additional_requirements: '',
  requested_by: 'Current User',
  priority: 'medium',
  expected_delivery_date: '',
  status: 'Draft'
})

// Computed properties
const totalAmount = computed(() => {
  return (prForm.value.quantity || 0) * (prForm.value.unit_price || 0)
})

const canSubmit = computed(() => {
  return selectedVendor.value.ap_ac_number && 
         prForm.value.department && 
         prForm.value.purpose &&
         prForm.value.sku &&
         prForm.value.description &&
         prForm.value.quantity > 0
})

// Methods
const updatePRPeriod = () => {
  // Implement PR period update logic
  console.log('Update PR Period clicked')
}

const selectVendor = (vendor) => {
  selectedVendor.value = vendor
  showVendorModal.value = false
}

const selectSku = (sku) => {
  prForm.value.sku = sku.sku
  prForm.value.description = sku.sku_name
  prForm.value.unit_price = sku.current_price || 0
  showSkuModal.value = false
}

const selectUser = (user) => {
  prForm.value.requested_by = user.name
  showUserModal.value = false
}

const savePR = async () => {
  try {
    // Implement save logic
    console.log('Saving PR:', prForm.value)
    // Show success message
  } catch (error) {
    console.error('Error saving PR:', error)
  }
}

const submitPR = async () => {
  try {
    // Implement submit logic
    console.log('Submitting PR:', prForm.value)
    // Show success message
  } catch (error) {
    console.error('Error submitting PR:', error)
  }
}

const printPR = () => {
  // Implement print logic
  console.log('Printing PR')
}

const goBack = () => {
  window.history.back()
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

// Lifecycle
onMounted(() => {
  // Generate PR number
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const day = String(now.getDate()).padStart(2, '0')
  const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')
  
  prForm.value.pr_number = `PR-${year}${month}${day}-${random}`
})
</script>

<style scoped>
/* Add any specific styles here */
</style>
