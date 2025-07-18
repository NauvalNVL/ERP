<template>
    <AppLayout header="Define Business Form">
        <Head title="Define Business Form" />

        <div class="container mx-auto px-4 py-6">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
                <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                    <i class="fas fa-file-alt mr-3"></i> Define Business Form
                </h2>
                <p class="text-cyan-100">Create and manage business forms for your organization</p>
            </div>

            <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
                <!-- Notifications -->
                <div v-if="notification.message" :class="notification.type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'" class="border px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ notification.message }}</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Section -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-pen mr-2"></i>
                            {{ isEditing ? 'Edit Business Form' : 'Create New Business Form' }}
                        </h3>

                        <form @submit.prevent="submitForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="bf_code" class="block text-sm font-medium text-gray-700 mb-1">Form Code <span class="text-red-500">*</span></label>
                                    <input id="bf_code" v-model="form.bf_code" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.bf_code}" :disabled="isEditing" required>
                                    <div v-if="errors.bf_code" class="text-red-500 text-xs mt-1">{{ errors.bf_code[0] }}</div>
                                </div>
                                <div>
                                    <label for="bf_name" class="block text-sm font-medium text-gray-700 mb-1">Form Name <span class="text-red-500">*</span></label>
                                    <input id="bf_name" v-model="form.bf_name" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.bf_name}" required>
                                    <div v-if="errors.bf_name" class="text-red-500 text-xs mt-1">{{ errors.bf_name[0] }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="bf_group" class="block text-sm font-medium text-gray-700 mb-1">Form Group <span class="text-red-500">*</span></label>
                                    <input id="bf_group" v-model="form.bf_group" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.bf_group}" required>
                                    <div v-if="errors.bf_group" class="text-red-500 text-xs mt-1">{{ errors.bf_group[0] }}</div>
                                </div>
                                <div>
                                    <label for="bf_iso" class="block text-sm font-medium text-gray-700 mb-1">ISO Reference</label>
                                    <input id="bf_iso" v-model="form.bf_iso" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.bf_iso}">
                                    <div v-if="errors.bf_iso" class="text-red-500 text-xs mt-1">{{ errors.bf_iso[0] }}</div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4 my-4"></div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Approval Information</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="check_by_name" class="block text-sm font-medium text-gray-700 mb-1">Checked By</label>
                                    <input id="check_by_name" v-model="form.check_by_name" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.check_by_name}">
                                    <div v-if="errors.check_by_name" class="text-red-500 text-xs mt-1">{{ errors.check_by_name[0] }}</div>
                                </div>
                                <div>
                                    <label for="check_by_title" class="block text-sm font-medium text-gray-700 mb-1">Checker Title</label>
                                    <input id="check_by_title" v-model="form.check_by_title" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.check_by_title}">
                                    <div v-if="errors.check_by_title" class="text-red-500 text-xs mt-1">{{ errors.check_by_title[0] }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                <div>
                                    <label for="approve_by_name" class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                                    <input id="approve_by_name" v-model="form.approve_by_name" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.approve_by_name}">
                                    <div v-if="errors.approve_by_name" class="text-red-500 text-xs mt-1">{{ errors.approve_by_name[0] }}</div>
                                </div>
                                <div>
                                    <label for="approve_by_title" class="block text-sm font-medium text-gray-700 mb-1">Approver Title</label>
                                    <input id="approve_by_title" v-model="form.approve_by_title" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" :class="{'border-red-500': errors.approve_by_title}">
                                    <div v-if="errors.approve_by_title" class="text-red-500 text-xs mt-1">{{ errors.approve_by_title[0] }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3">
                                <div>
                                    <button type="button" @click="resetForm" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        <i class="fas fa-times mr-2"></i> Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-2">
                                    <button v-if="isEditing" type="button" @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <i class="fas fa-trash mr-2"></i> Delete
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Table Section -->
                    <div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-list mr-2"></i> Business Forms List
                                </h3>

                                <div class="flex items-center space-x-2">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" v-model="search" class="pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Search forms...">
                                    </div>
                                    <Link :href="route('vue.business-form.view-print')" class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 flex items-center text-sm">
                                        <i class="fas fa-print mr-1"></i> View & Print
                                    </Link>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th @click="sortBy('bf_code')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                                Code <i :class="getSortIcon('bf_code')"></i>
                                            </th>
                                            <th @click="sortBy('bf_name')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                                Name <i :class="getSortIcon('bf_name')"></i>
                                            </th>
                                            <th @click="sortBy('bf_group')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                                Group <i :class="getSortIcon('bf_group')"></i>
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-if="loading">
                                            <td colspan="4" class="px-4 py-4 text-center">
                                                <div class="flex justify-center">
                                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-else-if="filteredForms.length === 0">
                                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                                No business forms found.
                                            </td>
                                        </tr>
                                        <tr v-for="(bform, index) in filteredForms" :key="bform.id" :class="{'bg-blue-50': index % 2 === 0}" class="hover:bg-blue-100">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ bform.bf_code }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ bform.bf_name }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ bform.bf_group }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <button @click="editForm(bform)" class="text-blue-600 hover:text-blue-900 mr-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="showFormDetails(bform)" class="text-green-600 hover:text-green-900" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Details Panel -->
                        <div v-if="selectedForm" class="mt-4 bg-white p-4 rounded-lg border border-gray-200 shadow">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-700">Business Form Details</h3>
                                <button @click="selectedForm = null" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Form Code</p>
                                    <p class="text-sm">{{ selectedForm.bf_code }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Form Name</p>
                                    <p class="text-sm">{{ selectedForm.bf_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Form Group</p>
                                    <p class="text-sm">{{ selectedForm.bf_group }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">ISO Reference</p>
                                    <p class="text-sm">{{ selectedForm.bf_iso || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Checked By</p>
                                    <p class="text-sm">{{ selectedForm.check_by_name || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Checker Title</p>
                                    <p class="text-sm">{{ selectedForm.check_by_title || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Approved By</p>
                                    <p class="text-sm">{{ selectedForm.approve_by_name || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Approver Title</p>
                                    <p class="text-sm">{{ selectedForm.approve_by_title || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Created At</p>
                                    <p class="text-sm">{{ formatDate(selectedForm.created_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Updated At</p>
                                    <p class="text-sm">{{ formatDate(selectedForm.updated_at) }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button @click="editForm(selectedForm)" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Deletion</h3>
            <p class="text-gray-700 mb-6">
                Are you sure you want to delete the business form "{{ form.bf_name }}" ({{ form.bf_code }})? This action cannot be undone.
            </p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button @click="deleteForm" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const businessForms = ref([]);
const loading = ref(true);
const errors = ref({});
const isEditing = ref(false);
const selectedForm = ref(null);
const search = ref('');
const sortField = ref('bf_code');
const sortDirection = ref('asc');
const showDeleteModal = ref(false);

const notification = ref({
    message: '',
    type: ''
});

// Initialize form with empty values
const initialFormState = {
    id: null,
    bf_code: '',
    bf_name: '',
    bf_group: '',
    bf_iso: '',
    check_by_name: '',
    check_by_title: '',
    approve_by_name: '',
    approve_by_title: ''
};

const form = ref({ ...initialFormState });

// Set notification
const setNotification = (message, type) => {
    notification.value.message = message;
    notification.value.type = type;
    setTimeout(() => {
        notification.value.message = '';
    }, 5000);
};

// Fetch business forms
const fetchBusinessForms = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/business-forms');
        if (!response.ok) throw new Error('Failed to fetch data');
        const data = await response.json();
        businessForms.value = data;
    } catch (error) {
        console.error('Error fetching business forms:', error);
        setNotification('Failed to load business forms.', 'error');
    } finally {
        loading.value = false;
    }
};

// Sort business forms
const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
};

// Get sort icon
const getSortIcon = (field) => {
    if (sortField.value !== field) return 'fas fa-sort text-gray-300';
    return sortDirection.value === 'asc' ? 'fas fa-sort-up text-blue-500' : 'fas fa-sort-down text-blue-500';
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Filtered and sorted forms
const filteredForms = computed(() => {
    let filtered = [...businessForms.value];
    if (search.value) {
        const query = search.value.toLowerCase();
        filtered = filtered.filter(bform =>
            Object.values(bform).some(val => String(val).toLowerCase().includes(query))
        );
    }
    return filtered.sort((a, b) => {
        let valA = a[sortField.value] || '';
        let valB = b[sortField.value] || '';
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
});

// Reset form
const resetForm = () => {
    form.value = { ...initialFormState };
    isEditing.value = false;
    errors.value = {};
    selectedForm.value = null;
};

// Show form details
const showFormDetails = (bform) => {
    selectedForm.value = { ...bform };
};

// Edit form
const editForm = (bform) => {
    isEditing.value = true;
    form.value = { ...bform };
    selectedForm.value = null;
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
};

// Confirm delete
const confirmDelete = () => {
    showDeleteModal.value = true;
};

// API call helper
const apiCall = async (url, options) => {
    errors.value = {};
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    options.headers = { ...defaultHeaders, ...options.headers };

    try {
        const response = await fetch(url, options);
        const data = await response.json();
        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                errors.value = data.errors;
            }
            throw new Error(data.message || 'An error occurred');
        }
        return data;
    } catch (error) {
        console.error('API Error:', error);
        setNotification(error.message, 'error');
        throw error;
    }
};

// Delete form
const deleteForm = async () => {
    if (!form.value.id) return;
    try {
        await apiCall(`/api/business-forms/${form.value.id}`, { method: 'DELETE' });
        setNotification('Business form deleted successfully.', 'success');
        await fetchBusinessForms();
        resetForm();
    } catch (e) {
        // error handled in apiCall
    } finally {
        showDeleteModal.value = false;
    }
};

// Submit form
const submitForm = async () => {
    const url = isEditing.value ? `/api/business-forms/${form.value.id}` : '/api/business-forms';
    const method = isEditing.value ? 'PUT' : 'POST';

    try {
        const data = await apiCall(url, {
            method,
            body: JSON.stringify(form.value)
        });
        setNotification(data.message, 'success');
        await fetchBusinessForms();
        resetForm();
    } catch (error) {
        // Error is handled in apiCall
    }
};

onMounted(fetchBusinessForms);
</script>
