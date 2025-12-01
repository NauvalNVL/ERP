<template>
  <AppLayout :header="'View & Print Invoice Log'">
    <div class="space-y-4">
      <div class="bg-gradient-to-r from-sky-600 via-blue-600 to-indigo-600 p-4 rounded-lg shadow text-white">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-3">
            <div class="bg-white/20 rounded-lg p-2">
              <i class="fas fa-history text-xl" />
            </div>
            <div>
              <h2 class="text-xl md:text-2xl font-bold">View &amp; Print Invoice Log</h2>
              <p class="text-xs md:text-sm text-blue-100">Filter and review invoice history, then print or export via browser print.</p>
            </div>
          </div>
          <div class="hidden md:flex flex-col items-end text-xs text-blue-100">
            <span>Today {{ today }}</span>
            <span>Period {{ period.month }}/{{ period.year }}</span>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Period -->
          <div>
            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
              <i class="fas fa-calendar-check text-sky-600 text-xs" />
              <span>Period</span>
            </label>
            <div class="flex items-center gap-2">
              <input v-model="period.month" type="text" maxlength="2" class="w-16 px-2 py-1 border rounded bg-gray-50 text-center text-sm" placeholder="MM" />
              <input v-model="period.year" type="text" maxlength="4" class="w-24 px-2 py-1 border rounded bg-gray-50 text-center text-sm" placeholder="YYYY" />
            </div>
          </div>

          <!-- Invoice range -->
          <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice# Range</label>
            <div class="flex flex-wrap items-center gap-2">
              <div class="flex items-center gap-1">
                <input v-model="invoiceFrom" type="text" class="w-32 px-3 py-1 border rounded text-sm" placeholder="From" />
                <button
                  type="button"
                  class="px-2 py-1 rounded bg-sky-600 text-white text-xs flex items-center gap-1"
                  @click="openInvoiceTable('from')"
                >
                  <i class="fas fa-table" />
                </button>
              </div>
              <span class="text-gray-500 text-xs">to</span>
              <div class="flex items-center gap-1">
                <input v-model="invoiceTo" type="text" class="w-32 px-3 py-1 border rounded text-sm" placeholder="To" />
                <button
                  type="button"
                  class="px-2 py-1 rounded bg-sky-600 text-white text-xs flex items-center gap-1"
                  @click="openInvoiceTable('to')"
                >
                  <i class="fas fa-table" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-2">
          <button type="button" class="px-4 py-2 rounded bg-sky-600 text-white text-sm flex items-center gap-2" @click="fetchInvoiceLog">
            <i class="fas fa-search" />
            <span>View Log</span>
          </button>
          <button type="button" class="px-4 py-2 rounded bg-gray-100 text-gray-800 text-sm" @click="clearFilters">
            Clear
          </button>
          <p v-if="errorMessage" class="ml-auto text-xs text-red-600 flex items-center gap-1">
            <i class="fas fa-exclamation-triangle" />
            <span>{{ errorMessage }}</span>
          </p>
        </div>
      </div>

      <!-- Result table -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="overflow-x-auto max-h-[420px]">
          <table class="min-w-full text-xs md:text-sm divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Invoice#</th>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Date</th>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Customer</th>
                <th class="px-3 py-2 text-right font-semibold text-gray-600">Amount</th>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Tax</th>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Status</th>
                <th class="px-3 py-2 text-left font-semibold text-gray-600">Created By</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-if="loading">
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                  <i class="fas fa-spinner fa-spin mr-2" />Loading invoice log...
                </td>
              </tr>
              <tr v-else-if="invoices.length === 0">
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">No invoices found.</td>
              </tr>
              <tr
                v-for="row in invoices"
                :key="row.invoice_number + '-' + row.invoice_date"
                class="cursor-pointer"
                :class="[
                  'hover:bg-sky-50',
                  selectedInvoice && selectedInvoice.invoice_no === row.invoice_number
                    ? 'bg-sky-50 ring-1 ring-sky-300'
                    : ''
                ]"
                @click="selectInvoiceForLog(row)"
              >
                <td class="px-3 py-2 font-semibold text-sky-700">{{ row.invoice_number }}</td>
                <td class="px-3 py-2">{{ row.invoice_date }}</td>
                <td class="px-3 py-2">
                  <div class="text-gray-900">{{ row.customer_code }}</div>
                  <div class="text-[11px] text-gray-500 truncate">{{ row.customer_name }}</div>
                </td>
                <td class="px-3 py-2 text-right font-medium">{{ formatCurrency(row.amount) }}</td>
                <td class="px-3 py-2">
                  <div class="text-xs">{{ row.tax_code || '-' }}</div>
                  <div class="text-[11px] text-gray-500" v-if="row.tax_percent">{{ row.tax_percent }}%</div>
                </td>
                <td class="px-3 py-2">
                  <span :class="['inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold', statusChipClass(row.status)]">
                    {{ row.status || 'Prepared' }}
                  </span>
                </td>
                <td class="px-3 py-2 text-xs">
                  <div>{{ row.created_by || '-' }}</div>
                  <div class="text-[11px] text-gray-500">{{ row.created_date || '' }}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-4 text-xs md:text-sm space-y-4">
        <div v-if="selectedInvoice">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Customer Name</div>
              <div class="text-gray-900">{{ selectedInvoice.customer_name || '-' }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Order Mode</div>
              <div class="text-gray-900">{{ selectedInvoice.order_mode || '-' }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">A/C Currency</div>
              <div class="text-gray-900">{{ selectedInvoice.currency || 'IDR' }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Exchange Rate</div>
              <div class="text-gray-900">{{ selectedInvoice.exchange_rate ?? 0 }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">2nd Reference#</div>
              <div class="text-gray-900 truncate">{{ selectedInvoice.second_ref || selectedInvoice.ref2 || '-' }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Salesperson</div>
              <div class="text-gray-900">{{ selectedInvoice.salesperson || '-' }}</div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-4 mt-4">
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Gross</div>
              <div class="text-right font-medium">{{ formatCurrency(selectedInvoice.total_amount) }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Tax</div>
              <div class="text-right font-medium">{{ formatCurrency(selectedInvoice.tax_amount) }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500">Net</div>
              <div class="text-right font-semibold text-emerald-700">{{ formatCurrency(selectedInvoice.net_amount) }}</div>
            </div>
          </div>

          <!-- Audit trail -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4 mt-4">
            <div>
              <div class="text-[11px] font-semibold text-gray-500 mb-1">Issued By</div>
              <div class="flex justify-between gap-2">
                <span class="text-gray-900">{{ selectedInvoice.issued_by || '-' }}</span>
                <span class="text-gray-500">{{ formatAuditDateTime(selectedInvoice.issued_date, selectedInvoice.issued_time) }}</span>
              </div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500 mb-1">Amended By</div>
              <div class="flex justify-between gap-2">
                <span class="text-gray-900">{{ selectedInvoice.amended_by || '-' }}</span>
                <span class="text-gray-500">{{ formatAuditDateTime(selectedInvoice.amended_date, selectedInvoice.amended_time) }}</span>
              </div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500 mb-1">Printed By</div>
              <div class="flex justify-between gap-2">
                <span class="text-gray-900">{{ selectedInvoice.printed_by || '-' }}</span>
                <span class="text-gray-500">{{ formatAuditDateTime(selectedInvoice.printed_date, selectedInvoice.printed_time) }}</span>
              </div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500 mb-1">Posted By</div>
              <div class="flex justify-between gap-2">
                <span class="text-gray-900">{{ selectedInvoice.posted_by || '-' }}</span>
                <span class="text-gray-500">{{ formatAuditDateTime(selectedInvoice.posted_date, selectedInvoice.posted_time) }}</span>
              </div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-gray-500 mb-1">Cancelled By</div>
              <div class="flex justify-between gap-2">
                <span class="text-gray-900">{{ selectedInvoice.cancelled_by || '-' }}</span>
                <span class="text-gray-500">{{ formatAuditDateTime(selectedInvoice.cancelled_date, selectedInvoice.cancelled_time) }}</span>
              </div>
            </div>
          </div>

          <div class="border-t border-gray-100 pt-4 mt-4">
            <div class="text-[11px] font-semibold text-gray-500 mb-1">Cancel Reason</div>
            <div class="text-gray-900">
              {{ selectedInvoice.cancelled_reason_1 || selectedInvoice.cancelled_reason_2 || '-' }}
            </div>
          </div>
        </div>
        <div v-else class="text-gray-500 text-xs italic">
          Click a row above to view detailed invoice information.
        </div>
      </div>

      <!-- Lookup modals -->
      <InvoiceTableModal
        :open="showInvoiceTable"
        :initial-query="tableQuery"
        @close="showInvoiceTable = false"
        @select="onInvoiceSelected"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import InvoiceTableModal from '@/Components/InvoiceTableModal.vue';

const now = new Date();
const period = ref({
  month: String(now.getMonth() + 1).padStart(2, '0'),
  year: String(now.getFullYear()),
});
const today = computed(() => now.toLocaleDateString('en-GB'));

const invoiceFrom = ref('');
const invoiceTo = ref('');

const invoices = ref([]);
const loading = ref(false);
const errorMessage = ref('');

const selectedInvoice = ref(null);

const showInvoiceTable = ref(false);

const tableQuery = ref({ part1: '', part2: '', part3: '' });
const invoiceRangeTarget = ref('from');

const formatCurrency = (value) => {
  const num = Number(value || 0);
  return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const statusChipClass = (value) => {
  switch (value) {
    case 'Posted':
      return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
    case 'Cancelled':
      return 'bg-rose-100 text-rose-800 border border-rose-300';
    default:
      return 'bg-sky-100 text-sky-800 border border-sky-300';
  }
};

const buildParams = () => {
  const params = {
    year: period.value.year,
    month: period.value.month,
  };
  if (invoiceFrom.value) params.invoice_from = invoiceFrom.value.trim();
  if (invoiceTo.value) params.invoice_to = invoiceTo.value.trim();
  return params;
};

const fetchInvoiceLog = async () => {
  loading.value = true;
  errorMessage.value = '';
  try {
    const res = await axios.get('/api/invoices/log', { params: buildParams() });
    invoices.value = Array.isArray(res.data) ? res.data : [];
  } catch (e) {
    console.error('Error fetching invoice log:', e);
    errorMessage.value = 'Failed to fetch invoice log data';
    invoices.value = [];
  } finally {
    loading.value = false;
  }
};

const clearFilters = () => {
  invoiceFrom.value = '';
  invoiceTo.value = '';
  errorMessage.value = '';
  invoices.value = [];
  selectedInvoice.value = null;
};

const openInvoiceTable = (target = 'from') => {
  invoiceRangeTarget.value = target;
  tableQuery.value = { part1: '', part2: '', part3: '' };
  showInvoiceTable.value = true;
};

const onInvoiceSelected = (row) => {
  if (!row) return;
  const no = row.invoice_no || row.invoice_number || '';

  if (invoiceRangeTarget.value === 'from') {
    invoiceFrom.value = no;
  } else if (invoiceRangeTarget.value === 'to') {
    invoiceTo.value = no;
  } else {
    invoiceFrom.value = no;
    invoiceTo.value = no;
  }

  showInvoiceTable.value = false;
};

const selectInvoiceForLog = async (row) => {
  if (!row || !row.invoice_number) return;

  try {
    const res = await axios.get(`/api/invoices/${encodeURIComponent(row.invoice_number)}`);
    if (res.data) {
      const data = res.data;
      selectedInvoice.value = {
        ...data,
        exchange_rate: parseFloat(data.exchange_rate ?? 0),
        total_amount: parseFloat(data.total_amount ?? 0),
        tax_amount: parseFloat(data.tax_amount ?? 0),
        net_amount: parseFloat(data.net_amount ?? 0),
      };
    }
  } catch (e) {
    console.error('Error fetching invoice details for log:', e);
  }
};

const formatAuditDateTime = (date, time) => {
  if (!date) return '';
  return time ? `${date} ${time}` : date;
};
</script>

