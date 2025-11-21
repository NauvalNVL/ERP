<template>
  <AppLayout :header="'View & Print Tax Type'">
    <Head title="View & Print Tax Type" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i>
        View & Print Tax Type
      </h2>
      <p class="text-cyan-100">Preview and print tax type master data for invoicing.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Actions Bar -->
      <div class="flex flex-wrap items-center justify-between mb-6">
        <div class="flex items-center space-x-2 mb-3 sm:mb-0">
          <button
            @click="printTable"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2"
          >
            <i class="fas fa-file-pdf mr-2"></i>
            <span>Print PDF</span>
          </button>
          <Link
            href="/warehouse-management/invoice/setup/define-tax-type"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-2"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Define Tax Type</span>
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
            placeholder="Search tax code, name, apply, custom type..."
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
            class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 text-white py-4 px-6 flex items-center"
          >
            <div class="flex items-center">
              <div class="mr-4">
                <i class="fas fa-percent text-3xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">TAX TYPE LIST</h2>
                <p class="text-sm opacity-80">View and print sales tax type master data</p>
              </div>
            </div>
          </div>

          <!-- Table Content -->
          <table class="min-w-full border-collapse">
            <thead class="bg-blue-100">
              <tr>
                <th
                  @click="sortTable('code')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Tax Code
                  <i :class="getSortIcon('code')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('name')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Tax Name
                  <i :class="getSortIcon('name')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('apply')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Apply
                  <i :class="getSortIcon('apply')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('rate')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Tax Rate %
                  <i :class="getSortIcon('rate')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('custom_type')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Custom Type
                  <i :class="getSortIcon('custom_type')" class="text-xs ml-1"></i>
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
                  colspan="6"
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  <div class="flex justify-center">
                    <div
                      class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"
                    ></div>
                  </div>
                  <p class="mt-2">Loading tax types...</p>
                </td>
              </tr>
              <tr v-else-if="filteredTaxTypes.length === 0">
                <td
                  colspan="6"
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  No tax types found.
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
                v-for="(tax, index) in filteredTaxTypes"
                :key="tax.code"
                :class="index % 2 === 0 ? 'bg-blue-50' : 'bg-white'"
                class="hover:bg-blue-100"
              >
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm font-medium text-gray-900">
                    {{ tax.code || 'N/A' }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm text-gray-900">
                    {{ tax.name || 'N/A' }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                    :class="tax.apply === 'Y'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'"
                  >
                    {{ tax.apply === 'Y' ? 'Y-Yes' : 'N-No' }}
                  </span>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm text-gray-900">
                    {{ Number(tax.rate ?? 0).toFixed(2) }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <div class="text-sm text-gray-900">
                    {{ tax.custom_type || 'N-NIL' }}
                  </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                    :class="tax.status === 'A'
                      ? 'bg-emerald-100 text-emerald-800'
                      : 'bg-gray-200 text-gray-700'"
                  >
                    {{ tax.status === 'A' ? 'A-Active' : 'O-Obsolete' }}
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
              <div>Total Tax Types: {{ filteredTaxTypes.length }}</div>
              <div v-if="searchQuery">
                Filtered from {{ taxTypes.length }} total records
              </div>
              <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Print Instructions -->
      <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
        <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
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

const taxTypes = ref([])
const loading = ref(true)
const searchQuery = ref('')
const sortColumn = ref('code')
const sortDirection = ref('asc')
const currentDate = new Date().toLocaleString()

onMounted(async () => {
  await fetchTaxTypes()
  loading.value = false
})

const fetchTaxTypes = async () => {
  try {
    const response = await fetch('/api/invoices/tax-types', {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    if (!response.ok) {
      throw new Error('Failed to fetch tax types')
    }

    const data = await response.json()

    if (Array.isArray(data)) {
      taxTypes.value = data
    } else if (Array.isArray(data.data)) {
      taxTypes.value = data.data
    } else {
      taxTypes.value = []
      console.error('Unexpected data format for tax types:', data)
    }
  } catch (error) {
    console.error('Error fetching tax types:', error)
    taxTypes.value = []
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

const filteredTaxTypes = computed(() => {
  let filtered = [...taxTypes.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(tx => {
      const code = (tx.code || '').toString().toLowerCase()
      const name = (tx.name || '').toString().toLowerCase()
      const apply = (tx.apply || '').toString().toLowerCase()
      const customType = (tx.custom_type || '').toString().toLowerCase()
      const status = (tx.status || '').toString().toLowerCase()
      return (
        code.includes(query) ||
        name.includes(query) ||
        apply.includes(query) ||
        customType.includes(query) ||
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
    doc.text('TAX TYPE LIST', 10, 15)

    doc.setFontSize(10)
    doc.setTextColor(100)
    doc.text('View and print tax type master data', 10, 22)

    const tableData = filteredTaxTypes.value.map(tx => [
      tx.code || 'N/A',
      tx.name || 'N/A',
      tx.apply === 'Y' ? 'Y-Yes' : 'N-No',
      Number(tx.rate ?? 0).toFixed(2),
      tx.custom_type || 'N-NIL',
      tx.status === 'A' ? 'A-Active' : 'O-Obsolete'
    ])

    autoTable(doc, {
      startY: 28,
      head: [['Tax Code', 'Tax Name', 'Apply', 'Tax Rate %', 'Custom Type', 'Status']],
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
        `Total Tax Types: ${filteredTaxTypes.value.length} | Generated: ${currentDate}`,
        10,
        pageHeight - 10
      )
      doc.text(
        `Page ${i} of ${pageCount}`,
        doc.internal.pageSize.width - 35,
        pageHeight - 10
      )
    }

    doc.save(`tax-type-list-${new Date().getTime()}.pdf`)
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

