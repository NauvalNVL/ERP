<template>
  <AppLayout header="Print Purchase Requisition">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Print Purchase Requisition</h1>
              <p class="text-sm text-gray-600">Generate and print purchase requisition documents</p>
            </div>
            <div class="flex items-center space-x-2">
              <i class="fas fa-file-pdf text-2xl text-blue-600"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Search Section -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Search Purchase Requisition</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">PR Number</label>
              <input
                v-model="searchForm.pr_number"
                type="text"
                placeholder="e.g., PR202501001"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="searchForm.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">All Status</option>
                <option value="DRAFT">Draft</option>
                <option value="PENDING_APPROVAL">Pending Approval</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
                <option value="CANCELLED">Cancelled</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Print Format</label>
              <select
                v-model="printFormat"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="standard">Standard Format</option>
                <option value="detailed">Detailed Format</option>
                <option value="summary">Summary Format</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
              <input
                v-model="searchForm.department"
                type="text"
                placeholder="Department Code/Name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
          
          <div class="flex justify-end mt-4 space-x-3">
            <button
              @click="resetSearch"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Reset
            </button>
            <button
              @click="searchPR"
              class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700"
            >
              <i class="fas fa-search mr-2"></i>
              Search
            </button>
          </div>
        </div>
      </div>

      <!-- Results Section -->
      <div v-if="searchResults.length > 0" class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Purchase Requisitions</h3>
            <div class="flex space-x-2">
              <button
                @click="printSelected"
                :disabled="selectedPRs.length === 0"
                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fas fa-print mr-2"></i>
                Print Selected ({{ selectedPRs.length }})
              </button>
              <button
                @click="exportToExcel"
                :disabled="searchResults.length === 0"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                <i class="fas fa-file-excel mr-2"></i>
                Export to Excel
              </button>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left">
                    <input
                      type="checkbox"
                      @change="toggleAllSelection"
                      :checked="isAllSelected"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PR Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="pr in searchResults" :key="pr.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <input
                      type="checkbox"
                      :value="pr.id"
                      v-model="selectedPRs"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ pr.pr_number }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(pr.request_date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ pr.department_name || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ pr.requestor_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="getStatusBadgeClass(pr.status)"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ pr.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatCurrency(pr.total_estimated_value) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button
                      @click="printSingle(pr)"
                      class="text-blue-600 hover:text-blue-900"
                      title="Print this PR"
                    >
                      <i class="fas fa-print"></i>
                    </button>
                    <button
                      @click="viewPR(pr)"
                      class="text-green-600 hover:text-green-900"
                      title="View PR details"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="emailPR(pr)"
                      class="text-purple-600 hover:text-purple-900"
                      title="Email PR"
                    >
                      <i class="fas fa-envelope"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Print Options Modal -->
      <div v-if="showPrintModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Print Options</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Print Format</label>
                <select
                  v-model="printOptions.format"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="standard">Standard Format</option>
                  <option value="detailed">Detailed Format</option>
                  <option value="summary">Summary Format</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Paper Size</label>
                <select
                  v-model="printOptions.paperSize"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="A4">A4</option>
                  <option value="Letter">Letter</option>
                  <option value="Legal">Legal</option>
                </select>
              </div>
              
              <div class="flex items-center">
                <input
                  type="checkbox"
                  v-model="printOptions.includeSignature"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <label class="ml-2 text-sm text-gray-700">Include signature fields</label>
              </div>
              
              <div class="flex items-center">
                <input
                  type="checkbox"
                  v-model="printOptions.includeLogo"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <label class="ml-2 text-sm text-gray-700">Include company logo</label>
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
              <button
                @click="closePrintModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                @click="confirmPrint"
                :disabled="printing"
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
              >
                {{ printing ? 'Printing...' : 'Print' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Box -->
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Print Features</h3>
            <div class="mt-2 text-sm text-blue-700">
              <ul class="list-disc list-inside space-y-1">
                <li><strong>Standard Format:</strong> Standard PR layout with all essential information</li>
                <li><strong>Detailed Format:</strong> Includes item specifications and approval history</li>
                <li><strong>Summary Format:</strong> Condensed format showing key information only</li>
                <li>Multiple PRs can be printed in batch with same format settings</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Data
const loading = ref(false)
const hasSearched = ref(false)
const searchResults = ref([])
const selectedPRs = ref([])
const showPrintModal = ref(false)
const printing = ref(false)
const printFormat = ref('standard')

// Search form
const searchForm = reactive({
  pr_number: '',
  status: '',
  department: ''
})

// Print options
const printOptions = reactive({
  format: 'standard',
  paperSize: 'A4',
  includeSignature: true,
  includeLogo: true
})

// Computed
const isAllSelected = computed(() => {
  return searchResults.value.length > 0 && selectedPRs.value.length === searchResults.value.length
})

// Methods
const searchPR = async () => {
  loading.value = true
  hasSearched.value = true
  
  try {
    const params = new URLSearchParams()
    
    if (searchForm.pr_number) params.append('search', searchForm.pr_number)
    if (searchForm.status) params.append('status', searchForm.status)
    if (searchForm.department) params.append('department', searchForm.department)

    const response = await fetch(`/api/purchase-requisitions?${params}`)
    const result = await response.json()
    
    if (result.success) {
      searchResults.value = result.data
      selectedPRs.value = []
    } else {
      error('Failed to search purchase requisitions')
    }
  } catch (err) {
    error('An error occurred while searching')
  } finally {
    loading.value = false
  }
}

const resetSearch = () => {
  Object.keys(searchForm).forEach(key => {
    searchForm[key] = ''
  })
  searchResults.value = []
  selectedPRs.value = []
  hasSearched.value = false
}

const toggleAllSelection = () => {
  if (isAllSelected.value) {
    selectedPRs.value = []
  } else {
    selectedPRs.value = searchResults.value.map(pr => pr.id)
  }
}

const printSingle = (pr) => {
  selectedPRs.value = [pr.id]
  showPrintModal.value = true
}

const printSelected = () => {
  if (selectedPRs.value.length === 0) {
    error('Please select at least one PR to print')
    return
  }
  showPrintModal.value = true
}

const confirmPrint = async () => {
  printing.value = true
  
  try {
    const response = await fetch('/api/purchase-requisitions/print', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.getCsrfToken(),
      },
      body: JSON.stringify({
        pr_ids: selectedPRs.value,
        format: printOptions.format,
        paper_size: printOptions.paperSize,
        include_signature: printOptions.includeSignature,
        include_logo: printOptions.includeLogo
      })
    })

    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `PR_${new Date().toISOString().split('T')[0]}.pdf`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      success('PRs printed successfully')
      closePrintModal()
    } else {
      error('Failed to generate print document')
    }
  } catch (err) {
    error('An error occurred while printing')
  } finally {
    printing.value = false
  }
}

