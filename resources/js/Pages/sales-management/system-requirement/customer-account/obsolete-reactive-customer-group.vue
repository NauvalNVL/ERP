<template>
  <AppLayout header="Obsolete/Reactive Customer Group">
    <Head title="Obsolete/Reactive Customer Group" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
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
                <i class="fas fa-users text-white text-sm"></i>
              </div>
              <div>
                <h1 class="text-lg sm:text-xl font-semibold leading-tight">
                  Obsolete/Reactive Customer Group
                </h1>
                <p class="text-xs sm:text-sm text-blue-100">
                  Activate or deactivate customer groups quickly and easily.
                </p>
              </div>
            </div>
            <div class="relative w-full sm:w-72">
              <span
                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-100"
              >
                <i class="fas fa-search text-xs"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search customer group (code, name)..."
                class="block w-full rounded-md border border-gray-200 bg-white py-1.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
              />
            </div>
          </div>
        </div>

        <div
          v-if="notification.show"
          :class="[
            notification.type === 'success'
              ? 'bg-blue-50 border-blue-200 text-blue-800'
              : 'bg-red-50 border-red-200 text-red-800',
            'px-4 py-3 mb-4 rounded-lg border text-sm shadow-sm',
          ]"
        >
          <span class="block">{{ notification.message }}</span>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
          <div
            class="px-4 py-2 sm:px-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white"
          >
            <div>
              <h2 class="text-sm font-semibold text-gray-800">Customer Group List</h2>
              <p class="text-xs text-gray-500">
                {{ filteredGroups.length }} of {{ groups.length }} customer groups
              </p>
            </div>
            <div class="flex items-center gap-2">
              <label class="text-xs font-medium text-gray-600">Status:</label>
              <select
                v-model="statusFilter"
                class="py-1.5 px-2.5 text-xs border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="all">All</option>
                <option value="active">Active Only</option>
                <option value="obsolete">Obsolete Only</option>
              </select>
              <button
                @click="fetchGroups()"
                class="ml-2 py-1.5 px-3 text-xs border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              >
                <i class="fas fa-sync mr-1"></i>
                Refresh
              </button>
              <Link
                href="/customer-group"
                class="ml-2 py-1.5 px-3 text-xs border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              >
                <i class="fas fa-arrow-left mr-1"></i>
                Back
              </Link>
            </div>
          </div>

          <div class="relative">
            <div v-if="loading" class="py-10 flex justify-center items-center">
              <div
                class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
              ></div>
            </div>

            <div v-else class="overflow-x-auto">
              <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      scope="col"
                      class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                    >
                      Group Code
                    </th>
                    <th
                      scope="col"
                      class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                    >
                      Description
                    </th>
                    <th
                      scope="col"
                      class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide"
                    >
                      Status
                    </th>
                    <th
                      scope="col"
                      class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide"
                    >
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <tr
                    v-for="group in filteredGroups"
                    :key="group.group_code"
                    class="hover:bg-gray-50"
                  >
                    <td
                      class="px-4 py-2 whitespace-nowrap text-xs font-mono text-gray-700"
                    >
                      {{ group.group_code }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
                      {{ group.description }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-center">
                      <span
                        v-if="getStatusLabel(group) === 'Active'"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100"
                      >
                        <i class="fas fa-check-circle mr-1"></i>
                        Active
                      </span>
                      <span
                        v-else-if="getStatusLabel(group) === 'Obsolete'"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100"
                      >
                        <i class="fas fa-times-circle mr-1"></i>
                        Obsolete
                      </span>
                      <span
                        v-else
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-100"
                      >
                        <i class="fas fa-question-circle mr-1"></i>
                        Unknown
                      </span>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                      <div class="flex items-center justify-center">
                        <button
                          @click="toggleStatus(group)"
                          :disabled="isToggling || getStatusLabel(group) === 'Unknown'"
                          :class="[
                            getStatusLabel(group) === 'Unknown'
                              ? 'text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed'
                              : getStatusLabel(group) === 'Active'
                              ? 'text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100'
                              : 'text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-100',
                            'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 px-3 py-1 rounded-md text-xs font-semibold inline-flex items-center justify-center',
                          ]"
                          :style="{ minWidth: '120px' }"
                        >
                          <i
                            :class="[
                              getStatusLabel(group) === 'Active'
                                ? 'fas fa-toggle-off'
                                : 'fas fa-toggle-on',
                              'mr-1',
                            ]"
                          ></i>
                          {{
                            getStatusLabel(group) === "Unknown"
                              ? "N/A"
                              : getStatusLabel(group) === "Active"
                              ? "Mark Obsolete"
                              : "Mark Active"
                          }}
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredGroups.length === 0">
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                      No customer groups found.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div
            v-if="isToggling"
            class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-center"
          >
            <div class="bg-white px-6 py-4 rounded-lg shadow-lg text-center">
              <div
                class="w-8 h-8 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"
              ></div>
              <p class="text-sm text-gray-700">Updating status...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Swal from "sweetalert2";

