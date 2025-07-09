<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Select Control Period</h2>
            <div class="flex space-x-4 mb-6">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                    <input 
                        type="number" 
                        id="month" 
                        v-model="monthInput" 
                        min="1" 
                        max="12" 
                        class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    />
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                    <input 
                        type="number" 
                        id="year" 
                        v-model="yearInput" 
                        :min="minYear" 
                        :max="maxYear" 
                        class="mt-1 block w-28 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    />
                </div>
            </div>
            <div class="flex justify-end">
                <button 
                    @click="selectPeriod" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out"
                >
                    Select
                </button>
                <button 
                    @click="closeModal" 
                    class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out"
                >
                    Cancel
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    currentMonth: [Number, null],
    currentYear: [Number, null],
});

const emit = defineEmits(['close', 'period-selected']);

const monthInput = ref(new Date().getMonth() + 1);
const yearInput = ref(new Date().getFullYear());

const minYear = ref(1900); // Or a more reasonable start year for your ERP
const maxYear = ref(2100); // Or a more reasonable end year

watch(() => props.show, (newValue) => {
    if (newValue) {
        // Set initial values from props or current date if not provided
        monthInput.value = props.currentMonth !== null ? props.currentMonth : new Date().getMonth() + 1;
        yearInput.value = props.currentYear !== null ? props.currentYear : new Date().getFullYear();
    }
});

const selectPeriod = () => {
    if (monthInput.value && yearInput.value) {
        emit('period-selected', { month: monthInput.value, year: yearInput.value });
    } else {
        alert('Please enter a valid month and year.');
    }
};

const closeModal = () => {
    emit('close');
};
</script> 