<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
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
            <DialogPanel class="relative transform overflow-hidden bg-white shadow-2xl sm:my-8 sm:w-full sm:max-w-3xl rounded-lg border-2 border-blue-200">
              <!-- Modern Header -->
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 flex items-center justify-between">
                <DialogTitle class="text-sm font-semibold text-white flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                  </svg>
                  Delivery Order Screen
                </DialogTitle>
                <button @click="handleClose" class="text-white hover:bg-blue-800 p-1 rounded">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>

              <!-- Toolbar - CPS Style -->
              <div class="px-3 py-2 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-300 flex items-center gap-1.5 shadow-sm">
                <button @click="handleClose" class="p-1.5 hover:bg-red-50 rounded border border-gray-300 bg-white transition-all shadow-sm" title="Power Off">
                  <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button @click="openBrowseModal" class="p-1.5 hover:bg-blue-50 rounded border border-gray-300 bg-white transition-all shadow-sm" title="Browse">
                  <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button @click="refreshData" class="p-1.5 hover:bg-green-50 rounded border border-gray-300 bg-white transition-all shadow-sm" title="Refresh">
                  <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <button @click="handleClose" class="p-1.5 hover:bg-red-50 rounded border border-gray-300 bg-white transition-all shadow-sm" title="Close">
                  <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>

              <!-- Content -->
              <div class="p-4">
                <!-- Table - CPS Style -->
                <div class="border-2 border-gray-300 rounded overflow-hidden shadow-sm">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-50">
                      <tr class="border-b-2 border-gray-300">
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20 border-r border-gray-300">No</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300">
                          <div class="flex items-center gap-2">
                            D/Order#
                            <button @click="openBrowseModal" class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded p-0.5 transition-all" title="Browse">
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                              </svg>
                            </button>
                          </div>
                        </th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300">D/O Date</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-24">Select</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="index in 5" :key="index" 
                        :class="[
                          'transition-colors duration-150',
                          selectedOrders[index-1] && isSelected(selectedOrders[index-1].do_number) 
                            ? 'bg-blue-100 hover:bg-blue-200' 
                            : selectedOrders[index-1] 
                              ? 'hover:bg-gray-50' 
                              : 'bg-white'
                        ]"
                      >
                        <td :class="[
                          'px-4 py-2.5 text-sm font-medium border-r border-gray-200',
                          selectedOrders[index-1] && isSelected(selectedOrders[index-1].do_number) ? 'text-blue-900' : 'text-gray-700'
                        ]">
                          {{ String(index).padStart(2, '0') }}
                        </td>
                        <td :class="[
                          'px-2 py-2 text-sm border-r border-gray-200',
                          selectedOrders[index-1] && isSelected(selectedOrders[index-1].do_number) ? 'text-blue-900' : 'text-gray-900'
                        ]">
                          <!-- First Row: Search Input + Browse Button -->
                          <div v-if="index === 1" class="flex items-center gap-1">
                            <input
                              v-model="searchQuery"
                              type="text"
                              placeholder="Search D/O..."
                              @keyup.enter="searchDeliveryOrder"
                              class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <button 
                              @click="openBrowseModal" 
                              class="p-1.5 text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded transition-all" 
                              title="Browse"
                            >
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                              </svg>
                            </button>
                          </div>
                          <!-- Other Rows: Display DO Number -->
                          <div v-else class="flex items-center gap-2 px-2">
                            <span 
                              v-if="selectedOrders[index-1]" 
                              class="font-semibold cursor-pointer text-blue-600 hover:text-blue-800 hover:underline transition-all" 
                              @click="viewOrderItems(selectedOrders[index-1])"
                            >
                              {{ selectedOrders[index-1].do_number }}
                            </span>
                            <span v-else class="text-gray-400">-</span>
                          </div>
                        </td>
                        <td :class="[
                          'px-4 py-2.5 text-sm border-r border-gray-200',
                          selectedOrders[index-1] && isSelected(selectedOrders[index-1].do_number) ? 'text-blue-900' : 'text-gray-600'
                        ]">
                          <span v-if="selectedOrders[index-1]">
                            {{ formatDate(selectedOrders[index-1].do_date) }}
                          </span>
                          <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                          <input 
                            v-if="selectedOrders[index-1]"
                            type="checkbox" 
                            :checked="isSelected(selectedOrders[index-1].do_number)"
                            @change="toggleSelection(selectedOrders[index-1])"
                            class="w-4 h-4 text-blue-600 rounded border-2 border-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 cursor-pointer"
                          />
                          <input v-else type="checkbox" disabled class="w-4 h-4 rounded border-2 border-gray-300 opacity-20 cursor-not-allowed" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Summary - CPS Style -->
                <div class="mt-3 px-1 text-xs text-gray-700 font-medium">
                  <span>{{ selectedOrders.length > 0 ? '1' : '0' }}-{{ Math.min(5, selectedOrders.length) }} / {{ selectedOrders.length }} records</span>
                </div>
              </div>

              <!-- Footer - CPS Style -->
              <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-t-2 border-gray-300 flex justify-center gap-3 shadow-inner">
                <button 
                  @click="handleClose"
                  class="px-8 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-400 rounded hover:bg-gray-50 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all shadow-sm"
                >
                  Cancel
                </button>
                <button 
                  @click="handleOK"
                  :disabled="selectedCount === 0"
                  class="px-8 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 border-2 border-blue-600 rounded hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-400 transition-all shadow-md"
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
  customerCode: { type: String, default: '' },
  customerName: { type: String, default: '' },
  taxIndexNo: { type: String, default: '' },
  invoiceDate: { type: String, default: '' },
  selectedDeliveryOrders: { type: Array, default: () => [] }
})

