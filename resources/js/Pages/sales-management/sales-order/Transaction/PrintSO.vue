<template>
  <AppLayout header="Print SO">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50" v-page-transition>
      <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
            <i class="fa-solid fa-print text-white text-2xl"></i>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Print Sales Order
          </h1>
          <p class="text-gray-600 text-lg">Generate and print sales order reports with modern interface</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20 animate-fade-in-up">
          <!-- Card Header -->
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-print text-white text-lg"></i>
                </div>
            <div>
                  <h2 class="text-xl font-bold text-white">Print Configuration</h2>
                  <p class="text-blue-100 text-sm">Configure your sales order printing parameters</p>
            </div>
          </div>
          <button
            @click="resetForm"
                class="modern-btn-secondary"
          >
                <i class="fa-solid fa-rotate-left mr-2"></i>
                Reset Form
          </button>
            </div>
        </div>

          <!-- Card Content -->
          <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                      <button class="modern-btn-primary" @click="quickPrint">
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
            <div class="mt-8 flex justify-center">
              <button class="modern-btn-confirm" @click="openPrinter()">
                <i class="fa-solid fa-print mr-2"></i>
                Proceed to Print
              </button>
            </div>
          </div>
          </div>

        <!-- Preview Section -->
        <div v-if="preview" class="mt-8 bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-eye text-white"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">Preview Report</h3>
                  <p class="text-green-100 text-sm">Review your sales order report before printing</p>
            </div>
          </div>
              <div class="flex gap-2">
                <button class="modern-btn-download" @click="downloadPdf">
                  <i class="fa-solid fa-file-pdf mr-2"></i> PDF
                </button>
                <button class="modern-btn-download" @click="downloadExcel">
                  <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </button>
          </div>
        </div>
      </div>
          <div class="p-6 overflow-auto max-h-96">
            <div class="font-mono text-xs whitespace-pre leading-5 bg-white p-6 rounded-lg border shadow-sm" style="font-family: 'Courier New', monospace;">
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
      @zoom="onSalesOrderZoom"
    />

    <!-- Printer Modal -->
    <div v-if="printer.open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="printer.open = false"></div>
      <div class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl border border-white/20 animate-fade-in-up">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4 rounded-t-2xl">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-print text-white"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white">Printer Selection</h3>
              <p class="text-purple-100 text-sm">Configure your printing preferences</p>
            </div>
          </div>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 space-y-6">
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
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
          <button class="modern-btn-cancel" @click="printer.open = false">
            <i class="fa-solid fa-times mr-2"></i>Cancel
          </button>
          <button class="modern-btn-confirm" @click="proceedPrint">
            <i class="fa-solid fa-check mr-2"></i>Proceed
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
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

function onSalesOrderZoom(selectedSo) {
  // Handle zoom functionality if needed
  console.log('Zoom sales order:', selectedSo)
}

function closeSalesOrderModal() {
  salesOrderModal.open = false
}

