<template>
    <AppLayout header="Manajemen Password Pengguna">
        <Head title="Amend User Password" />
        <div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg p-8">
                <div class="flex items-center mb-8">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-key text-blue-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Password Pengguna</h1>
                </div>

                <!-- Form Pencarian User -->
                <div class="bg-blue-50 rounded-xl p-6 mb-8">
                    <form @submit.prevent="searchUser" class="space-y-4">
                        <div>
                            <label for="search_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search text-blue-500 mr-1"></i>Cari User ID
                            </label>
                            <div class="flex gap-3">
                                <input type="text" v-model="search_user_id" id="search_user_id" 
                                    class="form-input flex-1 rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan User ID..." required>
                                <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
                                    <i class="fas fa-search mr-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Form Ubah Password -->
                <div v-if="foundUser" class="bg-green-50 rounded-xl p-6 border-2 border-green-200">
                    <form @submit.prevent="updatePassword" class="space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user-check text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">User Ditemukan: 
                                <span class="text-blue-600">{{ foundUser.user_id }}</span>
                            </h3>
                        </div>

                        <input type="hidden" v-model="form.user_id">

                        <!-- Pesan Error -->
                        <div v-if="Object.keys($page.props.errors).length > 0" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <div v-for="(error, field) in $page.props.errors" :key="field">
                                <p>{{ error }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-blue-500 mr-1"></i>Password Baru
                            </label>
                            <input type="password" v-model="form.new_password" id="new_password" 
                                class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                placeholder="Masukkan password minimal 8 karakter"
                                required
                                minlength="8">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-blue-500 mr-1"></i>Konfirmasi Password
                            </label>
                            <input type="password" v-model="form.new_password_confirmation" id="new_password_confirmation" 
                                class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                placeholder="Ketik ulang password"
                                required
                                minlength="8">
                        </div>

                        <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-sync-alt mr-2"></i>Perbarui Password
                        </button>
                    </form>
                </div>

                <!-- Feedback Messages -->
                <div v-if="message" class="mt-6" :class="messageClass">
                    <i :class="messageIcon + ' mr-2'"></i>{{ message }}
                </div>

                <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>

                <!-- Kembali ke Daftar User -->
                <div class="mt-6 text-center">
                    <Link href="/user" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar User
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
        AppLayout,
        Head,
        Link
    },
    props: {
        user: Object
    },
    data() {
        return {
            search_user_id: this.user ? this.user.user_id : '',
            foundUser: this.user || null,
            form: {
                user_id: this.user ? this.user.user_id : '',
                new_password: '',
                new_password_confirmation: ''
            },
            message: null,
            messageClass: '',
            messageIcon: ''
        }
    },
    methods: {
        searchUser() {
            if (!this.search_user_id) return;
            
            // Reset message
            this.message = null;
            
            // Use axios to search for user
            axios.get(`/api/users?search=${this.search_user_id}`)
                .then(response => {
                    if (response.data && response.data.length > 0) {
                        this.foundUser = response.data[0];
                        this.form.user_id = this.foundUser.user_id;
                        this.message = `User ${this.foundUser.official_name} ditemukan.`;
                        this.messageClass = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-fade-in';
                        this.messageIcon = 'fas fa-check-circle';
                    } else {
                        this.foundUser = null;
                        this.message = `User dengan ID "${this.search_user_id}" tidak ditemukan.`;
                        this.messageClass = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg';
                        this.messageIcon = 'fas fa-exclamation-triangle';
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                    this.foundUser = null;
                    this.message = 'Terjadi kesalahan saat mencari user.';
                    this.messageClass = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg';
                    this.messageIcon = 'fas fa-times-circle';
                });
        },
        updatePassword() {
            if (!this.foundUser) return;
            
            this.$inertia.post('/api/users/update-password', this.form, {
                onSuccess: () => {
                    this.message = 'Password berhasil diperbarui.';
                    this.messageClass = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-fade-in';
                    this.messageIcon = 'fas fa-check-circle';
                    this.form.new_password = '';
                    this.form.new_password_confirmation = '';
                },
                onError: (errors) => {
                    this.message = Object.values(errors)[0];
                    this.messageClass = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg';
                    this.messageIcon = 'fas fa-times-circle';
                }
            });
        }
    }
}
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style> 