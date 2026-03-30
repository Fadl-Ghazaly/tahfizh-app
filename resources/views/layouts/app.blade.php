<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $appSettings['name'] ?? config('app.name', 'Laravel') }}</title>

        <!-- Dynamic Theme CSS -->
        <style>
            :root {
                --primary: {{ $appSettings['theme']['primary'] }};
                --primary-rgb: {{ $appSettings['primary_rgb'] }};
                --sidebar-bg: {{ $appSettings['theme']['sidebar'] }};
                --sidebar-accent: {{ $appSettings['theme']['accent'] }};
                --accent-rgb: {{ $appSettings['accent_rgb'] }};
                --primary-hover: {{ $appSettings['theme']['hover'] }};
            }
        </style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-track></script>
    </head>
    <body class="h-full font-sans antialiased text-gray-900 dark:text-gray-100 flex overflow-hidden">
        <div x-data="{ sidebarOpen: false }" class="flex h-full w-full overflow-hidden">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-100 dark:bg-gray-900">
            <!-- Mobile header -->
            <div class="md:hidden bg-white dark:bg-[#1f2937] border-b border-gray-200 dark:border-gray-800 shadow-sm flex items-center p-4 gap-4">
                <!-- The toggle button is managed by Alpine -->
                <button @click="sidebarOpen = true" class="text-slate-800 dark:text-gray-200 hover:text-slate-600 dark:hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5v14" />
                    </svg>
                </button>
                <span class="font-bold text-slate-800 dark:text-gray-200 text-lg truncate">{{ $appSettings['name'] }}</span>
            </div>

            <!-- Page Heading (Desktop only, or keep visible) -->
            @isset($header)
                <header class="bg-white dark:bg-[#1f2937] shadow z-10 hidden md:block border-b border-gray-200 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            {{ $header }}
                        </h2>
                        
                        <!-- User Dropdown in Header instead of Navbar -->
                        <div class="flex items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" wire:navigate.hover>
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-[#111827]">
                {{ $slot }}
            </main>
        </div>
        </div>
    </body>
</html>
