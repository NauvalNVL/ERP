<template>
    <AppLayout header="Amend User Password">
        <Head title="Amend User Password" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden no-overscroll gutter-stable">
            <div class="max-w-4xl mx-auto relative z-0">
                <!-- Floating background bubbles -->
                <div class="hidden md:block absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                    <div class="bubble bubble-1"></div>
                    <div class="bubble bubble-2"></div>
                    <div class="bubble bubble-4"></div>
                </div>
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white shadow-lg rounded-2xl border border-indigo-700 mb-6">
                    <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center">
                            <KeyIcon class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">Amend User Password</h2>
                            <p class="text-xs sm:text-sm text-indigo-100">Update user password securely</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="space-y-4 md:space-y-6 lg:space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 lg:gap-8">
                        <!-- Search Section -->
                        <div>
                            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden h-full flex flex-col" style="content-visibility:auto; contain-intrinsic-size: 1px 280px; contain: content;">
                                <div class="px-4 py-3 sm:px-6 border-b border-indigo-600 bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
                                    <h3 class="text-sm font-semibold flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/20">
                                            <SearchIcon class="h-4 w-4 text-white" />
                                        </span>
                                        <span>Find User</span>
                                    </h3>
                                    <p class="text-xs text-indigo-100 mt-1">Search by User ID</p>
                                </div>
                                <div class="p-4 sm:p-6 flex-1 flex flex-col justify-between">
                                    <form @submit.prevent="searchUser" class="space-y-3">
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <SearchIcon class="h-4 w-4 text-gray-400" />
                                            </div>
                                            <input
                                                type="text"
                                                v-model="search_user_id"
                                                id="search_user_id"
                                                class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-indigo-200 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                placeholder="Enter User ID..."
                                                required
                                            >
                                        </div>
                                        <button
                                            type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                                        >
                                            <SearchIcon class="h-4 w-4 mr-2" />
                                            Search User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- User Guide Section -->
                        <div>
                            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden h-full flex flex-col">
                                <div class="px-4 py-3 sm:px-6 border-b border-indigo-200 bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
                                    <h3 class="text-sm font-semibold text-white">User Guide</h3>
                                    <p class="text-xs text-indigo-100 mt-1">Quick guide to use the password change screen.</p>
                                </div>
                                <div class="p-4 sm:p-6 text-sm text-gray-700 space-y-2 flex-1">
                                    <p class="leading-relaxed">
                                        1. Enter the <span class="font-semibold">User ID</span> in the <span class="font-semibold">Find User</span> box and click <span class="font-semibold">Search User</span>.
                                    </p>
                                    <p class="leading-relaxed">
                                        2. If the user is found, their information and the <span class="font-semibold">Update Password</span> form will appear below.
                                    </p>
                                    <p class="leading-relaxed">
                                        3. Type and confirm the new password (minimum 8 characters), then click <span class="font-semibold">Update Password</span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Update Section -->
                    <div>
                        <!-- Feedback Messages -->
                        <div class="space-y-3 mb-4 no-anchor">
                            <div v-if="message" :class="messageClass">
                                <div class="flex items-center px-4 py-3 rounded-lg shadow-sm">
                                    <div class="flex-shrink-0">
                                        <component :is="messageIcon" class="h-5 w-5" :class="messageTextColor" />
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium" :class="messageTextColor">{{ message }}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="$page.props.flash.success"
                                class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm"
                            >
                                <div class="flex items-center">
                                    <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                                    <span class="text-sm font-medium">{{ $page.props.flash.success }}</span>
                                </div>
                            </div>
                            <div
                                v-if="$page.props.flash.error"
                                class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm"
                            >
                                <div class="flex items-center">
                                    <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                                    <span class="text-sm font-medium">{{ $page.props.flash.error }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Update Password Form -->
                        <div v-if="foundUser" class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 360px; contain: content;">
                            <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 px-4 py-3 sm:px-6 border-b border-indigo-700">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-white/20 flex items-center justify-center">
                                        <UserCircleIcon class="h-5 w-5 text-white" />
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-semibold text-white">
                                            {{ foundUser.official_name }}
                                        </h3>
                                        <p class="text-xs text-indigo-100">User ID: {{ foundUser.user_id }}</p>
                                    </div>
                                </div>
                            </div>

                            <form @submit.prevent="updatePassword" class="p-4 sm:p-6 space-y-5">

                                <input type="hidden" v-model="form.user_id">
                                
                                <!-- New Password -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="new_password" class="flex items-center text-sm sm:text-base font-semibold text-gray-800 mb-2">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 mr-2">
                                                <LockClosedIcon class="h-4 w-4 text-indigo-600" />
                                            </span>
                                            <span>New Password</span>
                                        </label>
                                        <div class="relative">
                                            <input
                                                :type="showPassword ? 'text' : 'password'"
                                                v-model="form.new_password"
                                                id="new_password"
                                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                placeholder="Enter at least 8 characters"
                                                required
                                                minlength="8"
                                            >
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button
                                                    @click="showPassword = !showPassword"
                                                    type="button"
                                                    class="text-gray-400 hover:text-indigo-600 transition-colors duration-150"
                                                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                                >
                                                    <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                                                    <EyeOffIcon v-else class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                        <div v-if="form.new_password" class="mt-3">
                                            <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-500 ease-out" :class="passwordStrength.color" :style="{ width: passwordStrength.width }"></div>
                                            </div>
                                            <p class="text-xs mt-2 font-medium" :class="passwordStrength.textColor">Password Strength: {{ passwordStrength.label }}</p>
                                        </div>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div>
                                        <label for="new_password_confirmation" class="flex items-center text-sm sm:text-base font-semibold text-gray-800 mb-2">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 mr-2">
                                                <LockClosedIcon class="h-4 w-4 text-indigo-600" />
                                            </span>
                                            <span>Confirm New Password</span>
                                        </label>
                                        <div class="relative">
                                            <input
                                                :type="showConfirmPassword ? 'text' : 'password'"
                                                v-model="form.new_password_confirmation"
                                                id="new_password_confirmation"
                                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                placeholder="Re-type the password"
                                                required
                                                minlength="8"
                                            >
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button
                                                    @click="showConfirmPassword = !showConfirmPassword"
                                                    type="button"
                                                    class="text-gray-400 hover:text-indigo-600 transition-colors duration-150"
                                                    :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'"
                                                >
                                                    <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5" />
                                                    <EyeOffIcon v-else class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Password Match Indicator -->
                                        <div v-if="form.new_password_confirmation" class="mt-3">
                                            <div v-if="form.new_password === form.new_password_confirmation" class="flex items-center text-green-600">
                                                <CheckCircleIcon class="h-5 w-5 mr-2" />
                                                <span class="text-sm font-medium">Passwords match</span>
                                            </div>
                                            <div v-else class="flex items-center text-red-600">
                                                <ExclamationCircleIcon class="h-5 w-5 mr-2" />
                                                <span class="text-sm font-medium">Passwords do not match</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3 pt-5 border-t border-gray-200">
                                    <Link
                                        href="/user"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                                    >
                                        <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                        Back to Users
                                    </Link>
                                    <button
                                        type="submit"
                                        :disabled="!form.new_password || !form.new_password_confirmation || form.new_password !== form.new_password_confirmation"
                                        class="flex-1 inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <RefreshIcon class="h-5 w-5 mr-2" />
                                        Update Password
                                    </button>
                                </div>
                            </form>
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
import axios from 'axios';
import { 
    KeyIcon, 
    MagnifyingGlassIcon as SearchIcon,
    UserCircleIcon,
    LockClosedIcon,
    ArrowPathIcon as RefreshIcon,
    ArrowLeftIcon,
    EyeIcon,
    EyeSlashIcon as EyeOffIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline';

export default {
    components: {
        AppLayout,
        Head,
        Link,
        KeyIcon, 
        SearchIcon,
        UserCircleIcon,
        LockClosedIcon,
        RefreshIcon,
        ArrowLeftIcon,
        EyeIcon,
        EyeOffIcon,
        CheckCircleIcon,
        ExclamationCircleIcon,
        InformationCircleIcon
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
            messageIcon: null,
            messageTextColor: '',
            showPassword: false,
            showConfirmPassword: false,
            passwordStrength: {
                score: 0,
                label: 'Weak',
                color: 'bg-red-500',
                textColor: 'text-red-700',
                width: '0%'
            },
        }
    },
    watch: {
        'form.new_password'(newPassword) {
            this.updatePasswordStrength(newPassword);
        }
    },
    methods: {
        searchUser() {
            if (!this.search_user_id) return;
            this.message = null;
            axios.get(`/api/users?search=${this.search_user_id}`)
                .then(response => {
                    if (response.data && response.data.length > 0) {
                        const user = response.data[0];
                        if (user.status !== 'A') {
                            this.foundUser = null;
                            this.form.user_id = '';
                            this.setMessage(`User "${user.user_id}" is inactive or obsolete and cannot be updated here.`, 'error');
                            return;
                        }
                        this.foundUser = user;
                        this.form.user_id = user.user_id;
                        this.setMessage(`User '${user.official_name}' found.`, 'success');
                    } else {
                        this.foundUser = null;
                        this.form.user_id = '';
                        this.setMessage(`User with ID "${this.search_user_id}" not found.`, 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                    this.foundUser = null;
                    this.setMessage('An error occurred while searching for the user.', 'error');
                });
        },
        updatePassword() {
            if (!this.foundUser) return;
            
            // Add CSRF token to form data
            const formData = {
                ...this.form,
                _token: this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            };
            
            this.$inertia.post('/system-security/update-password', formData, {
                onSuccess: () => {
                    this.form.new_password = '';
                    this.form.new_password_confirmation = '';
                },
                onError: (errors) => {
                    console.error('Update password errors:', errors);
                    this.setMessage('Failed to update password', 'error');
                }
            });
        },
        setMessage(text, type = 'info') {
            this.message = text;
            if (type === 'success') {
                this.messageClass = 'bg-gradient-to-r from-green-600 to-emerald-600 border-l-4 border-green-700';
                this.messageIcon = CheckCircleIcon;
                this.messageTextColor = 'text-white';
            } else if (type === 'warning') {
                this.messageClass = 'bg-gradient-to-r from-yellow-500 to-orange-500 border-l-4 border-yellow-600';
                this.messageIcon = ExclamationCircleIcon;
                this.messageTextColor = 'text-white';
            } else if (type === 'error') {
                this.messageClass = 'bg-gradient-to-r from-red-500 to-pink-500 border-l-4 border-red-600';
                this.messageIcon = ExclamationCircleIcon;
                this.messageTextColor = 'text-white';
            } else {
                this.messageClass = 'bg-gradient-to-r from-blue-500 to-indigo-500 border-l-4 border-blue-600';
                this.messageIcon = InformationCircleIcon;
                this.messageTextColor = 'text-white';
            }
        },
        updatePasswordStrength(password) {
            let score = 0;
            if (!password) {
                this.passwordStrength = { score: 0, label: '', color: 'bg-gray-200', textColor: 'text-gray-500', width: '0%' };
                return;
            }

            if (password.length >= 8) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            let label = '';
            let color = '';
            let textColor = '';
            let width = (score / 5) * 100 + '%';

            switch (score) {
                case 0:
                case 1:
                case 2:
                    label = 'Weak';
                    color = 'bg-red-500';
                    textColor = 'text-red-700';
                    break;
                case 3:
                    label = 'Medium';
                    color = 'bg-yellow-500';
                    textColor = 'text-yellow-700';
                    break;
                case 4:
                    label = 'Strong';
                    color = 'bg-blue-500';
                    textColor = 'text-blue-700';
                    break;
                case 5:
                    label = 'Very Strong';
                    color = 'bg-green-500';
                    textColor = 'text-green-700';
                    break;
            }
            
            this.passwordStrength = { score, label, color, textColor, width };
        }
    }
}
</script>

<style scoped>
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

/* Custom gradient backgrounds */
.bg-gradient-to-br {
    background: linear-gradient(135deg, var(--tw-gradient-stops));
}

/* Remove global transitions to avoid layout jank */
/* Intentionally left empty */

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

/* Password strength indicator animation */
.password-strength-bar {
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Button loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .max-w-4xl {
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

/* Prevent scroll jumping utilities */
.no-overscroll {
    overscroll-behavior: contain;
    overscroll-behavior-y: contain;
}

.no-anchor {
    overflow-anchor: none;
}

.gutter-stable {
    scrollbar-gutter: stable both-edges;
}
</style>