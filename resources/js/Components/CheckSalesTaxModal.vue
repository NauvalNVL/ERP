<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <!-- Header -->
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                  <div>
                    <DialogTitle class="text-base font-semibold text-gray-800">Check Sales Tax Screen</DialogTitle>
                    <p class="text-xs text-gray-500 mt-0.5">Verify tax configuration before invoice preparation</p>
                  </div>
                  <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="handleClose">
                    <XIcon class="w-5 h-5"/>
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="p-5">
                <!-- Info Banner -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                  <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-xs text-blue-800">
                      <p class="font-medium mb-1">Tax Verification Required</p>
                      <p>Please verify the sales tax configuration below. The selected tax will be applied to all invoices prepared in this session.</p>
                    </div>
                  </div>
                </div>

                <!-- Tax Table -->
                <div class="border rounded-lg overflow-hidden">
                  <div v-if="loading" class="p-8 text-center text-gray-500">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                    <p class="text-sm">Loading tax options...</p>
                  </div>

                  <table v-else class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                          No Tax Code
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                          Name
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                          Apply
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                          Tax%
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                          Include
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr 
                        v-for="tax in taxOptions" 
                        :key="tax.code"
                        :class="[
                          'cursor-pointer transition-colors',
                          selectedTax?.code === tax.code ? 'bg-blue-100 hover:bg-blue-200' : 'hover:bg-gray-50'
                        ]"
                        @click="selectTax(tax)"
                      >
                        <td class="px-4 py-3 whitespace-nowrap">
                          <div class="flex items-center gap-2">
                            <input 
                              type="radio" 
                              :checked="selectedTax?.code === tax.code"
                              class="text-blue-600 focus:ring-blue-500"
                              @click.stop="selectTax(tax)"
                            />
                            <span class="text-sm font-medium text-gray-900">{{ tax.code }}</span>
                          </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                          <span class="text-sm text-gray-700">{{ tax.name }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                          <span 
                            :class="[
                              'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                              tax.apply ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                            ]"
                          >
                            {{ tax.apply ? 'Yes' : 'No' }}
                          </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                          <span class="text-sm font-semibold text-gray-900">{{ formatPercent(tax.rate) }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                          <span 
                            :class="[
                              'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                              tax.include ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                            ]"
                          >
                            {{ tax.include ? 'Yes' : 'No' }}
                          </span>
                        </td>
                      </tr>
                      <tr v-if="!loading && taxOptions.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                          No active tax codes found
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Selected Tax Summary -->
                <div v-if="selectedTax" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                  <div class="flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-green-800">Selected Tax:</span>
                    <span class="text-green-900">{{ selectedTax.code }} - {{ selectedTax.name }} ({{ formatPercent(selectedTax.rate) }})</span>
                  </div>
                </div>
              </div>

              <!-- Footer Actions -->
              <div class="px-5 py-4 border-t bg-gray-50 flex items-center justify-between">
                <button 
                  class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="handleZoom"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                  </svg>
                  Zoom
                </button>
                
                <div class="flex items-center gap-2">
                  <button 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                    @click="handleClose"
                  >
                    <XIcon class="w-4 h-4"/>
                    Exit
                  </button>
                  <button 
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                    :disabled="!selectedTax"
                    @click="handleOk"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    OK
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XIcon } from '@heroicons/vue/outline'

const props = defineProps({
  open: { type: Boolean, default: false },
  customerCode: { type: String, default: '' },
  preselectedTaxCode: { type: String, default: '' },
})

const emit = defineEmits(['close', 'confirm'])

const loading = ref(false)
const taxOptions = ref([])
const selectedTax = ref(null)

// Fetch tax options when modal opens
watch(() => props.open, async (isOpen) => {
  if (isOpen) {
    await fetchTaxOptions()
    // Auto-select preselected tax if provided
    if (props.preselectedTaxCode && taxOptions.value.length > 0) {
      const preselected = taxOptions.value.find(t => t.code === props.preselectedTaxCode)
      if (preselected) {
        selectedTax.value = preselected
      }
    }
    // Auto-select first active tax if none selected
    if (!selectedTax.value && taxOptions.value.length > 0) {
      const firstActive = taxOptions.value.find(t => t.apply)
      if (firstActive) {
        selectedTax.value = firstActive
      }
    }
  } else {
    // Reset on close
    selectedTax.value = null
  }
})

const fetchTaxOptions = async () => {
  loading.value = true
  try {
    // Use same API endpoint as SalesTaxIndexModal for data consistency
    const res = await fetch('/api/material-management/tax-types', {
      headers: { 'Accept': 'application/json' }
    })
    if (res.ok) {
      const data = await res.json()
      // Transform data to match expected format (same mapping as SalesTaxIndexModal)
      let allTaxes = (Array.isArray(data) ? data : (data.data || [])).map(tax => {
        const code = tax.code || tax.tax_code || tax.TaxCode || tax.Code || tax.taxtype || tax.type || tax.id || ''
        const name = tax.name || tax.description || tax.tax_name || tax.TaxName || tax.Name || ''
        const rate = parseFloat(tax.rate || tax.tax_rate || tax.RATEPPN || 0)
        const status = (tax.status ?? tax.is_active ?? tax.active ?? true) ? true : false
        
        return {
          code: code,
          name: name,
          rate: rate,
          apply: !!status,
          include: tax.include === true || tax.include === 'Yes' || tax.tax_included === true,
        }
      })
      
      // CPS Behavior: If preselectedTaxCode exists, only show that specific tax
      // This matches CPS where Check Sales Tax Screen only displays the tax selected in Tax Exemption Table
      if (props.preselectedTaxCode) {
        console.log('Searching for tax code:', props.preselectedTaxCode)
        console.log('Available taxes:', allTaxes.map(t => t.code))
        
        const selectedTaxOnly = allTaxes.find(t => 
          t.code && props.preselectedTaxCode && 
          t.code.toLowerCase() === props.preselectedTaxCode.toLowerCase()
        )
        
        if (selectedTaxOnly) {
          taxOptions.value = [selectedTaxOnly]
          console.log('✅ CPS Mode: Showing only preselected tax:', selectedTaxOnly)
        } else {
          console.warn('⚠️ Preselected tax not found, showing all taxes')
          taxOptions.value = allTaxes
        }
      } else {
        // If no preselection, show all taxes
        taxOptions.value = allTaxes
        console.log('No preselection, showing all taxes:', allTaxes.length)
      }
    } else {
      taxOptions.value = []
    }
  } catch (e) {
    console.error('Failed to fetch tax options:', e)
    taxOptions.value = []
  } finally {
    loading.value = false
  }
}

const selectTax = (tax) => {
  selectedTax.value = tax
}

const formatPercent = (rate) => {
  return `${parseFloat(rate).toFixed(2)}%`
}

const handleOk = () => {
  if (selectedTax.value) {
    // Emit both selected tax AND all tax options to parent
    emit('confirm', {
      selectedTax: selectedTax.value,
      allTaxOptions: taxOptions.value
    })
  }
}

const handleClose = () => {
  emit('close')
}

const handleZoom = () => {
  // Optional: Implement zoom/detail view
  console.log('Zoom clicked for tax:', selectedTax.value)
}
</script>

<style scoped>
/* Custom scrollbar for table */
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
