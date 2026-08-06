<div class="flex h-full flex-col overflow-y-auto bg-white dark:bg-[#1A1617] border-r-4 border-iba-black dark:border-iba-light transition-colors duration-300">

    {{-- Branding Header --}}
    <div class="flex flex-col justify-center shrink-0 py-6 px-6 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-900">
        <a href="{{ route('ibalong.dashboard') }}" class="block transform hover:-translate-y-1 transition-transform">
            <img src="{{ asset('images/HOI Logo Blue.png') }}" alt="Heroes of Innovation Challenge" class="h-10 sm:h-12 w-auto object-contain object-left mb-2 drop-shadow-sm">
        </a>
        <div class="text-[10px] font-black text-iba-black dark:text-iba-light tracking-widest uppercase mt-1">Community Center</div>
    </div>

    {{-- Navigation  --}}
    <nav class="flex-1 px-4 py-6 space-y-3">
        @php $role = auth('ibalong')->user()->role_id ?? 0; @endphp

        {{-- Overview --}}
        <a href="{{ route('ibalong.dashboard') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.dashboard') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Overview
        </a>

        {{-- TEAM ONLY: My Quest Logs & Appointments (Role 3) --}}
        @if($role == 3)
            <a href="{{ route('ibalong.team.quests.index') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.team.quests.index') ? 'bg-iba-orange text-iba-black border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.team.quests.index') ? 'text-iba-black' : 'text-gray-400 group-hover:text-iba-orange' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                My Quest Logs
            </a>

            <a href="{{ route('ibalong.team.appointments') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('team.appointments') ? 'bg-iba-black text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#0095AC] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.team.appointments') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Appointments & Hubs
            </a>

            <a href="{{ route('ibalong.evaluations.index') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('evaluations.*') ? 'bg-iba-teal text-white border-iba-black shadow-[4px_4px_0_0_#131011]' : 'border-transparent text-gray-600 hover:border-iba-black hover:bg-gray-100 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('evaluations.*') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Surveys & Feedback
            </a>
        @endif

        {{-- Community Logs --}}
        <a href="{{ route('ibalong.community-logs') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.community-logs*') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.community-logs*') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
            Community Logs
        </a>

        {{-- Resource Room --}}
        <a href="{{ route('ibalong.resources') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.resources') ? 'bg-iba-orange text-iba-black border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
            <svg class="mr-3 shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.resources') ? 'text-iba-black' : 'text-gray-400 group-hover:text-iba-orange' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
            Resource Room
        </a>



        {{-- Admin, Facilitator & Judge Controls (Roles 1, 2, 4, 5) --}}
        @if(in_array($role, [1, 2, 4, 5]))
            <div class="pt-4 pb-2">
                <p class="px-2 text-[10px] font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-2">Administration</p>
            </div>

            {{-- Master Quest Roster (Accessible to Admins, Facilitators & Judges) --}}
            <a href="{{ route('ibalong.admin.quests.index') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.quests.index') ? 'bg-iba-orange text-iba-black border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.quests.index') ? 'text-iba-black' : 'text-gray-400 group-hover:text-iba-orange' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Quest Roster
            </a>

            {{-- Mentor Hub (Accessible to Admins, Facilitators & Mentors/Judges) --}}
            <a href="{{ route('ibalong.mentor.hub') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('mentor.hub') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.mentor.hub') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Mentor Hub
            </a>
        @endif

        @if(in_array($role, [1, 2, 4]))
            <a href="{{ route('ibalong.admin.team-accounts') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.team-accounts') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.team-accounts') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Team Accounts
            </a>

            {{-- Team Intake (Accessible to Admins & Facilitators) --}}
            <a href="{{ route('ibalong.admin.registrants') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.registrants') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.registrants') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Team Intake
            </a>

            {{-- Event Control (Accessible to Admins & Facilitators) --}}
            <a href="{{ route('ibalong.admin.events') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.events') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.events') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Event Control
            </a>

            {{-- Scheduling Matrix (Accessible to Admins & Facilitators) --}}
            <a href="{{ route('ibalong.admin.scheduler') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('admin.scheduler') ? 'bg-iba-orange text-iba-black border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('admin.scheduler') ? 'text-iba-black' : 'text-gray-400 group-hover:text-iba-orange' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Scheduling Matrix
            </a>

            <a href="{{ route('ibalong.admin.dial-up') }}"
            class="flex items-center gap-3 px-4 py-3 border-2 {{ request()->routeIs('ibalong.admin.dial-up') ? 'bg-iba-orange border-iba-black text-iba-black shadow-[4px_4px_0_0_#131011] translate-x-1' : 'border-transparent text-gray-600 hover:border-iba-black hover:bg-gray-50 hover:text-iba-black transition-all' }}">

                {{-- Retro Terminal Icon --}}
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>

                <span class="text-xs font-black uppercase tracking-widest">Dial-Up Terminal</span>
            </a>

            {{-- Strictly Admin Only Controls (Role 1 & 2) --}}
            @if(in_array($role, [1, 2]))
                <div class="border-t-2 border-dashed border-gray-300 dark:border-gray-700 my-2"></div>
                <a href="{{ route('ibalong.admin.users') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.users') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.users') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Personnel
                </a>

                <a href="{{ route('ibalong.admin.evaluations.index') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('admin.evaluations.*') ? 'bg-iba-orange text-iba-black border-iba-black shadow-[4px_4px_0_0_#131011]' : 'border-transparent text-gray-600 hover:border-iba-black hover:bg-gray-100 hover:-translate-y-0.5' }}">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('admin.evaluations.*') ? 'text-iba-black' : 'text-gray-400 group-hover:text-iba-orange' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Evaluation Matrix
                </a>

                <a href="{{ route('ibalong.admin.partners') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.partners') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.partners') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Partners
                </a>

                <a href="{{ route('ibalong.admin.committees') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.committees') ? 'bg-iba-teal text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.committees') ? 'text-white' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Committees
                </a>

                <a href="{{ route('ibalong.admin.notifications') }}" class="group flex items-center px-4 py-3 text-xs font-bold uppercase tracking-wider border-2 transition-all {{ request()->routeIs('ibalong.admin.notifications') ? 'bg-iba-red text-white border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-iba-black dark:hover:border-iba-light hover:bg-gray-100 dark:hover:bg-gray-800 hover:-translate-y-0.5' }}">
                    <svg class="mr-3 shrink-0 h-5 w-5 transition-colors {{ request()->routeIs('ibalong.admin.notifications') ? 'text-white' : 'text-gray-400 group-hover:text-iba-red' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                    Alerts System
                </a>
            @endif
        @endif
    </nav>

    {{-- User Footer --}}
    <div class="p-6 border-t-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-[#1A1617]">
        <a href="{{ route('ibalong.profile') }}" class="flex items-center gap-4 mb-5 group cursor-pointer border-2 border-transparent p-2 hover:border-iba-black dark:hover:border-iba-light transition-all">
            <div class="w-12 h-12 bg-iba-teal text-white flex items-center justify-center font-black text-lg border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] shrink-0 group-hover:translate-y-0.5 group-hover:shadow-none transition-all">
                {{ substr(auth('ibalong')->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-black text-sm text-iba-black dark:text-white uppercase truncate group-hover:text-iba-teal transition-colors">{{ auth('ibalong')->user()->name ?? 'User' }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest truncate mt-0.5">{{ auth('ibalong')->user()->designation ?? 'Role' }}</p>
            </div>
        </a>
        <form action="{{ route('ibalong.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-center px-4 py-3 text-sm font-black uppercase tracking-wider text-iba-black dark:text-iba-light bg-white dark:bg-gray-800 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] hover:bg-iba-red hover:text-white dark:hover:bg-iba-red hover:-translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                Sign Out
            </button>
        </form>
    </div>
</div>
