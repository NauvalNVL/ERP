<template>
  <AppLayout :header="'Reactive / Unobsolete User'">
    <Head title="Reactive / Unobsolete User" />

    <div
      class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 overflow-x-hidden"
    >
      <div class="max-w-7xl mx-auto relative z-0">
        <!-- Floating background bubbles -->
        <div
          class="hidden md:block absolute inset-0 -z-10 overflow-hidden pointer-events-none"
        >
          <div class="bubble bubble-1"></div>
          <div class="bubble bubble-2"></div>
          <div class="bubble bubble-4"></div>
        </div>
        <!-- Header Section -->
        <div
          class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white shadow-lg rounded-2xl border border-indigo-700 mb-6"
        >
          <div
            class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
          >
            <div class="flex items-center gap-3">
              <div
                class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center"
              >
                <i class="fas fa-user-clock text-white text-sm"></i>
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                  Reactive / Unobsolete User
                </h2>
                <p class="text-xs sm:text-sm text-indigo-100">
                  Manage obsolete / inactive users and reactivate access
                </p>
              </div>
            </div>
            <div class="text-xs sm:text-sm text-indigo-100">
              <span class="font-semibold">{{ filteredUsers.length }}</span>
              <span class="ml-1">users found</span>
            </div>
          </div>
        </div>

        <div
          class="bg-white/90 shadow-lg rounded-2xl border border-indigo-100 p-4 sm:p-6 backdrop-blur-sm"
        >
          <!-- Success/Error Messages -->
          <div
            v-if="notification.show"
            :class="{
              'bg-green-50 border border-green-200 text-green-800':
                notification.type === 'success',
              'bg-red-50 border border-red-200 text-red-800':
                notification.type === 'error',
              'px-4 py-3 rounded-lg mb-4 shadow-sm': true,
            }"
          >
            <span class="block text-sm">{{ notification.message }}</span>
          </div>

          <!-- Search and Filter Controls -->
          <div class="mb-4 flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[260px]">
              <div class="relative">
                <span
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"
                >
                  <i class="fas fa-search text-xs"></i>
                </span>
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Search users (ID, username, name, title)..."
                  class="pl-9 pr-3 py-2 w-full border border-gray-300 rounded-md bg-white text-sm text-gray-900 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500"
                />
              </div>
            </div>
            <div>
              <select
                v-model="statusFilter"
                class="py-2 px-3 border border-gray-300 rounded-md bg-white text-sm text-gray-900 focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="all">All Statuses</option>
                <option value="active">Active Only</option>
                <option value="obsolete">Obsolete Only</option>
              </select>
            </div>
          </div>

          <!-- Loading Indicator -->
          <div v-if="loading" class="my-10 flex justify-center">
            <div
              class="w-10 h-10 border-4 border-solid border-indigo-500 border-t-transparent rounded-full animate-spin"
            ></div>
          </div>

          <!-- Users Table -->
          <div v-else class="overflow-x-auto rounded-lg border border-gray-200">
            <table
              class="min-w-full table-auto divide-y divide-gray-200 text-sm bg-white"
            >
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                  >
                    User ID
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                  >
                    Username
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                  >
                    Official Name
                  </th>
                  <th
                    scope="col"
                    class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
                  >
                    Title
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
              <tbody class="bg-white divide-y divide-gray-100">
                <tr
                  v-for="user in paginatedUsers"
                  :key="user.user_id"
                  class="hover:bg-gray-50"
                >
                  <td class="px-4 py-2 whitespace-nowrap text-xs font-mono text-gray-700">
                    {{ user.user_id }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                    {{ user.username || "-" }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                    {{ user.official_name || "-" }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                    {{ user.official_title || "-" }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-center">
                    <span
                      v-if="user.is_active"
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100"
                    >
                      <i class="fas fa-check-circle mr-1"></i>
                      Active
                    </span>
                    <span
                      v-else
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100"
                    >
                      <i class="fas fa-times-circle mr-1"></i>
                      Obsolete
                    </span>
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-center">
                    <button
                      @click="toggleUserStatus(user)"
                      :disabled="isToggling"
                      class="inline-flex items-center justify-center px-3 py-1.5 rounded-md text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-offset-1"
                      :class="[
                        isToggling
                          ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                          : user.is_active
                          ? 'bg-red-50 text-red-700 hover:bg-red-100 focus:ring-red-400'
                          : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 focus:ring-emerald-400',
                      ]"
                      :style="{ minWidth: '140px' }"
                    >
                      <i
                        :class="[
                          'mr-1',
                          user.is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on',
                        ]"
                      ></i>
                      <span>
                        {{ user.is_active ? "Mark Obsolete" : "Mark Active" }}
                      </span>
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedUsers.length === 0">
                  <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                    No users found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination Controls -->
          <div
            v-if="totalPages > 1"
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4"
          >
            <div class="flex items-center gap-3">
              <span class="text-xs text-gray-500">
                Page {{ currentPage }} of {{ totalPages }}
              </span>
            </div>
            <div class="flex items-center justify-end gap-2">
              <button
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                :class="[
                  currentPage === 1
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white text-gray-700 hover:bg-gray-50',
                  'inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-md text-xs font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500',
                ]"
              >
                Previous
              </button>

              <button
                @click="changePage(currentPage + 1)"
                :disabled="currentPage >= totalPages"
                :class="[
                  currentPage >= totalPages
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white text-gray-700 hover:bg-gray-50',
                  'inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-md text-xs font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500',
                ]"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const users = ref([]);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref("");
