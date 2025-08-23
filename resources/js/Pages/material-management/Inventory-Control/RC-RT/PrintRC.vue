<template>
  <AppLayout :header="header">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
              <i class="fas fa-print text-purple-500 mr-3"></i>
              Print Receipt (RC)
            </h1>
            <p class="text-gray-600 mt-2">Generate and print receipt transaction reports</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="generateReport"
              :disabled="!isFormValid || isGenerating"
              class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-file-pdf mr-2" v-if="!isGenerating"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isGenerating ? 'Generating...' : 'Generate Report' }}
            </button>
            <button
              @click="printReport"
              :disabled="!reportData || isPrinting"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-print mr-2" v-if="!isPrinting"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isPrinting ? 'Printing...' : 'Print Report' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Report Criteria -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-filter text-blue-500 mr-2"></i>
          Report Criteria
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From *</label>
            <input
              type="date"
              v-model="form.date_from"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To *</label>
            <input
              type="date"
              v-model="form.date_to"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="form.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Status</option>
              <option value="Draft">Draft</option>
              <option value="Posted">Posted</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        
        <div class="mt-4 flex space-x-3">
          <button
            @click="previewReport"
            :disabled="!isFormValid || isPreviewing"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-eye mr-2" v-if="!isPreviewing"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isPreviewing ? 'Loading...' : 'Preview Report' }}
          </button>
          <button
            @click="clearForm"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear
          </button>
        </div>
      </div>

      <!-- Report Preview -->
      <div v-if="reportData" class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-chart-bar text-indigo-500 mr-2"></i>
          Report Summary
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-blue-600">Total Transactions</p>
                <p class="text-2xl font-bold text-blue-800">{{ reportData.summary.total_transactions }}</p>
              </div>
              <i class="fas fa-file-alt text-blue-400 text-2xl"></i>
            </div>
          </div>
          
          <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-green-600">Total Amount</p>
                <p class="text-2xl font-bold text-green-800">{{ formatCurrency(reportData.summary.total_amount) }}</p>
              </div>
              <i class="fas fa-money-bill-wave text-green-400 text-2xl"></i>
            </div>
          </div>
          
          <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-purple-600">Total Quantity</p>
                <p class="text-2xl font-bold text-purple-800">{{ formatNumber(reportData.summary.total_quantity) }}</p>
              </div>
              <i class="fas fa-boxes text-purple-400 text-2xl"></i>
            </div>
          </div>
          
          <div class="bg-orange-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-orange-600">Posted Count</p>
                <p class="text-2xl font-bold text-orange-800">{{ reportData.summary.posted_count }}</p>
              </div>
              <i class="fas fa-check-circle text-orange-400 text-2xl"></i>
            </div>
          </div>
        </div>
        
        <!-- Transactions Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in reportData.transactions" :key="transaction.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.transaction_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.transaction_date) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.supplier_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.sku_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatNumber(transaction.quantity) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(transaction.unit_price) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(transaction.total_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusBadgeClass(transaction.status)">
                    {{ transaction.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Reports -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-history text-indigo-500 mr-2"></i>
          Recent Reports
        </h3>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Filter</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="report in recentReports" :key="report.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDateTime(report.created_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.date_from }} - {{ report.date_to }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.status || 'All' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.transaction_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="downloadReport(report)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                  <button
                    @click="printReport(report)"
                    class="text-green-600 hover:text-green-900"
                  >
                    <i class="fas fa-print"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const props = defineProps({
  header: String
})

const toast = useToast()

// Reactive data
const form = ref({
  date_from: new Date().toISOString().split('T')[0],
  date_to: new Date().toISOString().split('T')[0],
  status: ''
})

const reportData = ref(null)
const recentReports = ref([])

const isGenerating = ref(false)
const isPrinting = ref(false)
const isPreviewing = ref(false)

// Computed properties
const isFormValid = computed(() => {
  return form.value.date_from && form.value.date_to
})

// Methods
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const formatNumber = (number) => {
  return new Intl.NumberFormat('id-ID').format(number)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('id-ID')
}

const getStatusBadgeClass = (status) => {
  return {
    'Draft': 'bg-gray-100 text-gray-800',
    'Posted': 'bg-green-100 text-green-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }[status] || 'bg-gray-100 text-gray-800'
}

const previewReport = async () => {
  if (!isFormValid.value) {
    toast.error('Please fill in all required fields')
    return
  }

  try {
    isPreviewing.value = true
    
    const response = await axios.post('/api/rc-rt/generate-report', {
      transaction_type: 'RC',
      ...form.value,
      format: 'json'
    })

    if (response.data.success) {
      reportData.value = response.data.data
      toast.success('Report preview loaded successfully!')
    } else {
      toast.error(response.data.message || 'Failed to generate report')
    }
  } catch (error) {
    console.error('Error generating report:', error)
    toast.error(error.response?.data?.message || 'Error generating report')
  } finally {
    isPreviewing.value = false
  }
}

const generateReport = async () => {
  if (!isFormValid.value) {
    toast.error('Please fill in all required fields')
    return
  }

  try {
    isGenerating.value = true
    
    const response = await axios.post('/api/rc-rt/generate-report', {
      transaction_type: 'RC',
      ...form.value,
      format: 'pdf'
    })

    if (response.data.success) {
      // Create and download PDF
      const blob = new Blob([response.data.data], { type: 'application/pdf' })
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `RC_Report_${form.value.date_from}_${form.value.date_to}.pdf`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      window.URL.revokeObjectURL(url)
      
      toast.success('Report generated and downloaded successfully!')
    } else {
      toast.error(response.data.message || 'Failed to generate report')
    }
  } catch (error) {
    console.error('Error generating report:', error)
    toast.error(error.response?.data?.message || 'Error generating report')
  } finally {
    isGenerating.value = false
  }
}

const printReport = async (report = null) => {
  try {
    isPrinting.value = true
    
    if (report) {
      // Print specific report
      const response = await axios.get(`/api/rc-rt/reports/${report.id}/print`)
      // Handle print response
    } else {
      // Print current preview
      if (reportData.value) {
        window.print()
      } else {
        toast.error('No report data to print')
      }
    }
    
    toast.success('Report sent to printer successfully!')
  } catch (error) {
    console.error('Error printing report:', error)
    toast.error(error.response?.data?.message || 'Error printing report')
  } finally {
    isPrinting.value = false
  }
}

const downloadReport = async (report) => {
  try {
    const response = await axios.get(`/api/rc-rt/reports/${report.id}/download`)
    
    if (response.data.success) {
      const blob = new Blob([response.data.data], { type: 'application/pdf' })
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `RC_Report_${report.id}.pdf`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      window.URL.revokeObjectURL(url)
      
      toast.success('Report downloaded successfully!')
    }
  } catch (error) {
    console.error('Error downloading report:', error)
    toast.error('Error downloading report')
  }
}

const clearForm = () => {
  form.value = {
    date_from: new Date().toISOString().split('T')[0],
    date_to: new Date().toISOString().split('T')[0],
    status: ''
  }
  reportData.value = null
}

const loadRecentReports = async () => {
  try {
    const response = await axios.get('/api/rc-rt/reports/recent')
    
    if (response.data.success) {
      recentReports.value = response.data.data || []
    }
  } catch (error) {
    console.error('Error loading recent reports:', error)
  }
}

// Initialize
onMounted(() => {
  loadRecentReports()
})
</script>

<style scoped>
/* Custom styles */
.transition-all {
  transition: all 0.3s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
