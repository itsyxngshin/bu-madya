<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BU MADYA Admin</title>
    <link rel="icon" href="{{ asset('images/MADYA Web Logo1.png') }}">

    <link rel="icon" href="{{ asset('images/MADYA Web Logo1.png') }}">

    {{-- 2. Standard Description --}}
    <meta name="description" content="@yield('meta_description', 'Advocating for youth empowerment and social change.')">

    {{-- 3. FACEBOOK / OPEN GRAPH META TAGS --}}
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('meta_title', 'BU MADYA Web')" />
    <meta property="og:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))" />

    {{-- 4. TWITTER CARD DATA --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>


    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { bg: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
{{-- [UPDATED] Body Tag with Dark Mode Defaults --}}
<body class="bg-stone-50 dark:bg-gray-900 font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300">

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
         class="min-h-screen flex bg-stone-50 dark:bg-gray-900 relative">

        {{-- MOBILE BACKDROP OVERLAY --}}
        <div x-show="sidebarOpen && isMobile"
             @click="sidebarOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden backdrop-blur-sm"></div>

        <x-madya-admin-sidebar />

        <main class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out w-full"
              :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">

            {{-- 1. STICKY TOP HEADER --}}
            {{-- [UPDATED] Header Background & Borders --}}
            <header class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 h-16 px-4 md:px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm transition-all duration-300">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    @if (isset($header))
                        <div class="font-bold text-gray-800 dark:text-gray-100 font-heading text-lg leading-tight truncate max-w-[200px] md:max-w-none">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    
                    {{-- [NEW] Dark Mode Toggle for Admin Dashboard --}}
                    <button x-data="{ 
                                isDark: document.documentElement.classList.contains('dark'),
                                toggle() {
                                    this.isDark = !this.isDark;
                                    if (this.isDark) { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; } 
                                    else { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; }
                                }
                            }" 
                            @click="toggle"
                            class="p-2 rounded-full text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800 transition-colors focus:outline-none">
                        <svg x-show="isDark" x-cloak class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg x-show="!isDark" class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Session</p>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ now()->format('M d, Y') }}</p>
                    </div>

                    <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden md:block"></div>

                    <div class="flex items-center ml-1 md:ml-3">
                        <div class="hidden md:block text-right mr-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium leading-tight uppercase">
                                {{ Auth::user()->role?->role_name ?? 'Administrator' }}
                            </p>
                        </div>

                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-red-200 dark:focus:border-red-500/50 transition shadow-sm hover:shadow-md">
                                <img class="h-9 w-9 rounded-full object-cover bg-gray-200 dark:bg-gray-700"
                                     src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}"
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
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-xl py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-50 divide-y divide-gray-100 dark:divide-gray-700"
                                 style="display: none;">

                                <div class="px-4 py-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Signed in as</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    Profile Settings
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- 2. PAGE CONTENT --}}
            <div class="p-4 md:p-6 lg:p-8 flex-1 overflow-clip">
                 {{ $slot }}
            </div>

        </main>
    </div>

    @stack('modals')
    @stack('scripts')
    @livewireScripts
</body>
</html>
