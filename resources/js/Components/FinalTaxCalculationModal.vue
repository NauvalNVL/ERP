<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[70]" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gradient-to-br from-gray-900/70 via-blue-900/50 to-gray-900/70 backdrop-blur-md" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-8 sm:scale-90" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-8 sm:scale-90">
            <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-gradient-to-br from-white to-blue-50 shadow-2xl ring-1 ring-blue-200/50">
              <!-- Header with Icon -->
              <div class="relative px-6 py-5 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 overflow-hidden">
                <div class="absolute inset-0 bg-grid-white/10"></div>
                <div class="relative flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                      </svg>
                    </div>
                    <DialogTitle class="text-lg font-bold text-white">Final Tax Calculation</DialogTitle>
                  </div>
                  <button @click="$emit('close')" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Content with modern cards -->
              <div class="p-6 space-y-4">
                <!-- Total Amount Card -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200/50 hover:shadow-md transition-shadow">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="p-2 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </div>
                      <label class="text-sm font-semibold text-gray-700">Total Amount</label>
                    </div>
                    <div class="text-right">
                      <div class="text-xl font-bold text-gray-900">{{ formatCurrency(totalAmount) }}</div>
                      <div class="text-xs text-gray-500 mt-0.5">Base invoice amount</div>
                    </div>
                  </div>
                </div>

                <!-- Tax Group Selection Card -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-200/50 hover:shadow-md transition-shadow">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <label class="text-sm font-semibold text-gray-700">Tax Group</label>
                  </div>
                  <select 
                    v-model="selectedTaxGroup"
                    class="w-full px-4 py-3 text-sm border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-blue-50/50 font-medium text-gray-900 transition-all"
                    @change="calculateTax"
                  >
                    <option value="">Select Tax Group...</option>
                    <option v-for="tax in taxOptions" :key="tax.code" :value="tax.code">
                      {{ tax.name }} ({{ tax.rate }}%)
                    </option>
                  </select>
                </div>

                <!-- Tax Amount Card (Highlighted like CPS) -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 shadow-lg border border-blue-400 transform hover:scale-[1.02] transition-transform">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                      </div>
                      <label class="text-sm font-semibold text-white">Tax Amount</label>
                    </div>
                    <div class="text-right">
                      <div class="text-2xl font-bold text-white">{{ formatCurrency(taxAmount) }}</div>
                      <div class="text-xs text-blue-100 mt-0.5">{{ selectedTaxRate }}% tax applied</div>
                    </div>
                  </div>
                </div>

                <!-- Divider with gradient -->
                <div class="relative py-2">
                  <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t-2 border-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                  </div>
                  <div class="relative flex justify-center">
                    <span class="px-3 bg-gradient-to-br from-white to-blue-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Calculation</span>
                  </div>
                </div>

                <!-- Net Amount Card (Final Result) -->
                <div class="bg-gradient-to-br from-emerald-500 via-green-500 to-emerald-600 rounded-xl p-5 shadow-xl border-2 border-emerald-400 transform hover:scale-[1.02] transition-transform">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="p-2.5 bg-white/25 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </div>
                      <label class="text-base font-bold text-white">Net Amount</label>
                    </div>
                    <div class="text-right">
                      <div class="text-3xl font-black text-white tracking-tight">{{ formatCurrency(netAmount) }}</div>
                      <div class="text-xs text-emerald-100 mt-1 font-medium">Final invoice total</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer Actions with modern design -->
              <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-blue-50 border-t border-gray-200 flex items-center justify-center gap-4">
                <button 
                  class="inline-flex items-center gap-2 px-8 py-3 text-sm font-semibold rounded-xl border-2 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm hover:shadow-md"
                  @click="$emit('close')"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Cancel
                </button>
                <button 
                  class="inline-flex items-center gap-2 px-10 py-3 text-base font-bold rounded-xl bg-gradient-to-r from-emerald-600 via-green-600 to-emerald-700 text-white hover:from-emerald-700 hover:via-green-700 hover:to-emerald-800 disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-400 transition-all shadow-lg hover:shadow-xl transform hover:scale-105"
                  :disabled="!selectedTaxGroup"
                  @click="handleOK"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

const selectedTaxRate = computed(() => {
  if (!selectedTaxGroup.value) return 0
  const tax = props.taxOptions.find(t => t.code === selectedTaxGroup.value)
  return tax?.rate || 0
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
