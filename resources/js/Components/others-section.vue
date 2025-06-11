<template>
    <div class="bg-white p-4 rounded-md border border-gray-200 mb-5">
        <h4 class="font-medium text-blue-700 mb-3 pb-2 border-b border-gray-200">Others</h4>
        
        <div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Input Weight:</label>
            </div>
            <div class="ml-4">
                <div class="flex space-x-6 mb-2">
                    <label class="inline-flex items-center">
                        <input type="radio" v-model="inputWeight" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" v-model="inputWeight" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">N-No</span>
                    </label>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-xs text-gray-500">To input manual KG and M2 in M/Card.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: 'Yes'
    }
});

const emit = defineEmits(['update:modelValue']);

const inputWeight = ref(props.modelValue);

watch(inputWeight, (newValue) => {
    emit('update:modelValue', newValue);
});

watch(() => props.modelValue, (newValue) => {
    inputWeight.value = newValue;
});
</script>

<style scoped>
/* Input Weight explanation text */
.text-xs.text-gray-500 {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    color: #4B5563 !important;
    margin-top: 4px;
    font-size: 0.75rem;
}
</style> 