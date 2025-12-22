<template>
  <transition name="modal-fade">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div
        class="bg-white rounded-lg shadow-xl overflow-hidden w-11/12 max-w-7xl max-h-[95vh] flex flex-col"
      >
        <!-- Modal Header -->
        <div
          class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 border-b border-gray-200 flex items-center justify-between"
        >
          <div class="flex items-center space-x-3">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
              <i class="fas fa-cogs text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white">Machine Selecting Procedure</h3>
          </div>
          <button @click="close" class="text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="flex-grow overflow-auto p-6 bg-gray-50">
          <!-- Top Controls Section -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Release Mode -->
              <div class="flex items-center space-x-4">
                <label class="text-sm font-semibold text-gray-700">Release Mode:</label>
                <div class="flex items-center space-x-4">
                  <label class="flex items-center cursor-pointer">
                    <input type="radio" v-model="releaseMode" value="run" class="mr-2" />
                    <span class="text-sm">Run Production</span>
                  </label>
                  <label class="flex items-center cursor-pointer">
                    <input type="radio" v-model="releaseMode" value="no" class="mr-2" />
                    <span class="text-sm">No Production</span>
                  </label>
                </div>
              </div>

              <!-- Max FG Level -->
              <div class="flex items-center space-x-2">
                <label class="text-sm font-semibold text-gray-700 whitespace-nowrap"
                  >Max.FG Level:</label
                >
                <input
                  type="number"
                  v-model="maxFgLevel"
                  class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-right"
                  min="0"
                />
                <span class="text-xs text-gray-600"
                  >No w/Order once F/G on-hand hits maximum level: 0 = No Control</span
                >
              </div>
            </div>
          </div>

          <!-- Machine Selection Table -->
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
          >
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Step
                    </th>
                    <th
                      class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Mch Code
                    </th>
                    <th
                      class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Machine Name
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      P/Day
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      No /Up
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Setup Min
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Setup +/-
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Net Min
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Target Speed
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Speed +/-
                    </th>
                    <th
                      class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r"
                    >
                      Net Speed
                    </th>
                    <th
                      class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                    >
                      Special Instruction
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="(row, index) in mspRows"
                    :key="index"
                    class="hover:bg-blue-50 transition-colors"
                  >
                    <td
                      class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-r"
                    >
                      {{ row.step }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border-r">
                      <div class="flex items-center">
                        <input
                          type="text"
                          v-model="row.mchCode"
                          readonly
                          class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50"
                        />
                        <button
                          @click="openMachineSelector(index)"
                          class="ml-1 px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs transition-colors"
                        >
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 border-r">
                      <input
                        type="text"
                        v-model="row.machineName"
                        readonly
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.pDay"
                        min="0"
                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <div class="flex items-center justify-center space-x-1">
                        <input
                          type="number"
                          v-model="row.noUp1"
                          min="0"
                          :disabled="!row.mchCode"
                          class="w-12 px-1 py-1 border border-gray-300 rounded text-sm text-center"
                          :class="{ 'bg-gray-100 cursor-not-allowed': !row.mchCode }"
                        />
                        <span class="text-gray-500">/</span>
                        <input
                          type="number"
                          v-model="row.noUp2"
                          min="0"
                          :disabled="!row.mchCode"
                          class="w-12 px-1 py-1 border border-gray-300 rounded text-sm text-center"
                          :class="{ 'bg-gray-100 cursor-not-allowed': !row.mchCode }"
                        />
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.setupMin"
                        min="0"
                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.setupAdjust"
                        min="0"
                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.netMin"
                        readonly
                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-gray-50"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.targetSpeed"
                        min="0"
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.speedAdjust"
                        min="0"
                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                      />
                    </td>
                    <td class="px-4 py-3 text-center border-r">
                      <input
                        type="number"
                        v-model="row.netSpeed"
                        readonly
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-gray-50"
                      />
                    </td>
                    <td class="px-4 py-3">
                      <input
                        type="text"
                        v-model="row.specialInstruction"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                        placeholder="Enter special instruction..."
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Accumulated Total Section -->
            <div class="bg-gray-50 border-t border-gray-200 px-4 py-3">
              <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700"
                  >Accumulated Total:</span
                >
                <div class="flex items-center space-x-6">
                  <div class="flex items-center space-x-2">
                    <input
                      type="number"
                      :value="totalAccumulated1"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                    <input
                      type="number"
                      :value="totalAccumulated2"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                    <span class="text-gray-500">/</span>
                    <input
                      type="number"
                      :value="totalAccumulated3"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                  </div>
                  <span class="text-sm text-gray-600">Total Corr:</span>
                  <div class="flex items-center space-x-1">
                    <input
                      type="number"
                      :value="totalCorr1"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                    <span class="text-gray-500">/</span>
                    <input
                      type="number"
                      :value="totalCorr2"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                  </div>
                  <span class="text-sm text-gray-600">Total Conv:</span>
                  <div class="flex items-center space-x-1">
                    <input
                      type="number"
                      :value="totalConv1"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                    <span class="text-gray-500">/</span>
                    <input
                      type="number"
                      :value="totalConv2"
                      readonly
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-white font-semibold"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div
          class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end space-x-3"
        >
          <button
            @click="saveMspData"
            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-semibold flex items-center space-x-2"
          >
            <i class="fas fa-save"></i>
            <span>Save</span>
          </button>
          <button
            @click="close"
            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 font-semibold flex items-center space-x-2"
          >
            <i class="fas fa-times"></i>
            <span>Close</span>
          </button>
        </div>
      </div>

      <!-- Machine Selector Modal -->
      <MachineModal
        v-if="showMachineModal"
        :show="showMachineModal"
        :machines="machines"
        @close="showMachineModal = false"
        @select="onMachineSelected"
      />
    </div>
  </transition>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import MachineModal from "@/Components/MachineModal.vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  existingMspData: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(["close", "save"]);

// State
const releaseMode = ref("run");
const maxFgLevel = ref(0);
const showMachineModal = ref(false);
const selectedRowIndex = ref(null);
const machines = ref([]);

// Initialize MSP rows (Steps 10-80, matching the image)
const createEmptyMspRow = (step) => ({
  step,
  mchCode: "",
  machineName: "",
  pDay: null,
  noUp1: null,
  noUp2: null,
  setupMin: null,
  setupAdjust: null,
  netMin: null,
  targetSpeed: null,
  speedAdjust: null,
  netSpeed: null,
  specialInstruction: "",
});

const MSP_STEPS = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120];

