<template>
    <AppLayout header="User List">
        <Head title="User Management" />
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Animated Bubbles Background -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="bubble bubble-1"></div>
                <div class="bubble bubble-2"></div>
                <div class="bubble bubble-3"></div>
                <div class="bubble bubble-4"></div>
                <div class="bubble bubble-5"></div>
                <div class="bubble bubble-6"></div>
                <div class="bubble bubble-7"></div>
                <div class="bubble bubble-8"></div>
            </div>
            <div class="max-w-7xl mx-auto relative z-10">
                <!-- Header Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8" style="background: linear-gradient(90deg, #2563eb 0%, #4f46e5 50%, #9333ea 100%);">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mr-4">
                                    <UserGroupIcon class="h-8 w-8 text-white" />
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-white mb-1">User Management</h1>
                                    <p class="text-blue-100">Manage system users and their information</p>
                                </div>
                            </div>
                            <Link href="/user/create"
                                class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-xl shadow-lg hover:bg-white/30 focus:outline-none focus:ring-4 focus:ring-white/30 transition-all duration-300 transform hover:scale-105">
                                <UserAddIcon class="h-6 w-6 mr-3" />
                                Add New User
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <TransitionGroup name="fade" tag="div" class="mb-8">
                    <div v-if="$page.props.flash.success" key="success" class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg animate-fadeIn">
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

                    <div v-if="$page.props.flash.error" key="error" class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 p-6 rounded-xl shadow-lg animate-fadeIn">
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
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                <UserIcon class="h-6 w-6 text-white" />
                            </div>
                            User Directory
                        </h2>
                        <p class="text-blue-100 text-sm mt-1">Complete list of system users</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
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
                            <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-white/80 transition-all duration-300 hover:shadow-md">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg px-3 py-2">
                                                <span class="text-white font-mono font-semibold text-sm">{{ user.user_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-gray-100 rounded-full p-2 mr-3">
                                                <UserIcon class="h-5 w-5 text-gray-600" />
                                            </div>
                                            <span class="text-gray-900 font-medium text-lg">{{ user.username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-2 mr-3">
                                                <BadgeCheckIcon class="h-5 w-5 text-white" />
                                            </div>
                                            <span class="text-gray-900 font-semibold text-lg">{{ user.official_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-indigo-500 to-blue-500 text-white border border-indigo-300 shadow-md">
                                            <BriefcaseIcon class="h-4 w-4 mr-2" />
                                            {{ user.official_title || 'No Position' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <Menu as="div" class="relative inline-block text-left">
                                            <div>
                                                <MenuButton class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-600 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 transform hover:scale-105">
                                                    <DotsVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                                </MenuButton>
                                            </div>
                                            <transition enter-active-class="transition ease-out duration-200" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                                <MenuItems class="origin-top-right absolute right-0 mt-2 w-64 rounded-2xl shadow-2xl bg-white/90 backdrop-blur-sm ring-1 ring-black ring-opacity-5 focus:outline-none z-20 border border-white/20">
                                                    <div class="p-2">
                                                        <MenuItem v-slot="{ active }">
                                                            <Link :href="`/user/${user.user_id}/edit`" :class="[active ? 'bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-900' : 'text-gray-700', 'group flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 hover:scale-105']">
                                                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-1 mr-3">
                                                                    <PencilIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                                                </div>
                                                                <div>
                                                                    <p class="font-semibold">Edit User</p>
                                                                    <p class="text-xs opacity-75">Modify user details</p>
                                                                </div>
                                                            </Link>
                                                        </MenuItem>
                                                        <MenuItem v-slot="{ active }">
                                                            <Link :href="`/system-security/amend-password?search_user_id=${user.user_id}`" :class="[active ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-900' : 'text-gray-700', 'group flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 hover:scale-105']">
                                                                <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-1 mr-3">
                                                                    <KeyIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                                                </div>
                                                                <div>
                                                                    <p class="font-semibold">Change Password</p>
                                                                    <p class="text-xs opacity-75">Update user password</p>
                                                                </div>
                                                            </Link>
                                                        </MenuItem>
                                                        <MenuItem v-slot="{ active }">
                                                            <Link href="/system-security/define-access" :class="[active ? 'bg-gradient-to-r from-purple-50 to-pink-50 text-purple-900' : 'text-gray-700', 'group flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 hover:scale-105']">
                                                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-full p-1 mr-3">
                                                                    <LockClosedIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                                                </div>
                                                                <div>
                                                                    <p class="font-semibold">Define Access</p>
                                                                    <p class="text-xs opacity-75">Set user permissions</p>
                                                                </div>
                                                            </Link>
                                                        </MenuItem>
                                                    </div>
                                                    <div class="border-t border-gray-200 p-2">
                                                        <MenuItem v-slot="{ active }">
                                                            <button @click="confirmDelete(user.user_id)" :class="[active ? 'bg-gradient-to-r from-red-50 to-pink-50 text-red-900' : 'text-gray-700', 'group flex items-center px-4 py-3 text-sm w-full rounded-xl transition-all duration-200 hover:scale-105']">
                                                                <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-full p-1 mr-3">
                                                                    <TrashIcon class="h-4 w-4 text-white" aria-hidden="true" />
                                                                </div>
                                                                <div class="text-left">
                                                                    <p class="font-semibold">Delete User</p>
                                                                    <p class="text-xs opacity-75">Remove user permanently</p>
                                                                </div>
                                                            </button>
                                                        </MenuItem>
                                                    </div>
                                                </MenuItems>
                                            </transition>
                                        </Menu>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-full p-6 mb-4">
                                                <UserIcon class="h-12 w-12 text-gray-400" />
                                            </div>
                                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Users Found</h3>
                                            <p class="text-gray-500 mb-4">Start by adding your first user to the system</p>
                                            <Link href="/user/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
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
                    <div v-if="users.links && users.links.length > 3" class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
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
                                              'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg transform scale-105': link.active, 
                                              'bg-white text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 shadow-md hover:shadow-lg': !link.active
                                          }"
                                          class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 transition-all duration-300 hover:scale-105" 
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
    Menu, 
    MenuButton, 
    MenuItems, 
    MenuItem 
} from '@headlessui/vue';
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
    EllipsisVerticalIcon as DotsVerticalIcon
} from '@heroicons/vue/24/outline';
import Swal from 'sweetalert2';

export default {
    components: {
        AppLayout,
        Head,
        Link,
        Menu, 
        MenuButton, 
        MenuItems, 
        MenuItem,
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
        DotsVerticalIcon
    },
    props: {
        users: Object
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

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

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

/* Table row hover effects */
tr:hover {
    transform: translateY(-1px);
}

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