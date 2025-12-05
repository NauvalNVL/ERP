<template>
    <AppLayout header="Copy & Paste User Access Permission">
        <Head title="Copy & Paste User Access" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden">
            <div class="max-w-5xl w-full mx-auto relative z-0">
                <!-- Header Card -->
                <div class="bg-white/80 shadow-lg rounded-2xl border border-white/40 mb-6 backdrop-blur-sm">
                    <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 px-4 py-4 sm:px-6 sm:py-5">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center">
                                <CopyIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h1 class="text-lg sm:text-2xl font-bold leading-tight text-white">Copy &amp; Paste Permissions</h1>
                                <p class="text-xs sm:text-sm text-indigo-100">Transfer user access permissions between users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-4" style="content-visibility:auto; contain-intrinsic-size: 1px 560px; contain: content;">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 space-y-5">
                        <!-- Copy from User ID Section -->
                        <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-white">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50">
                                            <UserIcon class="h-4 w-4 text-indigo-600" />
                                        </span>
                                        <span>Source User (Copy From)</span>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">Select the user whose permissions you want to copy.</p>
                                </div>
                            </div>
                            <div class="mt-2 flex-1">
                                <label for="copyFromUserId" class="block text-xs font-medium text-gray-700">
                                    User ID
                                </label>
                                <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div class="relative flex-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <IdentificationIcon class="h-4 w-4 text-gray-400" />
                                        </div>
                                        <input 
                                            id="copyFromUserId"
                                            v-model="form.copyFromUserId" 
                                            type="text"
                                            class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-indigo-200 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                            placeholder="Enter source User ID..."
                                            @keyup.enter="searchCopyFromUser"
                                        >
                                    </div>
                                    <button 
                                        @click="searchCopyFromUser"
                                        class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="!form.copyFromUserId"
                                    >
                                        <SearchIcon class="h-4 w-4 mr-2" />
                                        <span>Search User</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Display Copy From User Info -->
                        <div v-if="copyFromUser" class="rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-500/80 flex items-center justify-center text-white font-semibold text-sm">
                                {{ ((copyFromUser.official_name || copyFromUser.user_id || '').charAt(0) || '').toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-indigo-900 truncate">{{ copyFromUser.official_name || copyFromUser.user_id }}</p>
                                <p class="text-xs text-indigo-800 truncate">{{ copyFromUser.user_id }} • {{ copyFromUser.official_title || 'No Title' }}</p>
                                <p class="text-[11px] text-indigo-700 mt-0.5">Source user selected</p>
                            </div>
                        </div>

                        <!-- Paste to User ID Section -->
                        <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-white">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50">
                                            <UserIcon class="h-4 w-4 text-indigo-600" />
                                        </span>
                                        <span>Target User (Paste To)</span>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">Select the user who will receive the copied permissions.</p>
                                </div>
                            </div>
                            <div class="mt-2 flex-1">
                                <label for="pasteToUserId" class="block text-xs font-medium text-gray-700">
                                    User ID
                                </label>
                                <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div class="relative flex-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <IdentificationIcon class="h-4 w-4 text-gray-400" />
                                        </div>
                                        <input 
                                            id="pasteToUserId"
                                            v-model="form.pasteToUserId" 
                                            type="text"
                                            class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-indigo-200 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                            placeholder="Enter target User ID..."
                                            @keyup.enter="searchPasteToUser"
                                        >
                                    </div>
                                    <button 
                                        @click="searchPasteToUser"
                                        class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="!form.pasteToUserId"
                                    >
                                        <SearchIcon class="h-4 w-4 mr-2" />
                                        <span>Search User</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Display Paste To User Info -->
                        <div v-if="pasteToUser" class="rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-500/80 flex items-center justify-center text-white font-semibold text-sm">
                                {{ ((pasteToUser.official_name || pasteToUser.user_id || '').charAt(0) || '').toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-indigo-900 truncate">{{ pasteToUser.official_name || pasteToUser.user_id }}</p>
                                <p class="text-xs text-indigo-800 truncate">{{ pasteToUser.user_id }} • {{ pasteToUser.official_title || 'No Title' }}</p>
                                <p class="text-[11px] text-indigo-700 mt-0.5">Target user selected</p>
                            </div>
                        </div>

                        <!-- Confirm to Paste Button -->
                        <div class="flex justify-center pt-5 border-t border-gray-200">
                            <button 
                                @click="confirmPaste"
                                class="inline-flex items-center px-6 py-2.5 md:px-8 md:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm md:text-base font-medium rounded-lg shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!copyFromUser || !pasteToUser || isProcessing"
                            >
                                <CheckIcon class="h-5 w-5 mr-2" />
                                {{ isProcessing ? 'Processing...' : 'Confirm Copy & Paste' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div v-if="message" class="mb-4">
                    <div
                        v-if="messageType === 'success'"
                        class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm"
                    >
                        <div class="flex items-center">
                            <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                            <p class="text-sm font-medium">{{ message }}</p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm"
                    >
                        <div class="flex items-center">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <p class="text-sm font-medium">{{ message }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash.success" class="mb-4">
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                            <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash.error" class="mb-4">
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <p class="text-sm font-medium">{{ $page.props.flash.error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Back to User List -->
                <div class="text-center mt-4">
                    <Link
                        href="/user"
                        class="inline-flex items-center px-4 py-2 md:px-5 md:py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        <ArrowLeftIcon class="h-5 w-5 mr-2" />
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
                const response = await axios.get(`/api/users/search/${encodeURIComponent(this.form.copyFromUserId)}`);
                const data = response.data;

                if (data && data.user) {
                    const user = data.user;
                    const userId = user.userID || user.user_id || '';

                    this.copyFromUser = {
                        id: user.id,
                        user_id: userId,
                        official_name: user.official_name ?? user.officialName ?? userId,
                        official_title: user.official_title ?? user.officialTitle ?? ''
                    };
                    this.showMessage('User found successfully', 'success');
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
                const response = await axios.get(`/api/users/search/${encodeURIComponent(this.form.pasteToUserId)}`);
                const data = response.data;

                if (data && data.user) {
                    const user = data.user;
                    const userId = user.userID || user.user_id || '';

                    this.pasteToUser = {
                        id: user.id,
                        user_id: userId,
                        official_name: user.official_name ?? user.officialName ?? userId,
                        official_title: user.official_title ?? user.officialTitle ?? ''
                    };
                    this.showMessage('User found successfully', 'success');
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
                // Minta backend untuk menyalin semua permissions langsung dari user sumber ke user target
                await axios.post('/api/users/copy-permissions', {
                    from_user_id: this.copyFromUser.user_id,
                    to_user_id: this.pasteToUser.user_id,
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

/* Smooth transitions (scoped to interactive elements) */
button, a, input, select, textarea, .transition, .transition-all {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
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