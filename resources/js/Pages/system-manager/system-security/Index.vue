<template>
    <AppLayout header="User List">
        <Head title="User Management" />
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 border-b border-blue-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0 flex items-center">
                            <UserGroupIcon class="h-6 w-6 text-blue-600 mr-2" />
                            User List
                        </h2>
                        <Link href="/user/create"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <UserAddIcon class="h-5 w-5 mr-2" />
                            Add New User
                        </Link>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <TransitionGroup name="fade" tag="div" class="px-6 pt-4">
                    <div v-if="$page.props.flash.success" key="success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm animate-fadeIn">
                        <div class="flex">
                            <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                            <span>{{ $page.props.flash.success }}</span>
                        </div>
                    </div>

                    <div v-if="$page.props.flash.error" key="error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm animate-fadeIn">
                        <div class="flex">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <span>{{ $page.props.flash.error }}</span>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Users Table -->
                <div class="px-6 pb-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <IdentificationIcon class="h-4 w-4 mr-1" />User ID
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <UserIcon class="h-4 w-4 mr-1" />Username
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <BadgeCheckIcon class="h-4 w-4 mr-1" />Official Name
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <BriefcaseIcon class="h-4 w-4 mr-1" />Position
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <CogIcon class="h-4 w-4 mr-1" />Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-medium">
                                        {{ user.user_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ user.username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ user.official_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ user.official_title || 'No Position' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <Menu as="div" class="relative inline-block text-left">
                                            <div>
                                                <MenuButton class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                                    <DotsVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                                </MenuButton>
                                            </div>
                                            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                                <MenuItems class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                    <div class="py-1">
                                                        <MenuItem v-slot="{ active }">
                                                            <Link :href="`/user/${user.id}/edit`" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
                                                                <PencilIcon class="mr-3 h-5 w-5 text-blue-400 group-hover:text-blue-500" aria-hidden="true" />
                                                                Edit User
                                                            </Link>
                                                        </MenuItem>
                                                        <MenuItem v-slot="{ active }">
                                                            <Link :href="`/system-security/amend-password?search_user_id=${user.user_id}`" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
                                                                <KeyIcon class="mr-3 h-5 w-5 text-green-400 group-hover:text-green-500" aria-hidden="true" />
                                                                Change Password
                                                            </Link>
                                                        </MenuItem>
                                                        <MenuItem v-slot="{ active }">
                                                            <Link href="/system-security/define-access" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
                                                                <LockClosedIcon class="mr-3 h-5 w-5 text-purple-400 group-hover:text-purple-500" aria-hidden="true" />
                                                                Define Access
                                                            </Link>
                                                        </MenuItem>
                                                    </div>
                                                    <div class="py-1">
                                                        <MenuItem v-slot="{ active }">
                                                            <button @click="confirmDelete(user.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm w-full']">
                                                                <TrashIcon class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" aria-hidden="true" />
                                                                Delete User
                                                            </button>
                                                        </MenuItem>
                                                    </div>
                                                </MenuItems>
                                            </transition>
                                        </Menu>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <UserIcon class="h-10 w-10 text-gray-300 mb-2" />
                                            <span>No user data available</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.links && users.links.length > 3" class="mt-6 flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <div v-for="(link, i) in users.links" :key="i" class="relative">
                                <span v-if="link.url === null" 
                                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-not-allowed"
                                      v-html="link.label">
                                </span>
                                <Link v-else 
                                      :href="link.url"
                                      :class="{'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active}"
                                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors" 
                                      v-html="link.label">
                                </Link>
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
} from '@heroicons/vue/outline';
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
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style> 