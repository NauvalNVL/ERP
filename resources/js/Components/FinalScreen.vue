<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[70]" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gradient-to-br from-gray-900/70 via-blue-900/50 to-gray-900/70 backdrop-blur-md" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-8 sm:scale-90" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-8 sm:scale-90">
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-lg bg-white shadow-2xl">
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
                    <DialogTitle class="text-lg font-bold text-white">Final Screen</DialogTitle>
                  </div>
                  <button @click="$emit('close')" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Content - Simplified CPS Style -->
              <div class="p-5 space-y-3">
                <!-- Total Amount -->
                <div class="flex items-center">
                  <label class="w-32 text-sm font-semibold text-gray-700">Total Amount:</label>
                  <div class="flex-1">
                    <input 
                      type="text"
                      :value="formatCurrency(totalAmount)"
                      readonly
                      class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded text-right font-bold text-gray-900"
                    />
                  </div>
                </div>

                <!-- Tax Group -->
                <div class="flex items-center">
                  <label class="w-32 text-sm font-semibold text-gray-700">Tax Group:</label>
                  <div class="flex-1">
                    <select 
                      v-model="selectedTaxGroup"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white font-medium text-gray-900"
                      @change="calculateTax"
                    >
                      <option value="">Select Tax Group...</option>
                      <option v-for="tax in taxOptions" :key="tax.code" :value="tax.code">
                        {{ tax.name }} ({{ tax.rate }}%)
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Tax Amount (Highlighted Blue like CPS) -->
                <div class="flex items-center bg-blue-500 p-2 rounded">
                  <label class="w-32 text-sm font-bold text-white">{{ selectedTaxGroup || 'M Taf/211' }}:</label>
                  <div class="flex-1">
                    <input 
                      type="text"
                      :value="formatCurrency(taxAmount)"
                      readonly
                      class="w-full px-3 py-2 text-sm bg-blue-400 border-2 border-blue-300 rounded text-right font-bold text-white"
                    />
                  </div>
                </div>

                <!-- Net Amount -->
                <div class="flex items-center">
                  <label class="w-32 text-sm font-semibold text-gray-700">Net Amount:</label>
                  <div class="flex-1">
                    <input 
                      type="text"
                      :value="formatCurrency(netAmount)"
                      readonly
                      class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded text-right font-bold text-green-600"
                    />
                  </div>
                </div>
              </div>

              <!-- Footer Actions - Simple CPS Style -->
              <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-center gap-3">
                <button 
                  class="px-10 py-2 text-sm font-semibold rounded border-2 border-gray-400 bg-white text-gray-700 hover:bg-gray-100 transition-colors"
                  @click="handleOK"
                  :disabled="!selectedTaxGroup"
                >
                  OK
                </button>
                <button 
                  class="px-10 py-2 text-sm font-semibold rounded border-2 border-gray-400 bg-white text-gray-700 hover:bg-gray-100 transition-colors"
                  @click="$emit('close')"
                >
                  Cancel
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
  customerCode: { type: String, default: '' },
  customerName: { type: String, default: '' },
  doNumber: { type: String, default: '' },
  doDate: { type: String, default: '' },
})

const emit = defineEmits(['close', 'confirm'])

// Simple state for tax calculation
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
  if (isOpen) {
    // Reset and initialize tax when modal opens
    if (props.taxCode) {
      selectedTaxGroup.value = props.taxCode
      calculateTax()
    } else {
      selectedTaxGroup.value = ''
      taxAmount.value = 0
    }
    
    // Warn if total amount is 0
    if (!props.totalAmount || props.totalAmount === 0) {
      console.warn('⚠️ Total Amount is 0! Did user input To Bill quantity in Sales Order Items?')
    }
    
    console.log('📋 Final Screen opened with:', {
      doNumber: props.doNumber,
      totalAmount: props.totalAmount,
      taxCode: props.taxCode,
      formatted: formatCurrency(props.totalAmount)
    })
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
  if (!selectedTaxGroup.value) {
    console.warn('⚠️ Tax Group must be selected')
    return
  }
  
  console.log('✅ Final Screen confirmed:', {
    taxCode: selectedTaxGroup.value,
    taxAmount: taxAmount.value,
    totalAmount: props.totalAmount,
    netAmount: netAmount.value
  })
  
  emit('confirm', {
    taxCode: selectedTaxGroup.value,
    taxAmount: taxAmount.value,
    totalAmount: props.totalAmount,
    netAmount: netAmount.value
  })
}
</script>
