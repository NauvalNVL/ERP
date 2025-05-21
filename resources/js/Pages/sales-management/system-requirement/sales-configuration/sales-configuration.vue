<template>
    <Head :title="title" />

    <div class="min-h-screen bg-gray-100 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Define Sales Configuration</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your sales system configuration settings</p>
            </div>

            <form @submit.prevent="submitForm">
                <div class="space-y-6">
                    <!-- MC Card Regulation & Workflow -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                                MC Card Regulation & Workflow
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-700">Printable Paper Quality</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label v-for="option in paperQualityOptions" :key="option.value" 
                                           class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" v-model="form.paper_quality" :value="option.value" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-900">{{ option.label }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activate Link to Salesperson -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-link mr-2 text-green-500"></i>
                                Activate Link to Salesperson
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <div v-for="(option, index) in salespersonLinks" :key="index"
                                     class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ option.label }}</span>
                                    <div class="flex space-x-4">
                                        <label v-for="choice in yesNoOptions" :key="choice.value" class="inline-flex items-center">
                                            <input type="radio" v-model="form[option.name]" :value="choice.value" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ choice.label }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Master Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-id-card mr-2 text-purple-500"></i>
                                Master Card
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6 space-y-6">
                            <!-- Flute Pattern & Scoring Tool -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Flute Pattern</label>
                                    <div class="flex space-x-2">
                                        <input v-for="i in 5" :key="i" type="text" v-model="form.flute_pattern[i-1]"
                                               class="w-16 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                               maxlength="2">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Scoring Tool</label>
                                    <div class="flex space-x-2">
                                        <input type="number" v-model="form.scoring_tool" 
                                               class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                               min="0">
                                        <button type="button" @click="searchScoringTool"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Radio Button Groups -->
                            <div class="space-y-6">
                                <div v-for="(option, index) in masterCardOptions" :key="index" class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">{{ option.label }}</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <label v-for="choice in checkOptions" :key="choice.value" 
                                               class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                            <input type="radio" v-model="form[option.name]" :value="choice.value" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-3 text-gray-900">{{ choice.label }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Access W/O In-Qualifier -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Access W/O In-Qualifier</label>
                                    <div class="flex space-x-4">
                                        <label v-for="choice in yesNoOptions" :key="choice.value" 
                                               class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                            <input type="radio" v-model="form.wo_qualifier" :value="choice.value" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-3 text-gray-900">{{ choice.label }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Copy & Paste Master Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-copy mr-2 text-indigo-500"></i>
                                Copy & Paste Master Card
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <div v-for="(option, index) in copyOptions" :key="index"
                                     class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ option.label }}</span>
                                    <div class="flex space-x-4">
                                        <label v-for="choice in yesNoOptions" :key="choice.value" class="inline-flex items-center">
                                            <input type="radio" v-model="form[option.name]" :value="choice.value" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ choice.label }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Order Delivery Schedule -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-truck mr-2 text-yellow-500"></i>
                                Sales Order Delivery Schedule
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <div v-for="(option, index) in deliveryOptions" :key="index"
                                     class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ option.label }}</span>
                                    <div class="flex space-x-4">
                                        <label v-for="choice in option.choices" :key="choice.value" class="inline-flex items-center">
                                            <input type="radio" v-model="form[option.name]" :value="choice.value" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ choice.label }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Order Sheet Board -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                <i class="fas fa-layer-group mr-2 text-red-500"></i>
                                Sales Order Sheet Board
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Scoring Tool</label>
                                <div class="flex space-x-2">
                                    <input type="number" v-model="form.sheet_board_scoring" 
                                           class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           min="0">
                                    <button type="button" @click="searchSheetBoardScoring"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="cancelForm"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

// Form data
const form = reactive({
    paper_quality: 'y_check',
    desktop_service: 'yes',
    account_credit: 'yes',
    sales_order: 'yes',
    flute_pattern: ['', '', '', '', ''],
    scoring_tool: '',
    mcp_stage: 'y_check',
    amount_approved: 'y_check',
    wo_qualifier: 'yes',
    copy_price: 'yes',
    copy_subsystem: 'yes',
    board_availability: '1',
    fg_picking: '1',
    sheet_board_scoring: ''
});

// Options data
const paperQualityOptions = [
    { value: 'y_check', label: 'Y-Check & Block' },
    { value: 'p_check', label: 'P-Check & Prompt' },
    { value: 'no_check', label: 'N-No Check' }
];

const salespersonLinks = [
    { label: 'Customer Desktop Service', name: 'desktop_service' },
    { label: 'Customer Account Credit', name: 'account_credit' },
    { label: 'Customer Sales Order', name: 'sales_order' }
];

const masterCardOptions = [
    { label: 'Check MCP Stage', name: 'mcp_stage' },
    { label: 'Amount Approved M/Card', name: 'amount_approved' }
];

const copyOptions = [
    { label: 'Copy Master Card Price', name: 'copy_price' },
    { label: 'Copy from Sub-System', name: 'copy_subsystem' }
];

const deliveryOptions = [
    {
        label: 'Sheet Board Availability',
        name: 'board_availability',
        choices: [
            { value: '1', label: '1-Computer' },
            { value: '2', label: '2-Sheet Board Reserve' }
        ]
    },
    {
        label: 'FG Picking List',
        name: 'fg_picking',
        choices: [
            { value: '1', label: '1-Lock Down by MCR' },
            { value: '2', label: '2-Lock Down by SOR' }
        ]
    }
];

const yesNoOptions = [
    { value: 'yes', label: 'Y-Yes' },
    { value: 'no', label: 'N-No' }
];

const checkOptions = [
    { value: 'y_check', label: 'Y-Check & Block' },
    { value: 'p_check', label: 'P-Check & Prompt' },
    { value: 'no_check', label: 'N-No Check' }
];

// Methods
const submitForm = () => {
    router.post(route('sales-configuration.store'), form);
};

const cancelForm = () => {
    router.visit(route('dashboard'));
};

const searchScoringTool = () => {
    // Implement scoring tool search functionality
    console.log('Searching scoring tool...');
};

const searchSheetBoardScoring = () => {
    // Implement sheet board scoring search functionality
    console.log('Searching sheet board scoring...');
};

// Props
const props = defineProps({
    title: String
});
</script>
