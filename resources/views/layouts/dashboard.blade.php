<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="scroll-smooth bg-iba-light dark:bg-iba-black"
      x-data="{ darkMode: localStorage.getItem('ibalong_theme') === 'dark' || (!('ibalong_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches), sidebarOpen: false }"
      x-init="$watch('darkMode', val => localStorage.setItem('ibalong_theme', val ? 'dark' : 'light'))"
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center | Ibalong Launchpad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }
        .bg-pixel-pattern { background-image: radial-gradient(#131011 1.5px, transparent 1.5px); background-size: 32px 32px; }
        .dark .bg-pixel-pattern { background-image: radial-gradient(rgba(255,251,247,0.2) 1.5px, transparent 1.5px); }
    </style>
</head>
<body class="antialiased text-iba-black dark:text-iba-light transition-colors duration-300 flex h-screen overflow-hidden">

    {{-- ROLE-BASED SIDEBAR --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-[#1A1617] border-r-8 border-iba-black dark:border-iba-light shadow-[10px_0_0_0_#0095AC] transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col h-full">
        
        {{-- Branding --}}
        <div class="p-6 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center bg-iba-light dark:bg-iba-black">
            <div>
                <div class="font-pixel text-[10px] text-iba-red tracking-tight">BU MADYA</div>
                <div class="font-bold text-sm uppercase tracking-widest mt-1">Command Center</div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-iba-black dark:text-iba-light">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Navigation Links --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            @php $role = auth('ibalong')->user()->role_id; @endphp

            <a href="{{ route('ibalong.dashboard') }}" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-orange hover:text-iba-black transition-colors {{ request()->routeIs('ibalong.dashboard') ? 'bg-iba-orange text-iba-black shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'bg-white dark:bg-iba-black' }}">
                ⌂ Overview
            </a>

            {{-- Admin & Super Admin Tools (Role 1 & 2) --}}
            @if(in_array($role, [1, 2]))
                <div class="pt-4 pb-2">
                    <span class="font-pixel text-[9px] text-gray-500 uppercase tracking-widest">Admin Controls</span>
                </div>
                <a href="{{ route('ibalong.admin.registrants') }}" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-teal hover:text-white transition-colors {{ request()->routeIs('ibalong.admin.registrants') ? 'bg-iba-teal text-white shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]' : 'bg-white dark:bg-iba-black' }}">
                    ⚔️ Cohort Intake
                </a>
                <a href="#" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-teal hover:text-white transition-colors bg-white dark:bg-iba-black">
                    👥 User Directory
                </a>
            @endif

            {{-- Facilitator / Judge Tools (Role 4 & 5) --}}
            @if(in_array($role, [4, 5]))
                <div class="pt-4 pb-2">
                    <span class="font-pixel text-[9px] text-gray-500 uppercase tracking-widest">Evaluation</span>
                </div>
                <a href="#" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-red hover:text-white transition-colors bg-white dark:bg-iba-black">
                    📊 Scoring Matrix
                </a>
            @endif

            {{-- Team Tools (Role 3) --}}
            @if($role == 3)
                <div class="pt-4 pb-2">
                    <span class="font-pixel text-[9px] text-gray-500 uppercase tracking-widest">Cohort Operations</span>
                </div>
                <a href="#" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-green hover:text-white transition-colors bg-white dark:bg-iba-black">
                    🛡️ My Profile
                </a>
                <a href="#" class="block p-4 border-4 border-iba-black dark:border-iba-light font-bold uppercase text-sm hover:bg-iba-green hover:text-white transition-colors bg-white dark:bg-iba-black">
                    🚀 Concept Submission
                </a>
            @endif
        </div>

        {{-- User Footer --}}
        <div class="p-4 border-t-4 border-iba-black dark:border-iba-light bg-iba-light dark:bg-iba-black">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 border-2 border-iba-black dark:border-iba-light bg-iba-orange flex items-center justify-center font-pixel text-xs">
                    {{ substr(auth('ibalong')->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-sm truncate uppercase">{{ auth('ibalong')->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth('ibalong')->user()->role->name }}</p>
                </div>
            </div>
            <form action="{{ route('ibalong.logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full p-2 border-2 border-iba-black dark:border-iba-light text-xs font-bold uppercase hover:bg-iba-red hover:text-white transition-colors">Terminate Session</button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-pixel-pattern">
        {{-- Topbar (Mobile Trigger & Dark Mode) --}}
        <header class="bg-white dark:bg-[#1A1617] border-b-4 border-iba-black dark:border-iba-light p-4 flex justify-between items-center shrink-0">
            <button @click="sidebarOpen = true" class="md:hidden p-2 border-2 border-iba-black bg-iba-orange">
                <svg class="w-5 h-5 text-iba-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex-1 md:hidden text-center font-pixel text-[10px]">COMMAND CENTER</div>
            <div class="flex justify-end w-full md:w-auto">
                <button @click="darkMode = !darkMode" class="p-2 border-2 border-iba-black dark:border-iba-light bg-iba-light dark:bg-iba-black text-iba-black dark:text-iba-light shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7] transition-transform active:translate-y-1 active:shadow-none">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
            </div>
        </header>

        {{-- Dynamic View Injection --}}
        <div class="flex-1 overflow-y-auto p-4 sm:p-8">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>