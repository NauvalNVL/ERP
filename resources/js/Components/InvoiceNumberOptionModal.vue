<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[80]" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-sm transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <!-- Header -->
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-600">
                <DialogTitle class="text-base font-semibold text-white">Option</DialogTitle>
              </div>

              <!-- Content -->
              <div class="p-6 space-y-4">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <input 
                    type="radio" 
                    v-model="selectedOption"
                    value="auto"
                    class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                    Auto Generated Number
                  </span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                  <input 
                    type="radio" 
                    v-model="selectedOption"
                    value="manual"
                    class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                    Manual Selection Number
                  </span>
                </label>

                <!-- Manual Input (shown when manual is selected) -->
                <div v-if="selectedOption === 'manual'" class="pl-7 pt-2">
                  <input 
                    type="text" 
                    v-model="manualNumber"
                    placeholder="Enter invoice number"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              <!-- Footer Actions -->
              <div class="px-5 py-4 border-t bg-gray-50 flex items-center justify-end gap-2">
                <button 
                  class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="$emit('close')"
                >
                  Exit
                </button>
                <button 
                  class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                  :disabled="selectedOption === 'manual' && !manualNumber"
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
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  open: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'confirm'])

const selectedOption = ref('auto')
const manualNumber = ref('')

// Reset when modal opens
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    selectedOption.value = 'auto'
    manualNumber.value = ''
  }
})

const handleOK = () => {
  emit('confirm', {
    mode: selectedOption.value,
    invoiceNumber: selectedOption.value === 'manual' ? manualNumber.value : null
  })
}
</script>
