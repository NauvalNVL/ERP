<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="close">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-600">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-white">
                    <i class="fas fa-list"></i>
                    <DialogTitle class="text-sm font-semibold">Customer's Sales Tax Index Table</DialogTitle>
                  </div>
                  <button class="text-white/90 hover:text-white" @click="close">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <div class="p-5">
                <!-- Info Bar -->
                <div class="mb-3 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800" v-if="customerCode">
                  <i class="fas fa-info-circle mr-2"></i>
                  <span>Customer: <strong>{{ customerCode }}</strong></span>
                </div>

                <div class="mb-3 flex items-center gap-3">
                  <div class="relative">
                    <i class="fas fa-search text-gray-400 absolute left-2 top-2.5"></i>
                    <input v-model.trim="query" type="text" placeholder="Search index, tax group or name" class="w-80 pl-8 pr-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                  </div>
                  <span class="hidden sm:inline text-xs text-gray-500">Use Up/Down to navigate, Enter to select</span>
                  <span class="ml-auto text-xs text-gray-400" v-if="!loading">{{ filtered.length }} items</span>
                </div>

                <div class="overflow-hidden rounded-lg ring-1 ring-gray-200" ref="tableWrap" tabindex="0"
                     @keydown.down.prevent="move(1)" @keydown.up.prevent="move(-1)" @keydown.enter.prevent="confirm" @keydown.esc.prevent="close">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Index#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tax Group</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tax Group Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">S/Tax Exemption Reference#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tarif Code</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                      <tr v-if="loading">
                        <td colspan="6" class="px-3 py-8 text-center">
                          <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                          <div class="mt-2 text-sm text-gray-500">Loading tax indices...</div>
                        </td>
                      </tr>
                      <tr v-else-if="error">
                        <td colspan="6" class="px-3 py-8 text-center text-red-600 text-sm">
                          <i class="fas fa-exclamation-triangle mr-2"></i>
                          {{ error }}
                        </td>
                      </tr>
                      <tr v-else-if="filtered.length === 0">
                        <td colspan="6" class="px-3 py-8 text-center text-gray-500 text-sm">
                          <i class="fas fa-inbox mr-2"></i>
                          No tax indices found for this customer.
                        </td>
                      </tr>
                      <template v-else>
                        <tr v-for="(item, i) in filtered" :key="i" :class="idx === i ? 'bg-blue-100 ring-1 ring-blue-400' : 'hover:bg-gray-50'" class="cursor-pointer" @click="select(i)" @dblclick="confirm">
                          <td class="px-3 py-2 text-sm font-medium">{{ String(item.index_number).padStart(2, '0') }}</td>
                          <td class="px-3 py-2 text-sm font-mono">{{ item.tax_group_code }}</td>
                          <td class="px-3 py-2 text-sm">{{ item.tax_group?.name || '-' }}</td>
                          <td class="px-3 py-2 text-sm text-gray-500">-</td>
                          <td class="px-3 py-2 text-sm text-gray-500">-</td>
                          <td class="px-3 py-2 text-sm">
                            <span :class="item.status === 'A' ? 'text-green-600 font-semibold' : 'text-red-600'">
                              {{ item.status === 'A' ? 'Active' : 'Obsolete' }}
                            </span>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>

                <div class="mt-3 text-xs text-gray-500 italic">
                  <p>Click row to select, double-click to select and close. Press ESC to cancel.</p>
                </div>
              </div>

              <div class="px-5 py-3 bg-gray-50 border-t flex justify-end gap-2">
                <button @click="close" class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors">
                  Exit
                </button>
                <button @click="confirm" :disabled="idx === null || filtered.length === 0" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed text-white rounded-md transition-colors">
                  Select
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
import { ref, computed, watch, nextTick } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import axios from 'axios'

const props = defineProps({
  open: Boolean,
  customerCode: String
})

const emit = defineEmits(['close', 'select'])

const data = ref([])
const loading = ref(false)
const error = ref(null)
const query = ref('')
const idx = ref(null)
const tableWrap = ref(null)

const filtered = computed(() => {
  if (!query.value) return data.value
  const q = query.value.toLowerCase()
  return data.value.filter(item =>
    String(item.index_number).includes(q) ||
    item.tax_group_code?.toLowerCase().includes(q) ||
    item.tax_group?.name?.toLowerCase().includes(q)
  )
})

watch(() => props.open, async (val) => {
  if (val) {
    query.value = ''
    idx.value = null
    await loadData()
    await nextTick()
    tableWrap.value?.focus()
  }
})

const loadData = async () => {
  if (!props.customerCode) {
    error.value = 'Customer code is required'
    return
  }

  loading.value = true
  error.value = null
  try {
    const response = await axios.get(`/api/invoices/customer-tax-indices/${props.customerCode}`)
    if (response.data.success) {
      data.value = response.data.data || []
      // Auto-select first active item
      if (data.value.length > 0) {
        const firstActive = data.value.findIndex(item => item.status === 'A')
        idx.value = firstActive >= 0 ? firstActive : 0
      }
    } else {
      throw new Error(response.data.message || 'Failed to load data')
    }
  } catch (err) {
    console.error('Error loading customer tax indices:', err)
    error.value = err.response?.data?.message || err.message || 'Failed to load customer tax indices'
    data.value = []
  } finally {
    loading.value = false
  }
}

function select(i) {
  idx.value = i
}

function move(dir) {
  if (filtered.value.length === 0) return
  if (idx.value === null) idx.value = 0
  else {
    idx.value = (idx.value + dir + filtered.value.length) % filtered.value.length
  }
}

function confirm() {
  if (idx.value !== null && filtered.value[idx.value]) {
    const selected = filtered.value[idx.value]
    emit('select', {
      index_number: selected.index_number,
      tax_group_code: selected.tax_group_code,
      tax_group_name: selected.tax_group?.name || '',
      status: selected.status
    })
    close()
  }
}

function close() {
  emit('close')
}
</script>
