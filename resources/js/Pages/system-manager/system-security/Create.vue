<template>
    <AppLayout header="Create User">
        <Head title="Create User" />
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 50%, #faf5ff 100%);">
            <div class="max-w-6xl mx-auto">
                <!-- Header Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8" style="background: linear-gradient(90deg, #2563eb 0%, #4f46e5 50%, #9333ea 100%);">
                        <div class="flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 mr-4">
                                <UserAddIcon class="h-8 w-8 text-white" />
                            </div>
                            <div class="text-center">
                                <h1 class="text-3xl font-bold text-white mb-2">Create New User - Modern UI</h1>
                                <p class="text-blue-100">Add new user to the ERP system with modern interface</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <form @submit.prevent="submitForm" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- User Information -->
                        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                        <UserCircleIcon class="h-6 w-6 text-white" />
                                    </div>
                                    User Information
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">Basic user details</p>
                            </div>
                            <div class="p-8">
                                <div class="space-y-6">
                                    <!-- User ID -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <IdentificationIcon class="h-5 w-5 text-white" />
                                            </div>
                                            User ID <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" 
                                               v-model="form.user_id" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Example: USER001"
                                               required>
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-2 mr-3">
                                                <AtSymbolIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Username <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" 
                                               v-model="form.username" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Login username"
                                               required>
                                    </div>

                                    <!-- Official Name -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-2 mr-3">
                                                <UserIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Name <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text" 
                                               v-model="form.official_name" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Full name"
                                               required>
                                    </div>

                                    <!-- Official Title -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 mr-3">
                                                <BriefcaseIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Title
                                        </label>
                                        <input type="text" 
                                               v-model="form.official_title" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Job position">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                        <PhoneIcon class="h-6 w-6 text-white" />
                                    </div>
                                    Contact Information
                                </h3>
                                <p class="text-emerald-100 text-sm mt-1">Contact details and status</p>
                            </div>
                            <div class="p-8">
                                <div class="space-y-6">
                                    <!-- Mobile Number -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full p-2 mr-3">
                                                <PhoneIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Mobile Number
                                        </label>
                                        <input type="tel" 
                                               v-model="form.mobile_number" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="08xxxxxxxxxx">
                                    </div>

                                    <!-- Official Tel -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <OfficeBuildingIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Telephone
                                        </label>
                                        <input type="tel" 
                                               v-model="form.official_tel" 
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="021xxxxxxx">
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-800 mb-4">
                                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full p-2 mr-3">
                                                <CogIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Status <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <RadioGroup v-model="form.status">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <RadioGroupOption v-for="option in statusOptions" :key="option.value" :value="option.value" v-slot="{ active, checked }">
                                                    <div :class="[active ? 'ring-4 ring-blue-300' : '', checked ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-blue-600' : 'bg-white text-gray-900 border-gray-200 hover:bg-gray-50', 'relative flex cursor-pointer rounded-xl px-6 py-4 shadow-lg focus:outline-none border-2 transition-all duration-300 transform hover:scale-105']">
                                                        <div class="flex w-full items-center justify-between">
                                                            <div class="flex items-center">
                                                                <div class="text-lg">
                                                                    <RadioGroupLabel as="p" :class="checked ? 'text-white' : 'text-gray-900'" class="font-semibold">
                                                                        {{ option.label }}
                                                                    </RadioGroupLabel>
                                                                </div>
                                                            </div>
                                                            <div v-show="checked" class="shrink-0">
                                                                <CheckIcon class="h-6 w-6 text-white" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </RadioGroupOption>
                                            </div>
                                        </RadioGroup>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Settings -->
                    <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <KeyIcon class="h-6 w-6 text-white" />
                                </div>
                                Password Settings
                            </h3>
                            <p class="text-purple-100 text-sm mt-1">Configure password policies</p>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Password Expiry -->
                                <div>
                                    <label class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-full p-2 mr-3">
                                            <CalendarIcon class="h-5 w-5 text-white" />
                                        </div>
                                        Password Expiry (Days)
                                    </label>
                                    <input type="number" 
                                           v-model="form.password_expiry_date" 
                                           class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                           placeholder="90"
                                           min="0">
                                </div>

                                <!-- Amend Expired Password -->
                                <div>
                                    <label class="flex items-center text-lg font-semibold text-gray-800 mb-4">
                                        <div class="bg-gradient-to-r from-pink-500 to-red-500 rounded-full p-2 mr-3">
                                            <ShieldCheckIcon class="h-5 w-5 text-white" />
                                        </div>
                                        Allow Amend Expired Password
                                    </label>
                                    <SwitchGroup>
                                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                            <Switch v-model="amendPasswordEnabled"
                                                :class="[amendPasswordEnabled ? 'bg-gradient-to-r from-pink-500 to-red-500' : 'bg-gray-300', 'relative inline-flex h-8 w-14 items-center rounded-full transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-pink-300']">
                                                <span aria-hidden="true" :class="[amendPasswordEnabled ? 'translate-x-7' : 'translate-x-1', 'inline-block h-6 w-6 rounded-full bg-white shadow-lg transform transition-transform duration-300 ease-in-out']" />
                                            </Switch>
                                            <SwitchLabel as="span" class="ml-4">
                                                <span class="text-lg font-semibold" :class="amendPasswordEnabled ? 'text-pink-600' : 'text-gray-600'">{{ amendPasswordEnabled ? 'Enabled' : 'Disabled' }}</span>
                                            </SwitchLabel>
                                        </div>
                                    </SwitchGroup>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Permissions Info -->
                    <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mr-4">
                                    <ShieldCheckIcon class="h-8 w-8 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">User Permissions</h3>
                                    <p class="text-indigo-100">Permissions will be configured after user creation</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-blue-500 rounded-full p-2">
                                            <ExclamationCircleIcon class="h-6 w-6 text-white" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">How to Configure Permissions:</h4>
                                        <ol class="text-gray-700 space-y-3">
                                            <li class="flex items-center">
                                                <span class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full text-sm flex items-center justify-center mr-3 font-semibold">1</span>
                                                <span class="text-base">Create new user by filling this form</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full text-sm flex items-center justify-center mr-3 font-semibold">2</span>
                                                <span class="text-base">User will be created without any permissions</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full text-sm flex items-center justify-center mr-3 font-semibold">3</span>
                                                <span class="text-base">Use <strong>"Define User Access Permission"</strong> menu to configure permissions</span>
                                            </li>
                                        </ol>
                                        
                                        <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-xl">
                                            <div class="flex items-center">
                                                <ExclamationCircleIcon class="h-6 w-6 text-yellow-600 mr-3" />
                                                <div>
                                                    <p class="font-semibold text-yellow-800 mb-1">Important Notice:</p>
                                                    <p class="text-yellow-700">
                                                        New users will not have access to any menu until permissions are configured through the "Define User Access Permission" menu.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 p-8">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <Link href="/user" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 transform hover:scale-105">
                                <XIcon class="h-6 w-6 mr-3" />
                                Cancel
                            </Link>
                            <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-lg font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transform hover:scale-105 transition-all duration-300">
                                <SaveIcon class="h-6 w-6 mr-3" />
                                Create User
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
} from '@headlessui/vue'
import { 
    UserIcon, 
    UserCircleIcon,
    UserPlusIcon as UserAddIcon,
    AtSymbolIcon,
    CheckBadgeIcon as BadgeCheckIcon,
    BriefcaseIcon,
    PhoneIcon,
    BuildingOfficeIcon as OfficeBuildingIcon,
    CogIcon,
    ShieldCheckIcon,
    CalendarIcon,
    KeyIcon,
    XMarkIcon as XIcon,
    ArrowDownTrayIcon as SaveIcon,
    IdentificationIcon,
    ExclamationCircleIcon,
    CheckIcon
} from '@heroicons/vue/24/outline'

