<div class="flex h-full flex-col overflow-y-auto">
    {{-- Branding Header --}}
    <div class="flex flex-col justify-center shrink-0 py-5 px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-900 dark:bg-gray-950">
        <a href="{{ route('ibalong.dashboard') }}" class="block">
            <img src="{{ asset('images/HOI Logo Blue.png') }}" alt="Heroes of Innovation Challenge" class="h-10 sm:h-12 w-auto object-contain object-left mb-2 hover:opacity-90 transition-opacity">
        </a>
        <div class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Command Center</div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-4 space-y-1">
        @php $role = auth('ibalong')->user()->role_id ?? 0; @endphp

        <a href="{{ route('ibalong.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-md transition-colors {{ request()->routeIs('ibalong.dashboard') ? 'bg-gray-100 text-iba-teal dark:bg-gray-700/50 dark:text-iba-teal' : 'text-gray-700 hover:bg-gray-50 hover:text-iba-teal dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('ibalong.dashboard') ? 'text-iba-teal' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Overview
        </a>

        {{-- Admin Controls --}}
        @if(in_array($role, [1, 2]))
            <div class="pt-6 pb-2">
                <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Administration</p>
            </div>
            <a href="{{ route('ibalong.admin.registrants') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-md transition-colors {{ request()->routeIs('ibalong.admin.registrants') ? 'bg-gray-100 text-iba-teal dark:bg-gray-700/50 dark:text-iba-teal' : 'text-gray-700 hover:bg-gray-50 hover:text-iba-teal dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('ibalong.admin.registrants') ? 'text-iba-teal' : 'text-gray-400 group-hover:text-iba-teal' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Cohort Intake
            </a>
        @endif
    </nav>

    {{-- User Footer --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-iba-teal text-white flex items-center justify-center font-bold text-sm">
                {{ substr(auth('ibalong')->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ auth('ibalong')->user()->name ?? 'User' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth('ibalong')->user()->designation ?? 'Role' }}</p>
            </div>
        </div>
        <form action="{{ route('ibalong.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-iba-red dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-red-400 transition-colors">
                Sign Out
            </button>
        </form>
    </div>
</div>