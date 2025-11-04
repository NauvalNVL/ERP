<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 py-8">
      <div class="bg-white rounded-lg shadow-2xl w-full max-w-3xl mx-auto relative animate-fade-in-up max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-4 rounded-t-lg shadow-md flex items-center justify-between flex-shrink-0">
          <div class="flex items-center gap-2">
            <i class="fas fa-unlock-alt text-white text-2xl"></i>
            <h2 class="text-xl font-bold text-white">Release Approved MC</h2>
          </div>
          <button @click="$emit('close')" class="text-white hover:text-gray-100 text-2xl">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 flex-grow overflow-y-auto">
          <!-- Top fields: AC, Customer, MCS, Model -->
          <div class="space-y-3 mb-4">
            <div class="grid grid-cols-12 gap-3 items-center">
              <label class="col-span-2 text-sm font-medium text-gray-700">AC#:</label>
              <input :value="customerCode" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              <input :value="customerName" readonly class="col-span-7 px-2 py-1 border rounded bg-gray-50" />
            </div>
            <div class="grid grid-cols-12 gap-3 items-center">
              <label class="col-span-2 text-sm font-medium text-gray-700">MCS#:</label>
              <input :value="mcsFrom" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              <label class="col-span-1 text-sm font-medium text-gray-700 text-right">Model:</label>
              <input :value="selectedMc?.mc_model || ''" readonly class="col-span-6 px-2 py-1 border rounded bg-gray-50" />
            </div>
          </div>

          <!-- Logs sections -->
          <div class="space-y-4">
            <!-- Last Maintenance Log -->
            <div class="border rounded p-4">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Last Maintenance Log</h3>
              <div class="grid grid-cols-12 gap-3 items-center mb-2">
                <label class="col-span-2 text-sm text-gray-700">Process:</label>
                <input :value="lastMaintenance.process" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
              <div class="grid grid-cols-12 gap-3 items-center mb-2">
                <label class="col-span-2 text-sm text-gray-700">User ID:</label>
                <input :value="lastMaintenance.user" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
              <div class="grid grid-cols-12 gap-3 items-center">
                <label class="col-span-2 text-sm text-gray-700">Date:</label>
                <input :value="lastMaintenance.date" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
                <label class="col-span-2 text-sm text-gray-700 text-right">Time:</label>
                <input :value="lastMaintenance.time" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
            </div>

            <!-- Last Approval Log -->
            <div class="border rounded p-4">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Last Approval Log</h3>
              <div class="grid grid-cols-12 gap-3 items-center mb-2">
                <label class="col-span-2 text-sm text-gray-700">User ID:</label>
                <input :value="lastApproval.user" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
              <div class="grid grid-cols-12 gap-3 items-center">
                <label class="col-span-2 text-sm text-gray-700">Date:</label>
                <input :value="lastApproval.date" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
                <label class="col-span-2 text-sm text-gray-700 text-right">Time:</label>
                <input :value="lastApproval.time" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
            </div>

            <!-- Current Release Log -->
            <div class="border rounded p-4">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Current Release Log</h3>
              <div class="grid grid-cols-12 gap-3 items-center mb-2">
                <label class="col-span-2 text-sm text-gray-700">User ID:</label>
                <input :value="currentRelease.user" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
              <div class="grid grid-cols-12 gap-3 items-center">
                <label class="col-span-2 text-sm text-gray-700">Date:</label>
                <input :value="currentRelease.date" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
                <label class="col-span-2 text-sm text-gray-700 text-right">Time:</label>
                <input :value="currentRelease.time" readonly class="col-span-3 px-2 py-1 border rounded bg-gray-50" />
              </div>
            </div>

            <!-- Active W/Order -->
            <div class="grid grid-cols-12 gap-3 items-center">
              <label class="col-span-2 text-sm font-medium text-gray-700">Active W/Order:</label>
              <input :value="selectedMc?.active_work_orders ?? 0" readonly class="col-span-2 px-2 py-1 border rounded bg-gray-50" />
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-100 px-4 py-3 sm:px-6 flex justify-end rounded-b-lg flex-shrink-0">
          <button class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm items-center mr-2" @click="$emit('close')">
            Close
          </button>
          <button class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-base font-medium text-white hover:from-green-700 hover:to-emerald-700 sm:ml-3 sm:w-auto sm:text-sm items-center">
            Proceed
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed, ref } from 'vue';
// defineProps and defineEmits are compiler macros and don't need to be imported

const props = defineProps({
  show: Boolean,
  customerCode: String,
  customerName: String,
  mcsFrom: String,
  mcsTo: String,
  masterCards: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close']);

// Selected MC by exact MCS#
const selectedMc = computed(() => {
  return props.masterCards.find(mc => String(mc.mc_seq) === String(props.mcsFrom)) || null;
});

// Placeholder logs (can be replaced with real data when available)
const lastMaintenance = ref({ process: 'Amendment', user: 'mc01', date: '01/11/2018', time: '08:43' });
const lastApproval = ref({ user: 'user', date: '26/08/2025', time: '16:15' });
const currentRelease = ref({ user: 'user', date: '26/08/2025', time: '16:24' });
</script>

<style scoped>
/* Add common styles or override if necessary */
.animate-fade-in-up {
  animation: fade-in-up 0.5s ease-out forwards;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Custom Scrollbar for the modal's table */
.custom-scrollbar {
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #a78bfa #e0e7ff; /* thumb and track color for Firefox */
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* width of the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #e0e7ff; /* color of the track */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #a78bfa; /* color of the scroll thumb */
    border-radius: 10px; /* roundness of the scroll thumb */
    border: 2px solid #e0e7ff; /* creates padding around scroll thumb */
}
</style> 