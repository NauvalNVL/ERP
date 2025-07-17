<template>
    <AppLayout :header="'Define Delivery Order Format'">
        <!-- Header Section with animated elements, adapted from Update MC -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-truck text-white text-2xl z-10"></i> <!-- Truck icon for Delivery Order -->
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Delivery Order Format</h2>
                    <p class="text-blue-100 max-w-2xl">Configure custom formats for delivery orders.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content (adapted from Update MC) -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Delivery Order Format Management</h3>
                        </div>

                        <!-- Form Content -->
                        <form @submit.prevent="saveFormat" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="format_code" class="block text-sm font-medium text-gray-700 mb-1">Format Code:</label>
                                    <div class="relative flex-1 group">
                                    <input 
                                        type="text" 
                                        id="format_code" 
                                        v-model="form.code" 
                                            @input="debouncedCheckCode"
                                        :readonly="isEditMode" 
                                            :class="{'border-red-500': codeExists && !isEditMode, 'border-green-500': !codeExists && form.code && !isEditMode}"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300 disabled:bg-gray-100"
                                            placeholder="Enter Format Code"
                                    />
                                    <button type="button" @click="openFormatModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                        <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                        <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Single Item: 1-9 Multiple Items: A-Z</p>
                                    <p v-if="codeExists && !isEditMode && form.code" class="mt-1 text-sm text-red-600">Code already exists. Press 'Record: Select' to load.</p>
                                </div>

                                <div class="flex items-end">
                                    <button 
                                        type="button" 
                                        @click="handleRecordAction"
                                        :disabled="!form.code"
                                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white transition-all duration-300 ease-in-out transform active:scale-95"
                                        :class="{
                                            'bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600': !isEditMode,
                                            'bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600': isEditMode,
                                            'opacity-50 cursor-not-allowed': !form.code
                                        }"
                                    >
                                        <i :class="isEditMode ? 'fas fa-pencil-alt' : 'fas fa-plus-circle'" class="mr-2"></i>
                                        <span>Record: {{ isEditMode ? 'Edit' : 'Add' }}</span>
                                    </button>
                                </div>
                            </div>

                            <div v-if="isFormVisible" class="space-y-6 transition-all duration-500 ease-in-out">
                            <div>
                                <label for="format_name" class="block text-sm font-medium text-gray-700 mb-1">Format Name:</label>
                                <input type="text" id="format_name" v-model="form.name" class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Format Type</legend>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center space-x-4">
                                        <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio text-indigo-600 focus:ring-indigo-500" name="format_type" value="S-Single Item" v-model="form.type">
                                            <span class="ml-2 text-gray-700">S-Single Item</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio text-indigo-600 focus:ring-indigo-500" name="format_type" value="M-Multiple Items" v-model="form.type">
                                            <span class="ml-2 text-gray-700">M-Multiple Items</span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <div>
                                <label for="default_printer" class="block text-sm font-medium text-gray-700 mb-1">Default Printer:</label>
                                <input type="text" id="default_printer" v-model="form.printer" class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                <button type="button" @click="newFormat" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                    <i class="fas fa-file"></i>
                                    <span>New</span>
                                </button>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                    <i class="fas fa-save"></i>
                                        <span>Save</span>
                                </button>
                                <button type="button" @click="deleteFormat" v-if="isEditMode" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete</span>
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Quick Info (adapted from Update MC) -->
                <!-- Removed this section as per user request to match provided image. -->
            </div>
        </div>

        <DeliveryOrderFormatModal
            :show="showDeliveryOrderFormatModal"
            :deliveryOrderFormats="allFormats"
            @close="closeFormatModal"
            @format-selected="handleFormatSelected"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, reactive, onMounted, watch, computed } from 'vue';
import DeliveryOrderFormatModal from '@/Components/DeliveryOrderFormatModal.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';

const form = reactive({
    code: '',
    name: '',
    type: 'S-Single Item',
    printer: '',
});

const isEditMode = ref(false);
const showDeliveryOrderFormatModal = ref(false);
const allFormats = ref([]);
const codeExists = ref(false);

const isFormVisible = computed(() => {
    return form.code && (isEditMode.value || !codeExists.value);
});