export default {
    components: {
        Head,
        Link,
        AppLayout,
        RadioGroup, 
        RadioGroupLabel, 
        RadioGroupOption,
        Switch,
        SwitchGroup,
        SwitchLabel,
        UserIcon, 
        UserCircleIcon,
        UserAddIcon,
        AtSymbolIcon,
        BadgeCheckIcon,
        BriefcaseIcon,
        PhoneIcon,
        OfficeBuildingIcon,
        CogIcon,
        ShieldCheckIcon,
        CalendarIcon,
        KeyIcon,
        XIcon,
        SaveIcon,
        IdentificationIcon,
        ExclamationCircleIcon,
        CheckIcon
    },
    data() {
        return {
            statusOptions: [
                { label: 'Active', value: 'A' },
                { label: 'Obsolete', value: 'O' }
            ],
            form: {
                user_id: '',
                username: '',
                official_name: '',
                official_title: '',
                mobile_number: '',
                official_tel: '',
                status: 'A',
                password_expiry_date: 90,
                amend_expired_password: 'Yes'
            }
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
                _token: this.$page.props.csrf
            };
            
            this.$inertia.post('/user', formData, {
                preserveState: false,
                preserveScroll: true,
                onStart: () => {
                    console.log('Form submission started');
                },
                onSuccess: (page) => {
                    console.log('User created successfully');
                },
                onError: (errors) => {
                    console.error('Form submission errors:', errors);
                },
                onFinish: () => {
                    console.log('Form submission finished');
                }
            });
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

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

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

/* Mobile responsiveness */
@media (max-width: 640px) {
    .max-w-6xl {
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
    
    .grid-cols-1.lg\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
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

/* Form validation styles */
.form-input:invalid {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-input:valid {
    border-color: #10b981;
}

/* Loading state */
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
</style>
