<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-5xl bg-white rounded-lg shadow-xl flex flex-col max-h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
          <h3 class="text-lg font-semibold text-white">Packing Details</h3>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
          <!-- Packing Details Table -->
          <div class="border border-gray-300 rounded-lg overflow-hidden overflow-x-auto">
            <table class="min-w-[640px] divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Item</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">P/Design</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Pcs/Bdls</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">To Del</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    Bdls x Qty + Loose Qty
                    <div class="text-[10px] font-normal leading-tight">Remark</div>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in packingItems" :key="index" class="hover:bg-gray-50">
                  <td class="px-3 py-2 text-sm text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pDesign"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 bg-gray-50"
                      placeholder=""
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.pcsRolls"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-center bg-gray-50"
                      placeholder=""
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2 border-r border-gray-200">
                    <input 
                      v-model="item.toDel"
                      type="text"
                      class="w-full px-2 py-1 text-xs border-0 focus:ring-0 text-right bg-gray-50"
                      placeholder=""
                      readonly
                    >
                  </td>
                  <td class="px-3 py-2">
                    <div class="flex items-center space-x-1">
                      <input 
                        v-model="item.rolls"
                        type="text"
                        class="w-12 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        placeholder=""
                      >
                      <span class="text-xs text-gray-600">x</span>
                      <input 
                        v-model="item.qty"
                        type="text"
                        class="w-8 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        placeholder=""
                      >
                      <span class="text-xs text-gray-600">x</span>
                      <input 
                        v-model="item.looseQty"
                        type="text"
                        class="w-12 px-1 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-center"
                        placeholder=""
                      >
                      <input 
                        v-model="item.remark"
                        type="text"
                        class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                        placeholder="Remark"
                      >
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end space-x-4 p-4 border-t border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
          <button 
            @click="closeModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button 
            @click="handleSave"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Create DO
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'

const { success, error, info } = useToast()

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  salesOrderDetailData: {
    type: Object,
    default: () => ({})
  }
})

// Emits
const emit = defineEmits(['close', 'save', 'save-delivery-order'])

// Modal state

// Reactive data

