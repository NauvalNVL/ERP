<template>
    <AppLayout :header="'Salesperson'">
    <Head title="Salesperson Management" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
        <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                <i class="fas fa-user-tie text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Salesperson</h2>
                <p class="text-xs sm:text-sm text-emerald-100">Define salespersons for sales management</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-emerald-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Salesperson Management</h3>
                    </div>


                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewSalesperson" class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading salesperson data...</p>
                        </div>
                    </div>
                    <div v-else-if="salespersons.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No salesperson data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ salespersons.length }} salespersons found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.name }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Salesperson Info Card -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Salesperson Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Salesperson code must be unique</li>
                                <li>Use the <span class="font-medium">search</span> button to select a salesperson</li>
                                <li>Each salesperson must have a team</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Preset Positions</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">E</span>
                                    <span>Executive</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">M</span>
                                    <span>Manager</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">S</span>
                                    <span>Supervisor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/sales-team" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Sales Teams</p>
                                <p class="text-xs text-purple-700">Manage sales teams</p>
                            </div>
                        </Link>

                        <Link href="/sales-person-team" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-user-plus text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Salesperson Team</p>
                                <p class="text-xs text-blue-700">Manage team assignments</p>
                            </div>
                        </Link>

                        <Link href="/sales-person/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print salesperson list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Table -->
    <SalespersonModal
        :show="showModal"
        :salespersons="salespersons"
        @close="showModal = false"
        @select="onSalespersonSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-emerald-100 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-2xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-xl mr-3">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Salesperson' : 'Edit Salesperson' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-emerald-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveSalespersonChanges" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                            <input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required :disabled="!isCreating">
                            <span class="text-xs text-gray-500">Code must be unique</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name:</label>
                            <input v-model="editForm.name" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Group:</label>
                            <div class="relative">
                                <button
                                    type="button"
                                    @click="showGroupDropdown = !showGroupDropdown"
                                    class="w-full flex items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 text-left"
                                >
                                    <span class="truncate">
                                        {{ editForm.grup || 'Select group' }}
                                    </span>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-2"></i>
                                </button>

                                <div
                                    v-if="showGroupDropdown"
                                    class="absolute z-20 mt-1 w-full max-h-48 overflow-auto rounded-md border border-gray-200 bg-white shadow-lg"
                                >
                                    <ul class="py-1 text-sm text-gray-700">
                                        <li
                                            v-for="team in salesTeams"
                                            :key="team.code"
                                            @click="selectGroupOption(team)"
                                            class="px-3 py-2 hover:bg-emerald-50 cursor-pointer flex justify-between"
                                        >
                                            <span>{{ team.code }}-{{ team.name }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code Group:</label>
                            <input v-model="editForm.code_grup" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Sales:</label>
                            <input v-model="editForm.target_sales" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Internal:</label>
                            <input v-model="editForm.internal" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                            <input v-model="editForm.email" type="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <select v-model="editForm.status" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteSalesperson(editForm.id)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                            <i class="fas fa-ban mr-2"></i>Obsolete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
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
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import SalespersonModal from '@/Components/salesperson-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Salesperson Management'
    }
});

const salespersons = ref([]);
const salesTeams = ref([
    { code: '01', name: 'MBI' },
    { code: '02', name: 'MMI' },
    { code: '03', name: 'KIM' },
]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    id: '',
    code: '',
    name: '',
    grup: '',
    code_grup: '',
    target_sales: 0,
    internal: '',
    email: '',
    status: 'Active'
});
const showGroupDropdown = ref(false);
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // If token from meta tag is not available or we want a fresh token, get from the form
    if (csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }

    return token || '';
};

const fetchSalespersons = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/salespersons', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!res.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await res.json();
        console.log('Fetched salespersons data:', data);

        if (Array.isArray(data)) {
            salespersons.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            salespersons.value = data.data;
        } else {
            salespersons.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching salespersons:', e);
        salespersons.value = [];
    } finally {
        loading.value = false;
    }
};

// Static sales team options for Group dropdown in Create/Edit Salesperson modal
// Format: code = team code, name = team name shown in dropdown label
// Example label in dropdown: 01-MBI, 02-MMI, 03-KIM

const fetchSalesTeams = async () => {
    // Static configuration – no remote fetch required
    salesTeams.value = [
        { code: '01', name: 'MBI' },
        { code: '02', name: 'MMI' },
        { code: '03', name: 'KIM' },
    ];
};

const onSalesTeamChange = () => {
    const selectedTeam = salesTeams.value.find(team => team.name === editForm.value.grup);
    if (selectedTeam) {
        editForm.value.code_grup = selectedTeam.code;
    } else {
        editForm.value.code_grup = '';
    }
};

const selectGroupOption = (team) => {
    // Set display value to name only (MBI/MMI/KIM)
    editForm.value.grup = team.name;
    // Set Code Group textbox to code (01/02/03)
    editForm.value.code_grup = team.code;
    // Close dropdown
    showGroupDropdown.value = false;
};



