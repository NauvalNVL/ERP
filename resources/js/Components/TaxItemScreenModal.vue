<template>
  <!-- Tax Item Screen Modal (CPS-style) -->
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div
      class="bg-white rounded-lg shadow-2xl w-4/5 max-w-5xl max-h-[75vh] flex flex-col"
    >
      <!-- Modal Header -->
      <div
        class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center"
      >
        <div class="flex items-center gap-2">
          <i class="fas fa-list"></i>
          <h3 class="text-sm font-semibold">Tax Item Screen</h3>
        </div>
        <button @click="closeModal" class="text-white/90 hover:text-white">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Top Info Bar -->
      <div class="px-4 py-2 bg-gray-100 border-b">
        <div class="flex gap-2">
          <button
            @click="openTaxTypeTable"
            class="p-1.5 hover:bg-gray-200 rounded"
            title="Add Tax Type"
          >
            <i class="fas fa-plus text-blue-600"></i>
          </button>
          <button
            @click="deleteSelectedTaxType"
            class="p-1.5 hover:bg-gray-200 rounded"
            title="Delete Selected"
          >
            <i class="fas fa-trash text-red-600"></i>
          </button>
          <button
            @click="saveTaxItems"
            class="p-1.5 hover:bg-gray-200 rounded"
            title="Save Tax Items"
          >
            <i class="fas fa-save text-green-600"></i>
          </button>
          <button
            @click="loadTaxTypes"
            class="p-1.5 hover:bg-gray-200 rounded"
            title="Refresh"
          >
            <i class="fas fa-sync text-gray-600"></i>
          </button>
        </div>
      </div>

      <!-- Table Content -->
      <div class="flex-1 overflow-auto p-4">
        <table class="w-full text-sm border">
          <thead class="bg-gray-100 border-b-2 border-gray-300">
            <tr>
              <th class="px-3 py-2 text-left font-semibold border-r w-16">No</th>
              <th class="px-3 py-2 text-left font-semibold border-r w-16">Seq</th>
              <th class="px-3 py-2 text-left font-semibold border-r w-32">Tax Code</th>
              <th class="px-3 py-2 text-left font-semibold border-r">Tax Name</th>
              <th class="px-3 py-2 text-center font-semibold border-r w-20">Apply</th>
              <th class="px-3 py-2 text-center font-semibold border-r w-24">Include</th>
              <th class="px-3 py-2 text-right font-semibold border-r w-24">Tax %</th>
              <th class="px-3 py-2 text-left font-semibold w-32">Compute Item</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in taxTypes"
              :key="item.code"
              @click="selectedRow = item.code"
              :class="
                selectedRow === item.code ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'
              "
              class="cursor-pointer border-b"
            >
              <td class="px-3 py-2 border-r">{{ String(index + 1).padStart(2, "0") }}</td>
              <td class="px-3 py-2 border-r text-center">
                <input
                  type="number"
                  v-model.number="item.sequence"
                  min="1"
                  class="w-14 px-1 py-0.5 text-xs border rounded text-center bg-white"
                  @click.stop
                />
              </td>
              <td class="px-3 py-2 border-r font-mono">{{ item.code }}</td>
              <td class="px-3 py-2 border-r">{{ item.name }}</td>
              <td class="px-3 py-2 border-r text-center">
                <input type="checkbox" v-model="item.apply" class="h-4 w-4" @click.stop />
              </td>
              <td class="px-3 py-2 border-r text-center">
                <input
                  type="checkbox"
                  v-model="item.include"
                  class="h-4 w-4"
                  @click.stop
                />
              </td>
              <td class="px-3 py-2 border-r text-right">
                {{ Number(item.rate).toFixed(2) }}
              </td>
              <td class="px-3 py-2">{{ item.custom_type || "Nil" }}</td>
            </tr>
            <!-- Empty rows for visual (up to 10) -->
            <tr
              v-for="n in Math.max(0, 10 - taxTypes.length)"
              :key="'empty-' + n"
              class="border-b"
            >
              <td class="px-3 py-2 border-r text-center text-gray-400">
                {{ String(taxTypes.length + n).padStart(2, "0") }}
              </td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2 border-r"></td>
              <td class="px-3 py-2"></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer with Remark -->
      <div class="px-4 py-3 bg-gray-50 border-t">
        <div class="mb-3">
          <label class="text-sm font-medium text-gray-700">Remark:</label>
          <input
            type="text"
            value="Compute Item: Yes With computation Items"
            readonly
            class="w-full mt-1 px-3 py-1.5 border border-gray-300 rounded bg-gray-100 text-sm"
          />
        </div>
        <div class="flex justify-center gap-3">
          <button
            @click="closeModal"
            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm"
          >
            Exit
          </button>
        </div>
      </div>
    </div>

    <!-- Sales Tax Type Table Modal (inline, sama seperti Define Tax Type) -->
    <div
      v-if="showTaxTypeTableModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]"
    >
      <div
        class="bg-white rounded-lg shadow-2xl w-4/5 max-w-4xl max-h-[70vh] flex flex-col"
      >
        <!-- Modal Header -->
        <div
          class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center"
        >
          <div class="flex items-center gap-2">
            <i class="fas fa-table"></i>
            <h3 class="text-sm font-semibold">Sales Tax Type Table</h3>
          </div>
          <button
            @click="showTaxTypeTableModal = false"
            class="text-white/90 hover:text-white"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Table Content -->
        <div class="flex-1 overflow-auto p-4">
          <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
              <tr>
                <th class="px-4 py-2 text-left font-semibold">Tax</th>
                <th class="px-4 py-2 text-left font-semibold">Tax Name</th>
                <th class="px-4 py-2 text-left font-semibold">Apply</th>
                <th class="px-4 py-2 text-left font-semibold">Tax Rate %</th>
                <th class="px-4 py-2 text-left font-semibold">Custom Type</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="tx in availableTaxTypes"
                :key="tx.code"
                @dblclick="selectTaxTypeFromTable(tx)"
                :class="
                  selectedTaxTypeRow === tx.code
                    ? 'bg-blue-500 text-white'
                    : 'hover:bg-gray-100'
                "
                @click="selectedTaxTypeRow = tx.code"
                class="cursor-pointer border-b"
              >
                <td class="px-4 py-2">{{ tx.code }}</td>
                <td class="px-4 py-2">{{ tx.name }}</td>
                <td class="px-4 py-2">{{ tx.apply }}</td>
                <td class="px-4 py-2">{{ Number(tx.rate).toFixed(2) }}</td>
                <td class="px-4 py-2">{{ tx.custom_type }}</td>
              </tr>
              <tr v-if="availableTaxTypes.length === 0">
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                  No tax types found
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Modal Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t flex justify-center gap-3">
          <button
            @click="selectTaxTypeFromTable(getSelectedAvailableTaxType())"
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm"
          >
            Select
          </button>
          <button
            @click="showTaxTypeTableModal = false"
            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  taxGroupCode: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["close", "saved"]);

