<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[70]" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <!-- Header -->
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-50 to-white">
                <DialogTitle class="text-base font-semibold text-gray-800">Final Screen</DialogTitle>
              </div>

              <!-- Content -->
              <div class="p-6 space-y-4">
                <!-- Total Amount -->
                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-gray-700">Total Amount:</label>
                  <input 
                    type="text" 
                    :value="formatCurrency(totalAmount)"
                    class="px-3 py-2 text-sm text-right border rounded-md w-48 bg-gray-50 font-semibold"
                    readonly
                  />
                </div>

                <!-- Tax Group -->
                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-gray-700">Tax Group:</label>
                  <div class="w-48">
                    <select 
                      v-model="selectedTaxGroup"
                      class="w-full px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-blue-500"
                      @change="calculateTax"
                    >
                      <option value="">Select Tax</option>
                      <option v-for="tax in taxOptions" :key="tax.code" :value="tax.code">
                        {{ tax.name }}
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Tax Amount (calculated) -->
                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-gray-700">Tax Amount:</label>
                  <input 
                    type="text" 
                    :value="formatCurrency(taxAmount)"
                    class="px-3 py-2 text-sm text-right border rounded-md w-48 bg-blue-50 font-semibold text-blue-700"
                    readonly
                  />
                </div>

                <!-- Divider -->
                <div class="border-t pt-4">
                  <!-- Net Amount -->
                  <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-gray-800">Net Amount:</label>
                    <input 
                      type="text" 
                      :value="formatCurrency(netAmount)"
                      class="px-3 py-2 text-sm text-right border-2 border-green-500 rounded-md w-48 bg-green-50 font-bold text-green-700"
                      readonly
                    />
                  </div>
                </div>
              </div>

              <!-- Footer Actions -->
              <div class="px-5 py-4 border-t bg-gray-50 flex items-center justify-end gap-2">
                <button 
                  class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="$emit('close')"
                >
                  Cancel
                </button>
                <button 
                  class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                  :disabled="!selectedTaxGroup"
                  @click="handleOK"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
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
  totalAmount: { type: Number, default: 0 },
  taxCode: { type: String, default: '' },
  taxOptions: { type: Array, default: () => [] },
})

const emit = defineEmits(['close', 'confirm'])

const selectedTaxGroup = ref('')
const taxAmount = ref(0)

// Watch for tax code changes
watch(() => props.taxCode, (newVal) => {
  if (newVal) {
    selectedTaxGroup.value = newVal
    calculateTax()
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (isOpen && props.taxCode) {
    selectedTaxGroup.value = props.taxCode
    calculateTax()
  }
})

const netAmount = computed(() => {
  return props.totalAmount + taxAmount.value
})

const calculateTax = () => {
  if (!selectedTaxGroup.value) {
    taxAmount.value = 0
    return
  }
  
  const tax = props.taxOptions.find(t => t.code === selectedTaxGroup.value)
  if (tax && tax.rate) {
    taxAmount.value = props.totalAmount * (tax.rate / 100)
  } else {
    taxAmount.value = 0
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value || 0)
}

const handleOK = () => {
  emit('confirm', {
    taxCode: selectedTaxGroup.value,
    taxAmount: taxAmount.value,
    netAmount: netAmount.value
  })
}
</script>
