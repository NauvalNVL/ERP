<template>
  <AppLayout :header="'Define Purchase Sub-Control'">
    <Head title="Define Purchase Sub-Control" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-sitemap mr-3"></i> Define Purchase Sub-Control
      </h2>
      <p class="text-green-100">Manage purchase sub-control codes and categories for detailed purchase classification</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchCode" placeholder="Search by P/SC Code or Name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newPurchaseSubControl" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New P/SC
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="mb-4 flex flex-wrap gap-4">
            <select v-model="categoryFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
            </select>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('psc_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        P/SC Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('psc_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        P/SC Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('category')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Category <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading purchase sub-controls...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPurchaseSubControls.length === 0" class="hover:bg-gray-50">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      No purchase sub-controls found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="psc in paginatedPurchaseSubControls" :key="psc.id" 
                      @click="selectPurchaseSubControl(psc)" 
                      :class="{'bg-green-50': selectedPurchaseSubControl && selectedPurchaseSubControl.id === psc.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ psc.psc_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ psc.psc_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ psc.category || 'N/A' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedPurchaseSubControls.length }} of {{ filteredPurchaseSubControls.length }} purchase sub-controls</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full lg:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedPurchaseSubControl" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-sitemap mr-2 text-green-500"></i> P/SC Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">P/SC Code:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaseSubControl.psc_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">P/SC Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedPurchaseSubControl.psc_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Category:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaseSubControl.category || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Created:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedPurchaseSubControl.created_at) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Updated:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedPurchaseSubControl.updated_at) }}</span>
              </div>
            </div>
            <div class="mt-6 space-y-2">
              <button @click="editPurchaseSubControl(selectedPurchaseSubControl)" class="w-full btn-green">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-sitemap text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No P/SC Selected</h3>
            <p class="text-gray-500 mb-4">Select a purchase sub-control from the table to view details</p>
            <button @click="newPurchaseSubControl" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New P/SC
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Purchase Sub Control Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="closeFormModal"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
          <!-- Modal Header -->
          <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              {{ isEditing ? 'Edit Purchase Sub-Control' : 'New Purchase Sub-Control' }}
            </h3>
            <button @click="closeFormModal" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="px-4 py-5 sm:p-6">
            <form @submit.prevent="savePurchaseSubControl" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700">P/SC Code *</label>
                  <input
                    v-model="formPurchaseSubControl.psc_code"
                    type="text"
                    :disabled="isEditing"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:bg-gray-100"
                    :class="{ 'border-red-500': errors.psc_code }"
                  />
                  <p v-if="errors.psc_code" class="mt-1 text-sm text-red-600">{{ errors.psc_code[0] }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">P/SC Name *</label>
                  <input
                    v-model="formPurchaseSubControl.psc_name"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.psc_name }"
                  />
                  <p v-if="errors.psc_name" class="mt-1 text-sm text-red-600">{{ errors.psc_name[0] }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Category</label>
                  <input
                    v-model="formPurchaseSubControl.category"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.category }"
                  />
                  <p v-if="errors.category" class="mt-1 text-sm text-red-600">{{ errors.category[0] }}</p>
                </div>

                </div>

              <!-- Modal Footer -->
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                  type="submit"
                  :disabled="saving"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
                  {{ saving ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                </button>
                <button
                  type="button"
                  @click="closeFormModal"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'

const toast = useToast()

// Reactive data
const purchaseSubControls = ref([])
const selectedPurchaseSubControl = ref(null)
const loading = ref(false)
const saving = ref(false)
const searchCode = ref('')
const categoryFilter = ref('')
const showFormModal = ref(false)
const isEditing = ref(false)
const errors = ref({})
const categories = ref([])
const currentPage = ref(1)
const itemsPerPage = ref(10)
const sortOrder = ref({
  field: 'psc_code',
  direction: 'asc'
})

// Form data
const formPurchaseSubControl = ref({
  psc_code: '',
  psc_name: '',
  category: ''
})

// Computed properties
const filteredPurchaseSubControls = computed(() => {
  let filtered = purchaseSubControls.value

  if (searchCode.value) {
    const query = searchCode.value.toLowerCase()
    filtered = filtered.filter(psc =>
      psc.psc_code.toLowerCase().includes(query) ||
      psc.psc_name.toLowerCase().includes(query) ||
      (psc.category && psc.category.toLowerCase().includes(query))
    )
  }

  if (categoryFilter.value) {
    filtered = filtered.filter(psc => psc.category === categoryFilter.value)
  }

  // Sort the results
  filtered = [...filtered].sort((a, b) => {
    const field = sortOrder.value.field
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1
    
    const valA = a[field] || ''
    const valB = b[field] || ''

    if (valA < valB) return -1 * direction
    if (valA > valB) return 1 * direction
    return 0
  })

  return filtered
})

const paginatedPurchaseSubControls = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredPurchaseSubControls.value.slice(start, end)
})

const totalPages = computed(() => {
  const total = filteredPurchaseSubControls.value.length
  if (total === 0) return 1
  return Math.ceil(total / itemsPerPage.value)
})

// Methods
const fetchPurchaseSubControls = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/purchase-sub-controls')
    purchaseSubControls.value = response.data
  } catch (error) {
    console.error('Error fetching purchase sub-controls:', error)
    toast.error('Failed to load purchase sub-controls')
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/purchase-sub-controls/categories')
    categories.value = response.data
  } catch (error) {
    console.error('Error fetching categories:', error)
  }
}

