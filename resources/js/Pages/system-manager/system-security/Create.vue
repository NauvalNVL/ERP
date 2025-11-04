<template>
    <AppLayout header="Create User">
        <Head title="Create User" />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <UserAddIcon class="h-8 w-8 text-blue-600 mr-3" />
                            Create New User
                        </h1>
                        <p class="text-gray-600 mt-2">Buat user baru untuk sistem ERP</p>
                    </div>
                </div>

                <form @submit.prevent="submitForm" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- User Information -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <UserCircleIcon class="h-6 w-6 text-blue-500 mr-2" />
                                User Information
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- User ID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        User ID <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           v-model="form.user_id" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="Contoh: USER001"
                                           required>
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           v-model="form.username" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="Username untuk login"
                                           required>
                                </div>

                                <!-- Official Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Official Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           v-model="form.official_name" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="Nama lengkap"
                                           required>
                                </div>

                                <!-- Official Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Official Title
                                    </label>
                                    <input type="text" 
                                           v-model="form.official_title" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="Jabatan">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <PhoneIcon class="h-6 w-6 text-green-500 mr-2" />
                                Contact Information
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- Mobile Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Mobile Number
                                    </label>
                                    <input type="tel" 
                                           v-model="form.mobile_number" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="08xxxxxxxxxx">
                                </div>

                                <!-- Official Tel -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Official Telephone
                                    </label>
                                    <input type="tel" 
                                           v-model="form.official_tel" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="021xxxxxxx">
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <RadioGroup v-model="form.status">
                                        <div class="flex space-x-4">
                                            <RadioGroupOption v-for="option in statusOptions" :key="option.value" :value="option.value" v-slot="{ active, checked }">
                                                <div :class="[active ? 'ring-2 ring-blue-500' : '', checked ? 'bg-blue-600 text-white' : 'bg-white text-gray-900', 'relative flex cursor-pointer rounded-lg px-5 py-4 shadow-md focus:outline-none border border-gray-200']">
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div class="text-sm">
                                                                <RadioGroupLabel as="p" :class="checked ? 'text-white' : 'text-gray-900'" class="font-medium">
                                                                    {{ option.label }}
                                                                </RadioGroupLabel>
                                                            </div>
                                                        </div>
                                                        <div v-show="checked" class="shrink-0 text-white">
                                                            <CheckIcon class="h-6 w-6" />
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

                    <!-- Password Settings -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <KeyIcon class="h-6 w-6 text-purple-500 mr-2" />
                            Password Settings
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password Expiry -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Expiry (Days)
                                </label>
                                <input type="number" 
                                       v-model="form.password_expiry_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                       placeholder="90"
                                       min="0">
                            </div>

                            <!-- Amend Expired Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Allow Amend Expired Password
                                </label>
                                <SwitchGroup>
                                    <div class="flex items-center">
                                        <Switch v-model="amendPasswordEnabled"
                                            :class="[amendPasswordEnabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                            <span aria-hidden="true" :class="[amendPasswordEnabled ? 'translate-x-6' : 'translate-x-1', 'inline-block h-4 w-4 rounded-full bg-white shadow transform transition ease-in-out duration-200']" />
                                        </Switch>
                                        <SwitchLabel as="span" class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">{{ amendPasswordEnabled ? 'Enabled' : 'Disabled' }}</span>
                                        </SwitchLabel>
                                    </div>
                                </SwitchGroup>
                            </div>
                        </div>
                    </div>

                    <!-- User Permissions Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                        <div class="flex items-center mb-4">
                            <ShieldCheckIcon class="h-8 w-8 text-blue-600 mr-3" />
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">User Permissions</h3>
                                <p class="text-sm text-gray-600">Permission akan diatur setelah user dibuat</p>
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-blue-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Cara Mengatur Permission:</h4>
                                    <ol class="text-sm text-gray-600 space-y-1">
                                        <li class="flex items-center">
                                            <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs flex items-center justify-center mr-2 font-medium">1</span>
                                            Buat user baru dengan mengisi form ini
                                        </li>
                                        <li class="flex items-center">
                                            <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs flex items-center justify-center mr-2 font-medium">2</span>
                                            User akan dibuat tanpa permission apapun
                                        </li>
                                        <li class="flex items-center">
                                            <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs flex items-center justify-center mr-2 font-medium">3</span>
                                            Gunakan menu <strong>"Define User Access Permission"</strong> untuk mengatur permission
                                        </li>
                                    </ol>
                                    
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                            <p class="text-sm text-yellow-800">
                                                <strong>Penting:</strong> User baru tidak akan memiliki akses ke menu apapun sampai permission diatur melalui menu "Define User Access Permission".
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <Link href="/user" 
                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            <XIcon class="h-4 w-4 mr-2" />
                            Cancel
                        </Link>
                        <button type="submit" 
                            class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            <SaveIcon class="h-4 w-4 mr-2" />
                            Create User
                        </button>
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
} from '@heroicons/vue/outline'

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
