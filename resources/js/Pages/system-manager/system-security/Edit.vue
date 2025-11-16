<template>
    <AppLayout header="Edit User">
        <Head title="Edit User" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="max-w-6xl w-full mx-auto">
                <!-- Header Card -->
                <div class="bg-white/80 shadow rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:via-indigo-600 md:to-purple-600 p-4 md:p-8">
                        <div class="flex items-center justify-center">
                            <div class="bg-white/20 rounded-full p-4 mr-4">
                                <UserIcon class="h-8 w-8 text-white" />
                            </div>
                            <div class="text-center">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Edit User</h1>
                                <p class="text-blue-100">Update existing user information with modern interface</p>
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

                <!-- Main Form -->
                <form @submit.prevent="submitForm" class="space-y-8 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 w-full">
                        <!-- Basic & Personal Information -->
                        <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 540px; contain: content;">
                            <div class="bg-blue-500 md:bg-gradient-to-r md:from-blue-500 md:to-cyan-500 p-4 md:p-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 rounded-full p-2 mr-3">
                                        <IdentificationIcon class="h-6 w-6 text-white" />
                                    </div>
                                    User Information
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">Basic user details</p>
                            </div>
                            <div class="p-4 md:p-8">
                                <div class="space-y-6">
                                    <!-- User ID -->
                                    <div>
                                        <label for="user_id" class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <IdentificationIcon class="h-5 w-5 text-white" />
                                            </div>
                                            User ID <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" v-model="form.user_id" id="user_id"
                                            class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                            required>
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label for="username" class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-2 mr-3">
                                                <AtSymbolIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Username <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" v-model="form.username" id="username"
                                            class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                            required>
                                    </div>

                                    <!-- Official Name -->
                                    <div>
                                        <label for="official_name" class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-2 mr-3">
                                                <UserCircleIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Name <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" v-model="form.official_name" id="official_name"
                                            class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                            required>
                                    </div>

                                    <!-- Official Title -->
                                    <div>
                                        <label for="official_title" class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 mr-3">
                                                <BriefcaseIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Position
                                        </label>
                                        <input type="text" v-model="form.official_title" id="official_title"
                                            class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Account Information -->
                        <div class="space-y-4 md:space-y-8">
                            <!-- Contact Information Section -->
                            <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 260px;">
                                <div class="bg-emerald-500 md:bg-gradient-to-r md:from-emerald-500 md:to-teal-500 p-4 md:p-6">
                                    <h3 class="text-xl font-semibold text-white flex items-center">
                                        <div class="bg-white/20 rounded-full p-2 mr-3">
                                            <PhoneIcon class="h-6 w-6 text-white" />
                                        </div>
                                        Contact Information
                                    </h3>
                                    <p class="text-emerald-100 text-sm mt-1">Contact details</p>
                                </div>
                                <div class="p-4 md:p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Mobile Number -->
                                        <div>
                                            <label for="mobile_number" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full p-2 mr-3">
                                                    <PhoneIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Mobile Number
                                            </label>
                                            <input type="text" v-model="form.mobile_number" id="mobile_number"
                                                class="block w-full px-4 md:px-6 py-3 md:py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white">
                                        </div>

                                        <!-- Official Tel -->
                                        <div>
                                            <label for="official_tel" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full p-2 mr-3">
                                                    <OfficeBuildingIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Office Phone
                                            </label>
                                            <input type="text" v-model="form.official_tel" id="official_tel"
                                                class="block w-full px-4 md:px-6 py-3 md:py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Settings Section -->
                            <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 260px;">
                                <div class="bg-cyan-500 md:bg-gradient-to-r md:from-cyan-500 md:to-blue-500 p-4 md:p-6">
                                    <h3 class="text-xl font-semibold text-white flex items-center">
                                        <div class="bg-white/20 rounded-full p-2 mr-3">
                                            <ShieldCheckIcon class="h-6 w-6 text-white" />
                                        </div>
                                        Account Settings
                                    </h3>
                                    <p class="text-cyan-100 text-sm mt-1">Status & password rules</p>
                                </div>
                                <div class="p-4 md:p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <div>
                                            <label for="status" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-4">
                                                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full p-2 mr-3">
                                                    <ShieldCheckIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Account Status
                                            </label>
                                            <RadioGroup v-model="form.status">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <RadioGroupOption v-for="option in statusOptions" :key="option.value" :value="option.value" v-slot="{ active, checked }">
                                                        <div :class="[active ? 'ring-2 ring-blue-300' : '', checked ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-blue-600' : 'bg-white text-gray-900 border-gray-200 hover:bg-gray-50', 'relative flex cursor-pointer rounded-xl px-4 md:px-6 py-3 md:py-4 shadow focus:outline-none border-2 transition-colors duration-200']">
                                                            <div class="flex w-full items-center justify-between">
                                                                <div class="flex items-center">
                                                                    <div class="text-sm md:text-base">
                                                                        <RadioGroupLabel as="p" :class="checked ? 'text-white' : 'text-gray-900'" class="font-semibold">
                                                                            {{ option.label }}
                                                                        </RadioGroupLabel>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </RadioGroupOption>
                                                </div>
                                            </RadioGroup>
                                        </div>

                                        <!-- Password Expiry Date -->
                                        <div>
                                            <label for="password_expiry_date" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full p-2 mr-3">
                                                    <CalendarIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Password Validity (days)
                                            </label>
                                            <input type="number" v-model="form.password_expiry_date" id="password_expiry_date"
                                                class="block w-full px-4 md:px-6 py-3 md:py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white"
                                                min="0">
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <!-- Amend Expired Password -->
                                        <div>
                                            <label for="amend_expired_password" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-pink-500 to-red-500 rounded-full p-2 mr-3">
                                                    <KeyIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Allow Amend Expired Password
                                            </label>
                                            <SwitchGroup>
                                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                                    <Switch v-model="amendPasswordEnabled"
                                                        :class="[amendPasswordEnabled ? 'bg-gradient-to-r from-pink-500 to-red-500' : 'bg-gray-300', 'relative inline-flex h-8 w-14 items-center rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-300']">
                                                        <span aria-hidden="true" :class="[amendPasswordEnabled ? 'translate-x-7' : 'translate-x-1', 'inline-block h-6 w-6 rounded-full bg-white transform transition-transform duration-300 ease-in-out']" />
                                                    </Switch>
                                                    <SwitchLabel as="span" class="ml-4">
                                                        <span class="text-sm md:text-lg font-semibold" :class="amendPasswordEnabled ? 'text-pink-600' : 'text-gray-600'">{{ amendPasswordEnabled ? 'Enabled' : 'Disabled' }}</span>
                                                    </SwitchLabel>
                                                </div>
                                            </SwitchGroup>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings Section -->
                            <div class="bg-white/80 shadow rounded-2xl border border-white/20 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 260px;">
                                <div class="bg-purple-500 md:bg-gradient-to-r md:from-purple-500 md:to-pink-500 p-4 md:p-6">
                                    <h3 class="text-xl font-semibold text-white flex items-center">
                                        <div class="bg-white/20 rounded-full p-2 mr-3">
                                            <LockClosedIcon class="h-6 w-6 text-white" />
                                        </div>
                                        Security Settings
                                    </h3>
                                    <p class="text-purple-100 text-sm mt-1">Update user password securely</p>
                                </div>
                                <div class="p-4 md:p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Password -->
                                        <div>
                                            <label for="password" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full p-2 mr-3">
                                                    <LockClosedIcon class="h-5 w-5 text-white" />
                                                </div>
                                                New Password
                                            </label>
                                            <p class="text-xs text-gray-500 mb-2">Leave empty if you don't want to change the password</p>
                                            <div class="relative">
                                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password"
                                                    class="block w-full px-4 md:px-6 py-3 md:py-4 pr-12 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white">
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
                                            <label for="password_confirmation" class="flex items-center text-sm md:text-lg font-semibold text-gray-800 mb-3">
                                                <div class="bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full p-2 mr-3">
                                                    <LockClosedIcon class="h-5 w-5 text-white" />
                                                </div>
                                                Confirm Password
                                            </label>
                                            <div class="relative">
                                                <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" id="password_confirmation"
                                                    class="block w-full px-4 md:px-6 py-3 md:py-4 pr-12 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white">
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
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white/80 shadow rounded-2xl border border-white/20 p-4 md:p-8" style="content-visibility:auto; contain-intrinsic-size: 1px 160px; contain: content;">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <Link href="/user"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                                <XIcon class="h-6 w-6 mr-3" />
                                Cancel
                            </Link>
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-lg font-semibold rounded-xl shadow hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                <SaveIcon class="h-6 w-6 mr-3" />
                                Update User
                            </button>
                        </div>
                    </div>
                </form>
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