<template>
  <AppLayout :header="'Input Stock-Take Data'">
    <Head title="Input Stock-Take Data" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-3 rounded-lg">
              <i class="fas fa-edit text-white text-2xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Input Stock-Take Data</h1>
              <p class="text-gray-600">Enter physical inventory count data</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Current Time</div>
            <div class="text-lg font-semibold text-gray-800">{{ currentTime }}</div>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">System: Online</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">Database: Connected</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-700">Data Entry: Ready</span>
            </div>
          </div>
          <div class="text-right">
            <span class="text-sm text-gray-500">Last Updated: {{ lastUpdated }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-edit text-blue-500 mr-2"></i>
            Stock-Take Data Entry
          </h2>
          
          <div class="space-y-4">
            <!-- SKU# Field -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU#</label>
              <div class="flex space-x-2">
                <input 
                  v-model="stockTakeData.skuCode" 
                  type="text" 
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter SKU code"
                >
                <button @click="openSkuLookup" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                  <i class="fas fa-search"></i>
                </button>
                <button @click="openSkuLookup" class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                  <i class="fas fa-list"></i>
                </button>
              </div>
            </div>

            <!-- Ref# Field -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ref#</label>
              <div class="flex space-x-2">
                <input 
                  v-model="stockTakeData.refCode" 
                  type="text" 
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter reference code"
                >
                <button @click="generateNewRef" class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                  New Ref#
                </button>
              </div>
            </div>

            <!-- SKU Details (Read-only) -->
            <div v-if="stockTakeData.skuCode" class="bg-gray-50 p-4 rounded-lg">
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="text-gray-600">SKU Name:</span>
                  <span class="font-medium ml-2">{{ stockTakeData.skuName }}</span>
                </div>
                <div>
                  <span class="text-gray-600">Category:</span>
                  <span class="font-medium ml-2">{{ stockTakeData.category }}</span>
                </div>
                <div>
                  <span class="text-gray-600">Base Unit:</span>
                  <span class="font-medium ml-2">{{ stockTakeData.baseUnit }}</span>
                </div>
                <div>
                  <span class="text-gray-600">Location:</span>
                  <span class="font-medium ml-2">{{ stockTakeData.location }}</span>
                </div>
              </div>
            </div>

            <!-- Quantity Field -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
              <div class="flex space-x-2">
                <input 
                  v-model="stockTakeData.quantity" 
                  type="number" 
                  step="0.001"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="0.000"
                >
                <button @click="openUnitSelector" class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                  <i class="fas fa-cog"></i>
                </button>
              </div>
            </div>

            <!-- Location Field -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Loc#</label>
              <div class="flex space-x-2">
                <input 
                  v-model="stockTakeData.locationCode" 
                  type="text" 
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter location code"
                >
                <button @click="openLocationLookup" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-3 mt-6">
            <button @click="reviewData" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
              Record: Review
            </button>
            <button @click="selectData" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
              Record: Select
            </button>
          </div>
        </div>

        <!-- Help Table -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-table text-purple-500 mr-2"></i>
            Stock-Take Data Help Table
          </h2>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loc</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref#</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in helpTableData" :key="index" 
                    @click="selectHelpItem(item)"
                    :class="selectedHelpIndex === index ? 'bg-blue-50' : 'hover:bg-gray-50'"
                    class="cursor-pointer">
                  <td class="px-3 py-2 text-sm text-gray-900">{{ item.sku }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ item.name }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ item.loc }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ item.ref }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Help Table Actions -->
          <div class="flex justify-end space-x-3 mt-4">
            <button @click="selectHelpItem" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
              Select
            </button>
            <button @click="closeHelpTable" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
              Exit
            </button>
          </div>
        </div>
      </div>

      <!-- SKU Lookup Modal -->
      <div v-if="showSkuLookup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-96 overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">SKU Lookup</h3>
            <button @click="closeSkuLookup" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="mb-4">
            <input 
              v-model="skuSearchTerm" 
              type="text" 
              placeholder="Search SKU..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU#</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU Name</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">UOM</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="sku in filteredSkuList" :key="sku.skuCode" 
                    @click="selectSku(sku)"
                    class="hover:bg-gray-50 cursor-pointer">
                  <td class="px-3 py-2 text-sm text-gray-900">{{ sku.skuCode }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ sku.skuName }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ sku.category }}</td>
                  <td class="px-3 py-2 text-sm text-gray-900">{{ sku.uom }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import Swal from 'sweetalert2'

const { success, error, warning } = useToast()

// Reactive data
const currentTime = ref('')
const lastUpdated = ref('')
const showSkuLookup = ref(false)
const skuSearchTerm = ref('')
const selectedHelpIndex = ref(-1)

// Stock-take data
const stockTakeData = ref({
  skuCode: '',
  refCode: '',
  skuName: '',
  category: '',
  baseUnit: '',
  location: '',
  quantity: '',
  locationCode: ''
})

// Help table data from the image
const helpTableData = ref([
  { sku: '001-A01001', name: 'ANNELING WIRE 2.8MM (KAWAT PRESS BALLER)', loc: '006', ref: '001-A01001-003' },
  { sku: '001-A02003', name: 'ARMEX BAKING SODA POWDER (25KG/BAG)', loc: '002', ref: '001-A02003-005' },
  { sku: '001-A03001', name: 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)', loc: '003', ref: '001-A03001-001' },
  { sku: '001-B01001', name: 'BORAK', loc: '001', ref: '001-B01001-002' },
  { sku: '001-B03001', name: 'BEDAK POWDER', loc: '012', ref: '001-B03001-004' },
  { sku: '001-B04001', name: 'BIO 80 CLEANER/PEMBERISH ALMUNIUM @25LTR/PAIL', loc: '001', ref: '001-B04001-001' },
  { sku: '001-C01001', name: 'COSTIK SODA', loc: '002', ref: '001-C01001-003' },
  { sku: '001-C02001', name: 'CUTTING ROLL SNN 23.08X0.71X1000 2PT', loc: '003', ref: '001-C02001-002' },
  { sku: '001-C03001', name: 'CREASING MATRIX UK.0.5X1.5 (G.TAPE)', loc: '001', ref: '001-C03001-001' },
  { sku: '001-C04001', name: 'CALCIUM HIDROCSIDA (KAPUR)', loc: '002', ref: '001-C04001-004' },
  { sku: '001-C05001', name: 'CLEANSING AGEN ECL-24 (@20KG/GLN)', loc: '003', ref: '001-C05001-002' },
  { sku: '001-D01001', name: 'DETERGEN @1600 GR', loc: '001', ref: '001-D01001-003' },
  { sku: '001-F01001', name: 'FLOCCULANT FA-40B', loc: '002', ref: '001-F01001-001' },
  { sku: '001-G01001', name: 'GREASE SPRAY WD-40', loc: '003', ref: '001-G01001-002' }
])

// Mock SKU list
const skuList = ref([
  { skuCode: '001-A01001', skuName: 'ANNELING WIRE 2.8MM (KAWAT PRESS BALLER)', category: '001', uom: 'KG' },
  { skuCode: '001-A02003', skuName: 'ARMEX BAKING SODA POWDER (25KG/BAG)', category: '001', uom: 'BAG' },
  { skuCode: '001-A03001', skuName: 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)', category: '001', uom: 'KG' },
  { skuCode: '001-B01001', skuName: 'BORAK', category: '001', uom: 'KG' },
  { skuCode: '001-B03001', skuName: 'BEDAK POWDER', category: '001', uom: 'BTL' },
  { skuCode: '007-S01327', skuName: 'SERVICE AC SERVO DRIVER TIPE DASD-S60 SPEA', category: '007', uom: 'UNIT' }
])

// Computed properties
const filteredSkuList = computed(() => {
  if (!skuSearchTerm.value) return skuList.value
  return skuList.value.filter(sku => 
    sku.skuCode.toLowerCase().includes(skuSearchTerm.value.toLowerCase()) ||
    sku.skuName.toLowerCase().includes(skuSearchTerm.value.toLowerCase())
  )
})

// Methods
const updateCurrentTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('en-US', { 
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
  lastUpdated.value = now.toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

const openSkuLookup = () => {
  showSkuLookup.value = true
  skuSearchTerm.value = ''
}

const closeSkuLookup = () => {
  showSkuLookup.value = false
}

const selectSku = (sku) => {
  stockTakeData.value.skuCode = sku.skuCode
  stockTakeData.value.skuName = sku.skuName
  stockTakeData.value.category = sku.category
  stockTakeData.value.baseUnit = sku.uom
  stockTakeData.value.location = '006' // Default location
  closeSkuLookup()
}

const generateNewRef = () => {
  if (!stockTakeData.value.skuCode) {
    warning('Please select an SKU first')
    return
  }
  
  const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '')
  stockTakeData.value.refCode = `${stockTakeData.value.skuCode}-${timestamp}`
  success('New reference generated')
}

const openLocationLookup = () => {
  // Mock location selection
  const locations = ['001', '002', '003', '006', '012']
  const randomLocation = locations[Math.floor(Math.random() * locations.length)]
  stockTakeData.value.locationCode = randomLocation
  success(`Location ${randomLocation} selected`)
}

const openUnitSelector = () => {
  // Mock unit selection
  const units = ['KG', 'PCS', 'BAG', 'BTL', 'UNIT']
  const randomUnit = units[Math.floor(Math.random() * units.length)]
  stockTakeData.value.baseUnit = randomUnit
  success(`Unit ${randomUnit} selected`)
}

const selectHelpItem = (item) => {
  if (item) {
    stockTakeData.value.skuCode = item.sku
    stockTakeData.value.skuName = item.name
    stockTakeData.value.locationCode = item.loc
    stockTakeData.value.refCode = item.ref
    stockTakeData.value.category = item.sku.split('-')[0]
    stockTakeData.value.baseUnit = 'KG' // Default unit
    stockTakeData.value.location = item.loc
    success('Item selected from help table')
  }
}

const closeHelpTable = () => {
  // This would close the help table in a real implementation
  success('Help table closed')
}

const reviewData = () => {
  if (!stockTakeData.value.skuCode || !stockTakeData.value.quantity) {
    warning('Please fill in SKU and Quantity fields')
    return
  }

  Swal.fire({
    title: 'Review Stock-Take Data',
    html: `
      <div class="text-left">
        <p><strong>SKU:</strong> ${stockTakeData.value.skuCode}</p>
        <p><strong>Name:</strong> ${stockTakeData.value.skuName}</p>
        <p><strong>Ref#:</strong> ${stockTakeData.value.refCode}</p>
        <p><strong>Quantity:</strong> ${stockTakeData.value.quantity}</p>
        <p><strong>Location:</strong> ${stockTakeData.value.locationCode}</p>
      </div>
    `,
    icon: 'info',
    confirmButtonText: 'Confirm',
    showCancelButton: true,
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      success('Stock-take data reviewed and confirmed')
    }
  })
}

const selectData = () => {
  if (!stockTakeData.value.skuCode || !stockTakeData.value.quantity) {
    warning('Please fill in SKU and Quantity fields')
    return
  }

  success('Stock-take data selected and saved')
  
  // Reset form
  stockTakeData.value = {
    skuCode: '',
    refCode: '',
    skuName: '',
    category: '',
    baseUnit: '',
    location: '',
    quantity: '',
    locationCode: ''
  }
}

// Lifecycle hooks
let timeInterval

onMounted(() => {
  updateCurrentTime()
  timeInterval = setInterval(updateCurrentTime, 1000)
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style>
