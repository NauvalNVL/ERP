<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-green-600 to-emerald-600">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-cogs mr-2 text-white"></i>
              Product Design Screen
            </h3>
            <button
              @click="$emit('close')"
              class="text-white hover:text-gray-200 transition-colors"
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
                        :readonly="props.readOnlyQuantity"
                        :disabled="props.readOnlyQuantity"
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 disabled:bg-gray-100 disabled:text-gray-500"
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
                    :disabled="props.readOnlyQuantity"
                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors disabled:bg-gray-300 disabled:text-gray-600 disabled:cursor-not-allowed"
                  >
                    Set
                  </button>
                  <input
                    v-model="totalQuantity"
                    type="number"
                    :readonly="props.readOnlyQuantity"
                    :disabled="props.readOnlyQuantity"
                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm disabled:bg-gray-100 disabled:text-gray-500"
                    @input="updateMainQuantity"
                  >
                  <input
                    v-model="totalUnitPrice"
                    type="number"
                    step="0.01"
                    class="w-24 px-2 py-1 border border-gray-300 rounded text-sm"
                    @input="updateMainUnitPrice"
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
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
          <button
            @click="$emit('close')"
            class="px-4 py-2 text-sm font-medium text-blue-700 border border-blue-500 rounded-md bg-white hover:bg-blue-50 hover:border-blue-600 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="saveDesign"
            class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm"
          >
            Save Design
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useToast } from '@/Composables/useToast'

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
  },
  mcComponents: {
    type: Array,
    default: () => []
  },
  // When true, quantities are driven by Set Quantity in Order Details
  // and all quantity inputs in this modal should be read-only
  readOnlyQuantity: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'save'])

const { success, error } = useToast()

// Data
const totalQuantity = ref(null)
const totalUnitPrice = ref(3036.3600)

