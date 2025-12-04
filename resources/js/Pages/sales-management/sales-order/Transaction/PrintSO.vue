<template>
  <AppLayout header="Print SO">
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8" v-page-transition>
      <div class="max-w-6xl mx-auto">
        <!-- Header Section -->

        <!-- Main Card -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
          <!-- Card Header -->
          <div class="bg-blue-600 px-4 py-3 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
                  <i class="fa-solid fa-print text-white"></i>
                </div>
            <div>
                  <h2 class="text-lg sm:text-xl font-semibold text-white leading-tight">Print Configuration</h2>
                  <p class="text-xs sm:text-sm text-blue-100">Configure your sales order printing parameters</p>
            </div>
          </div>
          <button
            @click="resetForm"
                class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg bg-white text-blue-600 shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
          >
                <i class="fa-solid fa-rotate-left mr-2"></i>
                Reset Form
          </button>
            </div>
        </div>

          <!-- Card Content -->
          <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
              <!-- Left Column -->
              <div class="space-y-6">
                <!-- Current Period Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-calendar-days text-blue-500"></i>
                    <h3 class="modern-section-title">Current Period</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="flex items-center gap-3">
                      <div class="flex-1">
                        <label class="modern-label">Month</label>
                        <input
                          v-model.number="form.period.month"
                          type="number"
                          min="1"
                          max="12"
                          class="modern-input text-center"
                          placeholder="MM"
                        />
                      </div>
                      <div class="flex-1">
                        <label class="modern-label">Year</label>
                        <input
                          v-model.number="form.period.year"
                          type="number"
                          min="2000"
                          max="2099"
                          class="modern-input text-center"
                          placeholder="YYYY"
                        />
                      </div>
                    </div>
              </div>
            </div>

                <!-- SO Range Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-list-ol text-green-500"></i>
                    <h3 class="modern-section-title">Sales Order Range</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-4">
                      <!-- From Range -->
                      <div>
                        <label class="modern-label">From S/Order#</label>
                <div class="flex items-center gap-2">
                          <input v-model.number="form.from.month" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                            <input v-model.number="form.from.year" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                            <span class="text-gray-400">/</span>
                            <input v-model="form.from.seq" type="text" class="modern-input w-24" placeholder="Seq" />
                            <button
                              class="modern-icon-btn"
                              title="Lookup"
                              @click="openSalesOrderLookup('from')"
                            >
                    <i class="fa-solid fa-magnifying-glass"></i>
                  </button>
                        </div>
                      </div>

                      <!-- To Range -->
                      <div>
                        <label class="modern-label">To S/Order#</label>
                        <div class="flex items-center gap-2">
                          <input v-model.number="form.to.month" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                            <input v-model.number="form.to.year" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                            <span class="text-gray-400">/</span>
                            <input v-model="form.to.seq" type="text" class="modern-input w-24" placeholder="Seq" />
                            <button
                              class="modern-icon-btn"
                              title="Lookup"
                              @click="openSalesOrderLookup('to')"
                            >
                    <i class="fa-solid fa-magnifying-glass"></i>
                  </button>
                </div>
                      </div>
                </div>
              </div>
            </div>

                <!-- Quick Print Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-bolt text-yellow-500"></i>
                    <h3 class="modern-section-title">Quick Print</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="flex gap-2">
                      <input
                        v-model="quick.so"
                        class="modern-input flex-1"
                        placeholder="Enter SO Number (e.g. SO20250001)"
                      />
                      <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700" @click="quickPrint">
                        <i class="fa-solid fa-bolt mr-2"></i>Quick Print
                      </button>
            </div>
              </div>
            </div>
          </div>

              <!-- Right Column -->
              <div class="space-y-6">
                <!-- Print Settings Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-cog text-purple-500"></i>
                    <h3 class="modern-section-title">Print Settings</h3>
                  </div>
                  <div class="modern-section-content">
                    <div>
                      <label class="modern-label">Number of Copies</label>
                      <select v-model.number="form.copies" class="modern-select">
                        <option v-for="n in 9" :key="n" :value="n">{{ n }} {{ n === 1 ? 'Copy' : 'Copies' }}</option>
                      </select>
                    </div>
          </div>
        </div>

                <!-- Order Status Filter Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-filter text-orange-500"></i>
                    <h3 class="modern-section-title">Order Status Filter</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="grid grid-cols-1 gap-3">
                      <label class="modern-checkbox">
                        <input type="checkbox" v-model="form.status.outstanding" />
                        <span class="checkmark"></span>
                        <span class="label-text">Outstanding</span>
                        <span class="status-badge outstanding">Active</span>
                      </label>
                      <label class="modern-checkbox">
                        <input type="checkbox" v-model="form.status.partial" />
                        <span class="checkmark"></span>
                        <span class="label-text">Partial Completed</span>
                        <span class="status-badge partial">Partial</span>
                      </label>
                      <label class="modern-checkbox">
                        <input type="checkbox" v-model="form.status.closed" />
                        <span class="checkmark"></span>
                        <span class="label-text">Closed</span>
                        <span class="status-badge closed">Closed</span>
                      </label>
                      <label class="modern-checkbox">
                        <input type="checkbox" v-model="form.status.completed" />
                        <span class="checkmark"></span>
                        <span class="label-text">Completed</span>
                        <span class="status-badge completed">Done</span>
                      </label>
                      <label class="modern-checkbox">
                        <input type="checkbox" v-model="form.status.cancelled" />
                        <span class="checkmark"></span>
                        <span class="label-text">Cancelled</span>
                        <span class="status-badge cancelled">Cancelled</span>
                      </label>
            </div>
          </div>
              </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-center">
              <button class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1" @click="openPrinter()">
                <i class="fa-solid fa-print mr-2"></i>
                Proceed to Print
              </button>
            </div>
          </div>
          </div>

        <!-- Preview Section -->
        <div v-if="preview" class="mt-6 bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
          <div class="bg-blue-600 px-4 py-3 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="h-7 w-7 rounded-full bg-blue-500 flex items-center justify-center">
                  <i class="fa-solid fa-eye text-white text-sm"></i>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-white">Preview Report</h3>
                  <p class="text-xs text-blue-100">Review your sales order report before printing</p>
            </div>
          </div>
              <div class="flex gap-2">
                <button class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-white text-blue-600 shadow-sm hover:bg-blue-50" @click="downloadPdf">
                  <i class="fa-solid fa-file-pdf mr-2"></i> PDF
                </button>
                <button class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-white text-blue-600 shadow-sm hover:bg-blue-50" @click="downloadExcel">
                  <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </button>
          </div>
        </div>
      </div>
          <div class="p-4 sm:p-6">
            <div class="font-mono text-xs whitespace-pre leading-5 bg-gray-50 p-4 rounded-lg border border-gray-200" style="font-family: 'Courier New', monospace;">
              {{ previewText }}
        </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sales Order Lookup Modal -->
    <SalesOrderLookupModal
      :is-open="salesOrderModal.open"
      :search-params="salesOrderModal.searchParams"
      @close="closeSalesOrderModal"
      @select="onSalesOrderSelect"
    />

    <!-- Printer Modal -->
    <div v-if="printer.open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="printer.open = false"></div>
      <div class="relative bg-white w-full max-w-md rounded-xl shadow-lg border border-gray-200">
        <!-- Modal Header -->
        <div class="bg-blue-600 px-4 py-3 rounded-t-xl">
          <div class="flex items-center gap-3">
            <div class="h-7 w-7 rounded-full bg-blue-500 flex items-center justify-center">
              <i class="fa-solid fa-print text-white text-sm"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-white">Printer Selection</h3>
              <p class="text-xs text-blue-100">Configure your printing preferences</p>
            </div>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-4 sm:p-6 space-y-4">
          <div>
            <label class="modern-label">Printer Code</label>
            <input
              v-model="printer.code"
              class="modern-input w-full"
              placeholder="e.g. HPL-001"
            />
            <p class="text-xs text-gray-500 mt-1">Enter the printer identifier for this print job</p>
          </div>
          <div>
            <label class="modern-label">User ID</label>
            <input
              v-model="printer.user"
              class="modern-input w-full bg-gray-100"
              disabled
            />
            <p class="text-xs text-gray-500 mt-1">Current user identifier (read-only)</p>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-4 py-3 sm:px-6 bg-gray-50 rounded-b-xl flex justify-end gap-2">
          <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-white text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-50" @click="printer.open = false">
            <i class="fa-solid fa-times mr-2"></i>Cancel
          </button>
          <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700" @click="proceedPrint">
            <i class="fa-solid fa-check mr-2"></i>Proceed
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import axios from 'axios'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import SalesOrderLookupModal from '@/Components/SalesOrderLookupModal.vue'

