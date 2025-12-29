<aside 
    x-cloak
    x-show="sidebarOpen"
    x-transition:enter="transform transition ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="w-64 bg-white border-r border-gray-200 h-screen fixed left-0 top-0 flex flex-col z-50 font-sans shadow-lg"
>
    
    {{-- LOGO SECTION --}}
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md p-1 border border-gray-100">
                <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
                BU <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">MADYA</span>
                </span>
                <span class="text-[9px] font-bold text-red-600 uppercase tracking-wider">Admin Panel</span>
            </div>
        </div>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- NAVIGATION LINKS --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        
        {{-- Helper function for active classes --}}
        @php
            $activeClass = 'bg-red-50 text-red-700 border-r-4 border-red-600 font-bold';
            $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition-colors';
        @endphp

        <a href="{{ route('open.home') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('open.home') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Home
        </a>

        {{-- 1. DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        {{-- 2. USERS (New) --}}
        {{-- Route: Create this route if it doesn't exist yet --}}
        <a href="{{ route('admin.user.index') ?? '#' }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            User Management
        </a>

        {{-- 3. PROJECTS --}}
        <a href="{{ route('admin.projects.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.projects.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Projects
        </a>

        {{-- 4. NEWS --}}
        <a href="{{ route('admin.news.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.news.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            News & Updates
        </a>

        {{-- 5. LINKAGES --}}
        <a href="{{ route('admin.linkages.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.linkages.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            Linkages
        </a>

        {{-- SEPARATOR --}}
        <div class="pt-4 mt-4 border-t border-gray-100">
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">System</p>
            
            {{-- 6. SETTINGS (New) --}}
            {{-- Route: Create this route if it doesn't exist yet --}}
            <a href="{{ route('admin.settings') ?? '#' }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-l-md {{ request()->routeIs('admin.settings') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </div>

    </nav>

    {{-- LOGOUT SECTION --}}
    <div class="border-t border-gray-200 p-4 bg-gray-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-red-50 hover:border-red-100 text-gray-600 hover:text-red-700 rounded-lg transition-colors text-sm font-bold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>