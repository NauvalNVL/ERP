<template>
  <AppLayout :header="'Define Customer Sales Tax Index'">
    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div
          class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4"
        >
          <div
            class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
          >
            <div class="flex items-center gap-3">
              <div
                class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center"
              >
                <i class="fas fa-user-tag text-white text-sm"></i>
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                  Define Customer Sales Tax Index
                </h2>
                <p class="text-xs sm:text-sm text-blue-100">
                  Configure customer-specific sales tax settings.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-4">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200">
              <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg mr-3 text-white">
                  <i class="fas fa-edit"></i>
                </div>
                <div>
                  <h3 class="text-sm sm:text-base font-semibold text-slate-800">
                    Customer Tax Index Management
                  </h3>
                  <p class="text-xs text-slate-500">Define tax settings for customers.</p>
                </div>
              </div>
              <div class="px-4 py-4 sm:px-6">
                <!-- Customer Code Input -->
                <div class="mb-4">
                  <label
                    for="customerCode"
                    class="block text-sm font-semibold text-slate-700 mb-1"
                    >Customer Code</label
                  >
                  <div class="relative flex">
                    <input
                      id="customerCode"
                      v-model="form.customer_code"
                      @input="handleCustomerCodeInput"
                      type="text"
                      placeholder="Search customer code..."
                      class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-slate-800 placeholder-slate-400 text-sm"
                      :readonly="recordMode !== 'select'"
                      :class="{
                        'bg-gray-100 cursor-not-allowed': recordMode !== 'select',
                      }"
                    />
                    <button
                      type="button"
                      @click="openCustomerModal"
                      class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-500 bg-blue-600 hover:bg-blue-700 text-white rounded-r-md text-sm"
                      title="Select Customer"
                    >
                      <i class="fas fa-table"></i>
                    </button>
                  </div>
                  <p v-if="selectedCustomer" class="text-sm text-gray-600 mt-1">
                    {{ selectedCustomer.name }}
                  </p>
                </div>

                <!-- Sales Tax Index# Input -->
                <div class="mb-4">
                  <label
                    for="salesTaxIndex"
                    class="block text-sm font-semibold text-slate-700 mb-1"
                    >Sales Tax Index#</label
                  >
                  <div class="relative flex">
                    <input
                      id="salesTaxIndex"
                      v-model.number="form.index_number"
                      type="number"
                      min="1"
                      placeholder="Enter index number..."
                      class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-slate-800 placeholder-slate-400 text-sm"
                      :readonly="!form.customer_code"
                      :class="{ 'bg-gray-100 cursor-not-allowed': !form.customer_code }"
                    />
                    <button
                      type="button"
                      @click="openIndexTableModal"
                      :disabled="!form.customer_code"
                      class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-500 bg-blue-600 hover:bg-blue-700 text-white rounded-r-md text-sm"
                      :class="{ 'opacity-50 cursor-not-allowed': !form.customer_code }"
                      title="View Customer's Tax Indices"
                    >
                      <i class="fas fa-table"></i>
                    </button>
                  </div>
                </div>

                <!-- Data Status Information -->
                <div
                  v-if="recordMode === 'select'"
                  class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg"
                >
                  <p class="text-sm font-semibold text-amber-800">
                    No customer selected.
                  </p>
                  <p class="text-xs text-amber-700 mt-1">
                    Select a customer and index number to begin.
                  </p>
                </div>
                <div v-else class="mt-3 bg-blue-50 border border-blue-200 p-3 rounded-lg">
                  <p class="text-sm font-semibold text-blue-800">
                    Customer: {{ form.customer_code }} | Index#: {{ form.index_number }}
                  </p>
                  <p class="text-xs text-blue-700 mt-1">
                    Mode: {{ recordMode === "review" ? "Review/Edit" : "New Entry" }}
                  </p>
                </div>

                <!-- Form Fields (shown only when customer and index selected) -->
                <div v-if="recordMode !== 'select'" class="space-y-4 mt-4">
                  <!-- Index Status -->
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                      Index Status:
                    </label>
                    <div class="flex gap-4">
                      <label class="flex items-center cursor-pointer">
                        <input
                          type="radio"
                          v-model="form.status"
                          value="A"
                          class="mr-2"
                        />
                        <span class="text-sm">A-Active</span>
                      </label>
                      <label class="flex items-center cursor-pointer">
                        <input
                          type="radio"
                          v-model="form.status"
                          value="O"
                          class="mr-2"
                        />
                        <span class="text-sm">O-Obsolete</span>
                      </label>
                    </div>
                  </div>

                  <!-- Tax Group -->
                  <div>
                    <label
                      for="taxGroup"
                      class="block text-sm font-semibold text-slate-700 mb-1"
                    >
                      Tax Group:
                    </label>
                    <div class="relative flex">
                      <input
                        id="taxGroup"
                        v-model="form.tax_group_code"
                        type="text"
                        placeholder="Select tax group..."
                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-200 bg-gray-100 cursor-pointer text-sm"
                        readonly
                        @click="openTaxGroupModal"
                      />
                      <button
                        type="button"
                        @click="openTaxGroupModal"
                        class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-500 bg-blue-600 hover:bg-blue-700 text-white rounded-r-md text-sm"
                      >
                        <i class="fas fa-table"></i>
                      </button>
                    </div>
                    <p v-if="selectedTaxGroup" class="text-sm text-gray-600 mt-1">
                      {{ selectedTaxGroup.name }}
                    </p>
                  </div>

                  <!-- Save/Cancel Buttons -->
                  <div
                    class="flex justify-between items-center pt-4 border-t border-gray-200"
                  >
                    <button
                      v-if="recordMode === 'review'"
                      type="button"
                      @click="handleDelete"
                      class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium"
                    >
                      <i class="fas fa-ban mr-2"></i>
                      Obsolete
                    </button>
                    <div v-else></div>

                    <div class="flex gap-3">
                      <button
                        type="button"
                        @click="handleCancel"
                        class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium"
                      >
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                      </button>
                      <button
                        type="button"
                        @click="handleSave"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium"
                      >
                        <i class="fas fa-save mr-2"></i>
                        Save
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Quick Info -->
          <div class="lg:col-span-1 space-y-4">
            <!-- Info Card -->
            <div class="bg-white shadow-sm rounded-xl border border-blue-100">
              <div class="px-4 py-3 sm:px-5 border-b border-blue-100 flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                  <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-blue-900">
                  Information
                </h3>
              </div>
              <div class="px-4 py-4 sm:px-5">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <h4
                    class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2"
                  >
                    Instructions
                  </h4>
                  <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                    <li>Select customer and index number</li>
                    <li>Choose applicable tax group</li>
                    <li>Manage multiple tax indices</li>
                    <li>Save changes to apply</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Account Modal (Reuse Existing) -->
    <CustomerAccountModal
      v-if="showCustomerModal"
      :show="showCustomerModal"
      :customerAccounts="customers"
      :initial-search="searchQuery"
      @close="showCustomerModal = false"
      @select="selectCustomer"
    />

    <!-- Tax Group Modal (Reuse Existing) -->
    <TaxGroupModal
      :show="showTaxGroupModal"
      @close="showTaxGroupModal = false"
      @select="selectTaxGroup"
    />

    <!-- Customer Sales Tax Index Table Modal -->
    <CustomerSalesTaxorSalesTaxExemptionTable
      :open="showIndexTableModal"
      :customerCode="form.customer_code"
      @close="showIndexTableModal = false"
      @select="selectIndex"
    />
  </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerAccountModal from "@/Components/customer-account-modal.vue";
