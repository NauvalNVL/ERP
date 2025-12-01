<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-full sm:max-w-4xl lg:max-w-7xl max-h-[95vh] flex flex-col">
      <!-- Header -->
      <div class="bg-teal-700 text-white p-3">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center space-x-3">
            <div class="bg-teal-600 p-2 rounded">
              <i class="fas fa-clipboard-list text-lg"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold">Customer Service Dashboard</h3>
            </div>
          </div>
          <div class="flex space-x-2">
            <button
              @click="$emit('close')"
              class="px-3 py-1 bg-red-600 text-white  hover:bg-red-700 text-sm"
            >
              <i class="fas fa-times mr-1"></i>
            </button>
          </div>
        </div>

        <!-- Customer / Sales Order Information Grid -->
        <div class="bg-teal-600 rounded p-2">
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 text-xs">
            <div>
              <div class="text-teal-200">Customer:</div>
              <div class="font-semibold">{{ soData.order_info?.customer_code || '' }}</div>
              <div class="font-semibold">{{ soData.order_info?.customer_name || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">M/Card Seq#:</div>
              <div class="font-semibold">{{ soData.order_info?.master_card_seq || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">S/Order #:</div>
              <div class="font-semibold text-yellow-300">{{ displaySoNumber }}</div>
            </div>
            <div>
              <div class="text-teal-200">S/Order Date:</div>
              <div class="font-semibold">{{ formatDate(soData.order_info?.so_date) }}</div>
            </div>
            <div>
              <div class="text-teal-200">S/O Status:</div>
              <div class="font-semibold text-red-300">{{ soData.order_info?.so_status || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">S/O Group:</div>
              <div class="font-semibold">{{ soData.order_info?.order_group || 'Sales' }}</div>
              <div class="text-teal-200">S/O Type:</div>
              <div class="font-semibold">{{ soData.order_info?.order_type || 'S1' }}</div>
            </div>
          </div>

          <!-- Delivery Order header info (visible when coming from Search by Delivery Order) -->
          <div
            v-if="soData.selected_delivery_order"
            class="mt-1 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 text-[11px]"
          >
            <div>
              <div class="text-teal-200">D/Order#:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.do_number || '' }}</div>
              <div class="text-teal-200">D/O Date:</div>
              <div class="font-semibold">{{ formatDate(soData.selected_delivery_order.do_date) }}</div>
            </div>
            <div>
              <div class="text-teal-200">Vehicle#:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.vehicle_no || '' }}</div>
              <div class="text-teal-200">Vehicle Class:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.vehicle_class || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">Salesperson:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.salesperson_code || '' }}</div>
              <div class="text-teal-200">C/Ticket#:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.cticket_no || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">Order Mode:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.order_mode || '' }}</div>
              <div class="text-teal-200">Sales Type:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.sales_type || '' }}</div>
            </div>
            <div>
              <div class="text-teal-200">On-Hold:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.on_hold || '' }}</div>
              <div class="text-teal-200">Status:</div>
              <div class="font-semibold">{{ soData.selected_delivery_order.status || '' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1 overflow-auto p-3">
        <!-- CPS-style Comprehensive Table -->
        <div class="border rounded overflow-hidden text-xs">
          <table class="min-w-full border-collapse">
            <thead>
              <tr class="bg-gray-800 text-white">
                <th class="border border-gray-600 px-2 py-1 text-left w-48">Item</th>
                <th class="border border-gray-600 px-2 py-1 text-center">Main</th>
                <th
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-600 px-2 py-1 text-center"
                >
                  Fit {{ index + 1 }}
                </th>
              </tr>
            </thead>
            <tbody>
              <!-- Product Design Section (Yellow) -->
              <tr class="bg-yellow-200">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Product Design</td>
                <td class="border border-gray-400 px-2 py-1 text-center">{{ mainItem.item_code || '' }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-center"
                >
                  {{ fitting.design || fitting.fitting_code || '' }}
                </td>
              </tr>

              <tr class="bg-yellow-100">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Part No</td>
                <td class="border border-gray-400 px-2 py-1 text-center">{{ mainItem.description || '' }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-center"
                >
                  {{ fitting.description || '' }}
                </td>
              </tr>

              <tr class="bg-yellow-50">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Pcs / Unit</td>
                <td class="border border-gray-400 px-2 py-1 text-center">
                  {{ formatPcsUnit(mainItem.pcs, mainItem.unit) }}
                </td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-center"
                >
                  {{ formatPcsUnit(fitting.pcs, fitting.unit) }}
                </td>
              </tr>

              <!-- Sales Order Section (Yellow) -->
              <tr class="bg-yellow-200">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Sales Order</td>
                <td class="border border-gray-400 px-2 py-1 text-right font-semibold">{{ formatNumber(soData.order_info?.set_quantity) }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                >
                  {{ formatNumber(fitting.order_qty) }}
                </td>
              </tr>

              <tr class="bg-yellow-100">
                <td class="border border-gray-400 px-2 py-1">Currency / Ex. Rate</td>
                <td class="border border-gray-400 px-2 py-1 text-right">
                  {{ soData.order_info?.currency || '' }}
                  <span v-if="soData.order_info?.exchange_rate">/
                    {{ formatBalanceNumber(soData.order_info.exchange_rate) }}
                  </span>
                </td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                ></td>
              </tr>

              <tr class="bg-yellow-50">
                <td class="border border-gray-400 px-2 py-1">Unit Price / Amount</td>
                <td class="border border-gray-400 px-2 py-1 text-right">
                  <div>{{ formatBalanceNumber(soData.order_info?.unit_price) }}</div>
                  <div class="text-[10px] text-gray-600">
                    {{ formatBalanceNumber(soData.order_info?.amount) }}
                    <span v-if="soData.order_info?.base_amount">(Base: {{ formatBalanceNumber(soData.order_info.base_amount) }})</span>
                  </div>
                </td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                ></td>
              </tr>

              <tr class="bg-yellow-100">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Net Delivered</td>
                <td class="border border-gray-400 px-2 py-1 text-right font-semibold">{{ formatNumber(netDeliveredMain) }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                >
                  {{ formatNumber(getNetDeliveredForComponent(fitting.component)) }}
                </td>
              </tr>

              <tr class="bg-yellow-50">
                <td class="border border-gray-400 px-2 py-1 font-semibold">SO Balance</td>
                <td class="border border-gray-400 px-2 py-1 text-right font-semibold">{{ formatBalanceNumber(balanceMain) }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                >
                  {{ formatBalanceNumber(getBalanceForComponent(fitting)) }}
                </td>
              </tr>

              <!-- Delivery Order Section (Cyan) -->
              <tr class="bg-cyan-200">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Delivery Order</td>
                <td class="border border-gray-400 px-2 py-1 text-right font-semibold">{{ formatNumber(netDeliveredMain) }}</td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                >
                  {{ formatNumber(getNetDeliveredForComponent(fitting.component)) }}
                </td>
              </tr>

              <tr class="bg-cyan-100">
                <td class="border border-gray-400 px-2 py-1">Remark</td>
                <td class="border border-gray-400 px-2 py-1 text-left text-[11px]">
                  {{ soData.order_info?.remark || '' }}
                </td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                ></td>
              </tr>

              <tr class="bg-cyan-50">
                <td class="border border-gray-400 px-2 py-1">Instruction</td>
                <td class="border border-gray-400 px-2 py-1 text-left text-[11px]">
                  <div v-if="soData.order_info?.instruction1">{{ soData.order_info.instruction1 }}</div>
                  <div v-if="soData.order_info?.instruction2">{{ soData.order_info.instruction2 }}</div>
                </td>
                <td
                  v-for="(fitting, index) in cpsColumns"
                  :key="index"
                  class="border border-gray-400 px-2 py-1 text-right"
                ></td>
              </tr>

              <tr class="bg-cyan-100">
                <td class="border border-gray-400 px-2 py-1 font-semibold">Invoice/DHN/FZH</td>
                <td class="border border-gray-400 px-2 py-1 text-center text-xs" colspan="100%">
                  <span v-if="invoiceList.length > 0" class="text-blue-700">
                    {{ invoiceList.join(', ') }}
                  </span>
                  <span v-else class="text-gray-400">No invoices</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Warehouse Management Label -->
        <div class="mt-2 text-xs text-gray-600 flex items-center">
          <i class="fas fa-warehouse mr-2"></i>
          WAREHOUSE MANAGEMENT
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-gray-100 border-t border-gray-300 p-2 flex justify-end">
        <button
          @click="$emit('close')"
          class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm transition-colors"
        >
          <i class="fas fa-times mr-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, onMounted } from 'vue';

const props = defineProps({
  soData: {
    type: Object,
    required: true,
    default: () => ({
      order_info: {},
      item_details: [],
      fittings: [],
      delivery_orders: [],
      delivery_order_summary: {},
      invoices: [],
      invoice_summary: {},
      net_delivered: 0,
      balance: 0
    })
  }
});

const emit = defineEmits(['close']);

// Debug logging
onMounted(() => {
  console.log('CPS SODetailView mounted with data:', props.soData);
});

const displaySoNumber = computed(() => {
  return props.soData?.order_info?.so_number || props.soData?.so_number || '';
});

const mainItem = computed(() => {
  const item = props.soData?.item_details?.[0] || {};
  return {
    item_code: item.item_code || props.soData?.order_info?.model || '',
    description: item.description || props.soData?.order_info?.model || '',
    pcs: item.pcs || props.soData?.order_info?.set_quantity || 0,
    unit: item.unit || 'PCS',
    order_qty: item.order_qty || props.soData?.order_info?.set_quantity || 0
  };
});

const cpsColumns = computed(() => {
  const fittings = props.soData?.fittings || [];
  const columns = [];

  // Selalu siapkan 9 kolom fitting (Fit1..Fit9) seperti CPS.
  // Jika data fitting kurang dari 9, kolom sisanya akan kosong.
  for (let i = 0; i < 9; i++) {
    columns.push(fittings[i] || {});
  }

  return columns;
});

const netDeliveredMain = computed(() => {
  const doSummary = props.soData?.delivery_order_summary || {};
  return doSummary['Main'] || doSummary['main'] || props.soData?.net_delivered || 0;
});

const balanceMain = computed(() => {
  const orderQty = parseFloat(props.soData?.order_info?.set_quantity || 0);
  const delivered = parseFloat(netDeliveredMain.value || 0);
  return orderQty - delivered;
});

const invoiceList = computed(() => {
  const summary = props.soData?.invoice_summary || {};
  return summary.invoice_list || [];
});

const getNetDeliveredForComponent = (component) => {
  if (!component) return 0;
  const doSummary = props.soData?.delivery_order_summary || {};
  return doSummary[component] || 0;
};

const getBalanceForComponent = (fitting) => {
  const orderQty = parseFloat(fitting.order_qty || 0);
  const delivered = parseFloat(getNetDeliveredForComponent(fitting.component) || 0);
  return orderQty - delivered;
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return date.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  } catch (e) {
    return dateString;
  }
};

const formatNumber = (num) => {
  if (num === null || num === undefined || num === '') return '';
  try {
    const number = parseFloat(num);
    if (isNaN(number)) return num;
    if (number === 0) return '';
    return number.toLocaleString('en-US', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    });
  } catch (e) {
    return num;
  }
};

// Formatter khusus untuk SO Balance: tetap menampilkan nilai 0 dari database
const formatBalanceNumber = (num) => {
  if (num === null || num === undefined || num === '') return '';
  try {
    const number = parseFloat(num);
    if (isNaN(number)) return num;
    return number.toLocaleString('en-US', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    });
  } catch (e) {
    return num;
  }
};

const formatPcsUnit = (pcs, unit) => {
  const p = formatNumber(pcs);
  const u = unit || '';
  if (!p && !u) return '';
  if (!p) return u;
  if (!u) return p + ' Pcs';
  return `${p} / ${u}`;
};
</script>

<style scoped>
/* Custom table styling */
table {
  font-size: 11px;
}

th {
  font-weight: 600;
}
</style>
