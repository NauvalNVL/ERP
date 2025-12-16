<template>
  <AppLayout :header="'Input No Faktur - Tax Invoice Number Input'">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 rounded-lg shadow-lg mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="bg-white/20 rounded-lg p-3">
            <i class="fas fa-receipt text-3xl text-white" />
          </div>
          <div>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-1">Input No Faktur</h2>
            <p class="text-blue-100 text-sm">Input tax invoice numbers for approved invoices</p>
          </div>
        </div>
        <div class="hidden md:block text-right text-blue-100 text-sm">
          <p>{{ new Date().toLocaleDateString('id-ID') }}</p>
          <p class="text-xs">{{ selectedInvoices.length }} invoice(s) selected</p>
        </div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
          <i class="fas fa-filter text-blue-600" />
          Filter Invoices
        </h3>
        <button
          @click="searchInvoices"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          :disabled="loading"
        >
          <i v-if="loading" class="fas fa-spinner fa-spin" />
          <i v-else class="fas fa-search" />
          {{ loading ? 'Searching...' : 'Search' }}
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-calendar-alt text-gray-400 mr-1" />
            Month
          </label>
          <select v-model="filters.month" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Months</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-calendar text-gray-400 mr-1" />
            Year
          </label>
          <input
            v-model="filters.year"
            type="number"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            placeholder="2025"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-user text-gray-400 mr-1" />
            Customer Code
          </label>
          <input
            v-model="filters.customerCode"
            type="text"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            placeholder="Customer code"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-info-circle text-gray-400 mr-1" />
            Status
          </label>
          <select v-model="filters.status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="APPROVED">Approved</option>
            <option value="POSTED">Posted</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Invoice List Table -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
          <i class="fas fa-list text-blue-600" />
          Invoice List
        </h3>
        <div class="flex items-center gap-3">
          <button
            @click="selectAll"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2"
          >
            <i class="fas fa-check-double" />
            Select All
          </button>
          <button
            @click="clearSelection"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2"
          >
            <i class="fas fa-times" />
            Clear
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="text-center">
          <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-3" />
          <p class="text-gray-600">Loading invoices...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="invoices.length === 0" class="text-center py-12">
        <i class="fas fa-inbox text-6xl text-gray-300 mb-4" />
        <p class="text-gray-500 text-lg">No invoices found</p>
        <p class="text-gray-400 text-sm">Try adjusting your filters</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                <input
                  type="checkbox"
                  @change="toggleSelectAll"
                  :checked="selectedInvoices.length === invoices.length && invoices.length > 0"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
              </th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Invoice No</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tax Code</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Faktur No</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr
              v-for="invoice in invoices"
              :key="invoice.IV_NUM"
              :class="['hover:bg-blue-50 transition-colors', isSelected(invoice.IV_NUM) ? 'bg-blue-50' : '']"
            >
              <td class="px-4 py-3">
                <input
                  type="checkbox"
                  :checked="isSelected(invoice.IV_NUM)"
                  @change="toggleInvoice(invoice)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
              </td>
              <td class="px-4 py-3 font-medium text-gray-900">{{ invoice.IV_NUM }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(invoice.IV_DMY) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">
                <div>{{ invoice.AC_NAME }}</div>
                <div class="text-xs text-gray-400">{{ invoice.AC_NUM }}</div>
              </td>
              <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">
                {{ formatCurrency(invoice.IV_TRAN_AMT) }}
              </td>
              <td class="px-4 py-3 text-center">
                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                  {{ invoice.IV_TAX_CODE || 'N/A' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  :class="[
                    'px-2 py-1 text-xs rounded-full',
                    invoice.IV_STS === 'APPROVED' ? 'bg-green-100 text-green-800' :
                    invoice.IV_STS === 'POSTED' ? 'bg-blue-100 text-blue-800' :
                    'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ invoice.IV_STS }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  v-if="invoice.FAKTUR_NO"
                  class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800"
                >
                  <i class="fas fa-check mr-1" />
                  {{ invoice.FAKTUR_NO }}
                </span>
                <span v-else class="text-xs text-gray-400">
                  <i class="fas fa-minus" />
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex justify-between items-center mt-4 pt-4 border-t">
        <p class="text-sm text-gray-600">
          Showing <span class="font-semibold">{{ invoices.length }}</span> invoice(s)
        </p>
        <p class="text-sm text-gray-600">
          <span class="font-semibold">{{ selectedInvoices.length }}</span> invoice(s) selected
        </p>
      </div>
    </div>

    <!-- Input Faktur Number Section -->
    <div v-if="selectedInvoices.length > 0" class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
        <i class="fas fa-edit text-blue-600" />
        Input Faktur Number
      </h3>

      <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex items-start gap-3">
          <i class="fas fa-info-circle text-blue-600 mt-1" />
          <div class="text-sm text-blue-800">
            <p class="font-medium mb-1">Input Options:</p>
            <ul class="list-disc ml-5 space-y-1">
              <li><strong>Single Input:</strong> Input one faktur number for all selected invoices (auto-increment)</li>
              <li><strong>Individual Input:</strong> Input faktur number for each invoice separately</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Faktur Date -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i class="fas fa-calendar-day text-gray-400 mr-1" />
          Tanggal Faktur Diterbitkan
        </label>
        <input
          v-model="fakturDate"
          type="date"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <p class="text-xs text-gray-500 mt-1">Wajib diisi. Tanggal ini akan disimpan ke kolom Tgl_Faktur.</p>
      </div>

      <!-- Single Input Mode -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i class="fas fa-hashtag text-gray-400 mr-1" />
          Starting Faktur Number (for all selected)
        </label>
        <div class="flex gap-3">
          <input
            v-model="globalFakturNumber"
            type="text"
            class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            placeholder="e.g., 010.001-25.00000001"
          />
          <button
            @click="applyToAll"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fas fa-check-circle" />
            Apply to All
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-1">
          Format: XXX.XXX-YY.NNNNNNNN (will auto-increment for multiple invoices)
        </p>
      </div>

      <!-- Individual Input Table -->
      <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
          <h4 class="font-medium text-gray-800 flex items-center gap-2">
            <i class="fas fa-list-ol text-blue-600" />
            Individual Faktur Numbers
          </h4>
        </div>
        <div class="overflow-x-auto max-h-96">
          <table class="w-full">
            <thead class="bg-gray-50 border-b sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Invoice No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Faktur Number</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="(invoice, index) in selectedInvoices"
                :key="invoice.IV_NUM"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-3 text-sm text-gray-600">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ invoice.IV_NUM }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ invoice.AC_NAME }}</td>
                <td class="px-4 py-3">
                  <input
                    v-model="invoice.FAKTUR_NO_INPUT"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="010.001-25.00000001"
                  />
                </td>
                <td class="px-4 py-3 text-center">
                  <button
                    @click="clearFakturNumber(index)"
                    class="text-red-600 hover:text-red-800"
                    title="Clear"
                  >
                    <i class="fas fa-times-circle" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between items-center mt-6">
        <button
          @click="clearAllFaktur"
          class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2"
        >
          <i class="fas fa-eraser" />
          Clear All
        </button>
        <div class="flex gap-3">
          <button
            @click="previewFaktur"
            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2"
          >
            <i class="fas fa-eye" />
            Preview
          </button>
          <button
            @click="saveFakturNumbers"
            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2"
          >
            <i class="fas fa-save" />
            Save Faktur Numbers
          </button>
        </div>
      </div>
    </div>

    <!-- Message Toast -->
    <div
      v-if="message"
      :class="[
        'fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 transition-all z-50',
        message.type === 'success' ? 'bg-green-600 text-white' :
        message.type === 'error' ? 'bg-red-600 text-white' :
        'bg-blue-600 text-white'
      ]"
    >
      <i
        :class="[
          'fas',
          message.type === 'success' ? 'fa-check-circle' :
          message.type === 'error' ? 'fa-exclamation-circle' :
          'fa-info-circle'
        ]"
      />
      <span>{{ message.text }}</span>
      <button @click="message = null" class="ml-2 hover:opacity-80">
        <i class="fas fa-times" />
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';

// State
const loading = ref(false);
const invoices = ref([]);
const selectedInvoices = ref([]);
const globalFakturNumber = ref('');
const fakturDate = ref(new Date().toISOString().slice(0,10));
const message = ref(null);

// Filters
const filters = reactive({
  month: '',
  year: '',
  customerCode: '',
  status: ''
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

// Fetch invoices from backend
const fetchInvoices = async () => {
  loading.value = true;
  message.value = null;
  selectedInvoices.value = [];

  try {
    const params = new URLSearchParams();
    if (filters.month) params.append('month', filters.month);
    if (filters.year) params.append('year', filters.year);
    if (filters.customerCode) params.append('customer_code', filters.customerCode);
    if (filters.status) params.append('status', filters.status);

    const response = await fetch(`/api/invoices/coretax/invoices?${params.toString()}`);
    if (!response.ok) {
      const text = await response.text();
      throw new Error(text || `HTTP ${response.status}`);
    }
    const data = await response.json();

    if (data.success) {
      invoices.value = sortInvoicesNewestFirst(data.data || []);
      showMessage(`Loaded ${invoices.value.length} invoice(s)`, 'success');
    } else {
      invoices.value = [];
      showMessage(data.message || 'Failed to fetch invoices', 'error');
    }
  } catch (error) {
    console.error('Error fetching invoices:', error);
    invoices.value = [];
    showMessage('Error fetching invoices', 'error');
  } finally {
    loading.value = false;
  }
};

// Alias for search button
const searchInvoices = () => fetchInvoices();

const toggleInvoice = (invoice) => {
  if (invoice.FAKTUR_NO) {
    showMessage('Invoice sudah memiliki No Faktur', 'error');
    return;
  }
  const index = selectedInvoices.value.findIndex(inv => inv.IV_NUM === invoice.IV_NUM);
  if (index > -1) {
    selectedInvoices.value.splice(index, 1);
  } else {
    selectedInvoices.value.push({ ...invoice, FAKTUR_NO_INPUT: '' });
  }
};

const isSelected = (invoiceNum) => {
  return selectedInvoices.value.some(inv => inv.IV_NUM === invoiceNum);
};

const toggleSelectAll = () => {
  if (selectedInvoices.value.length === invoices.value.length) {
    selectedInvoices.value = [];
  } else {
    selectedInvoices.value = invoices.value
      .filter(inv => !inv.FAKTUR_NO)
      .map(inv => ({ ...inv, FAKTUR_NO_INPUT: '' }));
  }
};

const selectAll = () => {
  selectedInvoices.value = invoices.value
    .filter(inv => !inv.FAKTUR_NO)
    .map(inv => ({ ...inv, FAKTUR_NO_INPUT: '' }));
};

const clearSelection = () => {
  selectedInvoices.value = [];
};

const applyToAll = () => {
  if (!globalFakturNumber.value) {
    showMessage('Please enter a faktur number', 'error');
    return;
  }

  // Auto-increment logic
  const baseFaktur = globalFakturNumber.value;
  selectedInvoices.value.forEach((invoice, index) => {
    invoice.FAKTUR_NO_INPUT = incrementFakturNumber(baseFaktur, index);
  });

  showMessage(`Applied faktur numbers to ${selectedInvoices.value.length} invoice(s)`, 'success');
};

const incrementFakturNumber = (fakturNumber, increment) => {
  // Extract the last number part and increment it
  const parts = fakturNumber.split('.');
  if (parts.length >= 3) {
    const lastPart = parts[parts.length - 1];
    const number = parseInt(lastPart) + increment;
    parts[parts.length - 1] = number.toString().padStart(lastPart.length, '0');
    return parts.join('.');
  }
  return fakturNumber;
};

const clearFakturNumber = (index) => {
  selectedInvoices.value[index].FAKTUR_NO_INPUT = '';
};

const clearAllFaktur = () => {
  selectedInvoices.value.forEach(invoice => {
    invoice.FAKTUR_NO_INPUT = '';
  });
  globalFakturNumber.value = '';
  showMessage('Cleared all faktur numbers', 'info');
};

const previewFaktur = () => {
  const withFaktur = selectedInvoices.value.filter(inv => inv.FAKTUR_NO_INPUT);
  if (withFaktur.length === 0) {
    showMessage('No faktur numbers entered', 'error');
    return;
  }
  
  showMessage(`Preview: ${withFaktur.length} invoice(s) ready to save`, 'info');
};

const saveFakturNumbers = async () => {
  const withFaktur = selectedInvoices.value.filter(inv => inv.FAKTUR_NO_INPUT);
  
  if (withFaktur.length === 0) {
    showMessage('No faktur numbers to save', 'error');
    return;
  }

  if (!fakturDate.value) {
    showMessage('Tanggal faktur wajib diisi', 'error');
    return;
  }

  const confirm = await Swal.fire({
    title: 'Simpan Faktur?',
    text: `Anda akan menyimpan ${withFaktur.length} nomor faktur ke database dengan tanggal ${fakturDate.value}.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, simpan',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#16a34a',
    cancelButtonColor: '#9ca3af'
  });

  if (!confirm.isConfirmed) {
    return;
  }

  loading.value = true;
  try {
    const payload = {
      invoices: withFaktur.map(inv => ({
        IV_NUM: inv.IV_NUM,
        FAKTUR_NO_INPUT: inv.FAKTUR_NO_INPUT
      })),
      faktur_date: fakturDate.value
    };

    const res = await fetch('/api/invoices/coretax/save-faktur', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.content || '',
      },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (!res.ok || data.success === false) {
      const msg = data.message || data.error || `HTTP ${res.status}`;
      throw new Error(msg);
    }

    showMessage(`Saved ${data.saved_count || withFaktur.length} faktur number(s)`, 'success');
    await Swal.fire({
      title: 'Berhasil',
      text: `Berhasil menyimpan ${data.saved_count || withFaktur.length} nomor faktur.`,
      icon: 'success',
      confirmButtonColor: '#16a34a'
    });
    // Refresh list and reset selection
    await fetchInvoices();
    selectedInvoices.value = [];
    globalFakturNumber.value = '';
  } catch (error) {
    console.error('Failed to save faktur numbers:', error);
    showMessage(error.message || 'Failed to save faktur numbers', 'error');
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => {
  if (!date) return '-';
  const d = new Date(date);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatCurrency = (amount) => {
  if (!amount) return 'Rp 0';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
};

const showMessage = (text, type = 'info') => {
  message.value = { text, type };
  setTimeout(() => {
    message.value = null;
  }, 3000);
};

onMounted(() => {
  fetchInvoices();
});
</script>
