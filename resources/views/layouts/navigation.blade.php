<div class="flex h-full z-50">
    <!-- Off-canvas menu for mobile, show/hide based on off-canvas modal state. -->
    <div x-show="sidebarOpen" class="relative z-50 md:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-50 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" @click.away="sidebarOpen = false" class="relative flex w-full max-w-xs flex-1 flex-col pt-5 pb-4" style="background-color: var(--sidebar-bg)">
                <!-- Logo Mobile area with Close Button -->
                <div class="flex flex-shrink-0 items-center justify-between px-4 border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded bg-[var(--primary)] flex items-center justify-center font-bold text-white text-lg shadow-sm">DI</div>
                        <div>
                            <span class="block font-bold text-white text-lg leading-tight">{{ $appSettings['name'] }}</span>
                            <span class="block text-[var(--primary)] text-xs">Sistem Tahfidz</span>
                        </div>
                    </div>
                    <!-- Close button -->
                    <button @click="sidebarOpen = false" type="button" class="ml-2 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none hover:bg-[var(--sidebar-accent)]">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Links Mobile -->
                <div class="mt-5 h-0 flex-1 overflow-y-auto">
                    <nav class="space-y-1 px-2">
                        @php
                            $links = [
                                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                                ['route' => 'santri.index', 'label' => 'Data Santri', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'adminOnly' => true],
                                ['route' => 'ustadz.index', 'label' => 'Data Ustadz', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'adminOnly' => true],
                                ['route' => 'setoran.index', 'label' => 'Input Setoran', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['route' => 'laporan.index', 'label' => 'Laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                ['route' => 'pengaturan.index', 'label' => 'Pengaturan', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'adminOnly' => true],
                            ];
                        @endphp

                        @foreach($links as $link)
                            @if(!isset($link['adminOnly']) || (isset($link['adminOnly']) && Auth::user()->role === 'admin'))
                             @php $isActive = request()->routeIs($link['route'] == 'dashboard' ? 'dashboard' : explode('.', $link['route'])[0] . '.*'); @endphp
                            <a href="{{ route($link['route']) }}" wire:navigate class="{{ $isActive ? 'text-white shadow-sm ring-1 ring-white/10' : 'text-gray-300 hover:bg-[rgba(var(--accent-rgb),0.6)] hover:text-white' }} group flex items-center px-3 py-2.5 text-base font-medium rounded-lg transition-colors duration-150" {!! $isActive ? 'style="background-color: var(--sidebar-accent)"' : '' !!}>
                                <svg class="{{ $isActive ? 'text-[var(--primary)]' : 'text-gray-400 group-hover:text-white' }} mr-4 flex-shrink-0 h-6 w-6 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                                </svg>
                                {{ $link['label'] }}
                            </a>
                            @endif
                        @endforeach
                    </nav>

                    <!-- Mobile Profile options -->
                    <div class="mt-8 pt-4 border-t border-white/10 px-2 space-y-2">
                        <!-- Mobile User Info -->
                        <div class="px-3 pb-2 text-gray-300 border-b border-white/5 mb-2">
                            <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                            <div class="text-sm truncate">{{ Auth::user()->email }}</div>
                        </div>

                         <a href="{{ route('profile.edit') }}" wire:navigate class="group flex items-center px-3 py-2 text-base font-medium rounded-lg text-gray-300 hover:bg-[rgba(var(--accent-rgb),0.6)] hover:text-white transition-colors">
                            <svg class="text-gray-400 group-hover:text-white mr-4 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full group flex items-center px-3 py-2 text-base font-medium rounded-lg text-gray-300 hover:bg-[rgba(var(--accent-rgb),0.6)] hover:text-white transition-colors">
                                <svg class="text-gray-400 group-hover:text-white mr-4 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="w-14 flex-shrink-0" aria-hidden="true">
                <!-- Dummy element to force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:w-64 md:flex-col shrink-0 relative z-40">
        <!-- Sidebar component -->
        <div class="flex min-h-0 flex-1 flex-col border-r border-white/10 shadow-xl overflow-hidden" style="background-color: var(--sidebar-bg)">
            <!-- Logo Desktop -->
            <div class="flex items-center px-4 h-[72px] border-b border-white/10 shrink-0">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 w-full">
                    <div class="w-10 h-10 rounded bg-[var(--primary)] flex items-center justify-center font-bold text-white shadow-sm shrink-0">
                        DI
                    </div>
                    <div class="overflow-hidden">
                        <span class="block font-bold text-white text-base leading-tight truncate">{{ $appSettings['name'] }}</span>
                        <span class="block text-[var(--primary)] text-xs font-medium truncate">Sistem Tahfidz</span>
                    </div>
                </a>
            </div>
            <div class="flex flex-1 flex-col overflow-y-auto pt-6 pb-4">
                <nav class="flex-1 space-y-2 px-4">
                    @foreach($links as $link)
                        @if(!isset($link['adminOnly']) || (isset($link['adminOnly']) && Auth::user()->role === 'admin'))
                        @php $isActive = request()->routeIs($link['route'] == 'dashboard' ? 'dashboard' : explode('.', $link['route'])[0] . '.*'); @endphp
                        <a href="{{ route($link['route']) }}" wire:navigate class="{{ $isActive ? 'text-white shadow-md ring-1 ring-white/10' : 'text-gray-300 hover:bg-[rgba(var(--accent-rgb),0.6)] hover:text-white transition-colors duration-200' }} group flex items-center px-4 py-3 text-sm font-medium rounded-lg" {!! $isActive ? 'style="background-color: var(--sidebar-accent)"' : '' !!}>
                            <svg class="{{ $isActive ? 'text-[var(--primary)]' : 'text-[#8ca59b] group-hover:text-white transition-colors duration-200' }} mr-3 flex-shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                            </svg>
                            {{ $link['label'] }}
                        </a>
                        @endif
                    @endforeach
                </nav>
            </div>
            
            <!-- (Optional) Bottom user widget on desktop, but we have it in the top header. Instead just a branding or version tag -->
             <div class="px-4 py-4 border-t border-white/5 opacity-50 text-xs text-center text-white font-medium">
                Sistem Tahfidz v1.0
             </div>
        </div>
    </div>
</div>
