<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BU MADYA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 font-sans antialiased text-gray-900">
    
    <div x-data="{ sidebarOpen: true }" class="min-h-screen flex bg-stone-50 relative">
        
        {{-- SIDEBAR --}}
        <x-madya-admin-sidebar />
        
        {{-- MAIN CONTENT WRAPPER --}}
        <main class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out"
              :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
            
            {{-- 1. STICKY TOP HEADER --}}
            <header class="bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between sticky top-0 z-40 shadow-sm">
                
                {{-- LEFT ZONE: Toggle & Page Title --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-red-600 focus:outline-none transition-colors p-1 rounded-md hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </button>

                    {{-- [FIX 1] Optional Header Check: Prevents crash if slot is missing --}}
                    @if (isset($header))
                        <div class="font-bold text-gray-800 font-heading text-lg leading-tight">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                {{-- RIGHT ZONE: Date & Profile --}}
                <div class="flex items-center gap-6">
                    
                    {{-- Date/Session Info --}}
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Current Session</p>
                        <p class="text-xs font-bold text-gray-700">{{ now()->format('F d, Y • l') }}</p>
                    </div>

                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

                    {{-- Profile --}}
                    <div class="flex items-center ml-3">
                        <div class="hidden md:block text-right mr-3">
                            <p class="text-sm font-bold text-gray-900 leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            
                            {{-- [FIX 2] Safe Accessors for Role/Director --}}
                            @if(Auth::user()->directorAssignment)
                                <p class="text-[10px] text-gray-500 font-medium leading-tight">
                                    {{ Auth::user()->directorAssignment->director?->name ?? 'Director' }}
                                </p>
                            @else
                                <p class="text-[10px] text-gray-500 font-medium leading-tight uppercase">
                                    {{ Auth::user()->role?->role_name ?? 'Administrator' }}
                                </p>
                            @endif
                        </div>

                        {{-- Avatar Dropdown --}}
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition shadow-sm hover:shadow-md">
                                {{-- [FIX 3] Use Jetstream's built-in profile_photo_url to handle defaults automatically --}}
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
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                style="display: none;">
                                
                                <div class="px-4 py-2 text-xs text-gray-400">Manage Account</div>

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Your Profile
                                </a>

                                <div class="border-t border-gray-100"></div>

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
            
            {{-- 2. PAGE CONTENT --}}
            <div class="p-6 lg:p-8">
                 {{ $slot }}
            </div>

        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>