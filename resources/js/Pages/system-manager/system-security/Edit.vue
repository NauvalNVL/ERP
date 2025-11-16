<template>
    <AppLayout header="Edit User">
        <Head title="Edit User" />
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Animated Bubbles Background -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none hidden">
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
                <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-4 sm:p-6" style="background: linear-gradient(90deg, #2563eb 0%, #4f46e5 50%, #9333ea 100%);">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
                            <div class="flex items-center">
                                <div class="bg-white/20 rounded-full p-2 sm:p-3 mr-3 sm:mr-4">
                                    <UserIcon class="h-8 w-8 text-white" />
                                </div>
                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1">Edit User</h1>
                                    <p class="text-blue-100 text-xs sm:text-sm">Update user information and settings</p>
                                </div>
                            </div>
                            <div class="w-full sm:w-auto text-left sm:text-right mt-3 sm:mt-0">
                                <div class="bg-white/20 rounded-xl p-3 sm:p-4">
                                    <p class="text-sm text-blue-200 mb-1">User ID</p>
                                    <p class="text-2xl font-bold text-white truncate">{{ user.user_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <div v-if="Object.keys($page.props.errors).length > 0" class="mb-8">
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 border-l-4 border-red-600 p-6 rounded-xl shadow">
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-2 mr-4">
                                <ExclamationCircleIcon class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">Validation Errors</h3>
                                <ul class="mt-2 space-y-1 text-white">
                                    <li v-for="(error, index) in Object.values($page.props.errors)" :key="index" class="flex items-center">
                                        <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white shadow-md rounded-2xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-600 to-gray-700 p-4 md:p-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <div class="bg-white/20 rounded-full p-2 mr-3">
                                <IdentificationIcon class="h-6 w-6 text-white" />
                            </div>
                            User Information
                        </h2>
                        <p class="text-slate-200 text-sm mt-1">Update user details and account settings</p>
                    </div>
                    <div class="p-4 md:p-8">

                        <form @submit.prevent="submitForm" class="space-y-8">
                            <!-- Basic Information Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                        <UserIcon class="h-5 w-5 text-white" />
                                    </div>
                                    Basic Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- User ID -->
                                    <div>
                                        <label for="user_id" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full p-1 mr-2">
                                                <IdentificationIcon class="h-4 w-4 text-white" />
                                            </div>
                                            User ID
                                        </label>
                                        <input type="text" v-model="form.user_id" id="user_id" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white"
                                            required>
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label for="username" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-1 mr-2">
                                                <AtSymbolIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Username
                                        </label>
                                        <input type="text" v-model="form.username" id="username" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Details Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 mr-3">
                                        <UserCircleIcon class="h-5 w-5 text-white" />
                                    </div>
                                    Personal Details
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Official Name -->
                                    <div>
                                        <label for="official_name" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-1 mr-2">
                                                <UserCircleIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Official Name
                                        </label>
                                        <input type="text" v-model="form.official_name" id="official_name" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white"
                                            required>
                                    </div>

                                    <!-- Official Title -->
                                    <div>
                                        <label for="official_title" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-1 mr-2">
                                                <BriefcaseIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Position
                                        </label>
                                        <input type="text" v-model="form.official_title" id="official_title" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-full p-2 mr-3">
                                        <PhoneIcon class="h-5 w-5 text-white" />
                                    </div>
                                    Contact Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Mobile Number -->
                                    <div>
                                        <label for="mobile_number" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-full p-1 mr-2">
                                                <PhoneIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Mobile Number
                                        </label>
                                        <input type="text" v-model="form.mobile_number" id="mobile_number" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white">
                                    </div>

                                    <!-- Official Tel -->
                                    <div>
                                        <label for="official_tel" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-full p-1 mr-2">
                                                <OfficeBuildingIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Office Phone
                                        </label>
                                        <input type="text" v-model="form.official_tel" id="official_tel" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full p-2 mr-3">
                                        <LockClosedIcon class="h-5 w-5 text-white" />
                                    </div>
                                    Security Settings
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Password -->
                                    <div>
                                        <label for="password" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full p-1 mr-2">
                                                <LockClosedIcon class="h-4 w-4 text-white" />
                                            </div>
                                            New Password
                                        </label>
                                        <p class="text-xs text-gray-500 mb-2">Leave empty if you don't want to change the password</p>
                                        <div class="relative">
                                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password" 
                                                class="block w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button @click="showPassword = !showPassword" type="button" class="text-gray-400 hover:text-purple-500 transition-colors" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                                    <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                                                    <EyeOffIcon v-else class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                        <div v-if="form.password" class="mt-3">
                                            <div class="h-3 rounded-full bg-gray-200 overflow-hidden">
                                                <div class="h-3 rounded-full transition-all duration-500" :class="passwordStrength.color" :style="{ width: passwordStrength.width }"></div>
                                            </div>
                                            <p class="text-sm mt-2 font-medium" :class="passwordStrength.textColor">{{ passwordStrength.label }}</p>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label for="password_confirmation" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full p-1 mr-2">
                                                <LockClosedIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Confirm Password
                                        </label>
                                        <div class="relative">
                                            <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" id="password_confirmation" 
                                                class="block w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button @click="showConfirmPassword = !showConfirmPassword" type="button" class="text-gray-400 hover:text-indigo-500 transition-colors" :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                                                    <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5" />
                                                    <EyeOffIcon v-else class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Settings Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full p-2 mr-3">
                                        <ShieldCheckIcon class="h-5 w-5 text-white" />
                                    </div>
                                    Account Settings
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full p-1 mr-2">
                                                <ShieldCheckIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Account Status
                                        </label>
                                        <RadioGroup v-model="form.status" class="mt-2">
                                            <RadioGroupLabel class="sr-only">Choose an account status</RadioGroupLabel>
                                            <div class="grid grid-cols-2 gap-3">
                                                <RadioGroupOption as="template" v-for="option in statusOptions" :key="option.label" :value="option.value" v-slot="{ active, checked }">
                                                    <div :class="[checked ? 'bg-gradient-to-r from-emerald-500 to-teal-500 border-transparent text-white shadow' : 'bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 hover:border-emerald-300', 'border rounded-xl py-3 px-3 md:py-4 md:px-4 flex items-center justify-center text-sm font-semibold cursor-pointer focus:outline-none transition-colors duration-200']">
                                                        <RadioGroupLabel as="span">{{ option.label }}</RadioGroupLabel>
                                                    </div>
                                                </RadioGroupOption>
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <!-- Password Expiry Date -->
                                    <div>
                                        <label for="password_expiry_date" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full p-1 mr-2">
                                                <CalendarIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Password Validity (days)
                                        </label>
                                        <input type="number" v-model="form.password_expiry_date" id="password_expiry_date" 
                                            class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-200 text-gray-900 bg-gray-50 hover:bg-white"
                                            min="0">
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <!-- Amend Expired Password -->
                                    <div>
                                        <label for="amend_expired_password" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full p-1 mr-2">
                                                <KeyIcon class="h-4 w-4 text-white" />
                                            </div>
                                            Update Expired Password
                                        </label>
                                        <SwitchGroup as="div" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                                            <div>
                                                <SwitchLabel as="span" class="text-sm font-medium text-gray-900">
                                                    Allow password updates when expired
                                                </SwitchLabel>
                                                <p class="text-xs text-gray-500 mt-1">Enable users to update their own expired passwords</p>
                                            </div>
                                            <Switch v-model="amendPasswordEnabled" :class="[amendPasswordEnabled ? 'bg-gradient-to-r from-cyan-500 to-blue-500' : 'bg-gray-300', 'relative inline-flex flex-shrink-0 h-7 w-12 border-2 border-transparent rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500']">
                                                <span aria-hidden="true" :class="[amendPasswordEnabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-6 w-6 rounded-full bg-white shadow ring-0 transition-transform']" />
                                            </Switch>
                                        </SwitchGroup>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-200">
                                <Link href="/user" 
                                    class="inline-flex justify-center items-center px-5 py-3 sm:px-8 sm:py-4 bg-gray-500 text-white text-lg font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 hover:bg-gray-600">
                                    <XIcon class="h-6 w-6 mr-3" />
                                    Cancel
                                </Link>
                                <button type="submit" 
                                    class="inline-flex justify-center items-center px-5 py-3 sm:px-8 sm:py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-lg font-semibold rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-colors duration-200">
                                    <SaveIcon class="h-6 w-6 mr-3" />
                                    Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { 
    RadioGroup, 
    RadioGroupLabel, 
    RadioGroupOption, 
    Switch, 
    SwitchGroup, 
    SwitchLabel 
} from '@headlessui/vue';
import { 
    UserIcon, 
    UserCircleIcon,
    AtSymbolIcon,
    BriefcaseIcon,
    PhoneIcon,
    BuildingOfficeIcon as OfficeBuildingIcon,
    ShieldCheckIcon,
    CalendarIcon,
    KeyIcon,
    XMarkIcon as XIcon,
    ArrowDownTrayIcon as SaveIcon,
    IdentificationIcon,
    ExclamationCircleIcon,
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon as EyeOffIcon
} from '@heroicons/vue/24/outline';

export default {
    components: {
        AppLayout,
        Head,
        Link,
        RadioGroup, 
        RadioGroupLabel, 
        RadioGroupOption,
        Switch,
        SwitchGroup,
        SwitchLabel,
        UserIcon, 
        UserCircleIcon,
        AtSymbolIcon,
        BriefcaseIcon,
        PhoneIcon,
        OfficeBuildingIcon,
        ShieldCheckIcon,
        CalendarIcon,
        KeyIcon,
        XIcon,
        SaveIcon,
        IdentificationIcon,
        ExclamationCircleIcon,
        LockClosedIcon,
        EyeIcon,
        EyeOffIcon
    },
    props: {
        user: Object
    },
    data() {
        return {
            statusOptions: [
                { label: 'Active', value: 'A' },
                { label: 'Obsolete', value: 'O' }
            ],
            showPassword: false,
            showConfirmPassword: false,
            passwordStrength: {
                score: 0,
                label: 'Weak',
                color: 'bg-red-500',
                textColor: 'text-red-700',
                width: '0%'
            },
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
    watch: {
        'form.password'(newPassword) {
            this.updatePasswordStrength(newPassword);
        }
    },
    computed: {
        amendPasswordEnabled: {
            get() {
                return this.form.amend_expired_password === 'Yes';
            },
            set(value) {
                this.form.amend_expired_password = value ? 'Yes' : 'No';
            }
        }
    },
    methods: {
        submitForm() {
            // Add CSRF token to form data
            const formData = {
                ...this.form,
                _token: this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            };
            
            this.$inertia.put(`/user/${this.user.id}`, formData, {
                preserveState: true,
                preserveScroll: true,
                onError: (errors) => {
                    console.error('Update user errors:', errors);
                }
            });
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
    
    .grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Enhanced form styling */
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Password strength indicator */
.password-strength {
    transition: all 0.5s ease;
}

/* Switch enhanced styling */
.switch-enhanced {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Radio button enhanced styling */
.radio-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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