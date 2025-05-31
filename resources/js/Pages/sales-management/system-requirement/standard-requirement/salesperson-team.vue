<template>
    <AppLayout :header="'Salesperson Team'">
    <Head title="Salesperson Team Management" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-users mr-3"></i> Define Salesperson Team
        </h2>
        <p class="text-cyan-100">Define sales teams and assign members to each team</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Salesperson Team Management</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Team:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-users"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewSalespersonTeam" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
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
                            <p class="text-sm font-medium text-yellow-800">Loading salesperson team data...</p>
                        </div>
                    </div>
                    <div v-else-if="salespersonTeams.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No salesperson team data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="loadSeedData" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Run Salesperson Team Seeder</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ salespersonTeams.length }} salesperson teams found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.s_person_code }}</span> - {{ selectedRow.salesperson_name }} ({{ selectedRow.sales_team_name }})
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Team Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Info Team</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Each salesperson must have a team</li>
                                <li>Use the <span class="font-medium">search</span> button to find</li>
                                <li>Position determines the level in team</li>
                                <li>Team code must be unique and can't be changed</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Team Positions</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">E</span>
                                    <span>Executive</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Preset Teams</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">01</span>
                                    <span>MBI</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">02</span>
                                    <span>MANAGEMENT LOCAL</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">03</span>
                                    <span>MANAGEMENT MNC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/vue/sales-team" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Teams</p>
                                <p class="text-xs text-purple-700">Manage sales teams</p>
                            </div>
                        </Link>

                        <Link href="/vue/salesperson" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-user-tie text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Salespersons</p>
                                <p class="text-xs text-blue-700">Manage salespersons</p>
                            </div>
                        </Link>

                        <Link href="/salesperson-team/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print team list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <SalespersonTeamModal
        :show="showModal"
        :salesperson-teams="salespersonTeams"
        @close="showModal = false"
        @select="onSalespersonTeamSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Salesperson Team' : 'Edit Salesperson Team' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveSalespersonTeamChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                            <input v-model="editForm.s_person_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name:</label>
                            <input v-model="editForm.salesperson_name" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sales Team:</label>
                            <select v-model="editForm.sales_team_id" class="block w-full rounded-md border-gray-300 shadow-sm" required @change="updateTeamDetails">
                                <option value="1">01 - MBI</option>
                                <option value="2">02 - MANAGEMENT LOCAL</option>
                                <option value="3">03 - MANAGEMENT MNC</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position:</label>
                            <select v-model="editForm.sales_team_position" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="E-Executive">E-Executive</option>
                                <option value="M-Manager">M-Manager</option>
                                <option value="S-Supervisor">S-Supervisor</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteSalespersonTeam(editForm.s_person_code)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
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
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import SalespersonTeamModal from '@/Components/salesperson-team-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Salesperson Team Management'
    }
});

const salespersonTeams = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    id: '',
    s_person_code: '',
    salesperson_name: '',
    sales_team_id: '1',
    st_code: '01',
    sales_team_name: 'MBI',
    sales_team_position: 'E-Executive'
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Teams mapping
const salesTeams = [
    { id: 1, code: '01', name: 'MBI' },
    { id: 2, code: '02', name: 'MANAGEMENT LOCAL' },
    { id: 3, code: '03', name: 'MANAGEMENT MNC' }
];

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

const fetchSalespersonTeams = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/salesperson-teams', { 
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
        
        if (Array.isArray(data)) {
            salespersonTeams.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            salespersonTeams.value = data.data;
        } else {
            salespersonTeams.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching salesperson teams:', e);
        salespersonTeams.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchSalespersonTeams();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && salespersonTeams.value.length > 0) {
        const foundTeam = salespersonTeams.value.find(team => 
            team.s_person_code.toLowerCase().includes(newQuery.toLowerCase()) ||
            team.salesperson_name.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundTeam) {
            selectedRow.value = foundTeam;
        }
    }
});

const onSalespersonTeamSelected = (team) => {
    selectedRow.value = team;
    searchQuery.value = team.s_person_code;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...team };
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a salesperson team first', 'error');
    }
};

const createNewSalespersonTeam = () => {
    isCreating.value = true;
    editForm.value = {
        id: '',
        s_person_code: '',
        salesperson_name: '',
        sales_team_id: '1',
        st_code: '01',
        sales_team_name: 'MBI',
        sales_team_position: 'E-Executive'
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        id: '',
        s_person_code: '',
        salesperson_name: '',
        sales_team_id: '1',
        st_code: '01',
        sales_team_name: 'MBI',
        sales_team_position: 'E-Executive'
    };
    isCreating.value = false;
};

// Update team details when sales_team_id changes
const updateTeamDetails = () => {
    const team = salesTeams.find(t => t.id.toString() === editForm.value.sales_team_id.toString());
    if (team) {
        editForm.value.st_code = team.code;
        editForm.value.sales_team_name = team.name;
    }
};

const saveSalespersonTeamChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/salesperson-teams' : `/api/salesperson-teams/${editForm.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        console.log('Saving salesperson team with data:', editForm.value);
        
        // Prepare data for API
        const formData = {
            s_person_code: editForm.value.s_person_code,
            salesperson_name: editForm.value.salesperson_name,
            st_code: editForm.value.st_code,
            sales_team_name: editForm.value.sales_team_name,
            sales_team_position: editForm.value.sales_team_position
        };
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData),
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error saving salesperson team');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Salesperson team created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    selectedRow.value.salesperson_name = editForm.value.salesperson_name;
                    selectedRow.value.st_code = editForm.value.st_code;
                    selectedRow.value.sales_team_name = editForm.value.sales_team_name;
                    selectedRow.value.sales_team_position = editForm.value.sales_team_position;
                }
                showNotification('Salesperson team updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchSalespersonTeams();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving salesperson team changes:', e);
        showNotification('Error saving salesperson team: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteSalespersonTeam = async (code) => {
    if (!confirm(`Are you sure you want to delete salesperson team member "${code}"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        // Get a fresh CSRF token
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            console.error('No CSRF token found - cannot proceed with deletion');
            showNotification('Security token missing. Please refresh the page and try again.', 'error');
            saving.value = false;
            return;
        }
        
        console.log('Deleting salesperson team with ID:', editForm.value.id);
        const deleteUrl = `/api/salesperson-teams/${editForm.value.id}`;
        console.log('Delete URL:', deleteUrl);
        
        const response = await fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        console.log('Delete response status:', response.status);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ message: 'Error parsing error response' }));
            console.error('Error response data:', errorData);
            throw new Error(errorData.message || `Error deleting salesperson team (Status: ${response.status})`);
        }
        
        const result = await response.json();
        console.log('Delete operation result:', result);
        
        if (result.success) {
            // Remove the item from the local array
            salespersonTeams.value = salespersonTeams.value.filter(team => team.id !== editForm.value.id);
            
            if (selectedRow.value && selectedRow.value.id === editForm.value.id) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification('Salesperson team member deleted successfully', 'success');
        } else {
            showNotification('Error deleting salesperson team member: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting salesperson team member:', e);
        showNotification('Error deleting salesperson team member: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const loadSeedData = async () => {
    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        
        const response = await fetch('/api/salesperson-teams/seed', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error seeding data');
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Salesperson team data seeded successfully', 'success');
            await fetchSalespersonTeams();
        } else {
            showNotification('Error seeding data: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error seeding data:', e);
        showNotification('Error seeding data: ' + e.message, 'error');
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