import TaxGroupModal from "@/Components/TaxGroupModal.vue";
import CustomerSalesTaxorSalesTaxExemptionTable from "@/Components/CustomerSalesTaxorSalesTaxExemptionTable.vue";
import axios from "axios";
import Swal from "sweetalert2";

// Record modes
const recordMode = ref("select"); // 'select', 'add', 'review'

// Form data
const form = ref({
  customer_code: "",
  index_number: null,
  tax_group_code: "",
  status: "A",
});

// Selected data objects
const selectedCustomer = ref(null);
const selectedTaxGroup = ref(null);
const lastLoadedKey = ref("");
const taxGroups = ref([]);

// Modal states
const showCustomerModal = ref(false);
const showTaxGroupModal = ref(false);
const showIndexTableModal = ref(false);

// Customer data
const customers = ref([]);
const searchQuery = ref("");

// Handle customer code input
const handleCustomerCodeInput = () => {
  if (recordMode.value !== "select") return;
  // Real-time validation or search can be added here
};

// Open customer modal
const openCustomerModal = async () => {
  // Load customer accounts if not already loaded
  if (customers.value.length === 0) {
    await loadCustomerAccounts();
  }
  showCustomerModal.value = true;
};

// Load customer accounts from API
const loadCustomerAccounts = async () => {
  try {
    const response = await axios.get("/api/customers-with-status?status=active");
    const data = response.data;

    if (Array.isArray(data)) {
      customers.value = data;
    } else if (data.data && Array.isArray(data.data)) {
      customers.value = data.data;
    }
  } catch (error) {
    console.error("Error loading customer accounts:", error);
    await Swal.fire({
      icon: "error",
      title: "Failed to Load Customers",
      text:
        "Failed to load customer accounts: " +
        (error.response?.data?.message || error.message),
    });
  }
};