const statusFilter = ref("all");
const notification = ref({
  show: false,
  message: "",
  type: "success",
});

const currentPage = ref(1);
const perPage = ref(15);

const fetchUsers = async () => {
  loading.value = true;

  try {
    const response = await axios.get("/api/users");
    const data = Array.isArray(response.data) ? response.data : [];

    users.value = data.map((user) => {
      const statusCode = user.status || user.Status || "";
      const isActive =
        user.is_active === true ||
        statusCode === "A" ||
        statusCode === "Active" ||
        statusCode === "ACTIVE";

      return {
        user_id: user.userID || user.user_id || "",
        username: user.userName || user.username || "",
        official_name: user.official_name ?? user.officialName ?? "",
        official_title: user.official_title ?? user.officialTitle ?? "",
        status_code: statusCode,
        is_active: isActive,
      };
    });
  } catch (error) {
    console.error("Error loading users for Reactive/Unobsolete User:", error);
    showNotification(
      "Error loading users: " + (error?.message || "Unknown error"),
      "error"
    );
  } finally {
    loading.value = false;
  }
};

const filteredUsers = computed(() => {
  let list = [...users.value];

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    list = list.filter((user) => {
      return (
        (user.user_id && user.user_id.toLowerCase().includes(q)) ||
        (user.username && user.username.toLowerCase().includes(q)) ||
        (user.official_name && user.official_name.toLowerCase().includes(q)) ||
        (user.official_title && user.official_title.toLowerCase().includes(q))
      );
    });
  }

  if (statusFilter.value !== "all") {
    const isActive = statusFilter.value === "active";
    list = list.filter((user) => user.is_active === isActive);
  }

  return list;
});

const totalPages = computed(() => {
  if (!filteredUsers.value.length) return 1;
  return Math.ceil(filteredUsers.value.length / perPage.value);
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  const end = start + perPage.value;
  return filteredUsers.value.slice(start, end);
});

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  currentPage.value = page;
};

const showNotification = (message, type = "success") => {
  notification.value = {
    show: true,
    message,
    type,
  };

  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

const toggleUserStatus = async (user) => {
  if (!user || !user.user_id || isToggling.value) {
    return;
  }

  const currentStatusLabel = user.is_active ? "Active" : "Obsolete";
  const targetStatusLabel = user.is_active ? "Obsolete" : "Active";

  const label = user.username || user.user_id;
  const confirmMessage = `Are you sure you want to change status for "${label}" from ${currentStatusLabel} to ${targetStatusLabel}?`;
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

  if (!confirmRes.isConfirmed) {
    return;
  }

  isToggling.value = true;

  try {
    const targetStatusCode = user.is_active ? "O" : "A";
    const urlSafeId = encodeURIComponent(user.user_id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const response = await axios.put(
      `/api/users/${urlSafeId}/status`,
      {
        status: targetStatusCode,
      },
      csrfToken
        ? {
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              Accept: "application/json",
            },
          }
        : undefined
    );

    const updatedUser = response?.data?.user;
    const newStatusCode = updatedUser?.status ?? targetStatusCode;
    const isNowActive = newStatusCode === "A" || newStatusCode === "Active";

    user.is_active = isNowActive;
    user.status_code = newStatusCode;

    showNotification(
      `User "${label}" status changed to ${targetStatusLabel}.`,
      "success"
    );
  } catch (error) {
    console.error("Error toggling user status:", error);
    const message = error?.response?.data?.message || error?.message || "Unknown error";
    showNotification("Error updating user status: " + message, "error");
  } finally {
    isToggling.value = false;
  }
};

onMounted(() => {
  fetchUsers();
});
</script>
