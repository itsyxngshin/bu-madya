<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="scroll-smooth"
      x-data="{ darkMode: localStorage.getItem('ibalong_theme') === 'dark' || (!('ibalong_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('darkMode', val => localStorage.setItem('ibalong_theme', val ? 'dark' : 'light'))"
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOI Challenge 2026 | Ibalong Launchpad</title>
    <link rel="icon" href="{{ asset('images/HOI Main Blue.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }

        /* Premium Retro Neo-Brutalist Button - Light & Dark */
        .btn-retro {
            border: 4px solid #131011;
            box-shadow: 6px 6px 0 0 #131011;
            transition: all 0.15s ease-in-out;
        }
        .dark .btn-retro {
            border-color: #FFFBF7;
            box-shadow: 6px 6px 0 0 #FFFBF7;
        }

        .btn-retro:hover { transform: translate(-2px, -2px); box-shadow: 8px 8px 0 0 #131011; }
        .dark .btn-retro:hover { box-shadow: 8px 8px 0 0 #FFFBF7; }

        .btn-retro:active { transform: translate(6px, 6px); box-shadow: 0px 0px 0 0 transparent; }
        .dark .btn-retro:active { box-shadow: 0px 0px 0 0 transparent; }

        /* 8-Bit Pixel Dot Pattern - Light & Dark */
        .bg-pixel-pattern {
            background-image: radial-gradient(#131011 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }
        .dark .bg-pixel-pattern {
            background-image: radial-gradient(rgba(255,251,247,0.3) 1.5px, transparent 1.5px);
        }

        /* Non-Repeating Stretched Ethnic Trims */
        .trim-element-1 {
            background-image: url('{{ asset('images/ELEMENT 1.png') }}');
            background-repeat: no-repeat; background-position: center; background-size: 100% 100%; height: 32px; width: 100%;
        }
        .trim-element-2 {
            background-image: url('{{ asset('images/ELEMENT 2.png') }}');
            background-repeat: no-repeat; background-position: center; background-size: 100% 100%; height: 32px; width: 100%;
        }
        @media (min-width: 768px) { .trim-element-1, .trim-element-2 { height: 44px; } }
    </style>
    @stack('adsense')
</head>
<body class="antialiased overflow-x-hidden pt-20 md:pt-24 bg-iba-light text-iba-black dark:bg-iba-black dark:text-iba-light transition-colors duration-300">

    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-iba-light dark:bg-iba-black border-b-4 border-iba-black dark:border-iba-light shadow-[0_4px_0_0_#131011] dark:shadow-[0_4px_0_0_#FFFBF7] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">

            {{-- BRANDING LOGO --}}
            <a href="{{ route('ibalong.home') }}" class="z-50 flex items-center">
                <img src="{{ asset('images/HOI Logo Blue.png') }}" alt="Heroes of Innovation Challenge 2026" class="h-10 sm:h-12 w-auto drop-shadow-sm hover:-translate-y-0.5 transition-transform">
            </a>

            <div class="flex items-center gap-4 sm:gap-6 z-50">

                {{-- DESKTOP NAVIGATION --}}
                <div class="hidden md:flex gap-6 font-pixel text-[9px] text-iba-black dark:text-iba-light items-center">
                    <a href="{{ route('ibalong.home') }}" class="hover:text-iba-orange transition-colors">HOME</a>
                    <a href="{{ route('ibalong.about') }}" class="hover:text-iba-teal transition-colors">ABOUT</a>
                    <a href="{{ route('ibalong.roster') }}" class="hover:text-iba-green transition-colors">ROSTER</a>
                    <a href="{{ route('ibalong.volunteer') }}" class="hover:text-iba-red transition-colors">VOLUNTEER</a>

                    @auth('ibalong')
                        <div class="flex items-center gap-3 pl-4 border-l-2 border-dashed border-iba-black/20 dark:border-iba-light/20">
                            <a href="{{ route('ibalong.dashboard') }}" class="text-iba-teal hover:text-teal-600 transition-colors">DASHBOARD</a>

                            {{-- Desktop Avatar --}}
                            <a href="{{ route('ibalong.dashboard') }}" class="w-8 h-8 border-2 border-iba-black dark:border-iba-light overflow-hidden bg-white shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all">
                                @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && auth('ibalong')->user()->profile_photo_path)
                                    <img src="{{ auth('ibalong')->user()->profile_photo_url }}" alt="{{ auth('ibalong')->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('ibalong')->user()->name) }}&background=0095AC&color=fff&bold=true&size=100" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                            </a>
                        </div>
                    @else
                        <a href="{{ route('ibalong.login') }}" class="hover:text-iba-red transition-colors flex items-center gap-1 pl-4 border-l-2 border-dashed border-iba-black/20 dark:border-iba-light/20">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg> LOGIN
                        </a>
                    @endauth
                </div>

                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" class="p-2 border-2 border-iba-black dark:border-iba-light bg-white dark:bg-iba-black text-iba-black dark:text-iba-light shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7] transition-transform active:translate-y-1 active:shadow-none">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-iba-black dark:text-iba-light border-2 border-iba-black dark:border-iba-light bg-iba-orange shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7]">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        {{-- MOBILE NAVIGATION --}}
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden absolute top-full left-0 w-full bg-iba-light dark:bg-iba-black border-b-4 border-iba-black dark:border-iba-light shadow-[0_10px_0_0_rgba(0,0,0,0.2)] font-pixel text-[10px] text-center">
            <div class="flex flex-col py-2">
                <a href="{{ route('ibalong.home') }}" @click="mobileMenuOpen = false" class="py-4 text-iba-black dark:text-iba-light hover:bg-iba-orange hover:text-white border-b-2 border-dashed border-iba-black/20 dark:border-iba-light/20">HOME</a>
                <a href="{{ route('ibalong.about') }}" @click="mobileMenuOpen = false" class="py-4 text-iba-black dark:text-iba-light hover:bg-iba-teal hover:text-white border-b-2 border-dashed border-iba-black/20 dark:border-iba-light/20">ABOUT</a>
                <a href="{{ route('ibalong.roster') }}" @click="mobileMenuOpen = false" class="py-4 text-iba-black dark:text-iba-light hover:bg-iba-green hover:text-white border-b-2 border-dashed border-iba-black/20 dark:border-iba-light/20">THE ROSTER</a>
                <a href="{{ route('ibalong.volunteer') }}" @click="mobileMenuOpen = false" class="py-4 text-iba-black dark:text-iba-light hover:bg-iba-red hover:text-white border-b-2 border-dashed border-iba-black/20 dark:border-iba-light/20">VOLUNTEER</a>

                @auth('ibalong')
                    <div class="py-6 flex flex-col items-center justify-center gap-4 bg-white/50 dark:bg-black/20">
                        {{-- Mobile Avatar & Name --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 border-2 border-iba-black dark:border-iba-light overflow-hidden shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7] bg-white">
                                @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && auth('ibalong')->user()->profile_photo_path)
                                    <img src="{{ auth('ibalong')->user()->profile_photo_url }}" alt="{{ auth('ibalong')->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('ibalong')->user()->name) }}&background=0095AC&color=fff&bold=true&size=100" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="text-iba-black dark:text-iba-light uppercase tracking-widest">{{ auth('ibalong')->user()->name }}</span>
                        </div>
                        <a href="{{ route('ibalong.dashboard') }}" @click="mobileMenuOpen = false" class="text-iba-teal hover:underline mt-2">GO TO DASHBOARD ➔</a>
                    </div>
                @else
                    <a href="{{ route('ibalong.login') }}" @click="mobileMenuOpen = false" class="py-5 text-iba-black dark:text-iba-light hover:bg-gray-200 dark:hover:bg-gray-800">LOGIN</a>
                @endauth
            </div>
        </div>
    </nav>

    {{ $slot }}

    <livewire:ibalong.footer />

    @livewireScripts
</body>
</html>
