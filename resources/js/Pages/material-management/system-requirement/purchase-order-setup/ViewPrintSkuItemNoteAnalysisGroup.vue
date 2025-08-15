<template>
  <AppLayout :header="'View & Print SKU Item Note Analysis Group'">
    <Head title="View & Print SKU Item Note Analysis Group" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print SKU Item Note Analysis Group
      </h2>
      <p class="text-teal-100">Generate and print reports for SKU item note analysis groups</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Filter Section -->
      <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select v-model="filters.category" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select v-model="filters.status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
              <option value="">All Status</option>
              <option value="active">Active Only</option>
              <option value="inactive">Inactive Only</option>
            </select>
          </div>
          <div class="flex items-end">
            <button @click="loadData" class="btn-primary w-full">
              <i class="fas fa-filter mr-2"></i> Apply Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mb-6 flex gap-4">
        <button @click="printReport" class="btn-primary">
          <i class="fas fa-print mr-2"></i> Print Report
        </button>
        <button @click="exportData" class="btn-secondary">
          <i class="fas fa-download mr-2"></i> Export Data
        </button>
        <button @click="refreshData" class="btn-secondary">
          <i class="fas fa-sync-alt mr-2"></i> Refresh
        </button>
      </div>

      <!-- Print Preview -->
      <div id="printArea" class="border border-gray-200 p-6 bg-white">
        <!-- Report Header -->
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900">SKU Item Note Analysis Groups Report</h1>
          <p class="text-sm text-gray-600 mt-2">Generated on {{ new Date().toLocaleDateString() }}</p>
          <div v-if="filters.category || filters.status" class="mt-2 text-sm text-gray-500">
            <span v-if="filters.category">Category: {{ filters.category }}</span>
            <span v-if="filters.category && filters.status"> | </span>
            <span v-if="filters.status">Status: {{ filters.status === 'active' ? 'Active Only' : filters.status === 'inactive' ? 'Inactive Only' : 'All' }}</span>
          </div>
        </div>

        <!-- Summary Statistics -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 p-3 rounded border">
            <div class="text-center">
              <div class="text-2xl font-bold text-blue-600">{{ totalGroups }}</div>
              <div class="text-sm text-blue-800">Total Groups</div>
            </div>
          </div>
          <div class="bg-green-50 p-3 rounded border">
            <div class="text-center">
              <div class="text-2xl font-bold text-green-600">{{ activeGroups }}</div>
              <div class="text-sm text-green-800">Active Groups</div>
            </div>
          </div>
          <div class="bg-red-50 p-3 rounded border">
            <div class="text-center">
              <div class="text-2xl font-bold text-red-600">{{ inactiveGroups }}</div>
              <div class="text-sm text-red-800">Inactive Groups</div>
            </div>
          </div>
          <div class="bg-purple-50 p-3 rounded border">
            <div class="text-center">
              <div class="text-2xl font-bold text-purple-600">{{ uniqueCategories }}</div>
              <div class="text-sm text-purple-800">Categories</div>
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-100">
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Group Code</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Group Name</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Category</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Description</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Status</th>
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Created</th>
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
              <tr v-else-if="analysisGroups.length === 0">
                <td colspan="6" class="border border-gray-300 px-3 py-4 text-center text-gray-500">
                  No data found with current filters
                </td>
              </tr>
              <tr v-for="group in analysisGroups" :key="group.id" class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-2 font-medium">{{ group.group_code }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ group.group_name }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ group.category || '-' }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ group.description || '-' }}</td>
                <td class="border border-gray-300 px-3 py-2">
                  <span :class="group.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                    {{ group.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="border border-gray-300 px-3 py-2">{{ formatDate(group.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Report Footer -->
        <div class="mt-6 text-center text-xs text-gray-500">
          <p>Report generated by ERP System - Material Management Module</p>
          <p>Total records: {{ analysisGroups.length }}</p>
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
const analysisGroups = ref([])
const categories = ref([])
const loading = ref(false)

// Filters
const filters = ref({
  category: '',
  status: ''
})

// Computed properties
const totalGroups = computed(() => analysisGroups.value.length)
const activeGroups = computed(() => analysisGroups.value.filter(g => g.is_active).length)
const inactiveGroups = computed(() => analysisGroups.value.filter(g => !g.is_active).length)
const uniqueCategories = computed(() => {
  const cats = new Set(analysisGroups.value.map(g => g.category).filter(c => c))
  return cats.size
})

// Methods
const loadData = async () => {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (filters.value.category) params.append('category', filters.value.category)
    if (filters.value.status) params.append('status', filters.value.status)
    
    const response = await fetch(`/api/sku-item-note-analysis-groups/for-print?${params}`)
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
  loadData()
  loadCategories()
}

const printReport = () => {
  const printContent = document.getElementById('printArea').innerHTML
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Item Note Analysis Groups Report</title>
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
          .text-2xl { font-size: 1.5rem; }
          .text-lg { font-size: 1.125rem; }
          .font-bold { font-weight: bold; }
          .text-gray-900 { color: #111827; }
          .text-gray-600 { color: #4b5563; }
          .text-gray-500 { color: #6b7280; }
          .mb-6 { margin-bottom: 1.5rem; }
          .mt-6 { margin-top: 1.5rem; }
          .grid { display: grid; }
          .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
          .gap-4 { gap: 1rem; }
          .p-3 { padding: 0.75rem; }
          .rounded { border-radius: 0.375rem; }
          .border { border: 1px solid #d1d5db; }
          .bg-blue-50 { background-color: #eff6ff; }
          .bg-green-50 { background-color: #f0fdf4; }
          .bg-red-50 { background-color: #fef2f2; }
          .bg-purple-50 { background-color: #faf5ff; }
          .text-blue-600 { color: #2563eb; }
          .text-green-600 { color: #16a34a; }
          .text-red-600 { color: #dc2626; }
          .text-purple-600 { color: #9333ea; }
          .text-blue-800 { color: #1e40af; }
          .text-green-800 { color: #166534; }
          .text-red-800 { color: #991b1b; }
          .text-purple-800 { color: #6b21a8; }
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

const exportData = () => {
  if (analysisGroups.value.length === 0) {
    error('No data to export')
    return
  }

  const csvContent = [
    ['Group Code', 'Group Name', 'Category', 'Description', 'Status', 'Created'],
    ...analysisGroups.value.map(group => [
      group.group_code,
      group.group_name,
      group.category || '',
      group.description || '',
      group.is_active ? 'Active' : 'Inactive',
      formatDate(group.created_at)
    ])
  ].map(row => row.map(cell => `"${cell}"`).join(',')).join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `sku-item-note-analysis-groups-${new Date().toISOString().split('T')[0]}.csv`
  link.click()
  window.URL.revokeObjectURL(url)
  success('Data exported successfully')
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Lifecycle
onMounted(() => {
  refreshData()
})
</script>

<style scoped>
.btn-primary {
  @apply bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center;
}

.btn-secondary {
  @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center;
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