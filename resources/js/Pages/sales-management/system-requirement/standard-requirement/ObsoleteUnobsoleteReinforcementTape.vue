<template>
    <AppLayout :header="pageHeader">
    <Head :title="pageHeader" />

    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg mb-6">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-sync-alt mr-3"></i>
            Manage Reinforcement Tape Status (Obsolete/Unobsolete)
        </h2>
        <p class="text-emerald-100">Toggle the active status of reinforcement tapes.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6">
        <div v-if="notification.show"
             :class="{
                'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                'px-4 py-3 rounded relative mb-4': true
             }">
            <span class="block sm:inline">{{ notification.message }}</span>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[260px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search reinforcement tapes..."
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process Code</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button
                                @click="toggleReinforcementTapeStatus(tape)"
                                :disabled="isToggling"
                                :class="[
                                    getStatus(tape) === 'Act'
                                        ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                        : 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200',
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