const groups = ref([]);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref("");
const statusFilter = ref("all");
const notification = ref({
  show: false,
  message: "",
  type: "success",
});

const getCsrfToken = () => {
  const tokenTag = document.head.querySelector('meta[name="csrf-token"]');
  return tokenTag ? tokenTag.content : "";
};

const normalizeStatus = (raw) => {
  if (raw === null || raw === undefined) return "";
  return String(raw).trim();
};

const getStatusLabel = (group) => {
  const status = normalizeStatus(group.status ?? group.STATUS);
  if (status) {
    const s = status.toLowerCase();
    if (
      s === "act" ||
      s === "active" ||
      s === "a" ||
      s === "y" ||
      s === "1" ||
      s === "true"
    )
      return "Active";
    if (
      s === "obs" ||
      s === "obsolete" ||
      s === "inactive" ||
      s === "i" ||
      s === "n" ||
      s === "0" ||
      s === "false"
    )
      return "Obsolete";
  }

  if (group.is_active !== undefined && group.is_active !== null) {
    if (group.is_active === true || group.is_active === 1 || group.is_active === "1")
      return "Active";
    if (group.is_active === false || group.is_active === 0 || group.is_active === "0")
      return "Obsolete";
  }

  const active = normalizeStatus(group.active ?? group.ACTIVE);
  if (active) {
    const a = active.toLowerCase();
    if (a === "y" || a === "a" || a === "1" || a === "true") return "Active";
    if (a === "n" || a === "i" || a === "0" || a === "false") return "Obsolete";
  }

  const ac = normalizeStatus(group.AC ?? group.ac);
  if (ac) {
    const a = ac.toLowerCase();
    if (a === "y" || a === "a") return "Active";
    if (a === "n" || a === "i") return "Obsolete";
  }

  return "Unknown";
};

const filteredGroups = computed(() => {
  let filtered = [...groups.value];

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (g) =>
        (g.group_code && g.group_code.toLowerCase().includes(q)) ||
        (g.description && g.description.toLowerCase().includes(q))
    );
  }

  if (statusFilter.value !== "all") {
    filtered = filtered.filter((g) => {
      const label = getStatusLabel(g);
      if (statusFilter.value === "active") return label === "Active";
      if (statusFilter.value === "obsolete") return label === "Obsolete";
      return true;
    });
  }

  return filtered;
});

const showNotification = (message, type = "success") => {
  notification.value = { show: true, message, type };
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

const fetchGroups = async () => {
  loading.value = true;
  try {
    const response = await fetch("/api/customer-groups", {
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (!response.ok) throw new Error("Failed to fetch customer groups");
    const data = await response.json();
    groups.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error("Error fetching customer groups:", error);
    groups.value = [];
    showNotification("Error loading customer groups: " + error.message, "error");
  } finally {
    loading.value = false;
  }
};

const toggleStatus = async (group) => {
  if (isToggling.value) return;
  if (getStatusLabel(group) === "Unknown") {
    showNotification(
      "Status field for Customer Group is not available in current data source.",
      "error"
    );
    return;
  }

  const confirmMessage = `Are you sure you want to change the status for "${group.group_code} - ${group.description}"?`;
  const confirmRes = await Swal.fire({
    title: "Confirm Status Change?",
    text: confirmMessage,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "OK",
    cancelButtonText: "Cancel",
    reverseButtons: true,
    allowOutsideClick: false,
  });

  if (!confirmRes.isConfirmed) return;

  isToggling.value = true;
  try {
    const csrfToken = getCsrfToken();
    const url = `/api/customer-groups/${encodeURIComponent(group.group_code)}/status`;

    const response = await fetch(url, {
      method: "PUT",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {}),
      },
      body: JSON.stringify({}),
    });

    const result = await response.json().catch(() => ({}));
    if (!response.ok) {
      throw new Error(result.message || "Failed to update customer group status");
    }

    showNotification("Customer group status updated successfully", "success");
    await fetchGroups();
  } catch (error) {
    console.error("Error toggling customer group status:", error);
    showNotification("Error updating status: " + error.message, "error");
  } finally {
    isToggling.value = false;
  }
};

onMounted(() => {
  fetchGroups();
});
</script>
