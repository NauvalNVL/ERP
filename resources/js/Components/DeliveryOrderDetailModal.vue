<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[60]" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <!-- Header -->
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                  <div>
                    <DialogTitle class="text-base font-semibold text-gray-800">Delivery Order Table</DialogTitle>
                    <p class="text-xs text-gray-500 mt-0.5">Detailed delivery order information</p>
                  </div>
                  <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="$emit('close')">
                    <XIcon class="w-5 h-5"/>
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="p-5">
                <!-- Customer Info -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                      <span class="font-medium text-gray-700">Customer:</span>
                      <span class="ml-2 text-gray-900">{{ customerCode }} - {{ customerName }}</span>
                    </div>
                  </div>
                </div>

                <!-- DO Table -->
                <div class="border rounded-lg overflow-hidden">
                  <div class="overflow-x-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50 sticky top-0">
                        <tr>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">D/Order#</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">D/O Date</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Vehicle#</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Item#</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">P/C</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Mode</th>
                          <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr 
                          v-for="item in deliveryOrders" 
                          :key="item.do_number"
                          :class="[
                            'cursor-pointer transition-colors',
                            currentSelectedDO?.do_number === item.do_number ? 'bg-blue-100' : 'hover:bg-gray-50'
                          ]"
                          @click="selectRow(item)"
                        >
                          <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-blue-600">
                            {{ item.do_number }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                            {{ item.do_date }}
                          </td>
                          <td class="px-3 py-2 text-sm text-gray-700">
                            {{ item.customer_code }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                            {{ item.vehicle || '-' }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ item.item || '1' }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ item.item || '1' }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                            {{ item.mode || 'Multiple' }}
                          </td>
                          <td class="px-3 py-2 whitespace-nowrap">
                            <span 
                              :class="[
                                'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                item.status === 'Invoiced' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                              ]"
                            >
                              {{ item.status || 'Active' }}
                            </span>
                          </td>
                        </tr>
                        <tr v-if="deliveryOrders.length === 0">
                          <td colspan="8" class="px-3 py-8 text-center text-sm text-gray-500">
                            No delivery orders found
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Additional Info (CPS-style) -->
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                  <div class="space-y-2">
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">D/Order#:</span>
                      <span class="text-gray-900">{{ currentSelectedDO?.do_number || '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Cust. Name:</span>
                      <span class="text-gray-900">{{ customerName }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Salesperson:</span>
                      <span class="text-gray-900">{{ currentSelectedDO?.salesperson || 'S111' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Order Mode:</span>
                      <div class="flex items-center gap-2">
                        <label class="inline-flex items-center">
                          <input type="radio" checked class="text-blue-600" disabled />
                          <span class="ml-1 text-xs">D-Order by Customer</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" class="text-blue-600" disabled />
                          <span class="ml-1 text-xs">Deliver & Invoice to Customer</span>
                        </label>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Agent Cost.:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded w-48" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Sales Type:</span>
                      <input type="text" value="Sales" class="px-2 py-1 text-xs border rounded w-32" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">D/O Inst1:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded flex-1" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">D/O Inst2:</span>
                      <input type="text" value="SAMAUN" class="px-2 py-1 text-xs border rounded flex-1" disabled />
                    </div>
                  </div>

                  <div class="space-y-2">
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">C/Ticket#:</span>
                      <input type="text" value="00 0000 00000" class="px-2 py-1 text-xs border rounded w-48" disabled />
                      <label class="inline-flex items-center text-xs">
                        <input type="checkbox" class="rounded text-blue-600" disabled />
                        <span class="ml-1">On Hold</span>
                      </label>
                      <span class="text-xs text-gray-600">No</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Prepared by:</span>
                      <input type="text" value="whs10" class="px-2 py-1 text-xs border rounded w-24" disabled />
                      <span class="text-xs text-gray-700">Date:</span>
                      <input type="text" value="06/07/2025" class="px-2 py-1 text-xs border rounded w-32" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Amended by:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded w-24" disabled />
                      <span class="text-xs text-gray-700">Date:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded w-32" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Cancelled by:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded w-24" disabled />
                      <span class="text-xs text-gray-700">Date:</span>
                      <input type="text" class="px-2 py-1 text-xs border rounded w-32" disabled />
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="font-medium text-gray-700 w-32">Printed by:</span>
                      <input type="text" value="whs10" class="px-2 py-1 text-xs border rounded w-24" disabled />
                      <span class="text-xs text-gray-700">Date:</span>
                      <input type="text" value="06/07/2025" class="px-2 py-1 text-xs border rounded w-32" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer Actions -->
              <div class="px-5 py-4 border-t bg-gray-50 flex items-center justify-between">
                <button 
                  class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                  title="Zoom"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                  </svg>
                  Zoom
                </button>

                <div class="flex items-center gap-2">
                  <button 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                    @click.stop.prevent="handleSelect"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Select
                  </button>
                  <button 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                    @click="$emit('close')"
                  >
                    <XIcon class="w-4 h-4"/>
                    Exit
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
import { ref, watch, nextTick } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon as XIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  open: { type: Boolean, default: false },
  deliveryOrders: { type: Array, default: () => [] },
  customerCode: { type: String, default: '' },
  customerName: { type: String, default: '' },
  selectedDO: { type: Object, default: null },
})

const emit = defineEmits(['close', 'select'])

const currentSelectedDO = ref(null)

// Initialize with passed selectedDO or first DO
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    if (props.selectedDO) {
      currentSelectedDO.value = props.selectedDO
    } else if (props.deliveryOrders.length > 0) {
      currentSelectedDO.value = props.deliveryOrders[0]
    }
  }
}, { immediate: true })

const selectRow = (item) => {
  currentSelectedDO.value = item
}

const handleSelect = async () => {
  console.log('DeliveryOrderDetailModal - handleSelect called')
  console.log('currentSelectedDO:', currentSelectedDO.value)
  
  if (currentSelectedDO.value) {
    console.log('Emitting select event with DO:', currentSelectedDO.value.do_number)
    
    // Use nextTick to ensure DOM is updated before emitting
    await nextTick()
    
    // Emit select event with a slight delay to prevent race conditions
    setTimeout(() => {
      emit('select', currentSelectedDO.value)
      console.log('Select event emitted successfully')
    }, 10)
  } else {
    console.log('No DO selected, emitting close')
    emit('close')
  }
}
</script>

<style scoped>
/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
