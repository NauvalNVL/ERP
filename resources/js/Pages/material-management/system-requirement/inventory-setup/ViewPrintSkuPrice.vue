<template>
  <div class="p-6 bg-white">
    <div class="print:hidden mb-6 flex justify-between">
      <h1 class="text-2xl font-bold">{{ title }}</h1>
      <button @click="print" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        <i class="fas fa-print mr-2"></i> Print
      </button>
    </div>

    <div class="print:block">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">{{ title }}</h1>
        <p class="text-gray-600">Generated on: {{ date }}</p>
      </div>

      <table class="min-w-full border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2 border text-left">SKU#</th>
            <th class="px-4 py-2 border text-left">STS</th>
            <th class="px-4 py-2 border text-left">SKU Name</th>
            <th class="px-4 py-2 border text-left">Category</th>
            <th class="px-4 py-2 border text-left">Type</th>
            <th class="px-4 py-2 border text-left">UOM</th>
            <th class="px-4 py-2 border text-right">BOH</th>
            <th class="px-4 py-2 border text-right">FPO</th>
            <th class="px-4 py-2 border text-right">ROL</th>
            <th class="px-4 py-2 border text-right">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sku in skus" :key="sku.sku" class="hover:bg-gray-50">
            <td class="px-4 py-2 border">{{ sku.sku }}</td>
            <td class="px-4 py-2 border">{{ sku.sts }}</td>
            <td class="px-4 py-2 border">{{ sku.sku_name }}</td>
            <td class="px-4 py-2 border">{{ sku.category_name || sku.category_code }}</td>
            <td class="px-4 py-2 border">{{ sku.type }}</td>
            <td class="px-4 py-2 border">{{ sku.uom }}</td>
            <td class="px-4 py-2 border text-right">{{ sku.boh }}</td>
            <td class="px-4 py-2 border text-right">{{ sku.fpo }}</td>
            <td class="px-4 py-2 border text-right">{{ sku.rol }}</td>
            <td class="px-4 py-2 border text-right">{{ formatCurrency(sku.price) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-6 text-sm text-gray-600">
        <p>Total SKUs: {{ skus.length }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  skus: Array,
  title: String,
  date: String
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2
  }).format(value || 0);
};

const print = () => {
  window.print();
};
</script>

<style>
@media print {
  @page {
    size: landscape;
  }
  
  body {
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
  }
  
  .print\\:hidden {
    display: none !important;
  }
  
  .print\\:block {
    display: block !important;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    padding: 8px;
    border: 1px solid #ddd;
  }
  
  thead {
    background-color: #f3f4f6 !important;
  }
}
</style> 