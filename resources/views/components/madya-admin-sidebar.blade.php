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
                <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest mt-0.5">Admin Panel</span>
            </div>
        </a>

        {{-- Close Button (Mobile Only) --}}
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-600 p-1 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- NAVIGATION LINKS --}}
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 custom-scrollbar">

        {{-- UNIFIED STYLING SYSTEM --}}
        @php
            $linkClass = 'flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm group font-semibold';

            // Premium Active vs Inactive State Styling
            $activeClass = 'bg-gradient-to-r from-red-50 to-white text-red-700 shadow-sm ring-1 ring-red-100/50';
            $inactiveClass = 'text-gray-500 hover:bg-gray-50 hover:text-gray-900';

            // Icon State Styling
            $iconBase = 'w-5 h-5 shrink-0 transition-transform group-hover:scale-110 duration-200';
            $iconActive = $iconBase . ' text-red-600';
            $iconInactive = $iconBase . ' text-gray-400 group-hover:text-gray-500';

            // Role Definitions
            $role = auth()->user()->role?->role_name ?? 'guest';
            $isAdmin = in_array($role, ['administrator']);
            $isOrg = $role === 'organization';
            $isDirector = $role === 'director';
        @endphp

        {{-- ========================================== --}}
        {{-- DASHBOARD --}}
        {{-- ========================================== --}}
        @php
            $dashboardRoute = match($role) {
                'administrator' => route('admin.dashboard'),
                'organization' => route('partner.dashboard'),
                'director' => route('dashboard'),
                default => route('dashboard'),
            };
            $isDashboardActive = request()->routeIs('admin.dashboard') || request()->routeIs('partner.dashboard') || request()->routeIs('dashboard');
        @endphp
        <a href="{{ $dashboardRoute }}" class="{{ $linkClass }} {{ $isDashboardActive ? $activeClass : $inactiveClass }}">
            <svg class="{{ $isDashboardActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        {{-- ========================================== --}}
        {{-- CORE WORKSPACE (Events, Projects, Campaigns) --}}
        {{-- ========================================== --}}
        <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Workspace</p>
        </div>

        @if($isAdmin || $isOrg)
            @php
                $eventsRoute = $isOrg ? route('partner.events.index') : route('admin.events.index');
                $isEventsActive = request()->routeIs('partner.events.*') || request()->routeIs('admin.events.*');
            @endphp
            <a href="{{ $eventsRoute }}" class="{{ $linkClass }} {{ $isEventsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isEventsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Manage Events
            </a>
        @endif

        @if($isAdmin)
            @php $isProjectsActive = request()->routeIs('admin.projects.*'); @endphp
            <a href="{{ route('admin.projects.index') }}" class="{{ $linkClass }} {{ $isProjectsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isProjectsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Manage Projects
            </a>
        @endif

        @if($isAdmin || $isOrg || $isDirector)
            @php
                $campaignRoute = match($role) {
                    'organization'  => route('partner.campaigns.index'),
                    'director'      => route('director.campaigns.index'),
                    default         => route('admin.campaigns.index'),
                };
                $isCampaignsActive = request()->routeIs('*.campaigns.*');
            @endphp
            <a href="{{ $campaignRoute }}" class="{{ $linkClass }} {{ $isCampaignsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isCampaignsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Campaigns
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- ELECTIONS ENGINE --}}
        {{-- ========================================== --}}
        @if($isAdmin || $isOrg)
            <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Democracy</p>
            </div>

            @php
                $electionsRoute = $isAdmin ? route('admin.elections.index') : route('partner.elections.index');
                $isElectionsActive = request()->routeIs('admin.elections.*') || request()->routeIs('partner.elections.*');
            @endphp
            <a href="{{ $electionsRoute }}" class="{{ $linkClass }} {{ $isElectionsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isElectionsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Election Manager
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- SERVICES (Evaluations, Welfare, Transparency) --}}
        {{-- ========================================== --}}
        <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Services</p>
        </div>

        @if($isAdmin || $isOrg)
            @php
                $evalRoute = $isAdmin ? route('admin.evaluations.index') : route('partner.evaluations.index');
                $isEvaluationsActive = request()->routeIs('admin.evaluations.*') || request()->routeIs('partner.evaluations.*');
            @endphp
            <a href="{{ $evalRoute }}" class="{{ $linkClass }} {{ $isEvaluationsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isEvaluationsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Evaluation Manager
            </a>
        @endif

        @if($isAdmin)
            @php $isTransparencyActive = request()->routeIs('admin.transparency.*'); @endphp
            <a href="{{ route('admin.transparency.index') }}" class="{{ $linkClass }} {{ $isTransparencyActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isTransparencyActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Transparency Board
            </a>

            @php $isFramesReviewActive = request()->routeIs('admin.frames.*'); @endphp
            <a href="{{ route('admin.frames.index') }}" class="{{ $linkClass }} {{ $isFramesReviewActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isFramesReviewActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Review Frames
            </a>
        @endif

        @if($isOrg)
            @php $isFramesActive = request()->routeIs('partner.frames.*'); @endphp
            <a href="{{ route('partner.frames.submit') }}" class="{{ $linkClass }} {{ $isFramesActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isFramesActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                My Campaign Frames
            </a>
        @endif

        @if($isAdmin || ($isOrg && auth()->user()->can_manage_welfare))
            @php
                $welfareRoute = $isAdmin ? route('admin.welfare.index') : route('partner.welfare.index');
                $isWelfareActive = request()->routeIs('admin.welfare.*') || request()->routeIs('partner.welfare.*');
            @endphp
            <a href="{{ $welfareRoute }}" class="{{ $linkClass }} {{ $isWelfareActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isWelfareActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                Welfare & Grievances
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- COMMUNITY & MODERATION --}}
        {{-- ========================================== --}}
        @if($isAdmin || $isDirector)
            <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Community</p>
            </div>

            @php $isModerationActive = request()->routeIs('admin.community.moderation'); @endphp
            <a href="{{ route('admin.community.moderation') ?? '#' }}" class="{{ $linkClass }} {{ $isModerationActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isModerationActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                Feed Moderation
                
                {{-- Automated System Flag Badge --}}
                @php $flaggedCount = \App\Models\Post::where('is_flagged', true)->count(); @endphp
                @if($flaggedCount > 0)
                    <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-[10px] font-black animate-pulse shadow-sm">
                        {{ $flaggedCount }}
                    </span>
                @endif
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- ADMIN PLATFORM CONTROLS --}}
        {{-- ========================================== --}}
        @if($isAdmin)
            <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Platform</p>
            </div>

            @php $isApplicantsActive = request()->routeIs('admin.membership-requests') || request()->routeIs('admin.membership-settings'); @endphp
            <a href="{{ route('admin.membership-requests') }}" class="{{ $linkClass }} {{ $isApplicantsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isApplicantsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Membership
                @php $pendingCount = \App\Models\MembershipApplication::where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-[10px] font-black animate-pulse shadow-sm">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            @php $isPillarsActive = request()->routeIs('admin.pillars.*'); @endphp
            <a href="{{ route('admin.pillars.index') }}" class="{{ $linkClass }} {{ $isPillarsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isPillarsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                Pillars
            </a>
            
            @php $isNewsActive = request()->routeIs('admin.news.*') || request()->routeIs('admin.news.*'); @endphp
            <a href="{{ route('admin.news.index') }}" class="{{ $linkClass }} {{ $isNewsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isNewsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Content & News
            </a>

            @php $isLinkagesActive = request()->routeIs('admin.linkages.*'); @endphp
            <a href="{{ route('admin.linkages.index') }}" class="{{ $linkClass }} {{ $isLinkagesActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isLinkagesActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                Linkages
                @php $pendingProposals = \App\Models\LinkageProposal::where('status', 'pending')->count(); @endphp
                @if($pendingProposals > 0)
                    <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-[10px] font-black animate-pulse shadow-sm">
                        {{ $pendingProposals }}
                    </span>
                @endif
            </a>

            {{-- SYSTEM --}}
            <div class="pt-5 pb-2 px-3 mt-2 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">System</p>
            </div>

            @php $isUsersActive = request()->routeIs('admin.user.*'); @endphp
            <a href="{{ route('admin.user.index') ?? '#' }}" class="{{ $linkClass }} {{ $isUsersActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isUsersActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                User Management
            </a>

            @php $isSystemSettingsActive = request()->routeIs('admin.settings'); @endphp
            <a href="{{ route('admin.settings') ?? '#' }}" class="{{ $linkClass }} {{ $isSystemSettingsActive ? $activeClass : $inactiveClass }}">
                <svg class="{{ $isSystemSettingsActive ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- SHARED FOOTER LINKS --}}
        {{-- ========================================== --}}
        <a href="{{ route('open.home') }}" class="{{ $linkClass }} {{ $inactiveClass }} mt-6 border-t border-gray-100 pt-5 rounded-none">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Public Homepage
        </a>

    </nav>

    {{-- LOGOUT SECTION --}}
    <div class="border-t border-gray-200 p-4 bg-gray-50 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 hover:text-white text-gray-600 rounded-xl transition-all duration-200 text-xs font-bold uppercase tracking-widest shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>