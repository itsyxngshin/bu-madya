<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/90 backdrop-blur-xl shadow-sm border-b border-gray-200': scrolled, 'bg-white/50 backdrop-blur-md border-b border-transparent': !scrolled }"
     class="sticky top-0 z-50 transition-all duration-300">

    {{-- DEFINING LINKS --}}
    @php
        $navLinks = [
            ['name' => 'Home', 'route' => 'open.home', 'active' => 'open.home.*', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['name' => 'Projects', 'route' => 'projects.index', 'active' => 'projects.*', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['name' => 'Events', 'route' => 'events.index', 'active' => 'events.*', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['name' => 'Directory', 'route' => 'open.directory', 'active' => 'open.directory.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['name' => 'Linkages', 'route' => 'linkages.index', 'active' => 'linkages.*', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
            ['name' => 'News', 'route' => 'news.index', 'active' => 'news.*', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['name' => 'About', 'route' => 'about', 'active' => 'about.*', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['name' => 'Pillars', 'route' => 'pillars.index', 'active' => 'pillars.*', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> 
            
            {{-- 1. LEFT SIDE: Logo --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('images/official_logo.png') }}" class="block h-10 w-auto drop-shadow-sm group-hover:scale-105 transition transform" alt="BU MADYA Logo" />
                    <div class="flex flex-col">
                        <span class="font-heading font-black text-lg lg:text-xl text-gray-900 leading-none tracking-tighter group-hover:text-red-600 transition">BU <span class="text-red-600">MADYA</span></span>
                    </div>
                </a>
            </div>

            {{-- 2. CENTER: Desktop Navigation --}}
            <div class="hidden md:flex items-center justify-center space-x-6 lg:space-x-8">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" 
                       class="group flex flex-col items-center justify-center transition-all duration-200
                       {{ request()->routeIs($link['active']) ? 'text-red-600' : 'text-gray-400 hover:text-gray-900' }}">
                        <svg class="w-6 h-6 mb-1 transition-transform group-hover:-translate-y-1 {{ request()->routeIs($link['active']) ? 'text-red-600' : 'text-gray-400 group-hover:text-red-500' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider {{ request()->routeIs($link['active']) ? 'text-red-600' : 'text-gray-500 group-hover:text-gray-900' }}">
                            {{ $link['name'] }}
                        </span>
                    </a>
                @endforeach

                @auth
                    <a href="{{ route('roundtable.index') }}" 
                       class="group flex flex-col items-center justify-center transition-all duration-200 text-yellow-600 hover:text-yellow-700">
                        <svg class="w-6 h-6 mb-1 transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Roundtable</span>
                    </a>
                @endauth
            </div>

            {{-- 3. RIGHT SIDE: Auth & Mobile Toggle --}}
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(Auth::user()->role && in_array(Auth::user()->role->role_name, ['administrator', 'director']))
                            <a href="{{ Auth::user()->role->role_name === 'administrator' ? route('admin.dashboard') : route('dashboard') }}" 
                               class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-red-600 transition whitespace-nowrap border border-gray-200 px-3 py-1 rounded-full hover:border-red-200">
                                Dashboard
                            </a>
                        @endif
                        
                        <div class="relative ml-2" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-red-300 transition hover:shadow-md">
                                <img class="h-9 w-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path ? (filter_var(Auth::user()->profile_photo_path, FILTER_VALIDATE_URL) ? Auth::user()->profile_photo_path : asset(Auth::user()->profile_photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}" />
                            </button>

                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-xs text-gray-500 uppercase font-bold">Signed in as</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('profile.public', Auth::user()->username) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">Your Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition whitespace-nowrap">Log in</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 hover:shadow-lg transition transform hover:-translate-y-0.5 whitespace-nowrap">Join Us</a>
                    @endauth
                </div>

                {{-- Mobile Hamburger --}}
                <div class="flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. COMPACT MOBILE MENU (4-COLUMN GRID) --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white/95 backdrop-blur-xl border-b border-gray-200 shadow-2xl relative z-40 max-h-[85vh] overflow-y-auto">
        
        <div class="px-4 py-6">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 block text-center">Discover and Synergize</span>
            
            {{-- 4-COLUMN COMPACT GRID --}}
            <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" 
                       class="flex flex-col items-center justify-center group">
                        
                        {{-- Icon Circle --}}
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 mb-2 shadow-sm
                            {{ request()->routeIs($link['active']) 
                               ? 'bg-red-50 text-red-600 shadow-red-100 ring-2 ring-red-100' 
                               : 'bg-white text-gray-500 border border-gray-100 hover:border-red-200 hover:text-red-500' }}">
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
                <a href="{{ route('roundtable.index') }}" 
                   class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl transition text-xs font-bold mt-6 border border-dashed border-yellow-400 bg-yellow-50 text-yellow-800 hover:bg-yellow-100 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    The Roundtable
                </a>
            @endauth
        </div>
        
        {{-- Mobile Auth Footer --}}
        <div class="px-4 pb-6 pt-2 bg-gray-50/50 border-t border-gray-100">
            @auth
                <div class="flex items-center gap-3 mb-4">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" src="{{ Auth::user()->profile_photo_path ? (filter_var(Auth::user()->profile_photo_path, FILTER_VALIDATE_URL) ? Auth::user()->profile_photo_path : asset(Auth::user()->profile_photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" />
                    <div class="overflow-hidden">
                        <div class="font-bold text-gray-900 leading-tight truncate text-sm">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                @php
                    $userRole = Auth::user()->role->role_name ?? '';
                    $canViewDashboard = in_array($userRole, ['administrator', 'director']);
                @endphp

                {{-- LAYOUT: Use 2 columns if Dashboard is visible, otherwise 1 column --}}
                <div class="grid {{ $canViewDashboard ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">

                    {{-- 1. DASHBOARD BUTTON (Conditionally Rendered) --}}
                    @if($canViewDashboard)
                        @php
                            // Optional: Send them to different dashboards if needed
                            $dashboardRoute = match($userRole) {
                                'administrator' => route('admin.dashboard'),
                                'director'      => route('dashboard'), // Or route('director.dashboard')
                                default         => route('open.home'),
                            };
                        @endphp

                        <a href="{{ $dashboardRoute }}" class="flex justify-center py-2.5 bg-white border border-gray-200 rounded-lg text-xs font-bold uppercase tracking-wide text-gray-600 hover:bg-gray-100 shadow-sm transition">
                            Dashboard
                        </a>
                    @endif

                    {{-- 2. LOGOUT BUTTON (Always Visible) --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-red-100 border border-transparent rounded-lg text-xs font-bold uppercase tracking-wide text-red-700 hover:bg-red-200 shadow-sm transition">
                            Log Out
                        </button>
                    </form>

                </div>
            @else
                <div class="grid grid-cols-2 gap-3">
                     <a href="{{ route('login') }}" class="flex justify-center items-center py-2.5 bg-white border border-gray-200 rounded-lg text-xs font-bold uppercase tracking-wide text-gray-700 hover:bg-gray-50">
                         Log In
                     </a>
                     <a href="{{ route('register') }}" class="flex justify-center items-center py-2.5 bg-gray-900 text-white rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-red-600 transition shadow-lg">
                         Join Us
                     </a>
                </div>
            @endauth
        </div>
    </div>
</nav>