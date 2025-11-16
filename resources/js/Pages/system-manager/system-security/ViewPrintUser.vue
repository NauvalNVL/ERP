<template>
    <AppLayout header="View & Print User">
        <Head title="View & Print User" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden overflow-x-hidden">
            <div class="max-w-7xl w-full mx-auto relative z-10">
                <!-- Header Card -->
                <div class="bg-white/80 shadow rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:via-indigo-600 md:to-purple-600 p-4 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center">
                                <div class="bg-white/20 rounded-full p-4 mr-4">
                                    <UsersIcon class="h-8 w-8 text-white" />
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">View & Print Users</h1>
                                    <p class="text-blue-100">Comprehensive user information and printing</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 justify-start md:justify-end">
                                <button 
                                    @click="refreshData"
                                    class="inline-flex items-center px-4 md:px-6 py-2.5 md:py-3 bg-white/10 text-white font-semibold rounded-xl shadow hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/40 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    title="Refresh Data"
                                >
                                    <ArrowPathIcon class="h-5 w-5 mr-2" />
                                    Refresh
                                </button>
                                <button 
                                    @click="printUsers"
                                    class="inline-flex items-center px-4 md:px-6 py-2.5 md:py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow hover:from-emerald-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    title="Print Users"
                                >
                                    <PrinterIcon class="h-5 w-5 mr-2" />
                                    Print
                                </button>
                                <button 
                                    @click="exportUsers"
                                    class="inline-flex items-center px-4 md:px-6 py-2.5 md:py-3 bg-gradient-to-r from-indigo-500 to-blue-600 text-white font-semibold rounded-xl shadow hover:from-indigo-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
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
                <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden mb-8" style="content-visibility:auto; contain-intrinsic-size: 1px 520px; contain: content;">
                    <div class="bg-slate-700 md:bg-gradient-to-r md:from-slate-600 md:to-gray-700 p-4 md:p-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <div class="bg-white/20 rounded-full p-2 mr-3">
                                <TableCellsIcon class="h-6 w-6 text-white" />
                            </div>
                            User Directory
                        </h2>
                        <p class="text-slate-200 text-sm mt-1">Click on any user to view detailed information</p>
                    </div>
                    <div class="overflow-hidden">
                        <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                            <!-- Desktop / tablet table -->
                            <table class="hidden md:table min-w-full table-fixed">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-10">
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
                                <tbody class="bg-white/50 divide-y divide-gray-200">
                                    <tr 
                                        v-for="(user, index) in users" 
                                        :key="user.userID"
                                        :class="[
                                            'cursor-pointer transition-colors duration-150 hover:bg-white/80',
                                            selectedUser && selectedUser.userID === user.userID ? 'bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500' : ''
                                        ]"
                                        @click="selectUser(user)"
                                    >
                                        <td class="px-3 md:px-6 py-3 md:py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg px-3 py-2">
                                                    <span class="text-white font-mono font-semibold text-sm">{{ user.userID }}</span>
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
                            <div class="md:hidden divide-y divide-gray-200 bg-slate-900">
                                <div
                                    v-for="(user, index) in users"
                                    :key="user.userID"
                                    :class="[
                                        'px-4 py-3 flex flex-col gap-2 cursor-pointer',
                                        selectedUser && selectedUser.userID === user.userID ? 'bg-slate-800 border-l-4 border-indigo-500' : ''
                                    ]"
                                    @click="selectUser(user)"
                                >
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg px-3 py-2 mr-3">
                                                <span class="text-white font-mono font-semibold text-sm">{{ user.userID }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-white">{{ user.userName }}</div>
                                                <div class="text-xs text-slate-200">{{ user.status === 'Active' ? 'Active User' : 'Inactive User' }}</div>
                                            </div>
                                        </div>
                                        <button
                                            @click.stop="selectUser(user)"
                                            class="ml-2 inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-[11px] font-medium rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                        >
                                            <DocumentTextIcon class="h-4 w-4 mr-1" />
                                            View
                                        </button>
                                    </div>
                                    <div class="text-[11px] text-slate-200 mb-0.5">Official Name</div>
                                    <div class="text-sm font-medium text-white break-words mb-1">{{ user.officialName || 'No Official Name' }}</div>
                                    <div class="text-[11px] text-slate-200 mb-0.5">Mobile</div>
                                    <div class="text-sm text-slate-100 break-words mb-1">{{ user.mobileNumber || 'No mobile number' }}</div>
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="flex items-center">
                                            <div class="text-[11px] text-slate-200 mr-2">Position</div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-semibold bg-gradient-to-r from-indigo-500 to-blue-500 text-white border border-indigo-300">
                                                <BriefcaseIcon class="h-4 w-4 mr-1" />
                                                {{ user.officialTitle || 'No Position' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details Form -->
                <div v-if="selectedUser" class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden mb-8" style="content-visibility:auto; contain-intrinsic-size: 1px 640px; contain: content;">
                    <div class="bg-emerald-600 md:bg-gradient-to-r md:from-emerald-600 md:to-teal-600 p-4 md:p-6">
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
                    <div class="p-4 md:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div class="bg-white/80 rounded-xl p-4 md:p-6 border border-white/20">
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

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/30">
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
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/30">
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

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/30">
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
                <div class="text-center">
                    <Link href="/user" class="inline-flex items-center px-6 py-3 md:px-8 md:py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        <ArrowLeftIcon class="h-6 w-6 mr-3" />
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