async function proceedPrint() {
  printer.open = false
  
  try {
    // Compose payload similar to desktop filters
    const payload = {
      period: `${form.period.month} ${form.period.year}`,
      range: {
        from: form.from.seq || formatSO(form.from.month, form.from.year, '00001'),
        to: form.to.seq || formatSO(form.to.month, form.to.year, '99999'),
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
  const fromRange = form.from.seq || formatSO(form.from.month, form.from.year, '00001')
  const toRange = form.to.seq || formatSO(form.to.month, form.to.year, '99999')
  
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
    // Prepare parameters
    const params = {
      month: form.period.month,
      year: form.period.year,
      status: Object.keys(form.status).filter(k => form.status[k])
    }
    
    // Add SO range if specified
    if (form.from.seq || form.to.seq) {
      params.from_so = form.from.seq || formatSO(form.from.month, form.from.year, '00001')
      params.to_so = form.to.seq || formatSO(form.to.month, form.to.year, '99999')
    }
    
    console.log('Fetching sales orders with params:', params)
    
    const response = await axios.get('/api/sales-orders', { params })
    
    console.log('Sales orders response:', response.data)
    
    if (response.data.success && response.data.data) {
      const salesOrders = response.data.data
      const tableData = [
        ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date']
      ]
      
      if (salesOrders.length > 0) {
        salesOrders.forEach(order => {
          tableData.push([
            order.SO_Num || '',
            order.PO_Num || '',
            order.AC_NAME || '',
            order.STS || 'OPEN',
            order.AMOUNT ? `Rp ${Number(order.AMOUNT).toLocaleString('id-ID')}` : 'Rp 0',
            order.SO_DMY || order.PO_DATE || ''
          ])
        })
      } else {
        tableData.push(['No sales orders found for the selected criteria', '', '', '', '', ''])
      }
      
      return tableData
    } else {
      // Fallback to empty data
      return [
        ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date'],
        ['No data available', '', '', '', '', '']
      ]
    }
  } catch (error) {
    console.error('Error fetching sales orders:', error)
    // Fallback to empty data
    return [
      ['SO#', 'Customer PO#', 'Customer', 'Status', 'Amount', 'Date'],
      ['Error loading data: ' + (error.response?.data?.message || error.message), '', '', '', '', '']
    ]
  }
}

async function downloadPdf() {
  try {
    // Fetch sales orders data first
    const salesOrdersData = await fetchSalesOrdersData()
    
    if (salesOrdersData.length <= 1) {
      alert('No sales orders found for the selected criteria')
      return
    }
    
    const doc = new jsPDF({ unit: 'pt', format: 'a4' })
    const pageWidth = doc.internal.pageSize.width
    const pageHeight = doc.internal.pageSize.height
    
    // Process each sales order (skip header row)
    const salesOrders = salesOrdersData.slice(1)
    let pageCount = 0
    
    for (let i = 0; i < salesOrders.length; i++) {
      const orderRow = salesOrders[i]
      const soNumber = orderRow[0]
      
      // Skip if no SO number
      if (!soNumber || soNumber === 'No sales orders found for the selected criteria' || soNumber === 'Error loading data') {
        continue
      }
      
      if (pageCount > 0) {
        doc.addPage()
      }
      pageCount++
      
      // Fetch detailed SO data
      let soDetails = null
      try {
        const response = await fetch(`/api/sales-order/${encodeURIComponent(soNumber)}/delivery-schedules`)
        const json = await response.json()
        if (json.success) {
          soDetails = json.data
        }
      } catch (error) {
        console.error('Error fetching SO details:', error)
      }
      
      // Generate individual SO format
      await generateIndividualSO(doc, soDetails || {
        sales_order: {
          so_number: orderRow[0],
          customer_po_number: orderRow[1],
          customer_name: orderRow[2],
          status: orderRow[3],
          total_amount: orderRow[4],
          po_date: orderRow[5]
        },
        delivery_schedules: []
      }, printer)
    }
    
    // Check if any valid SO was processed
    if (pageCount === 0) {
      alert('No valid sales orders found to generate PDF')
      return
    }
    
    // Save the PDF
    const fromRange = form.from.seq || formatSO(form.from.month, form.from.year, '00001')
    const toRange = form.to.seq || formatSO(form.to.month, form.to.year, '99999')
    const fileName = pageCount === 1 ? 
      `SO_${salesOrders.find(row => row[0] && !row[0].includes('No sales orders') && !row[0].includes('Error'))?.[0] || 'Unknown'}.pdf` : 
      `SO_${fromRange}_to_${toRange}.pdf`
    
    doc.save(fileName)
  } catch (error) {
    console.error('Error generating PDF:', error)
    console.error('Error stack:', error.stack)
    alert('Error generating PDF: ' + (error.message || error.toString()))
  }
}

async function generateIndividualSO(doc, soData, printerInfo) {
  const so = soData.sales_order || {}
  const schedules = soData.delivery_schedules || []
  
  // Get page dimensions
  const pageWidth = doc.internal.pageSize.width
  const pageHeight = doc.internal.pageSize.height
  
  // Set up fonts
  doc.setFont('helvetica', 'normal')
  
  // Header Section
  doc.setFontSize(16)
  doc.setTextColor(0, 0, 0)
  doc.text('PT. MULTIBOX INDAH', 50, 40)
  doc.text('SALES ORDER', 50, 60)
  
  // SO Info (right side)
  doc.setFontSize(10)
  doc.text(`S/ORDER# : ${so.so_number || ''}`, 400, 40)
  doc.text(`S/O DATE : ${so.po_date || ''}`, 400, 55)
  doc.text('PAGE NO  : 1', 400, 70)
  
  // Separator line
  doc.setDrawColor(0, 0, 0)
  doc.setLineWidth(1)
  doc.line(50, 80, 550, 80)
  
  let currentY = 100
  
  // Customer Information Section
  doc.setFontSize(9)
  doc.text('CUSTOMER:', 50, currentY)
  doc.text('DELIVER TO:', 300, currentY)
  
  currentY += 15
  doc.text(so.customer_name || '', 50, currentY)
  doc.text(so.customer_name || '', 300, currentY)
  
  currentY += 15
  doc.text(so.customer_address || '', 50, currentY)
  doc.text(so.delivery_address || '', 300, currentY)
  
  currentY += 30
  
  // Order Details Section
  doc.text(`P/ORDER    : ${so.customer_po_number || ''}`, 50, currentY)
  doc.text(`P/ORDER DATE : ${so.po_date || ''}`, 300, currentY)
  
  currentY += 15
  doc.text(`ACCOUNT NO : ${so.customer_code || ''}`, 50, currentY)
  doc.text(`CURRENCY     : ${so.currency || 'IDR'}`, 300, currentY)
  
  currentY += 15
  doc.text(`M/CARD SEQ : ${so.master_card_seq || ''}`, 50, currentY)
  doc.text(`PAYMENT TERM : ${so.credit_terms || '30 DAYS'}`, 300, currentY)
  
  currentY += 15
  doc.text(`MODEL      : ${so.master_card_model || ''}`, 50, currentY)
  
  currentY += 30
  
  // Item Details Table
  doc.setDrawColor(0, 0, 0)
  doc.setLineWidth(1)
  doc.line(50, currentY, 550, currentY)
  
  currentY += 15
  
  // Table Headers
  doc.setFontSize(8)
  doc.text('TYPE', 60, currentY)
  doc.text('DESCRIPTION', 120, currentY)
  doc.text('QUANTITY', 350, currentY)
  doc.text('UOM', 410, currentY)
  doc.text('UNIT PRICE', 450, currentY)
  doc.text('AMOUNT', 510, currentY)
  
  currentY += 5
  doc.line(50, currentY, 550, currentY)
  currentY += 15
  
  // Item Details
  const details = so.details || [{}]
  details.forEach((detail, index) => {
    doc.text('Main', 60, currentY)
    doc.text(detail.item_description || detail.item_code || so.master_card_model || '', 120, currentY)
    doc.text(String(detail.order_quantity || ''), 350, currentY)
    doc.text(detail.uom || 'PCS', 410, currentY)
    doc.text(formatCurrency(detail.unit_price || 0), 450, currentY)
    doc.text(formatCurrency((detail.order_quantity || 0) * (detail.unit_price || 0)), 510, currentY)
    currentY += 15
  })
  
  currentY += 15
  
  // Delivery Schedule Section
  doc.setFontSize(9)
  doc.text('DELIVERY SCHEDULE :', 50, currentY)
  currentY += 20
  
  doc.setFontSize(8)
  doc.text('DATE', 60, currentY)
  doc.text('MAIN', 120, currentY)
  doc.text('F1', 160, currentY)
  doc.text('F2', 180, currentY)
  doc.text('F3', 200, currentY)
  doc.text('F4', 220, currentY)
  doc.text('F5', 240, currentY)
  doc.text('F6', 260, currentY)
  doc.text('F7', 280, currentY)
  doc.text('F8', 300, currentY)
  doc.text('F9', 320, currentY)
  doc.text('REMARKS', 350, currentY)
  
  currentY += 15
  
  if (schedules.length > 0) {
    schedules.forEach(schedule => {
      doc.text(schedule.schedule_date || '', 60, currentY)
      doc.text(String(schedule.delivery_quantity || ''), 120, currentY)
      doc.text(schedule.remark || '', 350, currentY)
      currentY += 15
    })
  } else {
    currentY += 15
  }
  
  currentY += 20
  
  // SO Remark
  doc.setFontSize(9)
  doc.text(`SO Remark : ${so.remark || ''}`, 50, currentY)
  currentY += 30
  
  // Order Instructions
  doc.text('ORDER INSTRUCTION :', 50, currentY)
  currentY += 15
  doc.text(so.instruction1 || '', 50, currentY)
  currentY += 15
  doc.text(so.instruction2 || '', 50, currentY)
  
  // Footer Section
  const footerY = pageHeight - 100
  
  doc.text('CHECKED BY : ___________________', 50, footerY)
  doc.text('APPROVED BY : ___________________', 300, footerY)
  
  doc.setFontSize(8)
  doc.text(`ISSUED BY  : ${printerInfo?.user || 'user2'}`, 50, footerY + 30)
  doc.text(`PRINTED BY: ${printerInfo?.code || 'HPL-001'}`, 300, footerY + 30)
  doc.text(`${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 50, footerY + 45)
  doc.text('*** End of Page ***', 450, footerY + 45)
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0)
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

// Quick print by SO number, using data created from PrepareMCSO
async function quickPrint() {
  if (!quick.so) return
  try {
    const res = await fetch(`/api/sales-order/${encodeURIComponent(quick.so)}/delivery-schedules`, {
      headers: { Accept: 'application/json' },
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
  } catch (e) {
    preview.value = true
    previewText.value = `Error fetching SO ${quick.so}: ${e?.message || e}`
  }
}

function renderSoReport(so, details, schedules) {
  const lines = []
  const pad = (t, n) => String(t ?? '').padEnd(n)
  const num = (v) => (v == null ? '' : Number(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))

  lines.push('PT. MULTIBOX INDAH'.padEnd(60) + `S/ORDER#: ${so.so_number || ''}`)
  lines.push('SALES ORDER'.padEnd(60) + `S/O DATE : ${so.po_date || ''}`)
  lines.push('-'.repeat(100))
  lines.push(`CUSTOMER: ${so.customer_name || so.customer_code}`)
  lines.push(`ADDRESS : ${so.customer_address || ''}`)
  lines.push('')
  lines.push(`P/ORDER  : ${so.customer_po_number || ''}    CURRENCY : ${so.currency || ''}`)
  lines.push(`M/CARD SEQ: ${so.master_card_seq || ''}        PAYMENT TERM : ${so.credit_terms || ''}`)
  lines.push(`MODEL    : ${so.master_card_model || ''}`)
  lines.push('-'.repeat(100))
  lines.push(pad('TYPE', 15) + pad('DESCRIPTION', 35) + pad('QUANTITY', 12) + pad('UOM', 6) + pad('UNIT PRICE', 12) + pad('AMOUNT', 12))
  lines.push('-'.repeat(100))
  details.forEach((d) => {
    lines.push(
      pad('Main', 15) +
        pad(d.item_description || d.item_code || '', 35) +
        pad(num(d.order_quantity), 12) +
        pad(d.uom || 'PCS', 6) +
        pad(num(d.unit_price), 12) +
        pad(num((Number(d.order_quantity) || 0) * (Number(d.unit_price) || 0)), 12)
    )
  })
  lines.push('')
  lines.push('DELIVERY SCHEDULE:')
  lines.push(pad('DATE', 14) + pad('MAIN', 10) + 'REMARKS')
  schedules.forEach((s) => {
    const date = (s.schedule_date || '').toString()
    lines.push(pad(date, 14) + pad(String(s.delivery_quantity || ''), 10) + (s.remark || ''))
  })
  lines.push('')
  lines.push('SO Remark : ' + (so.remark || ''))
  lines.push('')
  lines.push('ORDER INSTRUCTION :')
  lines.push((so.instruction1 || '').toString())
  lines.push((so.instruction2 || '').toString())
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