const mspRows = ref(MSP_STEPS.map((s) => createEmptyMspRow(s)));

const resetMspRows = () => {
  mspRows.value = MSP_STEPS.map((s) => createEmptyMspRow(s));
};

// Computed totals
const totalAccumulated1 = computed(() => {
  return mspRows.value.reduce((sum, row) => sum + (parseInt(row.noUp1) || 0), 0);
});

const totalAccumulated2 = computed(() => {
  return mspRows.value.reduce((sum, row) => sum + (parseInt(row.noUp2) || 0), 0);
});

const totalAccumulated3 = computed(() => {
  return mspRows.value.reduce((sum, row) => sum + (parseInt(row.noUp1) || 0), 0);
});

const totalCorr1 = computed(() => {
  return mspRows.value
    .filter((row) => row.step === 10)
    .reduce((sum, row) => sum + (parseInt(row.noUp1) || 0), 0);
});

const totalCorr2 = computed(() => {
  return mspRows.value
    .filter((row) => row.step === 10)
    .reduce((sum, row) => sum + (parseInt(row.noUp2) || 0), 0);
});

const totalConv1 = computed(() => {
  return mspRows.value
    .filter((row) => row.step >= 20 && row.step <= 120)
    .reduce((sum, row) => sum + (parseInt(row.noUp1) || 0), 0);
});

const totalConv2 = computed(() => {
  return mspRows.value
    .filter((row) => row.step >= 20 && row.step <= 120)
    .reduce((sum, row) => sum + (parseInt(row.noUp2) || 0), 0);
});

const clampNonNegative = (value) => {
  const num = parseFloat(value);
  if (isNaN(num) || num < 0) {
    return 0;
  }
  return num;
};

// Watch for changes in setup and speed to calculate net values
watch(
  mspRows,
  (newRows) => {
    newRows.forEach((row) => {
      // Clamp base numeric fields to minimum 0
      if (row.pDay !== null && row.pDay !== undefined) {
        row.pDay = clampNonNegative(row.pDay);
      }
      if (row.noUp1 !== null && row.noUp1 !== undefined) {
        row.noUp1 = clampNonNegative(row.noUp1);
      }
      if (row.noUp2 !== null && row.noUp2 !== undefined) {
        row.noUp2 = clampNonNegative(row.noUp2);
      }
      if (row.setupMin !== null && row.setupMin !== undefined) {
        row.setupMin = clampNonNegative(row.setupMin);
      }
      if (row.setupAdjust !== null && row.setupAdjust !== undefined) {
        row.setupAdjust = clampNonNegative(row.setupAdjust);
      }
      if (row.targetSpeed !== null && row.targetSpeed !== undefined) {
        row.targetSpeed = clampNonNegative(row.targetSpeed);
      }
      if (row.speedAdjust !== null && row.speedAdjust !== undefined) {
        row.speedAdjust = clampNonNegative(row.speedAdjust);
      }

      // Calculate Net Min
      if (row.setupMin !== null && row.setupAdjust !== null) {
        const setupMinVal = clampNonNegative(row.setupMin);
        const setupAdjustVal = clampNonNegative(row.setupAdjust);
        row.netMin = setupMinVal + setupAdjustVal;
      }

      // Calculate Net Speed
      if (row.targetSpeed !== null && row.speedAdjust !== null) {
        const targetSpeedVal = clampNonNegative(row.targetSpeed);
        const speedAdjustVal = clampNonNegative(row.speedAdjust);
        row.netSpeed = targetSpeedVal + speedAdjustVal;
      }
    });
  },
  { deep: true }
);

