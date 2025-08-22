<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print PO Arrival Schedule
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Main Content -->
          <div class="p-6">
            <!-- Filter Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
              <!-- Current PO Period -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Current PO Period:
                </label>
                <div class="flex space-x-2">
                  <input
                    v-model="filters.currentPoPeriod.month"
                    type="text"
                    class="w-16 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="MM"
                    maxlength="2"
                  />
                  <input
                    v-model="filters.currentPoPeriod.year"
                    type="text"
                    class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="YYYY"
                    maxlength="4"
                  />
                </div>
              </div>

              <!-- ETA -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  ETA:
                </label>
                <div class="flex space-x-2">
                  <div class="flex-1">
                    <input
                      v-model="filters.eta.from"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                  <div class="flex-1">
                    <input
                      v-model="filters.eta.to"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                </div>
              </div>

              <!-- Category -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Category:
                </label>
                <div class="flex space-x-2">
                  <input
                    v-model="filters.category"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter category"
                    readonly
                  />
                  <button
                    @click="openCategoryModal"
                    type="button"
                    class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <!-- SKU# -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  SKU#:
                </label>
                <div class="flex space-x-2">
                  <input
                    v-model="filters.sku"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter SKU"
                    readonly
                  />
                  <button
                    @click="openSkuModal"
                    type="button"
                    class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <!-- Purchaser -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Purchaser:
                </label>
                <div class="flex space-x-2">
                  <input
                    v-model="filters.purchaser"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter purchaser"
                    readonly
                  />
                  <button
                    @click="openPurchaserModal"
                    type="button"
                    class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <!-- AP AC# -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  AP AC#:
                </label>
                <div class="flex space-x-2">
                  <input
                    v-model="filters.apAcNumber"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter AP AC#"
                    readonly
                  />
                  <button
                    @click="openVendorModal"
                    type="button"
                    class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Checkbox Groups -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
              <!-- PO Status -->
              <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">
                  PO Status:
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="filters.poStatus"
                      type="checkbox"
                      value="O"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">O-Outstanding</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poStatus"
                      type="checkbox"
                      value="P"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">P-Partial Completed</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poStatus"
                      type="checkbox"
                      value="C"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">C-Completed</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poStatus"
                      type="checkbox"
                      value="M"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">M-Closed Manually</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poStatus"
                      type="checkbox"
                      value="X"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">X-Cancelled</span>
                  </label>
                </div>
              </div>

              <!-- POA Status -->
              <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">
                  POA Status:
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="filters.poaStatus"
                      type="checkbox"
                      value="P"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">P-Pending</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poaStatus"
                      type="checkbox"
                      value="A"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">A-Approved</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="filters.poaStatus"
                      type="checkbox"
                      value="R"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">R-Rejected</span>
                  </label>
                </div>
              </div>

              <!-- Radio Button Groups -->
              <div class="space-y-6">
                <!-- Sorting Method -->
                <div class="space-y-3">
                  <label class="block text-sm font-medium text-gray-700">
                    Sorting Method:
                  </label>
                  <div class="space-y-2">
                    <label class="flex items-center">
                      <input
                        v-model="filters.sortingMethod"
                        type="radio"
                        value="1"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">1-ETA + SKU#</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        v-model="filters.sortingMethod"
                        type="radio"
                        value="2"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">2-SKU# + ETA</span>
                    </label>
                  </div>
                </div>

                <!-- Due Option -->
                <div class="space-y-3">
                  <label class="block text-sm font-medium text-gray-700">
                    Due Option:
                  </label>
                  <div class="space-y-2">
                    <label class="flex items-center">
                      <input
                        v-model="filters.dueOption"
                        type="radio"
                        value="Y"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">Y-Show All</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        v-model="filters.dueOption"
                        type="radio"
                        value="N"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">N-With Due Qty only</span>
                    </label>
                  </div>
                </div>

                <!-- Print SKU Name -->
                <div class="space-y-3">
                  <label class="block text-sm font-medium text-gray-700">
                    Print SKU Name:
                  </label>
                  <div class="space-y-2">
                    <label class="flex items-center">
                      <input
                        v-model="filters.printSkuName"
                        type="radio"
                        value="Y"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">Y-SKU Name + SKU Additional Name + PO SKU Open Line</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        v-model="filters.printSkuName"
                        type="radio"
                        value="N"
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span class="ml-2 text-sm text-gray-700">N-Only SKU Name</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Note and Action Button -->
            <div class="flex justify-between items-center">
              <div class="text-red-600 text-sm font-medium">
                Note: Print to Excel only.
              </div>
              <button
                @click="generateReport"
                :disabled="loading"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="loading">Generating...</span>
                <span v-else>Proceed</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Table Modal -->
    <CategoryTableModal
      :show="showCategoryModal"
      @close="closeCategoryModal"
      @select="selectCategory"
    />

    <!-- SKU Lookup Modal -->
    <SkuLookupModal
      :show="showSkuModal"
      @close="closeSkuModal"
      @select="selectSku"
    />

    <!-- Purchaser Table Modal -->
    <PurchaserTableModal
      :show="showPurchaserModal"
      @close="closePurchaserModal"
      @select="selectPurchaser"
    />

    <!-- Vendor Profile Modal -->
    <VendorProfileModal
      :show="showVendorModal"
      @close="closeVendorModal"
      @select="selectVendor"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryTableModal from '@/Components/CategoryTableModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import PurchaserTableModal from '@/Components/PurchaserTableModal.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Modal states