const packingItems = ref([
  { 
    name: 'Main', 
    pDesign: '', 
    pcsRolls: '', 
    toDel: '', 
    rolls: '', 
    qty: '', 
    looseQty: '', 
    remark: '' 
  },
  { name: 'Fit1', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit2', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit3', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit4', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit5', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit6', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit7', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit8', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' },
  { name: 'Fit9', pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' }
])


// Functions
const closeModal = () => {
  emit('close')
}

const handlePowerOff = () => {
  info('Power off functionality will be implemented')
}

const handlePrint = () => {
  info('Print functionality will be implemented')
}

const handleSave = () => {
  // Emit create DO directly with packing details
  const payload = {
    packingDetails: { packingItems: packingItems.value }
  }
  emit('save-delivery-order', payload)
  success('Creating Delivery Order')
}

async function populatePackingFromProps() {
  // Reset rows to blank first
  packingItems.value = packingItems.value.map(pi => ({ ...pi, pDesign: '', pcsRolls: '', toDel: '', rolls: '', qty: '', looseQty: '', remark: '' }))

  if (props.salesOrderDetailData && props.salesOrderDetailData.itemRows) {
    props.salesOrderDetailData.itemRows.forEach((item, index) => {
      if (index < packingItems.value.length) {
        const packingItem = packingItems.value[index]
        packingItem.pDesign = item.pDesign || ''
        // Set "To Del" from "To Deliver" data
        const toDeliverValue = parseFloat(String(item.toDeliver || '').toString().replace(/,/g, '')) || 0
        packingItem.toDel = toDeliverValue > 0 ? (item.toDeliver || '') : ''
      }
    })
  }

  // Use To Delivery Set if individual To Deliver values are not present
  if (props.salesOrderDetailData && props.salesOrderDetailData.orderDetail) {
    const orderDetail = props.salesOrderDetailData.orderDetail
    const toDeliverySetValue = parseFloat(String(orderDetail.toDeliverySet || '').toString().replace(/,/g, '')) || 0
    const hasIndividualToDeliver = props.salesOrderDetailData.itemRows?.some(item => parseFloat(item.toDeliver) > 0)
    if (toDeliverySetValue > 0 && !hasIndividualToDeliver) {
      const mainItem = packingItems.value.find(item => item.name === 'Main')
      if (mainItem) mainItem.toDel = orderDetail.toDeliverySet
    }
    // Prefer pcsPerBdl passed from detail modal when available
    const pcsPerBdlPassed = Number(orderDetail.pcsPerBdl || 0)
    if (pcsPerBdlPassed > 0) {
      // Set only for Main, clear others
      const mainItem = packingItems.value.find(i => i.name === 'Main')
      packingItems.value.forEach(pi => { pi.pcsRolls = pi.name === 'Main' ? pcsPerBdlPassed.toString() : '' })
      const toDel = Number((mainItem?.toDel || '0').toString().replace(/,/g, ''))
      if (mainItem && toDel > 0) {
        const bdls = Math.floor(toDel / pcsPerBdlPassed)
        const loose = toDel % pcsPerBdlPassed
        mainItem.rolls = bdls ? bdls.toString() : ''
        mainItem.qty = pcsPerBdlPassed.toString()
        mainItem.looseQty = loose ? loose.toString() : ''
      }
      // We have data; skip API fetch later by returning early
      return
    }
  }

  // Fetch pcs_per_bdl from SO detail API using S/Order parts
  try {
    const od = props.salesOrderDetailData?.orderDetail || {}
    const soNumber = od.soNumber || (() => {
      const soMonth = (od.sOrderMonth || '').toString().padStart(2, '0')
      const soYear = od.sOrderYear || ''
      const soSeq = (od.sOrderSeq || '').toString().padStart(5, '0')
      return [soMonth, soYear, soSeq].every(Boolean) ? `${soMonth}-${soYear}-${soSeq}` : ''
    })()
    if (soNumber) {
      const resp = await axios.get(`/api/sales-order/${soNumber}/detail`)
      const data = resp.data?.data || {}
      const pcsPerBdl = Number(data?.item_details?.pcs_per_bdl || 0)
      if (pcsPerBdl > 0) {
        // Set only for Main, clear others
        const mainItem = packingItems.value.find(i => i.name === 'Main')
        packingItems.value.forEach(pi => { pi.pcsRolls = pi.name === 'Main' ? pcsPerBdl.toString() : '' })
        const toDel = Number(mainItem?.toDel || 0)
        if (mainItem && toDel > 0) {
          const bdls = Math.floor(toDel / pcsPerBdl)
          const loose = toDel % pcsPerBdl
          mainItem.rolls = bdls ? bdls.toString() : ''
          mainItem.qty = pcsPerBdl.toString()
          mainItem.looseQty = loose ? loose.toString() : ''
        }
      }
    }
  } catch (e) {
    // ignore; user can fill manually
  }
}

// Populate on mount and whenever modal opens or data changes
onMounted(populatePackingFromProps)
watch(() => props.isOpen, (val) => { if (val) populatePackingFromProps() })
watch(() => props.salesOrderDetailData, () => { populatePackingFromProps() }, { deep: true })
</script>

<style scoped>
/* Custom styles for table inputs */
.border-0 {
  border: none !important;
}

.border-0:focus {
  outline: none;
  box-shadow: none;
}

/* Table cell styling */
td input {
  background: transparent;
}

td input:focus {
  background: #f0f9ff;
}

/* Delivery schedule table styling */
.divide-y > * + * {
  border-top-width: 1px;
}

.divide-gray-200 > * + * {
  border-color: #e5e7eb;
}
</style>