const items = reactive([
  {
    name: 'Main',
    design: 'B1',
    pcs: 1,
    quantity: null,
    unitPrice: 3036.3600,
    unit: 'Pcs',
    amount: 0
  },
  {
    name: 'Fit 1',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 2',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 3',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 4',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 5',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 6',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 7',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 8',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
  },
  {
    name: 'Fit 9',
    design: '',
    pcs: '',
    quantity: null,
    unitPrice: null,
    unit: '',
    amount: 0
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

// Helper: hydrate items and dimensions from MC components (Main, Fit1-9)
const hydrateFromMcComponents = () => {
  try {
    const comps = Array.isArray(props.mcComponents) ? props.mcComponents : []
    if (!comps.length) {
      console.log('No mcComponents provided to ProductDesignScreenModal, skipping hydrate')
      return
    }

    const desiredLabels = ['Main', 'Fit1', 'Fit2', 'Fit3', 'Fit4', 'Fit5', 'Fit6', 'Fit7', 'Fit8', 'Fit9']

    comps.forEach((comp) => {
      const rawLabel = (comp.c_num || comp.comp_no || comp.COMP || '').toString()
      if (!rawLabel) return

      const idx = desiredLabels.indexOf(rawLabel)
      if (idx === -1) return

      const itemRow = items[idx]
      const dimRow = dimensionItems[idx]
      if (!itemRow || !dimRow) return

      const nameLabel = idx === 0 ? 'Main' : `Fit ${idx}`
      itemRow.name = nameLabel
      dimRow.name = nameLabel

      // P/Design
      itemRow.design = comp.pd || comp.p_design || itemRow.design

      // PCS/SET
      const pcsRaw = comp.pcs_set ?? comp.pcsPerSet ?? comp.pcs
      if (pcsRaw !== undefined && pcsRaw !== null && pcsRaw !== '') {
        const pcsNum = parseFloat(pcsRaw)
        itemRow.pcs = isNaN(pcsNum) ? pcsRaw : pcsNum
      }

      // Part#
      dimRow.partNumber = comp.part_no || comp.part_num || dimRow.partNumber

      // Dimensions from id/ed structure or fallback ext_dim/int_dim style fields
      const id = comp.id || {}
      const ed = comp.ed || {}

      const extL = ed.L ?? comp.ext_dim_1
      const extW = ed.W ?? comp.ext_dim_2
      const extH = ed.H ?? comp.ext_dim_3
      const intL = id.L ?? comp.int_dim_1
      const intW = id.W ?? comp.int_dim_2
      const intH = id.H ?? comp.int_dim_3

      const extParts = [extL, extW, extH].filter(v => v !== undefined && v !== null && v !== '')
      const intParts = [intL, intW, intH].filter(v => v !== undefined && v !== null && v !== '')

      if (extParts.length) {
        dimRow.extDimension = extParts.join(' x ')
      }
      if (intParts.length) {
        dimRow.intDimension = intParts.join(' x ')
      }
    })

    console.log('✅ ProductDesignScreenModal hydrated from mcComponents:', {
      items: items.map(i => ({ name: i.name, design: i.design, pcs: i.pcs })),
      dimensionItems: dimensionItems.map(d => ({ name: d.name, ext: d.extDimension, int: d.intDimension, part: d.partNumber }))
    })
  } catch (e) {
    console.error('Error hydrating ProductDesignScreenModal from mcComponents:', e)
  }
}

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

  // Update total text boxes when Main item changes
  if (item.name === 'Main') {
    // Update totalQuantity when Main item quantity changes
    if (quantity > 0) {
      totalQuantity.value = quantity
    }

    // Update totalUnitPrice when Main item unit price changes
    if (unitPrice > 0) {
      totalUnitPrice.value = unitPrice
    }
  }
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0)
}

// Helper: apply a quantity to Main only (used when user clicks Set in this modal)
const setQuantity = () => {
  console.log('setQuantity called with totalQuantity (Main only):', totalQuantity.value)
  const quantity = parseFloat(totalQuantity.value)
  if (isNaN(quantity) || quantity <= 0) {
    return
  }

  // Only update Main row when user interacts inside Product Design screen
  items[0].quantity = quantity
  calculateAmount(items[0])
  console.log(`Main item updated via setQuantity: quantity=${items[0].quantity}, unitPrice=${items[0].unitPrice}, amount=${items[0].amount}`)
}

// Helper: apply a quantity to Main and all Fit components that have MC data
// Used only when quantity is pushed from parent (Order Details setQuantity / initialQuantity)
const applyQuantityToAllComponents = (qty) => {
  const numericQty = Number(qty)
  if (!numericQty || numericQty <= 0) {
    return
  }

  console.log('applyQuantityToAllComponents called with:', numericQty)

  items.forEach((item, index) => {
    // Always apply to Main (index 0)
    if (index === 0) {
      item.quantity = numericQty
      calculateAmount(item)
      console.log(`Main item set from external quantity: quantity=${item.quantity}, unitPrice=${item.unitPrice}, amount=${item.amount}`)
      return
    }

    // For Fit rows, only apply if there is MC data (design/pcs/partNumber filled)
    const dimRow = dimensionItems[index]
    const hasComponentData = !!item.design || !!item.pcs || !!(dimRow && dimRow.partNumber)
    if (!hasComponentData) {
      return
    }

    item.quantity = numericQty
    calculateAmount(item)
    console.log(`Fit item ${index} (${item.name}) set from external quantity: quantity=${item.quantity}, unitPrice=${item.unitPrice}, amount=${item.amount}`)
  })
}

// Update Main item quantity when total quantity changes
const updateMainQuantity = () => {
  const quantity = parseFloat(totalQuantity.value)
  if (!isNaN(quantity) && quantity >= 0) {
    items[0].quantity = quantity
    calculateAmount(items[0])
  }
}

// Update Main item unit price when total unit price changes
const updateMainUnitPrice = () => {
  const unitPrice = parseFloat(totalUnitPrice.value)
  if (!isNaN(unitPrice) && unitPrice >= 0) {
    items[0].unitPrice = unitPrice
    calculateAmount(items[0])
  }
}

// Allow parent to push quantity while modal is open
const applyExternalQuantity = (qty) => {
  console.log('applyExternalQuantity called with:', qty)
  if (qty && Number(qty) > 0) {
    const numericQty = Number(qty)
    totalQuantity.value = numericQty
    applyQuantityToAllComponents(numericQty)
    console.log('External quantity applied to all components:', numericQty, 'items:', items.map(i => ({ name: i.name, quantity: i.quantity })))
  } else {
    // If no quantity or zero, clear the values
    totalQuantity.value = null
    items.forEach((item) => {
      item.quantity = null
      item.amount = 0
    })
    console.log('Quantity cleared')
  }
}

const resetModalState = () => {
  totalQuantity.value = null
  items.forEach((item) => {
    item.quantity = null
    item.amount = 0
  })
}

defineExpose({ applyExternalQuantity, resetModalState })

const saveDesign = () => {
  // Validate that at least main item has quantity and price
  const mainItem = items[0]
  if (!mainItem.quantity || !mainItem.unitPrice) {
    error('Please fill in quantity and unit price for main item')
    return
  }

  const designData = {
    // Include all items that have a quantity so Delivery Schedule can see Main + Fit quantities
    items: items.filter(item => item.quantity),
    dimensions: dimensionItems.filter(item => item.totalGrossKg),
    totalAmount: totalAmount.value,
    totalGrossKg: totalGrossKg.value
  }

  emit('save', designData)
}

// Initialize
onMounted(() => {
  console.log('ProductDesignScreenModal mounted with initialQuantity:', props.initialQuantity)

  // Hydrate rows from MC components (Main, Fit1-9) when available
  hydrateFromMcComponents()

  // Load master card data if available (fallback / default for Main)
  if (props.masterCard) {
    // Populate design data from master card if Main row design is still empty
    if (!items[0].design) {
      items[0].design = props.masterCard.model || 'B1'
    }
    if (!items[0].unitPrice) {
      items[0].unitPrice = props.masterCard.unit_price || 3036.3600
    }
    console.log('Master card data loaded:', { design: items[0].design, unitPrice: items[0].unitPrice })
  }

  // Calculate initial amounts
  items.forEach(calculateAmount)

  // Initialize quantity from parent if provided
  if (props.initialQuantity && Number(props.initialQuantity) > 0) {
    console.log('Setting initial quantity from props:', props.initialQuantity)
    totalQuantity.value = Number(props.initialQuantity)
    applyQuantityToAllComponents(totalQuantity.value)
  } else {
    // If no initial quantity provided, set main item quantity to null
    console.log('No initial quantity provided, clearing values')
    items[0].quantity = null
    totalQuantity.value = null
  }
})

// Re-hydrate when MC components change (e.g. after async fetch in parent)
watch(() => props.mcComponents, (newVal) => {
  console.log('mcComponents changed in ProductDesignScreenModal:', newVal)
  hydrateFromMcComponents()
}, { deep: true })

// Keep quantity in sync with parent changes
watch(() => props.initialQuantity, (newVal) => {
  console.log('Props initialQuantity changed to:', newVal)
  if (newVal && Number(newVal) > 0) {
    const numericQty = Number(newVal)
    console.log('Setting quantity from watch:', numericQty)
    totalQuantity.value = numericQty
    applyQuantityToAllComponents(numericQty)
  } else {
    // If no quantity or zero, clear the values
    console.log('Clearing quantity from watch')
    totalQuantity.value = null
    items[0].quantity = null
    calculateAmount(items[0])
  }
})

// Apply quantity when modal is opened
watch(() => props.show, (newVal) => {
  if (newVal && props.initialQuantity && Number(props.initialQuantity) > 0) {
    console.log('Modal opened, applying initial quantity:', props.initialQuantity)
    const numericQty = Number(props.initialQuantity)
    totalQuantity.value = numericQty
    applyQuantityToAllComponents(numericQty)
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
