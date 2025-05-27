<template>
    <AppLayout header="Form Pendaftaran User Baru">
        <Head title="Create User" />
        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg p-8">
                <div v-if="Object.keys($page.props.errors).length > 0" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i> Terdapat kesalahan dalam input data:
                    <ul class="mt-2 list-disc list-inside">
                        <li v-for="(error, index) in Object.values($page.props.errors)" :key="index">{{ error }}</li>
                    </ul>
                </div>

                <div v-if="$page.props.flash.error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-blue-200 pb-3">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>Form Pendaftaran User Baru
                </h2>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Section Informasi Utama -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                            <i class="fas fa-id-card mr-2"></i>Informasi Utama
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User ID -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-blue-500 mr-1"></i>User ID
                                </label>
                                <input type="text" v-model="form.user_id" id="user_id" 
                                    :class="{'border-red-500': $page.props.errors.user_id}"
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: EMP001">
                                <p v-if="$page.props.errors.user_id" class="text-red-500 text-sm mt-1 animate-pulse">
                                    {{ $page.props.errors.user_id }}
                                </p>
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-circle text-blue-500 mr-1"></i>Username
                                </label>
                                <input type="text" v-model="form.username" id="username" 
                                    :class="{'border-red-500': $page.props.errors.username}"
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: johndoe">
                                <p v-if="$page.props.errors.username" class="text-red-500 text-sm mt-1 animate-pulse">
                                    {{ $page.props.errors.username }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section Data Pribadi -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                            <i class="fas fa-address-book mr-2"></i>Data Pribadi
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Official Name -->
                            <div>
                                <label for="official_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-signature text-blue-500 mr-1"></i>Nama Resmi
                                </label>
                                <input type="text" v-model="form.official_name" id="official_name" 
                                    :class="{'border-red-500': $page.props.errors.official_name}"
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: John Doe">
                                <p v-if="$page.props.errors.official_name" class="text-red-500 text-sm mt-1 animate-pulse">
                                    {{ $page.props.errors.official_name }}
                                </p>
                            </div>

                            <!-- Official Title -->
                            <div>
                                <label for="official_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-briefcase text-blue-500 mr-1"></i>Jabatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.official_title" id="official_title" required
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: Manager Produksi">
                            </div>

                            <!-- Mobile Number -->
                            <div>
                                <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-mobile-alt text-blue-500 mr-1"></i>Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.mobile_number" id="mobile_number" required
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: 08123456789">
                            </div>

                            <!-- Official Tel -->
                            <div>
                                <label for="official_tel" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone-alt text-blue-500 mr-1"></i>Telepon Kantor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.official_tel" id="official_tel" required
                                    class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: 0211234567">
                            </div>
                        </div>
                    </div>

                    <!-- Section Pengaturan Akun -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                            <i class="fas fa-cogs mr-2"></i>Pengaturan Akun
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-lock text-blue-500 mr-1"></i>Status Akun
                                </label>
                                <select v-model="form.status" id="status" 
                                    class="form-select w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all">
                                    <option value="A" class="text-green-600">🟢 Active</option>
                                    <option value="O" class="text-red-600">🔴 Obsolate</option>
                                </select>
                            </div>

                            <!-- Password Expiry Date -->
                            <div>
                                <label for="password_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-times text-blue-500 mr-1"></i>Masa Berlaku Password
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="number" v-model="form.password_expiry_date" id="password_expiry_date" 
                                        class="form-input w-32 rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                        min="0">
                                    <span class="text-sm text-gray-500">hari (0 = tidak kadaluarsa)</span>
                                </div>
                            </div>

                            <!-- Amend Expired Password -->
                            <div>
                                <label for="amend_expired_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-key text-blue-500 mr-1"></i>Perbarui Password Kadaluarsa
                                </label>
                                <select v-model="form.amend_expired_password" id="amend_expired_password" 
                                    class="form-select w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all">
                                    <option value="Yes">Ya</option>
                                    <option value="No">Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <Link href="/user" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-all">
                            <i class="fas fa-times mr-2"></i>Batal
                        </Link>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
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
    data() {
        return {
            form: {
                user_id: '',
                username: '',
                official_name: '',
                official_title: '',
                mobile_number: '',
                official_tel: '',
                status: 'A',
                password_expiry_date: 0,
                amend_expired_password: 'Yes'
            }
        }
    },
    methods: {
        submitForm() {
            this.$inertia.post('/user', this.form);
        }
    }
}
</script>

<style scoped>
.animate-pulse {
    animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style> 