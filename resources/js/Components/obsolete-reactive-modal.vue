<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full animate-modalScaleIn">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4 relative">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
                            <i class="fas fa-search text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white" id="modal-title">{{ title }}</h3>
                    </div>
                    <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <div class="bg-gray-50 p-6">
                    <input 
                        type="text" 
                        v-model="searchTerm"
                        :placeholder="`Search for ${title.toLowerCase()}...`"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"
                    >
                </div>

                <div class="px-6 pb-6" style="max-height: 50vh; overflow-y: auto;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th v-for="header in headers" :key="header.key" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
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
                                @click="selectItem(item)"
                                class="hover:bg-indigo-50 cursor-pointer transition-colors duration-150"
                            >
                                <td v-for="header in headers" :key="header.key" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ item[header.key] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-100 px-6 py-4 flex justify-end">
                    <button 
                        @click="closeModal" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors"
                    >
                        Close
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
});

const emit = defineEmits(['close', 'select']);

const searchTerm = ref('');

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

const selectItem = (item) => {
    emit('select', item);
    closeModal();
};

const closeModal = () => {
    searchTerm.value = '';
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
</style> 