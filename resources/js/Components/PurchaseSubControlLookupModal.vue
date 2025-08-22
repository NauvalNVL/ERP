<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Purchase Sub Control (PSC) Lookup</h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex space-x-4">
            <div class="flex-1">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search PSC by code, name, or category..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="searchPSCs"
              />
            </div>
            <button
              @click="searchPSCs"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              <i class="fas fa-search mr-2"></i>
              Search
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="max-h-96 overflow-y-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PSC Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PSC Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchaser</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="psc in filteredPSCs" :key="psc.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.psc_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.psc_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ psc.purchaser }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getStatusColor(psc.status)">
                    {{ psc.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="selectPSC(psc)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    <i class="fas fa-check mr-1"></i>
                    Select
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
              Showing {{ filteredPSCs.length }} of {{ pscs.length }} PSC records
            </div>
            <div class="flex space-x-3">
              <button
                @click="resetModal"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-undo mr-2"></i>
                Reset
              </button>
              <button
                @click="closeModal"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
              >
                <i class="fas fa-times mr-2"></i>
                Exit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

// Reactive data
const pscs = ref([])
const selectedPSC = ref(null)
const searchQuery = ref('')
const loading = ref(false)

// Computed properties
const filteredPSCs = computed(() => {
  if (!searchQuery.value) {
    return pscs.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return pscs.value.filter(psc => 
    psc.psc_code.toLowerCase().includes(query) ||
    psc.psc_name.toLowerCase().includes(query) ||
    psc.category.toLowerCase().includes(query) ||
    psc.purchaser.toLowerCase().includes(query)
  )
})

// Methods
const closeModal = () => {
  emit('close')
}

const resetModal = () => {
  searchQuery.value = ''
  selectedPSC.value = null
}

const selectPSC = (psc) => {
  selectedPSC.value = psc
  emit('select', psc)
}

const searchPSCs = () => {
  // This would typically make an API call
  // For now, we're just filtering the existing data
  console.log('Searching PSCs with query:', searchQuery.value)
}

const getStatusColor = (status) => {
  switch (status) {
    case 'Active':
      return 'text-green-600'
    case 'Inactive':
      return 'text-red-600'
    case 'Pending':
      return 'text-yellow-600'
    default:
      return 'text-gray-600'
  }
}

const fetchPSCs = async () => {
  try {
    loading.value = true
    
    // Try to fetch from API first
    const response = await fetch('/api/purchase-sub-controls')
    if (response.ok) {
      pscs.value = await response.json()
    } else {
      // Fallback to mock data
      pscs.value = [
        {
          id: 1,
          psc_code: 'PSC001',
          psc_name: 'Raw Materials Control',
          category: 'Raw Materials',
          purchaser: 'John Doe',
          status: 'Active'
        },
        {
          id: 2,
          psc_code: 'PSC002',
          psc_name: 'Packaging Materials Control',
          category: 'Packaging',
          purchaser: 'Jane Smith',
          status: 'Active'
        },
        {
          id: 3,
          psc_code: 'PSC003',
          psc_name: 'Office Supplies Control',
          category: 'Office Supplies',
          purchaser: 'Mike Johnson',
          status: 'Active'
        },
        {
          id: 4,
          psc_code: 'PSC004',
          psc_name: 'Maintenance Supplies Control',
          category: 'Maintenance',
          purchaser: 'Sarah Wilson',
          status: 'Inactive'
        },
        {
          id: 5,
          psc_code: 'PSC005',
          psc_name: 'IT Equipment Control',
          category: 'IT Equipment',
          purchaser: 'David Brown',
          status: 'Active'
        },
        {
          id: 6,
          psc_code: 'PSC006',
          psc_name: 'Safety Equipment Control',
          category: 'Safety Equipment',
          purchaser: 'Lisa Davis',
          status: 'Active'
        },
        {
          id: 7,
          psc_code: 'PSC007',
          psc_name: 'Chemical Supplies Control',
          category: 'Chemicals',
          purchaser: 'Tom Miller',
          status: 'Pending'
        },
        {
          id: 8,
          psc_code: 'PSC008',
          psc_name: 'Tooling Control',
          category: 'Tools',
          purchaser: 'Amy Garcia',
          status: 'Active'
        }
      ]
    }
  } catch (error) {
    console.error('Failed to fetch PSCs:', error)
    // Use mock data as fallback
    pscs.value = [
      {
        id: 1,
        psc_code: 'PSC001',
        psc_name: 'Raw Materials Control',
        category: 'Raw Materials',
        purchaser: 'John Doe',
        status: 'Active'
      },
      {
        id: 2,
        psc_code: 'PSC002',
        psc_name: 'Packaging Materials Control',
        category: 'Packaging',
        purchaser: 'Jane Smith',
        status: 'Active'
      },
      {
        id: 3,
        psc_code: 'PSC003',
        psc_name: 'Office Supplies Control',
        category: 'Office Supplies',
        purchaser: 'Mike Johnson',
        status: 'Active'
      },
      {
        id: 4,
        psc_code: 'PSC004',
        psc_name: 'Maintenance Supplies Control',
        category: 'Maintenance',
        purchaser: 'Sarah Wilson',
        status: 'Inactive'
      },
      {
        id: 5,
        psc_code: 'PSC005',
        psc_name: 'IT Equipment Control',
        category: 'IT Equipment',
        purchaser: 'David Brown',
        status: 'Active'
      },
      {
        id: 6,
        psc_code: 'PSC006',
        psc_name: 'Safety Equipment Control',
        category: 'Safety Equipment',
        purchaser: 'Lisa Davis',
        status: 'Active'
      },
      {
        id: 7,
        psc_code: 'PSC007',
        psc_name: 'Chemical Supplies Control',
        category: 'Chemicals',
        purchaser: 'Tom Miller',
        status: 'Pending'
      },
      {
        id: 8,
        psc_code: 'PSC008',
        psc_name: 'Tooling Control',
        category: 'Tools',
        purchaser: 'Amy Garcia',
        status: 'Active'
      }
    ]
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchPSCs()
})

watch(() => props.show, (newValue) => {
  if (newValue) {
    fetchPSCs()
  }
})
</script> 