const refreshData = () => {
  selectedPurchaseSubControl.value = null
  searchCode.value = ''
  categoryFilter.value = ''
  currentPage.value = 1
  fetchPurchaseSubControls()
  fetchCategories()
}

const searchByCode = () => {
  // Filter is handled by computed property
  if (filteredPurchaseSubControls.value.length === 1) {
    selectedPurchaseSubControl.value = filteredPurchaseSubControls.value[0]
  }
}

const selectRecord = () => {
  if (selectedPurchaseSubControl.value) {
    toast.success(`Selected: ${selectedPurchaseSubControl.value.psc_code} - ${selectedPurchaseSubControl.value.psc_name}`)
    // You can emit an event or perform other actions here
  }
}

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortOrder.value.field = field
    sortOrder.value.direction = 'asc'
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}



const selectPurchaseSubControl = (psc) => {
  selectedPurchaseSubControl.value = psc
}

const newPurchaseSubControl = () => {
  isEditing.value = false
  formPurchaseSubControl.value = {
    psc_code: '',
    psc_name: '',
    category: ''
  }
  errors.value = {}
  showFormModal.value = true
}

const editPurchaseSubControl = (psc) => {
  isEditing.value = true
  formPurchaseSubControl.value = {
    psc_code: psc.psc_code,
    psc_name: psc.psc_name,
    category: psc.category || ''
  }
  errors.value = {}
  showFormModal.value = true
}

const savePurchaseSubControl = async () => {
  saving.value = true
  errors.value = {}

  try {
    if (isEditing.value) {
      const response = await axios.put(`/api/purchase-sub-controls/${selectedPurchaseSubControl.value.id}`, formPurchaseSubControl.value)
      toast.success('Purchase Sub-Control updated successfully')
      
      // Update the item in the list
      const index = purchaseSubControls.value.findIndex(psc => psc.id === selectedPurchaseSubControl.value.id)
      if (index !== -1) {
        purchaseSubControls.value[index] = response.data.data
        selectedPurchaseSubControl.value = response.data.data
      }
    } else {
      const response = await axios.post('/api/purchase-sub-controls', formPurchaseSubControl.value)
      toast.success('Purchase Sub-Control created successfully')
      
      // Add the new item to the list
      purchaseSubControls.value.unshift(response.data.data)
    }
    
    closeFormModal()
    fetchCategories() // Refresh categories in case a new one was added
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      toast.error('Failed to save purchase sub-control')
    }
  } finally {
    saving.value = false
  }
}





const closeFormModal = () => {
  showFormModal.value = false
  errors.value = {}
}

// Keyboard shortcuts
const handleKeydown = (event) => {
  if (event.key === 'F2' && selectedPurchaseSubControl.value) {
    event.preventDefault()
    editPurchaseSubControl(selectedPurchaseSubControl.value)
  } else if (event.key === 'Escape') {
    event.preventDefault()
    if (showFormModal.value) {
      closeFormModal()
    }
  }
}

// Watchers
watch(filteredPurchaseSubControls, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

watch(itemsPerPage, () => {
  currentPage.value = 1
})

// Lifecycle
onMounted(() => {
  fetchPurchaseSubControls()
  fetchCategories()
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}



.btn-green {
  @apply bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Form field styles */
.form-field {
  @apply bg-gray-50 p-4 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-2 flex items-center;
}

.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}
</style>
