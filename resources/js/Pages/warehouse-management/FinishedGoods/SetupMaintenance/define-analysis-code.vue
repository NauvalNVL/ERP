<template>
    <AppLayout :header="'Define Analysis Code'">
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-table text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Analysis Code</h2>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-hashtag text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Analysis Code Management</h3>
                        </div>
                        <form @submit.prevent="handleRecordAction" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-hashtag text-white text-xs"></i>
                                        </span>
                                        Code:
                                    </label>
                                    <div class="relative flex group">
                                        <input 
                                            type="text" 
                                            id="code" 
                                            v-model="form.code"
                                            @input="onCodeInput"
                                            :readonly="isEditMode"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openAnalysisCodeModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-table relative z-10"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-end justify-end">
                                    <button 
                                        type="submit"
                                        :class="{
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': !isEditMode,
                                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700': isEditMode
                                        }"
                                        class="text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                            <i :class="isEditMode ? 'fas fa-edit' : 'fas fa-plus'" class="text-white text-xs"></i>
                                        </div>
                                        <span>Record: {{ isEditMode ? 'Select' : 'Add' }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-align-left text-white text-xs"></i>
                                        </span>
                                        Name:
                                    </label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        v-model="form.name"
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-layer-group text-white text-xs"></i>
                                        </span>
                                        Grouping:
                                    </label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio mr-2" value="FGSI" v-model="form.grouping" /> F/Goods Stock-In
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio mr-2" value="FGSO" v-model="form.grouping" /> F/Goods Stock-Out
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio mr-2" value="FGST" v-model="form.grouping" /> F/Goods Stock-Take
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio mr-2" value="FGTR" v-model="form.grouping" /> F/Goods Warehouse Transfer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Quick Tips -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-purple-50 rounded-full opacity-20"></div>
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Tips</h3>
                        </div>
                        <div class="space-y-4 relative z-10">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border-l-4 border-blue-400">
                                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    How to Use
                                </h4>
                                <ul class="space-y-2 text-sm text-blue-700">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-search text-white text-xs"></i>
                                        </span>
                                        <span>Click table icon to search and select analysis code.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-plus text-white text-xs"></i>
                                        </span>
                                        <span>Enter new code to add a new analysis code.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AnalysisCodeModal
            :show="isModalOpen"
            @close="closeAnalysisCodeModal"
            @select-code="handleSelectCode"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import AnalysisCodeModal from '@/Components/AnalysisCodeModal.vue';
import axios from 'axios';

const form = reactive({
    code: '',
    name: '',
    grouping: '',
});
const isEditMode = ref(false);
const isModalOpen = ref(false);

const openAnalysisCodeModal = () => {
    isModalOpen.value = true;
};
const closeAnalysisCodeModal = () => {
    isModalOpen.value = false;
};

const handleSelectCode = (codeObj) => {
    form.code = codeObj.code;
    form.name = codeObj.name;
    form.grouping = codeObj.grouping;
    isEditMode.value = true;
    closeAnalysisCodeModal();
};
const onCodeInput = async () => {
    if (!form.code) {
        form.name = '';
        form.grouping = '';
        isEditMode.value = false;
        return;
    }
    try {
        const response = await axios.get(`/api/material-management/analysis-codes/${form.code}`);
        if (response.data) {
            form.name = response.data.name;
            form.grouping = response.data.grouping;
            isEditMode.value = true;
        } else {
            form.name = '';
            form.grouping = '';
            isEditMode.value = false;
        }
    } catch (error) {
        form.name = '';
        form.grouping = '';
        isEditMode.value = false;
    }
};
const handleRecordAction = async () => {
    if (isEditMode.value) {
        // Update
        try {
            await axios.put(`/api/material-management/analysis-codes/${form.code}`, {
                name: form.name,
                grouping: form.grouping,
            });
            alert('Analysis code updated!');
        } catch (error) {
            console.error('Error updating analysis code:', error);
            alert('Failed to update analysis code.');
        }
    } else {
        // Add
        try {
            await axios.post('/api/material-management/analysis-codes', {
                code: form.code,
                name: form.name,
                grouping: form.grouping,
            });
            alert('Analysis code added!');
        } catch (error) {
            console.error('Error adding analysis code:', error);
            alert('Failed to add analysis code. Code might already exist.');
        }
    }
    // Setelah aksi, reset form dan nonaktifkan edit mode
    form.code = '';
    form.name = '';
    form.grouping = '';
    isEditMode.value = false;
};
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.form-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-block;
  vertical-align: middle;
  background-origin: border-box;
  user-select: none;
  flex-shrink: 0;
  border-radius: 100%;
  height: 1.25em;
  width: 1.25em;
  color: #6366f1;
  background-color: #ffffff;
  border-color: #d1d5db;
  border-width: 1px;
}
.form-radio:checked {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-color: currentColor;
}
.form-radio:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.45);
}
</style> 