<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-[60]" @close="$emit('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
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
            <DialogPanel
              class="w-full max-w-6xl max-h-[90vh] transform bg-white shadow-2xl rounded-xl flex flex-col overflow-hidden"
            >
              <!-- Header -->
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                <div class="flex items-center justify-between">
                  <DialogTitle
                    class="text-sm font-semibold text-white flex items-center gap-2"
                  >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                      />
                    </svg>
                    Sales Order Items Screen
                  </DialogTitle>
                  <button
                    @click="$emit('close')"
                    class="text-white hover:bg-blue-800 p-1 rounded"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>
              </div>
              <!-- Main content (scrollable) -->
              <div class="flex-1 overflow-y-auto">
                <!-- Content -->
                <div class="p-4">
                  <!-- Loading Indicator -->
                  <div v-if="loading" class="text-center py-8">
                    <div
                      class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
                    ></div>
                    <p class="mt-2 text-sm text-gray-600">Loading DO items...</p>
                  </div>

                  <!-- Content (show when not loading) -->
                  <div v-else>
                    <!-- Header Info Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-xs">
                      <div class="flex items-center gap-2">
                        <label class="font-semibold text-gray-700">D/Order#:</label>
                        <span class="font-medium text-blue-700">{{
                          doData?.do_number || doNumber
                        }}</span>
                      </div>
                      <div class="flex items-center gap-2">
                        <label class="font-semibold text-gray-700">D/O Date:</label>
                        <span>{{ doData?.do_date || doDate }}</span>
                      </div>
                      <div class="flex items-center gap-2">
                        <label class="font-semibold text-gray-700"
                          >Control Set Order:</label
                        >
                        <span>{{ controlSetOrder }}</span>
                      </div>
                    </div>

                    <!-- S/O List Table -->
                    <div class="border border-gray-300 rounded-md overflow-x-auto mb-4">
                      <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                          <tr>
                            <th
                              class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                            >
                              No
                            </th>
                            <th
                              class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                            >
                              S/O Item#
                            </th>
                            <th
                              class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                            >
                              M/Card Seq#
                            </th>
                            <th
                              class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                            >
                              D/Order Ref.#
                            </th>
                            <th
                              class="px-2 py-2 text-right text-xs font-semibold uppercase tracking-wider"
                            >
                              Amount
                            </th>
                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                          <tr v-if="soList.length === 0" class="bg-yellow-100">
                            <td class="px-2 py-2 text-sm font-medium">1001</td>
                            <td class="px-2 py-2 text-sm">
                              {{ doData?.so_number || soNumber || "-" }}
                            </td>
                            <td class="px-2 py-2 text-sm">
                              {{ doData?.mc_seq || mcSeq || "-" }}
                            </td>
                            <td class="px-2 py-2 text-sm">
                              {{ doData?.do_ref || doData?.do_number || doNumber }}
                            </td>
                            <td class="px-2 py-2 text-sm text-right font-semibold">
                              {{ formatCurrency(doData?.total_amount || totalAmount) }}
                            </td>
                          </tr>
                          <tr
                            v-for="(item, index) in soList"
                            :key="index"
                            :class="index === 0 ? 'bg-yellow-100' : ''"
                          >
                            <td class="px-2 py-2 text-sm font-medium">
                              {{ 1001 + index }}
                            </td>
                            <td class="px-2 py-2 text-sm">{{ item.so_number }}</td>
                            <td class="px-2 py-2 text-sm">{{ item.mc_seq }}</td>
                            <td class="px-2 py-2 text-sm">{{ item.do_ref }}</td>
                            <td class="px-2 py-2 text-sm text-right font-semibold">
                              {{ formatCurrency(item.amount) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <!-- S/O Count and Totals -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 text-xs">
                      <div class="flex items-center gap-2">
                        <label class="font-semibold text-gray-700">S/O Count:</label>
                        <span class="font-medium">{{ soCount }}</span>
                      </div>
                      <div></div>
                      <div></div>
                      <div class="flex items-center gap-2 justify-between md:justify-end">
                        <label class="font-semibold text-gray-700">Total:</label>
                        <span class="font-bold text-blue-700">{{
                          formatCurrency(total || totalAmount)
                        }}</span>
                      </div>
                    </div>

                    <!-- Model Info -->
                    <div class="mb-4 text-xs">
                      <div class="flex items-center gap-2">
                        <label class="font-semibold text-gray-700">Model:</label>
                        <span class="font-medium text-blue-700">{{
                          doData?.model || model
                        }}</span>
                      </div>
                    </div>

                    <!-- Item Details Table -->
                    <div class="border border-gray-300 rounded-md overflow-x-auto">
                      <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-100">
                          <tr>
                            <th class="px-2 py-2 text-left font-semibold text-gray-700">
                              Item
                            </th>
                            <th class="px-2 py-2 text-left font-semibold text-gray-700">
                              P/Design
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              Pcs
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              Unit
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              U/Price
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              Deliver
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              Reject
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              Unbill
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              To Bill
                            </th>
                            <th class="px-2 py-2 text-center font-semibold text-gray-700">
                              To Bill KG
                            </th>
                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                          <tr v-if="itemDetails.length === 0">
                            <td class="px-2 py-2 font-medium">Main</td>
                            <td class="px-2 py-2">B1</td>
                            <td class="px-2 py-2 text-center">1</td>
                            <td class="px-2 py-2 text-center">Pcs</td>
                            <td class="px-2 py-2 text-right">
                              {{ formatCurrency(unitPrice) }}
                            </td>
                            <td class="px-2 py-2 text-center">300</td>
                            <td class="px-2 py-2 text-center">-</td>
                            <td class="px-2 py-2 text-center">300</td>
                            <td class="px-2 py-2 text-center">
                              <input
                                type="number"
                                placeholder="0"
                                class="w-full px-1 py-1 text-center border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                              />
                            </td>
                            <td class="px-2 py-2 text-center">0.00</td>
                          </tr>
                          <tr
                            v-for="(item, index) in itemDetails"
                            :key="index"
                            :class="item.item === 'Main' ? '' : 'bg-gray-50'"
                          >
                            <td class="px-2 py-2 font-medium">
                              {{ item.item }}
                            </td>
                            <td
                              class="px-2 py-2"
                              :class="item.p_design === '-' ? 'text-gray-400' : ''"
                            >
                              {{ item.p_design }}
                            </td>
                            <td
                              class="px-2 py-2 text-center"
                              :class="item.pcs === null ? 'text-gray-400' : ''"
                            >
                              {{ formatNumber(item.pcs) }}
                            </td>
                            <td
                              class="px-2 py-2 text-center"
                              :class="item.unit === null ? 'text-gray-400' : ''"
                            >
                              {{ item.unit || "-" }}
                            </td>
                            <td
                              class="px-2 py-2 text-right"
                              :class="item.u_price === null ? 'text-gray-400' : ''"
                            >
                              {{ formatCurrency(item.u_price) }}
                            </td>
                            <td
                              class="px-2 py-2 text-center"
                              :class="item.deliver === null ? 'text-gray-400' : ''"
                            >
                              {{ formatNumber(item.deliver) }}
                            </td>
                            <td class="px-2 py-2 text-center text-gray-400">
                              {{ formatNumber(item.reject) }}
                            </td>
                            <td
                              class="px-2 py-2 text-center"
                              :class="item.unbill === null ? 'text-gray-400' : ''"
                            >
                              {{ formatNumber(item.unbill) }}
                            </td>
                            <td class="px-2 py-2 text-center">
                              <input
                                v-if="!isKgUnit(item)"
                                v-model.number="item.to_bill"
                                @input="onToBillChange(item)"
                                type="number"
                                :max="item.unbill"
                                placeholder="0"
                                class="w-full px-1 py-1 text-center border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                              />
                              <span v-else class="text-gray-400"></span>
                            </td>
                            <td class="px-2 py-2 text-center">
                              <input
                                v-if="isKgUnit(item)"
                                v-model.number="item.to_bill_kg"
                                @input="onToBillKgChange(item)"
                                type="number"
                                :max="item.unbill"
                                step="0.0001"
                                placeholder="0"
                                class="w-full px-1 py-1 text-center border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                              />
                              <span v-else class="text-gray-400"></span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div
                class="px-4 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-t-2 border-gray-300 flex justify-center gap-3 shadow-inner"
              >
                <button
                  @click="$emit('close')"
                  class="px-8 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-400 rounded hover:bg-gray-50 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all shadow-sm"
                >
                  Cancel
                </button>
                <button
                  @click="handleConfirm"
                  class="px-8 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 border-2 border-blue-600 rounded hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md"
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
import { ref, computed, watch, onMounted } from "vue";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";

const props = defineProps({
  open: { type: Boolean, default: false },
  doNumber: { type: String, default: "" },
  doDate: { type: String, default: "" },
  soNumber: { type: String, default: "" },
  mcSeq: { type: String, default: "" },
  model: { type: String, default: "SHIPPING CASE ACOSTA JUMBO" },
  totalAmount: { type: Number, default: 0 },
  unitPrice: { type: Number, default: 14700000 },
});

const emit = defineEmits(["close", "confirm"]);

// State for fetched data
const loading = ref(false);
const doData = ref(null);
const soList = ref([]);
const itemDetails = ref([]);
const controlSetOrder = ref(0);
const soCount = ref(1);
const total = ref(0);

// Helper functions
const normalizeUnit = (unit) =>
  String(unit || "")
    .trim()
    .toUpperCase();
const isKgUnit = (item) => normalizeUnit(item?.unit) === "KG";

// Store original KG per unit for calculations
const originalKgPerUnit = ref(0);

// Fetch DO items data from API
const fetchDoItems = async () => {
  if (!props.doNumber) {
    console.warn("⚠️ No DO number provided");
    return;
  }

  loading.value = true;
  console.log("🔄 Fetching DO items for:", props.doNumber);

  try {
    const response = await fetch(`/api/invoices/do-items?do_number=${props.doNumber}`, {
      headers: { Accept: "application/json" },
    });

    if (response.ok) {
      const data = await response.json();
      console.log("✅ DO items data received:", data);

      doData.value = data;
      soList.value = data.so_list || [];
      itemDetails.value = (data.item_details || []).map((row) => {
        const unitIsKg = normalizeUnit(row?.unit) === "KG";
        return {
          ...row,
          to_bill: unitIsKg ? null : Number(row?.to_bill || 0),
          to_bill_kg: null,
          unbill: Number(row?.unbill || 0),
          u_price: Number(row?.u_price || 0),
        };
      });
      controlSetOrder.value = data.control_set_order || 0;
      soCount.value = data.s_o_count || 1;

      // Total should be 0 initially - will calculate when user inputs to_bill
      // This matches CPS behavior where user must explicitly input quantities
      total.value = 0;

      // Calculate and store original KG per unit for future calculations
      const mainItem = data.item_details?.find((item) => item.item === "Main");
      if (mainItem) {
        // Priority 1: Use kg_per_unit from API (most reliable)
        if (mainItem.kg_per_unit && mainItem.kg_per_unit > 0) {
          originalKgPerUnit.value = mainItem.kg_per_unit;
          console.log("📦 KG per unit (from API):", originalKgPerUnit.value.toFixed(4));
        }
        // Priority 2: Calculate from deliver quantity if no kg_per_unit
        // Note: to_bill_kg is 0 initially, so we can't use it for calculation
        else {
          console.warn("⚠️ No kg_per_unit from API, KG calculation may not work");
          originalKgPerUnit.value = 0;
        }

        console.log("📊 KG Calculation Setup:", {
          kg_per_unit: mainItem.kg_per_unit,
          deliver: mainItem.deliver,
          originalKgPerUnit: originalKgPerUnit.value,
          ready: originalKgPerUnit.value > 0,
        });
      }

      // Set S/O List amount to 0 initially (will update when user inputs to_bill)
      if (soList.value.length > 0) {
        soList.value[0].amount = 0;
      }

      console.log("📊 Populated:", {
        soCount: soCount.value,
        total: total.value,
        itemCount: itemDetails.value.length,
        originalKgPerUnit: originalKgPerUnit.value,
        note: "Total is 0 - user must input To Bill quantity (CPS-compatible)",
      });
    } else {
      console.error("❌ Failed to fetch DO items:", response.status);
      const errorData = await response.json();
      console.error("Error details:", errorData);
    }
  } catch (error) {
    console.error("❌ Error fetching DO items:", error);
  } finally {
    loading.value = false;
  }
};

// Watch for modal open and DO number changes
watch(
  () => props.open,
  (isOpen) => {
    if (isOpen && props.doNumber) {
      fetchDoItems();
    }
  }
);

watch(
  () => props.doNumber,
  (newDoNumber) => {
    if (props.open && newDoNumber) {
      fetchDoItems();
    }
  }
);

const formatCurrency = (value) => {
  if (value === null || value === undefined) return "-";
  return new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(value);
};

const formatNumber = (value, decimals = 0) => {
  if (value === null || value === undefined) return "-";
  return new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  }).format(value);
};

// Calculate To Bill KG when To Bill quantity changes (CPS-compatible)
const onToBillChange = (item) => {
  console.log("📝 To Bill changed for:", item.item, "New value:", item.to_bill);

  // Validate: To Bill cannot exceed Unbill
  if (item.to_bill > item.unbill) {
    console.warn("⚠️ To Bill exceeds Unbill, capping to Unbill");
    item.to_bill = item.unbill;
  }

  // Validate: Cannot be negative
  if (item.to_bill < 0) {
    item.to_bill = 0;
  }

  // Mutual exclusivity: when To Bill is used, To Bill KG must be empty
  item.to_bill_kg = null;

  // Recalculate total amount
  calculateTotal();

  console.log("✅ Updated:", {
    to_bill: item.to_bill,
    to_bill_kg:
      item.to_bill_kg === null || item.to_bill_kg === undefined
        ? null
        : Number(item.to_bill_kg).toFixed(2),
    new_total: formatCurrency(total.value),
  });
};

const onToBillKgChange = (item) => {
  console.log("📝 To Bill KG changed for:", item.item, "New value:", item.to_bill_kg);

  // Mutual exclusivity: when unit is KG, only to_bill_kg may be filled
  item.to_bill = null;

  // Validate: To Bill KG cannot exceed Unbill (for KG unit, unbill is assumed KG)
  if (Number(item.to_bill_kg || 0) > Number(item.unbill || 0)) {
    console.warn("⚠️ To Bill KG exceeds Unbill, capping to Unbill");
    item.to_bill_kg = Number(item.unbill || 0);
  }

  if (Number(item.to_bill_kg || 0) < 0) {
    item.to_bill_kg = 0;
  }

  calculateTotal();

  console.log("✅ Updated (KG):", {
    to_bill: item.to_bill,
    to_bill_kg: Number(item.to_bill_kg || 0).toFixed(4),
    new_total: formatCurrency(total.value),
  });
};

// Calculate total based on To Bill quantities
const calculateTotal = () => {
  let newTotal = 0;

  itemDetails.value.forEach((item) => {
    const unitPrice = Number(item.u_price || 0);
    if (!unitPrice || unitPrice <= 0) return;

    if (isKgUnit(item)) {
      const qtyKg = Number(item.to_bill_kg || 0);
      if (qtyKg > 0) newTotal += qtyKg * unitPrice;
    } else {
      const qty = Number(item.to_bill || 0);
      if (qty > 0) newTotal += qty * unitPrice;
    }
  });

  total.value = newTotal;

  // Update soList amounts if exists
  if (soList.value.length > 0) {
    soList.value[0].amount = newTotal;
  }

  console.log("💰 Total recalculated:", formatCurrency(newTotal));
};

const handleConfirm = () => {
  console.log("✅ Sales Order Items confirmed, proceeding to Final Tax Calculation");

  // Use calculated total from user input (not original from API)
  const finalTotal = total.value || doData.value?.total_amount || props.totalAmount;

  // Prepare updated DO data with user-edited values
  const updatedDoData = {
    ...doData.value,
    total_amount: finalTotal,
    item_details: itemDetails.value, // Include updated to_bill values
  };

  console.log("📤 Emitting data:", {
    finalTotal: formatCurrency(finalTotal),
    itemDetails: itemDetails.value,
  });

  emit("confirm", {
    doNumber: props.doNumber,
    totalAmount: finalTotal,
    model: doData.value?.model || props.model,
    doData: updatedDoData,
    itemDetails: itemDetails.value, // Pass updated item details
  });
};
</script>

<style scoped>
/* Custom table styling */
table {
  font-size: 11px;
  overflow-x: auto;
}

/* Highlighted row */
.bg-yellow-100 {
  background-color: #fef3cd;
}
</style>
