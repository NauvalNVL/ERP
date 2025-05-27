<template>
    <AppLayout :header="'Industry'">
    <Head title="Industry Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-industry mr-3"></i> Define Industry
        </h2>
        <p class="text-cyan-100">Define industries for customer and product categorization</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-cogs text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Industry Management</h3>
                    </div>
                    <!-- Header with navigation buttons -->
                    <div class="flex items-center space-x-2 mb-6">
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="showModal = true">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="editSelectedIndustry">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="createNewIndustry">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Industry Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-industry"></i>
                                </span>
                                <input type="text" v-model="industryCode" @input="searchIndustryCode" @keyup.enter="handleSearchAndCreate" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter industry code">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                            <button type="button" @click="createNewIndustry" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="searchResult" class="mt-4" v-html="searchResult"></div>
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading industry data...</p>
                        </div>
                    </div>
                    <div v-else-if="industries.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No industry data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchIndustries" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ industries.length }} industries found.</p>
                        <p v-if="selectedIndustry" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedIndustry.code }}</span> - {{ selectedIndustry.name }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Industry Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Industry Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Industry code must be unique and maximum 4 characters</li>
                                <li>Use the <span class="font-medium">Add New</span> button to add a new industry</li>
                                <li>Industry name should be descriptive and specific</li>
                                <li>Industries are used for customer categorization</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Industries</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">F</span>
                                    <span>Food</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">E</span>
                                    <span>Electronics</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">P</span>
                                    <span>Pharmaceuticals</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">A</span>
                                    <span>Automotive</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">R</span>
                                    <span>Retail</span>
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
                        <Link href="/color" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-palette text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Colors</p>
                                <p class="text-xs text-purple-700">Manage colors</p>
                            </div>
                        </Link>

                        <Link href="/geo" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-globe-americas text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Geo</p>
                                <p class="text-xs text-blue-700">Manage geographical data</p>
                            </div>
                        </Link>

                        <Link href="/vue/industry/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print industry list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <IndustryModal
        :show="showModal"
        :industries="industries"
        @close="showModal = false"
        @select="onIndustrySelected"
    />

    <!-- Edit/Create Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Add Industry' : 'Edit Industry' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveIndustryChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Industry Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.code" type="text" maxlength="4" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Industry Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.name" type="text" maxlength="100" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                            <div class="border border-gray-300 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-sm">
                                <p>Industry code maximum 4 characters. Industry name maximum 100 characters.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteIndustry" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
import { ref, onMounted } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import IndustryModal from '@/Components/industry-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Industry Management'
    },
    industries: {
        type: Array,
        default: () => []
    }
});

