<template>
    <AppLayout header="Edit User">
        <Head title="Edit User" />
        <div class="container px-6 py-8 mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 border-b border-blue-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <UserIcon class="h-6 w-6 text-blue-600 mr-2" />
                        Edit User
                    </h2>
                </div>

                <div class="p-8">
                    <div v-if="Object.keys($page.props.errors).length > 0" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg animate-pulse-error">
                        <div class="flex items-start">
                            <ExclamationCircleIcon class="h-5 w-5 text-red-500 mr-2 mt-0.5" />
                            <div>
                                <p class="font-medium">There are some issues with your input:</p>
                                <ul class="mt-2 list-disc list-inside pl-2 space-y-1">
                                    <li v-for="(error, index) in Object.values($page.props.errors)" :key="index">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User ID -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <IdentificationIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    User ID
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" v-model="form.user_id" id="user_id" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                                        required>
                                </div>
                    </div>

                    <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <AtSymbolIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Username
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="text" v-model="form.username" id="username" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                            required>
                                </div>
                            </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Official Name -->
                            <div>
                                <label for="official_name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <UserCircleIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Official Name
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="text" v-model="form.official_name" id="official_name" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                            required>
                                </div>
                    </div>

                    <!-- Official Title -->
                            <div>
                                <label for="official_title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <BriefcaseIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Position
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="text" v-model="form.official_title" id="official_title" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900">
                                </div>
                            </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mobile Number -->
                            <div>
                                <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <PhoneIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Mobile Number
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="text" v-model="form.mobile_number" id="mobile_number" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900">
                                </div>
                    </div>

                    <!-- Official Tel -->
                            <div>
                                <label for="official_tel" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <OfficeBuildingIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Office Phone
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="text" v-model="form.official_tel" id="official_tel" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900">
                                </div>
                            </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <LockClosedIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Password 
                                    <span class="text-xs text-gray-500 ml-2">(Leave empty if you don't want to change the password)</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 pr-10">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button @click="showPassword = !showPassword" type="button" class="text-gray-400 hover:text-gray-500" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                            <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                                            <EyeOffIcon v-else class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.password" class="mt-2">
                                    <div class="h-2 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full transition-all duration-300" :class="passwordStrength.color" :style="{ width: passwordStrength.width }"></div>
                                    </div>
                                    <p class="text-sm mt-1" :class="passwordStrength.textColor">{{ passwordStrength.label }}</p>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <LockClosedIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Confirm Password
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" id="password_confirmation" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 pr-10">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button @click="showConfirmPassword = !showConfirmPassword" type="button" class="text-gray-400 hover:text-gray-500" :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                                            <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5" />
                                            <EyeOffIcon v-else class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <ShieldCheckIcon class="h-4 w-4 text-blue-500 mr-1" />
                                    Status
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
                                    Password Validity Period (days)
                                </label>
                                <div class="relative rounded-md shadow-sm">
                        <input type="number" v-model="form.password_expiry_date" id="password_expiry_date" 
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900"
                            min="0">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            Update User
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
} from '@headlessui/vue';
import { 
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
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
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