<template>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user-lock text-blue-500 mr-3"></i>Define User Access Permission
            </h2>

            <!-- Search Form -->
            <div class="mb-8">
                <form @submit.prevent="searchUser" class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cari User ID
                                <span class="text-xs text-gray-500">(Contoh: ADMIN001)</span>
                            </label>
                            <input type="text" v-model="form.user_id" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan User ID" required>
                        </div>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-all flex items-center">
                            <i class="fas fa-search mr-2"></i>Cari User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Permissions Form -->
            <div v-if="foundUser" class="bg-gray-50 p-6 rounded-xl">
                <form @submit.prevent="savePermissions" class="space-y-6">
                    <input type="hidden" v-model="form.user_id">
                    
                    <div class="flex items-center mb-6">
                        <img :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(foundUser.official_name) + '&amp;background=random'" 
                             class="w-12 h-12 rounded-full mr-4" alt="Avatar">
                        <div>
                            <h3 class="text-lg font-semibold">{{ foundUser.official_name }}</h3>
                            <p class="text-sm text-gray-600">{{ foundUser.user_id }}</p>
                        </div>
                    </div>

                    <!-- Permission Groups -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- System Manager -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="font-semibold text-lg mb-4 text-blue-600">
                                <i class="fas fa-cogs mr-2"></i>System Manager
                            </h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="system_manager" 
                                           id="system_manager" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="system_manager" class="ml-2 block text-sm text-gray-900">
                                        Akses Penuh System Manager
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Management -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="font-semibold text-lg mb-4 text-green-600">
                                <i class="fas fa-chart-line mr-2"></i>Sales Management
                            </h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="sales_dashboard" 
                                           id="sales_dashboard" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="sales_dashboard" class="ml-2 block text-sm text-gray-900">
                                        Dashboard Sales
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="sales_report" 
                                           id="sales_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="sales_report" class="ml-2 block text-sm text-gray-900">
                                        Laporan Penjualan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Material Management -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="font-semibold text-lg mb-4 text-purple-600">
                                <i class="fas fa-pallet mr-2"></i>Material Management
                            </h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="material_inventory" 
                                           id="material_inventory" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="material_inventory" class="ml-2 block text-sm text-gray-900">
                                        Manajemen Inventory
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="material_orders" 
                                           id="material_orders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="material_orders" class="ml-2 block text-sm text-gray-900">
                                        Manajemen Purchase Order
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Production Management -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="font-semibold text-lg mb-4 text-red-600">
                                <i class="fas fa-industry mr-2"></i>Production Management
                            </h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="permissions" value="production_schedule" 
                                           id="production_schedule" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                    <label for="production_schedule" class="ml-2 block text-sm text-gray-900">
                                        Jadwal Produksi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan Permissions
                        </button>
                    </div>
                </form>
            </div>

            <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
            </div>

            <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
            </div>

            <!-- Kembali ke Daftar User -->
            <div class="mt-6 text-center">
                <a @click="$inertia.visit(route('vue.system-security.index'))" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar User
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object
    },
    data() {
        return {
            form: {
                user_id: '',
            },
            foundUser: null,
            permissions: []
        }
    },
    mounted() {
        if (this.user) {
            this.foundUser = this.user;
            this.form.user_id = this.user.user_id;
            this.loadUserPermissions();
        }
    },
    methods: {
        searchUser() {
            // Pencarian user berdasarkan user_id
            axios.get(`/api/users/search?user_id=${this.form.user_id}`)
                .then(response => {
                    this.foundUser = response.data.user;
                    if (this.foundUser) {
                        this.loadUserPermissions();
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                });
        },
        loadUserPermissions() {
            // Load permissions pengguna
            if (!this.foundUser) return;
            
            axios.get(`/api/users/${this.foundUser.id}/permissions`)
                .then(response => {
                    this.permissions = response.data;
                })
                .catch(error => {
                    console.error('Error loading permissions:', error);
                });
        },
        savePermissions() {
            this.$inertia.put(route('vue.system-security.update-access', this.foundUser.id), {
                permissions: this.permissions
            });
        }
    }
}
</script> 