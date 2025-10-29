<template>
    <AppLayout header="View & Print User">
        <Head title="View & Print User" />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <!-- Header with Icons -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-users text-blue-500 mr-3"></i>View & Print User
                    </h2>
                    <div class="flex items-center space-x-3">
                        <button 
                            @click="refreshData"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors flex items-center"
                            title="Refresh"
                        >
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button 
                            @click="printUsers"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors flex items-center"
                            title="Print"
                        >
                            <i class="fas fa-print"></i>
                        </button>
                        <button 
                            @click="exportUsers"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors flex items-center"
                            title="Export"
                        >
                            <i class="fas fa-file-export"></i>
                        </button>
                    </div>
                </div>

                <!-- User Table -->
                <div class="mb-6 border border-gray-300 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">UID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">User Printer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Route</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">SLM#</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr 
                                    v-for="(user, index) in users" 
                                    :key="user.userID"
                                    :class="{ 'bg-blue-100': selectedUser && selectedUser.userID === user.userID, 'hover:bg-gray-50': !selectedUser || selectedUser.userID !== user.userID }"
                                    @click="selectUser(user)"
                                    class="cursor-pointer"
                                >
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ user.userID }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ user.userName }}</td>
                                    <td class="px-4 py-2 text-sm border-r border-gray-200">
                                        <span :class="user.status === 'Active' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
                                            {{ user.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ user.userPrinter || '' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r border-gray-200">{{ user.route || '' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ user.slm || '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- User Details Form -->
                <div v-if="selectedUser" class="grid grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Official Name:</label>
                            <input 
                                v-model="selectedUser.officialName" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Official Title:</label>
                            <input 
                                v-model="selectedUser.officialTitle" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Mobile Number:</label>
                            <input 
                                v-model="selectedUser.mobileNumber" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Office Tel:</label>
                            <input 
                                v-model="selectedUser.officeTel" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">SLM Name:</label>
                            <input 
                                v-model="selectedUser.slmName" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Password Expiry:</label>
                            <div class="flex items-center space-x-2">
                                <input 
                                    v-model="selectedUser.passwordExpiry" 
                                    type="text"
                                    class="w-16 px-3 py-2 border border-gray-300 rounded bg-gray-50 text-center"
                                    readonly
                                >
                                <span class="text-sm text-gray-600">Days [Zero for None]</span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Password Expired:</label>
                            <input 
                                v-model="selectedUser.passwordExpired" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Password Locked:</label>
                            <input 
                                v-model="selectedUser.passwordLocked" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-40 text-sm font-medium text-gray-700">Menu Type:</label>
                            <input 
                                v-model="selectedUser.menuType" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">Access Unit Price:</label>
                            <input 
                                v-model="selectedUser.accessUnitPrice" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">Amend MC:</label>
                            <input 
                                v-model="selectedUser.amendMC" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">Access Customer A/C:</label>
                            <input 
                                v-model="selectedUser.accessCustomerAC" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">Amend MC Price:</label>
                            <input 
                                v-model="selectedUser.amendMCPrice" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">Amend Expired Password:</label>
                            <input 
                                v-model="selectedUser.amendExpiredPassword" 
                                type="text"
                                class="w-24 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">WBMS Restricted Pass:</label>
                            <input 
                                v-model="selectedUser.wbmsRestrictedPass" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">RC + RT Price:</label>
                            <input 
                                v-model="selectedUser.rcRtPrice" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>

                        <div class="flex items-center">
                            <label class="w-48 text-sm font-medium text-gray-700">BP RC Cost:</label>
                            <input 
                                v-model="selectedUser.bpRcCost" 
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50"
                                readonly
                            >
                        </div>
                    </div>
                </div>

                <!-- Back to User List -->
                <div class="mt-8 text-center">
                    <Link href="/user" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                        <i class="fas fa-arrow-left mr-1"></i> Back to User List
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default {
    components: {
        Head,
        Link,
        AppLayout
    },
    data() {
        return {
            users: [],
            selectedUser: null
        }
    },
    mounted() {
        this.loadUsers();
    },
    methods: {
        async loadUsers() {
            try {
                const response = await axios.get('/api/users');
                this.users = response.data.map(user => ({
                    userID: user.userID,
                    userName: user.userName,
                    status: user.status === 'A' ? 'Active' : 'Obsolete',
                    userPrinter: user.userPrinter || '/VIEWER',
                    route: user.route || 'Function to User',
                    slm: user.slm || '',
                    officialName: user.officialName,
                    officialTitle: user.officialTitle,
                    mobileNumber: user.mobileNumber,
                    officeTel: user.officeTel,
                    slmName: user.slmName || '',
                    passwordExpiry: user.passwordExpiryDate || '0',
                    passwordExpired: user.passwordExpired || 'No',
                    passwordLocked: user.passwordLocked || 'No',
                    menuType: user.menuType || 'V-View Tree',
                    accessUnitPrice: user.accessUnitPrice || 'No',
                    amendMC: user.amendMC || 'No',
                    accessCustomerAC: user.accessCustomerAC || 'No',
                    amendMCPrice: user.amendMCPrice || 'No',
                    amendExpiredPassword: user.amendExpiredPassword || 'No',
                    wbmsRestrictedPass: user.wbmsRestrictedPass || '',
                    rcRtPrice: user.rcRtPrice || '',
                    bpRcCost: user.bpRcCost || ''
                }));
            } catch (error) {
                console.error('Error loading users:', error);
            }
        },
        selectUser(user) {
            this.selectedUser = user;
        },
        refreshData() {
            this.loadUsers();
            this.selectedUser = null;
        },
        printUsers() {
            window.print();
        },
        exportUsers() {
            // Export functionality can be implemented here
            console.log('Export users');
        }
    }
}
</script>

<style scoped>
@media print {
    .no-print {
        display: none;
    }
}

table {
    border-collapse: collapse;
}

thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

tr.cursor-pointer:hover {
    background-color: #f9fafb;
}
</style>