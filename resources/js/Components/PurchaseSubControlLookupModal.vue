<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Purchase Sub-Control Table
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Search -->
        <div class="px-4 py-3 border-b border-gray-200">
          <div class="flex gap-2">
            <input
              v-model="searchQuery"
              @input="searchPurchaseSubControls"
              type="text"
              placeholder="Search by P/SC Code, Name, or Category..."
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <button
              @click="searchPurchaseSubControls"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
              Search
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="max-h-96 overflow-y-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  P/SC Code
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  P/SC Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Category
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="psc in filteredPurchaseSubControls"
                :key="psc.id"
                @click="selectPurchaseSubControl(psc)"
                :class="[
                  'cursor-pointer hover:bg-blue-50 transition-colors',
                  selectedPscId === psc.id ? 'bg-blue-100' : ''
                ]"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ psc.psc_code }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ psc.psc_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ psc.category || 'N/A' }}
                </td>
              </tr>
              <tr v-if="filteredPurchaseSubControls.length === 0">
                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                  {{ loading ? 'Loading purchase sub-controls...' : 'No purchase sub-controls found' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            @click="confirmSelection"
            :disabled="!selectedPscId"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Select
          </button>
          <button
            @click="closeModal"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  currentPscCode: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close', 'select'])

const purchaseSubControls = ref([])
const filteredPurchaseSubControls = ref([])
const searchQuery = ref('')
const selectedPscId = ref('')
const loading = ref(false)

const searchPurchaseSubControls = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/purchase-sub-controls', {
      params: { 
        search: searchQuery.value
      }
    })
    purchaseSubControls.value = response.data
    
    // Filter by search query if provided
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filteredPurchaseSubControls.value = purchaseSubControls.value.filter(psc => {
        return psc.psc_code.toLowerCase().includes(query) ||
               psc.psc_name.toLowerCase().includes(query) ||
               (psc.category && psc.category.toLowerCase().includes(query))
      })
    } else {
      filteredPurchaseSubControls.value = purchaseSubControls.value
    }
  } catch (error) {
    console.error('Error fetching purchase sub-controls:', error)
  } finally {
    loading.value = false
  }
}

const selectPurchaseSubControl = (psc) => {
  selectedPscId.value = psc.id
}

const confirmSelection = () => {
  if (selectedPscId.value) {
    const selectedPsc = filteredPurchaseSubControls.value.find(psc => psc.id === selectedPscId.value)
    emit('select', selectedPsc)
    closeModal()
  }
}

const closeModal = () => {
  emit('close')
  selectedPscId.value = ''
  searchQuery.value = ''
}

// Watch for show prop changes
watch(() => props.show, (newVal) => {
  if (newVal) {
    searchPurchaseSubControls()
    // Set selected PSC if current code is provided
    if (props.currentPscCode) {
      const currentPsc = purchaseSubControls.value.find(psc => psc.psc_code === props.currentPscCode)
      if (currentPsc) {
        selectedPscId.value = currentPsc.id
      }
    }
  }
})

onMounted(() => {
  if (props.show) {
    searchPurchaseSubControls()
  }
})
</script> 