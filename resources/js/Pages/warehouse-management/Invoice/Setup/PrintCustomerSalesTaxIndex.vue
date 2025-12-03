<template>
  <AppLayout :header="'View & Print Customer Sales Tax Index'">
    <Head title="View & Print Customer Sales Tax Index" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i>
        View & Print Customer Sales Tax Index
      </h2>
      <p class="text-cyan-100">Preview and print sales tax indices per customer, based on Define Customer Sales Tax Index.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Actions & Filters -->
      <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center space-x-2">
            <button
              @click="printTable"
              :disabled="indices.length === 0 || !form.customer_code"
              class="bg-emerald-500 hover:bg-emerald-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-2 rounded flex items-center space-x-2"
            >
              <i class="fas fa-file-pdf mr-2"></i>
              <span>Print PDF</span>
            </button>
            <Link
              href="/warehouse-management/invoice/setup/define-customer-sales-tax-index"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-2"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              <span>Back to Define Customer Sales Tax Index</span>
            </Link>
          </div>

          <div class="flex items-center gap-3">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input
                type="text"
                v-model="searchQuery"
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-64"
                placeholder="Search index#, tax group, status..."
              />
            </div>
          </div>
        </div>

        <!-- Customer Selector -->
        <div class="grid grid-cols-1 md:grid-cols-[2fr,3fr,auto] gap-3 items-center">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
            <div class="flex gap-2">
              <input
                v-model="form.customer_code"
                type="text"
                placeholder="Type or select customer code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                @keyup.enter="loadIndices"
              />
              <button
                type="button"
                @click="openCustomerModal"
                class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm flex items-center gap-2"
              >
                <i class="fas fa-table"></i>
                Select
              </button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
            <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-sm text-gray-800 min-h-[40px] flex items-center">
              {{ selectedCustomer?.name || '-' }}
            </div>
          </div>
          <div class="flex items-end">
            <button
              type="button"
              @click="loadIndices"
              :disabled="!form.customer_code"
              class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-md text-sm flex items-center gap-2"
            >
              <i class="fas fa-sync-alt"></i>
              Load Indices
            </button>
          </div>
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
            class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 text-white py-4 px-6 flex items-center justify-between"
          >
            <div class="flex items-center">
              <div class="mr-4">
                <i class="fas fa-user-tag text-3xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">CUSTOMER SALES TAX INDEX</h2>
                <p class="text-sm opacity-80">
                  {{ form.customer_code ? `Customer: ${form.customer_code}` : 'Select a customer to view tax indices' }}
                </p>
              </div>
            </div>
            <div v-if="selectedCustomer" class="text-sm text-cyan-100">
              {{ selectedCustomer.name }}
            </div>
          </div>

          <!-- Table Content -->
          <table class="min-w-full border-collapse">
            <thead class="bg-blue-100">
              <tr>
                <th
                  @click="sortTable('index_number')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Index#
                  <i :class="getSortIcon('index_number')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('tax_group_code')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Tax Group
                  <i :class="getSortIcon('tax_group_code')" class="text-xs ml-1"></i>
                </th>
                <th
                  @click="sortTable('tax_group_name')"
                  class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer text-gray-900"
                >
                  Tax Group Name
                  <i :class="getSortIcon('tax_group_name')" class="text-xs ml-1"></i>
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
                      class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"
                    ></div>
                  </div>
                  <p class="mt-2">Loading customer tax indices...</p>
                </td>
              </tr>
              <tr v-else-if="form.customer_code && filteredIndices.length === 0">
                <td
                  colspan="4"
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  No tax indices found for this customer.
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
              <tr v-else-if="!form.customer_code">
                <td
                  colspan="4"
                  class="px-4 py-3 text-center text-gray-500 border border-gray-300"
                >
                  Please select a customer and click "Load Indices".
                </td>
              </tr>
              <tr
                v-for="(row, index) in filteredIndices"
                :key="`${row.customer_code}-${row.index_number}`"
                :class="index % 2 === 0 ? 'bg-blue-50' : 'bg-white'"
                class="hover:bg-blue-100"
              >
                <td class="px-4 py-2 border border-gray-300 text-sm text-gray-900">
                  {{ row.index_number }}
                </td>
                <td class="px-4 py-2 border border-gray-300 text-sm text-gray-900">
                  {{ row.tax_group_code }}
                </td>
                <td class="px-4 py-2 border border-gray-300 text-sm text-gray-900">
                  {{ row.tax_group_name || '-' }}
                </td>
                <td class="px-4 py-2 border border-gray-300">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                    :class="row.status === 'A'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-gray-200 text-gray-700'"
                  >
                    {{ row.status === 'A' ? 'A-Active' : 'O-Obsolete' }}
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
              <div>
                Total Indices: {{ filteredIndices.length }}
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
          <li>Select a customer and click "Load Indices" to view data.</li>
          <li>Click the "Print PDF" button above to generate and download PDF.</li>
          <li>PDF will be generated in landscape orientation (A4).</li>
          <li>PDF includes formatted table with headers and page numbers.</li>
        </ul>
      </div>
    </div>

    <!-- Customer Account Modal -->
    <CustomerAccountModal
      v-if="showCustomerModal"
      :show="showCustomerModal"
      :customer-accounts="customers"
      :initial-search="customerSearch"
      @close="showCustomerModal = false"
      @select="handleCustomerSelect"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import axios from 'axios'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const form = ref({
  customer_code: ''
})

