<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-puzzle-piece mr-2 text-blue-500"></i>
          SKU Part#
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-500 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Form -->
      <div class="space-y-6">
        <!-- SKU -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU#</label>
            <input 
              type="text" 
              v-model="form.sku"
              readonly
              class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU Name</label>
            <input 
              type="text" 
              v-model="form.skuName"
              readonly
              class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
            >
          </div>
        </div>

        <!-- Action Buttons Row -->
        <div class="flex items-center justify-center space-x-2 bg-gray-50 p-3 rounded-lg">
          <button 
            @click="newSkuPart"
            class="flex items-center space-x-1 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"
          >
            <i class="fas fa-plus"></i>
            <span>New</span>
          </button>
          <button 
            @click="editSkuPart"
            :disabled="!selectedSkuPart"
            class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-edit"></i>
            <span>Edit</span>
          </button>
          <button 
            @click="deleteSkuPart"
            :disabled="!selectedSkuPart"
            class="flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-trash"></i>
            <span>Delete</span>
          </button>
          <button 
            @click="printSkuParts"
            class="flex items-center space-x-1 px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
          >
            <i class="fas fa-print"></i>
            <span>Print</span>
          </button>
        </div>

        <!-- Table -->
        <div class="border rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  No
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Part#
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                  <div class="flex justify-center items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading SKU parts...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="skuParts.length === 0">
                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                  No SKU parts defined
                </td>
              </tr>
              <tr v-for="(part, index) in skuParts" 
                  :key="part.id" 
                  :class="{'bg-blue-50': selectedSkuPart && selectedSkuPart.id === part.id}"
                  @click="selectSkuPart(part)"
                  class="hover:bg-gray-50 cursor-pointer">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ String(index + 1).padStart(2, '0') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ part.part_number }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-end space-x-3 mt-6">
        <button 
          @click="close" 
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
        >
          Exit
        </button>
      </div>
    </div>
    </div>
  </div>

    <!-- Add/Edit SKU Part Modal -->
    <Modal :show="showPartForm" @close="showPartForm = false" :max-width="'md'">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h4 class="text-lg font-bold text-gray-900">
            {{ editingPart ? 'Edit' : 'Add' }} SKU Part
          </h4>
          <button @click="showPartForm = false" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="saveSkuPart" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Part Number <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              v-model="partForm.part_number"
              required
              maxlength="100"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter part number"
            >
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button 
              type="button" 
              @click="showPartForm = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
            >
              {{ editingPart ? 'Update' : 'Add' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Modal from '@/Components/Modal.vue'


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
const skuParts = ref([])
const selectedSkuPart = ref(null)
const loading = ref(false)
const showPartForm = ref(false)
const editingPart = ref(false)

const form = ref({
  sku: '',
  skuName: ''
})

const partForm = ref({
  part_number: ''
})

// Methods
const fetchSkuParts = async () => {
  if (!props.sku.sku) return
  
  loading.value = true
  try {
    // Simulate API call - replace with actual endpoint
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // Pre-populate with existing part numbers from the SKU
    const parts = []
    if (props.sku.part_number1) {
      parts.push({ id: 1, part_number: props.sku.part_number1 })
    }
    if (props.sku.part_number2) {
      parts.push({ id: 2, part_number: props.sku.part_number2 })
    }
    if (props.sku.part_number3) {
      parts.push({ id: 3, part_number: props.sku.part_number3 })
    }
    
    skuParts.value = parts
  } catch (error) {
    console.error('Error fetching SKU parts:', error)
  } finally {
    loading.value = false
  }
}

const selectSkuPart = (part) => {
  selectedSkuPart.value = part
}

const newSkuPart = () => {
  editingPart.value = false
  partForm.value = {
    part_number: ''
  }
  showPartForm.value = true
}

const editSkuPart = () => {
  if (!selectedSkuPart.value) return
  
  editingPart.value = true
  partForm.value = { ...selectedSkuPart.value }
  showPartForm.value = true
}

const deleteSkuPart = () => {
  if (!selectedSkuPart.value) return
  
  if (confirm('Are you sure you want to delete this SKU part?')) {
    const index = skuParts.value.findIndex(p => p.id === selectedSkuPart.value.id)
    if (index !== -1) {
      skuParts.value.splice(index, 1)
      selectedSkuPart.value = null
    }
  }
}

const saveSkuPart = () => {
  if (editingPart.value) {
    const index = skuParts.value.findIndex(p => p.id === selectedSkuPart.value.id)
    if (index !== -1) {
      skuParts.value[index] = { ...partForm.value }
    }
  } else {
    const newPart = {
      id: Date.now(),
      ...partForm.value
    }
    skuParts.value.push(newPart)
  }
  
  showPartForm.value = false
  selectedSkuPart.value = null
}

const printSkuParts = () => {
  // Implement print functionality
  console.log('Print SKU parts')
}

const close = () => {
  emit('close')
  selectedSkuPart.value = null
}

// Watchers
watch(() => props.sku, (newSku) => {
  if (newSku && newSku.sku) {
    form.value.sku = newSku.sku
    form.value.skuName = newSku.sku_name
  }
}, { immediate: true })

watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchSkuParts()
  }
})

onMounted(() => {
  if (props.show) {
    fetchSkuParts()
  }
})
</script>

<style scoped>
/* Custom styling if needed */
</style>