const showCategoryModal = ref(false)
const showSkuModal = ref(false)
const showPurchaserModal = ref(false)
const showVendorModal = ref(false)

// Loading state
const loading = ref(false)

// Filter form data
const filters = reactive({
  currentPoPeriod: {
    month: '6',
    year: '2025'
  },
  eta: {
    from: '',
    to: ''
  },
  category: '',
  sku: '',
  purchaser: '',
  apAcNumber: '',
  poStatus: ['O', 'P', 'C', 'M', 'X'], // Default all checked
  poaStatus: ['P', 'A', 'R'], // Default all checked
  sortingMethod: '1', // Default to ETA + SKU#
  dueOption: 'Y', // Default to Show All
  printSkuName: 'N' // Default to Only SKU Name
})

// Modal functions
const openCategoryModal = () => {
  showCategoryModal.value = true
}

const closeCategoryModal = () => {
  showCategoryModal.value = false
}

const selectCategory = (category) => {
  filters.category = category.code
  closeCategoryModal()
}

const openSkuModal = () => {
  showSkuModal.value = true
}

const closeSkuModal = () => {
  showSkuModal.value = false
}

const selectSku = (sku) => {
  filters.sku = sku.sku_number
  closeSkuModal()
}

const openPurchaserModal = () => {
  showPurchaserModal.value = true
}

const closePurchaserModal = () => {
  showPurchaserModal.value = false
}

const selectPurchaser = (purchaser) => {
  filters.purchaser = purchaser.purchaser_id
  closePurchaserModal()
}

const openVendorModal = () => {
  showVendorModal.value = true
}

const closeVendorModal = () => {
  showVendorModal.value = false
}

const selectVendor = (vendor) => {
  filters.apAcNumber = vendor.ap_ac_number
  closeVendorModal()
}

// Generate report function
const generateReport = async () => {
  loading.value = true
  
  try {
    // Validate required fields
    if (!filters.currentPoPeriod.month || !filters.currentPoPeriod.year) {
      error('Current PO Period is required')
      return
    }

    // Prepare filter data
    const reportData = {
      currentPoPeriod: `${filters.currentPoPeriod.month}/${filters.currentPoPeriod.year}`,
      eta: filters.eta,
      category: filters.category,
      sku: filters.sku,
      purchaser: filters.purchaser,
      apAcNumber: filters.apAcNumber,
      poStatus: filters.poStatus,
      poaStatus: filters.poaStatus,
      sortingMethod: filters.sortingMethod,
      dueOption: filters.dueOption,
      printSkuName: filters.printSkuName
    }

    // Call API to generate report
    const response = await fetch('/api/po-arrival-schedule/generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(reportData)
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `PO_Arrival_Schedule_${filters.currentPoPeriod.month}_${filters.currentPoPeriod.year}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      success('Report generated successfully')
    } else {
      const errorData = await response.json()
      error(errorData.message || 'Failed to generate report')
    }
  } catch (err) {
    console.error('Error generating report:', err)
    error('Failed to generate report')
  } finally {
    loading.value = false
  }
}

// Initialize default values
onMounted(() => {
  // Set default ETA to current month
  const now = new Date()
  const firstDay = new Date(now.getFullYear(), now.getMonth(), 1)
  const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0)
  
  filters.eta.from = firstDay.toISOString().split('T')[0]
  filters.eta.to = lastDay.toISOString().split('T')[0]
})
</script>
