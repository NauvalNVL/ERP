<template>
  <AppLayout :header="'Prepare Purchase Requisition'">
    <Head title="Prepare Purchase Requisition" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-plus mr-3"></i> Prepare Purchase Requisition
      </h2>
      <p class="text-blue-100">Create new purchase requisition requests</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search PR by number or description..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newPR" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New PR
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="exportPRs" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-download mr-2"></i> Export
              </button>
            </div>
          </div>
              
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('pr_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        PR Number <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('department')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Department <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('date_request')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Request Date <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Status <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('total_amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Total Amount <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading purchase requisitions...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPRs.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No purchase requisitions found. Try adjusting your search or create a new PR.
                    </td>
                  </tr>
                  <tr v-for="pr in paginatedPRs" :key="pr.id" 
                      @click="selectPR(pr)" 
                      :class="{'bg-blue-50': selectedPR && selectedPR.id === pr.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ pr.pr_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pr.department }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(pr.date_request) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(pr.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ pr.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatCurrency(pr.total_amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                  
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedPRs.length }} of {{ filteredPRs.length }} purchase requisitions</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
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
          <div v-if="selectedPR" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> PR Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PR Number:</span>
                <span class="font-medium text-gray-900">{{ selectedPR.pr_number }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Department:</span>
                <span class="font-medium text-gray-900">{{ selectedPR.department }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Request Date:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedPR.date_request) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span :class="getStatusClass(selectedPR.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ selectedPR.status }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-medium text-gray-900">{{ formatCurrency(selectedPR.total_amount) }}</span>
              </div>
            </div>
            <div class="mt-6 space-y-2">
              <button @click="editPR(selectedPR)" class="w-full btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button v-if="selectedPR.status === 'Draft'" @click="submitPR(selectedPR)" class="w-full btn-primary">
                <i class="fas fa-paper-plane mr-1"></i> Submit
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-file-plus text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No PR Selected</h3>
            <p class="text-gray-500 mb-4">Select a purchase requisition from the table to view details</p>
            <button @click="newPR" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New PR
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showFormModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-file-plus text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Purchase Requisition</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6">
          <form @submit.prevent="savePR">
            <!-- PR Header Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
              <!-- Basic Info -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Basic Information</h3>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">PR Number</label>
                  <input
                    type="text"
                    :value="formPR.pr_number || 'Auto Generated'"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
                  />
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <select
                      v-model="formPR.pr_period"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      required
                    >
                      <option value="">Select Period</option>
                      <option v-for="period in periods" :key="period" :value="period">{{ period }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select
                      v-model="formPR.pr_year"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      required
                    >
                      <option value="">Select Year</option>
                      <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Request Date</label>
                  <input
                    type="date"
                    v-model="formPR.date_request"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Required Date</label>
                  <input
                    type="date"
                    v-model="formPR.date_required"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                  />
                </div>
              </div>

              <!-- Department Info -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Department Information</h3>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                  <input
                    type="text"
                    v-model="formPR.department"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter department"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Project/Cost Center</label>
                  <input
                    type="text"
                    v-model="formPR.project_cost_center"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter project or cost center"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Requestor</label>
                  <div class="relative">
                    <input
                      type="text"
                      v-model="formPR.requestor"
                      @click="showUserModal = true"
                      readonly
                      class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Click to select requestor"
                    />
                    <button
                      type="button"
                      @click="showUserModal = true"
                      class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                  <textarea
                    v-model="formPR.purpose"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter purpose of requisition"
                  ></textarea>
                </div>
              </div>

              <!-- Approval Info -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Approval Information</h3>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                  <input
                    type="text"
                    :value="formPR.status || 'Draft'"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                  <select
                    v-model="formPR.priority"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                  <textarea
                    v-model="formPR.notes"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Additional notes or comments"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- PR Items Section -->
            <div class="mb-8">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">PR Items</h3>
                <button
                  type="button"
                  @click="addItem"
                  class="btn-primary"
                >
                  <i class="fas fa-plus mr-2"></i> Add Item
                </button>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="formPR.items.length === 0">
                      <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        No items added. Click "Add Item" to start.
                      </td>
                    </tr>
                    <tr v-for="(item, index) in formPR.items" :key="index">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative">
                          <input
                            type="text"
                            v-model="item.sku"
                            @click="openSkuModal(index)"
                            readonly
                            class="w-32 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Select SKU"
                          />
                          <button
                            type="button"
                            @click="openSkuModal(index)"
                            class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-500 hover:text-gray-700"
                          >
                            <i class="fas fa-search text-xs"></i>
                          </button>
                        </div>
                      </td>
                      <td class="px-6 py-4">
                        <input
                          type="text"
                          v-model="item.description"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Item description"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input
                          type="number"
                          v-model.number="item.quantity"
                          @input="calculateItemTotal(index)"
                          class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input
                          type="text"
                          v-model="item.unit"
                          class="w-16 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Unit"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input
                          type="number"
                          v-model.number="item.unit_price"
                          @input="calculateItemTotal(index)"
                          class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ formatCurrency(item.total_price) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                          type="button"
                          @click="removeItem(index)"
                          class="text-red-600 hover:text-red-900"
                        >
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Total Section -->
              <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-end">
                  <div class="text-right">
                    <div class="text-lg font-semibold text-gray-900">
                      Total Amount: {{ formatCurrency(totalAmount) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Save' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
              <button v-if="!isEditing || formPR.status === 'Draft'" type="button" @click="savePRAndSubmit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-paper-plane mr-2"></i> Save & Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- SKU Lookup Modal -->
    <SkuLookupModal
      :show="showSkuModal"
      @close="showSkuModal = false"
      @select="handleSkuSelect"
    />

    <!-- User Lookup Modal -->
    <UserLookupModal
      :show="showUserModal"
      @close="showUserModal = false"
      @select="handleUserSelect"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import UserLookupModal from '@/Components/UserLookupModal.vue'
import { useToast } from '@/Composables/useToast'

// State variables
const purchaseRequisitions = ref([])
const selectedPR = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const showFormModal = ref(false)
const showSkuModal = ref(false)
const showUserModal = ref(false)
const isEditing = ref(false)
const currentItemIndex = ref(null)
const sortOrder = ref({
  field: 'pr_number',
  direction: 'asc'
})
const toast = useToast()
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Form data
const formPR = ref({
  pr_number: '',
  pr_period: '',
  pr_year: new Date().getFullYear(),
  date_request: new Date().toISOString().split('T')[0],
  date_required: '',
  department: '',
  project_cost_center: '',
  requestor: '',
  purpose: '',
  status: 'Draft',
  priority: 'Medium',
  notes: '',
  items: []
})

// Computed properties
const years = computed(() => {
  const currentYear = new Date().getFullYear()
  return Array.from({ length: 5 }, (_, i) => currentYear - 2 + i)
})

const periods = computed(() => {
  return Array.from({ length: 12 }, (_, i) => String(i + 1).padStart(2, '0'))
})

const filteredPRs = computed(() => {
  // Ensure purchaseRequisitions.value is always an array
  let result = Array.isArray(purchaseRequisitions.value) ? purchaseRequisitions.value : []
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(pr =>
      (pr.pr_number && pr.pr_number.toLowerCase().includes(query)) ||
      (pr.department && pr.department.toLowerCase().includes(query)) ||
      (pr.purpose && pr.purpose.toLowerCase().includes(query))
    )
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1
    
    const valA = a[field] || ''
    const valB = b[field] || ''

    if (valA < valB) return -1 * direction
    if (valA > valB) return 1 * direction
    return 0
  })

  return result
})

const paginatedPRs = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredPRs.value.slice(start, end)
})

