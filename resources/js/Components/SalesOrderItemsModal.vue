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

              <!-- Toolbar - CPS ERP Style -->
              <div class="px-4 py-2 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-300 shadow-sm">
                <div class="flex items-center gap-2">
                  <!-- Exit/Close Button (Red) -->
                  <button 
                    @click="$emit('close')" 
                    class="p-2 hover:bg-red-100 rounded-md bg-white border-2 border-red-400 transition-all shadow-md hover:shadow-lg" 
                    title="Exit / Close"
                  >
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                  </button>

                  <!-- Delete Button (Red) -->
                  <button 
                    @click="handleDelete" 
                    class="p-2 hover:bg-red-100 rounded-md bg-white border-2 border-red-400 transition-all shadow-md hover:shadow-lg" 
                    title="Delete"
                  >
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                  </button>

                  <!-- Save/Update Button (Blue) -->
                  <button 
                    @click="handleSave" 
                    class="p-2 hover:bg-blue-100 rounded-md bg-white border-2 border-blue-500 transition-all shadow-md hover:shadow-lg" 
                    title="Save / Update"
                  >
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                  </button>

                  <!-- Enter/Return Button (Blue) -->
                  <button 
                    @click="handleConfirm" 
                    class="p-2 hover:bg-blue-100 rounded-md bg-white border-2 border-blue-500 transition-all shadow-md hover:shadow-lg" 
                    title="Enter / Return"
                  >
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </div>
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
              <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-t-2 border-gray-300 flex justify-center gap-3 shadow-inner">
                <button 
                  @click="$emit('close')"
                  class="px-8 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-400 rounded hover:bg-gray-50 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all shadow-sm"
                >
                  Cancel
                </button>
                <button 
                  @click="handleConfirm"
                  class="px-8 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 border-2 border-blue-600 rounded hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md"
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

const emit = defineEmits(['close', 'confirm', 'delete', 'save'])

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value || 0)
}

const handleConfirm = () => {
  console.log('✅ Sales Order Items confirmed, proceeding to Final Tax Calculation')
  emit('confirm', {
    doNumber: props.doNumber,
    totalAmount: props.totalAmount,
    model: props.model
  })
}

const handleDelete = () => {
  if (confirm('Delete this sales order item?')) {
    console.log('🗑️ Deleting sales order item')
    emit('delete')
  }
}

const handleSave = () => {
  console.log('💾 Saving sales order items')
  emit('save')
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
