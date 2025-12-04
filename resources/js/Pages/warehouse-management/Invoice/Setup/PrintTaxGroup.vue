<template>
  <AppLayout :header="'View & Print Tax Group'">
    <Head title="View & Print Tax Group" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
          <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
              <i class="fas fa-print text-white text-sm"></i>
            </div>
            <div>
              <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                View & Print Tax Group
              </h2>
              <p class="text-xs sm:text-sm text-blue-100">
                Preview and print tax group master data.
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6">
          <!-- Actions Bar -->
          <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <button
                @click="printTable"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center text-sm font-medium"
              >
                <i class="fas fa-file-pdf mr-2"></i>
                <span>Print PDF</span>
              </button>
              <Link
                href="/warehouse-management/invoice/setup/define-tax-group"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center text-sm font-medium"
              >
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Back</span>
              </Link>
            </div>

            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
              </div>
              <input
                type="text"
                v-model="searchQuery"
                class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                placeholder="Search..."
              />
            </div>
          </div>

          <!-- Table Section -->
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <div
              id="printableTable"
              class="min-w-full bg-white overflow-hidden"
            >
              <!-- Table Header Banner -->
              <div
                class="bg-blue-600 text-white py-3 px-4 flex items-center"
              >
                <div class="flex items-center">
                  <div class="mr-3">
                    <i class="fas fa-layer-group text-2xl"></i>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold">TAX GROUP LIST</h2>
                    <p class="text-xs opacity-90">Tax group master data</p>
                  </div>
                </div>
              </div>

              <!-- Table Content -->
              <table class="min-w-full border-collapse">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      @click="sortTable('code')"
                      class="px-4 py-2 text-left font-semibold border border-gray-200 cursor-pointer text-slate-700 text-sm"
                    >
                      Group Code
                      <i :class="getSortIcon('code')" class="text-xs ml-1"></i>
                    </th>
                    <th
                      @click="sortTable('name')"
                      class="px-4 py-2 text-left font-semibold border border-gray-200 cursor-pointer text-slate-700 text-sm"
                    >
                      Group Name
                      <i :class="getSortIcon('name')" class="text-xs ml-1"></i>
                    </th>
                    <th
                      @click="sortTable('sales_tax_applied')"
                      class="px-4 py-2 text-left font-semibold border border-gray-200 cursor-pointer text-slate-700 text-sm"
                    >
                      Sales Tax Applied
                      <i :class="getSortIcon('sales_tax_applied')" class="text-xs ml-1"></i>
                    </th>
                    <th
                      @click="sortTable('status')"
                      class="px-4 py-2 text-left font-semibold border border-gray-200 cursor-pointer text-slate-700 text-sm"
                    >
                      Status
                      <i :class="getSortIcon('status')" class="text-xs ml-1"></i>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <tr v-if="loading">
                    <td
                      colspan="4"
                      class="px-4 py-6 text-center text-slate-500 border border-gray-200"
                    >
                      <div class="flex justify-center">
                        <div
                          class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"
                        ></div>
                      </div>
                      <p class="mt-2 text-sm">Loading...</p>
                    </td>
                  </tr>
                  <tr v-else-if="filteredTaxGroups.length === 0">
                    <td
                      colspan="4"
                      class="px-4 py-6 text-center text-slate-500 border border-gray-200 text-sm"
                    >
                      No tax groups found.
                      <template v-if="searchQuery">
                        <p class="mt-2">
                          No results: "{{ searchQuery }}"
                        </p>
                        <button
                          @click="searchQuery = ''"
                          class="mt-2 text-blue-600 hover:underline text-sm"
                        >
                          Clear
                        </button>
                      </template>
                    </td>
                  </tr>
                  <tr
                    v-for="(group, index) in filteredTaxGroups"
                    :key="group.code"
                    :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    class="hover:bg-blue-50 transition-colors"
                  >
                    <td class="px-4 py-2 border border-gray-200">
                      <div class="text-sm font-medium text-slate-900">
                        {{ group.code || 'N/A' }}
                      </div>
                    </td>
                    <td class="px-4 py-2 border border-gray-200">
                      <div class="text-sm text-slate-900">
                        {{ group.name || 'N/A' }}
                      </div>
                    </td>
                    <td class="px-4 py-2 border border-gray-200">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="group.sales_tax_applied === 'Y'
                          ? 'bg-green-100 text-green-800'
                          : 'bg-gray-200 text-gray-700'"
                      >
                        {{ group.sales_tax_applied === 'Y' ? 'Y-Yes' : 'N-No' }}
                      </span>
                    </td>
                    <td class="px-4 py-2 border border-gray-200">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="group.status === 'A'
                          ? 'bg-green-100 text-green-800'
                          : 'bg-gray-200 text-gray-700'"
                      >
                        {{ group.status === 'A' ? 'A-Active' : 'O-Obsolete' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Table Footer -->
              <div
                class="bg-gray-50 px-4 py-2 border-t border-gray-200 text-xs text-slate-500"
              >
                <div class="flex items-center justify-between">
                  <div>Total: {{ filteredTaxGroups.length }}</div>
                  <div v-if="searchQuery" class="text-xs">
                    Filtered from {{ taxGroups.length }}
                  </div>
                  <div class="text-xs text-slate-400">{{ currentDate }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Print Instructions -->
          <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 flex items-center text-sm">
              <i class="fas fa-info-circle mr-2"></i>
              Instructions
            </h3>
            <ul class="list-disc pl-5 text-xs text-slate-600 space-y-1">
              <li>Click "Print PDF" to download PDF file</li>
              <li>Search or sort data before exporting</li>
              <li>PDF generated in landscape A4 format</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const taxGroups = ref([])
const loading = ref(true)
const searchQuery = ref('')
const sortColumn = ref('code')
const sortDirection = ref('asc')
const currentDate = new Date().toLocaleString()

onMounted(async () => {
  await fetchTaxGroups()
  loading.value = false
})

const fetchTaxGroups = async () => {
  try {
    const response = await fetch('/api/invoices/tax-groups', {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    if (!response.ok) {
      throw new Error('Failed to fetch tax groups')
    }

    const data = await response.json()

    if (Array.isArray(data)) {
      taxGroups.value = data
    } else if (Array.isArray(data.data)) {
      taxGroups.value = data.data
    } else {
      taxGroups.value = []
      console.error('Unexpected data format for tax groups:', data)
    }
  } catch (error) {
    console.error('Error fetching tax groups:', error)
    taxGroups.value = []
  }
}

const sortTable = column => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column
    sortDirection.value = 'asc'
  }
}

const getSortIcon = column => {
  if (sortColumn.value !== column) {
    return 'fas fa-sort text-black'
  }
  return sortDirection.value === 'asc'
    ? 'fas fa-sort-up text-black'
    : 'fas fa-sort-down text-black'
}

const filteredTaxGroups = computed(() => {
  let filtered = [...taxGroups.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(g => {
      const code = (g.code || '').toString().toLowerCase()
      const name = (g.name || '').toString().toLowerCase()
      const salesTax = (g.sales_tax_applied || '').toString().toLowerCase()
      const status = (g.status || '').toString().toLowerCase()
      return (
        code.includes(query) ||
        name.includes(query) ||
        salesTax.includes(query) ||
        status.includes(query)
      )
    })
  }

  filtered.sort((a, b) => {
    let valueA = a[sortColumn.value]
    let valueB = b[sortColumn.value]

    if (valueA === null || valueA === undefined) valueA = ''
    if (valueB === null || valueB === undefined) valueB = ''

    if (typeof valueA !== 'string') valueA = String(valueA || '')
    if (typeof valueB !== 'string') valueB = String(valueB || '')

    valueA = valueA.toLowerCase()
    valueB = valueB.toLowerCase()

    if (sortDirection.value === 'asc') {
      return valueA.localeCompare(valueB)
    }
    return valueB.localeCompare(valueA)
  })

  return filtered
})

const printTable = () => {
  try {
    const doc = new jsPDF({
      orientation: 'landscape',
      unit: 'mm',
      format: 'a4'
    })

    doc.setFontSize(16)
    doc.setTextColor(37, 99, 235)
    doc.text('TAX GROUP LIST', 10, 15)

    doc.setFontSize(10)
    doc.setTextColor(100)
    doc.text('View and print tax group master data', 10, 22)

    const tableData = filteredTaxGroups.value.map(g => [
      g.code || 'N/A',
      g.name || 'N/A',
      g.sales_tax_applied === 'Y' ? 'Y-Yes' : 'N-No',
      g.status === 'A' ? 'A-Active' : 'O-Obsolete'
    ])

    autoTable(doc, {
      startY: 28,
      head: [['Group Code', 'Group Name', 'Sales Tax Applied', 'Status']],
      body: tableData,
      theme: 'grid',
      tableWidth: 'auto',
      headStyles: {
        fillColor: [37, 99, 235],
        textColor: [255, 255, 255],
        fontStyle: 'bold',
        halign: 'left',
        fontSize: 10
      },
      bodyStyles: {
        textColor: [50, 50, 50],
        halign: 'left',
        fontSize: 9
      },
      alternateRowStyles: {
        fillColor: [219, 234, 254]
      },
      margin: { top: 28, left: 10, right: 10 }
    })

    const pageCount = doc.internal.getNumberOfPages()
    const pageHeight = doc.internal.pageSize.height

    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i)
      doc.setFontSize(8)
      doc.setTextColor(100)
      doc.text(
        `Total Tax Groups: ${filteredTaxGroups.value.length} | Generated: ${currentDate}`,
        10,
        pageHeight - 10
      )
      doc.text(
        `Page ${i} of ${pageCount}`,
        doc.internal.pageSize.width - 35,
        pageHeight - 10
      )
    }

    doc.save(`tax-group-list-${new Date().getTime()}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Error generating PDF. Please try again.')
  }
}
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  #printableTable,
  #printableTable * {
    visibility: visible;
  }
  #printableTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>

