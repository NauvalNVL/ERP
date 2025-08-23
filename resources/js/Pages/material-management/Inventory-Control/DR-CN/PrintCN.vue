<template>
  <AppLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-print mr-3"></i>
            Print Credit Note (CN)
          </h1>
          <p class="text-emerald-100 mt-2 text-lg">Generate and print credit note reports and documents</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="generateReport"
            :disabled="!isFormValid || isGenerating"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-file-pdf mr-2" v-if="!isGenerating"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isGenerating ? 'Generating...' : 'Generate PDF' }}
          </button>
          <button
            @click="previewReport"
            :disabled="!isFormValid || isGenerating"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
          >
            <i class="fas fa-eye mr-2"></i>
            Preview Report
          </button>
        </div>
      </div>
    </div>

    <!-- Report Criteria Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-filter text-emerald-600 mr-2"></i>
        Report Criteria
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Date Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date From *</label>
          <input
            v-model="criteria.date_from"
            type="date"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date To *</label>
          <input
            v-model="criteria.date_to"
            type="date"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
          />
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select
            v-model="criteria.status"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
          >
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Posted">Posted</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>

        <!-- Customer Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
          <input
            v-model="criteria.customer_code"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
            placeholder="Enter customer code"
          />
        </div>

        <!-- Amount Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
          <input
            v-model="criteria.min_amount"
            type="number"
            step="0.01"
            min="0"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
            placeholder="0.00"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
          <input
            v-model="criteria.max_amount"
            type="number"
            step="0.01"
            min="0"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
            placeholder="999999.99"
          />
        </div>

        <!-- Report Type -->
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
          <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center">
              <input
                v-model="criteria.report_type"
                type="radio"
                value="summary"
                class="form-radio text-emerald-600"
              />
              <span class="ml-2 text-sm text-gray-700">Summary Report</span>
            </label>
            <label class="flex items-center">
              <input
                v-model="criteria.report_type"
                type="radio"
                value="detailed"
                class="form-radio text-emerald-600"
              />
              <span class="ml-2 text-sm text-gray-700">Detailed Report</span>
            </label>
          </div>
        </div>

        <!-- Format -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
          <select
            v-model="criteria.format"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
          >
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Preview Section -->
    <div v-if="reportData" class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
          <i class="fas fa-eye text-blue-600 mr-2"></i>
          Report Preview
        </h3>
        <button
          @click="printPreview"
          class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
        >
          <i class="fas fa-print mr-2"></i>
          Print Preview
        </button>
      </div>

      <!-- Report Header -->
      <div class="text-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-900">{{ reportData.title }}</h2>
        <p class="text-gray-600 mt-2">{{ reportData.period }}</p>
        <p class="text-sm text-gray-500 mt-1">Generated: {{ formatDateTime(new Date()) }}</p>
      </div>

      <!-- Summary Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 rounded-lg p-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-emerald-600">{{ reportData.total_count }}</div>
            <div class="text-sm text-gray-600">Total Notes</div>
          </div>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ formatCurrency(reportData.total_amount) }}</div>
            <div class="text-sm text-gray-600">Total Amount</div>
          </div>
        </div>
        <div class="bg-blue-50 rounded-lg p-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ reportData.notes?.filter(n => n.status === 'Posted').length || 0 }}</div>
            <div class="text-sm text-gray-600">Posted</div>
          </div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ reportData.notes?.filter(n => n.status === 'Pending').length || 0 }}</div>
            <div class="text-sm text-gray-600">Pending</div>
          </div>
        </div>
      </div>

      <!-- Notes Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Note Number</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th v-if="criteria.report_type === 'detailed'" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="note in reportData.notes" :key="note.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ note.note_number }}</td>
              <td class="px-4 py-2 text-sm text-gray-500">{{ formatDate(note.note_date) }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ note.customer_name || 'N/A' }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ formatCurrency(note.amount) }}</td>
              <td class="px-4 py-2">
                <span :class="getStatusBadgeClass(note.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                  {{ note.status }}
                </span>
              </td>
              <td v-if="criteria.report_type === 'detailed'" class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate">
                {{ note.description }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-history text-gray-600 mr-2"></i>
        Recent Reports
      </h3>
      <div class="space-y-3">
        <div 
          v-for="report in recentReports" 
          :key="report.id"
          class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50"
        >
          <div>
            <div class="text-sm font-medium text-gray-900">{{ report.title }}</div>
            <div class="text-xs text-gray-500">{{ report.period }}</div>
            <div class="text-xs text-gray-500">Generated: {{ formatDateTime(report.generated_at) }}</div>
          </div>
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ report.record_count }} records</span>
            <button
              @click="downloadReport(report)"
              class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150"
              title="Download"
            >
              <i class="fas fa-download"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'

