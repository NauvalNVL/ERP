<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full animate-modalScaleIn">
                <div :class="headerClass" class="px-6 py-4 relative">
                    <div class="flex items-center">
                        <div :class="headerIconBgClass" class="p-2 rounded-full mr-4">
                            <i :class="headerIconClass" class="text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white" id="modal-title">{{ title }}</h3>
                    </div>
                    <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <div class="bg-white px-6 pt-6 pb-4">
                    <div v-if="filterTag" class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Filter: {{ filterTag }}
                        </span>
                    </div>
                    <input 
                        type="text" 
                        v-model="searchTerm"
                        :placeholder="`Search ${title.toLowerCase()}...`"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"
                    >
                </div>

                <div class="px-6 pb-6" style="max-height: 50vh; overflow-y: auto;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 sticky top-0 shadow-md">
                            <tr>
                                <th v-for="header in headers" :key="header.key" scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    {{ header.label }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="filteredItems.length === 0">
                                <td :colspan="headers.length" class="text-center py-10 text-gray-500">
                                    No results found.
                                </td>
                            </tr>
                            <tr 
                                v-for="item in filteredItems" 
                                :key="item.id" 
                                @click="selectRow(item)"
                                :class="{'bg-indigo-100 ring-2 ring-indigo-300': selectedItemInternal && selectedItemInternal.id === item.id}"
                                class="hover:bg-indigo-50 cursor-pointer transition-all duration-200 ease-in-out transform hover:scale-[1.01] hover:shadow-lg"
                            >
                                <td v-for="header in headers" :key="header.key" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span v-if="header.key === 'status' && item[header.key] === 'Active'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ item[header.key] }}
                                    </span>
                                    <span v-else-if="header.key === 'status' && item[header.key] === 'Obsolete'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ item[header.key] }}
                                    </span>
                                    <span v-else>
                                        {{ item[header.key] }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3">
                    <button 
                        v-if="showMoreOptionsButton"
                        @click="emit('moreOptions')" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center space-x-2"
                    >
                        <i class="fas fa-cog"></i>
                        <span>More Options</span>
                    </button>
                    <button 
                        v-if="showZoomButton"
                        @click="emit('zoom')" 
                        class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center space-x-2"
                    >
                        <i class="fas fa-search-plus"></i>
                        <span>Zoom</span>
                    </button>
                    <button 
                        @click="confirmSelection" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors"
                    >
                        Select
                    </button>
                    <button 
                        @click="closeModal" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    headers: {
        type: Array,
        required: true, // e.g., [{ key: 'name', label: 'Name' }, { key: 'code', label: 'Code' }]
    },
    headerClass: {
        type: String,
        default: 'bg-gradient-to-r from-indigo-600 to-purple-700' // Default to existing gradient
    },
    headerIconClass: {
        type: String,
        default: 'fas fa-search' // Default to existing icon
    },
    headerIconBgClass: {
        type: String,
        default: 'bg-white bg-opacity-20' // Default to existing background
    },
    filterTag: {
        type: String,
        default: ''
    },
    showMoreOptionsButton: {
        type: Boolean,
        default: false
    },
    showZoomButton: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'select', 'moreOptions', 'zoom']);

const searchTerm = ref('');
const selectedItemInternal = ref(null); // New ref to store selected item

const filteredItems = computed(() => {
    if (!searchTerm.value) {
        return props.items;
    }
    return props.items.filter(item => 
        Object.values(item).some(value => 
            String(value).toLowerCase().includes(searchTerm.value.toLowerCase())
        )
    );
});

// Modified selectItem to only store the item and not close the modal
const selectRow = (item) => {
    selectedItemInternal.value = item;
};

// New function for the "Select" button
const confirmSelection = () => {
    if (selectedItemInternal.value) {
        emit('select', selectedItemInternal.value);
    }
    closeModal(); // Always close after select or if no selection
};

const closeModal = () => {
    searchTerm.value = '';
    selectedItemInternal.value = null; // Clear selected item on close
    emit('close');
};
</script>

<style scoped>
@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.animate-modalScaleIn {
    animation: modalScaleIn 0.3s ease-out forwards;
}

/* Add a style for the selected row highlight */
.selected-row {
    background-color: #e0e7ff; /* A light indigo background */
}

</style> 