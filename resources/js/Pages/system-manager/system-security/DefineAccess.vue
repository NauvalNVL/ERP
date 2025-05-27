<template>
    <AppLayout header="Define User Access Permission">
        <Head title="Define User Access" />
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
                                <p class="text-sm text-gray-600">{{ foundUser.user_id }} - {{ foundUser.official_title || 'No Title' }}</p>
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
                                        <input type="checkbox" v-model="permissions" value="system_manager_full" 
                                               id="system_manager_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="system_manager_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh System Manager
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="system_security" 
                                                id="system_security" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="system_security" class="ml-2 block text-sm text-gray-900">
                                                System Security
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="system_maintenance" 
                                                id="system_maintenance" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="system_maintenance" class="ml-2 block text-sm text-gray-900">
                                                System Maintenance
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="system_administrator" 
                                                id="system_administrator" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="system_administrator" class="ml-2 block text-sm text-gray-900">
                                                System Administrator
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="db_integrity" 
                                                id="db_integrity" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="db_integrity" class="ml-2 block text-sm text-gray-900">
                                                DB Integrity Check
                                            </label>
                                        </div>
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
                                        <input type="checkbox" v-model="permissions" value="sales_management_full" 
                                               id="sales_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="sales_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Sales Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="sales_dashboard" 
                                               id="sales_dashboard" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="sales_dashboard" class="ml-2 block text-sm text-gray-900">
                                            Dashboard Sales
                                        </label>
                                    </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="sales_orders" 
                                                id="sales_orders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="sales_orders" class="ml-2 block text-sm text-gray-900">
                                                Sales Orders
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="customer_management" 
                                                id="customer_management" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="customer_management" class="ml-2 block text-sm text-gray-900">
                                                Customer Management
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="sales_report" 
                                               id="sales_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="sales_report" class="ml-2 block text-sm text-gray-900">
                                                Sales Reports
                                        </label>
                                        </div>
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
                                        <input type="checkbox" v-model="permissions" value="material_management_full" 
                                               id="material_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Material Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="material_inventory" 
                                               id="material_inventory" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_inventory" class="ml-2 block text-sm text-gray-900">
                                                Inventory Management
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="material_orders" 
                                               id="material_orders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_orders" class="ml-2 block text-sm text-gray-900">
                                                Purchase Orders
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="material_suppliers" 
                                                id="material_suppliers" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="material_suppliers" class="ml-2 block text-sm text-gray-900">
                                                Supplier Management
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="material_report" 
                                                id="material_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="material_report" class="ml-2 block text-sm text-gray-900">
                                                Material Reports
                                        </label>
                                        </div>
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
                                        <input type="checkbox" v-model="permissions" value="production_management_full" 
                                               id="production_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="production_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Production Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="production_schedule" 
                                               id="production_schedule" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="production_schedule" class="ml-2 block text-sm text-gray-900">
                                                Production Schedule
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_workorders" 
                                                id="production_workorders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_workorders" class="ml-2 block text-sm text-gray-900">
                                                Work Orders
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_quality" 
                                                id="production_quality" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_quality" class="ml-2 block text-sm text-gray-900">
                                                Quality Control
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_report" 
                                                id="production_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_report" class="ml-2 block text-sm text-gray-900">
                                                Production Reports
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Management -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-yellow-600">
                                    <i class="fas fa-warehouse mr-2"></i>Warehouse Management
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="warehouse_management_full" 
                                               id="warehouse_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="warehouse_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Warehouse Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_inventory" 
                                                id="warehouse_inventory" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_inventory" class="ml-2 block text-sm text-gray-900">
                                                Warehouse Inventory
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_receiving" 
                                                id="warehouse_receiving" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_receiving" class="ml-2 block text-sm text-gray-900">
                                                Receiving & Putaway
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_shipping" 
                                                id="warehouse_shipping" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_shipping" class="ml-2 block text-sm text-gray-900">
                                                Shipping & Fulfillment
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_report" 
                                                id="warehouse_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_report" class="ml-2 block text-sm text-gray-900">
                                                Warehouse Reports
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Mining -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-indigo-600">
                                    <i class="fas fa-chart-pie mr-2"></i>Data Mining
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="data_mining_full" 
                                               id="data_mining_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="data_mining_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Data Mining
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_analytics" 
                                                id="data_analytics" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_analytics" class="ml-2 block text-sm text-gray-900">
                                                Analytics Dashboard
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_reports" 
                                                id="data_reports" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_reports" class="ml-2 block text-sm text-gray-900">
                                                Custom Reports
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_export" 
                                                id="data_export" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_export" class="ml-2 block text-sm text-gray-900">
                                                Data Export
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_forecasts" 
                                                id="data_forecasts" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_forecasts" class="ml-2 block text-sm text-gray-900">
                                                Forecasting Tools
                                            </label>
                                        </div>
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

                <div v-if="searchMessage" class="mt-6 py-4 px-6 rounded-lg" :class="searchMessageClass">
                    <i :class="searchMessageIcon + ' mr-2'"></i>{{ searchMessage }}
                </div>

                <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
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
            form: {
                user_id: '',
            },
            foundUser: null,
            permissions: [],
            searchMessage: null,
            searchMessageClass: '',
            searchMessageIcon: ''
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
            this.searchMessage = null;
            
            // Pencarian user berdasarkan user_id
            axios.get(`/api/users?search=${this.form.user_id}`)
                .then(response => {
                    if (response.data && response.data.length > 0) {
                        this.foundUser = response.data[0];
                        this.loadUserPermissions();
                        this.searchMessage = `User ${this.foundUser.official_name} ditemukan.`;
                        this.searchMessageClass = 'bg-green-100 text-green-700';
                        this.searchMessageIcon = 'fas fa-check-circle';
                    } else {
                        this.foundUser = null;
                        this.searchMessage = `User dengan ID "${this.form.user_id}" tidak ditemukan.`;
                        this.searchMessageClass = 'bg-yellow-100 text-yellow-700';
                        this.searchMessageIcon = 'fas fa-exclamation-triangle';
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                    this.foundUser = null;
                    this.searchMessage = 'Terjadi kesalahan saat mencari user.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
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
                    this.searchMessage = 'Terjadi kesalahan saat mengambil data permission.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
                });
        },
        savePermissions() {
            if (!this.foundUser) return;
            
            this.$inertia.post(`/api/users/${this.foundUser.id}/permissions`, {
                permissions: this.permissions
            }, {
                onSuccess: () => {
                    this.searchMessage = 'Permissions berhasil diperbarui.';
                    this.searchMessageClass = 'bg-green-100 text-green-700';
                    this.searchMessageIcon = 'fas fa-check-circle';
                },
                onError: () => {
                    this.searchMessage = 'Terjadi kesalahan saat menyimpan permissions.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
                }
            });
        },
        selectAllInCategory(category) {
            // This is a function that could be implemented to select all permissions in a category
            const categoryPermissions = this.getPermissionsByCategory(category);
            categoryPermissions.forEach(permission => {
                if (!this.permissions.includes(permission)) {
                    this.permissions.push(permission);
                }
            });
        },
        getPermissionsByCategory(category) {
            // This would return all permissions that belong to a specific category
            // Implementation would depend on how you structure your permissions
            return [];
        }
    }
}
</script> 

<style scoped>
.permission-group:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style> 