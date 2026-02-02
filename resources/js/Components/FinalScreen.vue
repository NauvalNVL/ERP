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
                      :value="formatCurrency(displayTotalAmount)"
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
                      class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded text-left font-medium text-gray-900"
                    >
                      <option value="">Select Tax</option>
                      <option
                        v-for="t in normalizedTaxOptions"
                        :key="t.code"
                        :value="t.code"
                      >
                        {{ t.name }} ({{ t.rate }}%)
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Tax Amount (Highlighted Blue like CPS) -->
                <div class="flex items-center bg-blue-500 p-2 rounded">
                  <label class="w-32 text-sm font-bold text-white">{{ selectedTaxGroup || 'PPN11' }}:</label>
                  <div class="flex-1">
                    <input
                      type="text"
                      :value="formatCurrencyForDisplay(taxAmount)"
                      readonly
                      class="w-full px-3 py-2 text-sm !bg-blue-400 border-2 border-blue-300 rounded text-right font-bold !text-white placeholder-white"
                      :placeholder="formatCurrencyForDisplay(0)"
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
                  @click="$emit('close')"
                >
                  Cancel
                </button>
                <button
                  class="px-10 py-2 text-sm font-semibold rounded border-2 border-gray-400 bg-white text-gray-700 hover:bg-gray-100 transition-colors"
                  @click="handleOK"
                  :disabled="!selectedTaxGroup"
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

const normalizedTaxOptions = computed(() => {
  const raw = Array.isArray(props.taxOptions) ? props.taxOptions : []
  return raw
    .map((t) => {
      const code = (t?.code ?? t?.tax_code ?? t?.Code ?? t?.TAX_CODE ?? '').toString()
      const name = (t?.name ?? t?.tax_name ?? t?.TAXNAME ?? code).toString()
      const rateRaw = t?.rate ?? t?.tax_rate ?? t?.RATEPPN ?? 0
      const rate = Number(rateRaw) || 0
      const includeRaw = t?.include ?? t?.Include ?? t?.is_include ?? t?.isInclude ?? false
      const include = includeRaw === true || includeRaw === 1 || String(includeRaw).toUpperCase() === 'Y' || String(includeRaw).toUpperCase() === 'YES'
      return { code, name, rate, include }
    })
    .filter((t) => t.code && t.code.trim() !== '')
})

const round2 = (value) => {
  return Math.round((Number(value) || 0) * 100) / 100
}

const selectedTaxOption = computed(() => {
  if (!selectedTaxGroup.value) return null
  return normalizedTaxOptions.value.find(t => t.code === selectedTaxGroup.value) || null
})

const isIncludedTax = computed(() => {
  return !!selectedTaxOption.value?.include
})

const displayTotalAmount = computed(() => {
  const total = Number(props.totalAmount) || 0
  const tax = selectedTaxOption.value
  if (tax && tax.include && tax.rate > 0) {
    const divisor = 1 + (tax.rate / 100)
    if (!divisor) return round2(total)
    return round2(total / divisor)
  }
  return round2(total)
})

// Define calculateTax FIRST before any watchers that use it
const calculateTax = () => {
  console.log('🧮 Calculating tax...', {
    selectedTaxGroup: selectedTaxGroup.value,
    totalAmount: props.totalAmount,
    taxOptions: props.taxOptions
  })

  if (!selectedTaxGroup.value) {
    taxAmount.value = 0
    console.log('❌ No tax group selected, tax = 0')
    return
  }

  if (!props.totalAmount || props.totalAmount === 0) {
    taxAmount.value = 0
    console.warn('⚠️ Total Amount is 0, cannot calculate tax')
    return
  }

  const tax = selectedTaxOption.value
  console.log('📊 Found tax option:', tax)

  if (tax) {
    const total = Number(props.totalAmount) || 0
    if (tax.include) {
      const divisor = 1 + (tax.rate / 100)
      const base = divisor ? (total / divisor) : total
      taxAmount.value = round2(total - base)
    } else {
      taxAmount.value = round2(total * (tax.rate / 100))
    }
    console.log('✅ Tax calculated:', {
      taxCode: tax.code,
      taxRate: tax.rate,
      totalAmount: props.totalAmount,
      taxAmount: taxAmount.value
    })
  } else {
    taxAmount.value = 0
    console.warn('⚠️ Tax rate not found or invalid')
  }
}

watch(() => selectedTaxGroup.value, () => {
  calculateTax()
})

