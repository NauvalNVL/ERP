<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-cogs mr-2 text-green-600"></i>
              Product Design Screen
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
          <!-- Item Details Table -->
          <div class="mb-6">
            <h4 class="text-md font-medium text-gray-800 mb-3">Item Details</h4>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pcs</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FG Balance for {{ balanceDate }}</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(item, index) in items" :key="index" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.design }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.pcs }}</td>
                    <td class="px-4 py-3">
                      <input 
                        v-model="item.quantity" 
                        type="number"
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        @input="calculateAmount(item)"
                      >
                    </td>
                    <td class="px-4 py-3">
                      <input 
                        v-model="item.unitPrice" 
                        type="number"
                        step="0.01"
                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        @input="calculateAmount(item)"
                      >
                    </td>
                    <td class="px-4 py-3">
                      <select 
                        v-model="item.unit" 
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                      >
                        <option value="Pcs">Pcs</option>
                        <option value="Kilos">Kilos</option>
                        <option value="KG">KG</option>
                        <option value="Set">Set</option>
                        <option value="Box">Box</option>
                        <option value="Roll">Roll</option>
                        <option value="Sheet">Sheet</option>
                        <option value="Meter">Meter</option>
                        <option value="Liter">Liter</option>
                        <option value="Ton">Ton</option>
                      </select>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ formatCurrency(item.amount) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ item.fgBalance || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Totals Row -->
            <div class="bg-gray-50 px-4 py-3 border border-t-0 border-gray-200 rounded-b-lg">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <span class="text-sm font-medium text-gray-700">Total:</span>
                  <button 
                    @click="setQuantity"
                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                  >
                    Set
                  </button>
                  <input 
                    v-model="totalQuantity" 
                    type="number"
                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                  >
                  <input 
                    v-model="totalUnitPrice" 
                    type="number"
                    step="0.01"
                    class="w-24 px-2 py-1 border border-gray-300 rounded text-sm"
                  >
                  <span class="text-sm text-gray-700">IDR</span>
                  <span class="text-sm font-medium text-gray-900">{{ formatCurrency(totalAmount) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Dimensions Table -->
          <div class="mb-6">
            <h4 class="text-md font-medium text-gray-800 mb-3">Dimensions & Specifications</h4>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ext. Dimension</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int. Dimension</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gross KG</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packing</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MOQ</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(item, index) in dimensionItems" :key="index" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.extDimension }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.intDimension }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.partNumber }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ item.totalGrossKg }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.packing }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ item.moq }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Customer Service Dashboard -->
          <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-blue-800">Customer Service Dashboard Using Last SO#</span>
              <input 
                v-model="lastSONumber" 
                type="text"
                class="px-3 py-1 border border-blue-300 rounded text-sm bg-white"
                placeholder="SO Number"
              >
              <button 
                @click="openCustomerServiceDashboard"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
              >
                Customer Service Dashboard Using Last SO#
              </button>
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
            @click="saveDesign"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
          >
            Save Design
          </button>
        </div>
      </div>
    </div>

    <!-- Customer Service Dashboard Modal -->
    <CustomerServiceDashboardModal 
      :show="showCustomerServiceModal" 
      :soNumber="lastSONumber"
      @close="showCustomerServiceModal = false"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useToast } from '@/Composables/useToast'
import CustomerServiceDashboardModal from './CustomerServiceDashboardModal.vue'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  masterCard: Object,
  customer: Object,
  initialQuantity: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Data
const balanceDate = ref('08/2025')
const lastSONumber = ref('3.0620')
const totalQuantity = ref(null)
const totalUnitPrice = ref(3036.3600)
const showCustomerServiceModal = ref(false)

const items = reactive([
  {
    name: 'Main',
    design: 'B1',
    pcs: 1,
    quantity: null,
    unitPrice: 3036.3600,
    unit: 'Pcs',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 1',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 2',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 3',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 4',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 5',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 6',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 7',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 8',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  },
  {
    name: 'Fit 9',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0,
    fgBalance: null
  }
])

const dimensionItems = reactive([
  {
    name: 'Main',
    extDimension: '396 x 243 x 297',
    intDimension: '393 x 240 x 292',
    partNumber: 'BOX',
    totalGrossKg: '3.0620',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 1',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 2',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 3',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 4',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 5',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 6',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 7',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 8',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  },
  {
    name: 'Fit 9',
    extDimension: '',
    intDimension: '',
    partNumber: '',
    totalGrossKg: '',
    packing: '',
    moq: ''
  }
])

// Computed
const totalAmount = computed(() => {
  return items.reduce((sum, item) => sum + (item.amount || 0), 0)
})

const totalGrossKg = computed(() => {
  return dimensionItems.reduce((sum, item) => {
    const kg = parseFloat(item.totalGrossKg) || 0
    return sum + kg
  }, 0)
})

// Methods
const calculateAmount = (item) => {
  const quantity = parseFloat(item.quantity) || 0
  const unitPrice = parseFloat(item.unitPrice) || 0
  item.amount = quantity * unitPrice
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0)
}

