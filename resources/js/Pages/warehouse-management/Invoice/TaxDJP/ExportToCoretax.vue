<template>
  <AppLayout :header="'Export to Coretax - Bulk XML Generator'">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white shadow-lg rounded-lg border border-emerald-700 mb-6">
      <div class="px-4 py-3 sm:px-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
            <i class="fas fa-file-export text-white text-lg" />
          </div>
          <div>
            <h2 class="text-lg sm:text-xl font-semibold leading-tight">Export to Coretax</h2>
            <p class="text-xs sm:text-sm text-emerald-100">Generate bulk XML tax invoices for DJP Coretax system</p>
          </div>
        </div>
        <div class="hidden md:block text-right text-emerald-100 text-sm">
          <p>{{ new Date().toLocaleDateString('id-ID') }}</p>
          <p class="text-xs">{{ selectedInvoices.length }} invoice(s) selected</p>
        </div>
      </div>
    </div>

    <!-- Step Indicator -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
      <div class="flex items-center justify-between max-w-2xl mx-auto">
        <div :class="['flex items-center gap-2', currentStep >= 1 ? 'text-emerald-600' : 'text-gray-400']">
          <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold', currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200']">1</div>
          <span class="font-medium">Select Invoices</span>
        </div>
        <div class="flex-1 h-1 mx-4 bg-gray-200">
          <div :class="['h-full transition-all', currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-200']" :style="`width: ${currentStep >= 2 ? '100%' : '0%'}`"></div>
        </div>
        <div :class="['flex items-center gap-2', currentStep >= 2 ? 'text-emerald-600' : 'text-gray-400']">
          <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold', currentStep >= 2 ? 'bg-emerald-600 text-white' : 'bg-gray-200']">2</div>
          <span class="font-medium">Preview & Export</span>
        </div>
      </div>
    </div>

    <!-- Step 1: Invoice Selection -->
    <div v-show="currentStep === 1" class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
          <i class="fas fa-file-invoice text-emerald-600" />
          Step 1: Select Invoices for Export
        </h3>
        <button
          @click="fetchInvoices"
          class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center gap-2"
          :disabled="loading"
        >
          <i :class="['fas', loading ? 'fa-spinner fa-spin' : 'fa-sync-alt']" />
          {{ loading ? 'Loading...' : 'Refresh' }}
        </button>
      </div>

      <!-- Filters -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
          <div class="flex gap-2">
            <input v-model="filters.month" type="text" maxlength="2" placeholder="MM" class="w-16 px-2 py-1 border rounded text-center" />
            <input v-model="filters.year" type="text" maxlength="4" placeholder="YYYY" class="w-24 px-2 py-1 border rounded text-center" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
          <input v-model="filters.customerCode" type="text" class="w-full px-3 py-1 border rounded" placeholder="All" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="filters.status" class="w-full px-3 py-1 border rounded">
            <option value="">All</option>
            <option value="OPEN">OPEN</option>
            <option value="PAID">PAID</option>
          </select>
        </div>
        <div class="flex items-end">
          <button @click="fetchInvoices" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <i class="fas fa-search mr-2" />Filter
          </button>
        </div>
      </div>

      <!-- Invoice Table -->
      <div class="border rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[500px]">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left">
                  <input
                    type="checkbox"
                    @change="toggleSelectAll"
                    :checked="selectedInvoices.length === invoices.length && invoices.length > 0"
                    class="rounded border-gray-300"
                  />
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Invoice#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">NPWP</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Amount</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Tax</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading">
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                  <i class="fas fa-spinner fa-spin mr-2" />Loading invoices...
                </td>
              </tr>
              <tr v-else-if="invoices.length === 0">
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                  <i class="fas fa-inbox mr-2" />No invoices found
                </td>
              </tr>
              <tr
                v-else
                v-for="invoice in invoices"
                :key="invoice.IV_NUM"
                :class="['hover:bg-emerald-50 cursor-pointer', isSelected(invoice) ? 'bg-emerald-100' : '']"
                @click="toggleInvoice(invoice)"
              >
                <td class="px-4 py-3">
                  <input
                    type="checkbox"
                    :checked="isSelected(invoice)"
                    @click.stop
                    @change="toggleInvoice(invoice)"
                    class="rounded border-gray-300"
                  />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ invoice.IV_NUM }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ invoice.IV_DMY }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  <div class="font-medium">{{ invoice.AC_NUM }}</div>
                  <div class="text-xs text-gray-500">{{ invoice.AC_NAME }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ invoice.customer?.NPWP || '-' }}</td>
                <td class="px-4 py-3 text-sm text-right font-medium">{{ formatCurrency(invoice.IV_TRAN_AMT) }}</td>
                <td class="px-4 py-3 text-sm text-right text-emerald-600 font-medium">
                  {{ formatCurrency(invoice.IV_TRAN_AMT * (invoice.IV_TAX_PERCENT || 0) / 100) }}
                </td>
                <td class="px-4 py-3 text-sm">
                  <span :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusColor(invoice.IV_STS)]">
                    {{ invoice.IV_STS }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-600">
          <span class="font-semibold">{{ selectedInvoices.length }}</span> invoice(s) selected
        </p>
        <button
          @click="proceedToPreview"
          :disabled="selectedInvoices.length === 0 || loading"
          class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <i v-if="loading" class="fas fa-spinner fa-spin" />
          <i v-else class="fas fa-file-code" />
          {{ loading ? 'Generating...' : 'Generate XML' }}
        </button>
      </div>
    </div>

    <!-- Step 2: Preview & Export -->
    <div v-show="currentStep === 2" class="space-y-6">
      <!-- Summary Card -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-clipboard-check text-emerald-600" />
            Step 2: Preview & Export XML
          </h3>
          <div class="flex gap-3">
            <button
              @click="downloadXML"
              class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center gap-2"
            >
              <i class="fas fa-download" />
              Download XML
            </button>
            <button
              @click="copyXMLToClipboard"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
            >
              <i class="fas fa-copy" />
              Copy to Clipboard
            </button>
          </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-4">
            <div class="text-sm text-emerald-700 mb-1">Total Invoices</div>
            <div class="text-2xl font-bold text-emerald-900">{{ selectedInvoices.length }}</div>
          </div>
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <div class="text-sm text-blue-700 mb-1">Total Amount</div>
            <div class="text-2xl font-bold text-blue-900">{{ formatCurrency(totalAmount) }}</div>
          </div>
          <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <div class="text-sm text-purple-700 mb-1">Total Tax</div>
            <div class="text-2xl font-bold text-purple-900">{{ formatCurrency(totalTax) }}</div>
          </div>
          <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4">
            <div class="text-sm text-orange-700 mb-1">XML Size</div>
            <div class="text-2xl font-bold text-orange-900">{{ (generatedXML.length / 1024).toFixed(2) }} KB</div>
          </div>
        </div>

        <!-- XML Preview -->
        <div class="border rounded-lg overflow-hidden">
          <div class="bg-gray-800 px-4 py-2 flex items-center justify-between">
            <span class="text-white text-sm font-medium">TaxInvoiceBulk.xml</span>
            <span class="text-gray-400 text-xs">{{ selectedInvoices.length }} invoice(s)</span>
          </div>
          <div class="bg-gray-900 p-4 overflow-x-auto max-h-[500px]">
            <pre class="text-green-400 text-xs font-mono">{{ generatedXML }}</pre>
          </div>
        </div>

        <div class="flex justify-between items-center mt-6">
          <button
            @click="currentStep = 1"
            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2"
          >
            <i class="fas fa-arrow-left" />
            Back to Selection
          </button>
          <button
            @click="resetAll"
            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2"
          >
            <i class="fas fa-redo" />
            Start Over
          </button>
        </div>
      </div>
    </div>

    <!-- Per Invoice Customization Modal -->
    <div
      v-if="showPerInvoiceModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="showPerInvoiceModal = false"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Customize Tax Data Per Invoice</h3>
          <button @click="showPerInvoiceModal = false" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl" />
          </button>
        </div>

        <div class="p-6 space-y-6">
          <div
            v-for="(invoice, index) in selectedInvoices"
            :key="invoice.IV_NUM"
            class="border rounded-lg p-4 bg-gray-50"
          >
            <div class="flex items-center justify-between mb-3">
              <h4 class="font-semibold text-gray-800">
                {{ index + 1 }}. Invoice: {{ invoice.IV_NUM }} - {{ invoice.AC_NAME }}
              </h4>
              <button
                @click="copyGlobalToInvoice(invoice.IV_NUM)"
                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
              >
                <i class="fas fa-copy mr-1" />Copy from Global
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tax Invoice Date</label>
                <input
                  v-model="getInvoiceTaxData(invoice.IV_NUM).TaxInvoiceDate"
                  type="date"
                  class="w-full px-2 py-1 border rounded text-sm"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Invoice Option</label>
                <select v-model="getInvoiceTaxData(invoice.IV_NUM).TaxInvoiceOpt" class="w-full px-2 py-1 border rounded text-sm">
                  <option value="1">Normal</option>
                  <option value="2">Pembetulan</option>
                  <option value="3">Retur</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Transaction Code</label>
                <input
                  v-model="getInvoiceTaxData(invoice.IV_NUM).TrxCode"
                  type="text"
                  class="w-full px-2 py-1 border rounded text-sm"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Buyer Email</label>
                <input
                  v-model="getInvoiceTaxData(invoice.IV_NUM).BuyerEmail"
                  type="email"
                  class="w-full px-2 py-1 border rounded text-sm"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">VAT Rate (%)</label>
                <input
                  v-model.number="getInvoiceTaxData(invoice.IV_NUM).VATRate"
                  type="number"
                  step="0.01"
                  class="w-full px-2 py-1 border rounded text-sm"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="sticky bottom-0 bg-white border-t px-6 py-4 flex justify-end gap-3">
          <button
            @click="showPerInvoiceModal = false"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
          >
            Cancel
          </button>
          <button
            @click="applyPerInvoiceCustomization"
            class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700"
          >
            <i class="fas fa-check mr-2" />Apply Changes
          </button>
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div
      v-if="message"
      :class="['fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3', message.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white']"
    >
      <i :class="['fas', message.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle']" />
      {{ message.text }}
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Data
const currentStep = ref(1);
const loading = ref(false);
const invoices = ref([]);
const selectedInvoices = ref([]);
const showPerInvoiceModal = ref(false);
const generatedXML = ref('');
const message = ref(null);

// Filters
const filters = ref({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear(),
  customerCode: '',
  status: ''
});

// Global Tax Data
const globalTaxData = ref({
  TIN: '',
  TaxInvoiceDate: new Date().toISOString().split('T')[0],
  TaxInvoiceOpt: '1',
  TrxCode: '01',
  AddInfo: '',
  CustomDoc: '',
  CustomDocMonthYear: '',
  RefDesc: '',
  FacilityStamp: '',
  SellerIDTKU: '',
  BuyerEmail: '',
  VATRate: 11,
  STLGRate: 0,
  GoodsOpt: '1'
});

// Per-invoice tax data
const perInvoiceTaxData = ref({});

// Computed
const totalAmount = computed(() => {
  return selectedInvoices.value.reduce((sum, inv) => sum + (parseFloat(inv.IV_TRAN_AMT) || 0), 0);
});

const totalTax = computed(() => {
  return selectedInvoices.value.reduce((sum, inv) => {
    const amount = parseFloat(inv.IV_TRAN_AMT) || 0;
    const taxPercent = parseFloat(inv.IV_TAX_PERCENT) || 0;
    return sum + (amount * taxPercent / 100);
  }, 0);
});

const parseDateSkToEpoch = (value) => {
  const raw = value ?? '';
  const n = Number(raw);
  if (!Number.isFinite(n)) return null;
  const s = String(Math.trunc(n));
  if (s.length !== 8) return null;
  const yyyy = s.slice(0, 4);
  const mm = s.slice(4, 6);
  const dd = s.slice(6, 8);
  const t = Date.parse(`${yyyy}-${mm}-${dd}T00:00:00`);
  return Number.isNaN(t) ? null : t;
};

const parseDateStringToEpoch = (value) => {
  const str = String(value ?? '').trim();
  if (!str) return null;
  if (/^\d{4}-\d{2}-\d{2}/.test(str)) {
    const t = Date.parse(str);
    return Number.isNaN(t) ? null : t;
  }
  if (/^\d{2}\/\d{2}\/\d{4}$/.test(str)) {
    const [dd, mm, yyyy] = str.split('/');
    const t = Date.parse(`${yyyy}-${mm}-${dd}T00:00:00`);
    return Number.isNaN(t) ? null : t;
  }
  if (/^\d{2}-\d{2}-\d{4}$/.test(str)) {
    const [dd, mm, yyyy] = str.split('-');
    const t = Date.parse(`${yyyy}-${mm}-${dd}T00:00:00`);
    return Number.isNaN(t) ? null : t;
  }
  const t = Date.parse(str);
  return Number.isNaN(t) ? null : t;
};

const getInvoiceSortKey = (inv) => {
  return (
    parseDateSkToEpoch(inv?.IV_DATE_SK ?? inv?.DATE_SK ?? inv?.DateSK ?? inv?.IV_DATE) ??
    parseDateStringToEpoch(inv?.IV_DMY ?? inv?.IV_DATE ?? inv?.invoice_date) ??
    0
  );
};

const sortInvoicesNewestFirst = (rows) => {
  const list = Array.isArray(rows) ? rows.slice() : [];
  list.sort((a, b) => {
    const ta = getInvoiceSortKey(a);
    const tb = getInvoiceSortKey(b);
    if (ta !== tb) return tb - ta;
    return String(b?.IV_NUM ?? '').localeCompare(String(a?.IV_NUM ?? ''), 'en', {
      numeric: true,
      sensitivity: 'base',
    });
  });
  return list;
};

// Methods
const fetchInvoices = async () => {
  loading.value = true;
  try {
    const params = new URLSearchParams();
    if (filters.value.month) params.append('month', filters.value.month);
    if (filters.value.year) params.append('year', filters.value.year);
    if (filters.value.customerCode) params.append('customer_code', filters.value.customerCode);
    if (filters.value.status) params.append('status', filters.value.status);

    const response = await fetch(`/api/invoices/coretax/invoices?${params.toString()}`);
    const data = await response.json();
    
    if (data.success) {
      invoices.value = sortInvoicesNewestFirst(data.data || []);
    } else {
      showMessage(data.message || 'Error fetching invoices', 'error');
    }
  } catch (error) {
    console.error('Error fetching invoices:', error);
    showMessage('Error fetching invoices', 'error');
  } finally {
    loading.value = false;
  }
};

const toggleSelectAll = () => {
  if (selectedInvoices.value.length === invoices.value.length) {
    selectedInvoices.value = [];
  } else {
    selectedInvoices.value = [...invoices.value];
  }
};

const toggleInvoice = (invoice) => {
  const index = selectedInvoices.value.findIndex(inv => inv.IV_NUM === invoice.IV_NUM);
  if (index > -1) {
    selectedInvoices.value.splice(index, 1);
  } else {
    selectedInvoices.value.push(invoice);
  }
};

const isSelected = (invoice) => {
  return selectedInvoices.value.some(inv => inv.IV_NUM === invoice.IV_NUM);
};

const proceedToTaxData = () => {
  if (selectedInvoices.value.length === 0) {
    showMessage('Please select at least one invoice', 'error');
    return;
  }
  currentStep.value = 2;
};

const proceedToPreview = async () => {
  if (selectedInvoices.value.length === 0) {
    showMessage('Please select at least one invoice', 'error');
    return;
  }
  
  loading.value = true;
  try {
    // Call backend to generate XML automatically from invoice data
    const invoiceNumbers = selectedInvoices.value.map(inv => inv.IV_NUM);
    const response = await fetch('/api/invoices/coretax/generate-xml', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        invoice_numbers: invoiceNumbers
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      generatedXML.value = data.data.xml;
      showMessage(`XML generated successfully for ${data.data.invoice_count} invoice(s)`, 'success');
      currentStep.value = 2;
    } else {
      showMessage(data.message || 'Failed to generate XML', 'error');
    }
  } catch (error) {
    console.error('Error generating XML:', error);
    showMessage('Error generating XML', 'error');
  } finally {
    loading.value = false;
  }
};

const getInvoiceTaxData = (invoiceNum) => {
  if (!perInvoiceTaxData.value[invoiceNum]) {
    perInvoiceTaxData.value[invoiceNum] = { ...globalTaxData.value };
  }
  return perInvoiceTaxData.value[invoiceNum];
};

const copyGlobalToInvoice = (invoiceNum) => {
  perInvoiceTaxData.value[invoiceNum] = { ...globalTaxData.value };
  showMessage('Global data copied', 'success');
};

const applyPerInvoiceCustomization = () => {
  showPerInvoiceModal.value = false;
  showMessage('Customizations applied', 'success');
};

// XML generation is now handled by backend ExportToCoretaxController
// This function is kept for reference but no longer used
const generateXML = () => {
  console.log('XML generation is now handled by backend');
};

const downloadXML = () => {
  const blob = new Blob([generatedXML.value], { type: 'application/xml' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `TaxInvoiceBulk_${new Date().toISOString().split('T')[0]}.xml`;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  showMessage('XML file downloaded successfully', 'success');
};

const copyXMLToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(generatedXML.value);
    showMessage('XML copied to clipboard', 'success');
  } catch (error) {
    showMessage('Failed to copy to clipboard', 'error');
  }
};

const resetAll = () => {
  currentStep.value = 1;
  selectedInvoices.value = [];
  perInvoiceTaxData.value = {};
  generatedXML.value = '';
  showMessage('Reset successful', 'success');
};

const formatCurrency = (value) => {
  if (!value) return 'Rp 0';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getStatusColor = (status) => {
  const colors = {
    'OPEN': 'bg-yellow-100 text-yellow-800',
    'PAID': 'bg-green-100 text-green-800',
    'CANCELLED': 'bg-red-100 text-red-800'
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const showMessage = (text, type = 'success') => {
  message.value = { text, type };
  setTimeout(() => {
    message.value = null;
  }, 3000);
};

// Lifecycle
onMounted(() => {
  fetchInvoices();
});
</script>

<style scoped>
.animation-delay-500 {
  animation-delay: 500ms;
}

@keyframes ping-slow {
  0%, 100% {
    transform: scale(1);
    opacity: 0.3;
  }
  50% {
    transform: scale(1.5);
    opacity: 0;
  }
}

.animate-ping-slow {
  animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