const closePrintModal = () => {
  showPrintModal.value = false
}

const viewPR = (pr) => {
  // Open PR details in a new tab or modal
  window.open(`/material-management/purchase-order/pr-po/prepare-pr?view=${pr.id}`, '_blank')
}

const emailPR = async (pr) => {
  try {
    const response = await fetch(`/api/purchase-requisitions/${pr.id}/email`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.getCsrfToken(),
      }
    })

    const result = await response.json()
    
    if (result.success) {
      success('PR sent via email successfully')
    } else {
      error(result.message || 'Failed to send PR via email')
    }
  } catch (err) {
    error('An error occurred while sending email')
  }
}

const exportToExcel = async () => {
  try {
    const params = new URLSearchParams()
    if (searchForm.pr_number) params.append('search', searchForm.pr_number)
    if (searchForm.status) params.append('status', searchForm.status)
    if (searchForm.department) params.append('department', searchForm.department)

    const response = await fetch(`/api/purchase-requisitions/export?${params}`)
    
    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `PR_Export_${new Date().toISOString().split('T')[0]}.xlsx`
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
      
      success('PRs exported to Excel successfully')
    } else {
      error('Failed to export PRs')
    }
  } catch (err) {
    error('An error occurred while exporting')
  }
}

const getStatusBadgeClass = (status) => {
  const classMap = {
    'DRAFT': 'bg-gray-100 text-gray-800',
    'PENDING_APPROVAL': 'bg-yellow-100 text-yellow-800',
    'APPROVED': 'bg-green-100 text-green-800',
    'REJECTED': 'bg-red-100 text-red-800',
    'CANCELLED': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount || 0)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}
</script>