const now = new Date()
const form = reactive({
  period: { month: now.getMonth() + 1, year: now.getFullYear() },
  from: { month: now.getMonth() + 1, year: now.getFullYear(), seq: '' },
  to: { month: now.getMonth() + 1, year: now.getFullYear(), seq: '' },
  copies: 1,
  status: { outstanding: true, partial: true, closed: true, completed: true, cancelled: false },
})

const printer = reactive({ open: false, code: 'HPL-001', user: 'user2' })
const preview = ref(false)
const previewText = ref('')
const quick = reactive({ so: '' })

// Current user info
const currentUser = ref({
  user_id: '',
  official_name: '',
  username: ''
})

// Fetch current user info on component mount
const fetchCurrentUser = async () => {
  try {
    console.log('Fetching current user...')
    const response = await axios.get('/api/user/current', {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })
    console.log('User API response:', response.data)

    if (response.data.success) {
      currentUser.value = response.data.data
      console.log('✅ Current user loaded successfully:', currentUser.value)
      console.log('  - user_id:', currentUser.value.user_id)
      console.log('  - official_name:', currentUser.value.official_name)
      console.log('  - username:', currentUser.value.username)
    } else {
      console.warn('❌ Failed to fetch current user:', response.data.message)
      currentUser.value = {
        user_id: 'guest',
        official_name: 'Guest User',
        username: 'guest'
      }
    }
  } catch (error) {
    console.error('❌ Error fetching current user:', error)
    console.error('Error details:', error.response?.data)
    // Fallback to default if user not authenticated
    currentUser.value = {
      user_id: 'guest',
      official_name: 'Guest User',
      username: 'guest'
    }
  }
}

// Call on component mount using Vue lifecycle hook
onMounted(() => {
  fetchCurrentUser()
})

// Sales Order Lookup Modal
const salesOrderModal = reactive({
  open: false,
  type: 'from', // 'from' or 'to'
  searchParams: {}
})

function resetForm() {
  form.copies = 1
  form.status = { outstanding: true, partial: true, closed: true, completed: true, cancelled: false }
  form.from.seq = ''
  form.to.seq = ''
}

function openPrinter() {
  printer.open = true
}

// Sales Order Lookup functions
function openSalesOrderLookup(type) {
  salesOrderModal.type = type
  salesOrderModal.searchParams = type === 'from' ? form.from : form.to
  salesOrderModal.open = true
}

function onSalesOrderSelect(selectedSo) {
  // Parse SO number format: MM-YYYY-SEQ (e.g., "06-2025-02264")
  const soParts = selectedSo.so_number.split('-')

  if (soParts.length === 3) {
    const month = parseInt(soParts[0])
    const year = parseInt(soParts[1])
    const seq = soParts[2]

    if (salesOrderModal.type === 'from') {
      form.from.month = month
      form.from.year = year
      form.from.seq = seq
    } else {
      form.to.month = month
      form.to.year = year
      form.to.seq = seq
    }
  } else {
    // Fallback: try to extract from different formats
    console.warn('Unexpected SO number format:', selectedSo.so_number)
    if (salesOrderModal.type === 'from') {
      form.from.seq = selectedSo.so_number
    } else {
      form.to.seq = selectedSo.so_number
    }
  }

  closeSalesOrderModal()
}

function closeSalesOrderModal() {
  salesOrderModal.open = false
}

async function proceedPrint() {
  printer.open = false

  try {
    // Format SO numbers properly
    const fromSO = form.from.seq
      ? formatSO(form.from.month, form.from.year, form.from.seq)
      : formatSO(form.from.month, form.from.year, '00001')

    const toSO = form.to.seq
      ? formatSO(form.to.month, form.to.year, form.to.seq)
      : formatSO(form.to.month, form.to.year, '99999')

    // Compose payload similar to desktop filters
    const payload = {
      period: `${form.period.month} ${form.period.year}`,
      range: {
        from: fromSO,
        to: toSO,
      },
      copies: form.copies,
      status: Object.keys(form.status).filter(k => form.status[k]),
      printer: { code: printer.code, user: printer.user },
    }

    console.log('Proceed print payload:', payload)

    const res = await fetch('/api/so-report', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(payload),
    })

    const json = await res.json()
    console.log('SO report response:', json)

    preview.value = true
    previewText.value = await formatPreview(json)
  } catch (error) {
    console.error('Error in proceedPrint:', error)
    preview.value = true
    previewText.value = 'Error generating report: ' + (error.message || error)
  }
}

