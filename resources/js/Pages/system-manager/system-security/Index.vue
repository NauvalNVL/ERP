<template>
    <AppLayout header="User List">
        <Head title="User Management" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="max-w-7xl w-full mx-auto relative z-0">
                <!-- Floating background bubbles -->
                <div class="hidden md:block absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                    <div class="bubble bubble-1"></div>
                    <div class="bubble bubble-2"></div>
                    <div class="bubble bubble-4"></div>
                </div>
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white shadow-lg rounded-2xl border border-indigo-700 mb-6">
                    <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center">
                                <UserGroupIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">Define User</h2>
                                <p class="text-xs sm:text-sm text-indigo-100">Define and manage system users for ERP access</p>
                            </div>
                        </div>
                        <Link
                            href="/user/create"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <UserAddIcon class="h-4 w-4 mr-2" />
                            <span>Add New User</span>
                        </Link>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <TransitionGroup name="fade" tag="div" class="mb-4">
                    <div
                        v-if="$page.props.flash.success"
                        key="success"
                        class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm"
                    >
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-1.5 mr-3">
                                <CheckCircleIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm mb-0.5">Success</h3>
                                <p class="text-sm">{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="$page.props.flash.error"
                        key="error"
                        class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm"
                    >
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-1.5 mr-3">
                                <ExclamationCircleIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm mb-0.5">Error</h3>
                                <p class="text-sm">{{ $page.props.flash.error }}</p>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Users Table -->
                <div class="bg-white/90 shadow-lg rounded-2xl border border-indigo-100 overflow-hidden backdrop-blur-sm">
                    <div class="px-4 py-3 sm:px-6 border-b border-indigo-600 bg-gradient-to-r from-indigo-600 via-indigo-500 to-blue-600 text-white">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-sm font-semibold text-white flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-white/20 flex items-center justify-center">
                                        <UserIcon class="h-4 w-4 text-white" />
                                    </div>
                                    <span>User Directory</span>
                                </h2>
                                <p class="text-xs text-indigo-100 mt-1">Cari dan kelola user dengan cepat</p>
                            </div>
                            <div class="relative w-full md:w-72">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MagnifyingGlassIcon class="h-4 w-4 text-white/80" />
                                </div>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari user (ID, username, nama)..."
                                    class="block w-full rounded-xl border border-white/30 bg-white/90 py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-indigo-200 shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 hidden md:table-header-group">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        User ID
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        Username
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        Official Name
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        Position
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap text-xs font-mono text-gray-700">
                                        {{ user.user_id }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="hidden sm:flex h-7 w-7 items-center justify-center rounded-full bg-gray-100">
                                                <UserIcon class="h-4 w-4 text-gray-500" />
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ user.username }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="text-sm text-gray-900">
                                            {{ user.official_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ user.official_title || 'No Position' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <Link
                                                :href="`/user/${user.user_id}/edit`"
                                                class="inline-flex items-center justify-center rounded border border-gray-200 bg-white p-1.5 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-200"
                                                :class="{ 'pointer-events-none opacity-60': !isActive(user) }"
                                                :aria-disabled="!isActive(user)"
                                                :title="isActive(user) ? 'Edit User' : inactiveTitle('edit user')"
                                            >
                                                <PencilIcon class="h-4 w-4" />
                                            </Link>
                                            <Link
                                                :href="`/system-security/amend-password?search_user_id=${user.user_id}`"
                                                class="inline-flex items-center justify-center rounded border border-gray-200 bg-white p-1.5 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-200"
                                                :class="{ 'pointer-events-none opacity-60': !isActive(user) }"
                                                :aria-disabled="!isActive(user)"
                                                :title="isActive(user) ? 'Change Password' : inactiveTitle('change password')"
                                            >
                                                <KeyIcon class="h-4 w-4" />
                                            </Link>
                                            <Link
                                                :href="isActive(user) ? `/system-security/define-access?search_user_id=${user.user_id}` : '#'"
                                                class="inline-flex items-center justify-center rounded border border-gray-200 bg-white p-1.5 text-gray-600 hover:bg-purple-50 hover:text-purple-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-200"
                                                :class="{ 'pointer-events-none opacity-60': !isActive(user) }"
                                                :aria-disabled="!isActive(user)"
                                                :title="isActive(user) ? 'Define Access' : inactiveTitle('update access')"
                                            >
                                                <LockClosedIcon class="h-4 w-4" />
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-4 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-3">
                                                <UserIcon class="h-10 w-10 text-gray-400" />
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-600 mb-1">No Users Found</h3>
                                            <p class="text-sm text-gray-500 mb-3">Start by adding your first user to the system.</p>
                                            <Link
                                                href="/user/create"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700"
                                            >
                                                <UserAddIcon class="h-4 w-4 mr-2" />
                                                Add First User
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.links && users.links.length > 3" class="px-4 py-3 sm:px-6 bg-gray-50 border-t border-gray-200">
                        <nav class="flex justify-center" aria-label="Pagination">
                            <div class="flex space-x-1.5">
                                <div v-for="(link, i) in users.links" :key="i">
                                    <span
                                        v-if="link.url === null"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
                                        v-html="link.label"
                                    >
                                    </span>
                                    <Link
                                        v-else
                                        :href="link.url"
                                        :class="{
                                            'bg-indigo-600 text-white shadow-sm': link.active,
                                            'bg-white text-gray-700 hover:bg-gray-50 hover:text-indigo-600 shadow-sm': !link.active
                                        }"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 transition-colors duration-150"
                                    >
                                        <span v-html="link.label"></span>
                                    </Link>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    UserIcon,
    UserGroupIcon,
    UserPlusIcon as UserAddIcon,
    PencilIcon,
    KeyIcon,
    LockClosedIcon,
    CogIcon,
    IdentificationIcon,
    BriefcaseIcon,
    CheckBadgeIcon as BadgeCheckIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline';

export default {
    components: {
        AppLayout,
        Head,
        Link,
        UserIcon,
        UserGroupIcon,
        UserAddIcon,
        PencilIcon,
        KeyIcon,
        LockClosedIcon,
        CogIcon,
        IdentificationIcon,
        BriefcaseIcon,
        BadgeCheckIcon,
        CheckCircleIcon,
        ExclamationCircleIcon,
        MagnifyingGlassIcon
    },
    props: {
        users: Object
    },
    data() {
        return {
            searchQuery: ''
        }
    },
    computed: {
        filteredUsers() {
            if (!this.searchQuery || !this.searchQuery.trim()) {
                return this.users.data;
            }
            const query = this.searchQuery.toLowerCase().trim();
            return this.users.data.filter(user => {
                return (
                    user.user_id.toLowerCase().includes(query) ||
                    user.username.toLowerCase().includes(query) ||
                    (user.official_name && user.official_name.toLowerCase().includes(query))
                );
            });
        }
    },
    methods: {
        isActive(user) {
            return user.status === 'A';
        },
        inactiveTitle(action) {
            return `Inactive users cannot ${action}. Please reactive the user first.`;
        }
    }
}
</script>

<style scoped>
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

/* Glass morphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Focus effects */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(var(--ring-color), 0.3);
}

/* Custom gradient backgrounds */
.bg-gradient-to-br {
    background: linear-gradient(135deg, var(--tw-gradient-stops));
}

/* Remove global transitions for performance */

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

/* Removed row hover transform for performance */

/* Menu animations */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
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

    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-6 {
        padding-top: 1rem;
        padding-bottom: 1rem;
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
