<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
      <!-- Modal Header -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <i class="fas fa-print text-xl text-blue-600"></i>
            <h2 class="text-lg font-semibold text-gray-800">Print Preview - Delivery Orders</h2>
          </div>
          <button 
            @click="$emit('close')"
            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <!-- Print Options -->
      <div class="p-4 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Print Format -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Print Format:</label>
            <select 
              v-model="printOptions.format"
              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="standard">Standard Format</option>
              <option value="compact">Compact Format</option>
              <option value="detailed">Detailed Format</option>
            </select>
          </div>

          <!-- Copies -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Copies:</label>
            <input 
              v-model="printOptions.copies"
              type="number"
              min="1"
              max="10"
              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>

          <!-- Print Options -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Options:</label>
            <div class="space-y-1">
              <label class="flex items-center">
                <input 
                  v-model="printOptions.includeItems"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-gray-700">Include Items</span>
              </label>
              <label class="flex items-center">
                <input 
                  v-model="printOptions.includeSignature"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-gray-700">Include Signature</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Delivery Orders List -->
      <div class="flex-1 overflow-auto max-h-96">
        <div v-if="deliveryOrders.length === 0" class="flex items-center justify-center py-8">
          <div class="text-center">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
            <p class="text-gray-600">No delivery orders to print</p>
          </div>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input 
                    type="checkbox" 
                    v-model="selectAll"
                    @change="toggleSelectAll"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  >
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  DO Number
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  DO Date
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Customer
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Vehicle#
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="deliveryOrder in deliveryOrders" 
                :key="deliveryOrder.DO_Num"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-3 text-sm">
                  <input 
                    type="checkbox" 
                    v-model="selectedOrders"
                    :value="deliveryOrder.DO_Num"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  >
                </td>
                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                  {{ deliveryOrder.DO_Num }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.DO_DMY }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.AC_Name }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  {{ deliveryOrder.DO_VHC_Num }}
                </td>
                <td class="px-4 py-3 text-sm">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusClass(deliveryOrder.Status)">
                    {{ deliveryOrder.Status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Summary -->
      <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
        <div class="flex items-center justify-between text-sm text-gray-600">
          <span>{{ selectedOrders.length }} of {{ deliveryOrders.length }} delivery orders selected</span>
          <span v-if="customer.name">Customer: {{ customer.name }}</span>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
        <button 
          @click="$emit('close')"
          class="px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="previewPrint"
          class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition-colors"
          :disabled="selectedOrders.length === 0"
        >
          <i class="fas fa-eye mr-2"></i>
          Preview
        </button>
        <button 
          @click="handlePrint"
          class="px-6 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition-colors"
          :disabled="selectedOrders.length === 0"
        >
          <i class="fas fa-print mr-2"></i>
          Print ({{ selectedOrders.length }})
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  deliveryOrders: {
    type: Array,
    default: () => []
  },
  customer: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'print'])

// Print options
const printOptions = reactive({
  format: 'standard',
  copies: 1,
  includeItems: true,
  includeSignature: true
})

// Selected orders
const selectedOrders = ref([])
const selectAll = ref(false)

// Computed properties
const allOrdersSelected = computed(() => {
  return props.deliveryOrders.length > 0 && selectedOrders.value.length === props.deliveryOrders.length
})

// Methods
const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedOrders.value = props.deliveryOrders.map(order => order.DO_Num)
  } else {
    selectedOrders.value = []
  }
}

const previewPrint = () => {
  // Open print preview in new window
  const printData = {
    orders: props.deliveryOrders.filter(order => selectedOrders.value.includes(order.DO_Num)),
    options: printOptions,
    customer: props.customer
  }
  
  console.log('Print preview data:', printData)
  // TODO: Implement actual print preview
}

const handlePrint = () => {
  const printData = {
    orders: props.deliveryOrders.filter(order => selectedOrders.value.includes(order.DO_Num)),
    options: printOptions,
    customer: props.customer
  }
  
  emit('print', printData)
}

const getStatusClass = (status) => {
  switch (status) {
    case 'Draft':
      return 'bg-gray-100 text-gray-800'
    case 'Saved':
      return 'bg-blue-100 text-blue-800'
    case 'Cancelled':
      return 'bg-red-100 text-red-800'
    case 'Completed':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

// Watch for changes in delivery orders to reset selection
watch(() => props.deliveryOrders, () => {
  selectedOrders.value = []
  selectAll.value = false
  
  // Auto-select all orders when modal opens
  if (props.deliveryOrders.length > 0) {
    selectedOrders.value = props.deliveryOrders.map(order => order.DO_Num)
    selectAll.value = true
  }
}, { immediate: true })

// Watch for changes in selected orders to update selectAll
watch(selectedOrders, () => {
  selectAll.value = allOrdersSelected.value
}, { deep: true })
</script>

<style scoped>
/* Custom styles if needed */
</style>
