<template>
    <AppLayout header="Amend User Password">
        <Head title="Amend User Password" />
        <div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 border-b border-blue-200">
                     <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <KeyIcon class="h-6 w-6 text-blue-600 mr-3" />
                        Amend User Password
                    </h2>
                </div>

                <div class="p-8 space-y-8">
                    <!-- User Search Form -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <form @submit.prevent="searchUser" class="space-y-4">
                            <div>
                                <label for="search_user_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <SearchIcon class="h-5 w-5 text-gray-500 mr-2" />
                                    Search by User ID
                                </label>
                                <div class="flex gap-3">
                                    <input type="text" v-model="search_user_id" id="search_user_id" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Enter User ID..." required>
                                    <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                        <SearchIcon class="h-5 w-5 mr-2" />
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Feedback Messages -->
                    <div v-if="message" class="transition-all rounded-md p-4" :class="messageClass">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <component :is="messageIcon" class="h-5 w-5" :class="messageTextColor" aria-hidden="true" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium" :class="messageTextColor">{{ message }}</p>
                            </div>
                        </div>
                    </div>
                     <div v-if="$page.props.flash.success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm animate-fadeIn">
                        <div class="flex">
                            <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                            <span>{{ $page.props.flash.success }}</span>
                        </div>
                    </div>
                    <div v-if="$page.props.flash.error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm animate-fadeIn">
                        <div class="flex">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <span>{{ $page.props.flash.error }}</span>
                        </div>
                    </div>

                    <!-- Update Password Form -->
                    <form @submit.prevent="updatePassword" v-if="foundUser" class="space-y-6 pt-6 border-t">
                        <div class="flex items-center mb-4">
                            <UserCircleIcon class="h-8 w-8 text-gray-500 mr-4" />
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Update password for: <span class="text-blue-600">{{ foundUser.official_name }}</span>
                                </h3>
                                <p class="text-sm text-gray-500">User ID: {{ foundUser.user_id }}</p>
                            </div>
                        </div>

                        <input type="hidden" v-model="form.user_id">
                        
                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <LockClosedIcon class="h-4 w-4 text-gray-500 mr-1" />
                                New Password
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.new_password" id="new_password"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 pr-10"
                                    placeholder="Enter at least 8 characters"
                                    required
                                    minlength="8">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button @click="showPassword = !showPassword" type="button" class="text-gray-400 hover:text-gray-500" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                        <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                                        <EyeOffIcon v-else class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                            <div v-if="form.new_password" class="mt-2">
                                <div class="h-2 rounded-full bg-gray-200">
                                    <div class="h-2 rounded-full transition-all duration-300" :class="passwordStrength.color" :style="{ width: passwordStrength.width }"></div>
                                </div>
                                <p class="text-sm mt-1" :class="passwordStrength.textColor">{{ passwordStrength.label }}</p>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <LockClosedIcon class="h-4 w-4 text-gray-500 mr-1" />
                                Confirm New Password
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                    <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.new_password_confirmation" id="new_password_confirmation"
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 pr-10"
                                    placeholder="Re-type the password"
                                    required
                                    minlength="8">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button @click="showConfirmPassword = !showConfirmPassword" type="button" class="text-gray-400 hover:text-gray-500" :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                                        <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5" />
                                        <EyeOffIcon v-else class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <Link href="/user" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Back
                            </Link>
                            <button type="submit" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                                <RefreshIcon class="h-5 w-5 mr-2" />
                                Update Password
                            </button>
                        </div>
                    </form>
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
} from '@heroicons/vue/outline';

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
                        this.foundUser = response.data[0];
                        this.form.user_id = this.foundUser.user_id;
                        this.setMessage(`User '${this.foundUser.official_name}' found.`, 'success');
                    } else {
                        this.foundUser = null;
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
                this.messageClass = 'bg-green-50';
                this.messageIcon = CheckCircleIcon;
                this.messageTextColor = 'text-green-800';
            } else if (type === 'warning') {
                this.messageClass = 'bg-yellow-50';
                this.messageIcon = ExclamationCircleIcon;
                this.messageTextColor = 'text-yellow-800';
            } else if (type === 'error') {
                this.messageClass = 'bg-red-50';
                this.messageIcon = ExclamationCircleIcon;
                this.messageTextColor = 'text-red-800';
            } else {
                this.messageClass = 'bg-blue-50';
                this.messageIcon = InformationCircleIcon;
                this.messageTextColor = 'text-blue-800';
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
.animate-fadeIn {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style> 