const taxTypes = ref([]); // Tax types yang sudah dipilih untuk group ini
const selectedRow = ref(null);
const showTaxTypeTableModal = ref(false);
const originalTaxTypes = ref([]); // Track original for comparison

// For available tax types modal
const availableTaxTypes = ref([]); // Semua tax types yang available
const selectedTaxTypeRow = ref(null); // Selected row di available tax types table

const loadTaxTypes = async () => {
  if (!props.taxGroupCode) {
    taxTypes.value = [];
    originalTaxTypes.value = [];
    return;
  }

  try {
    const res = await axios.get(
      `/api/invoices/tax-groups/${props.taxGroupCode}/tax-types`
    );
    console.log("Tax Item Screen - API Response:", res.data);

    if (res.data && res.data.success) {
      const data = res.data.data || [];
      // Map data to match expected format (including sequence/apply/include)
      taxTypes.value = data.map((item, index) => ({
        code: item.code || item.tax_code,
        name: item.name || item.tax_name,
        // store as boolean for easier binding
        apply: item.apply === true || item.apply === "Y",
        include: item.include === true || item.include === "Y",
        rate: item.rate || item.tax_rate || 0,
        custom_type: item.custom_type || "Nil",
        sequence: item.sequence ?? index + 1,
      }));
      // Store original for comparison
      originalTaxTypes.value = JSON.parse(JSON.stringify(taxTypes.value));
      console.log(
        "✅ Loaded tax types for group:",
        props.taxGroupCode,
        taxTypes.value.length
      );
    } else {
      taxTypes.value = [];
      originalTaxTypes.value = [];
    }
  } catch (e) {
    console.error("❌ Error loading tax types:", e);
    taxTypes.value = [];
    originalTaxTypes.value = [];
  }
};

const loadAvailableTaxTypes = async () => {
  try {
    const res = await axios.get("/api/invoices/tax-types");
    console.log("Available Tax Types - API Response:", res.data);

    if (res.data && res.data.success) {
      const rows = res.data.data || [];
      // Only keep active tax types (status = 'A') to avoid showing obsolete ones
      const activeRows = rows.filter(
        (row) => (row.status || "A").toString().toUpperCase() === "A"
      );
      // Normalize available tax types
      availableTaxTypes.value = activeRows.map((item) => ({
        code: item.code,
        name: item.name,
        apply: item.apply === "Y" || item.apply === true,
        rate: item.rate || 0,
        custom_type: item.custom_type || "Nil",
      }));
      console.log(
        "✅ Loaded available active tax types:",
        availableTaxTypes.value.length
      );
    } else {
      availableTaxTypes.value = [];
    }
  } catch (e) {
    console.error("❌ Error loading available tax types:", e);
    availableTaxTypes.value = [];
  }
};