// Select customer from modal
const selectCustomer = async (account) => {
  // Set customer code and name from selected account
  form.value.customer_code = account.customer_code;
  selectedCustomer.value = {
    code: account.customer_code,
    name: account.customer_name,
  };
  showCustomerModal.value = false;

  // Check if customer has existing indices
  await checkCustomerIndices();
};

// Check if customer has existing tax indices
const checkCustomerIndices = async () => {
  if (!form.value.customer_code) return;

  try {
    const response = await axios.get(
      `/api/invoices/customer-tax-indices/${form.value.customer_code}`
    );
    if (response.data.success && response.data.data.length > 0) {
      // Customer has indices, suggest opening index table
      console.log("Customer has", response.data.data.length, "tax indices");
    }
  } catch (error) {
    console.error("Error checking customer indices:", error);
  }
};

// Open index table modal
const openIndexTableModal = async () => {
  if (!form.value.customer_code) {
    await Swal.fire({
      icon: "warning",
      title: "Customer Required",
      text: "Please select a customer first.",
    });
    return;
  }
  showIndexTableModal.value = true;
};

// Select index from modal
const selectIndex = (indexData) => {
  // indexData contains: index_number, tax_group_code, tax_group_name, status
  form.value.index_number = indexData.index_number;
  setReviewStateFromData(indexData);
  showIndexTableModal.value = false;
};

// Open tax group modal
const openTaxGroupModal = () => {
  showTaxGroupModal.value = true;
};

// Select tax group
const selectTaxGroup = (taxGroup) => {
  form.value.tax_group_code = taxGroup.code;
  selectedTaxGroup.value = taxGroup;
  showTaxGroupModal.value = false;
};

// Handle save
const handleSave = async () => {
  // Validation
  if (!form.value.customer_code) {
    await Swal.fire({
      icon: "warning",
      title: "Customer Required",
      text: "Please select a customer.",
    });
    return;
  }

  if (!form.value.index_number || form.value.index_number < 1) {
    await Swal.fire({
      icon: "warning",
      title: "Invalid Index Number",
      text: "Please enter a valid index number.",
    });
    return;
  }

  if (!form.value.tax_group_code) {
    await Swal.fire({
      icon: "warning",
      title: "Tax Group Required",
      text: "Please select a tax group.",
    });
    return;
  }

  const confirmResult = await Swal.fire({
    icon: "question",
    title: "Confirm Save",
    text: "Confirm Saving / Updating ?",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No",
  });

  if (!confirmResult.isConfirmed) {
    return;
  }

  try {
    const response = await axios.post("/api/invoices/customer-tax-indices", form.value);

    if (response.data.success) {
      await Swal.fire({
        icon: "success",
        title: "Saved",
        text: "Customer sales tax index saved successfully!",
      });
      recordMode.value = "review";
      updateLastLoadedKey(form.value.customer_code?.trim(), form.value.index_number);
    }
  } catch (error) {
    console.error("Error saving:", error);
    await Swal.fire({
      icon: "error",
      title: "Save Failed",
      text: "Failed to save: " + (error.response?.data?.message || error.message),
    });
  }
};

