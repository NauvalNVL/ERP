<!-- TEST EDIT -->
<!-- 
  Obsolete and Reactive Master Card Component
  This component handles bulk obsoleting and reactivating master cards in the system.
  Final polished version with enhanced UI, colors, responsiveness, and animations.
-->
<template>
    <AppLayout :header="'Obsolete & Reactive MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-sync-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Obsolete & Reactive Master Card</h2>
                    <p class="text-blue-100">Manage master card status in bulk based on specific criteria.</p>
                </div>
            </div>
        </div>

        <div class="rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content (The Form) -->
                <div class="lg:col-span-2 animate-fade-in-up">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        
                        <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-4 shadow-md">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Process Bulk Action</h3>
                        </div>
                            
                        <!-- Form for Bulk Action -->
                        <div class="space-y-6 relative z-10">
                            <!-- AC# -->
                            <div>
                                <label for="ac" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-user-circle text-xs"></i>
                                    </span>
                                    AC#:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="ac" v-model="form.ac" placeholder="Customer Account" class="input-field">
                                    <button type="button" class="lookup-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Product Code -->
                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-box text-xs"></i>
                                    </span>
                                    Product Code:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="product_code" v-model="form.product_code" placeholder="Product Code" class="input-field">
                                    <button type="button" class="lookup-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- MCS# Range -->
                            <div>
                                <label for="mcs_from" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-barcode text-xs"></i>
                                    </span>
                                    MCS# Range:
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_from" v-model="form.mcs_from" placeholder="From Sequence" class="input-field">
                                        <button type="button" class="lookup-button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-500 font-medium">TO</span>
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_to" v-model="form.mcs_to" placeholder="To Sequence" class="input-field">
                                        <button type="button" class="lookup-button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-200 !my-6"></div>

                            <!-- Action To Perform -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-bolt text-xs"></i>
                                    </span>
                                    Action To Perform
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <label class="action-radio" :class="{ 'action-radio-obsolete-active': form.action === 'obsolete' }">
                                        <input type="radio" v-model="form.action" value="obsolete" name="action-radio" class="hidden">
                                        <i class="fas fa-ban w-6 text-xl" :class="form.action === 'obsolete' ? 'text-red-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'obsolete' ? 'text-red-800' : 'text-gray-700'">Obsolete Active</span>
                                    </label>
                                    <label class="action-radio" :class="{ 'action-radio-reactivate-active': form.action === 'reactivate' }">
                                        <input type="radio" v-model="form.action" value="reactivate" name="action-radio" class="hidden">
                                        <i class="fas fa-check-circle w-6 text-xl" :class="form.action === 'reactivate' ? 'text-green-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'reactivate' ? 'text-green-800' : 'text-gray-700'">Reactivate Obsolete</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Reason -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-comment-dots text-xs"></i>
                                    </span>
                                    Provide Reason
                                </label>
                                <textarea id="reason" v-model="form.reason" rows="3" placeholder="A reason must be provided..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm focus:shadow-lg transition-shadow"></textarea>
                            </div>

                            <!-- Process Button -->
                            <div class="pt-6 text-center border-t border-gray-200">
                                <button @click="processSelections" class="process-button group">
                                    <span class="shimmer-effect"></span>
                                    <i class="fas fa-cogs mr-3 text-xl group-hover:animate-spin"></i>
                                    Process Selections
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info Panel -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-teal-500">
                            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                                 <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-4 shadow-md">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Instructions</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-4">
                                <li class="flex items-start">
                                    <i class="fas fa-filter text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Fill in one or more criteria to filter the master cards you want to update.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-bolt text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Choose whether to <span class="font-semibold text-red-600">Obsolete</span> or <span class="font-semibold text-green-600">Reactivate</span>.</span>
                                </li>
                                 <li class="flex items-start">
                                    <i class="fas fa-comment-dots text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>A reason for the bulk action is mandatory.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-cogs text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Click 'Process Selections' to apply the changes. This action is irreversible.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

const { showToast } = useToast();

const form = ref({
    ac: '',
    mcs_from: '',
    mcs_to: '',
    product_code: '',
    action: 'obsolete', // Default action
    reason: ''
});

const processSelections = async () => {
    if (!form.value.reason || form.value.reason.trim() === '') {
        showToast('Reason is required.', 'error');
        const reasonEl = document.getElementById('reason');
        if (reasonEl) {
            reasonEl.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            setTimeout(() => {
                reasonEl.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            }, 2500);
        }
        return;
    }

    const endpoint = form.value.action === 'obsolete' 
        ? '/api/obsolate-reactive-mc/bulk-obsolete' 
        : '/api/obsolate-reactive-mc/bulk-reactive';

    try {
        const response = await axios.post(endpoint, form.value);
        showToast(response.data.message || 'Process completed successfully!', 'success');
        // Reset the form after successful submission
        form.value.ac = '';
        form.value.mcs_from = '';
        form.value.mcs_to = '';
        form.value.product_code = '';
        form.value.reason = '';

    } catch (error) {
        const errorMessage = error.response?.data?.message || 'An error occurred during bulk processing.';
        console.error(`Error during bulk ${form.value.action}:`, error);
        showToast(errorMessage, 'error');
    }
};
</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:border-indigo-400 shadow-sm focus:shadow-md;
}
.lookup-button {
    @apply inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-gray-50 hover:bg-indigo-100 text-indigo-600 rounded-r-md transition-colors duration-300;
}
.action-radio {
    @apply flex-1 p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 bg-white flex items-center gap-4 hover:border-gray-400;
}
.action-radio-obsolete-active {
    @apply border-red-400 bg-red-50 shadow-lg scale-105;
}
.action-radio-reactivate-active {
    @apply border-green-400 bg-green-50 shadow-lg scale-105;
}
.process-button {
    @apply w-full md:w-auto bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold py-3 px-12 rounded-lg shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center mx-auto relative overflow-hidden;
}

.form-radio {
  @apply appearance-none w-5 h-5 border-2 border-gray-300 rounded-full transition-all duration-200;
}
.action-card:hover .form-radio { @apply border-current; }
.form-radio:checked { @apply border-8 border-current; }

/* Animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}
.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}
.animation-delay-300 { animation-delay: 0.3s; }
.animation-delay-500 { animation-delay: 0.5s; }

.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}
@keyframes shimmer {
    100% {
        left: 150%;
    }
}
</style>
