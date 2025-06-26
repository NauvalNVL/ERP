<template>
    <AppLayout title="View & Print Non-Active Customer">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center mr-4">
                    <i class="fas fa-user-clock text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">View & Print Non-Active Customer</h2>
                    <p class="text-teal-100 max-w-2xl">Generate a report of customers who have been inactive for a specified period.</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-cyan-50">
            <div class="max-w-4xl mx-auto">
                 <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-cyan-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-cyan-50 rounded-full opacity-50"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-teal-50 rounded-full opacity-50"></div>
                    
                    <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                        <div class="p-2 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-lg mr-4 shadow-md">
                            <i class="fas fa-filter text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Report Criteria</h3>
                    </div>

                    <form @submit.prevent="submit" class="relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
                            
                            <!-- Current Sales Period -->
                            <div class="group">
                                <label for="sales_period_month" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-calendar-alt text-xs"></i>
                                    </span>
                                    Current Sales Period
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input id="sales_period_month" type="number" v-model="form.sales_period_month" placeholder="MM" class="w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-shadow focus:shadow-md">
                                    <input id="sales_period_year" type="number" v-model="form.sales_period_year" placeholder="YYYY" class="w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-shadow focus:shadow-md">
                                </div>
                            </div>

                            <!-- Empty div for alignment -->
                            <div></div>

                            <!-- Salesman Code -->
                            <div class="md:col-span-2 group">
                                <label for="salesman_code_from" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-user-tie text-xs"></i>
                                    </span>
                                    Salesman Code
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative flex group flex-1">
                                        <input type="text" v-model="form.salesman_code_from" id="salesman_code_from" class="input-field" placeholder="From">
                                        <button type="button" class="lookup-button from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-500 font-medium">TO</span>
                                    <div class="relative flex group flex-1">
                                        <input type="text" v-model="form.salesman_code_to" class="input-field" placeholder="To">
                                        <button type="button" class="lookup-button from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Code -->
                            <div class="md:col-span-2 group">
                                <label for="customer_code_from" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-user-tag text-xs"></i>
                                    </span>
                                    Customer Code
                                </label>
                                <div class="flex items-center gap-4">
                                     <div class="relative flex group flex-1">
                                        <input type="text" v-model="form.customer_code_from" id="customer_code_from" class="input-field" placeholder="From">
                                        <button type="button" class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-500 font-medium">TO</span>
                                    <div class="relative flex group flex-1">
                                        <input type="text" v-model="form.customer_code_to" class="input-field" placeholder="To">
                                        <button type="button" class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer Status -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-check-circle text-xs"></i>
                                    </span>
                                    Customer Status
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <label class="action-checkbox" :class="{ 'action-checkbox-active': form.status_active }">
                                        <input type="checkbox" v-model="form.status_active" class="hidden">
                                        <i class="fas fa-play-circle w-6 text-xl" :class="form.status_active ? 'text-green-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.status_active ? 'text-green-800' : 'text-gray-700'">A-Active</span>
                                    </label>
                                    <label class="action-checkbox" :class="{ 'action-checkbox-active': form.status_obsolete }">
                                        <input type="checkbox" v-model="form.status_obsolete" class="hidden">
                                        <i class="fas fa-pause-circle w-6 text-xl" :class="form.status_obsolete ? 'text-orange-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.status_obsolete ? 'text-orange-800' : 'text-gray-700'">O-Obsolete</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Customer with no S/O -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-red-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-file-signature text-xs"></i>
                                    </span>
                                    Customer with no S/O
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <label class="action-radio" :class="{ 'action-radio-active': form.with_so === 'yes' }">
                                        <input type="radio" v-model="form.with_so" value="yes" name="with_so" class="hidden">
                                        <i class="fas fa-check w-6 text-xl" :class="form.with_so === 'yes' ? 'text-blue-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.with_so === 'yes' ? 'text-blue-800' : 'text-gray-700'">Y-Yes</span>
                                    </label>
                                    <label class="action-radio" :class="{ 'action-radio-active': form.with_so === 'no' }">
                                        <input type="radio" v-model="form.with_so" value="no" name="with_so" class="hidden">
                                        <i class="fas fa-times w-6 text-xl" :class="form.with_so === 'no' ? 'text-red-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.with_so === 'no' ? 'text-red-800' : 'text-gray-700'">N-No</span>
                                    </label>
                                </div>
                            </div>
                            
                             <!-- Non-Active for -->
                            <div class="md:col-span-2 group">
                                 <label for="non_active_months" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-hourglass-start text-xs"></i>
                                    </span>
                                    Non-Active for
                                 </label>
                                <div class="flex items-center space-x-3">
                                   <input id="non_active_months" type="number" v-model="form.non_active_months" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-200 focus:ring-opacity-50 transition-shadow focus:shadow-md">
                                   <span class="text-gray-600">Months and above without any new orders</span>
                                </div>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-10 pt-6 border-t border-gray-200 space-x-4">
                            <button @click="cancel" type="button" class="secondary-button group">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </button>
                            <button type="submit" class="primary-button group">
                                <span class="shimmer-effect"></span>
                                <i class="fas fa-print mr-2 group-hover:animate-pulse"></i>
                                Print
                            </button>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    sales_period_month: new Date().getMonth() + 1,
    sales_period_year: new Date().getFullYear(),
    salesman_code_from: '',
    salesman_code_to: '',
    customer_code_from: '',
    customer_code_to: '',
    status_active: true,
    status_obsolete: true,
    with_so: 'no',
    non_active_months: 0,
});

const submit = () => {
    // Logic to submit the form for printing
    console.log('Printing report with criteria:', form.data());
    // In a real scenario, this would make a GET request to a print endpoint
    // For example: form.get('/reports/non-active-customer', { ... });
};

const cancel = () => {
    // Logic to cancel and maybe go back to the dashboard or previous page
    window.history.back();
};
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-300 group-hover:border-cyan-400 shadow-sm focus:shadow-md;
}

.lookup-button {
    @apply inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r text-white shadow-sm hover:shadow-md transform hover:-translate-y-px;
}

.action-checkbox, .action-radio {
    @apply flex-1 p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 bg-white flex items-center gap-4 hover:border-gray-400;
}
.action-checkbox-active {
    @apply border-green-400 bg-green-50 shadow-lg scale-105;
}
.action-radio-active {
    @apply border-blue-400 bg-blue-50 shadow-lg scale-105;
}

.primary-button {
    @apply w-full md:w-auto bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center relative overflow-hidden;
}

.secondary-button {
     @apply w-full md:w-auto bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center relative overflow-hidden border border-gray-300;
}

.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}

@keyframes shimmer {
    100% {
        left: 150%;
    }
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 { 
    animation-delay: 0.5s; 
}

</style>