onMounted(async () => {
    await fetchSalespersons();
    await fetchSalesTeams();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && salespersons.value.length > 0) {
        const foundPerson = salespersons.value.find(person =>
            person.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            person.name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundPerson) {
            selectedRow.value = foundPerson;
        }
    }
});


const onSalespersonSelected = (person) => {
    selectedRow.value = person;
    searchQuery.value = person.code;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = {
        ...person
    };
    console.log('Selected person for editing:', editForm.value);
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = {
            ...selectedRow.value
        };
        console.log('Editing person with data:', editForm.value);
        showEditModal.value = true;
    } else {
        showNotification('Please select a salesperson first', 'error');
    }
};

const createNewSalesperson = () => {
    isCreating.value = true;
    editForm.value = {
        id: '',
        code: '',
        name: '',
        grup: '',
        code_grup: '',
        target_sales: 0,
        internal: '',
        email: '',
        status: 'Active'
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        id: '',
        code: '',
        name: '',
        grup: '',
        code_grup: '',
        target_sales: 0,
        internal: '',
        email: '',
        status: 'Active'
    };
    isCreating.value = false;
};

const saveSalespersonChanges = async () => {
    saving.value = true;
    try {
        // Validate form before sending
        if (!editForm.value.code) {
            showNotification('Salesperson code is required', 'error');
            saving.value = false;
            return;
        }
        if (!editForm.value.name) {
            showNotification('Salesperson name is required', 'error');
            saving.value = false;
            return;
        }

        const csrfToken = getCsrfToken();

        // Check if we need to create a custom API endpoint
        let url = '';

        if (isCreating.value) {
            url = '/api/salespersons'; // Create path
        } else {
            // Use the code as identifier, not the ID
            url = `/api/salespersons/update/${editForm.value.code}`;
        }

        console.log('Saving salesperson with data:', editForm.value);
        console.log('Using URL:', url);
        console.log('CSRF Token:', csrfToken ? 'present' : 'missing');

        // Create JSON body for submission (more reliable than FormData)
        const requestBody = {
            code: editForm.value.code,
            name: editForm.value.name,
            grup: editForm.value.grup || '',
            code_grup: editForm.value.code_grup || '',
            target_sales: editForm.value.target_sales || 0,
            internal: editForm.value.internal || '',
            email: editForm.value.email || '',
            status: editForm.value.status || 'Active'
        };

        console.log('Request body:', requestBody);

        const response = await fetch(url, {
            method: 'POST', // Always use POST since our route is defined as POST
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestBody),
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);

        const result = await response.json();
        console.log('Response result:', result);

        if (!response.ok) {
            throw new Error(result.message || `HTTP Error: ${response.status}`);
        }

        if (result.success) {
            // Gunakan data yang dikembalikan API sebagai sumber kebenaran
            const savedPerson = result.data || {
                code: editForm.value.code,
                name: editForm.value.name,
                grup: editForm.value.grup,
                code_grup: editForm.value.code_grup,
                target_sales: editForm.value.target_sales,
                internal: editForm.value.internal,
                email: editForm.value.email,
                status: editForm.value.status,
            };

            // Update array lokal supaya modal langsung merefleksikan perubahan
            const idx = salespersons.value.findIndex(p => p.code === savedPerson.code);

            if (isCreating.value) {
                if (idx === -1) {
                    salespersons.value.push(savedPerson);
                } else {
                    salespersons.value[idx] = savedPerson;
                }
                showNotification('Salesperson created successfully', 'success');
            } else {
                if (idx !== -1) {
                    salespersons.value[idx] = savedPerson;
                }
                showNotification('Salesperson updated successfully', 'success');
            }

            // Refetch from server to ensure sync with database
            console.log('Refetching salespersons from server...');
            await fetchSalespersons();
            console.log('Refetch complete. Total salespersons:', salespersons.value.length);
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving salesperson changes:', e);
        showNotification('Error saving salesperson: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteSalesperson = async (id) => {
    if (!confirm(`Are you sure you want to obsolete this salesperson?`)) {
        return;
    }

    saving.value = true;
    try {
        const csrfToken = getCsrfToken();

        // Use the code as identifier, not the ID
        const code = editForm.value.code;
        console.log('Obsoleting salesperson with code:', code);

        // Create form data - no need for method spoofing now
        const formData = new FormData();

        const response = await fetch(`/api/salespersons/delete/${code}`, {
            method: 'POST', // Use POST since our route is defined as POST
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error obsoleting salesperson');
        }

        const result = await response.json();

        if (result.success) {
            // Remove the item from the local array
            salespersons.value = salespersons.value.filter(person => person.code !== code);

            if (selectedRow.value && selectedRow.value.code === code) {
                selectedRow.value = null;
                searchQuery.value = '';
            }

            closeEditModal();
            showNotification('Salesperson obsoleted successfully', 'success');
        } else {
            showNotification('Error obsoleting salesperson: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error obsoleting salesperson:', e);
        showNotification('Error obsoleting salesperson: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};


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
</script>
