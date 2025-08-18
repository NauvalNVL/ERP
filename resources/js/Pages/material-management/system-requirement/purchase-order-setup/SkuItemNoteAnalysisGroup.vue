<template>
  <AppLayout :header="'Define SKU Item Note Analysis Group'">
    <Head title="Define SKU Item Note Analysis Group" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-tags mr-3"></i> Define SKU Item Note Analysis Group
      </h2>
      <p class="text-blue-100">Create and manage analysis groups for categorizing SKU item notes and observations</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by group code, name, category, or description..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newAnalysisGroup" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Group
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="showViewPrint" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> View & Print
              </button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="mb-4 flex flex-wrap gap-4">
            <select v-model="categoryFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
            </select>
            <select v-model="statusFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Status</option>
              <option value="true">Active</option>
              <option value="false">Inactive</option>
            </select>
          </div>

          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
              <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg">
                  <i class="fas fa-layer-group text-white"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-blue-600">Total Groups</p>
                  <p class="text-lg font-semibold text-blue-900">{{ totalGroups }}</p>
                </div>
              </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <div class="flex items-center">
                <div class="p-2 bg-green-500 rounded-lg">
                  <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-600">Active Groups</p>
                  <p class="text-lg font-semibold text-green-900">{{ activeGroups }}</p>
                </div>
              </div>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
              <div class="flex items-center">
                <div class="p-2 bg-orange-500 rounded-lg">
                  <i class="fas fa-pause-circle text-white"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-orange-600">Inactive Groups</p>
                  <p class="text-lg font-semibold text-orange-900">{{ inactiveGroups }}</p>
                </div>
              </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
              <div class="flex items-center">
                <div class="p-2 bg-purple-500 rounded-lg">
                  <i class="fas fa-folder text-white"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-purple-600">Categories</p>
                  <p class="text-lg font-semibold text-purple-900">{{ categories.length }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('group_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Group Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('group_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Group Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('category')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Category <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading analysis groups...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedAnalysisGroups.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      No analysis groups found. Try adjusting your search or create a new group.
                    </td>
                  </tr>
                  <tr v-for="group in paginatedAnalysisGroups" :key="group.id" 
                      @click="selectAnalysisGroup(group)" 
                      :class="{'bg-blue-50': selectedAnalysisGroup && selectedAnalysisGroup.id === group.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ group.group_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ group.group_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <span class="px-2 py-1 text-xs font-medium rounded-full"
                            :class="getCategoryBadgeClass(group.category)">
                        {{ group.category || 'No Category' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                      {{ group.description || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <span :class="group.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            class="px-2 py-1 text-xs font-medium rounded-full">
                        {{ group.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click.stop="editAnalysisGroup(group)" 
                                class="text-blue-600 hover:text-blue-900 transition-colors">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click.stop="toggleStatus(group)" 
                                :class="group.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                                class="transition-colors">
                          <i :class="group.is_active ? 'fas fa-pause' : 'fas fa-play'"></i>
                        </button>
                        <button @click.stop="deleteAnalysisGroup(group)" 
                                class="text-red-600 hover:text-red-900 transition-colors">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex items-center justify-between mt-4" v-if="filteredAnalysisGroups.length > itemsPerPage">
            <div class="text-sm text-gray-700">
              Showing {{ ((currentPage - 1) * itemsPerPage) + 1 }} to {{ Math.min(currentPage * itemsPerPage, filteredAnalysisGroups.length) }} of {{ filteredAnalysisGroups.length }} results
            </div>
            <div class="flex space-x-1">
              <button @click="currentPage--" :disabled="currentPage === 1" 
                      class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                Previous
              </button>
              <button v-for="page in totalPages" :key="page" @click="currentPage = page"
                      :class="page === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                      class="px-3 py-1 border rounded">
                {{ page }}
              </button>
              <button @click="currentPage++" :disabled="currentPage === totalPages" 
                      class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                Next
              </button>
            </div>
          </div>
        </div>

        <!-- Details Panel -->
        <div class="w-full lg:w-80 bg-gray-50 p-4 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Group Details
          </h3>
          
          <div v-if="selectedAnalysisGroup" class="space-y-4">
            <div>
              <label class="text-sm font-medium text-gray-600">Group Code</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border">{{ selectedAnalysisGroup.group_code }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Group Name</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border">{{ selectedAnalysisGroup.group_name }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Category</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border">{{ selectedAnalysisGroup.category || 'No Category' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Description</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border min-h-[60px]">{{ selectedAnalysisGroup.description || 'No description available' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Status</label>
              <p class="text-sm bg-white p-2 rounded border">
                <span :class="selectedAnalysisGroup.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ selectedAnalysisGroup.is_active ? 'Active' : 'Inactive' }}
                </span>
              </p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Created</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border">{{ formatDate(selectedAnalysisGroup.created_at) }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600">Last Updated</label>
              <p class="text-sm text-gray-900 bg-white p-2 rounded border">{{ formatDate(selectedAnalysisGroup.updated_at) }}</p>
            </div>
          </div>
          
          <div v-else class="text-center text-gray-500 py-8">
            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
            <p>Select a group to view details</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Analysis Group Form Modal -->
    <Modal :show="showModal" @close="closeModal" max-width="lg">
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ isEditing ? 'Edit Analysis Group' : 'New Analysis Group' }}
          </h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="saveAnalysisGroup" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Group Code *</label>
              <input type="text" v-model="form.group_code" 
                     :class="{'border-red-500': errors.group_code}"
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                     placeholder="e.g., QC-001" maxlength="20" required>
              <p v-if="errors.group_code" class="text-red-500 text-xs mt-1">{{ errors.group_code }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <input type="text" v-model="form.category" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                     placeholder="e.g., Quality Control" maxlength="50">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Group Name *</label>
            <input type="text" v-model="form.group_name" 
                   :class="{'border-red-500': errors.group_name}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="e.g., Quality Issue Notes" maxlength="100" required>
            <p v-if="errors.group_name" class="text-red-500 text-xs mt-1">{{ errors.group_name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea v-model="form.description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Describe the purpose and usage of this analysis group..."></textarea>
          </div>

          <div class="flex items-center">
            <input type="checkbox" v-model="form.is_active" id="is_active" class="mr-2">
            <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
          </div>

          <div class="flex justify-end space-x-3 pt-4 border-t">
            <button type="button" @click="closeModal" class="btn-secondary">
              Cancel
            </button>
            <button type="submit" :disabled="saving" class="btn-primary">
              <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
              {{ saving ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </Modal>

    <!-- View & Print Modal -->
    <Modal :show="showViewPrintModal" @close="closeViewPrintModal" max-width="4xl">
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">View & Print Analysis Groups</h3>
          <button @click="closeViewPrintModal" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Print Filters -->
        <div class="mb-4 flex gap-4">
          <select v-model="printFilters.category" class="border border-gray-300 rounded px-3 py-1">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
          </select>
          <select v-model="printFilters.status" class="border border-gray-300 rounded px-3 py-1">
            <option value="">All Status</option>
            <option value="active">Active Only</option>
            <option value="inactive">Inactive Only</option>
          </select>
          <button @click="loadPrintData" class="btn-secondary">
            <i class="fas fa-filter mr-2"></i> Apply Filters
          </button>
          <button @click="printReport" class="btn-primary">
            <i class="fas fa-print mr-2"></i> Print
          </button>
        </div>

        <!-- Print Preview -->
        <div id="printArea" class="border border-gray-200 p-4 bg-white max-h-96 overflow-y-auto">
          <div class="text-center mb-4">
            <h2 class="text-xl font-bold">SKU Item Note Analysis Groups Report</h2>
            <p class="text-sm text-gray-600">Generated on {{ new Date().toLocaleDateString() }}</p>
          </div>
          
          <table class="w-full text-sm border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-100">
                <th class="border border-gray-300 px-2 py-1 text-left">Group Code</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Group Name</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Category</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Description</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="group in printData" :key="group.id">
                <td class="border border-gray-300 px-2 py-1">{{ group.group_code }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ group.group_name }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ group.category || '-' }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ group.description || '-' }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ group.is_active ? 'Active' : 'Inactive' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Modal from '@/Components/Modal.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const analysisGroups = ref([])
const categories = ref([])
const loading = ref(false)
const saving = ref(false)
const searchQuery = ref('')
const categoryFilter = ref('')
const statusFilter = ref('')
const selectedAnalysisGroup = ref(null)
const currentPage = ref(1)
const itemsPerPage = ref(10)
const sortField = ref('group_code')
const sortDirection = ref('asc')

// Modal states
const showModal = ref(false)
const showViewPrintModal = ref(false)
const isEditing = ref(false)

// Form data
const form = ref({
  group_code: '',
  group_name: '',
  description: '',
  category: '',
  is_active: true
})

const errors = ref({})

// Print data
const printData = ref([])
const printFilters = ref({
  category: '',
  status: ''
})

// Computed properties
const filteredAnalysisGroups = computed(() => {
  let filtered = analysisGroups.value

  if (searchQuery.value) {
    const search = searchQuery.value.toLowerCase()
    filtered = filtered.filter(group => 
      group.group_code.toLowerCase().includes(search) ||
      group.group_name.toLowerCase().includes(search) ||
      (group.category && group.category.toLowerCase().includes(search)) ||
      (group.description && group.description.toLowerCase().includes(search))
    )
  }

  if (categoryFilter.value) {
    filtered = filtered.filter(group => group.category === categoryFilter.value)
  }

  if (statusFilter.value !== '') {
    const isActive = statusFilter.value === 'true'
    filtered = filtered.filter(group => group.is_active === isActive)
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortField.value] || ''
    let bVal = b[sortField.value] || ''
    
    if (typeof aVal === 'string') {
      aVal = aVal.toLowerCase()
      bVal = bVal.toLowerCase()
    }
    
    if (sortDirection.value === 'asc') {
      return aVal < bVal ? -1 : aVal > bVal ? 1 : 0
    } else {
      return aVal > bVal ? -1 : aVal < bVal ? 1 : 0
    }
  })

  return filtered
})

const paginatedAnalysisGroups = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredAnalysisGroups.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredAnalysisGroups.value.length / itemsPerPage.value)
})

const totalGroups = computed(() => analysisGroups.value.length)
const activeGroups = computed(() => analysisGroups.value.filter(g => g.is_active).length)
const inactiveGroups = computed(() => analysisGroups.value.filter(g => !g.is_active).length)

// Methods
const loadAnalysisGroups = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/sku-item-note-analysis-groups')
    const data = await response.json()
    analysisGroups.value = data
  } catch (err) {
    error('Failed to load analysis groups')
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await fetch('/api/sku-item-note-analysis-groups/categories')
    const data = await response.json()
    categories.value = data
  } catch (err) {
    console.error('Failed to load categories:', err)
  }
}

const refreshData = () => {
  loadAnalysisGroups()
  loadCategories()
}

const newAnalysisGroup = () => {
  form.value = {
    group_code: '',
    group_name: '',
    description: '',
    category: '',
    is_active: true
  }
  errors.value = {}
  isEditing.value = false
  showModal.value = true
}

const editAnalysisGroup = (group) => {
  form.value = {
    group_code: group.group_code,
    group_name: group.group_name,
    description: group.description || '',
    category: group.category || '',
    is_active: group.is_active
  }
  errors.value = {}
  isEditing.value = true
  selectedAnalysisGroup.value = group
  showModal.value = true
}

const saveAnalysisGroup = async () => {
  saving.value = true
  errors.value = {}

  try {
    const url = isEditing.value 
      ? `/api/sku-item-note-analysis-groups/${selectedAnalysisGroup.value.id}`
      : '/api/sku-item-note-analysis-groups'
    
    const method = isEditing.value ? 'PUT' : 'POST'

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      closeModal()
      refreshData()
    } else {
      if (data.errors) {
        errors.value = data.errors
      } else {
        error(data.message || 'Failed to save analysis group')
      }
    }
  } catch (err) {
    error('Failed to save analysis group')
  } finally {
    saving.value = false
  }
}

const deleteAnalysisGroup = async (group) => {
  if (!confirm(`Are you sure you want to delete "${group.group_name}"?`)) {
    return
  }

  try {
    const response = await fetch(`/api/sku-item-note-analysis-groups/${group.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      if (selectedAnalysisGroup.value && selectedAnalysisGroup.value.id === group.id) {
        selectedAnalysisGroup.value = null
      }
      refreshData()
    } else {
      error(data.message || 'Failed to delete analysis group')
    }
  } catch (err) {
    error('Failed to delete analysis group')
  }
}

const toggleStatus = async (group) => {
  try {
    const response = await fetch(`/api/sku-item-note-analysis-groups/${group.id}/toggle-active`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    const data = await response.json()

    if (response.ok) {
      success(data.message)
      refreshData()
    } else {
      error(data.message || 'Failed to update status')
    }
  } catch (err) {
    error('Failed to update status')
  }
}

const selectAnalysisGroup = (group) => {
  selectedAnalysisGroup.value = group
}

const closeModal = () => {
  showModal.value = false
  form.value = {
    group_code: '',
    group_name: '',
    description: '',
    category: '',
    is_active: true
  }
  errors.value = {}
  selectedAnalysisGroup.value = null
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
}

const getCategoryBadgeClass = (category) => {
  const classes = {
    'Quality Control': 'bg-red-100 text-red-800',
    'Supplier Management': 'bg-blue-100 text-blue-800',
    'Storage Management': 'bg-green-100 text-green-800',
    'Production': 'bg-purple-100 text-purple-800',
    'Maintenance': 'bg-orange-100 text-orange-800',
    'Cost Management': 'bg-yellow-100 text-yellow-800',
    'Safety & Compliance': 'bg-indigo-100 text-indigo-800',
    'General': 'bg-gray-100 text-gray-800'
  }
  return classes[category] || 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// View & Print functionality
const showViewPrint = () => {
  showViewPrintModal.value = true
  loadPrintData()
}

const closeViewPrintModal = () => {
  showViewPrintModal.value = false
  printData.value = []
  printFilters.value = { category: '', status: '' }
}

const loadPrintData = async () => {
  try {
    const params = new URLSearchParams()
    if (printFilters.value.category) params.append('category', printFilters.value.category)
    if (printFilters.value.status) params.append('status', printFilters.value.status)
    
    const response = await fetch(`/api/sku-item-note-analysis-groups/for-print?${params}`)
    const data = await response.json()
    printData.value = data
  } catch (err) {
    error('Failed to load print data')
  }
}

const printReport = () => {
  const printContent = document.getElementById('printArea').innerHTML
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Item Note Analysis Groups Report</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; font-weight: bold; }
          .text-center { text-center; }
          h2 { color: #333; margin-bottom: 5px; }
          p { color: #666; margin-top: 0; }
        </style>
      </head>
      <body>
        ${printContent}
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.print()
}

// Lifecycle
onMounted(() => {
  refreshData()
})
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center;
}

.btn-secondary {
  @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center;
}

.btn-info {
  @apply bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center;
}

@media print {
  body * {
    visibility: hidden;
  }
  #printArea, #printArea * {
    visibility: visible;
  }
  #printArea {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
