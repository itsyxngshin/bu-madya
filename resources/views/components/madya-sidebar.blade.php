<aside 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    
    {{-- LOGO AREA --}}
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 shrink-0 bg-white">
        <div class="flex items-center gap-3">
            {{-- Ensure this logo path is correct --}}
            <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            <span class="font-heading font-bold text-gray-800 tracking-tight">BU MADYA</span>
        </div>
        
        {{-- Close Button (Mobile Only) --}}
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-600 p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- NAVIGATION LINKS --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 scrollbar-thin hover:scrollbar-thumb-gray-200">
        
        @php
            $linkClass = 'flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-sm group';
            $activeClass = 'bg-red-50 text-red-700 font-bold border-r-4 border-red-600 shadow-sm';
            $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium';
            
            $iconActive = 'text-red-600';
            $iconInactive = 'text-gray-400 group-hover:text-gray-600 transition-colors';
        @endphp

        <a href="{{ route('dashboard') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <a href="{{ route('director.pillars.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('director.pillars.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('director.pillars.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            The Pillars
        </a>

        <a href="{{ route('open.committees') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('open.committees*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('open.committees*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Committees
        </a>

        <a href="{{ url('/directory') }}" 
           class="{{ $linkClass }} {{ request()->is('directory') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->is('directory') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Directors
        </a>

        <a href="{{ route('projects.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('projects.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('projects.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Projects
        </a>

        <a href="{{ route('news.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('news.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('news.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            News
        </a>

        <a href="{{ route('linkages.index') }}" 
           class="{{ $linkClass }} {{ request()->routeIs('linkages.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 {{ request()->routeIs('linkages.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            Linkages
            <span class="ml-auto bg-yellow-400 text-green-900 py-0.5 px-2 rounded-full text-[10px] font-bold shadow-sm">2</span>
        </a>

        {{-- BOTTOM: Public Site Link --}}
        <div class="pt-4 pb-2 px-3 mt-auto border-t border-gray-100">
             <a href="{{ route('open.home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition font-bold text-xs uppercase tracking-wide group">
                <svg class="w-4 h-4 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Public Homepage
             </a>
        </div>
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