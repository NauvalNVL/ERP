<template>
    <AppLayout header="Copy & Paste User Access Permission">
        <Head title="Copy & Paste User Access" />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-copy text-blue-500 mr-3"></i>Copy & Paste User Access Permission
                </h2>

                <!-- Main Form -->
                <div class="space-y-6">
                    <!-- Copy from User ID Section -->
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700 w-40">Copy from User ID:</label>
                        <input 
                            v-model="form.copyFromUserId" 
                            type="text"
                            class="flex-1 max-w-xs px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter User ID"
                            @keyup.enter="searchCopyFromUser"
                        >
                        <button 
                            @click="searchCopyFromUser"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors flex items-center"
                            :disabled="!form.copyFromUserId"
                            title="Search User"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <!-- Display Copy From User Info -->
                    <div v-if="copyFromUser" class="ml-44 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-blue-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-800">{{ copyFromUser.official_name }}</p>
                                <p class="text-sm text-gray-600">{{ copyFromUser.user_id }} - {{ copyFromUser.official_title || 'No Title' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Paste to User ID Section -->
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700 w-40">Paste to User ID:</label>
                        <input 
                            v-model="form.pasteToUserId" 
                            type="text"
                            class="flex-1 max-w-xs px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter User ID"
                            @keyup.enter="searchPasteToUser"
                        >
                        <button 
                            @click="searchPasteToUser"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors flex items-center"
                            :disabled="!form.pasteToUserId"
                            title="Search User"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <!-- Display Paste To User Info -->
                    <div v-if="pasteToUser" class="ml-44 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-green-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-800">{{ pasteToUser.official_name }}</p>
                                <p class="text-sm text-gray-600">{{ pasteToUser.user_id }} - {{ pasteToUser.official_title || 'No Title' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm to Paste Button -->
                    <div class="flex justify-center mt-8">
                        <button 
                            @click="confirmPaste"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                            :disabled="!copyFromUser || !pasteToUser || isProcessing"
                        >
                            <i class="fas fa-check mr-2"></i>
                            {{ isProcessing ? 'Processing...' : 'Confirm to Paste' }}
                        </button>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div v-if="message" class="mt-6 p-4 rounded-lg" :class="messageClass">
                    <i :class="messageIcon + ' mr-2'"></i>{{ message }}
                </div>

                <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>

                <!-- Back to User List -->
                <div class="mt-8 text-center">
                    <Link href="/user" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                        <i class="fas fa-arrow-left mr-1"></i> Back to User List
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
        Head,
        Link,
        AppLayout
    },
    data() {
        return {
            form: {
                copyFromUserId: '',
                pasteToUserId: ''
            },
            copyFromUser: null,
            pasteToUser: null,
            isProcessing: false,
            message: '',
            messageClass: '',
            messageIcon: ''
        }
    },
    methods: {
        async searchCopyFromUser() {
            if (!this.form.copyFromUserId) {
                this.showMessage('Please enter Copy from User ID', 'error');
                return;
            }

            try {
                const response = await axios.get('/api/users', {
                    params: { search: this.form.copyFromUserId }
                });

                if (response.data && response.data.length > 0) {
                    const user = response.data.find(u => u.userID === this.form.copyFromUserId);
                    if (user) {
                        this.copyFromUser = {
                            id: user.id,
                            user_id: user.userID,
                            official_name: user.officialName,
                            official_title: user.officialTitle
                        };
                        this.showMessage('User found successfully', 'success');
                    } else {
                        this.copyFromUser = null;
                        this.showMessage('User ID not found', 'error');
                    }
                } else {
                    this.copyFromUser = null;
                    this.showMessage('User ID not found', 'error');
                }
            } catch (error) {
                console.error('Error searching user:', error);
                this.showMessage('Error searching user', 'error');
            }
        },
        async searchPasteToUser() {
            if (!this.form.pasteToUserId) {
                this.showMessage('Please enter Paste to User ID', 'error');
                return;
            }

            try {
                const response = await axios.get('/api/users', {
                    params: { search: this.form.pasteToUserId }
                });

                if (response.data && response.data.length > 0) {
                    const user = response.data.find(u => u.userID === this.form.pasteToUserId);
                    if (user) {
                        this.pasteToUser = {
                            id: user.id,
                            user_id: user.userID,
                            official_name: user.officialName,
                            official_title: user.officialTitle
                        };
                        this.showMessage('User found successfully', 'success');
                    } else {
                        this.pasteToUser = null;
                        this.showMessage('User ID not found', 'error');
                    }
                } else {
                    this.pasteToUser = null;
                    this.showMessage('User ID not found', 'error');
                }
            } catch (error) {
                console.error('Error searching user:', error);
                this.showMessage('Error searching user', 'error');
            }
        },
        async confirmPaste() {
            if (!this.copyFromUser || !this.pasteToUser) {
                this.showMessage('Please search both users first', 'error');
                return;
            }

            if (this.copyFromUser.id === this.pasteToUser.id) {
                this.showMessage('Cannot copy permissions to the same user', 'error');
                return;
            }

            this.isProcessing = true;

            try {
                // Get permissions from copy-from user
                const permissionsResponse = await axios.get(`/api/users/${this.copyFromUser.id}/permissions`);
                const permissions = permissionsResponse.data;

                // Paste permissions to paste-to user
                await axios.post(`/api/users/${this.pasteToUser.id}/permissions`, {
                    permissions: permissions
                });

                this.showMessage(
                    `Permissions successfully copied from ${this.copyFromUser.user_id} to ${this.pasteToUser.user_id}`,
                    'success'
                );

                // Reset form after successful paste
                setTimeout(() => {
                    this.resetForm();
                }, 2000);

            } catch (error) {
                console.error('Error copying permissions:', error);
                this.showMessage('Error copying permissions', 'error');
            } finally {
                this.isProcessing = false;
            }
        },
        showMessage(text, type) {
            this.message = text;
            if (type === 'success') {
                this.messageClass = 'bg-green-100 text-green-700 border border-green-200';
                this.messageIcon = 'fas fa-check-circle';
            } else {
                this.messageClass = 'bg-red-100 text-red-700 border border-red-200';
                this.messageIcon = 'fas fa-exclamation-circle';
            }
        },
        resetForm() {
            this.form.copyFromUserId = '';
            this.form.pasteToUserId = '';
            this.copyFromUser = null;
            this.pasteToUser = null;
            this.message = '';
        }
    }
}
</script>

<style scoped>
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>