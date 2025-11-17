<template>
    <AppLayout :header="'Reactive / Unobsolete User'">
        <Head title="Reactive / Unobsolete User" />

        <!-- Header Section -->
        <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:via-indigo-600 md:to-purple-600 p-6 rounded-t-lg shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-user-clock mr-3"></i> Reactive / Unobsolete User
            </h2>
            <p class="text-blue-100">View user list and prepare UI for reactivating / un-obsoleting users.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6">
            <!-- Success/Error Messages -->
            <div
                v-if="notification.show"
                :class="{
                    'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                    'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                    'px-4 py-3 rounded relative mb-4': true
                }"
            >
                <span class="block sm:inline">{{ notification.message }}</span>
            </div>

            <!-- Search and Filter Controls -->
            <div class="mb-6 flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search users..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                        >
                    </div>
                </div>
                <div>
                    <select
                        v-model="statusFilter"
                        class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="all">All Statuses</option>
                        <option value="active">Active Only</option>
                        <option value="obsolete">Obsolete Only</option>
                    </select>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div v-if="loading" class="my-8 flex justify-center">
                <div class="w-12 h-12 border-4 border-solid border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            </div>

            <!-- Users Table -->
            <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Official Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="user in paginatedUsers"
                            :key="user.user_id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ user.user_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ user.username || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ user.official_name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ user.official_title || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    v-if="user.is_active"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                >
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                                <span
                                    v-else
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                                >
                                    <i class="fas fa-times-circle mr-1"></i> Obsolete
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <button
                                    @click="toggleUserStatus(user)"
                                    :disabled="isToggling"
                                    class="transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center"
                                    :class="[
                                        isToggling
                                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                            : user.is_active
                                                ? 'bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 focus:ring-red-500'
                                                : 'bg-gradient-to-r from-emerald-500 to-green-600 text-white hover:from-emerald-600 hover:to-green-700 focus:ring-emerald-500'
                                    ]"
                                    :style="{ minWidth: '150px' }"
                                >
                                    <i
                                        :class="[
                                            'mr-1',
                                            user.is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on'
                                        ]"
                                    ></i>
                                    <span>
                                        {{ user.is_active ? 'Mark Obsolete' : 'Mark Active' }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="paginatedUsers.length === 0">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div v-if="totalPages > 1" class="flex items-center justify-between mt-6">
                <div class="flex-1 flex justify-between items-center">
                    <button
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="[
                            currentPage === 1
                                ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                                : 'bg-gradient-to-r from-indigo-500 to-blue-600 text-white hover:from-indigo-600 hover:to-blue-700',
                            'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                        ]"
                    >
                        Previous
                    </button>

                    <span class="text-sm text-gray-700">
                        Page {{ currentPage }} of {{ totalPages }}
                    </span>

                    <button
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage >= totalPages"
                        :class="[
                            currentPage >= totalPages
                                ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                                : 'bg-gradient-to-r from-indigo-500 to-blue-600 text-white hover:from-indigo-600 hover:to-blue-700',
                            'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                        ]"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const notification = ref({
    show: false,
    message: '',
    type: 'success',
});

const currentPage = ref(1);
const perPage = ref(15);

const fetchUsers = async () => {
    loading.value = true;

    try {
        const response = await axios.get('/api/users');
        const data = Array.isArray(response.data) ? response.data : [];

        users.value = data.map((user) => {
            const statusCode = user.status || user.Status || '';
            const isActive =
                user.is_active === true ||
                statusCode === 'A' ||
                statusCode === 'Active' ||
                statusCode === 'ACTIVE';

            return {
                user_id: user.userID || user.user_id || '',
                username: user.userName || user.username || '',
                official_name: user.official_name ?? user.officialName ?? '',
                official_title: user.official_title ?? user.officialTitle ?? '',
                status_code: statusCode,
                is_active: isActive,
            };
        });
    } catch (error) {
        console.error('Error loading users for Reactive/Unobsolete User:', error);
        showNotification('Error loading users: ' + (error?.message || 'Unknown error'), 'error');
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

    if (statusFilter.value !== 'all') {
        const isActive = statusFilter.value === 'active';
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

const showNotification = (message, type = 'success') => {
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

    const currentStatusLabel = user.is_active ? 'Active' : 'Obsolete';
    const targetStatusLabel = user.is_active ? 'Obsolete' : 'Active';

    const label = user.username || user.user_id;
    const confirmMessage = `Are you sure you want to change status for "${label}" from ${currentStatusLabel} to ${targetStatusLabel}?`;
    if (!confirm(confirmMessage)) {
        return;
    }

    isToggling.value = true;

    try {
        const targetStatusCode = user.is_active ? 'O' : 'A';
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
                          'X-CSRF-TOKEN': csrfToken,
                          Accept: 'application/json',
                      },
                  }
                : undefined
        );

        const updatedUser = response?.data?.user;
        const newStatusCode = updatedUser?.status ?? targetStatusCode;
        const isNowActive = newStatusCode === 'A' || newStatusCode === 'Active';

        user.is_active = isNowActive;
        user.status_code = newStatusCode;

        showNotification(`User "${label}" status changed to ${targetStatusLabel}.`, 'success');
    } catch (error) {
        console.error('Error toggling user status:', error);
        const message = error?.response?.data?.message || error?.message || 'Unknown error';
        showNotification('Error updating user status: ' + message, 'error');
    } finally {
        isToggling.value = false;
    }
};

onMounted(() => {
    fetchUsers();
});
</script>
