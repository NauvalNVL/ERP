<template>
    <AppLayout header="Copy & Paste User Access Permission">
        <Head title="Copy & Paste User Access" />
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
            <div class="max-w-5xl mx-auto relative z-10">
                <!-- Header Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8" style="background: linear-gradient(90deg, #2563eb 0%, #4f46e5 50%, #9333ea 100%);">
                        <div class="flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 mr-4">
                                <CopyIcon class="h-8 w-8 text-white" />
                            </div>
                            <div class="text-center">
                                <h1 class="text-3xl font-bold text-white mb-2">Copy & Paste Permissions</h1>
                                <p class="text-blue-100">Transfer user access permissions between users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden mb-8">
                    <div class="p-8 space-y-8">
                        <!-- Copy from User ID Section -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/30">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4 rounded-lg mb-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                        <UserIcon class="h-6 w-6 text-white" />
                                    </div>
                                    Source User (Copy From)
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">Select user to copy permissions from</p>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-6">
                                <div class="flex-1">
                                    <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                            <IdentificationIcon class="h-5 w-5 text-white" />
                                        </div>
                                        User ID
                                    </label>
                                    <input 
                                        v-model="form.copyFromUserId" 
                                        type="text"
                                        class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                        placeholder="Enter source User ID..."
                                        @keyup.enter="searchCopyFromUser"
                                    >
                                </div>
                                <div class="flex items-end">
                                    <button 
                                        @click="searchCopyFromUser"
                                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-lg font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-all duration-300"
                                        :disabled="!form.copyFromUserId"
                                    >
                                        <SearchIcon class="h-6 w-6 mr-3" />
                                        Search User
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Display Copy From User Info -->
                        <div v-if="copyFromUser" class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 rounded-xl shadow-lg animate-fadeIn">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    {{ copyFromUser.official_name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="text-white">
                                    <h4 class="text-lg font-semibold">{{ copyFromUser.official_name }}</h4>
                                    <p class="text-blue-100">{{ copyFromUser.user_id }} • {{ copyFromUser.official_title || 'No Title' }}</p>
                                    <p class="text-xs text-blue-200 mt-1">✓ Source user selected</p>
                                </div>
                            </div>
                        </div>

                        <!-- Paste to User ID Section -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/30">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4 rounded-lg mb-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                        <UserIcon class="h-6 w-6 text-white" />
                                    </div>
                                    Target User (Paste To)
                                </h3>
                                <p class="text-green-100 text-sm mt-1">Select user to paste permissions to</p>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-6">
                                <div class="flex-1">
                                    <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 mr-3">
                                            <IdentificationIcon class="h-5 w-5 text-white" />
                                        </div>
                                        User ID
                                    </label>
                                    <input 
                                        v-model="form.pasteToUserId" 
                                        type="text"
                                        class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                        placeholder="Enter target User ID..."
                                        @keyup.enter="searchPasteToUser"
                                    >
                                </div>
                                <div class="flex items-end">
                                    <button 
                                        @click="searchPasteToUser"
                                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-lg font-semibold rounded-xl shadow-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-all duration-300"
                                        :disabled="!form.pasteToUserId"
                                    >
                                        <SearchIcon class="h-6 w-6 mr-3" />
                                        Search User
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Display Paste To User Info -->
                        <div v-if="pasteToUser" class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 rounded-xl shadow-lg animate-fadeIn">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    {{ pasteToUser.official_name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="text-white">
                                    <h4 class="text-lg font-semibold">{{ pasteToUser.official_name }}</h4>
                                    <p class="text-green-100">{{ pasteToUser.user_id }} • {{ pasteToUser.official_title || 'No Title' }}</p>
                                    <p class="text-xs text-green-200 mt-1">✓ Target user selected</p>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm to Paste Button -->
                        <div class="flex justify-center pt-8 border-t border-gray-200">
                            <button 
                                @click="confirmPaste"
                                class="inline-flex items-center px-12 py-5 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-xl font-bold rounded-2xl shadow-2xl hover:from-violet-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-violet-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-all duration-300"
                                :disabled="!copyFromUser || !pasteToUser || isProcessing"
                            >
                                <CheckIcon class="h-7 w-7 mr-4" />
                                {{ isProcessing ? 'Processing...' : 'Confirm Copy & Paste' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div v-if="message" class="mb-8">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 border-l-4 border-green-700 p-6 rounded-xl shadow-lg animate-fadeIn" v-if="messageType === 'success'">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <CheckCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">Success!</h3>
                                <p class="text-white">{{ message }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 border-l-4 border-red-600 p-6 rounded-xl shadow-lg animate-fadeIn" v-else>
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-2 mr-4">
                                <ExclamationCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">Error!</h3>
                                <p class="text-white">{{ message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash.success" class="mb-8">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 border-l-4 border-green-700 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-4">
                                <CheckCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">Success!</h3>
                                <p class="text-white">{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash.error" class="mb-8">
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 border-l-4 border-red-600 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-2 mr-4">
                                <ExclamationCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">Error!</h3>
                                <p class="text-white">{{ $page.props.flash.error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to User List -->
                <div class="text-center">
                    <Link href="/user" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white text-lg font-semibold rounded-xl shadow-lg hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 transform hover:scale-105 transition-all duration-300">
                        <ArrowLeftIcon class="h-6 w-6 mr-3" />
                        Back to User List
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
import { 
    UserIcon,
    IdentificationIcon,
    MagnifyingGlassIcon as SearchIcon,
    DocumentDuplicateIcon as CopyIcon,
    CheckIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

export default {
    components: {
        Head,
        Link,
        AppLayout,
        UserIcon,
        IdentificationIcon,
        SearchIcon,
        CopyIcon,
        CheckIcon,
        CheckCircleIcon,
        ExclamationCircleIcon,
        ArrowLeftIcon
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
            messageIcon: '',
            messageType: 'info'
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

            if (this.copyFromUser.user_id === this.pasteToUser.user_id) {
                this.showMessage('Cannot copy permissions to the same user', 'error');
                return;
            }

            this.isProcessing = true;

            try {
                // Get permissions from copy-from user
                const permissionsResponse = await axios.get(`/api/users/${this.copyFromUser.user_id}/permissions`);
                const permissions = permissionsResponse.data;

                // Paste permissions to paste-to user
                await axios.post(`/api/users/${this.pasteToUser.user_id}/permissions`, {
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
                const backendMsg = error?.response?.data?.message || error?.response?.data || error?.message || 'Error copying permissions';
                this.showMessage(backendMsg, 'error');
            } finally {
                this.isProcessing = false;
            }
        },
        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
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

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .max-w-5xl {
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