async function formatPreview(data) {
  const lines = []

  // Header Section
  lines.push('PT. MULTIBOX INDAH')
  lines.push('SALES ORDER REPORT')
  lines.push(''.padEnd(80, '-'))

  // Report Information
  const fromRange = form.from.seq
    ? formatSO(form.from.month, form.from.year, form.from.seq)
    : formatSO(form.from.month, form.from.year, '00001')

  const toRange = form.to.seq
    ? formatSO(form.to.month, form.to.year, form.to.seq)
    : formatSO(form.to.month, form.to.year, '99999')

  lines.push(`Period: ${form.period.month}/${form.period.year}`)
  lines.push(`Range: ${fromRange} to ${toRange}`)
  lines.push(`Status: ${Object.keys(form.status).filter(k => form.status[k]).join(', ').toUpperCase()}`)
  lines.push(`Printer: ${printer.code} | User: ${printer.user}`)
  lines.push('')

  // Use data from API response if available, otherwise fetch fresh data
  let salesOrdersData
  if (data && data.success && data.data && data.data.sales_orders) {
    // Use data from API response
    const salesOrders = data.data.sales_orders
    salesOrdersData = [
      ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date']
    ]

    if (salesOrders.length > 0) {
      salesOrders.forEach(order => {
        salesOrdersData.push([
          order.SO_Num || '',
          order.PO_Num || '',
          order.AC_NAME || '',
          order.STS || 'OPEN',
          order.AMOUNT ? `Rp ${Number(order.AMOUNT).toLocaleString('id-ID')}` : 'Rp 0',
          order.SO_DMY || order.PO_DATE || ''
        ])
      })
    } else {
      salesOrdersData.push(['No sales orders found for the selected criteria', '', '', '', '', ''])
    }
  } else {
    // Fallback: fetch fresh data
    salesOrdersData = await fetchSalesOrdersData()
  }

  // Create table header
  const headerRow = salesOrdersData[0]
  lines.push(headerRow.map((col, index) => {
    const widths = [12, 15, 30, 12, 15, 12] // Column widths
    return String(col).padEnd(widths[index])
  }).join(' | '))
  lines.push(''.padEnd(100, '-'))

  // Create table rows
  salesOrdersData.slice(1).forEach(row => {
    lines.push(row.map((col, index) => {
      const widths = [12, 15, 30, 12, 15, 12] // Column widths
      return String(col).padEnd(widths[index])
    }).join(' | '))
  })

  lines.push('')

  // Summary Section
  const dataRows = salesOrdersData.slice(1)
  const totalOrders = dataRows.length

  lines.push('SUMMARY')
  lines.push(`Total Sales Orders: ${totalOrders}`)

  if (data && data.data && data.data.summary) {
    const summary = data.data.summary
    lines.push(`Outstanding: ${summary.outstanding || 0}`)
    lines.push(`Partial: ${summary.partial || 0}`)
    lines.push(`Completed: ${summary.completed || 0}`)
    lines.push(`Closed: ${summary.closed || 0}`)
    lines.push(`Cancelled: ${summary.cancelled || 0}`)
  } else {
    lines.push(`Outstanding: ${dataRows.filter(row => row[3] === 'OPEN').length}`)
    lines.push(`Partial: ${dataRows.filter(row => row[3] === 'PARTIAL').length}`)
    lines.push(`Completed: ${dataRows.filter(row => row[3] === 'COMPLETED').length}`)
  }

  lines.push('')

  // Footer
  lines.push('Generated by ERP System')
  lines.push(`Generated on: ${new Date().toLocaleString()}`)

  return lines.join('\n')
}

async function fetchSalesOrdersData() {
  try {
    // Format SO numbers properly: MM-YYYY-XXXXX
    const fromSO = form.from.seq
      ? formatSO(form.from.month, form.from.year, form.from.seq)
      : formatSO(form.from.month, form.from.year, '00001')

    const toSO = form.to.seq
      ? formatSO(form.to.month, form.to.year, form.to.seq)
      : formatSO(form.to.month, form.to.year, '99999')

    // Get active status filters
    const activeStatuses = Object.keys(form.status).filter(k => form.status[k])

    console.log('Fetching sales orders with params:', {
      month: form.period.month,
      year: form.period.year,
      from_so: fromSO,
      to_so: toSO,
      status: activeStatuses
    })

    const response = await axios.get('/api/sales-orders', {
      params: {
        month: form.period.month,
        year: form.period.year,
        from_so: fromSO,
        to_so: toSO,
        status: activeStatuses
      },
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })

    console.log('API Response:', response.data)

    if (response.data.success) {
      const salesOrders = response.data.data
      console.log('Sales Orders Count:', salesOrders.length)

      const tableData = [
        ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date']
      ]

      if (salesOrders.length === 0) {
        console.warn('No sales orders found matching filters')
        tableData.push(['No sales orders found for the selected criteria', '', '', '', '', ''])
      } else {
        salesOrders.forEach(order => {
          console.log('Processing order:', order)

          const soNumber = order.so_number || order.SO_Num || ''

          // Extra safety: enforce SO range filtering on frontend as well
          if (fromSO && soNumber && soNumber < fromSO) {
            return
          }
          if (toSO && soNumber && soNumber > toSO) {
            return
          }

          tableData.push([
            soNumber,
            order.customer_po_number || order.PO_Num || '',
            order.customer_name || order.AC_NAME || '',
            order.status || order.STS || 'OPEN',
            `Rp ${(order.amount || order.AMOUNT || 0).toLocaleString('id-ID')}`,
            order.po_date || order.SO_DMY || order.PO_DATE || ''
          ])
        })
      }

      console.log('Table Data:', tableData)
      return tableData
    } else {
      console.warn('API returned success=false')
      // Fallback to empty data
      return [
        ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date'],
        ['No data available', '', '', '', '', '']
      ]
    }
  } catch (error) {
    console.error('Error fetching sales orders:', error)
    console.error('Error details:', error.response?.data)
    // Fallback to empty data
    return [
      ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date'],
      ['Error loading data: ' + (error.response?.data?.message || error.message), '', '', '', '', '']
    ]
  }
}

