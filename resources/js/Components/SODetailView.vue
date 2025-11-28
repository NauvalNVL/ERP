<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
      <!-- Header -->
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-semibold">SALES ORDER</h3>
        <div class="flex space-x-2">
          <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
            Print
          </button>
          <button
            @click="$emit('close')"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
          >
            Close
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1 overflow-auto p-4">
        <!-- SO Header Info -->
        <div class="grid grid-cols-4 gap-4 mb-4">
          <div>
            <div class="text-sm font-medium text-gray-500">SO #</div>
            <div class="font-semibold">{{ displaySoNumber }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-gray-500">Date</div>
            <div>{{ formatDate(soData.order_info?.so_date) || 'N/A' }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-gray-500">Customer</div>
            <div class="font-semibold">{{ soData.order_info?.customer_name || 'N/A' }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-gray-500">Salesperson</div>
            <div>{{ soData.order_info?.salesperson_name || 'N/A' }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-gray-500">Model</div>
            <div>{{ soData.order_info?.model || 'N/A' }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-gray-500">Order Qty</div>
            <div>{{ formatNumber(soData.order_info?.set_quantity) || '0' }}</div>
          </div>
          <div class="col-span-2">
            <div class="text-sm font-medium text-gray-500">Status</div>
            <div>
              <span :class="getStatusClass(soData.order_info?.so_status)">
                {{ soData.order_info?.so_status || 'N/A' }}
              </span>
            </div>
          </div>
        </div>

        <!-- CPS-style Summary Board -->
        <div class="mb-6 border rounded-lg overflow-hidden text-xs">
          <table class="min-w-full table-fixed">
            <thead>
              <tr>
                <th class="bg-gray-800 text-white px-3 py-2 text-left w-40">Item</th>
                <th
                  v-for="col in boardColumns"
                  :key="col.key"
                  class="bg-blue-900 text-white px-3 py-2 text-center"
                >
                  {{ col.label }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="row in boardRows"
                :key="row.key"
                :class="row.bg"
              >
                <td class="border px-3 py-2 font-semibold">
                  {{ row.label }}
                </td>
                <td
                  v-for="col in boardColumns"
                  :key="col.key"
                  class="border px-3 py-2 text-right"
                >
                  {{ getBoardCellValue(row.key, col) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Item Details Table -->
        <div class="mb-6">
          <h4 class="font-medium mb-2">Item Details</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Code</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PD</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PCS</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Qty</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Delivery</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="soData.item_details?.length">
                  <td class="border px-4 py-2">1</td>
                  <td class="border px-4 py-2">{{ soData.item_details[0].item_code || 'N/A' }}</td>
                  <td class="border px-4 py-2">{{ soData.item_details[0].description || 'N/A' }}</td>
                  <td class="border px-4 py-2">{{ soData.item_details[0].pd || 'N/A' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(soData.item_details[0].pcs) || '0' }}</td>
                  <td class="border px-4 py-2">{{ soData.item_details[0].unit || 'N/A' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(soData.item_details[0].order_qty) || '0' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(soData.item_details[0].net_delivery) || '0' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(soData.item_details[0].balance) || '0' }}</td>
                </tr>
                <tr v-else>
                  <td colspan="9" class="border px-4 py-2 text-center text-gray-500">No item details available</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Fittings Table -->
        <div class="mb-6">
          <h4 class="font-medium mb-2">Fittings</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fitting Code</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Design</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PCS</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Qty</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Delivery</th>
                  <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(fitting, index) in soData.fittings" :key="fitting.fitting_code">
                  <td class="border px-4 py-2">{{ index + 1 }}</td>
                  <td class="border px-4 py-2">{{ fitting.fitting_code || 'N/A' }}</td>
                  <td class="border px-4 py-2">{{ fitting.description || 'N/A' }}</td>
                  <td class="border px-4 py-2">{{ fitting.design || 'N/A' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(fitting.pcs) || '0' }}</td>
                  <td class="border px-4 py-2">{{ fitting.unit || 'N/A' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(fitting.order_qty) || '0' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(fitting.net_delivery) || '0' }}</td>
                  <td class="border px-4 py-2 text-right">{{ formatNumber(fitting.balance) || '0' }}</td>
                </tr>
                <tr v-if="!soData.fittings?.length">
                  <td colspan="9" class="border px-4 py-2 text-center text-gray-500">No fittings available</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-3 gap-6">
          <div>
            <h4 class="font-medium mb-2">Customer Information</h4>
            <div class="bg-gray-50 p-3 rounded border">
              <div class="whitespace-pre-line">{{ soData.order_info?.customer_address || 'N/A' }}</div>
              <div class="mt-2">
                <div class="font-medium">Customer PO #:</div>
                <div>{{ soData.order_info?.customer_po_number || 'N/A' }}</div>
              </div>
            </div>
          </div>

          <div>
            <h4 class="font-medium mb-2">Remarks</h4>
            <div class="bg-gray-50 p-3 rounded border h-full">
              {{ soData.order_info?.remark || 'No remarks' }}
            </div>
          </div>

          <div>
            <h4 class="font-medium mb-2">Instructions</h4>
            <div class="bg-gray-50 p-3 rounded border h-full">
              <div class="mb-2">{{ soData.order_info?.instruction1 || 'No instructions' }}</div>
              <div>{{ soData.order_info?.instruction2 || '' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-4 border-t flex justify-between items-center bg-gray-50">
        <div class="text-sm text-gray-500">
          Last updated: {{ formatDateTime(new Date()) }}
        </div>
        <div class="flex space-x-2">
          <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
            <i class="fas fa-print mr-1"></i> Print
          </button>
          <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
            <i class="fas fa-envelope mr-1"></i> Email
          </button>
          <button
            @click="$emit('close')"
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
          >
            <i class="fas fa-times mr-1"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
  soData: {
    type: Object,
    required: true,
    default: () => ({
      order_info: {},
      item_details: [],
      fittings: []
    })
  }
});

const emit = defineEmits(['close']);

const displaySoNumber = computed(() => {
  const info = (props.soData && props.soData.order_info) ? props.soData.order_info : {};
  if (info.so_number) {
    return info.so_number;
  }
  if (props.soData && props.soData.so_number) {
    return props.soData.so_number;
  }
  return 'N/A';
});

const mainItem = computed(() => {
  const d = props.soData ? props.soData.item_details : null;
  if (!d) return {};
  if (Array.isArray(d)) {
    return d[0] || {};
  }
  return d;
});

const fittingItems = computed(() => {
  const list = props.soData ? props.soData.fittings : [];
  if (!Array.isArray(list)) return [];
  return list;
});

const boardColumns = computed(() => {
  const cols = [
    { key: 'main', label: 'Main', source: 'main' },
  ];

  fittingItems.value.forEach((fit, index) => {
    if (index < 4) {
      cols.push({
        key: `fit${index + 1}`,
        label: `Fit ${index + 1}`,
        source: 'fit',
        index,
      });
    }
  });

  return cols;
});

const boardRows = [
  { key: 'pd', label: 'Product Design', bg: 'bg-yellow-200' },
  { key: 'pcs', label: 'PCS', bg: 'bg-yellow-100' },
  { key: 'unit', label: 'Unit', bg: 'bg-yellow-50' },
  { key: 'order_qty', label: 'Order', bg: 'bg-yellow-200' },
  { key: 'net_delivery', label: 'Net Delivery', bg: 'bg-yellow-100' },
  { key: 'balance', label: 'SO Balance', bg: 'bg-yellow-200' },
];

const getBoardCellValue = (rowKey, col) => {
  const item = col.source === 'main'
    ? (mainItem.value || {})
    : ((fittingItems.value[col.index] || {}));

  if (!item) return '';

  if (rowKey === 'pd') {
    return item.pd || item.design || '';
  }
  if (rowKey === 'pcs') {
    if (item.pcs === '' || item.pcs === null || item.pcs === undefined) return '';
    return formatNumber(item.pcs);
  }
  if (rowKey === 'unit') {
    return item.unit || '';
  }
  if (rowKey === 'order_qty') {
    if (item.order_qty === '' || item.order_qty === null || item.order_qty === undefined) return '';
    return formatNumber(item.order_qty);
  }
  if (rowKey === 'net_delivery') {
    if (item.net_delivery === '' || item.net_delivery === null || item.net_delivery === undefined) return '';
    return formatNumber(item.net_delivery);
  }
  if (rowKey === 'balance') {
    if (item.balance === '' || item.balance === null || item.balance === undefined) return '';
    return formatNumber(item.balance);
  }

  return '';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
  } catch (e) {
    return dateString;
  }
};

const formatDateTime = (date) => {
  if (!date) return '';
  return date.toLocaleString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  });
};

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0';
  return new Intl.NumberFormat('id-ID').format(Number(value));
};

const getStatusClass = (status) => {
  const statusMap = {
    'Outstanding': 'bg-yellow-100 text-yellow-800',
    'Partial': 'bg-blue-100 text-blue-800',
    'Completed': 'bg-green-100 text-green-800',
    'Closed': 'bg-gray-100 text-gray-800',
    'Cancelled': 'bg-red-100 text-red-800'
  };
  return `px-2 py-1 rounded text-xs font-medium ${statusMap[status] || 'bg-gray-100 text-gray-800'}`;
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
