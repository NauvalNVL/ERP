<template>
  <AppLayout :header="'Prepare MI (Material Issue)'">
    <Head title="Prepare MI (Material Issue)" />
    
    <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 rounded-xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold flex items-center mb-3">
              <i class="fas fa-plus-circle mr-4 text-purple-200"></i>
              Prepare MI (Material Issue)
            </h1>
            <p class="text-purple-100 text-xl font-medium">Create and manage material issue transactions</p>
            <div class="flex items-center mt-4 space-x-6">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-purple-200 mr-2"></i>
                <span class="text-sm">Period: {{ currentPeriod }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-user-circle text-purple-200 mr-2"></i>
                <span class="text-sm">User: {{ currentUser }}</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-clock text-purple-200 mr-2"></i>
                <span class="text-sm">{{ currentTime }}</span>
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              @click="saveTransaction"
              :disabled="!isFormValid || isSaving"
              class="bg-green-500 hover:bg-green-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-save mr-3" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-3" v-else></i>
              {{ isSaving ? 'Saving...' : 'Save MI' }}
            </button>
            <button
              @click="clearForm"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-eraser mr-3"></i>
              Clear
            </button>
            <button
              @click="printTransaction"
              :disabled="!form.transaction_number"
              class="bg-purple-500 hover:bg-purple-600 disabled:bg-gray-500 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              <i class="fas fa-print mr-3"></i>
              Print
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">System: Online</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">Database: Connected</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm font-medium text-gray-700">Network: Active</span>
          </div>
        </div>
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
          Ready to Process
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Form Section -->
      <div class="lg:col-span-3">
        <div class="bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-edit mr-3 text-purple-600"></i>
            Transaction Details
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Transaction Number -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Transaction Number *
              </label>
              <input
                v-model="form.transaction_number"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="Auto-generated"
                readonly
              />
            </div>

            <!-- Transaction Date -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Transaction Date *
              </label>
              <input
                v-model="form.transaction_date"
                type="date"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                required
              />
            </div>

            <!-- SKU Code -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                SKU Code *
              </label>
              <div class="relative">
                <input
                  v-model="form.sku_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  placeholder="Enter SKU code"
                  @click="showSkuModal = true"
                  readonly
                />
                <button
                  @click="showSkuModal = true"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>

            <!-- Location Code -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Location Code *
              </label>
              <div class="relative">
                <input
                  v-model="form.location_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  placeholder="Enter location code"
                  @click="showLocationModal = true"
                  readonly
                />
                <button
                  @click="showLocationModal = true"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>

            <!-- Category Code -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Category Code *
              </label>
              <div class="relative">
                <input
                  v-model="form.category_code"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  placeholder="Enter category code"
                  @click="showCategoryModal = true"
                  readonly
                />
                <button
                  @click="showCategoryModal = true"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Quantity *
              </label>
              <input
                v-model.number="form.quantity"
                type="number"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="0.00"
                required
              />
            </div>

            <!-- Unit Price -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Unit Price *
              </label>
              <input
                v-model.number="form.unit_price"
                type="number"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="0.00"
                required
              />
            </div>

            <!-- Total Amount -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Total Amount
              </label>
              <input
                :value="formattedTotalAmount"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              />
            </div>

            <!-- Reference Number -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Reference Number
              </label>
              <input
                v-model="form.reference_number"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                placeholder="Optional reference"
              />
            </div>
          </div>

          <!-- Description -->
          <div class="mt-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              Description *
            </label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              placeholder="Enter transaction description"
              required
            ></textarea>
          </div>

          <!-- Notes -->
          <div class="mt-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              Notes
            </label>
            <textarea
              v-model="form.notes"
              rows="2"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              placeholder="Optional notes"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="space-y-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history mr-2 text-purple-600"></i>
            Recent MI Transactions
          </h3>
          <div class="space-y-3">
            <div
              v-for="transaction in recentTransactions"
              :key="transaction.id"
              class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors"
              @click="loadTransaction(transaction)"
            >
              <div class="flex justify-between items-start">
                <div>
                  <div class="font-medium text-sm text-gray-800">{{ transaction.transaction_number }}</div>
                  <div class="text-xs text-gray-600">{{ transaction.description }}</div>
                </div>
                <span :class="getStatusBadgeClass(transaction.status)" class="text-xs px-2 py-1 rounded-full">
                  {{ transaction.status }}
                </span>
              </div>
              <div class="text-xs text-gray-500 mt-1">
                {{ formatCurrency(transaction.total_amount) }} | {{ transaction.formatted_transaction_date }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt mr-2 text-purple-600"></i>
            Quick Actions
          </h3>
          <div class="space-y-3">
            <button
              @click="generateTransactionNumber"
              class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center"
            >
              <i class="fas fa-magic mr-2"></i>
              Generate Number
            </button>
            <button
              @click="copyFromLastTransaction"
              class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center"
            >
              <i class="fas fa-copy mr-2"></i>
              Copy Last MI
            </button>
            <button
              @click="validateForm"
              class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center"
            >
              <i class="fas fa-check mr-2"></i>
              Validate Form
            </button>
          </div>
        </div>

        <!-- Validation Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-clipboard-check mr-2 text-purple-600"></i>
            Validation Status
          </h3>
          <div class="space-y-2">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              <span class="text-sm text-gray-700">Form validation</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              <span class="text-sm text-gray-700">Data integrity</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              <span class="text-sm text-gray-700">Business rules</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <SkuLookupModal
      v-if="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />

    <LocationTableModal
      v-if="showLocationModal"
      @close="showLocationModal = false"
      @select="selectLocation"
    />

    <CategoryTableModal
      v-if="showCategoryModal"
      @close="showCategoryModal = false"
      @select="selectCategory"
    />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import LocationTableModal from '@/Components/LocationTableModal.vue'
import CategoryTableModal from '@/Components/CategoryTableModal.vue'
import Swal from 'sweetalert2'

const toast = useToast()

// Reactive data
const currentTime = ref('')
const currentPeriod = ref('2024-01')
const currentUser = ref('Admin User')
const lastUpdated = ref('')
const isSaving = ref(false)
const showSkuModal = ref(false)
const showLocationModal = ref(false)
const showCategoryModal = ref(false)
const recentTransactions = ref([])

const form = ref({
  transaction_number: '',
  transaction_type: 'MI',
  sku_code: '',
  location_code: '',
  category_code: '',
  quantity: 0,
  unit_price: 0,
  total_amount: 0,
  description: '',
  transaction_date: new Date().toISOString().split('T')[0],
  reference_number: '',
  notes: ''
})

// Computed properties
const isFormValid = computed(() => {
  return form.value.sku_code &&
         form.value.location_code &&
         form.value.category_code &&
         form.value.quantity > 0 &&
         form.value.unit_price > 0 &&
         form.value.description &&
         form.value.transaction_date
})

const formattedTotalAmount = computed(() => {
  return formatCurrency(form.value.total_amount)
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString()
  lastUpdated.value = now.toLocaleString()
}

const generateTransactionNumber = async () => {
  try {
    const response = await fetch('/api/is-mi-mo-lt/generate-number', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ transaction_type: 'MI' })
    })

    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        form.value.transaction_number = data.transaction_number
        toast.success('Transaction number generated successfully')
      }
    }
  } catch (error) {
    toast.error('Error generating transaction number')
  }
}