// Fetch machines from API
const fetchMachines = async () => {
  try {
    const response = await fetch("/api/machines", {
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "same-origin",
    });

    if (!response.ok) {
      throw new Error("Failed to fetch machines");
    }

    const data = await response.json();
    machines.value = data.machines || [];

    // After loading machines, populate machine names for existing mchCodes
    mspRows.value.forEach((row) => {
      if (row.mchCode && !row.machineName) {
        const machine = machines.value.find((m) => m.machine_code === row.mchCode);
        if (machine) {
          row.machineName = machine.machine_name;
        }
      }
    });
  } catch (error) {
    console.error("Error fetching machines:", error);
    machines.value = [];
  }
};

// Open machine selector
const openMachineSelector = (index) => {
  selectedRowIndex.value = index;
  showMachineModal.value = true;
};

// Handle machine selection
const onMachineSelected = (machine) => {
  if (selectedRowIndex.value !== null) {
    const row = mspRows.value[selectedRowIndex.value];
    row.mchCode = machine.machine_code;
    row.machineName = machine.machine_name;
  }
  showMachineModal.value = false;
};

// Save MSP data
const saveMspData = () => {
  const machinesPayload = mspRows.value.map((row) => {
    const mch = row.mchCode && row.mchCode.trim() !== "" ? row.mchCode.trim() : null;
    if (!mch) {
      return {
        step: row.step,
        mchCode: null,
        machineName: "",
        noUp: null,
        specialInstruction: null,
      };
    }

    const up1 = row.noUp1 !== null && row.noUp1 !== undefined ? row.noUp1 : "";
    const up2 = row.noUp2 !== null && row.noUp2 !== undefined ? row.noUp2 : "";

    return {
      step: row.step,
      mchCode: mch,
      machineName: row.machineName,
      noUp: `${up1}/${up2}`,
      specialInstruction: row.specialInstruction || "",
    };
  });

  const mspData = {
    releaseMode: releaseMode.value,
    maxFgLevel: maxFgLevel.value,
    machines: machinesPayload,
  };

  emit("save", mspData);
  close();
};

// Close modal
const close = () => {
  emit("close");
};

// Load existing MSP data if provided
const applyExistingMspData = (newData) => {
  resetMspRows();

  if (!newData || typeof newData !== "object") {
    return;
  }

  if (Array.isArray(newData.machines)) {
    newData.machines.forEach((machine, idx) => {
      const step = machine?.step ?? MSP_STEPS[idx];
      const row = mspRows.value.find((r) => r.step === step) || mspRows.value[idx];
      if (!row) return;

      row.mchCode = (machine?.mchCode ?? "") + "";
      row.machineName = (machine?.machineName ?? "") + "";

      const noUpRaw = machine?.noUp ?? "";
      const parts = String(noUpRaw).split("/");
      row.noUp1 = parts[0] ? parseInt(parts[0]) : null;
      row.noUp2 = parts[1] ? parseInt(parts[1]) : null;

      row.specialInstruction = (machine?.specialInstruction ?? "") + "";
    });
    return;
  }

  for (let i = 1; i <= 12; i++) {
    if (newData[`msp${i}_mch`]) {
      const rowIndex = i - 1;
      if (rowIndex < mspRows.value.length) {
        mspRows.value[rowIndex].mchCode = newData[`msp${i}_mch`];

        if (newData[`msp${i}_up`]) {
          const upParts = String(newData[`msp${i}_up`]).split("/");
          mspRows.value[rowIndex].noUp1 = upParts[0] ? parseInt(upParts[0]) : null;
          mspRows.value[rowIndex].noUp2 = upParts[1] ? parseInt(upParts[1]) : null;
        }

        if (newData[`msp${i}_special_inst`]) {
          mspRows.value[rowIndex].specialInstruction = newData[`msp${i}_special_inst`];
        }
      }
    }
  }
};

watch(
  () => props.show,
  (val) => {
    if (val) {
      applyExistingMspData(props.existingMspData);
    }
  }
);

watch(
  () => props.existingMspData,
  (newData) => {
    if (props.show) {
      applyExistingMspData(newData);
    }
  },
  { deep: true }
);

// Fetch machines on mount
onMounted(() => {
  fetchMachines();
});
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Custom scrollbar */
.overflow-auto::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.overflow-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.overflow-auto::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.overflow-auto::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