// Handle delete (mark as obsolete in UI wording, backend still deletes record)
const handleDelete = async () => {
  const confirmResult = await Swal.fire({
    icon: "warning",
    title: "Confirm Delete",
    text: "Are you sure you want to delete this tax index?",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    confirmButtonText: "Yes, delete it",
    cancelButtonText: "Cancel",
  });

  if (!confirmResult.isConfirmed) {
    return;
  }

  try {
    const response = await axios.delete(
      `/api/invoices/customer-tax-indices/${form.value.customer_code}/${form.value.index_number}`
    );

    if (response.data.success) {
      await Swal.fire({
        icon: "success",
        title: "Deleted",
      });
      handleCancel();
    }
  } catch (error) {
    console.error("Error deleting:", error);
    await Swal.fire({
      icon: "error",
      title: "Delete Failed",
      text: "Failed to delete: " + (error.response?.data?.message || error.message),
    });
  }
};

// Handle cancel
const handleCancel = () => {
  form.value = {
    customer_code: "",
    index_number: null,
    tax_group_code: "",
    status: "A",
  };
  selectedCustomer.value = null;
  selectedTaxGroup.value = null;
  recordMode.value = "select";
  updateLastLoadedKey("", null);
};

const updateLastLoadedKey = (customer, index) => {
  lastLoadedKey.value = customer && index ? `${customer}#${index}` : "";
};

const setReviewStateFromData = (data) => {
  form.value.tax_group_code = data.tax_group_code || "";
  form.value.status = data.status || "A";
  selectedTaxGroup.value = data.tax_group
    ? {
        code: data.tax_group_code,
        name: data.tax_group?.name || data.tax_group_name || "",
      }
    : data.tax_group_name
    ? { code: data.tax_group_code, name: data.tax_group_name }
    : null;
  recordMode.value = "review";
  updateLastLoadedKey(form.value.customer_code?.trim(), form.value.index_number);
};

const prepareNewEntryState = () => {
  recordMode.value = "add";
  form.value.status = "A";
  form.value.tax_group_code = "";
  selectedTaxGroup.value = null;
  updateLastLoadedKey("", null);
};

const loadExistingIndex = async () => {
  const customer = form.value.customer_code?.trim();
  const indexNumber = Number(form.value.index_number);
  if (!customer || !indexNumber) return;

  const key = `${customer}#${indexNumber}`;
  if (lastLoadedKey.value === key && recordMode.value === "review") {
    return;
  }

  try {
    const response = await axios.get(
      `/api/invoices/customer-tax-indices/${encodeURIComponent(customer)}/${indexNumber}`
    );

    if (response.data?.success && response.data.data) {
      const data = response.data.data;
      setReviewStateFromData(data);
      if (!selectedTaxGroup.value && data.tax_group_code) {
        selectedTaxGroup.value = {
          code: data.tax_group_code,
          name: data.tax_group?.name || data.tax_group_name || "",
        };
      }
    } else {
      prepareNewEntryState();
    }
  } catch (error) {
    if (error.response?.status === 404) {
      prepareNewEntryState();
      return;
    }
    console.error("Error loading customer tax index:", error);
    await Swal.fire({
      icon: "error",
      title: "Load Failed",
      text:
        error.response?.data?.message ||
        error.message ||
        "Failed to load customer tax index.",
    });
  }
};

let loadIndexTimeout = null;
watch(
  [() => form.value.customer_code, () => form.value.index_number],
  ([customer, index]) => {
    if (loadIndexTimeout) clearTimeout(loadIndexTimeout);

    if (!customer || !index) {
      recordMode.value = "select";
      selectedTaxGroup.value = null;
      updateLastLoadedKey("", null);
      return;
    }

    loadIndexTimeout = setTimeout(() => {
      loadExistingIndex();
    }, 250);
  }
);

const fetchTaxGroups = async () => {
  try {
    const response = await axios.get("/api/invoices/tax-groups");
    if (response.data?.success) {
      taxGroups.value = response.data.data || [];
    }
  } catch (error) {
    console.error("Error fetching tax groups:", error);
  }
};

onMounted(() => {
  fetchTaxGroups();
});
</script>
