<template>
    <AppLayout :header="pageHeader">
        <Head :title="pageHeader" />

        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
                    <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-sync-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Manage Tax Type Status
                            </h2>
                            <p class="text-xs sm:text-sm text-blue-100">
                                Toggle active/obsolete status of tax types.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6">
                    <div
                        v-if="notification.show"
                        :class="{
                            'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                            'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                            'px-4 py-3 rounded-lg relative mb-4': true
                        }"
                    >
                        <span class="block sm:inline">{{ notification.message }}</span>
                    </div>

                    <div class="mb-4 flex flex-wrap items-center gap-3">
                        <div class="flex-1 min-w-[260px]">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-search text-sm"></i>
                                </span>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search tax types..."
                                    class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                >
                            </div>
                        </div>
                        <div>
                            <select
                                v-model="statusFilter"
                                class="py-2 px-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                                <option value="all">All Statuses</option>
                                <option value="active">Active Only</option>
                                <option value="obsolete">Obsolete Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Tax Code</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Tax Name</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Apply</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Rate (%)</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Custom Type</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-3 w-40 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr
                                    v-for="item in filteredTaxTypes"
                                    :key="item.code"
                                    class="hover:bg-blue-50 transition-colors"
                                >
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-slate-900">{{ item.code }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">{{ item.name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">{{ item.apply }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">{{ Number(item.rate ?? 0).toFixed(2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">{{ item.custom_type || 'N-NIL' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                        <span
                                            v-if="getStatus(item) === 'A'"
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800"
                                        >
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Active
                                        </span>
                                        <span
                                            v-else
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-red-100 text-red-800"
                                        >
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Obsolete
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-center w-40">
                                        <button
                                            @click="toggleTaxTypeStatus(item)"
                                            :disabled="isToggling"
                                            :class="[
                                                getStatus(item) === 'A'
                                                    ? 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
                                                    : 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100',
                                                'transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 px-3 py-1.5 rounded-lg text-xs font-medium flex items-center justify-center border',
                                                getStatus(item) === 'A' ? 'border-red-200' : 'border-green-200'
                                            ]"
                                            :style="{ minWidth: '110px' }"
                                        >
                                            <i :class="[getStatus(item) === 'A' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1.5']"></i>
                                            {{ getStatus(item) === 'A' ? 'Obsolete' : 'Activate' }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredTaxTypes.length === 0">
                                    <td colspan="7" class="px-4 py-8 text-center text-slate-500 text-sm">No tax types found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="isToggling"
                        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center"
                    >
                        <div class="bg-white p-6 rounded-xl shadow-xl text-center">
                            <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-sm text-slate-700">Updating status...</p>
                        </div>
                    </div>
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
    taxTypes: {
        type: Array,
        default: () => []
    },
    header: {
        type: String,
        default: 'Manage Tax Type Status'
    }
});

const pageHeader = computed(() => props.header || 'Manage Tax Type Status');

const taxTypes = ref(props.taxTypes || []);
const searchQuery = ref('');
const statusFilter = ref('all');
const isToggling = ref(false);
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const getStatus = (item) => {
    if (item.status) {
        return item.status;
    }
    return 'A';
};

const filteredTaxTypes = computed(() => {
    let list = [...taxTypes.value];

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter((item) => {
            const code = (item.code || '').toString().toLowerCase();
            const name = (item.name || '').toString().toLowerCase();
            const custom = (item.custom_type || '').toString().toLowerCase();
            return code.includes(q) || name.includes(q) || custom.includes(q);
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

const toggleTaxTypeStatus = async (item) => {
    if (isToggling.value) {
        return;
    }

    const confirmMessage = `Are you sure you want to change the status for "${item.code} - ${item.name}"?`;
    if (!confirm(confirmMessage)) {
        return;
    }

    isToggling.value = true;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const currentStatus = getStatus(item);
        const newStatus = currentStatus === 'A' ? 'O' : 'A';

        const response = await fetch(`/api/invoices/tax-types/${encodeURIComponent(item.code)}/status`, {
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
            throw new Error(errorData?.message || 'Failed to update tax type status');
        }

        item.status = newStatus;

        const statusText = newStatus === 'A' ? 'activated' : 'marked obsolete';
        showNotification(`Tax type "${item.code}" successfully ${statusText}`, 'success');
    } catch (error) {
        console.error('Error toggling tax type status:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};
</script>

