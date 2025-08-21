<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-chart-line mr-2 text-green-500"></i>
          SKU Balance & Inventory
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-500 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- SKU Info -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SKU Code</label>
            <div class="text-lg font-mono font-bold text-gray-900">{{ sku?.sku }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SKU Name</label>
            <div class="text-sm text-gray-600">{{ sku?.sku_name }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <div class="text-sm text-gray-600">{{ sku?.category_code }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
            <div class="text-sm text-gray-600">{{ sku?.uom }}</div>
          </div>
        </div>
      </div>

      <!-- Balance Overview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-box text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-blue-600">Balance on Hand</p>
              <p class="text-2xl font-bold text-blue-900">{{ formatNumber(balance.current_balance) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-lock text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-yellow-600">Reserved</p>
              <p class="text-2xl font-bold text-yellow-900">{{ formatNumber(balance.reserved_quantity) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-600">Available</p>
              <p class="text-2xl font-bold text-green-900">{{ formatNumber(balance.available_quantity) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Levels -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h4 class="text-md font-semibold text-gray-800 mb-3">Inventory Levels</h4>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Level</label>
            <div class="text-lg font-mono text-red-600">{{ formatNumber(sku?.min_level) }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
            <div class="text-lg font-mono text-orange-600">{{ formatNumber(sku?.reorder_level) }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Level</label>
            <div class="text-lg font-mono text-green-600">{{ formatNumber(sku?.max_level) }}</div>
          </div>
        </div>
      </div>

      <!-- Status Indicators -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white border rounded-lg p-4">
          <h4 class="text-md font-semibold text-gray-800 mb-3">Inventory Status</h4>
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Stock Level:</span>
              <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStockLevelClass()">
                {{ getStockLevelText() }}
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Last Updated:</span>
              <span class="text-sm text-gray-900">{{ formatDate(balance.last_updated) }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white border rounded-lg p-4">
          <h4 class="text-md font-semibold text-gray-800 mb-3">Quick Actions</h4>
          <div class="space-y-2">
            <button @click="refreshBalance" class="w-full text-left px-3 py-2 text-sm bg-blue-50 hover:bg-blue-100 rounded border">
              <i class="fas fa-sync-alt mr-2"></i> Refresh Balance
            </button>
            <button @click="viewHistory" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded border">
              <i class="fas fa-history mr-2"></i> View History
            </button>
            <button @click="adjustInventory" class="w-full text-left px-3 py-2 text-sm bg-yellow-50 hover:bg-yellow-100 rounded border">
              <i class="fas fa-edit mr-2"></i> Adjust Inventory
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-end space-x-3 mt-6">
        <button @click="close" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
          Close
        </button>
        <button @click="printBalance" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <i class="fas fa-print mr-1"></i> Print
        </button>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  sku: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close'])

// State
const balance = ref({
  sku: '',
  sku_name: '',
  current_balance: 0,
  reserved_quantity: 0,
  available_quantity: 0,
  last_updated: null
})
const loading = ref(false)

// Methods
const fetchSkuBalance = async () => {
  if (!props.sku.sku) return
  
  loading.value = true
  try {
    const response = await axios.get(`/api/material-management/skus/${props.sku.sku}/balance`)
    balance.value = response.data
  } catch (error) {
    console.error('Error fetching SKU balance:', error)
  } finally {
    loading.value = false
  }
}

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000'
  return parseFloat(value).toFixed(3)
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleString()
}

const getStockLevelClass = () => {
  const current = parseFloat(balance.value.current_balance) || 0
  const min = parseFloat(props.sku.min_level) || 0
  const reorder = parseFloat(props.sku.reorder_level) || 0
  const max = parseFloat(props.sku.max_level) || 0
  
  if (current <= min) {
    return 'bg-red-100 text-red-800'
  } else if (current <= reorder) {
    return 'bg-yellow-100 text-yellow-800'
  } else if (current >= max) {
    return 'bg-purple-100 text-purple-800'
  } else {
    return 'bg-green-100 text-green-800'
  }
}

const getStockLevelText = () => {
  const current = parseFloat(balance.value.current_balance) || 0
  const min = parseFloat(props.sku.min_level) || 0
  const reorder = parseFloat(props.sku.reorder_level) || 0
  const max = parseFloat(props.sku.max_level) || 0
  
  if (current <= min) {
    return 'Critical Low'
  } else if (current <= reorder) {
    return 'Below Reorder'
  } else if (current >= max) {
    return 'Overstock'
  } else {
    return 'Normal'
  }
}

const refreshBalance = () => {
  fetchSkuBalance()
}

const viewHistory = () => {
  // Implement inventory history view
  console.log('View inventory history for', props.sku.sku)
}

const adjustInventory = () => {
  // Implement inventory adjustment
  console.log('Adjust inventory for', props.sku.sku)
}

const printBalance = () => {
  // Implement print functionality
  window.print()
}

const close = () => {
  emit('close')
}

// Watchers
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchSkuBalance()
  }
})

onMounted(() => {
  if (props.show) {
    fetchSkuBalance()
  }
})
</script>

<style scoped>
/* Custom styling if needed */
</style>