async function downloadPdf() {
  try {
    // Ensure current user is loaded first
    if (!currentUser.value.user_id || currentUser.value.user_id === '') {
      console.log('⚠️ User not loaded yet, fetching...')
      await fetchCurrentUser()
      // Wait a bit to ensure the ref is updated
      await new Promise(resolve => setTimeout(resolve, 200))
    }

    console.log('➡️ Starting PDF generation')
    console.log('  Current user data:', JSON.stringify(currentUser.value, null, 2))
    console.log('  user_id:', currentUser.value.user_id)
    console.log('  official_name:', currentUser.value.official_name)

    // Alert for debugging
    if (!currentUser.value.user_id || currentUser.value.user_id === '' || currentUser.value.user_id === 'guest') {
      alert(`⚠️ WARNING: User data not loaded properly!

user_id: ${currentUser.value.user_id}
official_name: ${currentUser.value.official_name}

Please check browser console for details.`)
    }

    const doc = new jsPDF({ unit: 'pt', format: 'a4', orientation: 'portrait' })

    // Bangun range nomor S/Order berdasarkan input user (sequence) secara inklusif
    const fromSeq = form.from.seq ? parseInt(form.from.seq, 10) : 1
    const toSeq = form.to.seq ? parseInt(form.to.seq, 10) : fromSeq

    if (Number.isNaN(fromSeq) || Number.isNaN(toSeq)) {
      alert('Invalid S/Order range. Please check the sequence numbers.')
      return
    }

    const startSeq = Math.min(fromSeq, toSeq)
    const endSeq = Math.max(fromSeq, toSeq)

    console.log('Generating Sales Order PDF for sequence range:', { startSeq, endSeq })

    let processedCount = 0

    // Proses setiap S/Order di dalam range sequence yang dipilih user
    for (let seq = startSeq; seq <= endSeq; seq++) {
      const soNumber = formatSO(form.from.month, form.from.year, seq)

      console.log(`Processing SO seq ${seq}:`, soNumber)

      // Fetch detailed SO data with better error handling
      try {
        console.log(`Fetching details for SO: ${soNumber}`)
        const response = await fetch(`/api/sales-order/${encodeURIComponent(soNumber)}/delivery-schedules`, {
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })

        // Check if response is ok
        if (!response.ok) {
          console.error(`HTTP Error ${response.status} for SO ${soNumber}`)
          continue
        }

        // Get raw text first to check for BOM
        const rawText = await response.text()
        console.log(`Raw response for ${soNumber} (first 100 chars):`, rawText.substring(0, 100))

        // Remove BOM if present (BOM is \uFEFF)
        const cleanText = rawText.replace(/^\uFEFF/, '').replace(/^\ufeff/, '').trim()

        // Try to parse JSON
        let json
        try {
          json = JSON.parse(cleanText)
        } catch (parseError) {
          console.error(`JSON Parse Error for SO ${soNumber}:`, parseError)
          console.error('Raw text (first 50 chars):', rawText.substring(0, 50).split('').map(c => c.charCodeAt(0).toString(16)).join(' '))
          console.error('Clean text:', cleanText.substring(0, 200))
          // Continue to next SO instead of breaking the entire process
          continue
        }

        console.log(`Response for ${soNumber}:`, json)

        if (!json.success) {
          console.warn(`Failed to fetch SO ${soNumber}:`, json.message)
          continue
        }

        const so = json.data.sales_order
        const schedules = json.data.delivery_schedules || []
        const details = so.details || []

        console.log('SO Data:', so)
        console.log('Details:', details)
        console.log('Schedules:', schedules)

        // Add new page for each SO (except first)
        if (processedCount > 0) {
          doc.addPage()
        }

        // Render SO in format similar to image
        renderSalesOrderPdf(doc, so, details, schedules)
        processedCount++

      } catch (error) {
        console.error(`Error fetching SO ${soNumber}:`, error)
        console.error('Error details:', {
          message: error.message,
          stack: error.stack
        })
        // Continue to next SO instead of crashing
      }
    }

    if (processedCount === 0) {
      alert('No valid sales orders found to generate PDF.')
      return
    }

    console.log(`Processed ${processedCount} sales orders`)

    // Save the PDF
    doc.save(`SalesOrder_${form.period.month}_${form.period.year}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Error generating PDF: ' + error.message)
  }
}

function renderSalesOrderPdf(doc, so, details, schedules) {
  const leftMargin = 50
  const rightMargin = 545
  const pageWidth = 595
  let yPos = 60

  // Debug: Log current user data at PDF render time
  console.log('➡️ renderSalesOrderPdf called')
  console.log('  currentUser.value:', currentUser.value)
  console.log('  user_id:', currentUser.value.user_id)
  console.log('  official_name:', currentUser.value.official_name)
  console.log('  username:', currentUser.value.username)

  // Header
  doc.setFont('courier', 'bold')
  doc.setFontSize(10)
  doc.text('PT. MULTIBOX INDAH', leftMargin, yPos)
  doc.text(`S/ORDER# : ${so.so_number || ''}`, rightMargin, yPos, { align: 'right' })
  yPos += 11

  doc.text('SALES ORDER', leftMargin, yPos)
  doc.text(`S/O DATE : ${formatDate(so.po_date) || ''}`, rightMargin, yPos, { align: 'right' })
  yPos += 11

  // Credit control line
  doc.setFont('courier', 'normal')
  doc.setFontSize(9)
  const creditControl = 'Credit Control: System Approved'
  const creditDate = new Date().toLocaleString('en-GB', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
  // Use dynamic user_id instead of hardcoded 'mkt12'
  const userId = currentUser.value.user_id || 'guest'
  console.log('➡️ PDF Header - Using userId:', userId)
  console.log('  From currentUser.value:', currentUser.value)
  console.log('  currentUser.value.user_id:', currentUser.value.user_id)
  doc.text(`${creditControl}    , ${creditDate}, ${userId}`, leftMargin, yPos)
  doc.text(`PAGE NO : 1`, rightMargin, yPos, { align: 'right' })
  yPos += 3

  // Separator line
  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, rightMargin, yPos)
  yPos += 10

  // Customer section (left side)
  const customerStartY = yPos // Store the starting Y position for alignment
  doc.setFont('courier', 'bold')
  doc.setFontSize(9)
  doc.text('CUSTOMER:', leftMargin, yPos)
  yPos += 10

  doc.setFont('courier', 'normal')
  doc.text(so.customer_name || '', leftMargin, yPos)
  yPos += 10

  // Address - Format to show longer lines with proper breaks
  const address = so.customer_address || ''
  if (address) {
    // Split by comma and format with proper line breaks
    const addressParts = address.split(',').map(s => s.trim()).filter(Boolean)
    let currentLine = ''
    const maxLineWidth = 50 // Reduced from 70 to create more frequent breaks

    addressParts.forEach((part, index) => {
      if (currentLine === '') {
        currentLine = part
      } else if (currentLine.length + part.length + 2 <= maxLineWidth) {
        currentLine += ', ' + part
      } else {
        // Current line is full, write it and start new line
        doc.text(currentLine, leftMargin, yPos)
        yPos += 9
        currentLine = part
      }

      // If this is the last part, write the remaining line
      if (index === addressParts.length - 1 && currentLine) {
        doc.text(currentLine, leftMargin, yPos)
        yPos += 9
      }
    })
  }

  // Deliver To section (right side) - Align with CUSTOMER line
  doc.setFont('courier', 'bold')
  doc.text('DELIVER TO:', 300, customerStartY)
  doc.setFont('courier', 'normal')

  const deliverTo = so.delivery_location || so.customer_name || ''
  doc.text(deliverTo, 300, customerStartY + 10)

  // Delivery address (right side) - Format properly
  const deliveryAddress = so.delivery_address || ''
  if (deliveryAddress) {
    let deliveryYPos = customerStartY + 20
    const deliveryParts = deliveryAddress.split(',').map(s => s.trim()).filter(Boolean)
    let currentDeliveryLine = ''
    const maxDeliveryLineWidth = 50 // Reduced from 70 to create more frequent breaks

    deliveryParts.forEach((part, index) => {
      if (currentDeliveryLine === '') {
        currentDeliveryLine = part
      } else if (currentDeliveryLine.length + part.length + 2 <= maxDeliveryLineWidth) {
        currentDeliveryLine += ', ' + part
      } else {
        // Current line is full, write it and start new line
        doc.text(currentDeliveryLine, 300, deliveryYPos)
        deliveryYPos += 9
        currentDeliveryLine = part
      }

      // If this is the last part, write the remaining line
      if (index === deliveryParts.length - 1 && currentDeliveryLine) {
        doc.text(currentDeliveryLine, 300, deliveryYPos)
        deliveryYPos += 9
      }
    })

    // Update yPos to the maximum of customer or delivery address
    yPos = Math.max(yPos, deliveryYPos) + 5
  } else {
    yPos += 5
  }

  // Contact info
  doc.text(`TEL : ${so.customer_tel || ''}`, leftMargin, yPos)
  doc.text(`TEL : ${so.customer_tel || ''}`, 300, yPos)
  yPos += 9
  doc.text(`FAX : ${so.customer_fax || ''}`, leftMargin, yPos)
  doc.text(`FAX : ${so.customer_fax || ''}`, 300, yPos)
  yPos += 9
  doc.text(`EMAIL :`, leftMargin, yPos)
  doc.text(`EMAIL :`, 300, yPos)
  yPos += 10

  // Line separator
  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, rightMargin, yPos)
  yPos += 10

  // Order details
  doc.text(`P/ORDER      : ${so.customer_po_number || ''}`, leftMargin, yPos)
  doc.text(`P/ORDER DATE : ${formatDate(so.po_date) || ''}`, 300, yPos)
  yPos += 9
  doc.text(`ACCOUNT NO   : ${so.customer_code || ''}`, leftMargin, yPos)
  doc.text(`CURRENCY     : ${so.currency || 'IDR'}`, 300, yPos)
  yPos += 9
  doc.text(`M/CARD SEQ# : ${so.master_card_seq || ''}`, leftMargin, yPos)
  doc.text(`PAYMENT TERM : ${so.credit_terms || '30 DAYS'}`, 300, yPos)
  yPos += 9
  doc.text(`MODEL        : ${so.master_card_model || ''}`, leftMargin, yPos)
  doc.text(`PRINT. BLOCK#: 62`, 300, yPos)
  yPos += 10

  // Line separator
  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, rightMargin, yPos)
  yPos += 9

  // Table header - Adjusted to match data positions exactly
  doc.setFont('courier', 'bold')
  doc.setFontSize(8)
  doc.text('TYPE', leftMargin, yPos)
  doc.text('DESCRIPTION', leftMargin + 35, yPos)
  doc.text('QTY', leftMargin + 315, yPos)         // Align above QTY data
  doc.text('UOM', leftMargin + 360, yPos)         // Align above UOM data
  doc.text('PRICE', leftMargin + 385, yPos)       // Shortened label, align above price data
  doc.text('AMOUNT', rightMargin - 50, yPos)      // Align above amount data
  yPos += 3

  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, rightMargin, yPos)
  yPos += 9

  // Detail rows
  doc.setFont('courier', 'normal')
  doc.setFontSize(8)
  details.forEach((detail, index) => {
    if (index > 0) yPos += 5 // Add spacing between components

    const qty = detail.order_quantity || 0
    const price = detail.unit_price || 0
    const amount = qty * price

    // Use COMP_Num for TYPE field
    const compType = detail.comp_num || 'Main'
    doc.text(compType, leftMargin, yPos)

    // Part number and design info - Moved closer to TYPE column
    const partNo = so.part_number || ''
    const pDesign = detail.item_code || ''
    const perSet = detail.per_set || 1
    doc.text(`PART NO      : ${partNo}`, leftMargin + 35, yPos)
    yPos += 8
    doc.text(`P/DESIGN     : ${pDesign} ( ${perSet} PCS/SET )`, leftMargin + 35, yPos)
    yPos += 8

    // Flute and paper quality
    const flute = detail.flute || ''
    const pq1 = detail.paper_quality_1 || ''
    const pq2 = detail.paper_quality_2 || ''
    const pq3 = detail.paper_quality_3 || ''
    doc.text(`B/QUALITY    : ${pq1}125  /${pq2}125  /${pq3}125  /`, leftMargin + 35, yPos)
    yPos += 8

    // Measurement
    const dims = detail.dimensions || {}
    const measurement = `${dims.int_l || 460} L x  ${dims.int_w || 270} W x  ${dims.int_h || 260} H    (Int)`
    doc.text(`MEASUREMENT  : ${measurement}`, leftMargin + 35, yPos)
    yPos += 8

    // Roll size
    const rollSize = detail.roll_size || '220 cm'
    doc.text(`ROLL SIZE    : ${rollSize}`, leftMargin + 35, yPos)

    // Quantity, UOM, Price, Amount - MAXIMUM spacing to avoid overlap completely
    const qtyText = qty.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
    // Use 0 decimals for price to make it shorter and prevent overlap
    const priceText = price.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
    const amountText = amount.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })

    // Positions: QTY(340→390), UOM(360), PRICE(410→max415), AMOUNT(480→540) = 65px gap!
    doc.text(qtyText, leftMargin + 340, yPos - 24, { align: 'right' })      // QTY right-aligned
    doc.text(detail.uom || 'PCS', leftMargin + 360, yPos - 24)              // UOM left-aligned
    doc.text(priceText, leftMargin + 410, yPos - 24, { align: 'right' })    // PRICE: max width ~50px
    doc.text(amountText, rightMargin - 5, yPos - 24, { align: 'right' })    // AMOUNT: 65px+ gap!

    yPos += 12

    // Additional details
    const grossKg = detail.gross_kg || 0
    const pricePerM2 = detail.price_per_m2 || 0
    doc.text(`Sales Order KG       :  ${grossKg.toFixed(3)}`, leftMargin + 35, yPos)
    yPos += 8
    doc.text(`Price Per M2         :  ${pricePerM2.toFixed(3)}`, leftMargin + 35, yPos)
    yPos += 8
    doc.text(`Exclusive PPn 10%`, leftMargin + 35, yPos)
    yPos += 12
  })

  // Delivery schedule
  doc.setFont('courier', 'bold')
  doc.setFontSize(8)
  doc.text('DELIVERY SCHEDULE :', leftMargin, yPos)
  yPos += 9

  doc.text('DATE', leftMargin, yPos)
  doc.text('MAIN', leftMargin + 85, yPos)
  doc.text('F1', leftMargin + 120, yPos)
  doc.text('F2', leftMargin + 145, yPos)
  doc.text('F3', leftMargin + 170, yPos)
  doc.text('F4', leftMargin + 195, yPos)
  doc.text('F5', leftMargin + 220, yPos)
  doc.text('F6', leftMargin + 245, yPos)
  doc.text('F7', leftMargin + 270, yPos)
  doc.text('F8', leftMargin + 295, yPos)
  doc.text('F9', leftMargin + 320, yPos)
  doc.text('REMARKS', leftMargin + 345, yPos)
  yPos += 9

  doc.setFont('courier', 'normal')
  doc.setFontSize(8)

  // Process schedules - keep each schedule separate (don't group by date)
  const scheduleList = []

  // Process main component schedules
  schedules.forEach((schedule, index) => {
    const dateStr = formatDate(schedule.schedule_date)
    scheduleList.push({
      date: dateStr,
      main: schedule.delivery_quantity || 0,
      fit1: 0,
      fit2: 0,
      fit3: 0,
      fit4: 0,
      fit5: 0,
      fit6: 0,
      fit7: 0,
      fit8: 0,
      fit9: 0,
      remark: schedule.remark || '',
      index: index // Keep track of original schedule order
    })
  })

  // Process fit components schedules
  details.forEach((detail) => {
    const compNum = detail.comp_num || ''
    const fitMatch = compNum.match(/fit(\d+)/i)
    if (fitMatch) {
      const fitNum = parseInt(fitMatch[1])
      if (fitNum >= 1 && fitNum <= 9) {
        // Distribute fit quantities across all schedules proportionally
        schedules.forEach((schedule, index) => {
          const fitQty = detail.order_quantity || 0
          const mainQty = details[0]?.order_quantity || 1
          const scheduleQty = schedule.delivery_quantity || 0
          const proportionalQty = (fitQty / mainQty) * scheduleQty

          const fitKey = `fit${fitNum}`
          scheduleList[index][fitKey] += Math.round(proportionalQty)
        })
      }
    }
  })

  // Render delivery schedule table
  scheduleList.forEach((scheduleData) => {
    doc.text(scheduleData.date, leftMargin, yPos)

    // Main column
    const mainQty = scheduleData.main.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
    doc.text(mainQty, leftMargin + 110, yPos, { align: 'right' })

    // Fit columns F1-F9
    for (let i = 1; i <= 9; i++) {
      const fitQty = scheduleData[`fit${i}`]
      const qtyText = fitQty > 0 ? fitQty.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) : ''
      const xPos = leftMargin + 120 + ((i - 1) * 25)
      doc.text(qtyText, xPos + 15, yPos, { align: 'right' })
    }

    doc.text(scheduleData.remark, leftMargin + 345, yPos)
    yPos += 8
  })

  yPos += 9

  // SO Remark
  doc.setFont('courier', 'bold')
  doc.setFontSize(8)
  doc.text('SO Remark :', leftMargin, yPos)
  doc.setFont('courier', 'normal')
  doc.text(so.remark || '', leftMargin + 65, yPos)
  yPos += 12

  // Order instructions
  doc.setFont('courier', 'bold')
  doc.setFontSize(8)
  doc.text('ORDER INSTRUCTION :', leftMargin, yPos)
  yPos += 9
  doc.setFont('courier', 'normal')
  doc.text(`1. ${so.instruction1 || 'TOL +10%'}`, leftMargin, yPos)
  yPos += 8
  doc.text(`2. ${so.instruction2 || 'OO'}`, leftMargin, yPos)
  yPos += 15

  // Footer signatures
  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, rightMargin, yPos)
  yPos += 12

  doc.setFont('courier', 'bold')
  doc.setFontSize(8)
  doc.text('CHECKED BY : .', leftMargin, yPos)
  doc.text('APPROVED BY : .', 300, yPos)
  yPos += 25

  doc.setLineWidth(0.5)
  doc.line(leftMargin, yPos, leftMargin + 150, yPos)
  doc.line(300, yPos, 450, yPos)
  yPos += 12

  // Print info
  doc.setFont('courier', 'normal')
  doc.setFontSize(8)
  const issuedBy = currentUser.value.official_name || 'Unknown User'
  const issuedDate = new Date().toLocaleString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })
  const printedBy = currentUser.value.user_id || 'guest'

  console.log('➡️ PDF Footer - User Info:')
  console.log('  issuedBy (official_name):', issuedBy)
  console.log('  printedBy (user_id):', printedBy)
  console.log('  From currentUser.value:', currentUser.value)
  console.log('  currentUser.value.official_name:', currentUser.value.official_name)
  console.log('  currentUser.value.user_id:', currentUser.value.user_id)

  doc.text(`ISSUED BY : ${issuedBy}      ${issuedDate}`, leftMargin, yPos)
  yPos += 8
  doc.text(`PRINTED BY: ${printedBy}      ${issuedDate}`, leftMargin, yPos)
  yPos += 8
  doc.text(`Dok. No : MBI-FM-MKT-015   Rev : 00`, leftMargin, yPos)

  doc.text('*** End of Page ***', rightMargin, yPos, { align: 'right' })
}