const emit = defineEmits(['close', 'select', 'browse', 'viewItems'])

// State
const selectedDOs = ref([])
const searchQuery = ref('')

// Computed
const selectedOrders = computed(() => {
  return props.selectedDeliveryOrders || []
})

const selectedCount = computed(() => selectedDOs.value.length)

// Watch for prop changes
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    // Initialize selectedDOs with all orders from props
    selectedDOs.value = [...(props.selectedDeliveryOrders || [])]
  } else {
    selectedDOs.value = []
  }
})

// Watch for changes in selectedDeliveryOrders
watch(() => props.selectedDeliveryOrders, (newOrders) => {
  if (props.open && newOrders) {
    selectedDOs.value = [...newOrders]
  }
})

// Methods
const isSelected = (doNumber) => {
  return selectedDOs.value.some(d => d.do_number === doNumber)
}

const toggleSelection = (item) => {
  const index = selectedDOs.value.findIndex(d => d.do_number === item.do_number)
  if (index > -1) {
    selectedDOs.value.splice(index, 1)
  } else {
    selectedDOs.value.push(item)
  }
}

const searchDeliveryOrder = () => {
  const query = searchQuery.value.trim()
  if (!query) {
    console.log('⚠️ Search query is empty')
    return
  }
  
  console.log('🔍 Searching for Delivery Order:', query)
  
  // Find matching DO in selectedOrders
  const foundOrder = props.selectedDeliveryOrders.find(order => 
    order.do_number.toLowerCase().includes(query.toLowerCase())
  )
  
  if (foundOrder) {
    console.log('✅ Found Delivery Order:', foundOrder.do_number)
    // Auto-select the found order
    if (!isSelected(foundOrder.do_number)) {
      selectedDOs.value.push(foundOrder)
    }
  } else {
    console.log('❌ Delivery Order not found:', query)
  }
}

const openBrowseModal = () => {
  console.log('🔍 Opening Browse Modal for Delivery Orders')
  emit('browse')
}

const refreshData = () => {
  console.log('🔄 Refreshing Delivery Order Screen data')
  searchQuery.value = ''
  // Re-initialize with current orders
  if (props.open && props.selectedDeliveryOrders) {
    selectedDOs.value = [...props.selectedDeliveryOrders]
  }
}

const viewOrderItems = (order) => {
  // Emit event to show Sales Order Items screen
  emit('viewItems', order)
}

const handleClose = () => {
  emit('close')
}

const handleOK = () => {
  if (selectedDOs.value.length > 0) {
    emit('select', selectedDOs.value)
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    })
  } catch (e) {
    return dateString
  }
}
</script>

<style scoped>
input[type="checkbox"] {
  accent-color: #2563eb;
}
</style>