const toast = useToast()

// Form data
const criteria = ref({
  date_from: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0],
  date_to: new Date().toISOString().split('T')[0],
  status: '',
  customer_code: '',
  min_amount: '',
  max_amount: '',
  report_type: 'summary',
  format: 'pdf'
})

// Loading states
const isGenerating = ref(false)

// Data
const reportData = ref(null)
const recentReports = ref([])

// Computed properties
const isFormValid = computed(() => {
  return criteria.value.date_from && criteria.value.date_to
})

// Methods
const generateReport = async () => {
  if (!isFormValid.value) {
    toast.error('Please fill in required fields')
    return
  }

  isGenerating.value = true
  try {
    const response = await fetch('/api/dr-cn/generate-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        note_type: 'CR',
        ...criteria.value
      })
    })

    const result = await response.json()
    
    if (result.success) {
      reportData.value = result.data
      toast.success('Report generated successfully!')
      
      if (criteria.value.format === 'pdf') {
        downloadPDF(result.data)
      } else {
        downloadExcel(result.data)
      }
    } else {
      toast.error(result.message || 'Failed to generate report')
    }
  } catch (error) {
    console.error('Error generating report:', error)
    toast.error('Error generating report')
  } finally {
    isGenerating.value = false
  }
}

const previewReport = async () => {
  if (!isFormValid.value) {
    toast.error('Please fill in required fields')
    return
  }

  isGenerating.value = true
  try {
    const response = await fetch('/api/dr-cn/generate-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        note_type: 'CR',
        ...criteria.value
      })
    })

    const result = await response.json()
    
    if (result.success) {
      reportData.value = result.data
      toast.success('Report preview generated!')
    } else {
      toast.error(result.message || 'Failed to generate preview')
    }
  } catch (error) {
    console.error('Error generating preview:', error)
    toast.error('Error generating preview')
  } finally {
    isGenerating.value = false
  }
}

const printPreview = () => {
  if (!reportData.value) return

  const printContent = document.querySelector('.bg-white.rounded-lg.shadow-lg.p-6.mb-6:nth-of-type(2)').innerHTML
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>Credit Note Report</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f9f9f9; }
          .text-center { text-align: center; }
          .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
          .bg-emerald-50 { background-color: #ecfdf5; padding: 1rem; border-radius: 0.5rem; }
        </style>
      </head>
      <body>${printContent}</body>
    </html>
  `)
  printWindow.document.close()
  printWindow.print()
}

const downloadPDF = (data) => {
  // Mock PDF download
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `CN_Report_${criteria.value.date_from}_to_${criteria.value.date_to}.json`
  a.click()
  window.URL.revokeObjectURL(url)
}

const downloadExcel = (data) => {
  // Mock Excel download
  const csvContent = "Note Number,Date,Customer,Amount,Status,Description\n" + 
    data.notes.map(note => 
      `${note.note_number},${note.note_date},${note.customer_name || ''},${note.amount},${note.status},"${note.description}"`
    ).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `CN_Report_${criteria.value.date_from}_to_${criteria.value.date_to}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
}

const downloadReport = (report) => {
  toast.info('Downloading report: ' + report.title)
}

const loadRecentReports = () => {
  recentReports.value = [
    {
      id: 1,
      title: 'Credit Note Summary Report',
      period: '2025-01-01 to 2025-01-31',
      generated_at: new Date('2025-01-31T10:30:00'),
      record_count: 18
    },
    {
      id: 2,
      title: 'Credit Note Detailed Report',
      period: '2025-01-15 to 2025-01-30',
      generated_at: new Date('2025-01-30T15:45:00'),
      record_count: 12
    }
  ]
}

// Utility functions
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount || 0)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatDateTime = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusBadgeClass = (status) => {
  const classes = {
    'Draft': 'bg-gray-100 text-gray-800',
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Approved': 'bg-green-100 text-green-800',
    'Rejected': 'bg-red-100 text-red-800',
    'Posted': 'bg-blue-100 text-blue-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// Lifecycle hooks
onMounted(() => {
  loadRecentReports()
})
</script>
