<template>
  <AppLayout :header="'View & Print Tax Group'">
    <Head title="View & Print Tax Group" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i>
        View & Print Tax Group
      </h2>
      <p class="text-sky-100">Preview and print tax group master data, including sales tax applied status.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Actions Bar -->
      <div class="flex flex-wrap items-center justify-between mb-6">
        <div class="flex items-center space-x-2 mb-3 sm:mb-0">
          <button
            @click="printTable"
            class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded flex items-center space-x-2"
          >
            <i class="fas fa-file-pdf mr-2"></i>
            <span>Print PDF</span>
          </button>
          <Link
            href="/warehouse-management/invoice/setup/define-tax-group"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-2"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Define Tax Group</span>
          </Link>
        </div>

        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input
            type="text"
            v-model="searchQuery"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search group code, name, sales tax, status..."
          />
        </div>
      </div>

      <!-- Table Section -->
      <div class="overflow-x-auto">
        <div
          id="printableTable"
          class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden"
        >
          <!-- Table Header Banner -->
          <div
            class="bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 text-white py-4 px-6 flex items-center"
          >
            <div class="flex items-center">
              <div class="mr-4">
                <i class="fas fa-layer-group text-3xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">TAX GROUP LIST</h2>
                <p class="text-sm opacity-80">View and print tax group master data</p>
              </div>
            </div>
          </div>

          <!-- Table Content -->
          <table class="min-w-full border-collapse">
            <thead class="bg-sky-100">
              <tr>
                <th
                  @click="sortTable('code')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Group Code
                  <i :class="getSortIcon('code')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('name')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Group Name
                  <i :class="getSortIcon('name')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('sales_tax_applied')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Sales Tax Applied
                  <i :class="getSortIcon('sales_tax_applied')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('status')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
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
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  <div class="flex justify-center">
                    <div
                      class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"
                    ></div>
                  </div>
                  <p class="mt-2">Loading tax groups...</p>
                </td>
              </tr>
              <tr v-else-if="filteredTaxGroups.length === 0">
                <td
                  colspan="4"
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  No tax groups found.
                  <template v-if="searchQuery">
                    <p class="mt-2">
                      No results match your search query:
                      "{{ searchQuery }}"
                    </p>
                    <button
                      @click="searchQuery = ''"
                      class="mt-2 text-blue-500 hover:underline"
                    >
                      Clear search
                    </button>
                  </template>
                </td>
              </tr>
              <tr
                v-for="(group, index) in filteredTaxGroups"
                :key="group.code"
                :class="index % 2 === 0 ? 'bg-sky-50' : 'bg-white'"
                class="hover:bg-sky-100"
              >
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm font-medium text-gray-900">
                    {{ group.code || 'N/A' }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm text-gray-900">
                    {{ group.name || 'N/A' }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                    :class="group.sales_tax_applied === 'Y'
                      ? 'bg-emerald-100 text-emerald-800'
                      : 'bg-gray-200 text-gray-700'"
                  >
                    {{ group.sales_tax_applied === 'Y' ? 'Y-Yes' : 'N-No' }}
                  </span>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
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
            class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500"
          >
            <div class="flex items-center justify-between">
              <div>Total Tax Groups: {{ filteredTaxGroups.length }}</div>
              <div v-if="searchQuery">
                Filtered from {{ taxGroups.length }} total records
              </div>
              <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Print Instructions -->
      <div class="mt-6 bg-sky-50 p-4 rounded-lg border border-sky-100">
        <h3 class="font-semibold text-sky-800 mb-2 flex items-center">
          <i class="fas fa-info-circle mr-2"></i>
          PDF Export Instructions
        </h3>
        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
          <li>Click the "Print PDF" button above to generate and download PDF.</li>
          <li>PDF will be generated in landscape orientation (A4).</li>
          <li>You can search or sort data before exporting.</li>
          <li>PDF includes formatted table with headers and page numbers.</li>
        </ul>
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

