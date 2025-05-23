<template>
    <AppLayout header="Edit User">
        <Head title="Edit User" />
        <div class="container px-6 py-8 mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div v-if="Object.keys($page.props.errors).length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                    <ul>
                        <li v-for="(error, index) in Object.values($page.props.errors)" :key="index">{{ error }}</li>
                    </ul>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- User ID -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                        <input type="text" v-model="form.user_id" id="user_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" v-model="form.username" id="username" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Official Name -->
                    <div class="mb-4">
                        <label for="official_name" class="block text-sm font-medium text-gray-700">Nama Resmi</label>
                        <input type="text" v-model="form.official_name" id="official_name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Official Title -->
                    <div class="mb-4">
                        <label for="official_title" class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <input type="text" v-model="form.official_title" id="official_title" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-4">
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" v-model="form.mobile_number" id="mobile_number" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Official Tel -->
                    <div class="mb-4">
                        <label for="official_tel" class="block text-sm font-medium text-gray-700">Telepon Kantor</label>
                        <input type="text" v-model="form.official_tel" id="official_tel" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password 
                            <span class="text-xs text-gray-500">(Biarkan kosong jika tidak ingin mengubah password)</span>
                        </label>
                        <input type="password" v-model="form.password" id="password" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" v-model="form.password_confirmation" id="password_confirmation" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select v-model="form.status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="A">Active</option>
                            <option value="O">Obsolate</option>
                        </select>
                    </div>

                    <!-- Password Expiry Date -->
                    <div class="mb-4">
                        <label for="password_expiry_date" class="block text-sm font-medium text-gray-700">Masa Berlaku Password (hari)</label>
                        <input type="number" v-model="form.password_expiry_date" id="password_expiry_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            min="0">
                    </div>

                    <!-- Amend Expired Password -->
                    <div class="mb-4">
                        <label for="amend_expired_password" class="block text-sm font-medium text-gray-700">Perbarui Password Kadaluarsa</label>
                        <select v-model="form.amend_expired_password" id="amend_expired_password" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Yes">Ya</option>
                            <option value="No">Tidak</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a @click="$inertia.visit(route('vue.system-security.index'))" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout,
        Head
    },
    props: {
        user: Object
    },
    data() {
        return {
            form: {
                user_id: this.user.user_id,
                username: this.user.username,
                official_name: this.user.official_name,
                official_title: this.user.official_title || '',
                mobile_number: this.user.mobile_number || '',
                official_tel: this.user.official_tel || '',
                password: '',
                password_confirmation: '',
                status: this.user.status,
                password_expiry_date: this.user.password_expiry_date,
                amend_expired_password: this.user.amend_expired_password
            }
        }
    },
    methods: {
        submitForm() {
            this.$inertia.put(route('vue.system-security.update', this.user.id), this.form);
        }
    }
}
</script> 