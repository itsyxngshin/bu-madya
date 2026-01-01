<aside 
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="w-64 bg-white border-r border-gray-200 h-screen fixed left-0 top-0 flex flex-col z-50 font-sans shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out"
>
    
    {{-- LOGO SECTION --}}
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 shrink-0">
        <a href="{{ route('open.home') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md p-1 border border-gray-100 group-hover:scale-105 transition">
                <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-black text-lg tracking-tighter text-gray-900 leading-none">
                BU <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">MADYA</span>
                </span>
                <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest">Admin Panel</span>
            </div>
        </a>
        
        {{-- Close Button (Mobile Only) --}}
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-600 p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- NAVIGATION LINKS --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 scrollbar-thin hover:scrollbar-thumb-gray-200">
        
        {{-- Helper function for active classes defined in PHP --}}
        @php
            $linkClass = 'flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-sm group';
            $activeClass = 'bg-red-50 text-red-700 font-bold shadow-sm ring-1 ring-red-100';
            $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium';
            
            // Icon Styles
            $iconActive = 'text-red-600';
            $iconInactive = 'text-gray-400 group-hover:text-gray-600 transition-colors';
        @endphp

        {{-- 0. DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <div class="mt-8">
            <div class="pt-4 pb-2 px-3">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Membership</p>
            </div>
            
            <div class="mt-2 space-y-1">
                
                {{-- 1. REQUESTS LINK --}}
                <a href="{{ route('admin.membership-requests') }}" 
                    class="{{ $linkClass }} {{ request()->routeIs('admin.membership-requests') ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.membership.requests') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014"> </svg>
                        Applicants

                    {{-- PENDING COUNT BADGE --}}
                    {{-- This checks the DB directly for the count. --}}
                    @php
                        $pendingCount = \App\Models\MembershipApplication::where('status', 'pending')->count();
                    @endphp
                    
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold">
                            {{ $pendingCount }}
                        </span> 
                    @endif
                </a>

                {{-- 2. SETTINGS (WAVES) LINK --}}

                <a href="{{ route('admin.membership-settings') }}" 
                    class="{{ $linkClass }} {{ request()->routeIs('admin.membership-settings') ? $activeClass : $inactiveClass }}">
                         <svg class="w-5 h-5 {{ request()->routeIs('admin.membership.settings') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Membership Settings
                </a>
            </div>
        </div>

        {{-- SEPARATOR: MANAGEMENT --}}
        <div class="pt-4 pb-2 px-3">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Content</p>
        </div>

        {{-- 1. THE PILLARS (Important) --}}
        <a href="{{ route('director.pillars.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('director.pillars.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('director.pillars.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            The Pillars
        </a>

        {{-- 2. PROJECTS --}}
        <a href="{{ route('admin.projects.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.projects.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.projects.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Projects
        </a>

        {{-- 3. NEWS --}}
        <a href="{{ route('admin.news.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.news.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.news.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            News & Updates
        </a>

        {{-- 4. LINKAGES --}}
        <a href="{{ route('admin.linkages.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.linkages.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.linkages.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            Linkages
        </a>

        <a href="{{ route('admin.events.index') }}" 
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out
            {{ request()->routeIs('admin.events.*') 
                ? 'bg-red-50 text-red-600' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                
                {{-- Calendar Icon --}}
                <svg class="h-5 w-5 {{ request()->routeIs('admin.events.*') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                
                Events & Campaigns
        </a>

        {{-- SEPARATOR: SYSTEM --}}
        <div class="pt-4 pb-2 px-3 mt-2 border-t border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">System</p>
        </div>

        {{-- 5. USERS --}}
        <a href="{{ route('admin.user.index') ?? '#' }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            User Management
        </a>

        {{-- 6. SETTINGS --}}
        <a href="{{ route('admin.settings') ?? '#' }}" 
           class="{{ $linkClass }} {{ request()->routeIs('admin.settings') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.settings') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Settings
        </a>

        {{-- 7. RETURN HOME --}}
        <a href="{{ route('open.home') }}" 
           class="{{ $linkClass }} {{ $inactiveClass }} mt-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Public Homepage
        </a>

    </nav>

    {{-- LOGOUT SECTION --}}
    <div class="border-t border-gray-200 p-4 bg-gray-50 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 hover:text-white text-gray-600 rounded-lg transition-all duration-200 text-xs font-bold uppercase tracking-widest shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>