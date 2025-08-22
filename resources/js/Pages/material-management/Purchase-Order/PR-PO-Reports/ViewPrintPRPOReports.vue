<template>
  <AppLayout :header="'View & Print PR/PO Reports'">
    <div class="bg-white rounded-lg shadow-md p-6">
      <!-- Filter Section -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Options</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Report Period -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.reportPeriod"
                type="text"
                placeholder="MM/YYYY"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="setCurrentPeriod"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
              >
                Current
              </button>
            </div>
          </div>

          <!-- Date Range -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.dateFrom"
                type="date"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <span class="text-gray-500 self-center">to</span>
              <input
                v-model="filters.dateTo"
                type="date"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <!-- PR Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">PR Number</label>
            <input
              v-model="filters.prNumber"
              type="text"
              placeholder="PR Number"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- PO Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">PO Number</label>
            <input
              v-model="filters.poNumber"
              type="text"
              placeholder="PO Number"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Vendor -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor (AP AC#)</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.vendor"
                type="text"
                placeholder="AP AC#"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openVendorModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
            <div class="flex space-x-2">
              <input
                v-model="filters.sku"
                type="text"
                placeholder="SKU Code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button
                @click="openSkuModal"
                class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
              >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Status Filters -->
        <div class="mt-6">
          <h4 class="text-md font-medium text-gray-700 mb-3">Status Filters</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- PR Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">PR Status</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.prStatus"
                    type="checkbox"
                    value="Draft"
                    class="mr-2"
                  />
                  <span class="text-sm">Draft</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.prStatus"
                    type="checkbox"
                    value="Pending"
                    class="mr-2"
                  />
                  <span class="text-sm">Pending</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.prStatus"
                    type="checkbox"
                    value="Approved"
                    class="mr-2"
                  />
                  <span class="text-sm">Approved</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.prStatus"
                    type="checkbox"
                    value="Rejected"
                    class="mr-2"
                  />
                  <span class="text-sm">Rejected</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.prStatus"
                    type="checkbox"
                    value="Cancelled"
                    class="mr-2"
                  />
                  <span class="text-sm">Cancelled</span>
                </label>
              </div>
            </div>

            <!-- PO Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">PO Status</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Draft"
                    class="mr-2"
                  />
                  <span class="text-sm">Draft</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Pending"
                    class="mr-2"
                  />
                  <span class="text-sm">Pending</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Approved"
                    class="mr-2"
                  />
                  <span class="text-sm">Approved</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Rejected"
                    class="mr-2"
                  />
                  <span class="text-sm">Rejected</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Cancelled"
                    class="mr-2"
                  />
                  <span class="text-sm">Cancelled</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.poStatus"
                    type="checkbox"
                    value="Completed"
                    class="mr-2"
                  />
                  <span class="text-sm">Completed</span>
                </label>
              </div>
            </div>

            <!-- Report Options -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Options</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="filters.includePR"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include PR Details</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includePO"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include PO Details</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includeItems"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Item Details</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includePricing"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Pricing Information</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="filters.includeApproval"
                    type="checkbox"
                    class="mr-2"
                  />
                  <span class="text-sm">Include Approval History</span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
          <button
            @click="generateReport"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-file-excel mr-2"></i>
            {{ loading ? 'Generating...' : 'Generate Report' }}
          </button>
          
          <button
            @click="previewReport"
            :disabled="loading"
            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors disabled:opacity-50"
          >
            <i class="fas fa-eye mr-2"></i>
            Preview
          </button>
          
          <button
            @click="clearFilters"
            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Filters
          </button>
        </div>

        <div class="text-sm text-gray-600">
          <i class="fas fa-info-circle mr-1"></i>
          PR = Purchase Requisition, PO = Purchase Order
        </div>
      </div>

      <!-- Report Summary -->
      <div v-if="reportSummary" class="bg-gray-50 rounded-lg p-4 mb-6">
        <h4 class="text-md font-medium text-gray-800 mb-3">Report Summary</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ reportSummary.totalPR }}</div>
            <div class="text-sm text-gray-600">Total PR</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ reportSummary.totalPO }}</div>
            <div class="text-sm text-gray-600">Total PO</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-orange-600">{{ formatCurrency(reportSummary.totalAmount) }}</div>
            <div class="text-sm text-gray-600">Total Amount</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ reportSummary.avgProcessingTime }}</div>
            <div class="text-sm text-gray-600">Avg Processing (Days)</div>
          </div>
        </div>
      </div>

      <!-- PR/PO Preview -->
      <div v-if="prpoData.length > 0" class="bg-white border rounded-lg">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">PR/PO Preview</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PR Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PR Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in prpoData" :key="item.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.pr_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.po_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.supplier }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(item.amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getStatusColor(item.pr_status)">
                    {{ item.pr_status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span :class="getStatusColor(item.po_status)">
                    {{ item.po_status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Reports -->
      <div v-if="recentReports.length > 0" class="bg-white border rounded-lg mt-6">
        <div class="px-6 py-4 border-b">
          <h4 class="text-md font-medium text-gray-800">Recent Reports</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="report in recentReports" :key="report.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.generatedDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.period }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.recordCount }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.generatedBy }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="downloadReport(report.id)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                  <button
                    @click="deleteReport(report.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <VendorProfileModal
      :show="showVendorModal"
      @close="showVendorModal = false"
      @select="selectVendor"
    />
    
    <SkuLookupModal
      :show="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const loading = ref(false)
const showVendorModal = ref(false)
const showSkuModal = ref(false)

const filters = reactive({
  reportPeriod: '',
  dateFrom: '',
  dateTo: '',
  prNumber: '',
  poNumber: '',
  vendor: '',
  sku: '',
  prStatus: [],
  poStatus: [],
  includePR: true,
  includePO: true,
  includeItems: true,
  includePricing: true,
  includeApproval: false
})

const reportSummary = ref(null)
const prpoData = ref([])
const recentReports = ref([])

// Methods
const setCurrentPeriod = () => {
  const now = new Date()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const year = now.getFullYear()
  filters.reportPeriod = `${month}/${year}`
}

const openVendorModal = () => {
  showVendorModal.value = true
}

const openSkuModal = () => {
  showSkuModal.value = true
}

const selectVendor = (vendor) => {
  filters.vendor = vendor.ap_ac_number
  showVendorModal.value = false
}

const selectSku = (sku) => {
  filters.sku = sku.sku
  showSkuModal.value = false
}

const clearFilters = () => {
  Object.assign(filters, {
    reportPeriod: '',
    dateFrom: '',
    dateTo: '',
    prNumber: '',
    poNumber: '',
    vendor: '',
    sku: '',
    prStatus: [],
    poStatus: [],
    includePR: true,
    includePO: true,
    includeItems: true,
    includePricing: true,
    includeApproval: false
  })
  reportSummary.value = null
  prpoData.value = []
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const getStatusColor = (status) => {
  switch (status) {
    case 'Approved':
    case 'Completed':
      return 'text-green-600'
    case 'Pending':
      return 'text-yellow-600'
    case 'Rejected':
    case 'Cancelled':
      return 'text-red-600'
    case 'Draft':
      return 'text-gray-600'
    default:
      return 'text-gray-600'
  }
}

const generateReport = async () => {
  try {
    loading.value = true
    
    // Validate required fields
    if (!filters.reportPeriod && !filters.dateFrom && !filters.dateTo) {
      error('Please select a report period or date range')
      return
    }

    const response = await fetch('/api/pr-po-reports/generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(filters)
    })

    if (!response.ok) {
      throw new Error('Failed to generate report')
    }

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `PR_PO_Reports_${filters.reportPeriod || 'Custom'}.xlsx`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)

    success('Report generated successfully')
    await loadRecentReports()
    
  } catch (err) {
    error('Failed to generate report: ' + err.message)
  } finally {
    loading.value = false
  }
}

const previewReport = async () => {
  try {
    loading.value = true
    
    const response = await fetch('/api/pr-po-reports/preview', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(filters)
    })

    if (!response.ok) {
      throw new Error('Failed to preview report')
    }

    const data = await response.json()
    reportSummary.value = data.summary
    prpoData.value = data.prpoData || []
    
    success('Report preview loaded')
    
  } catch (err) {
    error('Failed to preview report: ' + err.message)
  } finally {
    loading.value = false
  }
}

const downloadReport = async (reportId) => {
  try {
    const response = await fetch(`/api/pr-po-reports/${reportId}/download`)
    if (!response.ok) {
      throw new Error('Failed to download report')
    }

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `PR_PO_Reports_${reportId}.xlsx`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)

    success('Report downloaded successfully')
  } catch (err) {
    error('Failed to download report: ' + err.message)
  }
}

const deleteReport = async (reportId) => {
  if (!confirm('Are you sure you want to delete this report?')) {
    return
  }

  try {
    const response = await fetch(`/api/pr-po-reports/${reportId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    if (!response.ok) {
      throw new Error('Failed to delete report')
    }

    success('Report deleted successfully')
    await loadRecentReports()
  } catch (err) {
    error('Failed to delete report: ' + err.message)
  }
}

const loadRecentReports = async () => {
  try {
    const response = await fetch('/api/pr-po-reports/recent')
    if (response.ok) {
      recentReports.value = await response.json()
    }
  } catch (err) {
    console.error('Failed to load recent reports:', err)
  }
}

// Lifecycle
onMounted(() => {
  setCurrentPeriod()
  loadRecentReports()
})
</script>