const selectedCustomer = ref(null)
const indices = ref([])
const loading = ref(false)
const searchQuery = ref('')
const sortColumn = ref('index_number')
const sortDirection = ref('asc')
const currentDate = new Date().toLocaleString()

// Customer modal state
const showCustomerModal = ref(false)
const customers = ref([])
const customerSearch = ref('')

const openCustomerModal = async () => {
  if (customers.value.length === 0) {
    await loadCustomers()
  }
  showCustomerModal.value = true
}

const loadCustomers = async () => {
  try {
    const response = await axios.get('/api/customers-with-status?status=active')
    const data = response.data

    if (Array.isArray(data)) {
      customers.value = data
    } else if (data.data && Array.isArray(data.data)) {
      customers.value = data.data
    }
  } catch (error) {
    console.error('Error loading customers:', error)
    alert('Failed to load customer accounts.')
  }
}

const handleCustomerSelect = account => {
  form.value.customer_code = account.customer_code
  selectedCustomer.value = {
    code: account.customer_code,
    name: account.customer_name
  }
  showCustomerModal.value = false
  loadIndices()
}

const loadIndices = async () => {
  if (!form.value.customer_code) return

  loading.value = true
  indices.value = []

  try {
    const response = await axios.get(
      `/api/invoices/customer-tax-indices/${encodeURIComponent(form.value.customer_code)}`
    )
    const data = response.data

    if (data && data.success && Array.isArray(data.data)) {
      indices.value = data.data.map(row => ({
        customer_code: row.customer_code,
        index_number: row.index_number,
        tax_group_code: row.tax_group_code,
        tax_group_name: row.tax_group?.name || '',
        status: row.status || 'A'
      }))
    } else if (Array.isArray(data)) {
      indices.value = data
    } else {
      indices.value = []
    }
  } catch (error) {
    console.error('Error loading customer tax indices:', error)
    alert('Failed to load customer tax indices.')
  } finally {
    loading.value = false
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

const filteredIndices = computed(() => {
  let filtered = [...indices.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(row => {
      const idx = String(row.index_number ?? '').toLowerCase()
      const group = String(row.tax_group_code ?? '').toLowerCase()
      const groupName = String(row.tax_group_name ?? '').toLowerCase()
      const status = String(row.status ?? '').toLowerCase()
      return (
        idx.includes(query) ||
        group.includes(query) ||
        groupName.includes(query) ||
        status.includes(query)
      )
    })
  }

  filtered.sort((a, b) => {
    let valueA = a[sortColumn.value]
    let valueB = b[sortColumn.value]

    if (valueA === null || valueA === undefined) valueA = ''
    if (valueB === null || valueB === undefined) valueB = ''

    if (typeof valueA !== 'string' && typeof valueA !== 'number') {
      valueA = String(valueA || '')
    }
    if (typeof valueB !== 'string' && typeof valueB !== 'number') {
      valueB = String(valueB || '')
    }

    valueA = String(valueA).toLowerCase()
    valueB = String(valueB).toLowerCase()

    if (sortDirection.value === 'asc') {
      return valueA.localeCompare(valueB)
    }
    return valueB.localeCompare(valueA)
  })

  return filtered
})

const printTable = () => {
  if (!form.value.customer_code || filteredIndices.value.length === 0) {
    alert('Please select a customer and ensure there is data to print.')
    return
  }

  try {
    const doc = new jsPDF({
      orientation: 'landscape',
      unit: 'mm',
      format: 'a4'
    })

    doc.setFontSize(16)
    doc.setTextColor(37, 99, 235)
    doc.text('CUSTOMER SALES TAX INDEX', 10, 15)

    doc.setFontSize(10)
    doc.setTextColor(100)
    const subtitle = selectedCustomer.value
      ? `Customer: ${form.value.customer_code} - ${selectedCustomer.value.name}`
      : `Customer: ${form.value.customer_code}`
    doc.text(subtitle, 10, 22)

    const tableData = filteredIndices.value.map(row => [
      row.index_number,
      row.tax_group_code,
      row.tax_group_name || '',
      row.status === 'A' ? 'A-Active' : 'O-Obsolete'
    ])

    autoTable(doc, {
      startY: 28,
      head: [['Index#', 'Tax Group', 'Tax Group Name', 'Status']],
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
        `Customer: ${form.value.customer_code} | Total Indices: ${filteredIndices.value.length} | Generated: ${currentDate}`,
        10,
        pageHeight - 10
      )
      doc.text(
        `Page ${i} of ${pageCount}`,
        doc.internal.pageSize.width - 35,
        pageHeight - 10
      )
    }

    doc.save(
      `customer-sales-tax-index-${form.value.customer_code}-${new Date().getTime()}.pdf`
    )
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

