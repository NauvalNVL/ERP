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
          Category Table
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
                placeholder="Search categories..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="searchCategories"
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
                Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="category in filteredCategories"
                  :key="category.id"
                @click="selectCategory(category)"
                  class="hover:bg-blue-50 cursor-pointer"
                  :class="{ 'bg-blue-100': selectedCategory?.id === category.id }"
                >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ category.code }}
              </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ category.name }}
              </td>
            </tr>
          </tbody>
        </table>
          </div>

          <!-- Loading state -->
          <div v-if="loading" class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-blue-500"></i>
            <span class="ml-2 text-gray-600">Loading categories...</span>
          </div>

          <!-- Empty state -->
          <div v-if="!loading && filteredCategories.length === 0" class="text-center py-4">
            <span class="text-gray-500">No categories found</span>
          </div>
      </div>

      <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button 
          @click="confirmSelection" 
          :disabled="!selectedCategory"
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
const categories = ref([])
const selectedCategory = ref(null)
const searchQuery = ref('')
const loading = ref(false)

// Computed properties
const filteredCategories = computed(() => {
  if (!searchQuery.value) {
    return categories.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return categories.value.filter(category => 
    category.code.toLowerCase().includes(query) ||
    category.name.toLowerCase().includes(query)
  )
})

// Methods
const closeModal = () => {
  emit('close')
  resetModal()
}

const resetModal = () => {
  selectedCategory.value = null
  searchQuery.value = ''
}

const selectCategory = (category) => {
  selectedCategory.value = category
}

const confirmSelection = () => {
  if (selectedCategory.value) {
    emit('select', selectedCategory.value)
    closeModal()
  }
}

const searchCategories = () => {
  // Search is handled by computed property
}

const fetchCategories = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/categories')
    if (response.ok) {
      const data = await response.json()
      categories.value = data
    } else {
      console.error('Failed to fetch categories')
      // Fallback to mock data
      categories.value = [
        { id: 1, code: '#A1.01', name: 'BEARING' },
        { id: 2, code: '#A1.02', name: 'BEARING' },
        { id: 3, code: '#A1.03', name: 'LOCKNUT' },
        { id: 4, code: '#A1.04', name: 'PUSH BOTOM' },
        { id: 5, code: '#A1.05', name: 'FITING ANGIN' },
        { id: 6, code: '#A1.06', name: 'BEARING FILLO BLOCK' },
        { id: 7, code: '#A1.07', name: 'BEARING' },
        { id: 8, code: '#A1.08', name: 'LOCKNUT' },
        { id: 9, code: '#A1.09', name: 'PUSH BOTOM' },
        { id: 10, code: '#A1.10', name: 'FITING ANGIN' },
        { id: 11, code: '#A1.11', name: 'BEARING FILLO BLOCK' },
        { id: 12, code: '#A1.12', name: 'BEARING' },
        { id: 13, code: '#A1.13', name: 'LOCKNUT' },
        { id: 14, code: '#A1.14', name: 'PUSH BOTOM' },
        { id: 15, code: '#A1.15', name: 'FITING ANGIN' },
        { id: 16, code: '#A1.16', name: 'BEARING FILLO BLOCK' },
        { id: 17, code: '#A2.01', name: 'BEARING' },
        { id: 18, code: '#A2.02', name: 'LOCKNUT' },
        { id: 19, code: '#A2.03', name: 'PUSH BOTOM' },
        { id: 20, code: '#A2.04', name: 'FITING ANGIN' }
      ]
    }
  } catch (error) {
    console.error('Error fetching categories:', error)
    // Fallback to mock data
    categories.value = [
      { id: 1, code: '#A1.01', name: 'BEARING' },
      { id: 2, code: '#A1.02', name: 'BEARING' },
      { id: 3, code: '#A1.03', name: 'LOCKNUT' },
      { id: 4, code: '#A1.04', name: 'PUSH BOTOM' },
      { id: 5, code: '#A1.05', name: 'FITING ANGIN' },
      { id: 6, code: '#A1.06', name: 'BEARING FILLO BLOCK' },
      { id: 7, code: '#A1.07', name: 'BEARING' },
      { id: 8, code: '#A1.08', name: 'LOCKNUT' },
      { id: 9, code: '#A1.09', name: 'PUSH BOTOM' },
      { id: 10, code: '#A1.10', name: 'FITING ANGIN' },
      { id: 11, code: '#A1.11', name: 'BEARING FILLO BLOCK' },
      { id: 12, code: '#A1.12', name: 'BEARING' },
      { id: 13, code: '#A1.13', name: 'LOCKNUT' },
      { id: 14, code: '#A1.14', name: 'PUSH BOTOM' },
      { id: 15, code: '#A1.15', name: 'FITING ANGIN' },
      { id: 16, code: '#A1.16', name: 'BEARING FILLO BLOCK' },
      { id: 17, code: '#A2.01', name: 'BEARING' },
      { id: 18, code: '#A2.02', name: 'LOCKNUT' },
      { id: 19, code: '#A2.03', name: 'PUSH BOTOM' },
      { id: 20, code: '#A2.04', name: 'FITING ANGIN' }
    ]
  } finally {
    loading.value = false
  }
}

// Watch for show prop changes
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchCategories()
  }
})

// Lifecycle
onMounted(() => {
  if (props.show) {
    fetchCategories()
  }
})
</script>

<style scoped>
/* Sticky header */
thead th {
  position: sticky;
  top: 0;
  z-index: 10;
}

/* Scrollbar styling */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
