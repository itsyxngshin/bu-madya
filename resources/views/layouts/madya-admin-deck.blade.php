<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BU MADYA Admin' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 selection:bg-red-500 selection:text-white">
    
    <div x-data="{ sidebarOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="min-h-screen flex relative">
        
        {{-- MOBILE OVERLAY BACKDROP --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden">
        </div>

        {{-- SIDEBAR COMPONENT --}}
        {{-- Note: Create this component separately or inline it if preferred --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 shadow-2xl">
             
             {{-- Logo Area --}}
             <div class="h-16 flex items-center px-6 bg-gray-900 border-b border-gray-800">
                 <a href="/" class="flex items-center gap-2">
                     <img src="{{ asset('images/official_logo.png') }}" class="h-8 w-auto" alt="Logo">
                     <span class="font-heading font-black text-lg tracking-tight text-white">BU <span class="text-red-500">MADYA</span></span>
                 </a>
             </div>

             {{-- Navigation Links --}}
             <nav class="px-4 py-6 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
                 <x-admin-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                     Dashboard
                 </x-admin-nav-link>
                 
                 <div class="pt-4 pb-2 px-2 text-[10px] font-bold uppercase text-gray-500 tracking-wider">Management</div>
                 
                 <x-admin-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="users">
                     Users & Roles
                 </x-admin-nav-link>
                 
                 <x-admin-nav-link href="{{ route('admin.projects.index') }}" :active="request()->routeIs('admin.projects.*')" icon="folder">
                     Projects
                 </x-admin-nav-link>

                 <x-admin-nav-link href="{{ route('admin.proposals.index') }}" :active="request()->routeIs('admin.proposals.*')" icon="document-text">
                     Proposals
                 </x-admin-nav-link>

                 <x-admin-nav-link href="{{ route('admin.linkages.index') }}" :active="request()->routeIs('admin.linkages.*')" icon="link">
                     Linkages
                 </x-admin-nav-link>

                 <div class="pt-4 pb-2 px-2 text-[10px] font-bold uppercase text-gray-500 tracking-wider">Content</div>

                 <x-admin-nav-link href="{{ route('admin.news.index') }}" :active="request()->routeIs('admin.news.*')" icon="newspaper">
                     News & Stories
                 </x-admin-nav-link>
                 
                 <x-admin-nav-link href="{{ route('roundtable.index') }}" :active="request()->routeIs('roundtable.*')" icon="chat-alt-2">
                     Roundtable
                 </x-admin-nav-link>
             </nav>
        </aside>
        
        {{-- MAIN CONTENT WRAPPER --}}
        <main class="flex-1 flex flex-col min-h-screen min-w-0 bg-gray-50/50">
            
            {{-- 1. STICKY TOP HEADER --}}
            <header :class="{ 'bg-white/90 backdrop-blur-xl shadow-sm': scrolled, 'bg-transparent': !scrolled }"
                    class="sticky top-0 z-30 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between transition-all duration-300">
                
                {{-- LEFT: Mobile Toggle & Title --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="text-gray-500 hover:text-gray-900 focus:outline-none lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    @if (isset($header))
                        <h2 class="font-heading font-bold text-xl text-gray-800 leading-tight hidden sm:block">
                            {{ $header }}
                        </h2>
                    @endif
                </div>

                {{-- RIGHT: Tools & Profile --}}
                <div class="flex items-center gap-4">
                    
                    {{-- Quick Action (Optional) --}}
                    <a href="/" class="hidden md:flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-red-600 transition uppercase tracking-wide mr-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        View Site
                    </a>

                    {{-- Notifications (Placeholder) --}}
                    <button class="relative p-2 text-gray-400 hover:text-gray-600 transition rounded-full hover:bg-gray-100">
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="relative ml-3" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" 
                                class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium uppercase">{{ Auth::user()->role->role_name ?? 'administrator' }}</p>
                            </div>
                            <img class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-red-200 transition" 
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
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 origin-top-right" 
                             style="display: none;">
                            
                            <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.public') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">
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
            </header>
            
            {{-- 2. PAGE CONTENT --}}
            <div class="p-4 sm:p-6 lg:p-8">
                 {{ $slot }}
            </div>

        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>