<template>
    <Head title="Login - ERP System" />
    <div class="min-h-screen flex items-center justify-center bg-gray-100 animate-fadeIn">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-lg animate-slideUp">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 mb-4 animate-bounce-soft">
                    <img
                        :src="logoSrc"
                        alt="ERP Logo"
                        class="w-full h-full object-contain drop-shadow-md"
                    />
                </div>
                <h2 class="text-center text-3xl font-extrabold text-gray-900 animate-fadeIn animation-delay-300">
                    Login to ERP System
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 animate-fadeIn animation-delay-500">
                    Enter your credentials to access the system
                </p>
            </div>

            <div v-if="Object.keys($page.props.errors).length > 0"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-shake"
                role="alert">
                <ul>
                    <li v-for="(error, index) in Object.values($page.props.errors)" :key="index">{{ error }}</li>
                </ul>
            </div>

            <form class="mt-8 space-y-6 animate-fadeIn animation-delay-700" method="POST" action="/login" @submit="isLoading = true">
                <input type="hidden" name="_token" :value="csrfToken" />
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="user_id" class="sr-only">User ID</label>
                        <input id="user_id" name="user_id" v-model="userId" type="text" required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                            placeholder="User ID"
                            :readonly="isLoading">
                    </div>
                    <div class="relative">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" v-model="password" :type="showPassword ? 'text' : 'password'" required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300 pr-10"
                            placeholder="Password"
                            :readonly="isLoading">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showPassword = !showPassword" class="text-gray-400 hover:text-gray-600 focus:outline-none" :disabled="isLoading">
                                <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                                <EyeOffIcon v-else class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" v-model="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-300"
                            :disabled="isLoading">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white transition-all duration-300 ease-in-out transform hover:scale-[1.02] active:scale-[0.98]"
                        :class="[isLoading ? 'bg-blue-500' : 'bg-blue-600 hover:bg-blue-700']"
                        :disabled="isLoading">

                        <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center">
                            <div class="loader">
                                <div class="dot-pulse"></div>
                            </div>
                        </div>

                        <span v-else class="flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign in
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { EyeIcon, EyeSlashIcon as EyeOffIcon } from '@heroicons/vue/24/outline';

const isLoading = ref(false);
const showPassword = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content || '';
const logoSrc = new URL('../../../../public/favicon.png', import.meta.url).href;

// Optional v-model bindings for nicer UX
const userId = ref('');
const password = ref('');
const remember = ref(false);
</script>

<style scoped>
.loader {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dot-pulse {
    position: relative;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: white;
    animation: dot-pulse 1.5s infinite linear;
    animation-delay: 0.25s;
}

.dot-pulse::before,
.dot-pulse::after {
    content: '';
    position: absolute;
    display: inline-block;
    top: 0;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: white;
}

.dot-pulse::before {
    left: -12px;
    animation: dot-pulse 1.5s infinite linear;
    animation-delay: 0s;
}

.dot-pulse::after {
    left: 12px;
    animation: dot-pulse 1.5s infinite linear;
    animation-delay: 0.5s;
}

@keyframes dot-pulse {
    0% {
        transform: scale(0.2);
        opacity: 0.8;
    }
    20% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(0.2);
        opacity: 0.8;
    }
}

/* Entry animations */
.animate-fadeIn {
    animation: fadeIn 0.8s ease-in-out;
}

.animate-slideUp {
    animation: slideUp 0.8s ease-out;
}

.animate-bounce-soft {
    animation: bounceSoft 2s ease infinite;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Animation delay classes */
.animation-delay-300 {
    animation-delay: 300ms;
}

.animation-delay-500 {
    animation-delay: 500ms;
}

.animation-delay-700 {
    animation-delay: 700ms;
}

/* Keyframes */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes bounceSoft {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
    20%, 40%, 60%, 80% {
        transform: translateX(5px);
    }
}
</style>
