<template>
  <AppLayout header="Print Delivery Order">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50" v-page-transition>
      <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
            <i class="fa-solid fa-print text-white text-2xl"></i>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Print Delivery Order
          </h1>
          <p class="text-gray-600 text-lg">Generate and print delivery order reports with modern interface</p>
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
                  <p class="text-blue-100 text-sm">Configure your delivery order printing parameters</p>
                </div>
              </div>
              <button
                @click="refreshForm"
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
                          v-model.number="currentPeriod.month"
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
                          v-model.number="currentPeriod.year"
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

                <!-- DO Range Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-list-ol text-green-500"></i>
                    <h3 class="modern-section-title">Delivery Order Range</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-4">
                      <!-- From Range -->
                      <div>
                        <label class="modern-label">From D/Order#</label>
                        <div class="flex items-center gap-2">
                          <input v-model.number="doRange.fromMonth" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                          <input v-model.number="doRange.fromYear" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                          <span class="text-gray-400">/</span>
                          <input v-model="doRange.fromNumber" type="text" class="modern-input w-24" placeholder="Seq" />
                          <button
                            class="modern-icon-btn"
                            title="Lookup"
                            @click="openDOModal"
                          >
                            <i class="fa-solid fa-magnifying-glass"></i>
                          </button>
                        </div>
                      </div>

                      <!-- To Range -->
                      <div>
                        <label class="modern-label">To D/Order#</label>
                        <div class="flex items-center gap-2">
                          <input v-model.number="doRange.toMonth" type="number" min="1" max="12" class="modern-input w-16 text-center" placeholder="MM" />
                          <span class="text-gray-400">/</span>
                          <input v-model.number="doRange.toYear" type="number" min="2000" max="2099" class="modern-input w-20 text-center" placeholder="YYYY" />
                          <span class="text-gray-400">/</span>
                          <input v-model="doRange.toNumber" type="text" class="modern-input w-24" placeholder="Seq" />
                          <button
                            class="modern-icon-btn"
                            title="Lookup"
                            @click="openToDOModal"
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
                        v-model="quickDO"
                        class="modern-input flex-1"
                        placeholder="Enter DO Number (e.g. DO20250001)"
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
                <!-- Customer Selection Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-user text-purple-500"></i>
                    <h3 class="modern-section-title">Customer Filter</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-3">
                      <div>
                        <label class="modern-label">Customer Code</label>
                        <div class="flex gap-2">
                          <input
                            v-model="customer.code"
                            class="modern-input flex-1"
                            placeholder="Enter customer code"
                          />
                          <button
                            class="modern-icon-btn"
                            title="Customer Lookup"
                            @click="openCustomerModal"
                          >
                            <i class="fa-solid fa-magnifying-glass"></i>
                          </button>
                        </div>
                      </div>
                      <div v-if="customer.name">
                        <label class="modern-label">Customer Name</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700">
                          {{ customer.name }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Print Settings Section -->
                <div class="modern-section">
                  <div class="modern-section-header">
                    <i class="fa-solid fa-cog text-orange-500"></i>
                    <h3 class="modern-section-title">Print Settings</h3>
                  </div>
                  <div class="modern-section-content">
                    <div class="space-y-4">
                      <div>
                        <label class="modern-label">Number of Copies</label>
                        <select v-model.number="printCopies" class="modern-select">
                          <option v-for="n in 9" :key="n" :value="n">{{ n }} {{ n === 1 ? 'Copy' : 'Copies' }}</option>
                        </select>
                      </div>
                      <div>
                        <label class="modern-checkbox">
                          <input type="checkbox" v-model="newEntryMode" />
                          <span class="checkmark"></span>
                          <span class="label-text">Print Only New Entry</span>
                          <span class="status-badge outstanding">New</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center">
              <button class="modern-btn-confirm" @click="proceedToPrint" :disabled="!hasValidRange">
                <i class="fa-solid fa-print mr-2"></i>
                Proceed to Print
              </button>
            </div>
          </div>
        </div>

        <!-- Status Messages -->
        <div v-if="message" class="mt-6">
          <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-check text-white"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">Status Update</h3>
                  <p class="text-green-100 text-sm">Operation completed successfully</p>
                </div>
              </div>
            </div>
            <div class="p-6">
              <p class="text-gray-700">{{ message }}</p>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div v-if="preview" class="mt-6 bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-eye text-white"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">Preview Surat Jalan</h3>
                  <p class="text-green-100 text-sm">Review sebelum mencetak PDF</p>
                </div>
              </div>
              <div class="flex gap-2">
                <button class="modern-btn-primary" @click="downloadPdf">
                  <i class="fa-solid fa-file-pdf mr-2"></i> PDF
                </button>
              </div>
            </div>
          </div>
          <div class="p-6 overflow-auto max-h-96">
            <pre class="font-mono text-xs leading-5 bg-white p-6 rounded-lg border shadow-sm" style="font-family: 'Courier New', monospace; white-space: pre-wrap;">{{ previewText }}</pre>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Account Modal -->
    <CustomerAccountModal
      :show="showCustomerModal"
      @close="closeCustomerModal"
      @select="handleCustomerSelect"
    />

    <!-- Delivery Order Table Modal (From DO) -->
    <DeliveryOrderTableModal
      :open="showDOModal"
      :customerCode="customer.code"
      :customerName="customer.name"
      :periodMonth="currentPeriod.month.toString()"
      :periodYear="currentPeriod.year.toString()"
      @close="closeDOModal"
      @select="handleDOSelect"
    />

    <!-- Delivery Order Table Modal (To DO) -->
    <DeliveryOrderTableModal
      :open="showToDOModal"
      :customerCode="customer.code"
      :customerName="customer.name"
      :periodMonth="currentPeriod.month.toString()"
      :periodYear="currentPeriod.year.toString()"
      @close="closeToDOModal"
      @select="handleToDOSelect"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import DeliveryOrderTableModal from '@/Components/DeliveryOrderTableModal.vue'
import jsPDF from 'jspdf'

const currentPeriod = reactive({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
})

const doRange = reactive({
  fromMonth: '',
  fromYear: '',
  fromNumber: '',
  toMonth: '',
  toYear: '',
  toNumber: ''
})

const customer = reactive({
  code: '',
  name: ''
})

const newEntryMode = ref(true)
const message = ref('')
const quickDO = ref('')
const printCopies = ref(1)
const preview = ref(false)
const previewText = ref('')
const currentUser = ref({ user_id: 'guest', official_name: 'Guest User' })

// Modal states
const showCustomerModal = ref(false)
const showDOModal = ref(false)
const showToDOModal = ref(false)

// Computed properties
const hasValidRange = computed(() => {
  return (doRange.fromMonth && doRange.fromYear && doRange.fromNumber) ||
         (doRange.toMonth && doRange.toYear && doRange.toNumber)
})

// Functions
const refreshForm = () => {
  Object.assign(doRange, {
    fromMonth: '',
    fromYear: '',
    fromNumber: '',
    toMonth: '',
    toYear: '',
    toNumber: ''
  })

  Object.assign(customer, {
    code: '',
    name: ''
  })

  newEntryMode.value = 'print_only'
  message.value = 'Form refreshed successfully'

  setTimeout(() => {
    message.value = ''
  }, 3000)
}

const proceedToPrint = async () => {
  try {
    if (!hasValidRange.value) {
      message.value = 'Please specify a valid delivery order range'
      return
    }

    // Build request params for backend print range
    const params = new URLSearchParams()
    // Compute effective range; if To empty, use From for single-DO
    const hasFrom = !!(doRange.fromMonth && doRange.fromYear && doRange.fromNumber)
    const effFromMonth = hasFrom ? String(doRange.fromMonth).padStart(2, '0') : String(currentPeriod.month).padStart(2, '0')
    const effFromYear = hasFrom ? String(doRange.fromYear) : String(currentPeriod.year)
    const effFromNumber = hasFrom ? String(doRange.fromNumber).padStart(5, '0') : '00001'

    const hasTo = !!(doRange.toMonth && doRange.toYear && doRange.toNumber)
    const effToMonth = hasTo ? String(doRange.toMonth).padStart(2, '0') : effFromMonth
    const effToYear = hasTo ? String(doRange.toYear) : effFromYear
    const effToNumber = hasTo ? String(doRange.toNumber).padStart(5, '0') : effFromNumber

    params.append('from_month', effFromMonth)
    params.append('from_year', effFromYear)
    params.append('from_number', effFromNumber)
    params.append('to_month', effToMonth)
    params.append('to_year', effToYear)
    params.append('to_number', effToNumber)
    // If single DO, pass exact do_num to guarantee match (use YYYY-MM-SSSSS)
    if (effFromMonth === effToMonth && effFromYear === effToYear && effFromNumber === effToNumber) {
      const exact = `${effFromYear}-${effFromMonth}-${effFromNumber}`
      params.append('do_num', exact)
    }
    if (newEntryMode.value) params.append('new_entry_mode', 'print_only')

    const url = `/api/delivery-orders/print-range?${params.toString()}`
    console.log('🔍 PrintDO - Request URL:', url)
    console.log('📤 PrintDO - Params sent:', {
      from_month: effFromMonth,
      from_year: effFromYear,
      from_number: effFromNumber,
      to_month: effToMonth,
      to_year: effToYear,
      to_number: effToNumber,
      do_num: effFromMonth === effToMonth && effFromYear === effToYear && effFromNumber === effToNumber ? `${effFromYear}-${effFromMonth}-${effFromNumber}` : null,
      new_entry_mode: newEntryMode.value || null
    })
    const res = await fetch(url, { headers: { Accept: 'application/json' } })
    const json = await res.json()
    console.log('📥 PrintDO - Response from backend:', json)

    // Normalize rows
    let rows = []
    if (json && json.success && Array.isArray(json.data)) rows = json.data
    else if (Array.isArray(json)) rows = json

    preview.value = true
    previewText.value = formatPreviewText(rows)
  } catch (e) {
    preview.value = true
    previewText.value = 'Error generating preview: ' + (e.message || e)
  }
}

function formatPreviewText(rows) {
  const lines = []

  if (!rows || rows.length === 0) {
    lines.push('No delivery orders found for selected range')
    return lines.join('\n')
  }

  // Group rows by DO number so Main/Fit components stay in one Surat Jalan
  const groups = new Map()
  rows.forEach(r => {
    const key = r.DO_Num || r.do_num || ''
    const groupKey = key || 'NO_DO'
    if (!groups.has(groupKey)) groups.set(groupKey, [])
    groups.get(groupKey).push(r)
  })

  const totalGroups = groups.size
  let groupIndex = 0

  for (const [, groupRows] of groups) {
    groupIndex++

    // Sort components by line number then COMP (Main, Fit1, Fit2...)
    groupRows.sort((a, b) => {
      const aNo = Number(a.No ?? a.no ?? 0)
      const bNo = Number(b.No ?? b.no ?? 0)
      if (aNo !== bNo) return aNo - bNo
      const aComp = String(a.COMP || '')
      const bComp = String(b.COMP || '')
      return aComp.localeCompare(bComp)
    })

    const header = groupRows[0]
    const base = groupRows.find(r => String(r.COMP || '').toLowerCase() === 'main') || header

    const doNum = header.DO_Num || header.do_num || ''
    const doDate = header.DO_DMY || header.DODateSK || ''
    const cust = header.AC_Name || header.AC_NAME || header.customer_name || ''
    const custAddress1 = header.ADDRESS1 || ''
    const custAddress2 = header.ADDRESS2 || ''
    const custAddress3 = header.ADDRESS3 || ''
    const custTel = header.TEL_NO || ''
    const custFax = header.FAX_NO || ''
    const vhc = header.DO_VHC_Num || header.vehicle_no || ''
    const soNum = base.SO_Num || ''
    const poNum = base.PO_Num || ''
    const model = base.Model || base.SO_Model || ''

    const doQty = parseFloat(base.DO_Qty || 0)
    const unit = base.Unit || base.SO_Unit || ''
    const unitLower = (unit || '').toLowerCase()
    const pcsPerBld = parseFloat(base.PCS_PER_BLD || 1)

    // Calculate bundle quantities based on Main line
    let bundles = 0
    let remainingPcs = 0

    if (pcsPerBld > 0 && doQty > 0) {
      bundles = Math.floor(doQty / pcsPerBld)
      remainingPcs = doQty - bundles * pcsPerBld
    } else {
      remainingPcs = doQty
    }

    // Header text (same untuk semua komponen di DO ini)
    lines.push('PT. MULTIBOX INDAH'.padEnd(50) + `No. SJ    : ${doNum}`)
    lines.push('Jl. Raya Cikande - Rangkas Bitung KM.6 Desa Kareo'.padEnd(50) + `Tanggal   : ${doDate}`)
    lines.push('Kec. Jawilan, Serang - Banten 42180'.padEnd(50) + 'Halaman   : 1')
    lines.push('Phone : (0254)404060 (Hunting)')
    lines.push('Fax   : (021)8252690')
    lines.push('')
    lines.push('                           SURAT JALAN')
    lines.push('')
    lines.push(`Kirim ke :`.padEnd(50) + `Nomor Truk : ${vhc || '-'}`)
    lines.push(`${cust || '-'}`.padEnd(50) + `Waktu Print : ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`)

    // Customer address from database
    if (custAddress1) {
      lines.push(custAddress1.padEnd(50) + 'Dibuat Oleh : whs12')
    }
    if (custAddress2) {
      lines.push(custAddress2)
    }
    if (custAddress3) {
      lines.push(custAddress3)
    }

    lines.push('')

    // Customer phone and fax from database
    if (custTel) {
      lines.push(`Tel   : ${custTel}`)
    }
    if (custFax) {
      lines.push(`Fax   : ${custFax}`)
    }

    lines.push('')
    lines.push('No. Nama Barang'.padEnd(60) + 'Jumlah'.padStart(12) + ' '.repeat(4) + 'Satuan')
    lines.push(''.padEnd(110, '-'))
    const qtyStrPrev = `${Number(doQty)}${unitLower}`
    const unitStrPrev = `${bundles}BDL x ${pcsPerBld}Pcs + ${remainingPcs}Pcs`
    lines.push(`1 SO# : ${soNum}/PO# / ${poNum}`.padEnd(60) + qtyStrPrev.padStart(12) + '    ' + unitStrPrev)
    lines.push(`  Model : ${model}`)

    // Tampilkan semua komponen (Main, Fit1..Fit9) dalam satu DO dengan detail quantity masing-masing
    // Dedup berdasarkan COMP agar data duplikat dari join tidak tercetak ganda
    const seenComps = new Set()
    groupRows.forEach(r => {
      const compLabel = r.COMP || 'Main'
      const compKey = String(compLabel).trim().toLowerCase()
      if (seenComps.has(compKey)) return
      seenComps.add(compKey)

      const mainName = r.ProductGroupName || ''
      const design = r.PD || r.MC_P_DESIGN || mainName || ''
      const intL = r.INT_L || 0
      const intW = r.INT_W || 0
      const intH = r.INT_H || 0
      const extL = r.EXT_L || 0
      const extW = r.EXT_W || 0
      const extH = r.EXT_H || 0
      const dimL = extL || intL
      const dimW = extW || intW
      const dimH = extH || intH

      // Get component-specific quantity
      const compQty = parseFloat(r.DO_Qty || 0)
      const compUnit = r.Unit || unit || ''
      const compUnitLower = (compUnit || '').toLowerCase()
      const compPcsPerBld = parseFloat(r.PCS_PER_BLD || pcsPerBld || 1)

      // Calculate bundle quantities for this component
      let compBundles = 0
      let compRemainingPcs = 0
      if (compPcsPerBld > 0 && compQty > 0) {
        compBundles = Math.floor(compQty / compPcsPerBld)
        compRemainingPcs = compQty - compBundles * compPcsPerBld
      } else {
        compRemainingPcs = compQty
      }

      const cleanLabel = compLabel ? String(compLabel).trim() : 'Main'
      
      // Format line dengan quantity dan dimension
      const compText = `  ${cleanLabel} : ${design}`
      const qtyStr = `${Number(compQty)}${compUnitLower}`
      const unitStr = `${compBundles}BDL x ${compPcsPerBld}Pcs + ${compRemainingPcs}Pcs`
      
      lines.push(compText.padEnd(60) + qtyStr.padStart(12) + '    ' + unitStr)
      lines.push(`    Dimensi : ${dimL} x ${dimW} x ${dimH}`)
    })

    lines.push('')
    lines.push('')
    lines.push('Keterangan :')
    lines.push('')
    lines.push('Yang menerima Jam : ..........    Yang mengantar              Hormat kami')
    lines.push('')
    lines.push('(Nama Jelas dan Cap Perusahaan)  [Driver Name]                 Gudang    _____')
    lines.push('Akhir dari halaman                Sopir')
    lines.push(''.padEnd(110, '-'))

    if (groupIndex < totalGroups) {
      lines.push('')
      lines.push(''.padEnd(110, '='))
      lines.push('')
    }
  }

  return lines.join('\n')
}

async function downloadPdf() {
  try {
    const doc = new jsPDF({ unit: 'pt', format: 'a4', orientation: 'portrait' })
    // Fetch rows again using current preview filters (simple approach)
    const params = new URLSearchParams()
    const hasFrom = !!(doRange.fromMonth && doRange.fromYear && doRange.fromNumber)
    const effFromMonth = hasFrom ? String(doRange.fromMonth).padStart(2, '0') : String(currentPeriod.month).padStart(2, '0')
    const effFromYear = hasFrom ? String(doRange.fromYear) : String(currentPeriod.year)
    const effFromNumber = hasFrom ? String(doRange.fromNumber).padStart(5, '0') : '00001'

    const hasTo = !!(doRange.toMonth && doRange.toYear && doRange.toNumber)
    const effToMonth = hasTo ? String(doRange.toMonth).padStart(2, '0') : effFromMonth
    const effToYear = hasTo ? String(doRange.toYear) : effFromYear
    const effToNumber = hasTo ? String(doRange.toNumber).padStart(5, '0') : effFromNumber

    params.append('from_month', effFromMonth)
    params.append('from_year', effFromYear)
    params.append('from_number', effFromNumber)
    params.append('to_month', effToMonth)
    params.append('to_year', effToYear)
    params.append('to_number', effToNumber)
    if (effFromMonth === effToMonth && effFromYear === effToYear && effFromNumber === effToNumber) {
      const exact = `${effFromYear}-${effFromMonth}-${effFromNumber}`
      params.append('do_num', exact)
    }
    if (newEntryMode.value) params.append('new_entry_mode', 'print_only')

    const res = await fetch(`/api/delivery-orders/print-range?${params.toString()}`, { headers: { Accept: 'application/json' } })
    const json = await res.json()
    const rows = (json && json.success && Array.isArray(json.data)) ? json.data : []

    // Group rows by DO number so each Surat Jalan page contains Main + Fit components
    const groups = new Map()
    rows.forEach(r => {
      const key = r.DO_Num || r.do_num || ''
      const groupKey = key || 'NO_DO'
      if (!groups.has(groupKey)) groups.set(groupKey, [])
      groups.get(groupKey).push(r)
    })

    let page = 0
    for (const [, groupRows] of groups) {
      if (page > 0) doc.addPage()
      await renderSuratJalan(doc, groupRows)
      page++
    }

    if (page === 0) {
      alert('No delivery orders found to print.')
      return
    }

    const mm = String(currentPeriod.month).padStart(2, '0')
    const yy = String(currentPeriod.year)
    doc.save(`SuratJalan_${mm}_${yy}.pdf`)
  } catch (e) {
    alert('Error generating PDF: ' + (e.message || e))
  }
}

async function renderSuratJalan(doc, groupRows) {
  const left = 50
  const right = 545
  const center = (left + right) / 2
  let y = 50

  // Normalize to array and sort components (Main, Fit1..Fit9)
  const rows = Array.isArray(groupRows) ? [...groupRows] : [groupRows]
  rows.sort((a, b) => {
    const aNo = Number(a.No ?? a.no ?? 0)
    const bNo = Number(b.No ?? b.no ?? 0)
    if (aNo !== bNo) return aNo - bNo
    const aComp = String(a.COMP || '')
    const bComp = String(b.COMP || '')
    return aComp.localeCompare(bComp)
  })

  const header = rows[0]
  const base = rows.find(r => String(r.COMP || '').toLowerCase() === 'main') || header

  // Extract header / base data
  const doNum = header.DO_Num || ''
  const doDate = header.DO_DMY || ''
  const custName = header.AC_Name || ''
  const custAddress1 = header.ADDRESS1 || ''
  const custAddress2 = header.ADDRESS2 || ''
  const custAddress3 = header.ADDRESS3 || ''
  const custTel = header.TEL_NO || ''
  const custFax = header.FAX_NO || ''
  const truck = header.DO_VHC_Num || ''
  const soNum = base.SO_Num || ''
  const poNum = base.PO_Num || ''
  const model = base.Model || base.SO_Model || ''
  const doQty = parseFloat(base.DO_Qty || 0)
  const unit = base.Unit || base.SO_Unit || ''
  const unitLower = (unit || '').toLowerCase()
  const pcsPerBld = parseFloat(base.PCS_PER_BLD || 1)
  
  // Fetch driver name based on truck number
  let driverName = 'SUHERMAN' // default fallback
  try {
    if (truck) {
      const driverRes = await fetch(`/api/vehicle/driver/${encodeURIComponent(truck)}`, { headers: { Accept: 'application/json' } })
      const driverData = await driverRes.json()
      if (driverData && driverData.success && driverData.driver_name) {
        driverName = driverData.driver_name
      }
    }
  } catch (e) {
    console.warn('Failed to fetch driver name:', e)
  }

  // Calculate bundle quantities
  let bundles = 0
  let pcsInBundles = 0
  let remainingPcs = 0

  if (pcsPerBld > 0 && doQty > 0) {
    bundles = Math.floor(doQty / pcsPerBld)
    pcsInBundles = bundles * pcsPerBld
    remainingPcs = doQty - pcsInBundles
  } else {
    remainingPcs = doQty
  }

  // Header Section
  doc.setFont('courier', 'bold')
  doc.setFontSize(10)
  doc.text('PT. MULTIBOX INDAH', left, y)
  doc.text(`No. SJ    : ${doNum}`, right, y, { align: 'right' })
  y += 12

  doc.setFont('courier', 'normal')
  doc.setFontSize(9)
  doc.text('Jl. Raya Cikande - Rangkas Bitung KM.6 Desa Kareo', left, y)
  doc.text(`Tanggal   : ${doDate}`, right, y, { align: 'right' })
  y += 12

  doc.text('Kec. Jawilan, Serang - Banten 42180', left, y)
  doc.text('Halaman   : 1', right, y, { align: 'right' })
  y += 12

  doc.text('Phone : (0254)404060 (Hunting)', left, y)
  y += 12

  doc.text('Fax   : (021)8252690', left, y)
  y += 25

  // Title
  doc.setFont('courier', 'bold')
  doc.setFontSize(16)
  doc.text('SURAT JALAN', center, y, { align: 'center' })
  y += 25

  // Customer and delivery info section
  doc.setFont('courier', 'normal')
  doc.setFontSize(9)
  doc.text('Kirim ke :', left, y)
  doc.text(`Nomor Truk : ${truck || '-'}`, right, y, { align: 'right' })
  y += 12

  doc.text(`${custName || '-'}`, left, y)
  doc.text(`Waktu Print : ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`, right, y, { align: 'right' })
  y += 12

  // Customer address from database with word-wrap
  if (custAddress1) {
    const addressMaxWidth = 280 // Maximum width before wrapping
    const addressLines = doc.splitTextToSize(custAddress1, addressMaxWidth)
    addressLines.forEach(line => {
      doc.text(line, left, y)
      y += 12
    })
    // Position "Dibuat Oleh" at the first address line
    doc.text(`Dibuat Oleh : ${currentUser.value.username || currentUser.value.user_id || 'whs12'}`, right, y - (addressLines.length * 12), { align: 'right' })
  }

  if (custAddress2) {
    doc.text(custAddress2, left, y)
    y += 12
  }

  if (custAddress3) {
    doc.text(custAddress3, left, y)
    y += 12
  }

  // Customer phone and fax from database
  if (custTel) {
    doc.text(`Tel   : ${custTel}`, left, y)
    y += 12
  }

  if (custFax) {
    doc.text(`Fax   : ${custFax}`, left, y)
    y += 12
  }

  y += 13

  // Table header with separate columns
  const qtyColX = right - 160 // column for quantity (Jumlah)
  const unitColX = right      // column for unit breakdown (Satuan)
  doc.text('No. Nama Barang', left, y)
  doc.text('Jumlah', qtyColX, y, { align: 'right' })
  doc.text('Satuan', unitColX, y, { align: 'right' })
  y += 8

  // Draw line under header
  doc.line(left, y, right, y)
  y += 15

  // Item details with proper bundle calculation
  doc.text(`1 SO# : ${soNum}/PO# / ${poNum}`, left, y)
  y += 12

  doc.text(`  Model : ${model}`, left, y)
  y += 12

  // Render all components (Main, Fit1..Fit9) for this DO with quantity details
  // Deduplicate by COMP so duplicate backend rows do not print twice
  const seenComps = new Set()
  rows.forEach(r => {
    const compLabel = r.COMP || 'Main'
    const compKey = String(compLabel).toLowerCase()
    if (seenComps.has(compKey)) return
    seenComps.add(compKey)

    const mainName = r.ProductGroupName || ''
    const design = r.PD || r.MC_P_DESIGN || mainName || ''
    const intL = r.INT_L || 0
    const intW = r.INT_W || 0
    const intH = r.INT_H || 0
    const extL = r.EXT_L || 0
    const extW = r.EXT_W || 0
    const extH = r.EXT_H || 0
    const dimL = Math.floor(extL || intL)
    const dimW = Math.floor(extW || intW)
    const dimH = Math.floor(extH || intH)

    // Get component-specific quantity
    const compQty = parseFloat(r.DO_Qty || 0)
    const compUnit = r.Unit || unit || ''
    const compUnitLower = (compUnit || '').toLowerCase()
    const compPcsPerBld = parseFloat(r.PCS_PER_BLD || pcsPerBld || 1)

    // Calculate bundle quantities for this component
    let compBundles = 0
    let compRemainingPcs = 0
    if (compPcsPerBld > 0 && compQty > 0) {
      compBundles = Math.floor(compQty / compPcsPerBld)
      compRemainingPcs = compQty - compBundles * compPcsPerBld
    } else {
      compRemainingPcs = compQty
    }

    // Component line with name, design, dimensions, quantity, and unit all on same line
    const compText = `  ${compLabel} : ${design}`
    const dimText = `${dimL} x ${dimW} x ${dimH}`
    const compQtyStr = `${Number(compQty)}${compUnitLower}`
    const compUnitStr = `${compBundles}BDL x ${compPcsPerBld}Pcs + ${compRemainingPcs}Pcs`
    
    // Position text elements: component on left, dimensions shifted left, quantity/unit on right
    doc.text(compText, left, y)
    doc.text(dimText, left + 100, y) // Shifted left from 180 to 100
    doc.text(compQtyStr, qtyColX, y, { align: 'right' })
    doc.text(compUnitStr, unitColX, y, { align: 'right' })
    y += 12
  })

  // Posisikan footer di area bawah halaman mirip CPS.
  // Jika konten masih tinggi di atas, lompat ke sekitar 650;
  // jika sudah mendekati bawah, cukup tambahkan jarak sedikit.
  if (y < 650) {
    y = 650
  } else {
    y += 40
  }

  // Footer section
  doc.text('Keterangan :', left, y)
  y += 40

  doc.text('Yang menerima Jam : ..........', left, y)
  doc.text('Yang mengantar', center - 50, y)
  doc.text('Hormat kami', right, y, { align: 'right' })
  y += 40

  // Signature names
  doc.text('(Nama Jelas dan Cap Perusahaan)', left, y)
  doc.text(driverName, center - 50, y)
  doc.text('Gudang    _____', right, y, { align: 'right' })
  y += 12

  doc.text('Akhir dari halaman', left, y)
  doc.text('Sopir', center - 50, y)
  y += 30

  // Bottom line
  doc.line(left, y, right, y)
}

onMounted(async () => {
  // Try fetch current user (optional)
  try {
    const res = await fetch('/api/user/current', { headers: { Accept: 'application/json' } })
    const json = await res.json()
    if (json && json.success && json.data) currentUser.value = json.data
  } catch (_) {}
})

// Modal functions
const openCustomerModal = () => {
  showCustomerModal.value = true
}

const closeCustomerModal = () => {
  showCustomerModal.value = false
}

const handleCustomerSelect = (selectedCustomer) => {
  if (selectedCustomer) {
    customer.code = selectedCustomer.customer_code
    customer.name = selectedCustomer.customer_name
    message.value = `Customer selected: ${selectedCustomer.customer_code} - ${selectedCustomer.customer_name}`

    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
  closeCustomerModal()
}

// DO Modal functions
const openDOModal = () => {
  showDOModal.value = true
}

const closeDOModal = () => {
  showDOModal.value = false
}

const handleDOSelect = (selectedDO) => {
  console.log('📥 Received DO selection:', selectedDO)

  if (selectedDO) {
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = selectedDO.do_number || selectedDO.doNumber
    console.log('🔍 Parsing DO number:', doNumber)

    if (doNumber) {
      const parts = doNumber.split('-')
      console.log('📊 DO parts:', parts)

      if (parts.length === 3) {
        // Actual format: MM-YYYY-SSSSS
        doRange.fromMonth = parseInt(parts[0])
        doRange.fromYear = parseInt(parts[1])
        doRange.fromNumber = parts[2]

        // If To range is empty, set it equal to From to enable single-DO print
        if (!doRange.toMonth && !doRange.toYear && !doRange.toNumber) {
          doRange.toYear = doRange.fromYear
          doRange.toMonth = doRange.fromMonth
          doRange.toNumber = doRange.fromNumber
        }

        console.log('✅ From DO populated:', {
          month: doRange.fromMonth,
          year: doRange.fromYear,
          number: doRange.fromNumber
        })

        message.value = `From DO selected: ${doNumber}`

        setTimeout(() => {
          message.value = ''
        }, 3000)
      } else {
        console.error('❌ Invalid DO number format:', doNumber)
        message.value = `Invalid DO format: ${doNumber}`
      }
    } else {
      console.error('❌ No DO number found in selection')
      message.value = 'No DO number found in selection'
    }
  } else {
    console.error('❌ No DO selected')
    message.value = 'No delivery order selected'
  }

  closeDOModal()
}

// To DO Modal functions
const openToDOModal = () => {
  showToDOModal.value = true
}

const closeToDOModal = () => {
  showToDOModal.value = false
}

const handleToDOSelect = (selectedDO) => {
  console.log('📥 Received To DO selection:', selectedDO)

  if (selectedDO) {
    // Parse DO number format: MM-YYYY-NNNNN
    const doNumber = selectedDO.do_number || selectedDO.doNumber
    console.log('🔍 Parsing To DO number:', doNumber)

    if (doNumber) {
      const parts = doNumber.split('-')
      console.log('📊 To DO parts:', parts)

      if (parts.length === 3) {
        // Actual format: MM-YYYY-SSSSS
        doRange.toMonth = parseInt(parts[0])
        doRange.toYear = parseInt(parts[1])
        doRange.toNumber = parts[2]

        console.log('✅ To DO populated:', {
          month: doRange.toMonth,
          year: doRange.toYear,
          number: doRange.toNumber
        })

        message.value = `To DO selected: ${doNumber}`

        setTimeout(() => {
          message.value = ''
        }, 3000)
      } else {
        console.error('❌ Invalid To DO number format:', doNumber)
        message.value = `Invalid To DO format: ${doNumber}`
      }
    } else {
      console.error('❌ No To DO number found in selection')
      message.value = 'No To DO number found in selection'
    }
  } else {
    console.error('❌ No To DO selected')
    message.value = 'No To delivery order selected'
  }

  closeToDOModal()
}

// Quick Print function
const quickPrint = () => {
  if (quickDO.value) {
    message.value = `Quick print initiated for: ${quickDO.value}`

    setTimeout(() => {
      message.value = ''
    }, 3000)
  } else {
    message.value = 'Please enter a DO number for quick print'

    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
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
         transition-all duration-200 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none;
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
</style>
