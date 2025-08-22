<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Purchaser Table
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 focus:outline-none"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="px-4 py-5 sm:p-6">
          <!-- Search -->
          <div class="mb-4">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search purchasers..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="searchPurchasers"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Purchaser ID
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Purchaser Name
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Type
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Email
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="purchaser in filteredPurchasers"
                  :key="purchaser.id"
                  @click="selectPurchaser(purchaser)"
                  class="hover:bg-blue-50 cursor-pointer"
                  :class="{ 'bg-blue-100': selectedPurchaser?.id === purchaser.id }"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ purchaser.purchaser_id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ purchaser.purchaser_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ purchaser.type }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ purchaser.email }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Loading state -->
          <div v-if="loading" class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-blue-500"></i>
            <span class="ml-2 text-gray-600">Loading purchasers...</span>
          </div>

          <!-- Empty state -->
          <div v-if="!loading && filteredPurchasers.length === 0" class="text-center py-4">
            <span class="text-gray-500">No purchasers found</span>
          </div>

          <!-- Note -->
          <div class="mt-4 text-sm text-gray-600">
            <p><strong>Note:</strong> PU - Purchaser RQ - Requestor</p>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            @click="confirmSelection"
            :disabled="!selectedPurchaser"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Select
          </button>
          <button
            @click="closeModal"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
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

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

// Reactive data
const purchasers = ref([])
const selectedPurchaser = ref(null)
const searchQuery = ref('')
const loading = ref(false)

// Computed properties
const filteredPurchasers = computed(() => {
  if (!searchQuery.value) {
    return purchasers.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return purchasers.value.filter(purchaser =>
    purchaser.purchaser_id.toLowerCase().includes(query) ||
    purchaser.purchaser_name.toLowerCase().includes(query) ||
    purchaser.type.toLowerCase().includes(query)
  )
})

// Methods
const closeModal = () => {
  emit('close')
  resetModal()
}

const resetModal = () => {
  selectedPurchaser.value = null
  searchQuery.value = ''
}

const selectPurchaser = (purchaser) => {
  selectedPurchaser.value = purchaser
}

const confirmSelection = () => {
  if (selectedPurchaser.value) {
    emit('select', selectedPurchaser.value)
    closeModal()
  }
}

const searchPurchasers = () => {
  // Search is handled by computed property
}

const fetchPurchasers = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/purchasers')
    if (response.ok) {
      const data = await response.json()
      purchasers.value = data
    } else {
      console.error('Failed to fetch purchasers')
      // Fallback to mock data
      purchasers.value = [
        {
          id: 1,
          purchaser_id: 'CHRS',
          purchaser_name: 'CHRISTINE',
          type: 'PU',
          email: 'christine@ptmbi.com'
        },
        {
          id: 2,
          purchaser_id: 'MKT',
          purchaser_name: 'INTERNAL SALES',
          type: 'PU/RQ',
          email: '00000000000000000000000000000000000000'
        },
        {
          id: 3,
          purchaser_id: 'MRSH',
          purchaser_name: 'MARSIH',
          type: 'PU',
          email: 'marsih@ptmbi.com'
        },
        {
          id: 4,
          purchaser_id: 'NKTA',
          purchaser_name: 'NIKITA',
          type: 'PU',
          email: 'ntjandra@multiindustry.com'
        },
        {
          id: 5,
          purchaser_id: 'PURST',
          purchaser_name: 'PURCHASING',
          type: 'PU',
          email: 'purst@ptmbi1.com'
        },
        {
          id: 6,
          purchaser_id: 'TRK',
          purchaser_name: 'TRUCK TRAILER',
          type: 'PU/RQ',
          email: '00000000000000000000000000000000000000'
        },
        {
          id: 7,
          purchaser_id: 'YUDA',
          purchaser_name: 'YUDA',
          type: 'PU',
          email: 'mbi.purchasing@ptmbi.com'
        },
        {
          id: 8,
          purchaser_id: 'YUFA',
          purchaser_name: 'YUFA',
          type: 'PU',
          email: 'yufa@ptmbi1.com'
        }
      ]
    }
  } catch (error) {
    console.error('Error fetching purchasers:', error)
    // Fallback to mock data
    purchasers.value = [
      {
        id: 1,
        purchaser_id: 'CHRS',
        purchaser_name: 'CHRISTINE',
        type: 'PU',
        email: 'christine@ptmbi.com'
      },
      {
        id: 2,
        purchaser_id: 'MKT',
        purchaser_name: 'INTERNAL SALES',
        type: 'PU/RQ',
        email: '00000000000000000000000000000000000000'
      },
      {
        id: 3,
        purchaser_id: 'MRSH',
        purchaser_name: 'MARSIH',
        type: 'PU',
        email: 'marsih@ptmbi.com'
      },
      {
        id: 4,
        purchaser_id: 'NKTA',
        purchaser_name: 'NIKITA',
        type: 'PU',
        email: 'ntjandra@multiindustry.com'
      },
      {
        id: 5,
        purchaser_id: 'PURST',
        purchaser_name: 'PURCHASING',
        type: 'PU',
        email: 'purst@ptmbi1.com'
      },
      {
        id: 6,
        purchaser_id: 'TRK',
        purchaser_name: 'TRUCK TRAILER',
        type: 'PU/RQ',
        email: '00000000000000000000000000000000000000'
      },
      {
        id: 7,
        purchaser_id: 'YUDA',
        purchaser_name: 'YUDA',
        type: 'PU',
        email: 'mbi.purchasing@ptmbi.com'
      },
      {
        id: 8,
        purchaser_id: 'YUFA',
        purchaser_name: 'YUFA',
        type: 'PU',
        email: 'yufa@ptmbi1.com'
      }
    ]
  } finally {
    loading.value = false
  }
}

// Watch for show prop changes
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchPurchasers()
  }
})

// Lifecycle
onMounted(() => {
  if (props.show) {
    fetchPurchasers()
  }
})
</script>
