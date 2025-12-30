<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BU MADYA Admin') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 font-sans antialiased text-gray-900">

    {{-- LAYOUT STATE --}}
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-stone-50">

        {{-- 1. MOBILE BACKDROP (Closes sidebar when clicking outside) --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden">
        </div>

        {{-- 2. SIDEBAR COMPONENT --}}
        <x-madya-sidebar />

        {{-- 3. MAIN CONTENT WRAPPER --}}
        {{-- CRITICAL FIX: lg:pl-64 pushes content right on desktop so sidebar doesn't cover it --}}
        <div class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300">

            {{-- TOP HEADER --}}
            <header class="h-16 bg-white/90 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                
                {{-- Left: Toggle & Title --}}
                <div class="flex items-center gap-4">
                    {{-- Toggle Button (Mobile Only) --}}
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden hover:text-red-600 transition p-2 rounded-md hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    @if (isset($header))
                        <div class="font-heading font-bold text-gray-800 text-lg leading-tight truncate">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                {{-- Right: User Profile --}}
                <div class="flex items-center gap-4">
                    {{-- Date (Hidden on small screens) --}}
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Today</p>
                        <p class="text-xs font-bold text-gray-700">{{ now()->format('M d, Y') }}</p>
                    </div>

                    {{-- Avatar Dropdown --}}
                    <div class="relative ml-3" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition hover:shadow-md">
                            <img class="h-9 w-9 rounded-full object-cover bg-gray-200" 
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
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-1 ring-1 ring-black ring-opacity-5 z-50 origin-top-right divide-y divide-gray-100"
                             style="display: none;">
                             
                            <div class="px-4 py-3">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>

                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-xs font-bold uppercase text-gray-600 hover:bg-gray-50 hover:text-gray-900">Profile</a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-bold uppercase text-red-600 hover:bg-red-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>