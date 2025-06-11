<template>
  <transition name="modal-fade">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl overflow-hidden w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Analysis Code Table</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body - Analysis Code List Table -->
        <div class="flex-grow overflow-auto p-4">
          <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group2</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="(analysisCode, index) in filteredAnalysisCodes" 
                :key="index" 
                @click="selectAnalysisCode(analysisCode)"
                :class="{'bg-blue-100': selectedAnalysisCode === analysisCode, 'hover:bg-gray-50 cursor-pointer': true}"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ analysisCode.code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ analysisCode.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ analysisCode.group }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ analysisCode.group2 }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="emitSelectedAnalysisCode" 
            :disabled="!selectedAnalysisCode"
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
  props: {
    modalType: {
      type: String,
      required: true,
      validator: (value) => ['amend', 'cancel', 'close'].includes(value),
    },
  },
  data() {
    return {
      allAnalysisCodes: {
        amend: [
          { code: 'AM01', name: 'EDIT SO', group: 'SO', group2: 'AM' },
          { code: 'AM02', name: 'EDIT HARGA SO MISS TRIM', group: 'SO', group2: 'AM' },
          { code: 'AM03', name: 'EDIT HARGA', group: 'SO', group2: 'AM' },
        ],
        cancel: [
          { code: 'CL01', name: 'SALAH INPUT', group: 'SO', group2: 'CL' },
          { code: 'CL02', name: 'PO BATAL (PERMINTAAN CUSTOMER)', group: 'SO', group2: 'CL' },
        ],
        close: [
          { code: 'CM01', name: 'GANTI HARGA BARU', group: 'SO', group2: 'CM' },
          { code: 'CM02', name: 'DISCONTINUE', group: 'SO', group2: 'CM' },
          { code: 'CM03', name: 'PO TIDAK LUNAS/KURANG PRODUKSI', group: 'SO', group2: 'CM' },
          { code: 'CM04', name: 'PO SELESAI', group: 'SO', group2: 'CM' },
        ],
      },
      selectedAnalysisCode: null,
    };
  },
  computed: {
    filteredAnalysisCodes() {
      return this.allAnalysisCodes[this.modalType] || [];
    },
  },
  methods: {
    selectAnalysisCode(code) {
      this.selectedAnalysisCode = code;
    },
    emitSelectedAnalysisCode() {
      if (this.selectedAnalysisCode) {
        this.$emit('analysis-code-selected', this.selectedAnalysisCode.code);
      }
      this.close();
    },
    close() {
      this.selectedAnalysisCode = null; 
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