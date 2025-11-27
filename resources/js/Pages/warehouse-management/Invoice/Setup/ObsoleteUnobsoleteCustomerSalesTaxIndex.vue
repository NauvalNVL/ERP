<template>
    <AppLayout :header="pageHeader">
        <Head :title="pageHeader" />

        <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-sync-alt mr-3"></i>
                Manage Customer Sales Tax Index Status (Obsolete/Unobsolete)
            </h2>
            <p class="text-emerald-100">Toggle the active status of customer sales tax indices.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6">
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

            <div class="mb-6 flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[260px]">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search customer, index, or tax group..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50"
                        >
                    </div>
                </div>
                <div>
                    <select
                        v-model="statusFilter"
                        class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                    >
                        <option value="all">All Statuses</option>
                        <option value="active">Active Only</option>
                        <option value="obsolete">Obsolete Only</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Index#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Group</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Group Name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 w-40 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="item in filteredIndices"
                            :key="`${item.customer_code}-${item.index_number}`"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ item.customer_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ formatIndex(item.index_number) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ item.tax_group_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.tax_group?.name || '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    v-if="getStatus(item) === 'A'"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                >
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                                <span
                                    v-else
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                                >
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Obsolete
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center w-40">
                                <button
                                    @click="toggleIndexStatus(item)"
                                    :disabled="isToggling"
                                    :class="[
                                        getStatus(item) === 'A'
                                            ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                            : 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200',
                                        'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center'
                                    ]"
                                    :style="{ minWidth: '120px' }"
                                >
                                    <i :class="[getStatus(item) === 'A' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                    {{ getStatus(item) === 'A' ? 'Mark Obsolete' : 'Mark Active' }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredIndices.length === 0">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No customer tax indices found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="isToggling"
                class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center"
            >
                <div class="bg-white p-4 rounded-lg shadow-lg text-center">
                    <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                    <p>Updating status...</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    indices: {
        type: Array,
        default: () => []
    },
    header: {
        type: String,
        default: 'Manage Customer Sales Tax Index Status'
    }
});

const pageHeader = computed(() => props.header || 'Manage Customer Sales Tax Index Status');

const indices = ref(props.indices || []);
const searchQuery = ref('');
const statusFilter = ref('all');
const isToggling = ref(false);
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const getStatus = (item) => {
    return item.status || 'A';
};

const formatIndex = (val) => {
    if (val === null || val === undefined) return '';
    return String(val).padStart(2, '0');
};

const filteredIndices = computed(() => {
    let list = [...indices.value];

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter((item) => {
            const customer = (item.customer_code || '').toString().toLowerCase();
            const indexNo = formatIndex(item.index_number).toLowerCase();
            const groupCode = (item.tax_group_code || '').toString().toLowerCase();
            const groupName = (item.tax_group?.name || '').toString().toLowerCase();
            return (
                customer.includes(q) ||
                indexNo.includes(q) ||
                groupCode.includes(q) ||
                groupName.includes(q)
            );
        });
    }

    if (statusFilter.value !== 'all') {
        const active = statusFilter.value === 'active';
        list = list.filter((item) => {
            const status = getStatus(item);
            return active ? status === 'A' : status === 'O';
        });
    }

    return list;
});

const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };

    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

const toggleIndexStatus = async (item) => {
    if (isToggling.value) {
        return;
    }

    const label = `${item.customer_code || ''} / Index ${formatIndex(item.index_number)} (${item.tax_group_code || ''})`;
    const confirmMessage = `Are you sure you want to change the status for "${label}"?`;
    if (!confirm(confirmMessage)) {
        return;
    }

    isToggling.value = true;

    try {
        const tokenTag = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenTag ? tokenTag.getAttribute('content') : null;
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const currentStatus = getStatus(item);
        const newStatus = currentStatus === 'A' ? 'O' : 'A';

        const url = `/api/invoices/customer-tax-indices/${encodeURIComponent(item.customer_code)}/${encodeURIComponent(item.index_number)}/status`;

        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => null);
            throw new Error(errorData?.message || 'Failed to update customer tax index status');
        }

        item.status = newStatus;

        const statusText = newStatus === 'A' ? 'activated' : 'marked obsolete';
        showNotification(`Customer tax index "${label}" successfully ${statusText}`, 'success');
    } catch (error) {
        console.error('Error toggling customer tax index status:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};
</script>

