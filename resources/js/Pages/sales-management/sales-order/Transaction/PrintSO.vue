<template>
  <AppLayout header="Print SO">
    <div class="max-w-3xl mx-auto" v-page-transition>
      <!-- Card -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-print text-blue-600 text-xl"></i>
            <div>
              <h2 class="text-base font-semibold text-gray-800">Print Sales Order</h2>
              <p class="text-xs text-gray-500">Mirror of CPS ERP dialog with a modern touch</p>
            </div>
          </div>
          <button
            @click="resetForm"
            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs rounded-md border border-gray-200 hover:bg-gray-50 text-gray-700"
          >
            <i class="fa-solid fa-rotate-left"></i>
            Reset
          </button>
        </div>

        <div class="p-5">
          <div class="space-y-4">
            <!-- Row: Current Period -->
            <div class="form-row">
              <div class="form-label">Current Period:</div>
              <div class="flex items-center gap-2">
                <input v-model.number="form.period.month" type="number" min="1" max="12" class="w-16 input" />
                <input v-model.number="form.period.year" type="number" min="2000" max="2099" class="w-20 input" />
              </div>
            </div>

            <!-- Row: SO Range -->
            <div class="form-row">
              <div class="form-label">S/Order#:</div>
              <div class="flex flex-col gap-2 w-full">
                <div class="flex items-center gap-2">
                  <input v-model.number="form.from.month" type="number" min="1" max="12" class="w-16 input" />
                  <input v-model.number="form.from.year" type="number" min="2000" max="2099" class="w-20 input" />
                  <input v-model="form.from.seq" type="text" class="w-28 input" placeholder="seq" />
                  <button class="icon-btn" title="Lookup">
                    <i class="fa-solid fa-magnifying-glass"></i>
                  </button>
                  <span class="text-gray-400 text-sm">to</span>
                  <input v-model.number="form.to.month" type="number" min="1" max="12" class="w-16 input" />
                  <input v-model.number="form.to.year" type="number" min="2000" max="2099" class="w-20 input" />
                  <input v-model="form.to.seq" type="text" class="w-28 input" placeholder="seq" />
                  <button class="icon-btn" title="Lookup">
                    <i class="fa-solid fa-magnifying-glass"></i>
                  </button>
                </div>
                <div class="flex items-center gap-2">
                  <input v-model="quick.so" class="input w-full" placeholder="Quick print by SO Number (e.g. SO20250001)" />
                  <button class="btn" @click="quickPrint"><i class="fa-solid fa-bolt mr-2"></i>Quick Print</button>
                </div>
              </div>
            </div>

            <!-- Row: Copies -->
            <div class="form-row">
              <div class="form-label">Number of Copy:</div>
              <select v-model.number="form.copies" class="input w-24">
                <option v-for="n in 9" :key="n" :value="n">{{ n }}</option>
              </select>
            </div>

            <!-- Row: Status -->
            <div class="form-row">
              <div class="form-label">S/Order Status:</div>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-2">
                <label class="status"><input type="checkbox" v-model="form.status.outstanding" /> Outstanding</label>
                <label class="status"><input type="checkbox" v-model="form.status.partial" /> Partial Completed</label>
                <label class="status"><input type="checkbox" v-model="form.status.closed" /> Closed</label>
                <label class="status"><input type="checkbox" v-model="form.status.completed" /> Completed</label>
                <label class="status"><input type="checkbox" v-model="form.status.cancelled" /> Cancelled</label>
              </div>
            </div>
          </div>

          <!-- Footer actions like CPS bottom-right Proceed -->
          <div class="mt-6 flex items-center justify-end">
            <button class="btn-secondary" @click="openPrinter()">
              Proceed
            </button>
          </div>
        </div>
          <!-- Period -->
          <div class="lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Period</label>
            <div class="flex gap-2">
              <input v-model.number="form.period.month" type="number" min="1" max="12" class="w-20 input" />
              <input v-model.number="form.period.year" type="number" min="2000" max="2099" class="w-28 input" />
            </div>
          </div>

          <!-- SO Range / Single SO quick search -->
          <div class="lg:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">S/Order# Range</label>
            <div class="grid grid-cols-2 gap-3">
              <div class="flex items-center gap-2">
                <input v-model.number="form.from.month" type="number" min="1" max="12" class="w-16 input" />
                <input v-model.number="form.from.year" type="number" min="2000" max="2099" class="w-20 input" />
                <input v-model="form.from.seq" type="text" class="flex-1 input" placeholder="sequence" />
              </div>
              <div class="flex items-center gap-2">
                <input v-model.number="form.to.month" type="number" min="1" max="12" class="w-16 input" />
                <input v-model.number="form.to.year" type="number" min="2000" max="2099" class="w-20 input" />
                <input v-model="form.to.seq" type="text" class="flex-1 input" placeholder="sequence" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
              <input v-model="quick.so" class="input flex-1" placeholder="Quick print by SO Number (e.g. SO20250001)" />
              <button class="btn" @click="quickPrint">Quick Print</button>
            </div>
          </div>

          <!-- Copies -->
          <div class="lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Copy</label>
            <select v-model.number="form.copies" class="input w-full">
              <option v-for="n in 9" :key="n" :value="n">{{ n }}</option>
            </select>
          </div>

          <!-- Status -->
          <div class="lg:col-span-12">
            <label class="block text-sm font-medium text-gray-700 mb-2">S/Order Status</label>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
              <label class="status"><input type="checkbox" v-model="form.status.outstanding" /> Outstanding</label>
              <label class="status"><input type="checkbox" v-model="form.status.partial" /> Partial Completed</label>
              <label class="status"><input type="checkbox" v-model="form.status.closed" /> Closed</label>
              <label class="status"><input type="checkbox" v-model="form.status.completed" /> Completed</label>
              <label class="status"><input type="checkbox" v-model="form.status.cancelled" /> Cancelled</label>
            </div>
          </div>

          <!-- Actions -->
          <div class="lg:col-span-12 flex items-center justify-end gap-3">
            <button
              class="btn-secondary"
              @click="openPrinter()"
            >
              <i class="fa-solid fa-print mr-2"></i> Proceed
            </button>
          </div>
        </div>
      </div>

      <!-- Preview -->
      <div v-if="preview" class="mt-6 bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-3 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-medium text-gray-800">Preview</h3>
          <div class="flex gap-2">
            <button class="btn" @click="downloadPdf"><i class="fa-solid fa-file-pdf mr-2"></i> PDF</button>
            <button class="btn" @click="downloadExcel"><i class="fa-solid fa-file-excel mr-2"></i> Excel</button>
          </div>
        </div>
        <div class="p-6 overflow-auto">
          <div class="font-mono text-sm whitespace-pre leading-6">
            {{ previewText }}
          </div>
        </div>
      </div>
    </div>

    <!-- Printer Modal -->
    <div v-if="printer.open" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" @click="printer.open = false"></div>
      <div class="relative bg-white w-full max-w-md rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Printer Selection</h3>
        <div class="space-y-4">
          <div>
            <label class="label">Printer Code</label>
            <input v-model="printer.code" class="input w-full" placeholder="e.g. HPL-001" />
          </div>
          <div>
            <label class="label">User ID</label>
            <input v-model="printer.user" class="input w-full" disabled />
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-2">
          <button class="btn-outline" @click="printer.open = false">Cancel</button>
          <button class="btn" @click="proceedPrint">OK</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

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