const totalPages = computed(() => {
  const total = filteredPRs.value.length
  if (total === 0) return 1
  return Math.ceil(total / itemsPerPage.value)
})

const totalAmount = computed(() => {
  return formPR.value.items.reduce((sum, item) => sum + (item.total_price || 0), 0)
})

// Watchers
watch(filteredPRs, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

watch(itemsPerPage, () => {
  currentPage.value = 1
})

// Methods
const fetchPRs = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/purchase-requisitions')
    const data = await response.json()
    purchaseRequisitions.value = data
  } catch (error) {
    console.error('Error fetching purchase requisitions:', error)
    toast.error('Failed to load purchase requisitions')
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  selectedPR.value = null
  searchQuery.value = ''
  fetchPRs()
}

const selectPR = (pr) => {
  selectedPR.value = pr
}

const newPR = () => {
  resetForm()
  showFormModal.value = true
}

const editPR = (pr) => {
  isEditing.value = true
  formPR.value = { ...pr, items: pr.items || [] }
  showFormModal.value = true
}

const resetForm = () => {
  formPR.value = {
    pr_number: '',
    pr_period: '',
    pr_year: new Date().getFullYear(),
    date_request: new Date().toISOString().split('T')[0],
    date_required: '',
    department: '',
    project_cost_center: '',
    requestor: '',
    purpose: '',
    status: 'Draft',
    priority: 'Medium',
    notes: '',
    items: []
  }
  isEditing.value = false
}