const saveTransaction = async () => {
  if (!validateForm()) return

  isSaving.value = true
  try {
    const response = await fetch('/api/is-mi-mo-lt/transactions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        toast.success('MI transaction saved successfully')
        clearForm()
        loadRecentTransactions()
        
        // Show success modal
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'MI transaction has been saved successfully.',
          confirmButtonColor: '#10B981',
          confirmButtonText: 'OK'
        })
      }
    } else {
      const error = await response.json()
      toast.error(error.message || 'Error saving transaction')
    }
  } catch (error) {
    toast.error('Error saving transaction')
  } finally {
    isSaving.value = false
  }
}

const clearForm = () => {
  form.value = {
    transaction_number: '',
    transaction_type: 'MI',
    sku_code: '',
    location_code: '',
    category_code: '',
    quantity: 0,
    unit_price: 0,
    total_amount: 0,
    description: '',
    transaction_date: new Date().toISOString().split('T')[0],
    reference_number: '',
    notes: ''
  }
  toast.info('Form cleared')
}

const printTransaction = () => {
  if (!form.value.transaction_number) {
    toast.warning('Please save the transaction first')
    return
  }
  
  // Navigate to print route
  window.open(`/material-management/inventory-control/is-mi-mo-lt/print-mi?transaction=${form.value.transaction_number}`, '_blank')
}

const validateForm = () => {
  if (!isFormValid.value) {
    toast.error('Please fill in all required fields')
    return false
  }
  
  if (form.value.quantity <= 0) {
    toast.error('Quantity must be greater than 0')
    return false
  }
  
  if (form.value.unit_price <= 0) {
    toast.error('Unit price must be greater than 0')
    return false
  }
  
  toast.success('Form validation passed')
  return true
}

const copyFromLastTransaction = async () => {
  try {
    const response = await fetch('/api/is-mi-mo-lt/mi-transactions?per_page=1')
    if (response.ok) {
      const data = await response.json()
      if (data.success && data.data.data.length > 0) {
        const lastTransaction = data.data.data[0]
        form.value.sku_code = lastTransaction.sku_code
        form.value.location_code = lastTransaction.location_code
        form.value.category_code = lastTransaction.category_code
        form.value.unit_price = lastTransaction.unit_price
        form.value.description = lastTransaction.description
        toast.info('Copied from last MI transaction')
      }
    }
  } catch (error) {
    toast.error('Error copying from last transaction')
  }
}

const loadTransaction = (transaction) => {
  form.value = { ...transaction }
  toast.info('Transaction loaded')
}

const selectSku = (sku) => {
  form.value.sku_code = sku.sku
  showSkuModal.value = false
}

const selectLocation = (location) => {
  form.value.location_code = location.code
  showLocationModal.value = false
}

const selectCategory = (category) => {
  form.value.category_code = category.code
  showCategoryModal.value = false
}

const loadRecentTransactions = async () => {
  try {
    const response = await fetch('/api/is-mi-mo-lt/mi-transactions?per_page=5')
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        recentTransactions.value = data.data.data
      }
    }
  } catch (error) {
    console.error('Error loading recent transactions:', error)
  }
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Draft': return 'bg-gray-100 text-gray-800'
    case 'Posted': return 'bg-green-100 text-green-800'
    case 'Cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

// Lifecycle hooks
onMounted(() => {
  updateCurrentTime()
  const interval = setInterval(updateCurrentTime, 1000)
  window.miInterval = interval
  
  loadRecentTransactions()
  generateTransactionNumber()
})

onUnmounted(() => {
  if (window.miInterval) {
    clearInterval(window.miInterval)
  }
})

// Watch for changes to calculate total amount
watch([() => form.value.quantity, () => form.value.unit_price], () => {
  form.value.total_amount = form.value.quantity * form.value.unit_price
})
</script>
