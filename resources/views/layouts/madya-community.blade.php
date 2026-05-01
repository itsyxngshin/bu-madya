<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'BU MADYA Community' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased selection:bg-red-100 selection:text-red-900">

    {{-- MOBILE TOP APP BAR (Hidden on Desktop) --}}
    <div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
        <div class="fixed top-0 inset-x-0 h-16 bg-white/90 backdrop-blur-xl border-b border-gray-200 flex items-center justify-between px-4 z-50">
            <a href="{{ route('open.home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100">
                    <img src="{{ asset('images/MADYA Web Logo1.png') }}" class="w-full h-full object-contain" alt="Logo" />
                </div>
                <span class="font-heading font-black text-lg tracking-tighter">BU <span class="text-red-600">MADYA</span></span>
            </a>

            <button @click="mobileMenuOpen = true" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        {{-- Mobile Slide-over Menu (Reuses your sidebar logic) --}}
        <div x-show="mobileMenuOpen" style="display: none;" class="relative z-[100]">
            <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <span class="font-black text-gray-900">Navigation</span>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-900"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto pl-2">
                    {{-- Embed Sidebar Component Here for Mobile --}}
                    @include('livewire.community.sidebar')
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN 3-COLUMN LAYOUT --}}
    <div class="max-w-[1300px] mx-auto flex justify-center lg:justify-between px-0 sm:px-4 lg:px-8">
        
        {{-- 1. LEFT SIDEBAR (Sticky Navigation) --}}
        <div class="hidden lg:block w-64 shrink-0">
            @include('livewire.community.sidebar')
        </div>

        {{-- 2. CENTER COLUMN (Scrollable Feed/Content) --}}
        {{-- The mt-16 is for mobile to account for the fixed top bar --}}
        <main class="w-full max-w-2xl min-h-screen bg-white/50 border-x border-gray-200/60 pb-20 mt-16 lg:mt-0">
            {{ $slot }}
        </main>

        {{-- 3. RIGHT SIDEBAR (Widgets, Trending, Events) --}}
        <div class="hidden xl:block w-80 shrink-0 h-screen sticky top-0 pl-8 py-8 overflow-y-auto hide-scrollbar">
            
            {{-- Search Box --}}
            <div class="relative mb-8">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search community..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-full text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition shadow-sm placeholder-gray-400 font-medium">
            </div>

            {{-- Widget: Community Guidelines --}}
            <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-2xl p-5 text-white shadow-lg shadow-red-900/20 mb-6">
                <h3 class="font-black text-lg mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Student Voices
                </h3>
                <p class="text-xs font-medium text-red-100 leading-relaxed mb-4">BU MADYA is a safe space for advocacy, academic freedom, and youth leadership. Keep the discourse respectful.</p>
                <a href="{{ route('privacy') }}" class="inline-block text-[10px] font-black uppercase tracking-widest bg-white/20 hover:bg-white/30 transition px-3 py-1.5 rounded-lg backdrop-blur-sm">Read Guidelines</a>
            </div>

            {{-- Widget: Trending Categories --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm mb-6">
                <h3 class="font-black text-gray-900 mb-4 text-sm">Trending Topics</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center justify-between group">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-red-500 transition">Advocacy</p>
                            <p class="text-sm font-bold text-gray-800 group-hover:text-red-600 transition">#Humanista2026</p>
                        </div>
                        <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-500 group-hover:bg-red-50 group-hover:text-red-600 transition">12</span>
                    </a>
                    <a href="#" class="flex items-center justify-between group">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-red-500 transition">Tech & Startups</p>
                            <p class="text-sm font-bold text-gray-800 group-hover:text-red-600 transition">#ProjectDIGiTS</p>
                        </div>
                        <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-500 group-hover:bg-red-50 group-hover:text-red-600 transition">8</span>
                    </a>
                </div>
            </div>

            {{-- Footer Links --}}
            <div class="px-2 flex flex-wrap gap-x-3 gap-y-1 text-[11px] font-bold text-gray-400">
                <a href="{{ route('about') }}" class="hover:underline hover:text-gray-600">About</a>
                <a href="{{ route('privacy') }}" class="hover:underline hover:text-gray-600">Privacy Policy</a>
                <a href="#" class="hover:underline hover:text-gray-600">Terms of Service</a>
                <span>&copy; {{ date('Y') }} BU MADYA</span>
            </div>

        </div>
    </div>

    @livewireScripts
</body>
</html>