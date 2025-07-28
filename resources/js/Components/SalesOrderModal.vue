<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none backdrop-blur-sm bg-black bg-opacity-50 transition-opacity duration-300">
        <div class="relative w-full max-w-6xl mx-auto my-6 transform transition-all duration-300 scale-100 opacity-100" :class="{'scale-95 opacity-0': !show}">
            <!-- Modal content -->
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none overflow-hidden">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-xl shadow-md">
                    <h3 class="text-2xl font-bold text-white">
                        Sales Order Table [Sorted by S/Order#]
                    </h3>
                    <button 
                        class="p-2 ml-auto bg-transparent border-0 text-white hover:text-gray-200 transition-colors duration-200 text-3xl leading-none font-semibold outline-none focus:outline-none"
                        @click="$emit('close')"
                    >
                        <span class="block outline-none focus:outline-none transform hover:rotate-90 transition-transform duration-300">
                            ×
                        </span>
                    </button>
                </div>
                
                <!-- Modal body -->
                <div class="relative p-6 flex-auto max-h-[80vh] overflow-y-auto custom-scrollbar">
                    <!-- Search Section -->
                    <div class="mb-6 flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Search:</label>
                        <div class="flex space-x-2">
                            <input type="text" v-model="searchFields.field1" placeholder="0" class="w-16 px-2 py-1 border border-gray-300 rounded text-center" />
                            <input type="text" v-model="searchFields.field2" placeholder="0" class="w-16 px-2 py-1 border border-gray-300 rounded text-center" />
                            <input type="text" v-model="searchFields.field3" placeholder="0" class="w-16 px-2 py-1 border border-gray-300 rounded text-center" />
                        </div>
                        <button @click="performSearch" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Search
                        </button>
                        <span class="text-sm font-medium text-gray-700">S/Order#</span>
                    </div>

                    <!-- Sales Order Table -->
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SO#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CUSTOMER PO#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">AC#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">MCS#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STATUS</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">D/LOCATION</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Select</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="order in filteredSalesOrders" :key="order.so_number" 
                                    @click="selectSalesOrder(order)" 
                                    class="hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                    :class="{ 'bg-blue-200': selectedOrder?.so_number === order.so_number }">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.so_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ order.customer_po }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ order.ac_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ order.mcs_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ order.status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ order.d_location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click.stop="selectSalesOrder(order)" class="text-blue-600 hover:text-blue-900 font-semibold transition-colors duration-150">Select</button>
                                    </td>
                                </tr>
                                <tr v-if="filteredSalesOrders.length === 0">
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No sales orders found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Details Section -->
                    <div v-if="selectedOrder" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Order Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name:</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-md bg-white">
                                    {{ selectedOrder.customer_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Model:</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-md bg-white">
                                    {{ selectedOrder.model }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order Mode:</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-md bg-white">
                                    {{ selectedOrder.order_mode }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson:</label>
                                <div class="flex space-x-2">
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-white flex-1">
                                        {{ selectedOrder.salesperson_code }}
                                    </div>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-white flex-1">
                                        {{ selectedOrder.salesperson_name }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order Group:</label>
                                <div class="flex space-x-2">
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-white flex-1">
                                        {{ selectedOrder.order_group }}
                                    </div>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-white flex-1">
                                        Order Type: {{ selectedOrder.order_type }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Details Table -->
                    <div v-if="selectedOrder" class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Item Details</h4>
                        <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ITEM</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">MAIN</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F1</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F2</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F3</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F4</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F5</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F6</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F7</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F8</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">F9</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in orderItems" :key="index" 
                                        class="hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                        :class="{ 'bg-blue-200': selectedItemIndex === index }"
                                        @click="selectItem(index)">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.item }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.main }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f1 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f2 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f3 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f4 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f5 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f6 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f7 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f8 || '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.f9 || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 bg-gray-50 border-t border-solid border-gray-200 rounded-b-xl">
                    <button 
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-bold uppercase px-6 py-2 text-sm rounded-lg shadow hover:shadow-md outline-none focus:outline-none mr-3 transition-all duration-150"
                        type="button"
                        @click="$emit('close')"
                    >
                        Exit
                    </button>
                    <button 
                        class="bg-blue-500 text-white hover:bg-blue-600 font-bold uppercase text-sm px-6 py-3 rounded-lg shadow hover:shadow-lg outline-none focus:outline-none mr-3 transition-all duration-150"
                        type="button"
                        @click="$emit('sort-by-so')"
                    >
                        Sort by SO#
                    </button>
                    <button 
                        class="bg-blue-500 text-white hover:bg-blue-600 font-bold uppercase text-sm px-6 py-3 rounded-lg shadow hover:shadow-lg outline-none focus:outline-none mr-3 transition-all duration-150"
                        type="button"
                        @click="$emit('sort-by-customer')"
                    >
                        Sort by Customer
                    </button>
                    <button 
                        v-if="selectedOrder"
                        class="bg-green-500 text-white hover:bg-green-600 font-bold uppercase text-sm px-6 py-3 rounded-lg shadow hover:shadow-lg outline-none focus:outline-none transition-all duration-150"
                        type="button"
                        @click="confirmSelection"
                    >
                        Select
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show" class="fixed inset-0 z-40 bg-black opacity-50 transition-opacity duration-300"></div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    salesOrders: {
        type: Array,
        default: () => [],
    },
});

const emits = defineEmits(['close', 'select-sales-order', 'sort-by-so', 'sort-by-customer']);

const searchFields = ref({
    field1: '',
    field2: '',
    field3: ''
});

const selectedOrder = ref(null);
const selectedItemIndex = ref(null);
const searchQuery = ref('');

const orderItems = computed(() => {
    if (!selectedOrder.value) return [];
    
    return [
        { item: 'PD', main: 'OF-SF' },
        { item: 'PCS', main: '1' },
        { item: 'UNIT', main: 'Pcs' },
        { item: 'ORDER', main: '1,000' },
        { item: 'NET DELIVERY', main: '1,000' },
        { item: 'BALANCE', main: '' }
    ];
});

const filteredSalesOrders = computed(() => {
    if (!searchQuery.value) {
        return props.salesOrders;
    }
    const query = searchQuery.value.toLowerCase();
    return props.salesOrders.filter(order => 
        order.so_number.toLowerCase().includes(query) || 
        order.customer_name.toLowerCase().includes(query) ||
        order.customer_po.toLowerCase().includes(query)
    );
});

const selectSalesOrder = (order) => {
    selectedOrder.value = order;
    selectedItemIndex.value = null;
};

const selectItem = (index) => {
    selectedItemIndex.value = index;
};

const performSearch = () => {
    // Combine search fields into a single query
    const query = [searchFields.value.field1, searchFields.value.field2, searchFields.value.field3]
        .filter(field => field.trim() !== '')
        .join('-');
    
    searchQuery.value = query;
};

const confirmSelection = () => {
    if (selectedOrder.value) {
        emits('select-sales-order', selectedOrder.value);
        emits('close');
    }
};

// Watch for changes in salesOrders prop to reset selection
watch(() => props.salesOrders, () => {
    selectedOrder.value = null;
    selectedItemIndex.value = null;
    searchQuery.value = '';
    searchFields.value = { field1: '', field2: '', field3: '' };
}, { deep: true });
</script>

<style scoped>
/* Custom Scrollbar for modern look */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style> 