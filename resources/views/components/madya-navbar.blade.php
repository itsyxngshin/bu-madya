<nav x-data="{ open: false }" class="bg-white/70 backdrop-blur-md border-b border-gray-200/50 sticky top-0 z-30 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- 1. LEFT SIDE: Logo & Main Links --}}
            <div class="flex">
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/official_logo.png') }}" class="block h-9 w-auto drop-shadow-sm" alt="BU MADYA Logo" />
                    </a>
                    <span class="font-heading font-black text-xl text-red-700 hidden md:block tracking-tight">BU MADYA</span>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @php
                        $navLink = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold leading-5 transition duration-150 ease-in-out';
                        $active = 'border-red-600 text-red-700 focus:border-red-700';
                        $inactive = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300';
                    @endphp
                    <a href="{{ route('open.home') }}" class="{{ $navLink }} {{ request()->routeIs('open.home.*') ? $active : $inactive }}">
                        Home
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="{{ $navLink }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                            Dashboard
                        </a>
                    @endauth
                    <a href="{{ route('projects.index') }}" class="{{ $navLink }} {{ request()->routeIs('projects.*') ? $active : $inactive }}">
                        Projects
                    </a>
                    <a href="{{ route('open.directory') }}" class="{{ $navLink }} {{ request()->routeIs('open.directory.*') ? $active : $inactive }}">
                        Directory
                    </a>
                    <a href="{{ route('linkages.index') }}" class="{{ $navLink }} {{ request()->routeIs('linkages.*') ? $active : $inactive }}">
                        Linkages
                    </a>
                    <a href="{{ route('news.index') }}" class="{{ $navLink }} {{ request()->routeIs('news.*') ? $active : $inactive }}">
                        News
                    </a>
                    <a href="{{ route('about') }}" class="{{ $navLink }} {{ request()->routeIs('about.*') ? $active : $inactive }}">
                        About
                    </a>
                </div>
            </div>

            {{-- 2. RIGHT SIDE: Auth Check --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                
                @auth
                    {{-- LOGGED IN STATE --}}
                    <div class="flex items-center ml-3">
                        <div class="hidden md:block text-right mr-3">
                            <p class="text-sm font-bold text-gray-900 leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            @if(Auth::user()->directorAssignment)
                                <p class="text-[10px] text-gray-500 font-medium leading-tight">
                                    {{ Auth::user()->directorAssignment?->director->name }}
                                </p>
                            @else
                                <p class="text-xs text-gray-500 leading-tight">
                                    {{ Auth::user()->role?->role_name ?? 'User' }}
                                </p>
                            @endif
                        </div>

                        {{-- Avatar Dropdown --}}
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition shadow-sm hover:shadow-md">
                                <img class="h-9 w-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path && filter_var(Auth::user()->profile_photo_path, FILTER_VALIDATE_URL) ? Auth::user()->profile_photo_path : asset(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        
                            <div x-show="dropdownOpen" 
                                @click.away="dropdownOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                style="display: none;">
                                
                                <a href="{{ route('profile.public', Auth::user()->username) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
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

                @else
                    {{-- [NEW] GUEST STATE (Login / Register) --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-red-600 text-white text-sm font-bold rounded-lg shadow-md hover:bg-red-700 transition transform hover:-translate-y-0.5">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            {{-- 3. MOBILE HAMBURGER --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 4. MOBILE MENU (Expanded) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/90 backdrop-blur-md border-b border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('open.home') }}" :active="request()->routeIs('open.home')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('projects.index') }}" :active="request()->routeIs('projects.*')">
                Projects
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('linkages.index') }}" :active="request()->routeIs('linkages.*')">
                Linkages
            </x-responsive-nav-link>
             <x-responsive-nav-link href="{{ route('open.directory') }}" :active="request()->routeIs('open.directory.*')">
                Directory
            </x-responsive-nav-link>
             <x-responsive-nav-link href="{{ route('news.index') }}" :active="request()->routeIs('news.*')">
                News
            </x-responsive-nav-link>
        </div>
        
        <div class="pt-4 pb-4 border-t border-gray-200">
            @auth
                <div class="flex items-center px-4">
                    <div class="shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_path && filter_var(Auth::user()->profile_photo_path, FILTER_VALIDATE_URL) ? Auth::user()->profile_photo_path : asset(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" />
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="{{ route('profile.public', Auth::user()->username) }}">
                            Profile
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600 font-bold">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                     <x-responsive-nav-link href="{{ route('login') }}">Log in</x-responsive-nav-link>
                     <x-responsive-nav-link href="{{ route('register') }}">Register</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>