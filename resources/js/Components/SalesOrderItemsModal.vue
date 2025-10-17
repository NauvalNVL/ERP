<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[60]" @close="$emit('close')">
      <TransitionChild 
        as="template" 
        enter="ease-out duration-300" 
        enter-from="opacity-0" 
        enter-to="opacity-100" 
        leave="ease-in duration-200" 
        leave-from="opacity-100" 
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild 
            as="template" 
            enter="ease-out duration-300" 
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            enter-to="opacity-100 translate-y-0 sm:scale-100" 
            leave="ease-in duration-200" 
            leave-from="opacity-100 translate-y-0 sm:scale-100" 
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="w-full max-w-6xl transform overflow-hidden bg-white shadow-2xl rounded-xl">
              <!-- Header -->
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                <div class="flex items-center justify-between">
                  <DialogTitle class="text-sm font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Sales Order Items Screen
                  </DialogTitle>
                  <button @click="$emit('close')" class="text-white hover:bg-blue-800 p-1 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Toolbar -->
              <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
                <button @click="$emit('close')" class="p-1.5 hover:bg-gray-200 rounded border border-gray-300" title="Power Off">
                  <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button class="p-1.5 hover:bg-gray-200 rounded border border-gray-300" title="Print">
                  <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button class="p-1.5 hover:bg-gray-200 rounded border border-gray-300" title="End Process">
                  <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button @click="$emit('close')" class="p-1.5 hover:bg-gray-200 rounded border border-gray-300" title="Close">
                  <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>

              <!-- Content -->
              <div class="p-4">
                <!-- Header Info Row -->
                <div class="grid grid-cols-6 gap-4 mb-4 text-xs">
                  <div class="flex items-center gap-2">
                    <label class="font-semibold text-gray-700">D/Order#:</label>
                    <span class="font-medium text-blue-700">{{ doNumber }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <label class="font-semibold text-gray-700">D/O Date:</label>
                    <span>{{ doDate }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <label class="font-semibold text-gray-700">Control Set Order:</label>
                    <span>0</span>
                  </div>
                </div>

                <!-- Main Data Table -->
                <div class="border border-gray-300 rounded-md overflow-hidden mb-4">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-600 text-white">
                      <tr>
                        <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider">Line</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider">S/Order#</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider">M/Card Seq#</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider">D/O Date#</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr class="bg-yellow-100">
                        <td class="px-2 py-2 text-sm font-medium">1001</td>
                        <td class="px-2 py-2 text-sm">{{ soNumber }}</td>
                        <td class="px-2 py-2 text-sm">{{ mcSeq }}</td>
                        <td class="px-2 py-2 text-sm">{{ doDate }}</td>
                        <td class="px-2 py-2 text-sm text-right font-semibold">{{ formatCurrency(totalAmount) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- S/O Count and Totals -->
                <div class="grid grid-cols-4 gap-4 mb-4 text-xs">
                  <div class="flex items-center gap-2">
                    <label class="font-semibold text-gray-700">S/O Count:</label>
                    <span class="font-medium">1</span>
                  </div>
                  <div></div>
                  <div></div>
                  <div class="flex items-center gap-2 justify-end">
                    <label class="font-semibold text-gray-700">Total:</label>
                    <span class="font-bold text-blue-700">{{ formatCurrency(totalAmount) }}</span>
                  </div>
                </div>

                <!-- Model Info -->
                <div class="mb-4 text-xs">
                  <div class="flex items-center gap-2">
                    <label class="font-semibold text-gray-700">Model:</label>
                    <span class="font-medium text-blue-700">{{ model }}</span>
                  </div>
                </div>

                <!-- Item Details Table -->
                <div class="border border-gray-300 rounded-md overflow-hidden">
                  <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-100">
                      <tr>
                        <th class="px-2 py-2 text-left font-semibold text-gray-700">Item</th>
                        <th class="px-2 py-2 text-left font-semibold text-gray-700">P/Design</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">Pcs</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">Unit</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">U/Price</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">Deliver</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">Reject</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">UsBill</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">To Bill</th>
                        <th class="px-2 py-2 text-center font-semibold text-gray-700">To Bill KG</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr>
                        <td class="px-2 py-2 font-medium">Main</td>
                        <td class="px-2 py-2">B1</td>
                        <td class="px-2 py-2 text-center">1</td>
                        <td class="px-2 py-2 text-center">Pcs</td>
                        <td class="px-2 py-2 text-right">{{ formatCurrency(unitPrice) }}</td>
                        <td class="px-2 py-2 text-center">300</td>
                        <td class="px-2 py-2 text-center">-</td>
                        <td class="px-2 py-2 text-center">-</td>
                        <td class="px-2 py-2 text-center">300</td>
                        <td class="px-2 py-2 text-center">-</td>
                      </tr>
                      <tr v-for="index in 8" :key="index" class="bg-gray-50">
                        <td class="px-2 py-2 text-gray-400">Fit{{ index }}</td>
                        <td class="px-2 py-2 text-gray-400">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                        <td class="px-2 py-2 text-gray-400 text-center">-</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Footer -->
              <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-center gap-3">
                <button 
                  @click="$emit('close')"
                  class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button 
                  @click="$emit('close')"
                  class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  OK
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  open: { type: Boolean, default: false },
  doNumber: { type: String, default: '' },
  doDate: { type: String, default: '' },
  soNumber: { type: String, default: '' },
  mcSeq: { type: String, default: '' },
  model: { type: String, default: 'SHIPPING CASE ACOSTA JUMBO' },
  totalAmount: { type: Number, default: 0 },
  unitPrice: { type: Number, default: 14700000 },
})

const emit = defineEmits(['close'])

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value || 0)
}
</script>

<style scoped>
/* Custom table styling */
table {
  font-size: 11px;
}

/* Highlighted row */
.bg-yellow-100 {
  background-color: #fef3cd;
}
</style>
