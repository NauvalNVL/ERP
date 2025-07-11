<template>
    <AppLayout header="New User Registration">
        <Head title="Create User" />
        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 border-b border-blue-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <UserAddIcon class="h-6 w-6 text-blue-600 mr-2" />
                        New User Registration
                    </h2>
                </div>

                <div class="p-8">
                    <div v-if="Object.keys($page.props.errors).length > 0" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg animate-pulse-error">
                        <div class="flex">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <span class="font-medium">There are errors in your input:</span>
                        </div>
                        <ul class="mt-2 list-disc list-inside">
                            <li v-for="(error, index) in Object.values($page.props.errors)" :key="index">{{ error }}</li>
                        </ul>
                    </div>

                    <div v-if="$page.props.flash.error" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                        <div class="flex">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2" />
                            <span>{{ $page.props.flash.error }}</span>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-8">
                        <!-- Section Basic Information -->
                        <div class="space-y-6">
                            <div class="flex items-center border-b border-gray-200 pb-2">
                                <IdentificationIcon class="h-5 w-5 text-blue-600 mr-2" />
                                <h3 class="text-lg font-semibold text-blue-700">Basic Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- User ID -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <UserIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        User ID
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.user_id" id="user_id" 
                                            :class="{'ring-2 ring-red-500 border-red-500': $page.props.errors.user_id}"
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: EMP001">
                                    </div>
                                    <p v-if="$page.props.errors.user_id" class="mt-1 text-sm text-red-600 animate-pulse">
                                        {{ $page.props.errors.user_id }}
                                    </p>
                                </div>

                                <!-- Username -->
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <AtSymbolIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Username
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.username" id="username" 
                                            :class="{'ring-2 ring-red-500 border-red-500': $page.props.errors.username}"
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: johndoe">
                                    </div>
                                    <p v-if="$page.props.errors.username" class="mt-1 text-sm text-red-600 animate-pulse">
                                        {{ $page.props.errors.username }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Section Personal Data -->
                        <div class="space-y-6 pt-4">
                            <div class="flex items-center border-b border-gray-200 pb-2">
                                <UserCircleIcon class="h-5 w-5 text-blue-600 mr-2" />
                                <h3 class="text-lg font-semibold text-blue-700">Personal Data</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Official Name -->
                                <div>
                                    <label for="official_name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <BadgeCheckIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Official Name
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.official_name" id="official_name" 
                                            :class="{'ring-2 ring-red-500 border-red-500': $page.props.errors.official_name}"
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: John Doe">
                                    </div>
                                    <p v-if="$page.props.errors.official_name" class="mt-1 text-sm text-red-600 animate-pulse">
                                        {{ $page.props.errors.official_name }}
                                    </p>
                                </div>

                                <!-- Official Title -->
                                <div>
                                    <label for="official_title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <BriefcaseIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Position <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.official_title" id="official_title" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: Production Manager">
                                    </div>
                                </div>

                                <!-- Mobile Number -->
                                <div>
                                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <PhoneIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Mobile Number <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.mobile_number" id="mobile_number" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: 08123456789">
                                    </div>
                                </div>

                                <!-- Official Tel -->
                                <div>
                                    <label for="official_tel" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <OfficeBuildingIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Office Phone <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" v-model="form.official_tel" id="official_tel" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                            placeholder="Example: 0211234567">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Account Settings -->
                        <div class="space-y-6 pt-4">
                            <div class="flex items-center border-b border-gray-200 pb-2">
                                <CogIcon class="h-5 w-5 text-blue-600 mr-2" />
                                <h3 class="text-lg font-semibold text-blue-700">Account Settings</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <ShieldCheckIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Account Status
                                    </label>
                                    <RadioGroup v-model="form.status" class="mt-2">
                                        <RadioGroupLabel class="sr-only">Choose an account status</RadioGroupLabel>
                                        <div class="grid grid-cols-2 gap-3">
                                            <RadioGroupOption as="template" v-for="option in statusOptions" :key="option.label" :value="option.value" v-slot="{ active, checked }">
                                                <div :class="[checked ? 'bg-blue-600 border-transparent text-white hover:bg-blue-700' : 'bg-white border-gray-300 text-gray-900 hover:bg-gray-50', 'border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium cursor-pointer focus:outline-none transition-all']">
                                                    <RadioGroupLabel as="span">{{ option.label }}</RadioGroupLabel>
                                                </div>
                                            </RadioGroupOption>
                                        </div>
                                    </RadioGroup>
                                </div>

                                <!-- Password Expiry Date -->
                                <div>
                                    <label for="password_expiry_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <CalendarIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Password Validity Period
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <div class="relative rounded-md shadow-sm w-full">
                                            <input type="number" v-model="form.password_expiry_date" id="password_expiry_date" 
                                                class="appearance-none block w-32 px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                                min="0">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">days</span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">(0 = never expires)</span>
                                    </div>
                                </div>

                                <!-- Amend Expired Password -->
                                <div>
                                    <label for="amend_expired_password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <KeyIcon class="h-4 w-4 text-blue-500 mr-1" />
                                        Update Expired Password
                                    </label>
                                    <SwitchGroup as="div" class="flex items-center mt-3">
                                        <Switch v-model="amendPasswordEnabled" :class="[amendPasswordEnabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']">
                                            <span aria-hidden="true" :class="[amendPasswordEnabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                                        </Switch>
                                        <SwitchLabel as="span" class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Enabled</span>
                                        </SwitchLabel>
                                    </SwitchGroup>
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
                                Save
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
    ExclamationCircleIcon
} from '@heroicons/vue/outline';

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
        ExclamationCircleIcon
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
                password_expiry_date: 0,
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
            this.$inertia.post('/user', this.form);
        }
    }
}
</script>

<style scoped>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-pulse-error {
    animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    border-left-width: 4px;
}

.appearance-none:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style> 