<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none backdrop-blur-sm bg-black bg-opacity-40">
        <div class="relative w-auto max-w-lg mx-auto my-6">
            <!-- Modal content -->
            <div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
                    <h3 class="text-xl font-semibold">
                        FG/DO Configuration Modal
                    </h3>
                    <button 
                        class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        @click="$emit('close')"
                    >
                        <span class="text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none">
                            ×
                        </span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative p-6 flex-auto">
                    <p class="my-4 text-blueGray-500 text-lg leading-relaxed">
                        This is a placeholder for the FG/DO Configuration modal content.
                        You can add forms or data display here.
                    </p>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
                    <button 
                        class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                        type="button"
                        @click="$emit('close')"
                    >
                        Close
                    </button>
                    <button 
                        class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                        type="button"
                        @click="$emit('save')"
                    >
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show" class="opacity-25 fixed inset-0 z-40 bg-black"></div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(['close', 'save']);
</script>

<style scoped>
/* Add any component specific styles here */
</style> 