<template>
  <transition name="modal-fade">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl overflow-hidden w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Printer Table</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body - Printer List Table -->
        <div class="flex-grow overflow-auto p-4">
          <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logical Name</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="(printer, index) in printers" 
                :key="index" 
                @click="selectPrinter(printer)"
                :class="{'bg-blue-100': selectedPrinter === printer, 'hover:bg-gray-50 cursor-pointer': true}"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ printer.code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ printer.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ printer.type }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ printer.driver }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ printer.logicalName }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="emitSelectedPrinter" 
            :disabled="!selectedPrinter"
            class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Select
          </button>
          <button 
            @click="close" 
            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      printers: [
        { code: '/EXCEL', name: 'Excel File', type: '3-Excel File', driver: 'Dump', logicalName: '' },
        { code: '/VIEWER', name: 'Report Viewer', type: '2-Viewer File', driver: 'Dump', logicalName: '' },
        { code: 'PRINTER01', name: 'EPSON LQ2180-SALES 1 ; IP 192.168.6.51', type: '1-Printer', driver: 'Epson', logicalName: 'dlk01_P1' },
        { code: 'PRINTER02', name: 'EPSON LQ2180-SALES 2 ; IP 192.168.6.52', type: '1-Printer', driver: 'Epson', logicalName: 'dlk02_P1' },
        { code: 'PRINTER03', name: 'EPSON LQ2180-SALES 3 ; IP 192.168.6.53', type: '1-Printer', driver: 'Epson', logicalName: 'dlk03_P1' },
        { code: 'PRINTER04', name: 'EPSON LQ2170-SALES 4 ; IP 192.168.6.54', type: '1-Printer', driver: 'Epson', logicalName: 'dlk04_P1' },
        { code: 'PRINTER05', name: 'EPSON LQ2190-EKSPEDISI ; IP 192.168.6.55', type: '1-Printer', driver: 'Epson', logicalName: 'dlk05_P1' },
        { code: 'PRINTER06', name: 'EPSON LQ2180-FINANCE 1 ; IP 192.168.6.56', type: '1-Printer', driver: 'Epson', logicalName: 'dlk06_P1' },
        { code: 'PRINTER07', name: 'EPSON LQ2180-FINANCE 2 ; IP 192.168.6.57', type: '1-Printer', driver: 'Epson', logicalName: 'dlk07_P1' },
        { code: 'PRINTER08', name: 'EPSON LQ2180-BACKUP1 ; IP 192.168.6.58', type: '1-Printer', driver: 'Epson', logicalName: 'dlk08_P1' },
        { code: 'PRINTER09', name: 'EPSON LQ2180-GUDANGSP ; IP 192.168.6.59', type: '1-Printer', driver: 'Epson', logicalName: 'dlk09_P1' },
        { code: 'PRINTER10', name: 'EPSON LQ2180-PPIC ; IP 192.168.6.60', type: '1-Printer', driver: 'Epson', logicalName: 'dlk10_P1' },
        { code: 'PRINTER11', name: 'EPSON LQ2190-FINANCE3 ; IP 192.168.6.61', type: '1-Printer', driver: 'Epson', logicalName: 'dlk11_P1' },
        { code: 'PRINTER12', name: 'EPSON LQ2180-FIN ; IP 192.168.7.51', type: '1-Printer', driver: 'Epson', logicalName: 'dlk12_P1' },
        { code: 'PRINTER13', name: 'EPSON LQ2180-ACC ; IP 192.168.7.52', type: '1-Printer', driver: 'Epson', logicalName: 'dlk13_P1' },
        { code: 'PRINTER14', name: 'EPSON LQ2180-PURCH ; IP 192.168.7.53', type: '1-Printer', driver: 'Epson', logicalName: 'dlk14_P1' },
        { code: 'PRINTER15', name: 'EPSON LQ2180-FIN2 ; IP 192.168.7.54', type: '1-Printer', driver: 'Epson', logicalName: 'dlk15_P1' },
        { code: 'PRINTER16', name: 'EPSON LQ2180-FINANCE KIM IP:11.48', type: '1-Printer', driver: 'Epson', logicalName: 'dlk16_P1' },
        { code: 'PRINTER17', name: 'EPSON LQ2180-FINANCE KIM IP:11.49', type: '1-Printer', driver: 'Epson', logicalName: 'dlk17_P1' },
        { code: 'PRINTER18', name: 'EPSON LQ-2180 ; IP: 192.168.6.62', type: '1-Printer', driver: 'Epson', logicalName: 'dlk18_P1' },
      ],
      selectedPrinter: null,
    };
  },
  methods: {
    selectPrinter(printer) {
      this.selectedPrinter = printer;
    },
    emitSelectedPrinter() {
      if (this.selectedPrinter) {
        this.$emit('printer-selected', this.selectedPrinter.code);
      }
      this.close();
    },
    close() {
      this.selectedPrinter = null; // Clear selection on close
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter, .modal-fade-leave-to {
  opacity: 0;
}
</style> 