const setQuantity = () => {
  console.log('setQuantity called with totalQuantity:', totalQuantity.value)
  // Apply quantity to all items that have a unit price
  items.forEach((item, index) => {
    if (item.unitPrice && item.unitPrice > 0) {
      item.quantity = totalQuantity.value
      calculateAmount(item)
      console.log(`Item ${index} (${item.name}): quantity=${item.quantity}, unitPrice=${item.unitPrice}, amount=${item.amount}`)
    }
  })
}

// Allow parent to push quantity while modal is open
const applyExternalQuantity = (qty) => {
  console.log('applyExternalQuantity called with:', qty)
  if (qty && Number(qty) > 0) {
    const numericQty = Number(qty)
    totalQuantity.value = numericQty
    setQuantity()
    console.log('Quantity applied:', numericQty, 'to items:', items.map(i => ({ name: i.name, quantity: i.quantity })))
  } else {
    // If no quantity or zero, clear the values
    totalQuantity.value = null
    items[0].quantity = null
    calculateAmount(items[0])
    console.log('Quantity cleared')
  }
}

defineExpose({ applyExternalQuantity })

const openCustomerServiceDashboard = () => {
  if (!lastSONumber.value) {
    error('Please enter SO number')
    return
  }
  
  // Open customer service dashboard modal
  showCustomerServiceModal.value = true
}

const saveDesign = () => {
  // Validate that at least main item has quantity and price
  const mainItem = items[0]
  if (!mainItem.quantity || !mainItem.unitPrice) {
    error('Please fill in quantity and unit price for main item')
    return
  }

  const designData = {
    items: items.filter(item => item.quantity && item.unitPrice),
    dimensions: dimensionItems.filter(item => item.totalGrossKg),
    totalAmount: totalAmount.value,
    totalGrossKg: totalGrossKg.value,
    lastSONumber: lastSONumber.value
  }

  emit('save', designData)
}

// Initialize
onMounted(() => {
  console.log('ProductDesignScreenModal mounted with initialQuantity:', props.initialQuantity)
  
  // Load master card data if available
  if (props.masterCard) {
    // Populate design data from master card
    items[0].design = props.masterCard.model || 'B1'
    items[0].unitPrice = props.masterCard.unit_price || 3036.3600
    console.log('Master card data loaded:', { design: items[0].design, unitPrice: items[0].unitPrice })
  }
  
  // Calculate initial amounts
  items.forEach(calculateAmount)

  // Initialize quantity from parent if provided
  if (props.initialQuantity && Number(props.initialQuantity) > 0) {
    console.log('Setting initial quantity from props:', props.initialQuantity)
    totalQuantity.value = Number(props.initialQuantity)
    setQuantity()
  } else {
    // If no initial quantity provided, set main item quantity to null
    console.log('No initial quantity provided, clearing values')
    items[0].quantity = null
    totalQuantity.value = null
  }
})

// Keep quantity in sync with parent changes
watch(() => props.initialQuantity, (newVal) => {
  console.log('Props initialQuantity changed to:', newVal)
  if (newVal && Number(newVal) > 0) {
    const numericQty = Number(newVal)
    console.log('Setting quantity from watch:', numericQty)
    totalQuantity.value = numericQty
    setQuantity()
  } else {
    // If no quantity or zero, clear the values
    console.log('Clearing quantity from watch')
    totalQuantity.value = null
    items[0].quantity = null
    calculateAmount(items[0])
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

/* Table hover effects */
tbody tr:hover {
  background-color: #f9fafb;
}

/* Input focus styles */
input:focus {
  outline: none;
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
</style>