// Watch for tax code changes (with validation)
watch(() => props.taxCode, (newVal, oldVal) => {
  console.log('👁️ Tax Code prop changed:', { old: oldVal, new: newVal })

  if (newVal && newVal.trim() !== '') {
    console.log('🔍 Validating tax code:', newVal)

    // Check if taxOptions is available
    if (!props.taxOptions || props.taxOptions.length === 0) {
      console.warn('⚠️ Tax Options not available yet, will wait...')
      return
    }

    // Find tax in options
    const taxExists = props.taxOptions.find(t => t.code === newVal)
    if (taxExists) {
      selectedTaxGroup.value = newVal
      console.log('✅ Tax auto-selected from prop watcher:', newVal)
      calculateTax()
    } else {
      console.warn('⚠️ Tax code not found in options:', {
        taxCode: newVal,
        availableOptions: props.taxOptions.map(t => t.code)
      })
    }
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    console.log('📋 Final Screen opened with:', {
      doNumber: props.doNumber,
      totalAmount: props.totalAmount,
      taxCode: props.taxCode,
      taxOptions: props.taxOptions,
      formatted: formatCurrency(props.totalAmount)
    })

    // Warn if total amount is 0
    if (!props.totalAmount || props.totalAmount === 0) {
      console.warn('⚠️ Total Amount is 0! Did user input To Bill quantity in Sales Order Items?')
    }

    // Auto-select tax group from taxCode prop
    if (props.taxCode && props.taxCode.trim() !== '') {
      console.log('🎯 Auto-selecting Tax Group:', props.taxCode)
      console.log('📦 Available taxOptions:', props.taxOptions)
      console.log('📊 taxOptions count:', props.taxOptions?.length || 0)
      console.log('📋 taxOptions structure:', JSON.stringify(props.taxOptions, null, 2))

      // Verify tax exists in options (case-insensitive check)
      const taxExists = props.taxOptions.find(t => {
        // Check both 'code' and 'tax_code' fields (API might use different field name)
        const tCode = t.code || t.tax_code || t.Code || t.TAX_CODE
        console.log('  Checking:', tCode, 'against', props.taxCode, '| Full object:', t)
        return tCode && tCode.toUpperCase() === props.taxCode.toUpperCase()
      })

      console.log('🔍 Tax search result:', taxExists)

      if (taxExists) {
        // Use the actual code from taxOptions (in case of case mismatch)
        const actualCode = taxExists.code || taxExists.tax_code || taxExists.Code || taxExists.TAX_CODE
        selectedTaxGroup.value = actualCode
        console.log('✅ Tax Group auto-selected:', actualCode)

        // Calculate tax with delay to ensure state is updated
        setTimeout(() => {
          calculateTax()
        }, 150)
      } else {
        console.warn('⚠️ Tax Code not found in options:', {
          taxCode: props.taxCode,
          taxCodeType: typeof props.taxCode,
          availableOptions: props.taxOptions.map(t => ({
            code: t.code,
            tax_code: t.tax_code,
            name: t.name,
            rate: t.rate
          }))
        })
        selectedTaxGroup.value = ''
        taxAmount.value = 0
      }
    } else {
      console.warn('⚠️ No Tax Code provided')
      selectedTaxGroup.value = ''
      taxAmount.value = 0
    }
  }
})

// Recalculate tax when totalAmount changes
watch(() => props.totalAmount, (newAmount, oldAmount) => {
  if (newAmount !== oldAmount && selectedTaxGroup.value) {
    console.log('💰 Total Amount changed, recalculating tax...', {
      old: oldAmount,
      new: newAmount
    })
    calculateTax()
  }
})

// Watch taxOptions - when loaded, auto-select if taxCode is set
watch(() => props.taxOptions, (newOptions, oldOptions) => {
  console.log('📊 Tax Options changed:', {
    oldCount: oldOptions?.length || 0,
    newCount: newOptions?.length || 0,
    options: newOptions?.map(t => ({ code: t.code, rate: t.rate }))
  })

  if (newOptions && newOptions.length > 0) {
    // If taxCode is set but selectedTaxGroup is not, auto-select
    if (props.taxCode && props.taxCode.trim() !== '' && !selectedTaxGroup.value) {
      console.log('🎯 Tax Options now available, attempting auto-select:', props.taxCode)

      // Case-insensitive search with multiple field support
      const taxExists = newOptions.find(t => {
        const tCode = t.code || t.tax_code || t.Code || t.TAX_CODE
        return tCode && tCode.toUpperCase() === props.taxCode.toUpperCase()
      })

      if (taxExists) {
        const actualCode = taxExists.code || taxExists.tax_code || taxExists.Code || taxExists.TAX_CODE
        selectedTaxGroup.value = actualCode
        console.log('✅ Tax auto-selected from taxOptions watcher:', actualCode)

        // Calculate tax after auto-select
        setTimeout(() => calculateTax(), 100)
      } else {
        console.warn('⚠️ Tax code not found after taxOptions loaded:', {
          taxCode: props.taxCode,
          availableOptions: newOptions.map(t => ({
            code: t.code,
            tax_code: t.tax_code,
            name: t.name
          }))
        })
      }
    }

    // If already selected, recalculate
    if (selectedTaxGroup.value) {
      console.log('🔄 Tax already selected, recalculating...')
      calculateTax()
    }
  }
}, { deep: true, immediate: true })

const netAmount = computed(() => {
  const total = Number(props.totalAmount) || 0
  if (isIncludedTax.value) {
    return round2(total)
  }
  return round2(total + taxAmount.value)
})

const selectedTaxRate = computed(() => {
  if (!selectedTaxGroup.value) return 0
  const tax = normalizedTaxOptions.value.find(t => t.code === selectedTaxGroup.value)
  return tax?.rate || 0
})

const selectedTaxDisplay = computed(() => {
  if (!selectedTaxGroup.value) return ''
  const tax = normalizedTaxOptions.value.find(t => t.code === selectedTaxGroup.value)
  return tax ? `${tax.name} (${tax.rate}%)` : selectedTaxGroup.value
})

// calculateTax moved above watch statements (line 134-168) to fix hoisting error

const formatCurrency = (value) => {
  // Format Indonesia: 3.036.360,00 (titik = thousand separator, koma = decimal)
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value || 0)
}

const formatCurrencyForDisplay = (value) => {
  // Use same Indonesian format for consistency: 303.636,00
  // Titik (.) = thousand separator, Koma (,) = decimal separator
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
    taxPercent: selectedTaxRate.value,
    taxAmount: taxAmount.value,
    totalAmount: props.totalAmount,
    netAmount: netAmount.value
  })

  emit('confirm', {
    taxCode: selectedTaxGroup.value,
    taxPercent: selectedTaxRate.value,
    taxAmount: taxAmount.value,
    totalAmount: displayTotalAmount.value,
    netAmount: netAmount.value
  })
}
</script>
