<template>
  <Transition leave-active-class="duration-200">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape="closeModal">
      <div class="flex min-h-full items-center justify-center p-4 text-center">
        <Transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div
            v-if="show"
            class="w-full max-w-2xl transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all"
          >
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Machine Table</h3>

            <!-- Search Input -->
            <div class="mb-4">
              <input
                type="text"
                v-model="searchTerm"
                placeholder="Search by Code or Name..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>

            <!-- Machine Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-md shadow-sm mb-4" style="max-height: 400px;">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Code
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Process
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Sub-Process
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="machine in filteredMachines"
                    :key="machine.code"
                    @click="selectMachine(machine)"
                    :class="{ 'bg-indigo-50 cursor-pointer': true, 'hover:bg-indigo-100': true, 'bg-blue-100': selectedMachine && selectedMachine.code === machine.code }"
                  >
                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ machine.code }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                      {{ machine.name }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                      {{ machine.process }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                      {{ machine.subProcess }}
                    </td>
                  </tr>
                  <tr v-if="filteredMachines.length === 0">
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                      No machines found.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Buttons -->
            <div class="mt-4 flex justify-between space-x-2">
              <button
                type="button"
                @click="sortMachines"
                class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                Sort By Machine Name
              </button>
              <div class="flex space-x-2">
                <button
                  type="button"
                  @click="emitSelected"
                  :disabled="!selectedMachine"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Select
                </button>
                <button
                  type="button"
                  @click="closeModal"
                  class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                  Exit
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'select']);

const searchTerm = ref('');
const selectedMachine = ref(null);
const sortAscending = ref(true); // For sorting by name

const dummyMachines = ref([
  { code: '01', name: 'CORR 250', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '02', name: 'RSS', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '08', name: 'RDC', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '10', name: 'EXCT', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '12', name: 'ASS', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '16', name: 'SORTIR', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '17', name: 'GL ROLL', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '1A', name: 'CORR 200', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '1B', name: 'CORR 250XIM', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '21', name: 'STITCH B', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '2104', name: 'CORR SF', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '2105', name: 'CORR 250 BHS', process: 'CORRUGATING', subProcess: 'CORRUGATING' },
  { code: '22', name: 'GLUE B', process: 'CONVERTING', subProcess: 'FINISHER' },
  { code: '25', name: 'FPS II', process: 'CONVERTING', subProcess: 'PRINTER' },
  { code: '2509', name: 'FD 9', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '2510', name: 'FD 10', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '2511', name: 'FD 11', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '2512', name: 'FD 12', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '2513', name: 'FD 13', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
  { code: '2514', name: 'DIE CUT CENT', process: 'CONVERTING', subProcess: 'DIE CUTTER' },
]);

const filteredMachines = computed(() => {
  let machines = [...dummyMachines.value]; // Create a copy to sort
  const query = searchTerm.value.toLowerCase();

  if (query) {
    machines = machines.filter(
      (machine) =>
        machine.code.toLowerCase().includes(query) ||
        machine.name.toLowerCase().includes(query) ||
        machine.process.toLowerCase().includes(query) ||
        machine.subProcess.toLowerCase().includes(query)
    );
  }

  // Apply sorting
  machines.sort((a, b) => {
    const nameA = a.name.toLowerCase();
    const nameB = b.name.toLowerCase();
    if (nameA < nameB) return sortAscending.value ? -1 : 1;
    if (nameA > nameB) return sortAscending.value ? 1 : -1;
    return 0;
  });

  return machines;
});

const sortMachines = () => {
  sortAscending.value = !sortAscending.value; // Toggle sort order
};

const selectMachine = (machine) => {
  selectedMachine.value = machine;
};

const emitSelected = () => {
  if (selectedMachine.value) {
    emit('select', selectedMachine.value);
    closeModal();
  }
};

const closeModal = () => {
  searchTerm.value = ''; // Reset search term
  selectedMachine.value = null; // Clear selection
  emit('close');
};

watch(
  () => props.show,
  (newVal) => {
    if (!newVal) {
      // Reset state when modal is closed
      searchTerm.value = '';
      selectedMachine.value = null;
    }
  }
);
</script>

<style scoped>
/* Add any specific styling for the modal here */
</style> 