<template>
    <AppLayout :header="'Define Machine'">
    <Head title="Define Machine" />

    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                            <i class="fas fa-cogs text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Machine
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Define machines for production processes.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use machine codes to control production resources.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Define Machine</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your machines.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Machine Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-cogs"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type machine code"
                                        >
                                        <button
                                            type="button"
                                            @click="showModal = true"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-600 hover:bg-emerald-700 text-white rounded-r-md text-sm"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                                    <button
                                        type="button"
                                        @click="createNewMachine"
                                        class="w-full flex items-center justify-center px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm"
                                    >
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Add New
                                    </button>
                                </div>
                            </div>

                            <!-- Data Status Information -->
                            <div v-if="loading" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg flex items-center space-x-3 text-sm">
                                <div class="flex items-center">
                                    <div class="mr-3 animate-spin rounded-full h-6 w-6 border-2 border-amber-300 border-t-amber-600"></div>
                                    <p class="font-medium text-amber-800">Loading machine data...</p>
                                </div>
                            </div>
                            <div v-else-if="machines.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No machine data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Data will be automatically loaded when available.</p>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ machines.length }} machines found.</p>
                                <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.machine_code }}</span> - {{ selectedRow.machine_name }} ({{ selectedRow.status }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Machine Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100 mb-2">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Machine Information</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Machine code must be unique and cannot be changed</li>
                                        <li>Use the <span class="font-medium">search</span> button to select a machine</li>
                                        <li>Status determines machine availability</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white shadow-sm rounded-xl border border-violet-100">
                        <div class="px-4 py-3 sm:px-5 border-b border-violet-100 flex items-center">
                            <div class="p-2 bg-violet-500 rounded-lg mr-3">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-slate-800">Quick Links</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="grid grid-cols-1 gap-3">
                                <Link href="/machine/view-print" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                                    <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                        <i class="fas fa-print text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-emerald-900 text-sm">Print List</p>
                                        <p class="text-xs text-emerald-700">Print machine list</p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Modal -->
    <MachineModal
      v-if="showModal"
      :show="showModal"
      :machines="machines"
      @close="showModal = false"
      @select="onMachineSelected"
      @refresh="refreshMachines"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Machine' : 'Edit Machine' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="saveMachineChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Machine Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.machine_code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Machine Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.machine_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Process:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-cogs"></i>
                                </span>
                                <select v-model="editForm.process" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <option value="">Select Process</option>
                                    <option value="10 - CORRUGATING">10 - CORRUGATING</option>
                                    <option value="20 - CONVERTING">20 - CONVERTING</option>
                                    <option value="30 - WAREHOUSE">30 - WAREHOUSE</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Process:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-sitemap"></i>
                                </span>
                                <select v-model="editForm.sub_process" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <option value="">Select Sub-Process</option>
                                    <option value="10 - PRINTER">10 - PRINTER</option>
                                    <option value="20 - DIECUTTER">20 - DIECUTTER</option>
                                    <option value="30 - FINISHER">30 - FINISHER</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <select v-model="editForm.resource_type" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <option value="">Select Resource Type</option>
                                    <option value="I-InHouse">I-InHouse</option>
                                    <option value="E-External">E-External</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Finisher Type:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-tools"></i>
                                </span>
                                <select v-model="editForm.finisher_type" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <option value="">Select Finisher Type</option>
                                    <option value="S-Stitcher">S-Stitcher</option>
                                    <option value="L-Stitcher">L-Stitcher</option>
                                    <option value="G-Gluer">G-Gluer</option>
                                    <option value="A-Assembler">A-Assembler</option>
                                    <option value="P-Packing">P-Packing</option>
                                    <option value="X-N/Applicable">X-N/Applicable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteMachine(editForm.id)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-ban mr-2"></i>Obsolete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-[80] bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
         :class="{
             'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
             'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
         }">
        <div class="p-4 flex items-center">
            <div class="mr-3">
                <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
                <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p :class="{
                    'text-green-800': notification.type === 'success',
                    'text-red-800': notification.type === 'error',
                    'text-yellow-800': notification.type === 'warning'
                }">{{ notification.message }}</p>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MachineModal from '@/Components/MachineModal.vue';

const props = defineProps({
    header: {
        type: String,
        default: 'Machine Management'
    },
    machines: {
        type: Array,
        default: () => []
    }
});

const machines = ref((props.machines || []).filter(item => !item.status || item.status === 'Act'));
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    id: null,
    machine_code: '',
    machine_name: '',
    process: '',
    sub_process: '',
    resource_type: '',
    finisher_type: '',
    status: 'Act'
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch machines from API
const fetchMachines = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/machines', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        const list = data.machines || [];
        machines.value = list.filter(item => !item.status || item.status === 'Act');
    } catch (error) {
        console.error('Error fetching machines:', error);
        showNotification('Failed to load machines data', 'error');
        machines.value = [];
    } finally {
        loading.value = false;
    }
};

const refreshMachines = async () => {
    await fetchMachines();
};

const createNewMachine = () => {
    editForm.value = {
        id: null,
        machine_code: '',
        machine_name: '',
        process: '',
        sub_process: '',
        resource_type: '',
        finisher_type: '',
        status: 'Act'
    };
    isCreating.value = true;
    showEditModal.value = true;
};

const onMachineSelected = (machine) => {
    selectedRow.value = machine;
    editForm.value = { ...machine };
    isCreating.value = false;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    selectedRow.value = null;
    editForm.value = {
        id: null,
        machine_code: '',
        machine_name: '',
        process: '',
        sub_process: '',
        resource_type: '',
        finisher_type: '',
        status: 'Act'
    };
};

const saveMachineChanges = async () => {
    saving.value = true;
    try {
        const url = isCreating.value ? '/machine' : `/machine/${editForm.value.id}`;
        const method = isCreating.value ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(editForm.value),
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            closeEditModal();
            await refreshMachines();
        } else {
            showNotification(data.message || 'Failed to save machine', 'error');
        }
    } catch (error) {
        console.error('Error saving machine:', error);
        showNotification('Error saving machine', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteMachine = async (id) => {
    if (!confirm('Are you sure you want to obsolete this machine?')) {
        return;
    }

    saving.value = true;
    try {
        const response = await fetch(`/machine/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Machine obsoleted successfully', 'success');
            closeEditModal();
            await refreshMachines();
        } else {
            showNotification(data.message || 'Failed to obsolete machine', 'error');
        }
    } catch (error) {
        console.error('Error obsoleting machine:', error);
        showNotification('Error obsoleting machine', 'error');
    } finally {
        saving.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = { show: true, message, type };
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

onMounted(() => {
    if (machines.value.length === 0) {
        fetchMachines();
    }
});
</script>