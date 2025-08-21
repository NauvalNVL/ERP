<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-exchange-alt mr-2 text-blue-500"></i>
          Alternate Unit
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
            @click="newAlternateUnit"
            class="flex items-center space-x-1 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"
          >
            <i class="fas fa-plus"></i>
            <span>New</span>
          </button>
          <button 
            @click="editAlternateUnit"
            :disabled="!selectedAlternateUnit"
            class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-edit"></i>
            <span>Edit</span>
          </button>
          <button 
            @click="deleteAlternateUnit"
            :disabled="!selectedAlternateUnit"
            class="flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-trash"></i>
            <span>Delete</span>
          </button>
          <button 
            @click="printAlternateUnits"
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
                  Alternate Unit
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Unit Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Conversion Factor
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                  <div class="flex justify-center items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading alternate units...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="alternateUnits.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                  No alternate units defined
                </td>
              </tr>
              <tr v-for="(unit, index) in alternateUnits" 
                  :key="unit.id" 
                  :class="{'bg-blue-50': selectedAlternateUnit && selectedAlternateUnit.id === unit.id}"
                  @click="selectAlternateUnit(unit)"
                  class="hover:bg-gray-50 cursor-pointer">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ String(index + 1).padStart(2, '0') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ unit.alternate_unit }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ unit.unit_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ unit.conversion_factor }}
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

    <!-- Unit Lookup Modal -->
    <UnitTableModal 
      :show="showUnitModal"
      @close="showUnitModal = false"
      @unitSelected="handleUnitSelection"
    />

    <!-- Add/Edit Alternate Unit Modal -->
    <Modal :show="showAlternateForm" @close="showAlternateForm = false" :max-width="'md'">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h4 class="text-lg font-bold text-gray-900">
            {{ editingAlternate ? 'Edit' : 'Add' }} Alternate Unit
          </h4>
          <button @click="showAlternateForm = false" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="saveAlternateUnit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Alternate Unit <span class="text-red-500">*</span>
            </label>
            <div class="flex">
              <input 
                type="text" 
                v-model="alternateForm.alternate_unit"
                readonly
                required
                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50"
                placeholder="Select unit..."
              >
              <button 
                type="button"
                @click="showUnitModal = true"
                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-md transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Conversion Factor <span class="text-red-500">*</span>
            </label>
            <input 
              type="number" 
              v-model="alternateForm.conversion_factor"
              step="0.001"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              placeholder="0.000"
            >
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button 
              type="button" 
              @click="showAlternateForm = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
            >
              {{ editingAlternate ? 'Update' : 'Add' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Modal from '@/Components/Modal.vue'
import UnitTableModal from '@/Components/UnitTableModal.vue'

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
const alternateUnits = ref([])
const selectedAlternateUnit = ref(null)
const loading = ref(false)
const showUnitModal = ref(false)
const showAlternateForm = ref(false)
const editingAlternate = ref(false)

const form = ref({
  sku: '',
  skuName: ''
})

const alternateForm = ref({
  alternate_unit: '',
  unit_name: '',
  conversion_factor: 1.0
})

// Methods
const fetchAlternateUnits = async () => {
  if (!props.sku.sku) return
  
  loading.value = true
  try {
    // Simulate API call - replace with actual endpoint
    await new Promise(resolve => setTimeout(resolve, 500))
    alternateUnits.value = []
  } catch (error) {
    console.error('Error fetching alternate units:', error)
  } finally {
    loading.value = false
  }
}

const selectAlternateUnit = (unit) => {
  selectedAlternateUnit.value = unit
}

const newAlternateUnit = () => {
  editingAlternate.value = false
  alternateForm.value = {
    alternate_unit: '',
    unit_name: '',
    conversion_factor: 1.0
  }
  showAlternateForm.value = true
}

const editAlternateUnit = () => {
  if (!selectedAlternateUnit.value) return
  
  editingAlternate.value = true
  alternateForm.value = { ...selectedAlternateUnit.value }
  showAlternateForm.value = true
}

const deleteAlternateUnit = () => {
  if (!selectedAlternateUnit.value) return
  
  if (confirm('Are you sure you want to delete this alternate unit?')) {
    const index = alternateUnits.value.findIndex(u => u.id === selectedAlternateUnit.value.id)
    if (index !== -1) {
      alternateUnits.value.splice(index, 1)
      selectedAlternateUnit.value = null
    }
  }
}

const saveAlternateUnit = () => {
  if (editingAlternate.value) {
    const index = alternateUnits.value.findIndex(u => u.id === selectedAlternateUnit.value.id)
    if (index !== -1) {
      alternateUnits.value[index] = { ...alternateForm.value }
    }
  } else {
    const newUnit = {
      id: Date.now(),
      ...alternateForm.value
    }
    alternateUnits.value.push(newUnit)
  }
  
  showAlternateForm.value = false
  selectedAlternateUnit.value = null
}

const handleUnitSelection = (unit) => {
  alternateForm.value.alternate_unit = unit.code
  alternateForm.value.unit_name = unit.short_name
}

const printAlternateUnits = () => {
  // Implement print functionality
  console.log('Print alternate units')
}

const close = () => {
  emit('close')
  selectedAlternateUnit.value = null
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
    fetchAlternateUnits()
  }
})

onMounted(() => {
  if (props.show) {
    fetchAlternateUnits()
  }
})
</script>

<style scoped>
/* Custom styling if needed */
</style>
