<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'BU MADYA Community' }}</title>
    <link rel="icon" href="{{ asset('images/MADYA Web Logo1.png') }}">
    <meta name="description" content="{{ $metaDescription ?? 'Join the BU MADYA community. Share your advocacies, connect with student leaders, and stay updated with campus events.' }}">

    {{-- 3. FACEBOOK / OPEN GRAPH META TAGS --}}
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('meta_title', 'BU MADYA Community')" />
    <meta property="og:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))" />

    {{-- 4. TWITTER CARD DATA --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))">

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

    @php
        // Navigation Links Array for the Mobile Grid
        $navLinks = [
            ['name' => 'Home', 'route' => 'open.home', 'active' => 'open.home.*', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['name' => 'Projects', 'route' => 'projects.index', 'active' => 'projects.*', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['name' => 'Events', 'route' => 'events.index', 'active' => 'events.*', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['name' => 'Directory', 'route' => 'open.directory', 'active' => 'open.directory.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['name' => 'Linkages', 'route' => 'linkages.index', 'active' => 'linkages.*', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
            ['name' => 'News', 'route' => 'news.index', 'active' => 'news.*', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['name' => 'About', 'route' => 'about', 'active' => 'about.*', 'icon' => 'M13 16h-1v-4h-1m1-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['name' => 'Pillars', 'route' => 'pillars.index', 'active' => 'pillars.*', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
        ];
    @endphp

    {{-- ========================================== --}}
    {{-- MOBILE TOP APP BAR & COMPACT MENU --}}
    {{-- ========================================== --}}
    <div x-data="{ mobileMenuOpen: false }" class="lg:hidden">

        {{-- Top Nav Bar --}}
        <div class="fixed top-0 left-0 right-0 w-full h-16 bg-white/95 backdrop-blur-xl border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-[100] shadow-sm transition-all duration-300">
            <a href="{{ route('open.home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100">
                    <img src="{{ asset('images/MADYA Web Logo1.png') }}" class="w-full h-full object-contain" alt="Logo" />
                </div>
                <span class="font-heading font-black text-lg tracking-tighter">BU <span class="text-red-600">MADYA</span></span>
            </a>

            {{-- Morphing Hamburger / X Icon --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-700 hover:bg-gray-100 transition shadow-sm border border-gray-200 outline-none active:scale-95">
                <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Background Blur Overlay --}}
        <div x-show="mobileMenuOpen" style="display: none;" x-transition.opacity class="fixed inset-0 top-16 bg-gray-900/40 backdrop-blur-sm z-30" @click="mobileMenuOpen = false"></div>

        {{-- The 4-Column Compact Dropdown Menu --}}
        <div x-show="mobileMenuOpen" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-16 left-0 right-0 bg-white/95 backdrop-blur-xl border-b border-gray-200 shadow-2xl z-[100] max-h-[calc(100vh-4rem)] overflow-y-auto hide-scrollbar">

            <div class="px-4 py-6">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 block text-center">Discover and Synergize</span>

                {{-- 4-COLUMN COMPACT GRID --}}
                <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                    @foreach($navLinks as $link)
                        <a href="{{ route($link['route']) }}" class="flex flex-col items-center justify-center group">
                            {{-- Icon Circle --}}
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 mb-2 shadow-sm
                                {{ request()->routeIs($link['active']) ? 'bg-red-50 text-red-600 shadow-red-100 ring-2 ring-red-100' : 'bg-white text-gray-500 border border-gray-100 hover:border-red-200 hover:text-red-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path></svg>
                            </div>
                            {{-- Tiny Text Label --}}
                            <span class="text-[9px] font-bold uppercase tracking-wider text-center leading-tight {{ request()->routeIs($link['active']) ? 'text-red-600' : 'text-gray-500 group-hover:text-gray-900' }}">
                                {{ $link['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- SPECIAL ROUNDTABLE LINK --}}
                @auth
                    <a href="{{ route('roundtable.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl transition text-xs font-bold mt-6 border border-dashed border-yellow-400 bg-yellow-50 text-yellow-800 hover:bg-yellow-100 uppercase tracking-wider shadow-sm">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        The Roundtable
                    </a>
                @endauth
            </div>

            {{-- Mobile Auth Footer --}}
            <div class="px-4 pb-6 pt-4 bg-gray-50/50 border-t border-gray-100">
                @auth
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $photoPath = Auth::user()->profile_photo_path;
                            $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=EF4444&background=FEF2F2';
                        @endphp
                        <a href="{{ route('profile.public', Auth::user()->username) }}" class="shrink-0 rounded-full border-2 border-white shadow-sm overflow-hidden block w-10 h-10">
                            <img class="w-full h-full object-cover" src="{{ $photoUrl }}" alt="Profile" />
                        </a>
                        <div class="overflow-hidden">
                            <a href="{{ route('profile.public', Auth::user()->username) }}" class="font-black text-gray-900 leading-tight truncate text-sm hover:text-red-600 transition">{{ Auth::user()->name }}</a>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    @php
                        $userRole = Auth::user()->role->role_name ?? 'guest';
                        $canViewDashboard = in_array($userRole, ['administrator', 'director', 'organization']);
                        $navDashboardRoute = match($userRole) {
                            'administrator' => route('admin.dashboard'),
                            'organization'  => route('partner.dashboard'),
                            default         => route('dashboard'),
                        };
                    @endphp

                    <div class="grid {{ $canViewDashboard ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">
                        @if($canViewDashboard)
                            <a href="{{ $navDashboardRoute }}" class="flex justify-center py-2.5 bg-white border border-gray-200 rounded-lg text-[11px] font-black uppercase tracking-widest text-gray-700 hover:bg-gray-100 shadow-sm transition">
                                Dashboard
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 bg-red-50 border border-red-100 rounded-lg text-[11px] font-black uppercase tracking-widest text-red-600 hover:bg-red-100 shadow-sm transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3">
                         <a href="{{ route('login') }}" class="flex justify-center items-center py-2.5 bg-white border border-gray-200 rounded-lg text-[11px] font-black uppercase tracking-widest text-gray-700 hover:bg-gray-50 shadow-sm transition">
                             Log In
                         </a>
                         <a href="{{ route('register') }}" class="flex justify-center items-center py-2.5 bg-gray-900 text-white rounded-lg text-[11px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition shadow-md active:scale-95">
                             Join Us
                         </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MAIN 3-COLUMN DESKTOP LAYOUT --}}
    {{-- ========================================== --}}
    <div class="max-w-[1300px] mx-auto flex justify-center lg:justify-between px-0 sm:px-4 lg:px-8">

        {{-- 1. LEFT SIDEBAR (Sticky Navigation - Hidden on Mobile) --}}
        <div class="hidden lg:block w-64 shrink-0">
            @include('livewire.community.sidebar')
        </div>

        {{-- 2. CENTER COLUMN (Scrollable Feed/Content) --}}
        {{-- The mt-16 pushes content below the fixed mobile header --}}
        <main class="w-full max-w-2xl min-h-screen bg-white/50 border-x border-gray-200/60 pb-20 mt-16 lg:mt-0">
            {{ $slot }}
        </main>

        {{-- 3. RIGHT SIDEBAR (Widgets, Trending, Events - Hidden on Mobile/Tablet) --}}
        <div class="hidden xl:block w-80 shrink-0 h-screen sticky top-0 pl-8 py-8 overflow-y-auto hide-scrollbar">
            
            {{-- Search Box --}}
            <div class="relative mb-6">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search community..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-100 rounded-full text-[13px] focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition shadow-sm placeholder-gray-400 font-medium">
            </div>

            {{-- Widget: Community Guidelines --}}
            <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-[1.5rem] p-5 text-white shadow-lg shadow-red-900/20 mb-6 border border-red-500/20">
                <h3 class="font-black text-[15px] mb-1.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Student Voices
                </h3>
                <p class="text-[12px] font-medium text-red-100/90 leading-[1.6] mb-4">BU MADYA is a safe space for advocacy, academic freedom, and youth leadership. Keep the discourse respectful.</p>
                <a href="{{ route('privacy') }}" class="inline-block text-[10px] font-black uppercase tracking-widest bg-white/20 hover:bg-white/30 transition px-3.5 py-2 rounded-xl backdrop-blur-sm shadow-sm">Read Guidelines</a>
            </div>

            {{-- 🔥 INJECT THE NEW DYNAMIC COMPONENT HERE 🔥 --}}
            <livewire:community.trending-sidebar />

            {{-- Footer Links --}}
            <div class="px-2 flex flex-wrap gap-x-3 gap-y-1.5 text-[11px] font-bold text-gray-400 mt-2">
                <a href="{{ route('about') }}" class="hover:underline hover:text-gray-600 transition-colors">About</a>
                <a href="{{ route('privacy') }}" class="hover:underline hover:text-gray-600 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:underline hover:text-gray-600 transition-colors">Terms of Service</a>
                <span class="w-full block mt-1 opacity-75">&copy; {{ date('Y') }} BU MADYA</span>
            </div>

        </div>
    </div>

    @livewireScripts
</body>
</html>
