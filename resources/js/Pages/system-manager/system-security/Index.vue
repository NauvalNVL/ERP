<template>
    <AppLayout header="Daftar Pengguna Sistem">
        <Head title="User Management" />
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-xl rounded-lg p-6">
                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
                        <i class="fas fa-users-cog text-blue-600 mr-2"></i>Daftar Pengguna Sistem
                    </h2>
                    <Link href="/user/create"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all flex items-center cursor-pointer">
                        <i class="fas fa-user-plus mr-2"></i>Tambah User Baru
                    </Link>
                </div>

                <!-- Success/Error Messages -->
                <div v-if="$page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-id-card mr-1"></i>User ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-circle mr-1"></i>Username
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-signature mr-1"></i>Nama Resmi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-briefcase mr-1"></i>Jabatan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i>Aksi
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ user.official_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ user.official_title || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-3">
                                        <Link :href="`/user/${user.id}/edit`" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors cursor-pointer"
                                           title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <a @click="confirmDelete(user.id)" 
                                           class="text-red-600 hover:text-red-900 transition-colors cursor-pointer"
                                           title="Hapus User">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <Link :href="`/system-security/amend-password?search_user_id=${user.user_id}`"
                                           class="text-green-600 hover:text-green-900 transition-colors cursor-pointer"
                                           title="Ubah Password">
                                            <i class="fas fa-key"></i>
                                        </Link>
                                        <Link href="/system-security/define-access"
                                           class="text-purple-600 hover:text-purple-900 transition-colors cursor-pointer"
                                           title="Define Access">
                                            <i class="fas fa-user-lock"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="users.data.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-database mr-2"></i>Tidak ada data pengguna
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.links && users.links.length > 3" class="mt-6">
                    <div class="flex flex-wrap justify-between mt-8">
                        <div v-for="(link, i) in users.links" :key="i" class="mb-1">
                            <a v-if="link.url === null" 
                              class="px-4 py-2 mx-1 text-gray-500 bg-white rounded-md cursor-not-allowed"
                              v-html="link.label">
                            </a>
                            <Link v-else 
                              :href="link.url"
                              :class="{'bg-blue-600 text-white': link.active}"
                              class="px-4 py-2 mx-1 bg-white text-gray-700 hover:bg-blue-600 hover:text-white rounded-md" 
                              v-html="link.label">
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout,
        Head,
        Link
    },
    props: {
        users: Object
    },
    methods: {
        confirmDelete(userId) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                this.$inertia.delete(`/user/${userId}`);
            }
        }
    }
}
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style> 