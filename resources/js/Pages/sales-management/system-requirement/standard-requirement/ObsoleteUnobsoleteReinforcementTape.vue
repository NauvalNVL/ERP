<template>
    <AppLayout :header="pageHeader">
    <Head :title="pageHeader" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                            <i class="fas fa-tape text-white text-sm"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-semibold leading-tight">{{ pageHeader }}</h1>
                            <p class="text-xs sm:text-sm text-green-100">Activate or deactivate reinforcement tapes quickly and easily.</p>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-100">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari reinforcement tape (code, name)..."
                            class="block w-full rounded-md border border-gray-200 bg-white py-1.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                        />
                    </div>
                </div>
            </div>

            <div
                v-if="notification.show"
                :class="[
                    notification.type === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                        : 'bg-red-50 border-red-200 text-red-800',
                    'px-4 py-3 mb-4 rounded-lg border text-sm shadow-sm'
                ]"
            >
                <span class="block">{{ notification.message }}</span>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-4 py-2 sm:px-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800">Reinforcement Tape List</h2>
                        <p class="text-xs text-gray-500">{{ filteredReinforcementTapes.length }} of {{ reinforcementTapes.length }} reinforcement tapes</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-medium text-gray-600">Status:</label>
                        <select
                            v-model="statusFilter"
                            class="py-1.5 px-2.5 text-xs border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        >
                            <option value="all">All</option>
                            <option value="active">Active Only</option>
                            <option value="obsolete">Obsolete Only</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process Code</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 w-40 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="tape in filteredReinforcementTapes"
                                :key="tape.id || tape.code"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ tape.code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ tape.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ tape.dry_end_code || '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span
                                        v-if="getStatus(tape) === 'Act'"
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800"
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
                                        @click="toggleReinforcementTapeStatus(tape)"
                                        :disabled="isToggling"
                                        :class="[
                                            getStatus(tape) === 'Act'
                                                ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                                : 'text-emerald-600 hover:text-emerald-900 bg-emerald-100 hover:bg-emerald-200',
                                            'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center'
                                        ]"
                                        :style="{ minWidth: '120px' }"
                                    >
                                        <i :class="[getStatus(tape) === 'Act' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                        {{ getStatus(tape) === 'Act' ? 'Mark Obsolete' : 'Mark Active' }}
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="filteredReinforcementTapes.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No reinforcement tapes found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    reinforcementTapes: {
        type: Array,
        default: () => []
    },
    header: {
        type: String,
        default: 'Manage Reinforcement Tape Status'
    }
});

const pageHeader = computed(() => props.header || 'Manage Reinforcement Tape Status');

const reinforcementTapes = ref(props.reinforcementTapes || []);
const searchQuery = ref('');
const statusFilter = ref('all');
const isToggling = ref(false);
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const getStatus = (tape) => {
    if (tape.status) {
        return tape.status;
    }
    if (typeof tape.is_active === 'boolean') {
        return tape.is_active ? 'Act' : 'Obs';
    }
    return 'Act';
};

const filteredReinforcementTapes = computed(() => {
    let list = [...reinforcementTapes.value];

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter((tape) => {
            const code = (tape.code || '').toString().toLowerCase();
            const name = (tape.name || '').toString().toLowerCase();
            const dryEnd = (tape.dry_end_code || '').toString().toLowerCase();
            return code.includes(q) || name.includes(q) || dryEnd.includes(q);
        });
    }

    if (statusFilter.value !== 'all') {
        const active = statusFilter.value === 'active';
        list = list.filter((tape) => {
            const status = getStatus(tape);
            return active ? status === 'Act' : status === 'Obs';
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

const toggleReinforcementTapeStatus = async (tape) => {
    if (isToggling.value) {
        return;
    }

    const confirmMessage = `Are you sure you want to change the status for "${tape.code} - ${tape.name}"?`;
    if (!confirm(confirmMessage)) {
        return;
    }

    isToggling.value = true;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const currentStatus = getStatus(tape);
        const newStatus = currentStatus === 'Act' ? 'Obs' : 'Act';

        const response = await fetch(`/api/reinforcement-tapes/${encodeURIComponent(tape.code)}/status`, {
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
            throw new Error(errorData?.message || 'Failed to update reinforcement tape status');
        }

        tape.status = newStatus;
        tape.is_active = newStatus === 'Act';

        const statusText = newStatus === 'Act' ? 'activated' : 'marked obsolete';
        showNotification(`Reinforcement tape "${tape.code}" successfully ${statusText}`, 'success');
    } catch (error) {
        console.error('Error toggling reinforcement tape status:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};
</script>

