<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BU MADYA') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 font-sans antialiased text-gray-900">
    
    <div x-data="{ 
            sidebarOpen: false, 
            scrolled: false 
         }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="min-h-screen flex relative overflow-hidden">
        
        {{-- 1. MOBILE BACKDROP (Click to close sidebar) --}}
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden">
        </div>

        {{-- 2. SIDEBAR CONTAINER --}}
        {{-- Fixed on mobile (slide-in), Sticky/Static on Desktop --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:h-screen lg:overflow-y-auto shadow-2xl lg:shadow-none">
            
            {{-- Your Sidebar Component --}}
            <x-madya-sidebar />
            
        </aside>
        
        {{-- 3. MAIN CONTENT WRAPPER --}}
        <main class="flex-1 flex flex-col min-h-screen min-w-0 bg-stone-50 overflow-y-auto h-screen">
            
            {{-- STICKY HEADER --}}
            <header :class="{ 'bg-white/90 backdrop-blur-md border-b border-gray-200/50 shadow-sm': scrolled, 'bg-transparent border-b border-transparent': !scrolled }"
                    class="sticky top-0 z-30 h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all duration-300">
                
                {{-- LEFT: Toggle & Title --}}
                <div class="flex items-center gap-4">
                    {{-- Mobile Toggle --}}
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden text-gray-500 hover:text-red-600 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    {{-- Page Title (Optional Slot) --}}
                    @if (isset($header))
                        <div class="font-bold text-gray-800 font-heading text-lg leading-tight truncate">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Tools & Profile --}}
                <div class="flex items-center gap-3 sm:gap-6">
                    
                    {{-- Date (Hidden on Mobile) --}}
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Today</p>
                        <p class="text-xs font-bold text-gray-700">{{ now()->format('M d, l') }}</p>
                    </div>

                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

                    {{-- Profile Section --}}
                    <div class="flex items-center gap-3">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 font-medium leading-tight uppercase tracking-wide">
                                {{ Auth::user()->role?->role_name ?? 'Member' }}
                            </p>
                        </div>

                        {{-- Dropdown --}}
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex text-sm border-2 border-white ring-2 ring-gray-100 rounded-full focus:outline-none focus:ring-red-200 transition shadow-sm">
                                <img class="h-9 w-9 rounded-full object-cover" 
                                     src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}" />
                            </button>
                        
                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 origin-top-right" 
                                 style="display: none;">
                                
                                {{-- Mobile User Info --}}
                                <div class="px-4 py-3 border-b border-gray-100 md:hidden">
                                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 font-medium">
                                    Profile Settings
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- PAGE CONTENT --}}
            <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
                 {{ $slot }}
            </div>

        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>