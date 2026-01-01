<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BU MADYA Admin</title>
    <link rel="icon" href="{{ asset('images/official_logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">


    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { bg: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 font-sans antialiased text-gray-900">
    
    {{-- LAYOUT STATE MANAGEMENT --}}
    <div x-data="{ 
            sidebarOpen: window.innerWidth >= 1024,
            isMobile: window.innerWidth < 1024,
            init() {
                window.addEventListener('resize', () => {
                    this.isMobile = window.innerWidth < 1024;
                    if (!this.isMobile) this.sidebarOpen = true;
                    else this.sidebarOpen = false;
                })
            }
         }" 
         class="min-h-screen flex bg-stone-50 relative">
        
        {{-- MOBILE BACKDROP OVERLAY --}}
        <div x-show="sidebarOpen && isMobile" 
             @click="sidebarOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden backdrop-blur-sm"></div>

        {{-- SIDEBAR COMPONENT --}}
        {{-- We pass the alpine state into the component --}}
        <x-madya-admin-sidebar />
        
        {{-- MAIN CONTENT WRAPPER --}}
        {{-- Added 'w-full' to prevent horizontal scroll issues --}}
        <main class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out w-full"
              :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
            
            {{-- 1. STICKY TOP HEADER --}}
            <header class="bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-4 md:px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm transition-all duration-300">
                
                {{-- LEFT ZONE: Toggle & Page Title --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-red-600 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-red-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    @if (isset($header))
                        <div class="font-bold text-gray-800 font-heading text-lg leading-tight truncate max-w-[200px] md:max-w-none">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                {{-- RIGHT ZONE: Date & Profile --}}
                <div class="flex items-center gap-6">
                    
                    {{-- Date/Session Info --}}
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Session</p>
                        <p class="text-xs font-bold text-gray-700">{{ now()->format('M d, Y') }}</p>
                    </div>

                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

                    {{-- Profile --}}
                    <div class="flex items-center ml-1 md:ml-3">
                        <div class="hidden md:block text-right mr-3">
                            <p class="text-sm font-bold text-gray-900 leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] text-gray-500 font-medium leading-tight uppercase">
                                {{ Auth::user()->role?->role_name ?? 'Administrator' }}
                            </p>
                        </div>

                        {{-- Avatar Dropdown --}}
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-red-200 transition shadow-sm hover:shadow-md">
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
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 divide-y divide-gray-100" 
                                 style="display: none;">
                                
                                <div class="px-4 py-2">
                                    <p class="text-xs text-gray-500">Signed in as</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-gray-700 hover:bg-gray-50 transition">
                                    Profile Settings
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-red-600 hover:bg-red-50 transition">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- 2. PAGE CONTENT --}}
            <div class="p-4 md:p-6 lg:p-8 flex-1 overflow-x-hidden">
                 {{ $slot }}
            </div>

        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>