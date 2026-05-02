@php
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

<aside class="flex flex-col w-full h-full lg:h-screen lg:sticky top-0 bg-transparent lg:pr-6 py-4 lg:py-8 overflow-y-auto hide-scrollbar">

    {{-- 1. LOGO SECTION (Hidden on mobile to prevent double-logos) --}}
    <div class="px-4 mb-8 hidden lg:block">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md p-1 border border-gray-100 group-hover:scale-105 transition transform">
                <img src="{{ asset('images/MADYA Web Logo1.png') }}" class="w-full h-full object-contain" alt="BU MADYA Logo" />
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-black text-xl text-gray-900 leading-none tracking-tighter group-hover:text-red-600 transition">
                    BU <span class="text-red-600">MADYA</span>
                </span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Community</span>
            </div>
        </a>
    </div>

    {{-- 2. MAIN NAVIGATION --}}
    <nav class="flex-1 space-y-1">
        @foreach($navLinks as $link)
            @php
                $isActive = request()->routeIs($link['active']);
            @endphp
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200 group {{ $isActive ? 'bg-red-50 text-red-600 font-black' : 'text-gray-600 hover:bg-white hover:shadow-sm font-bold' }}">

                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ $isActive ? 'text-red-600' : 'text-gray-400 group-hover:text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2.5' : '2' }}" d="{{ $link['icon'] }}"></path>
                </svg>

                <span class="text-sm tracking-wide">{{ $link['name'] }}</span>
            </a>
        @endforeach

        {{-- SPECIAL ROUNDTABLE LINK --}}
        @auth
            <div class="pt-4 mt-4 border-t border-gray-200/50">
                <a href="{{ route('roundtable.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200 group text-yellow-700 hover:bg-yellow-50 hover:shadow-sm font-bold">
                    <svg class="w-6 h-6 text-yellow-500 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    <span class="text-sm tracking-wide">Roundtable</span>
                </a>
            </div>
        @endauth
    </nav>

    {{-- 3. USER & AUTH SECTION --}}
    <div class="mt-8 pt-6 border-t border-gray-200/50">
        @auth
            @php
                $userRole = Auth::user()->role->role_name ?? 'guest';
                $canViewDashboard = in_array($userRole, ['administrator', 'director', 'organization']);
                $navDashboardRoute = match($userRole) {
                    'administrator' => route('admin.dashboard'),
                    'organization'  => route('partner.dashboard'),
                    default         => route('dashboard'),
                };

                $photoPath = Auth::user()->profile_photo_path;
                $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=4F46E5&background=E0E7FF';
            @endphp

            <div x-data="{ userMenuOpen: false }" class="relative">
                {{-- User Profile Button --}}
                <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-white hover:shadow-sm transition-all text-left group">
                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200 group-hover:border-red-300 transition-colors" src="{{ $photoUrl }}" alt="{{ Auth::user()->name }}" />
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-black text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>

                {{-- Flyout Menu --}}
                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50"
                     style="display: none;">

                    <a href="{{ route('profile.public', Auth::user()->username) }}" class="block px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600">Your Profile</a>

                    @if($canViewDashboard)
                        <a href="{{ $navDashboardRoute }}" class="block px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600">Management Dashboard</a>
                    @endif

                    <div class="border-t border-gray-100 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm font-black text-red-600 hover:bg-red-50">Log Out</button>
                    </form>
                </div>
            </div>
        @else
            <div class="space-y-3 px-2">
                <a href="{{ route('login') }}" class="flex items-center justify-center w-full py-2.5 bg-white border border-gray-200 text-gray-700 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="flex items-center justify-center w-full py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 hover:shadow-lg transition transform hover:-translate-y-0.5">
                    Join BU MADYA
                </a>
            </div>
        @endauth
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</aside>