function resetForm() {
  form.copies = 1
  form.status = { outstanding: true, partial: true, closed: true, completed: true, cancelled: false }
  form.from.seq = ''
  form.to.seq = ''
}

function openPrinter() {
  printer.open = true
}

async function proceedPrint() {
  printer.open = false
  // Compose payload similar to desktop filters
  const payload = {
    period: `${form.period.month} ${form.period.year}`,
    range: {
      from: formatSO(form.from.month, form.from.year, form.from.seq || '0'),
      to: formatSO(form.to.month, form.to.year, form.to.seq || '99999'),
    },
    copies: form.copies,
    status: Object.keys(form.status).filter(k => form.status[k]),
    printer: { code: printer.code, user: printer.user },
  }

  const res = await fetch('/api/so-report', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify(payload),
  })
  const json = await res.json()
  preview.value = true
  previewText.value = formatPreview(json)
}

function formatPreview(data) {
  const lines = []
  lines.push('PT. MULTIBOX INDAH\nSALES ORDER (Preview)')
  lines.push(''.padEnd(80, '-'))
  lines.push(`Period : ${form.period.month}/${form.period.year}`)
  lines.push(`Range  : ${formatSO(form.from.month, form.from.year, form.from.seq || '0')}  to  ${formatSO(form.to.month, form.to.year, form.to.seq || '99999')}`)
  lines.push(`Status : ${Object.keys(form.status).filter(k => form.status[k]).join(', ').toUpperCase()}`)
  lines.push(`Printer: ${printer.code}  User: ${printer.user}`)
  lines.push('')
  lines.push('This is a mock preview showing your input echoed from API:')
  lines.push(JSON.stringify(data.data || {}, null, 2))
  return lines.join('\n')
}

function downloadPdf() {
  const doc = new jsPDF({ unit: 'pt', format: 'a4' })
  const margin = 40
  const width = doc.internal.pageSize.getWidth() - margin * 2
  const text = previewText.value
  const lines = doc.splitTextToSize(text, width)
  doc.setFont('courier', 'normal')
  doc.setFontSize(10)
  doc.text(lines, margin, margin)
  doc.save('SalesOrder.pdf')
}

function downloadExcel() {
  const csv = previewText.value.replaceAll('\n', '\r\n')
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'SalesOrder.csv'
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
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
.input {
  @apply border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white;
}
.label { @apply block text-sm font-medium text-gray-700 mb-1; }
.status { @apply inline-flex items-center gap-2 text-sm text-gray-700; }
.btn { @apply inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700; }
.btn-outline { @apply inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 hover:bg-gray-50 text-gray-700; }
.btn-secondary { @apply inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm rounded-md hover:bg-emerald-700; }
.form-row { @apply grid grid-cols-12 items-center gap-3; }
.form-label { @apply col-span-4 sm:col-span-3 text-sm text-gray-700; }
.icon-btn { @apply inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50; }
</style>
