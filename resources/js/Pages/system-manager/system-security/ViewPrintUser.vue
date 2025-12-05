<template>
    <AppLayout header="View & Print User">
        <Head title="View & Print User" />
        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto w-full relative z-0">
                <!-- Header Card -->
                <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 mb-4">
                    <div class="bg-blue-600 px-4 py-3 sm:px-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center">
                                <div class="bg-white/20 rounded-full p-3 mr-3">
                                    <UsersIcon class="h-6 w-6 text-white" />
                                </div>
                                <div>
                                    <h1 class="text-lg md:text-xl font-semibold text-white leading-tight mb-1">View & Print Users</h1>
                                    <p class="text-blue-100 text-xs md:text-sm">Comprehensive user information and printing</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 justify-start md:justify-end">
                                <button 
                                    @click="refreshData"
                                    class="inline-flex items-center px-3 py-2 bg-white/10 text-white text-xs md:text-sm font-medium rounded-lg shadow-sm hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/40 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Refresh Data"
                                >
                                    <ArrowPathIcon class="h-5 w-5 mr-2" />
                                    Refresh
                                </button>
                                <button 
                                    @click="printUsers"
                                    class="inline-flex items-center px-3 py-2 bg-emerald-500 text-white text-xs md:text-sm font-medium rounded-lg shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Print Users"
                                >
                                    <PrinterIcon class="h-5 w-5 mr-2" />
                                    Print
                                </button>
                                <button 
                                    @click="exportUsers"
                                    class="inline-flex items-center px-3 py-2 bg-indigo-500 text-white text-xs md:text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Export Users"
                                >
                                    <DocumentArrowDownIcon class="h-5 w-5 mr-2" />
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Table -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-4">
                    <div class="bg-blue-600 px-4 py-3 sm:px-6">
                        <h2 class="text-lg md:text-xl font-semibold text-white flex items-center">
                            <div class="bg-white/20 rounded-full p-2 mr-3">
                                <TableCellsIcon class="h-6 w-6 text-white" />
                            </div>
                            User Directory
                        </h2>
                        <p class="text-blue-100 text-xs md:text-sm mt-1">Click on any user to view detailed information</p>
                    </div>
                    <div class="overflow-hidden">
                        <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                            <!-- Desktop / tablet table -->
                            <table class="hidden md:table min-w-full table-fixed divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-1 mr-2">
                                                    <IdentificationIcon class="h-4 w-4 text-white" />
                                                </div>
                                                User ID
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-1 mr-2">
                                                    <UserIcon class="h-4 w-4 text-white" />
                                                </div>
                                                Username
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-1 mr-2">
                                                    <CheckCircleIcon class="h-4 w-4 text-white" />
                                                </div>
                                                Official Name
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-1 mr-2">
                                                    <BriefcaseIcon class="h-4 w-4 text-white" />
                                                </div>
                                                Position
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-end">
                                                <div class="bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full p-1 mr-2">
                                                    <DocumentTextIcon class="h-4 w-4 text-white" />
                                                </div>
                                                Actions
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr 
                                        v-for="(user, index) in users" 
                                        :key="user.userID"
                                        :class="[
                                            'cursor-pointer hover:bg-gray-50',
                                            selectedUser && selectedUser.userID === user.userID ? 'bg-indigo-50 border-l-4 border-indigo-500' : ''
                                        ]"
                                        @click="selectUser(user)"
                                    >
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="rounded-md bg-blue-50 border border-blue-100 px-3 py-1.5">
                                                    <span class="text-xs font-mono font-semibold text-blue-700">{{ user.userID }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-nowrap">
                                            <div class="flex items-center min-w-0">
                                                <div class="bg-gray-100 rounded-full p-2 mr-3">
                                                    <UserIcon class="h-5 w-5 text-gray-600" />
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ user.userName }}</div>
                                                    <div class="text-xs text-gray-500 truncate">{{ user.status === 'Active' ? 'Active User' : 'Inactive User' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-normal">
                                            <div class="flex items-center min-w-0">
                                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                                    <CheckCircleIcon class="h-5 w-5 text-green-600" />
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 break-words">{{ user.officialName || 'No Official Name' }}</div>
                                                    <div class="text-xs text-gray-500 break-words">{{ user.mobileNumber || 'No mobile number' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 md:px-4 py-2 rounded-xl text-xs md:text-sm font-semibold bg-gradient-to-r from-indigo-500 to-blue-500 text-white border border-indigo-300 shadow-md">
                                                <BriefcaseIcon class="h-4 w-4 mr-2" />
                                                {{ user.officialTitle || 'No Position' }}
                                            </span>
                                        </td>
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-nowrap text-right text-xs md:text-sm font-medium">
                                            <button 
                                                @click.stop="selectUser(user)"
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                            >
                                                <DocumentTextIcon class="h-4 w-4 mr-2" />
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Mobile card list -->
                            <div class="md:hidden bg-white px-2 py-2">
                                <div
                                    v-for="(user, index) in users"
                                    :key="user.userID"
                                    :class="[
                                        'px-4 py-3 flex flex-col gap-2 cursor-pointer bg-white rounded-lg shadow-sm mb-3 border',
                                        selectedUser && selectedUser.userID === user.userID ? 'border-indigo-400 ring-1 ring-indigo-300' : 'border-gray-200'
                                    ]"
                                    @click="selectUser(user)"
                                >
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="bg-blue-50 border border-blue-100 rounded-md px-3 py-1.5 mr-3">
                                                <span class="text-xs font-mono font-semibold text-blue-700">{{ user.userID }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ user.userName }}</div>
                                            <div class="text-xs text-gray-500">{{ user.status === 'Active' ? 'Active User' : 'Inactive User' }}</div>
                                        </div>
                                    </div>
                                    <button
                                        @click.stop="selectUser(user)"
                                        class="ml-2 inline-flex items-center px-3 py-1.5 bg-indigo-500 text-white text-[11px] font-medium rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        <DocumentTextIcon class="h-4 w-4 mr-1" />
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details Form -->
                <div v-if="selectedUser" class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-4">
                    <div class="bg-emerald-600 px-4 py-4 sm:px-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                {{ selectedUser.officialName ? selectedUser.officialName.charAt(0).toUpperCase() : selectedUser.userID.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="text-xl md:text-2xl font-bold text-white">{{ selectedUser.officialName || selectedUser.userName }}</h3>
                                <p class="text-emerald-100">{{ selectedUser.userID }} • {{ selectedUser.officialTitle || 'No Title' }}</p>
                                <p class="text-sm text-emerald-200 mt-1">✓ User selected for detailed view</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div class="bg-white rounded-lg p-4 sm:p-5 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <UserIcon class="h-5 w-5 mr-2 text-emerald-600" />
                                        Personal Information
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                <IdentificationIcon class="h-4 w-4 mr-2 text-blue-500" />
                                                Official Name
                                            </label>
                                            <input 
                                                v-model="selectedUser.officialName" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200"
                                                readonly
                                            >
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                <BriefcaseIcon class="h-4 w-4 mr-2 text-purple-500" />
                                                Official Title
                                            </label>
                                            <input 
                                                v-model="selectedUser.officialTitle" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                <PhoneIcon class="h-4 w-4 mr-2 text-green-500" />
                                                Mobile Number
                                            </label>
                                            <input 
                                                v-model="selectedUser.mobileNumber" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                <PhoneIcon class="h-4 w-4 mr-2 text-blue-500" />
                                                Office Tel
                                            </label>
                                            <input 
                                                v-model="selectedUser.officeTel" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 sm:p-5 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <MapIcon class="h-5 w-5 mr-2 text-teal-600" />
                                        System Information
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2">SLM Name</label>
                                            <input 
                                                v-model="selectedUser.slmName" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2">Menu Type</label>
                                            <input 
                                                v-model="selectedUser.menuType" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div class="bg-gray-50 rounded-lg p-4 sm:p-5 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <CheckCircleIcon class="h-5 w-5 mr-2 text-indigo-600" />
                                        Security Settings
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2">Password Expiry (Days)</label>
                                            <div class="flex items-center space-x-2">
                                                <input 
                                                    v-model="selectedUser.passwordExpiry" 
                                                    type="text"
                                                    class="w-20 px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium text-center focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200"
                                                    readonly
                                                >
                                                <span class="text-sm text-gray-600">[Zero for None]</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Password Expired</label>
                                                <input 
                                                    v-model="selectedUser.passwordExpired" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200"
                                                    readonly
                                                >
                                            </div>
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Password Locked</label>
                                                <input 
                                                    v-model="selectedUser.passwordLocked" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 sm:p-5 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <BriefcaseIcon class="h-5 w-5 mr-2 text-purple-600" />
                                        Access Permissions
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Access Unit Price</label>
                                                <input 
                                                    v-model="selectedUser.accessUnitPrice" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Amend MC</label>
                                                <input 
                                                    v-model="selectedUser.amendMC" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Access Customer A/C</label>
                                                <input 
                                                    v-model="selectedUser.accessCustomerAC" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">Amend MC Price</label>
                                                <input 
                                                    v-model="selectedUser.amendMCPrice" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2">Amend Expired Password</label>
                                            <input 
                                                v-model="selectedUser.amendExpiredPassword" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="text-sm font-semibold text-gray-700 mb-2">WBMS Restricted Pass</label>
                                            <input 
                                                v-model="selectedUser.wbmsRestrictedPass" 
                                                type="text"
                                                class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                readonly
                                            >
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">RC + RT Price</label>
                                                <input 
                                                    v-model="selectedUser.rcRtPrice" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                            <div class="flex flex-col">
                                                <label class="text-sm font-semibold text-gray-700 mb-2">BP RC Cost</label>
                                                <input 
                                                    v-model="selectedUser.bpRcCost" 
                                                    type="text"
                                                    class="px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Back to User List -->
                <div class="text-center mt-4">
                    <Link href="/user" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <ArrowLeftIcon class="h-5 w-5 mr-2" />
                        Back to User List
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
import {
    UsersIcon,
    UserIcon,
    IdentificationIcon,
    BriefcaseIcon,
    CheckCircleIcon,
    PrinterIcon,
    MapIcon,
    TableCellsIcon,
    ArrowPathIcon,
    DocumentArrowDownIcon,
    PhoneIcon,
    ArrowLeftIcon,
    EyeIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline'

export default {
    components: {
        Head,
        Link,
        AppLayout,
        UsersIcon,
        UserIcon,
        IdentificationIcon,
        BriefcaseIcon,
        CheckCircleIcon,
        PrinterIcon,
        MapIcon,
        TableCellsIcon,
        ArrowPathIcon,
        DocumentArrowDownIcon,
        PhoneIcon,
        ArrowLeftIcon,
        EyeIcon,
        DocumentTextIcon
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

                    // align with Define User table fields
                    officialName: user.official_name ?? user.officialName ?? '',
                    officialTitle: user.official_title ?? user.officialTitle ?? '',

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

/* Modern Animations */
.animate-fadeIn {
    animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-slideIn {
    animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateX(-30px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

/* Animated Bubbles */
.bubble {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
    animation: float 15s infinite ease-in-out;
    pointer-events: none;
}

.bubble-1 {
    width: 80px;
    height: 80px;
    left: 10%;
    top: 20%;
    animation-delay: 0s;
    animation-duration: 20s;
}

.bubble-2 {
    width: 120px;
    height: 120px;
    right: 15%;
    top: 10%;
    animation-delay: 2s;
    animation-duration: 25s;
}

.bubble-3 {
    width: 60px;
    height: 60px;
    left: 20%;
    bottom: 30%;
    animation-delay: 4s;
    animation-duration: 18s;
}

.bubble-4 {
    width: 100px;
    height: 100px;
    right: 25%;
    bottom: 20%;
    animation-delay: 6s;
    animation-duration: 22s;
}

.bubble-5 {
    width: 40px;
    height: 40px;
    left: 50%;
    top: 15%;
    animation-delay: 8s;
    animation-duration: 16s;
}

.bubble-6 {
    width: 90px;
    height: 90px;
    left: 5%;
    bottom: 10%;
    animation-delay: 10s;
    animation-duration: 24s;
}

.bubble-7 {
    width: 70px;
    height: 70px;
    right: 5%;
    top: 50%;
    animation-delay: 12s;
    animation-duration: 19s;
}

.bubble-8 {
    width: 50px;
    height: 50px;
    left: 80%;
    bottom: 40%;
    animation-delay: 14s;
    animation-duration: 21s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) translateX(0px) rotate(0deg);
        opacity: 0.3;
    }
    25% {
        transform: translateY(-20px) translateX(10px) rotate(90deg);
        opacity: 0.6;
    }
    50% {
        transform: translateY(-40px) translateX(-10px) rotate(180deg);
        opacity: 0.4;
    }
    75% {
        transform: translateY(-20px) translateX(15px) rotate(270deg);
        opacity: 0.7;
    }
}

/* Glass morphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:scale-\[1\.02\]:hover {
    transform: scale(1.02);
}

/* Focus effects */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(var(--ring-color), 0.3);
}

/* Smooth transitions (scoped to interactive elements) */
button, a, input, select, textarea, .transition, .transition-all {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .max-w-7xl {
        max-width: 100%;
        margin: 0;
        padding: 0 1rem;
    }
    
    .rounded-2xl {
        border-radius: 1rem;
    }
    
    .p-8 {
        padding: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .grid-cols-1.lg\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-white\/80 {
        background-color: rgba(31, 41, 55, 0.8);
    }
    
    .text-gray-800 {
        color: #f9fafb;
    }
    
    .border-gray-200 {
        border-color: #374151;
    }
}
</style>