const savePR = async () => {
  loading.value = true
  try {
    formPR.value.total_amount = totalAmount.value

    let response
    if (isEditing.value) {
      response = await fetch(`/api/purchase-requisitions/${formPR.value.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formPR.value)
      })
      toast.success('Purchase requisition updated successfully')
      
      const index = purchaseRequisitions.value.findIndex(pr => pr.id === formPR.value.id)
      if (index !== -1) {
        const updatedPR = await response.json()
        purchaseRequisitions.value[index] = updatedPR
        selectedPR.value = updatedPR
      }
    } else {
      response = await fetch('/api/purchase-requisitions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formPR.value)
      })
      toast.success('Purchase requisition created successfully')
      const newPR = await response.json()
      purchaseRequisitions.value.push(newPR)
      selectedPR.value = newPR
    }
    
    showFormModal.value = false
  } catch (error) {
    console.error('Error saving purchase requisition:', error)
    toast.error('Failed to save purchase requisition')
  } finally {
    loading.value = false
  }
}

const savePRAndSubmit = async () => {
  formPR.value.status = 'Submitted'
  await savePR()
}

const submitPR = async (pr) => {
  try {
    await fetch(`/api/purchase-requisitions/${pr.id}/submit`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    toast.success('Purchase requisition submitted for approval successfully')
    fetchPRs()
  } catch (error) {
    console.error('Error submitting purchase requisition:', error)
    toast.error('Failed to submit purchase requisition')
  }
}

const addItem = () => {
  formPR.value.items.push({
    sku: '',
    description: '',
    quantity: 1,
    unit: '',
    unit_price: 0,
    total_price: 0
  })
}

const removeItem = (index) => {
  formPR.value.items.splice(index, 1)
}

const calculateItemTotal = (index) => {
  const item = formPR.value.items[index]
  item.total_price = (item.quantity || 0) * (item.unit_price || 0)
}

const openSkuModal = (index) => {
  currentItemIndex.value = index
  showSkuModal.value = true
}

const handleSkuSelect = (sku) => {
  if (currentItemIndex.value !== null) {
    const item = formPR.value.items[currentItemIndex.value]
    item.sku = sku.sku
    item.description = sku.sku_name
    item.unit = sku.unit
  }
  showSkuModal.value = false
  currentItemIndex.value = null
}

const handleUserSelect = (user) => {
  formPR.value.requestor = user.name
  showUserModal.value = false
}

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortOrder.value.field = field
    sortOrder.value.direction = 'asc'
  }
}

const exportPRs = () => {
  // Implementation for export functionality
  toast.info('Export functionality coming soon')
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount || 0)
}

const getStatusClass = (status) => {
  const statusClasses = {
    'Draft': 'bg-gray-100 text-gray-800',
    'Submitted': 'bg-blue-100 text-blue-800',
    'Approved': 'bg-green-100 text-green-800',
    'Rejected': 'bg-red-100 text-red-800',
    'Cancelled': 'bg-gray-100 text-gray-800'
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  fetchPRs()
})
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
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

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>