// Function to check if a code exists in the database
const checkCodeExistence = async () => {
    if (!form.code) {
        codeExists.value = false;
        isEditMode.value = false; // Ensure edit mode is off if code is empty
        return;
    }
    try {
        const response = await axios.get(route('delivery-order-formats.show', form.code));
        codeExists.value = !!response.data; // true if data exists, false otherwise
        if (codeExists.value) {
            isEditMode.value = true; // Automatically go into edit mode if code exists
            Object.assign(form, response.data); // Populate form with existing data
        } else {
            isEditMode.value = false; // Stay in add mode if code does not exist
            form.name = ''; // Clear name, type, printer for new entry
            form.type = 'S-Single Item';
            form.printer = '';
        }
    } catch (error) {
        if (error.response && error.response.status === 404) {
            codeExists.value = false;
            isEditMode.value = false; // Ensure edit mode is off if 404
            form.name = ''; // Clear name, type, printer for new entry
            form.type = 'S-Single Item';
            form.printer = '';
        } else {
            console.error('Error checking code existence:', error);
            // Optionally, handle other errors or keep current state
        }
    }
};

// Debounced version of checkCodeExistence to prevent excessive API calls
const debouncedCheckCode = debounce(checkCodeExistence, 300);

const fetchAllFormats = async () => {
    try {
        const response = await axios.get(route('delivery-order-formats.index'));
        allFormats.value = response.data;
    } catch (error) {
        console.error('Error fetching all delivery order formats:', error);
    }
};

const fetchFormat = async (code) => {
    if (!code) {
        newFormat(); // If no code, treat as new
        return;
    }
    try {
        const response = await axios.get(route('delivery-order-formats.show', code));
        const format = response.data;
        if (format) {
            form.code = format.code;
            form.name = format.name;
            form.type = format.type;
            form.printer = format.printer;
            isEditMode.value = true;
            codeExists.value = true;
        } else {
            newFormat();
            alert('Format not found.');
        }
    } catch (error) {
        console.error('Error fetching format:', error);
        newFormat();
        alert('Error fetching format.');
    }
};

const saveFormat = async () => {
    try {
        if (isEditMode.value) {
            await axios.put(route('delivery-order-formats.update', form.code), form);
            alert('Format updated successfully!');
        } else {
            await axios.post(route('delivery-order-formats.store'), form);
            alert('Format saved successfully!');
        }
        newFormat(); // Clear form after save/update
        fetchAllFormats(); // Refresh list in modal
    } catch (error) {
        console.error('Error saving format:', error.response ? error.response.data : error.message);
        alert('Failed to save format.' + (error.response && error.response.data.message ? ' ' + error.response.data.message : ''));
    }
};

const deleteFormat = async () => {
    if (!confirm('Are you sure you want to delete this format?')) {
        return;
    }
    try {
        await axios.delete(route('delivery-order-formats.destroy', form.code));
        alert('Format deleted successfully!');
        newFormat(); // Clear form after delete
        fetchAllFormats(); // Refresh list in modal
    } catch (error) {
        console.error('Error deleting format:', error.response ? error.response.data : error.message);
        alert('Failed to delete format.' + (error.response && error.response.data.message ? ' ' + error.response.data.message : ''));
    }
};

const newFormat = () => {
    form.code = '';
    form.name = '';
    form.type = 'S-Single Item';
    form.printer = '';
    isEditMode.value = false;
    codeExists.value = false;
};

const openFormatModal = async () => {
    await fetchAllFormats(); // Ensure modal has latest data
    showDeliveryOrderFormatModal.value = true;
};

const closeFormatModal = () => {
    showDeliveryOrderFormatModal.value = false;
};

const handleFormatSelected = (selectedFormat) => {
    if (selectedFormat) {
        form.code = selectedFormat.code;
        fetchFormat(selectedFormat.code);
    }
};

const handleRecordAction = () => {
    if (!form.code) {
        alert('Please enter a Format Code.');
        return;
    }
    if (isEditMode.value) {
        // If in edit mode, and user clicks "Record: Edit", it means they want to re-load/confirm edit.
        // The data is already loaded due to checkCodeExistence, so no explicit fetch needed.
        // This is primarily for visual feedback and consistency with "Record: Add"
        console.log('Currently in edit mode for code:', form.code);
    } else {
        // If in add mode (code does not exist), and user clicks "Record: Add"
        // The form fields will automatically appear due to `isFormVisible` computed property.
        console.log('Ready to add new format with code:', form.code);
    }
};

onMounted(() => {
    fetchAllFormats();
    // No initial checkCodeExistence needed on mount for an empty form.
    // The check will happen when the user types in form.code or selects from modal.
});

watch(() => form.code, (newCode, oldCode) => {
    if (newCode !== oldCode) {
        debouncedCheckCode();
    }
});

// No longer need to watch isEditMode explicitly, as checkCodeExistence handles it.
// watch(isEditMode, (newVal) => {
//     if (newVal === false && form.code) {
//         checkCodeExistence();
//     }
// });
</script>

<style scoped>
/* Add any specific styles for this page here if necessary */
</style> 