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

              <!-- Toolbar - CPS ERP Style -->
              <div class="px-3 py-2 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-300 shadow-sm">
                <!-- Search Bar -->
                <div class="flex items-center gap-2 bg-white rounded border border-gray-300 px-2 py-1 shadow-sm">
                  <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                  </svg>
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search D/Order#..."
                    @keyup.enter="searchDeliveryOrder"
                    class="flex-1 text-sm border-0 focus:outline-none focus:ring-0 p-0"
                  />
                </div>
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
                        <td class="px-2 py-2 border-r border-gray-200">
                          <!-- Input + Browse Button for each row -->
                          <div class="flex items-center gap-1">
                            <div class="flex-1 flex flex-col gap-0.5">
                              <input
                                :value="selectedOrders[index-1]?.do_number || ''"
                                type="text"
                                :placeholder="'D/Order# ' + String(index).padStart(2, '0')"
                                @keyup.enter="searchDeliveryOrder"
                                class="px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                :class="selectedOrders[index-1] ? 'text-blue-600 font-semibold' : 'text-gray-900'"
                              />
                              <!-- Billed indicator (CPS Flow) -->
                              <span 
                                v-if="selectedOrders[index-1] && isBilled(selectedOrders[index-1].do_number)"
                                class="text-xs text-green-600 font-semibold px-1"
                              >
                                ✓ Billed
                              </span>
                            </div>
                            <button 
                              @click="openBrowseModal" 
                              class="p-1.5 text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded transition-all flex-shrink-0" 
                              title="Browse"
                            >
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                              </svg>
                            </button>
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
                <!-- Proceed to Final Screen Button (CPS Flow) -->
                <button 
                  v-if="hasBilledItems"
                  @click="handleProceed"
                  class="px-8 py-2 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 border-2 border-green-600 rounded hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all shadow-md"
                  title="Proceed to Final Screen"
                >
                  Proceed →
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
  selectedDeliveryOrders: { type: Array, default: () => [] },
  billedItems: { type: Map, default: () => new Map() } // Track billed items per DO
})

const emit = defineEmits(['close', 'select', 'browse', 'viewItems', 'delete', 'save', 'proceed'])

// State
const selectedDOs = ref([])
const searchQuery = ref('')

// Computed
const selectedOrders = computed(() => {
  return props.selectedDeliveryOrders || []
})

const selectedCount = computed(() => selectedDOs.value.length)

// Check if user has billed any items (CPS Flow)
const hasBilledItems = computed(() => {
  return props.billedItems && props.billedItems.size > 0
})

// Watch for prop changes
watch(() => props.open, (isOpen, wasOpen) => {
  console.log('🎭 [Screen Modal] props.open changed:', wasOpen, '→', isOpen)
  if (isOpen) {
    console.log('✅ [Screen Modal] Opening - initializing with', props.selectedDeliveryOrders?.length || 0, 'orders')
    // Initialize selectedDOs with all orders from props
    selectedDOs.value = [...(props.selectedDeliveryOrders || [])]
  } else {
    console.log('❌ [Screen Modal] Closing - clearing selection')
    selectedDOs.value = []
  }
})

// Watch for changes in selectedDeliveryOrders
watch(() => props.selectedDeliveryOrders, (newOrders, oldOrders) => {
  console.log('📦 [Screen Modal] selectedDeliveryOrders updated:', oldOrders?.length || 0, '→', newOrders?.length || 0)
  console.log('📊 [Screen Modal] Current modal state - props.open:', props.open)
  if (props.open && newOrders) {
    console.log('🔄 [Screen Modal] Updating internal selectedDOs with new orders')
    selectedDOs.value = [...newOrders]
  } else {
    console.log('⏭️ [Screen Modal] Skipping update - modal closed or no orders')
  }
})

// Methods
const isSelected = (doNumber) => {
  return selectedDOs.value.some(d => d.do_number === doNumber)
}

const isBilled = (doNumber) => {
  return props.billedItems && props.billedItems.has(doNumber)
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
  console.log('🚪 [Screen Modal] handleClose called - emitting close event')
  console.trace('Close triggered from:')
  emit('close')
}

const handleOK = () => {
  if (selectedDOs.value.length > 0) {
    emit('select', selectedDOs.value)
  }
}

const handleDelete = () => {
  if (selectedDOs.value.length === 0) return
  
  if (confirm(`Delete ${selectedDOs.value.length} selected delivery order(s)?`)) {
    console.log('🗑️ Deleting selected orders:', selectedDOs.value.map(d => d.do_number))
    // Clear selection
    selectedDOs.value = []
    // Emit delete event
    emit('delete', selectedDOs.value)
  }
}

const handleSave = () => {
  if (selectedDOs.value.length === 0) return
  
  console.log('💾 Saving selected orders:', selectedDOs.value.map(d => d.do_number))
  // Emit save event
  emit('save', selectedDOs.value)
}

const handleReturn = () => {
  if (selectedDOs.value.length === 0) return
  
  console.log('↩️ Returning with selected orders:', selectedDOs.value.map(d => d.do_number))
  // Emit select event (same as OK)
  emit('select', selectedDOs.value)
}

const handleProceed = () => {
  console.log('🎯 Proceeding to Final Screen with billed items:', props.billedItems.size)
  // Emit proceed event to go to Final Screen
  emit('proceed')
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
