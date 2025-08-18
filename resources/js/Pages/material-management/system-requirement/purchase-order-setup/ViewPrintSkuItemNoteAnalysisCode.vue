<template>
  <AppLayout :header="'View & Print SKU Item Note Analysis Code'">
    <Head title="View & Print SKU Item Note Analysis Code" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print SKU Item Note Analysis Code
      </h2>
      <p class="text-purple-100">Generate and print reports for SKU item note analysis codes</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Filter Section -->
      <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Group</label>
            <select v-model="filters.analysisGroupId" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="">All Groups</option>
              <option v-for="group in analysisGroups" :key="group.id" :value="group.id">
                {{ group.group_code }} - {{ group.group_name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select v-model="filters.status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="">All Status</option>
              <option value="active">Active Only</option>
              <option value="inactive">Inactive Only</option>
            </select>
          </div>
        </div>
        <div class="mt-4 flex gap-4">
          <button @click="loadData" class="btn-primary">
            <i class="fas fa-filter mr-2"></i> Apply Filters
          </button>
          <button @click="printReport" class="btn-secondary">
            <i class="fas fa-print mr-2"></i> Print Report
          </button>
        </div>
      </div>

      <!-- Print Preview -->
      <div id="printArea" class="border border-gray-200 p-6 bg-white">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900">SKU Item Note Analysis Codes Report</h1>
          <p class="text-sm text-gray-600 mt-2">Generated on {{ new Date().toLocaleDateString() }}</p>
        </div>

        <!-- Summary Section -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ totalCodes }}</div>
            <div class="text-sm text-blue-800">Total Codes</div>
          </div>
          <div class="bg-green-50 p-4 rounded-lg border border-green-200 text-center">
            <div class="text-2xl font-bold text-green-600">{{ activeCodes }}</div>
            <div class="text-sm text-green-800">Active</div>
          </div>
          <div class="bg-red-50 p-4 rounded-lg border border-red-200 text-center">
            <div class="text-2xl font-bold text-red-600">{{ inactiveCodes }}</div>
            <div class="text-sm text-red-800">Inactive</div>
          </div>
          <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ uniqueGroups }}</div>
            <div class="text-sm text-purple-800">Groups Used</div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-100">
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Analysis Code</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Code Name</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Analysis Group</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Category</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Description</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="border border-gray-300 px-3 py-4 text-center">
                  <div class="flex justify-center items-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Loading data...
                  </div>
                </td>
              </tr>
              <tr v-else-if="analysisCodes.length === 0">
                <td colspan="6" class="border border-gray-300 px-3 py-4 text-center text-gray-500">
                  No data found with current filters
                </td>
              </tr>
              <tr v-for="code in analysisCodes" :key="code.id" class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-2 font-mono font-medium">{{ code.analysis_code }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ code.code_name }}</td>
                <td class="border border-gray-300 px-3 py-2">
                  {{ code.analysis_group ? `${code.analysis_group.group_code} - ${code.analysis_group.group_name}` : 'N/A' }}
                </td>
                <td class="border border-gray-300 px-3 py-2">
                  {{ code.analysis_group ? code.analysis_group.category || 'N/A' : 'N/A' }}
                </td>
                <td class="border border-gray-300 px-3 py-2">{{ code.description || '-' }}</td>
                <td class="border border-gray-300 px-3 py-2">
                  <span :class="code.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                    {{ code.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-6 text-center text-xs text-gray-500">
          <p>Report generated by ERP System - Material Management Module</p>
          <p>Total records: {{ analysisCodes.length }}</p>
          <p v-if="filters.analysisGroupId || filters.status">
            Filters applied: 
            <span v-if="filters.analysisGroupId">Analysis Group: {{ getGroupName(filters.analysisGroupId) }}</span>
            <span v-if="filters.analysisGroupId && filters.status"> | </span>
            <span v-if="filters.status">Status: {{ filters.status.charAt(0).toUpperCase() + filters.status.slice(1) }}</span>
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const analysisCodes = ref([])
const analysisGroups = ref([])
const loading = ref(false)

// Filters
const filters = ref({
  analysisGroupId: '',
  status: ''
})

// Computed properties
const totalCodes = computed(() => analysisCodes.value.length)
const activeCodes = computed(() => analysisCodes.value.filter(code => code.is_active).length)
const inactiveCodes = computed(() => analysisCodes.value.filter(code => !code.is_active).length)
const uniqueGroups = computed(() => {
  const groups = new Set(analysisCodes.value.map(code => code.analysis_group_id).filter(id => id))
  return groups.size
})

// Methods
const loadData = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (filters.value.analysisGroupId) params.append('analysis_group_id', filters.value.analysisGroupId)
    if (filters.value.status) params.append('status', filters.value.status)
    
    const response = await fetch(`/api/sku-item-note-analysis-codes/for-print?${params}`)
    const data = await response.json()
    analysisCodes.value = data
  } catch (err) {
    error('Failed to load analysis codes data')
  } finally {
    loading.value = false
  }
}

const fetchAnalysisGroups = async () => {
  try {
    const response = await fetch('/api/sku-item-note-analysis-codes/groups')
    const data = await response.json()
    analysisGroups.value = data
  } catch (err) {
    error('Failed to load analysis groups')
  }
}

const getGroupName = (groupId) => {
  const group = analysisGroups.value.find(g => g.id == groupId)
  return group ? `${group.group_code} - ${group.group_name}` : 'Unknown Group'
}

const printReport = () => {
  const printContent = document.getElementById('printArea').innerHTML
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Item Note Analysis Codes Report</title>
        <style>
          body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            color: #333;
          }
          table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
          }
          th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
          }
          th { 
            background-color: #f2f2f2; 
            font-weight: bold;
          }
          .text-center { text-align: center; }
          .font-bold { font-weight: bold; }
          .text-gray-900 { color: #111827; }
          .text-gray-600 { color: #4b5563; }
          .text-green-600 { color: #16a34a; }
          .text-red-600 { color: #dc2626; }
          .text-blue-600 { color: #2563eb; }
          .text-purple-600 { color: #9333ea; }
          .bg-blue-50 { background-color: #eff6ff; }
          .bg-green-50 { background-color: #f0fdf4; }
          .bg-red-50 { background-color: #fef2f2; }
          .bg-purple-50 { background-color: #faf5ff; }
          .border { border: 1px solid #d1d5db; }
          .border-blue-200 { border-color: #bfdbfe; }
          .border-green-200 { border-color: #bbf7d0; }
          .border-red-200 { border-color: #fecaca; }
          .border-purple-200 { border-color: #e9d5ff; }
          .rounded-lg { border-radius: 0.5rem; }
          .p-4 { padding: 1rem; }
          .mb-6 { margin-bottom: 1.5rem; }
          .grid { display: grid; }
          .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
          .gap-4 { gap: 1rem; }
          .font-mono { font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace; }
          @media print {
            body { margin: 0; }
            .no-print { display: none; }
          }
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
  fetchAnalysisGroups()
  loadData()
})
</script>

<style scoped>
.btn-primary {
  @apply bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
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