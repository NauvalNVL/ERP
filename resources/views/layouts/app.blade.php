<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    // konfigurasi tema kustom di sini jika diperlukan
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media (min-width: 1024px) {
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-0 left-0 z-20 p-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen"
            class="fixed inset-0 z-10 bg-black bg-opacity-50 lg:hidden"
            @click="sidebarOpen = false"></div>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <div :class="{'translate-x-0': sidebarOpen || window.innerWidth >= 1024, '-translate-x-full': !sidebarOpen && window.innerWidth < 1024}"
                class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out bg-gray-800 overflow-y-auto lg:sticky lg:top-0 lg:translate-x-0 h-screen hide-scrollbar">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="flex-1 min-w-0 flex flex-col">
                <!-- Top Navigation -->
                <div class="bg-white shadow-sm sticky top-0 z-10">
                    <div class="px-4 py-4 flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="mr-4 text-gray-500 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold">@yield('header', 'Dashboard')</h2>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="p-4 md:p-6 flex-grow">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>