async function downloadExcel() {
  try {
    // Fetch real sales orders data
    const salesOrdersData = await fetchSalesOrdersData()

    // Convert to CSV format
    const csvContent = salesOrdersData.map(row =>
      row.map(cell => `"${cell}"`).join(',')
    ).join('\r\n')

    // Add header information
    const fromRange = form.from.seq || formatSO(form.from.month, form.from.year, '00001')
    const toRange = form.to.seq || formatSO(form.to.month, form.to.year, '99999')

    const headerInfo = [
      'PT. MULTIBOX INDAH',
      'SALES ORDER REPORT',
      '',
      `Period: ${form.period.month}/${form.period.year}`,
      `Range: ${fromRange} to ${toRange}`,
      `Status: ${Object.keys(form.status).filter(k => form.status[k]).join(', ').toUpperCase()}`,
      `Printer: ${printer.code} | User: ${printer.user}`,
      '',
      csvContent
    ].join('\r\n')

    const blob = new Blob([headerInfo], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
    a.download = `SalesOrder_${form.period.month}_${form.period.year}.csv`
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Error downloading Excel:', error)
    alert('Error downloading Excel file')
  }
}

function formatSO(m, y, s) {
  const mm = String(m).padStart(2, '0')
  const yyyy = String(y)
  const seq = String(s).padStart(5, '0')
  return `${mm}-${yyyy}-${seq}`
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  if (isNaN(date.getTime())) return dateStr
  return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

// Quick print by SO number, using data created from PrepareMCSO
async function quickPrint() {
  if (!quick.so) return
  try {
    // Ensure current user is loaded first
    if (!currentUser.value.user_id || currentUser.value.user_id === '') {
      console.log('quickPrint: User not loaded yet, fetching...')
      await fetchCurrentUser()
      // Wait a bit to ensure the ref is updated
      await new Promise(resolve => setTimeout(resolve, 100))
    }

    console.log('quickPrint: Starting with user:', currentUser.value)

    const res = await fetch(`/api/sales-order/${encodeURIComponent(quick.so)}/delivery-schedules`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
    })
    const json = await res.json()
    if (!json.success) {
      preview.value = true
      previewText.value = `Sales Order ${quick.so} not found.`
      return
    }
    const so = json.data.sales_order
    const details = so.details || []
    const schedules = json.data.delivery_schedules || []
    preview.value = true
    previewText.value = renderSoReport(so, details, schedules)

    // Also generate PDF for quick print
    const doc = new jsPDF({ unit: 'pt', format: 'a4', orientation: 'portrait' })
    renderSalesOrderPdf(doc, so, details, schedules)
    doc.save(`SalesOrder_${quick.so}.pdf`)
  } catch (e) {
    preview.value = true
    previewText.value = `Error fetching SO ${quick.so}: ${e?.message || e}`
  }
}

function renderSoReport(so, details, schedules) {
  const lines = []
  const pad = (t, n) => String(t ?? '').padEnd(n)
  const padL = (t, n) => String(t ?? '').padStart(n)
  const num = (v, decimals = 2) => {
    if (v == null) return ''
    return Number(v).toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals })
  }

  // Header
  lines.push(pad('PT. MULTIBOX INDAH', 60) + `S/ORDER# : ${so.so_number || ''}`)
  lines.push(pad('SALES ORDER', 60) + `S/O DATE : ${formatDate(so.po_date) || ''}`)

  const creditDate = new Date().toLocaleString('en-GB', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
  const userId = currentUser.value.user_id || 'guest'
  console.log('Text Report - Using userId:', userId, 'from currentUser:', currentUser.value)
  lines.push(`Credit Control: System Approved    , ${creditDate}, ${userId}` + padL('PAGE NO : 1', 20))
  lines.push('-'.repeat(100))

  // Customer section
  lines.push('CUSTOMER:' + pad('', 51) + 'DELIVER TO:')
  lines.push(pad(so.customer_name || '', 60) + (so.delivery_location || so.customer_name || ''))

  const addressLines = (so.customer_address || '').split(',').map(s => s.trim())
  const deliveryLines = (so.delivery_address || so.customer_address || '').split(',').map(s => s.trim())
  const maxLines = Math.max(addressLines.length, deliveryLines.length)

  for (let i = 0; i < maxLines && i < 3; i++) {
    lines.push(pad(addressLines[i] || '', 60) + (deliveryLines[i] || ''))
  }

  lines.push('')
  lines.push(`TEL :` + pad('', 55) + 'TEL :')
  lines.push(`FAX :` + pad('', 55) + 'FAX :')
  lines.push(`EMAIL :` + pad('', 53) + 'EMAIL :')
  lines.push('-'.repeat(100))

  // Order info
  lines.push(`P/ORDER      : ${pad(so.customer_po_number || '', 40)}P/ORDER DATE : ${formatDate(so.po_date) || ''}`)
  lines.push(`ACCOUNT NO   : ${pad(so.customer_code || '', 40)}CURRENCY     : ${so.currency || 'IDR'}`)
  lines.push(`M/CARD SEQ# : ${pad(so.master_card_seq || '', 41)}PAYMENT TERM : ${so.credit_terms || '30 DAYS'}`)
  lines.push(`MODEL        : ${pad(so.master_card_model || '', 40)}PRINT. BLOCK#: 62`)
  lines.push('-'.repeat(100))

  // Table header
  lines.push(pad('TYPE', 15) + pad('DESCRIPTION', 35) + pad('QUANTITY', 12) + pad('UOM', 8) + pad('UNIT PRICE', 14) + 'AMOUNT')
  lines.push('-'.repeat(100))

  // Details
  details.forEach((detail) => {
    const qty = detail.order_quantity || 0
    const price = detail.unit_price || 0
    const amount = qty * price

    // Use COMP_Num for TYPE field
    const compType = detail.comp_num || 'Main'
    const perSet = detail.per_set || 1
    lines.push(pad(compType, 15) + pad(`PART NO      : ${so.part_number || ''}`, 35) + padL(num(qty, 0), 12) + pad(detail.uom || 'PCS', 8) + padL(num(price, 4), 14) + padL(num(amount, 2), 15))
    lines.push(pad('', 15) + `P/DESIGN     : ${detail.item_code || ''} ( ${perSet} PCS/SET )`)

    const flute = detail.flute || ''
    const pq1 = detail.paper_quality_1 || ''
    const pq2 = detail.paper_quality_2 || ''
    const pq3 = detail.paper_quality_3 || ''
    lines.push(pad('', 15) + `B/QUALITY    : ${pq1}125  /${pq2}125  /${pq3}125  /                      BF`)

    const dims = detail.dimensions || {}
    const measurement = `${dims.int_l || 460} L x  ${dims.int_w || 270} W x  ${dims.int_h || 260} H    (Int)`
    lines.push(pad('', 15) + `MEASUREMENT  : ${measurement}`)
    lines.push(pad('', 15) + `ROLL SIZE    : ${detail.roll_size || '220 cm'}`)
    lines.push('')
    lines.push(pad('', 15) + `Sales Order KG       :  ${num(detail.gross_kg || 0, 3)}`)
    lines.push(pad('', 15) + `Price Per M2         :  ${num(detail.price_per_m2 || 0, 3)}`)
    lines.push(pad('', 15) + `Exclusive PPn 10%`)
  })

  lines.push('')

  // Delivery schedule
  lines.push('')
  lines.push('DELIVERY SCHEDULE :')
  lines.push(pad('DATE', 14) + pad('MAIN', 10) + pad('F1', 6) + pad('F2', 6) + pad('F3', 6) + pad('F4', 6) + pad('F5', 6) + pad('F6', 6) + pad('F7', 6) + pad('F8', 6) + pad('F9', 6) + 'REMARKS')

  // Process schedules - keep each schedule separate (don't group by date)
  const scheduleListPreview = []

  // Process main component schedules
  schedules.forEach((schedule, index) => {
    const dateStr = formatDate(schedule.schedule_date)
    scheduleListPreview.push({
      date: dateStr,
      main: schedule.delivery_quantity || 0,
      fit1: 0,
      fit2: 0,
      fit3: 0,
      fit4: 0,
      fit5: 0,
      fit6: 0,
      fit7: 0,
      fit8: 0,
      fit9: 0,
      remark: schedule.remark || '',
      index: index
    })
  })

  // Process fit components schedules
  details.forEach((detail) => {
    const compNum = detail.comp_num || ''
    const fitMatch = compNum.match(/fit(\d+)/i)
    if (fitMatch) {
      const fitNum = parseInt(fitMatch[1])
      if (fitNum >= 1 && fitNum <= 9) {
        schedules.forEach((schedule, index) => {
          const fitQty = detail.order_quantity || 0
          const mainQty = details[0]?.order_quantity || 1
          const scheduleQty = schedule.delivery_quantity || 0
          const proportionalQty = (fitQty / mainQty) * scheduleQty

          const fitKey = `fit${fitNum}`
          scheduleListPreview[index][fitKey] += Math.round(proportionalQty)
        })
      }
    }
  })

  // Render delivery schedule rows
  scheduleListPreview.forEach((scheduleData) => {
    const mainQty = padL(num(scheduleData.main, 0), 8)
    const fitQtys = []
    for (let i = 1; i <= 9; i++) {
      const qty = scheduleData[`fit${i}`]
      fitQtys.push(qty > 0 ? padL(num(qty, 0), 4) : pad('', 4))
    }

    lines.push(
      pad(scheduleData.date, 14) +
      pad(mainQty, 10) +
      fitQtys.join('') +
      (scheduleData.remark || '')
    )
  })

  lines.push('')
  lines.push('SO Remark : ' + (so.remark || ''))
  lines.push('')
  lines.push('ORDER INSTRUCTION :')
  lines.push(`1. ${so.instruction1 || 'TOL +10%'}`)
  lines.push(`2. ${so.instruction2 || 'OO'}`)
  lines.push('')
  lines.push('-'.repeat(100))
  lines.push('CHECKED BY : .' + pad('', 40) + 'APPROVED BY : .')
  lines.push('')
  lines.push('_________________________' + pad('', 30) + '_________________________')
  lines.push('')

  const issuedDate = new Date().toLocaleString('en-GB', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
  const issuedBy = currentUser.value.official_name || 'Unknown User'
  const printedBy = currentUser.value.user_id || 'guest'
  lines.push(`ISSUED BY : ${issuedBy}      ${issuedDate}`)
  lines.push(`PRINTED BY: ${printedBy}      ${issuedDate}`)
  lines.push(`Dok. No : MBI-FM-MKT-015   Rev : 00` + pad('', 30) + '*** End of Page ***')

  return lines.join('\n')
}
</script>

<style scoped>
/* Modern Input Styles */
.modern-input {
  @apply w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition-all duration-200
         focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
         hover:border-gray-300 bg-white/80 backdrop-blur-sm;
}

.modern-select {
  @apply w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition-all duration-200
         focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
         hover:border-gray-300 bg-white/80 backdrop-blur-sm cursor-pointer;
}

.modern-label {
  @apply block text-sm font-semibold text-gray-700 mb-2;
}

/* Modern Section Styles */
.modern-section {
  @apply bg-white/60 backdrop-blur-sm rounded-xl border border-white/40 shadow-lg overflow-hidden;
}

.modern-section-header {
  @apply flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100;
}

.modern-section-title {
  @apply text-lg font-bold text-gray-800;
}

.modern-section-content {
  @apply p-4;
}

/* Modern Button Styles */
.modern-btn-primary {
  @apply inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white
         font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105
         transition-all duration-200 hover:from-blue-600 hover:to-blue-700;
}

.modern-btn-secondary {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105
         transition-all duration-200 hover:from-gray-600 hover:to-gray-700;
}

.modern-btn-confirm {
  @apply inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white
         font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105
         transition-all duration-200 hover:from-green-600 hover:to-emerald-700;
}

.modern-btn-cancel {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105
         transition-all duration-200 hover:from-red-600 hover:to-red-700;
}

.modern-btn-download {
  @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white
         font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105
         transition-all duration-200 hover:from-purple-600 hover:to-pink-600;
}

.modern-icon-btn {
  @apply inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200
         text-gray-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600
         transition-all duration-200 shadow-sm hover:shadow-md;
}

/* Modern Checkbox Styles */
.modern-checkbox {
  @apply flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50
         cursor-pointer transition-all duration-200 hover:shadow-sm;
}

.modern-checkbox input[type="checkbox"] {
  @apply sr-only;
}

.checkmark {
  @apply w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center
         transition-all duration-200;
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark {
  @apply bg-blue-500 border-blue-500;
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark::after {
  content: '✓';
  @apply text-white text-xs font-bold;
}

.label-text {
  @apply flex-1 text-sm font-medium text-gray-700;
}

/* Status Badge Styles */
.status-badge {
  @apply px-2 py-1 rounded-full text-xs font-semibold;
}

.status-badge.outstanding {
  @apply bg-green-100 text-green-800;
}

.status-badge.partial {
  @apply bg-yellow-100 text-yellow-800;
}

.status-badge.closed {
  @apply bg-gray-100 text-gray-800;
}

.status-badge.completed {
  @apply bg-blue-100 text-blue-800;
}

.status-badge.cancelled {
  @apply bg-red-100 text-red-800;
}

/* Animation */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.5s ease-out;
}

/* Legacy styles for compatibility */
.input {
  @apply modern-input;
}
.label { @apply modern-label; }
.status { @apply modern-checkbox; }
.btn { @apply modern-btn-primary; }
.btn-outline { @apply modern-btn-cancel; }
.btn-secondary { @apply modern-btn-secondary; }
.form-row { @apply grid grid-cols-12 items-center gap-3; }
.form-label { @apply col-span-4 sm:col-span-3 text-sm text-gray-700; }
.icon-btn { @apply modern-icon-btn; }
</style>