const openTaxTypeTable = async () => {
  await loadAvailableTaxTypes();
  selectedTaxTypeRow.value = null;
  showTaxTypeTableModal.value = true;
};

const getSelectedAvailableTaxType = () => {
  return availableTaxTypes.value.find((t) => t.code === selectedTaxTypeRow.value);
};

const selectTaxTypeFromTable = (taxType) => {
  if (!taxType) {
    return;
  }

  // Check if already exists
  const exists = taxTypes.value.some((t) => t.code === taxType.code);
  if (exists) {
    Swal.fire({
      icon: "info",
      title: "Already added",
      text: `Tax type "${taxType.code}" is already added to this group.`,
      confirmButtonText: "OK",
    });
    return;
  }

  // Add to list with default CPS-style attributes
  taxTypes.value.push({
    code: taxType.code,
    name: taxType.name,
    apply: taxType.apply === true || taxType.apply === "Y",
    include: false,
    rate: taxType.rate || 0,
    custom_type: taxType.custom_type || "Nil",
    sequence: taxTypes.value.length + 1,
  });
  showTaxTypeTableModal.value = false;
  console.log("✅ Added tax type:", taxType.code);
};

const deleteSelectedTaxType = async () => {
  if (!selectedRow.value) {
    await Swal.fire({
      icon: "info",
      title: "No selection",
      text: "Please select a tax type to delete.",
      confirmButtonText: "OK",
    });
    return;
  }

  const confirmRes = await Swal.fire({
    title: "Remove tax type?",
    text: `Remove tax type "${selectedRow.value}" from this group?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "OK",
    cancelButtonText: "Cancel",
    reverseButtons: true,
    allowOutsideClick: false,
  });

  if (!confirmRes.isConfirmed) {
    return;
  }

  taxTypes.value = taxTypes.value.filter((t) => t.code !== selectedRow.value);
  selectedRow.value = null;
  console.log("✅ Deleted tax type");
};

const saveTaxItems = async () => {
  if (!props.taxGroupCode) {
    await Swal.fire({
      icon: "info",
      title: "No tax group",
      text: "No tax group selected.",
      confirmButtonText: "OK",
    });
    return;
  }

  const confirmRes = await Swal.fire({
    title: "Confirm Saving / Updating?",
    text: "Confirm Saving / Updating ?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "OK",
    cancelButtonText: "Cancel",
    reverseButtons: true,
    allowOutsideClick: false,
  });

  if (!confirmRes.isConfirmed) {
    return;
  }

  try {
    // Build detailed tax_items payload for CPS-style configuration
    const taxItemsPayload = taxTypes.value.map((t, index) => ({
      tax_type_code: t.code,
      sequence: t.sequence ?? index + 1,
      apply: !!t.apply,
      include: !!t.include,
    }));

    const response = await axios.post(
      `/api/invoices/tax-groups/${props.taxGroupCode}/tax-types`,
      {
        tax_items: taxItemsPayload,
      }
    );

    if (response.data && response.data.success) {
      await Swal.fire({
        icon: "success",
        title: "Saved",
        text: "Tax items saved successfully!",
        confirmButtonText: "OK",
      });
      originalTaxTypes.value = JSON.parse(JSON.stringify(taxTypes.value));
      emit("saved");
    } else {
      await Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error saving tax items: " + (response.data.message || "Unknown error"),
        confirmButtonText: "OK",
      });
    }
  } catch (e) {
    console.error("Error saving tax items:", e);
    await Swal.fire({
      icon: "error",
      title: "Error",
      text: "Error saving tax items: " + (e.response?.data?.message || e.message),
      confirmButtonText: "OK",
    });
  }
};

const closeModal = () => {
  emit("close");
};

// Reload tax items whenever the modal is opened, including after it was closed
watch(
  () => props.show,
  (newVal) => {
    if (newVal && props.taxGroupCode) {
      loadTaxTypes();
      selectedRow.value = null;
    }
  },
  { immediate: true }
);

// Also reload when tax group changes while modal is visible
watch(
  () => props.taxGroupCode,
  (newVal, oldVal) => {
    if (props.show && newVal && newVal !== oldVal) {
      loadTaxTypes();
    }
  }
);
</script>

<style scoped>
tr.cursor-pointer:not(.bg-blue-500):hover {
  background-color: #f3f4f6;
}
</style>