const industries = ref(props.industries || []);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedIndustry = ref(null);
const industryCode = ref('');
const searchResult = ref('');
const editForm = ref({ 
    code: '', 
    name: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch industries from API
const fetchIndustries = async () => {
    loading.value = true;
    try {
        const res = await fetch('/industry', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        
        if (Array.isArray(data)) {
            industries.value = data;
        } else {
            industries.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching industries:', e);
        industries.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    if (industries.value.length === 0) {
        fetchIndustries();
    }
});

// Search for industry code
const searchIndustryCode = () => {
    if (!industryCode.value) {
        searchResult.value = '';
        return;
    }
    
    const matches = industries.value.filter(industry => 
        industry.code.toLowerCase().includes(industryCode.value.toLowerCase()) ||
        industry.name.toLowerCase().includes(industryCode.value.toLowerCase())
    );
    
    if (matches.length > 0) {
        if (matches.length === 1 && matches[0].code.toLowerCase() === industryCode.value.toLowerCase()) {
            // Exact match - show details and edit button
            searchResult.value = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Data found: <span class="font-semibold">${matches[0].code}</span> - ${matches[0].name}</p>
                        </div>
                    </div>
                </div>
            `;
            selectedIndustry.value = matches[0];
        } else if (matches.length <= 5) {
            // Multiple matches (up to 5) - show list
            let html = `<div class="p-3 bg-blue-100 rounded-lg mt-2">
                <p class="text-sm text-blue-800 mb-2">Multiple codes found:</p>
                <ul class="space-y-1">`;
            
            matches.forEach(match => {
                html += `<li class="flex justify-between items-center">
                    <button class="text-sm text-blue-700 hover:text-blue-900 hover:underline focus:outline-none">
                        ${match.code} - ${match.name}
                    </button>
                </li>`;
            });
            
            html += `</ul></div>`;
            searchResult.value = html;
        } else {
            // Too many matches
            searchResult.value = `
                <div class="p-3 bg-yellow-100 rounded-lg mt-2">
                    <p class="text-sm text-yellow-800">Found ${matches.length} matching codes. Please be more specific or use the search button.</p>
                </div>
            `;
        }
    } else {
        // No matches - show create option
        searchResult.value = `
            <div class="p-3 bg-red-100 rounded-lg mt-2">
                <div class="flex justify-between items-start">
                    <p class="text-sm text-red-800">No industry code "${industryCode.value}" found. Check the code or create new data.</p>
                </div>
            </div>
        `;
    }
};

// Handle search and create functionality when pressing Enter on industry code input
const handleSearchAndCreate = async () => {
    if (!industryCode.value) return;
    
    saving.value = true;
    try {
        const res = await fetch(`/industry/search/${industryCode.value.toUpperCase()}`, {
            headers: { 
                'Accept': 'application/json'
            }
        });
        
        const data = await res.json();
        
        if (data.exists) {
            // Industry exists, find it and select it
            const industry = industries.value.find(
                i => i.code.toUpperCase() === industryCode.value.toUpperCase()
            );
            
            if (industry) {
                selectedIndustry.value = industry;
                searchResult.value = `
                    <div class="p-3 bg-green-100 rounded-lg mt-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-green-800">Data found: <span class="font-semibold">${industry.code}</span> - ${industry.name}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        } else {
            // Industry doesn't exist, show create form
            isCreating.value = true;
            editForm.value = {
                code: industryCode.value.toUpperCase(),
                name: ''
            };
            showEditModal.value = true;
        }
    } catch (e) {
        console.error('Error searching industry:', e);
        showNotification('Error searching industry. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const onIndustrySelected = (industry) => {
    selectedIndustry.value = industry;
    industryCode.value = industry.code;
    showModal.value = false;
    
    // Update search result
    searchResult.value = `
        <div class="p-3 bg-green-100 rounded-lg mt-2">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-green-800">Data found: <span class="font-semibold">${industry.code}</span> - ${industry.name}</p>
                </div>
            </div>
        </div>
    `;
};

const editSelectedIndustry = () => {
    if (selectedIndustry.value) {
        isCreating.value = false;
        editForm.value = { ...selectedIndustry.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select an industry first', 'error');
    }
};

const createNewIndustry = () => {
    isCreating.value = true;
    editForm.value = { 
        code: industryCode.value.toUpperCase() || '', 
        name: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    isCreating.value = false;
};

const saveIndustryChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        // Different API call for create vs update
        let url = isCreating.value ? '/industry' : `/industry/${selectedIndustry.value ? selectedIndustry.value.id : ''}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        const formData = new FormData();
        formData.append('code', editForm.value.code.toUpperCase());
        formData.append('name', editForm.value.name.toUpperCase());
        
        if (!isCreating.value) {
            formData.append('_method', 'PUT');
        }
        
        const response = await fetch(url, {
            method: isCreating.value ? 'POST' : 'POST', // Always POST with FormData for method spoofing
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Error saving industry');
        }
        
        // Refresh the industry list
        await fetchIndustries();
        
        // Update the industry code field with the saved code
        industryCode.value = editForm.value.code.toUpperCase();
        
        // Find the saved industry in the refreshed list
        const savedIndustry = industries.value.find(
            i => i.code.toUpperCase() === editForm.value.code.toUpperCase()
        );
        
        if (savedIndustry) {
            selectedIndustry.value = savedIndustry;
            searchResult.value = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Industry ${isCreating.value ? 'created' : 'updated'}: <span class="font-semibold">${savedIndustry.code}</span> - ${savedIndustry.name}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        showNotification(`Industry ${isCreating.value ? 'created' : 'updated'} successfully`, 'success');
        closeEditModal();
    } catch (e) {
        console.error('Error saving industry:', e);
        showNotification(e.message || 'Error saving industry. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteIndustry = async () => {
    if (!selectedIndustry.value) {
        showNotification('No industry selected', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete industry "${selectedIndustry.value.code}"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        
        const response = await fetch(`/industry/${selectedIndustry.value.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Error deleting industry');
        }
        
        // Refresh the industry list
        await fetchIndustries();
        
        // Clear selection and search
        selectedIndustry.value = null;
        industryCode.value = '';
        searchResult.value = '';
        
        showNotification('Industry deleted successfully', 'success');
        closeEditModal();
    } catch (e) {
        console.error('Error deleting industry:', e);
        showNotification(e.message || 'Error deleting industry. Please try again.', 'error');
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
