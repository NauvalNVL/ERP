<template>
  <transition name="modal-fade">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl overflow-hidden w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">MSP Choice Table</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body - MSP Choice Tables -->
        <div class="flex-grow overflow-auto p-4 space-y-4">
          <!-- Top Table: Choice# and Compute Ups -->
          <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Choice#</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compute Ups</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(choice, index) in mspChoices" 
                  :key="index" 
                  @click="selectMspChoice(choice)"
                  :class="{'bg-blue-100': selectedMspChoice === choice, 'hover:bg-gray-50 cursor-pointer': true}"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ choice.choiceNum }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ choice.computeUps }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Bottom Table: Step, Machine, Machine Name, P/Day -->
          <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Step</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine Name</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Day</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(machine, index) in selectedMspChoiceMachines" 
                  :key="index" 
                  :class="{'bg-blue-100': false, 'hover:bg-gray-50 cursor-pointer': true}" <!-- No selection on this table for now -->
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ machine.step }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ machine.machine }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ machine.machineName }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ machine.pDay }}</td>
                </tr>
                <tr v-if="selectedMspChoiceMachines.length === 0">
                  <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">No machine data for this choice.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end space-x-3">
          <button 
            @click="emitSelectedMspChoice" 
            :disabled="!selectedMspChoice"
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
      mspChoices: [
        { choiceNum: 'SB', computeUps: 'Auto', machines: [
            { step: 10, machine: '01', machineName: 'CORR 250', pDay: 2 },
            { step: 20, machine: 'FG', machineName: 'FG', pDay: '' }
        ]},
        // Add more dummy data as needed
      ],
      selectedMspChoice: null,
    };
  },
  computed: {
    selectedMspChoiceMachines() {
      return this.selectedMspChoice ? this.selectedMspChoice.machines : [];
    }
  },
  methods: {
    selectMspChoice(choice) {
      this.selectedMspChoice = choice;
    },
    emitSelectedMspChoice() {
      if (this.selectedMspChoice) {
        this.$emit('msp-choice-selected', this.selectedMspChoice.choiceNum);
      }
      this.close();
    },
    close() {
      this.selectedMspChoice = null; 
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