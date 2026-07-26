<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="scroll-smooth h-full"
      x-data="{
          darkMode: localStorage.getItem('ibalong_theme') === 'dark' || (!('ibalong_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          mobileSidebarOpen: false,
          desktopSidebarOpen: localStorage.getItem('ibalong_sidebar') !== 'closed'
      }"
      x-init="
          $watch('darkMode', val => localStorage.setItem('ibalong_theme', val ? 'dark' : 'light'));
          $watch('desktopSidebarOpen', val => localStorage.setItem('ibalong_sidebar', val ? 'open' : 'closed'));
      "
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Center | Ibalong Launchpad</title>
    <link rel="icon" href="{{ asset('images/HOI Main Blue.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased h-full bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200 overflow-hidden flex">

    {{-- MOBILE SIDEBAR OVERLAY --}}
    <div x-show="mobileSidebarOpen" x-cloak class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80" @click="mobileSidebarOpen = false"></div>
        <div x-show="mobileSidebarOpen" x-transition.duration.300ms class="relative flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-gray-800 pt-5 pb-4 shadow-xl z-50">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="mobileSidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            @include('components.admin-sidebar-content')
        </div>
    </div>

    {{-- DESKTOP COLLAPSIBLE SIDEBAR --}}
    <aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm z-30 transition-all duration-300 ease-in-out overflow-hidden"
           :class="desktopSidebarOpen ? 'w-64' : 'w-0 border-transparent'">
        {{-- We fix the inner width to w-64 so the text doesn't squish during the slide animation --}}
        <div class="w-64 h-full flex flex-col">
            @include('components.admin-sidebar-content')
        </div>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    {{-- Notice w-full is removed here to fix the empty right-side space bug --}}
    <div class="flex flex-col flex-1 h-screen transition-all duration-300 ease-in-out overflow-x-hidden"
         :class="desktopSidebarOpen ? 'md:pl-64' : 'md:pl-0'">

        {{-- TOP HEADER --}}
        <header class="sticky top-0 z-20 flex h-16 flex-shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-colors duration-200">

            {{-- Mobile Hamburger --}}
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 md:hidden" @click="mobileSidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>

            {{-- Desktop Toggle Button --}}
            <div class="hidden md:flex items-center">
                <button type="button" class="-m-2.5 p-2.5 text-gray-500 hover:text-iba-teal dark:text-gray-400 dark:hover:text-iba-teal transition-colors" @click="desktopSidebarOpen = !desktopSidebarOpen">
                    <span class="sr-only">Toggle desktop sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-1 justify-end items-center gap-x-4 lg:gap-x-6">

                {{-- NEW: Notification Bell Component --}}
                <livewire:ibalong.components.notification-bell />

                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
