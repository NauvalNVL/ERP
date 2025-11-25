<template>
    <AppLayout header="User List">
        <Head title="User Management" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden overflow-x-hidden">
            <div class="max-w-7xl mx-auto relative z-0">
                <!-- Header Card -->
                <div class="bg-white/80 shadow rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:via-indigo-600 md:to-purple-600 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                            <div class="flex items-center mb-3 sm:mb-0">
                                <div class="bg-white/20 rounded-full p-2 sm:p-3 mr-3 sm:mr-4">
                                    <UserGroupIcon class="h-7 w-7 sm:h-8 sm:w-8 text-white" />
                                </div>
                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 leading-tight">User Management</h1>
                                    <p class="text-blue-100 text-xs sm:text-sm">Manage system users and their information</p>
                                </div>
                            </div>
                            <Link href="/user/create"
                                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-white/20 text-white font-semibold rounded-xl shadow hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/30 transition-colors duration-200">
                                <UserAddIcon class="h-5 w-5 sm:h-6 sm:w-6 mr-2 sm:mr-3" />
                                <span class="truncate">Add New User</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <TransitionGroup name="fade" tag="div" class="mb-8">
                    <div v-if="$page.props.flash.success" key="success" class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <CheckCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Success!</h3>
                                <p>{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="$page.props.flash.error" key="error" class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 p-6 rounded-xl shadow">
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-2 mr-4">
                                <ExclamationCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Error!</h3>
                                <p>{{ $page.props.flash.error }}</p>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Users Table -->
                <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden">
                    <div class="bg-blue-500 md:bg-gradient-to-r md:from-blue-500 md:to-cyan-500 p-4 md:p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                            <div>
                                <h2 class="text-lg md:text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 rounded-full p-1.5 md:p-2 mr-2 md:mr-3">
                                        <UserIcon class="h-6 w-6 text-white" />
                                    </div>
                                    User Directory
                                </h2>
                                <p class="text-blue-100 text-xs md:text-sm mt-1">Complete list of system users</p>
                            </div>
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <MagnifyingGlassIcon class="h-5 w-5 text-white/60" />
                                </div>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search users..."
                                    class="block w-full pl-10 pr-3 py-2 border border-white/30 rounded-lg bg-white/10 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed divide-y divide-gray-200">
                            <thead class="bg-gray-50 md:bg-gradient-to-r md:from-gray-50 md:to-gray-100 hidden lg:table-header-group">
                                <tr>
                                    <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-1 mr-2">
                                                <IdentificationIcon class="h-4 w-4 text-white" />
                                            </div>
                                            User ID
                                        </div>
                                    </th>
                                    <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-1 mr-2">
                                                <UserIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Username
                                        </div>
                                    </th>
                                    <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-1 mr-2">
                                                <BadgeCheckIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Official Name
                                        </div>
                                    </th>
                                    <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-1 mr-2">
                                                <BriefcaseIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Position
                                        </div>
                                    </th>
                                    <th class="px-8 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <div class="bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full p-1 mr-2">
                                                <CogIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 md:divide-y divide-transparent md:divide-gray-200">
                                <tr v-for="user in filteredUsers" :key="user.id" class="block md:table-row bg-white rounded-xl border border-gray-200 shadow-none mb-3 md:bg-transparent md:border-0 md:rounded-none md:shadow-none hover:bg-white/50 transition-colors duration-150">
                                    <td class="px-4 py-3 md:px-8 md:py-6 whitespace-normal md:whitespace-nowrap block md:table-cell">
                                        <div class="flex items-center justify-between md:justify-start">
                                            <div class="md:hidden text-xs font-medium text-gray-500 mr-3">User ID</div>
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg px-3 py-2">
                                                <span class="text-white font-mono font-semibold text-sm">{{ user.user_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 md:px-8 md:py-6 whitespace-normal md:whitespace-nowrap block md:table-cell">
                                        <div class="flex items-center justify-between md:justify-start">
                                            <div class="md:hidden text-xs font-medium text-gray-500 mr-3">Username</div>
                                            <div class="flex items-center">
                                                <div class="bg-gray-100 rounded-full p-2 mr-3">
                                                    <UserIcon class="h-5 w-5 text-gray-600" />
                                                </div>
                                                <span class="text-gray-900 font-medium text-lg break-words">{{ user.username }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 md:px-8 md:py-6 whitespace-normal block md:table-cell overflow-hidden">
                                        <div class="flex items-center justify-between md:justify-start min-w-0 overflow-hidden">
                                            <div class="md:hidden text-xs font-medium text-gray-500 mr-3">Official Name</div>
                                            <div class="flex items-center min-w-0 overflow-hidden">
                                                <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-2 mr-3">
                                                    <BadgeCheckIcon class="h-5 w-5 text-white" />
                                                </div>
                                                <span class="text-gray-900 font-semibold text-lg break-words max-w-full overflow-hidden">{{ user.official_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 md:px-8 md:py-6 whitespace-normal md:whitespace-nowrap block md:table-cell">
                                        <div class="flex items-center justify-between md:justify-start">
                                            <div class="md:hidden text-xs font-medium text-gray-500 mr-3">Position</div>
                                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-indigo-500 to-blue-500 text-white border border-indigo-300 shadow-md whitespace-normal break-words">
                                                <BriefcaseIcon class="h-4 w-4 mr-2" />
                                                {{ user.official_title || 'No Position' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 md:px-8 md:py-6 whitespace-normal md:whitespace-nowrap text-left md:text-right block md:table-cell">
                                        <div class="md:hidden text-xs font-medium text-gray-500 mb-1">Actions</div>
                                        <div class="grid grid-cols-2 gap-2 w-full md:w-auto md:flex md:flex-wrap md:gap-2 md:justify-end">
                                            <Link :href="`/user/${user.user_id}/edit`" class="inline-flex items-center justify-center w-full md:w-auto px-3 py-1.5 rounded-lg border text-xs sm:text-sm bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100" title="Edit User">
                                                <PencilIcon class="h-4 w-4 mr-1.5" />
                                                <span class="hidden sm:inline">Edit</span>
                                            </Link>
                                            <Link :href="`/system-security/amend-password?search_user_id=${user.user_id}`" class="inline-flex items-center justify-center w-full md:w-auto px-3 py-1.5 rounded-lg border text-xs sm:text-sm bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100" title="Change Password">
                                                <KeyIcon class="h-4 w-4 mr-1.5" />
                                                <span class="hidden sm:inline">Password</span>
                                            </Link>
                                            <Link href="/system-security/define-access" class="inline-flex items-center justify-center w-full md:w-auto px-3 py-1.5 rounded-lg border text-xs sm:text-sm bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100" title="Define Access">
                                                <LockClosedIcon class="h-4 w-4 mr-1.5" />
                                                <span class="hidden sm:inline">Access</span>
                                            </Link>
                                            <button @click="confirmDelete(user.user_id)" class="inline-flex items-center justify-center w-full md:w-auto px-3 py-1.5 rounded-lg border text-xs sm:text-sm bg-red-50 text-red-700 border-red-200 hover:bg-red-100" title="Delete User">
                                                <TrashIcon class="h-4 w-4 mr-1.5" />
                                                <span class="hidden sm:inline">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 md:bg-gradient-to-r md:from-gray-100 md:to-gray-200 rounded-full p-6 mb-4">
                                                <UserIcon class="h-12 w-12 text-gray-400" />
                                            </div>
                                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Users Found</h3>
                                            <p class="text-gray-500 mb-4">Start by adding your first user to the system</p>
                                            <Link href="/user/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow hover:from-blue-700 hover:to-indigo-700 transition-colors duration-200">
                                                <UserAddIcon class="h-5 w-5 mr-2" />
                                                Add First User
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.links && users.links.length > 3" class="p-6 bg-gray-50 border-t border-gray-200">
                        <nav class="flex justify-center" aria-label="Pagination">
                            <div class="flex space-x-2">
                                <div v-for="(link, i) in users.links" :key="i">
                                    <span v-if="link.url === null" 
                                          class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed"
                                          v-html="link.label">
                                    </span>
                                    <Link v-else 
                                          :href="link.url"
                                          :class="{
                                              'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow': link.active, 
                                              'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 shadow': !link.active
                                          }"
                                          class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 transition-colors duration-200" 
                                          v-html="link.label">
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
    TrashIcon, 
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
import Swal from 'sweetalert2';

export default {
    components: {
        AppLayout,
        Head,
        Link,
        UserIcon, 
        UserGroupIcon, 
        UserAddIcon, 
        PencilIcon, 
        TrashIcon, 
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
        confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this user. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(`/user/${userId}`);
                }
            });
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