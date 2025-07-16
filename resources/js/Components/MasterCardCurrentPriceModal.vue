<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-blue-50 via-cyan-50 to-white rounded-xl shadow-2xl w-full max-w-2xl mx-auto max-h-[95vh] flex flex-col overflow-hidden border-2 border-blue-200 animate-fadeIn">
      <!-- Modal Header -->
      <div class="flex items-center justify-between px-6 py-3 border-b-2 border-blue-200 bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-400 text-white">
        <h3 class="text-lg md:text-xl font-bold tracking-wide drop-shadow flex items-center gap-2">
          <i class="fas fa-money-check-alt text-white text-xl"></i>
          Zoom Master Card Current Price
        </h3>
        <button type="button" @click="$emit('close')" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-500 bg-opacity-30 hover:bg-opacity-60 transition-colors focus:outline-none">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>
      <!-- Modal Body -->
      <div class="p-6 space-y-4 bg-gradient-to-br from-white via-blue-50 to-cyan-50 flex-1 overflow-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
          <div class="flex items-center gap-2">
            <label class="font-semibold text-gray-700 w-32">Effective Date:</label>
            <input type="text" :value="mcData.effective_date" readonly class="flex-1 border border-blue-200 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono text-sm shadow-sm" />
          </div>
          <div class="flex items-center gap-2">
            <label class="font-semibold text-gray-700 w-20">Currency:</label>
            <input type="text" :value="mcData.currency" readonly class="flex-1 border border-blue-200 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono text-sm shadow-sm" />
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
          <div class="flex items-center gap-2">
            <label class="font-semibold text-gray-700 w-32">Reference:</label>
            <input type="text" :value="mcData.reference" readonly class="flex-1 border border-blue-200 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono text-sm shadow-sm" />
          </div>
          <div class="flex items-center gap-2">
            <label class="font-semibold text-gray-700 w-20">Remark:</label>
            <input type="text" :value="mcData.remark" readonly class="flex-1 border border-blue-200 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono text-sm shadow-sm" />
          </div>
        </div>
        <hr class="my-2 border-blue-200" />
        <div class="overflow-x-auto rounded-lg shadow">
          <table class="min-w-full border border-blue-200 text-sm bg-white rounded-lg overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-300 text-white">
              <tr>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">C#</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">PD</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">PCS</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">AREA (M2)</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">UNIT</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">PACKING</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">UNIT PRICE</th>
                <th class="px-2 py-2 border-b-2 border-blue-200 font-bold">M.O.Q</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-blue-100">
              <tr class="bg-gradient-to-r from-blue-100 via-cyan-100 to-white hover:from-blue-200 hover:to-cyan-200 transition-all">
                <td class="px-2 py-1 border-b border-blue-100 font-semibold">Main</td>
                <td class="px-2 py-1 border-b border-blue-100">B1</td>
                <td class="px-2 py-1 border-b border-blue-100">1</td>
                <td class="px-2 py-1 border-b border-blue-100">0.3314</td>
                <td class="px-2 py-1 border-b border-blue-100">Pcs</td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
              </tr>
              <tr v-for="fit in 9" :key="fit" class="hover:bg-blue-50 transition-all">
                <td class="px-2 py-1 border-b border-blue-100">Fit {{ fit }}</td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
                <td class="px-2 py-1 border-b border-blue-100"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-4 gap-2">
          <div class="flex items-center gap-2">
            <label class="font-semibold text-gray-700">Set:</label>
            <input type="text" :value="mcData.set" readonly class="border border-blue-200 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono text-sm shadow-sm w-32" />
          </div>
        </div>
      </div>
      <!-- Modal Footer -->
      <div class="flex justify-end gap-4 px-6 py-4 bg-gradient-to-r from-blue-100 via-cyan-100 to-white border-t border-blue-200">
        <button disabled class="px-7 py-2 rounded-lg font-bold text-gray-400 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-400 shadow-md cursor-not-allowed">Zoom</button>
        <button @click="$emit('close')" class="px-7 py-2 rounded-lg font-bold text-white bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-400 shadow-md hover:from-blue-600 hover:to-cyan-600 transition-all focus:outline-none focus:ring-2 focus:ring-blue-300">Exit</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  show: Boolean,
  mcData: Object,
});

const tableRows = [
  {
    c: 'Main',
    pd: 'B1',
    pcs: '1',
    area: '0.3314',
    unit: 'Pcs',
    packing: '',
    unit_price: '',
    moq: '',
  },
  { c: 'Fit 1', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 2', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 3', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 4', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 5', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 6', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 7', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 8', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
  { c: 'Fit 9', pd: '', pcs: '', area: '', unit: '', packing: '', unit_price: '', moq: '' },
];
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
  animation: fadeIn 0.25s ease-out;
}
</style> 