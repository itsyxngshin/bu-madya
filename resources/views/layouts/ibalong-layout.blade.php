<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="scroll-smooth"
      x-data="{ darkMode: localStorage.getItem('ibalong_theme') === 'dark' || (!('ibalong_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('darkMode', val => localStorage.setItem('ibalong_theme', val ? 'dark' : 'light'))"
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heroes of Innovation Challenge 2026 | Ibalong Launchpad</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.2s, color 0.2s; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }
        
        .btn-retro {
            border-style: solid;
            border-width: 4px;
            box-shadow: 0 4px 0 0 rgba(0,0,0,0.3);
        }
        .btn-retro:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 0 transparent;
        }

        .tribal-trim {
            background: repeating-linear-gradient(45deg, #D97706 0px, #D97706 10px, #B45309 10px, #B45309 20px);
            height: 12px;
            width: 100%;
        }
        .dark .tribal-trim {
            background: repeating-linear-gradient(45deg, #D97706 0px, #D97706 10px, #10B981 10px, #10B981 20px);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-[#11120D] dark:text-gray-100 antialiased overflow-x-hidden pt-20 md:pt-24">

    {{-- DYNAMIC FLOATING NAVBAR --}}
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-white/95 dark:bg-[#11120D]/95 border-b border-gray-200 dark:border-yellow-600/20 shadow-sm transition-colors backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            
            <div class="font-pixel text-[10px] sm:text-xs tracking-tight text-amber-700 dark:text-yellow-500 z-50">
                <span class="text-gray-900 dark:text-white">BU</span> MADYA
            </div>
            
            <div class="flex items-center gap-4 sm:gap-6 z-50">
                <div class="hidden md:flex gap-6 font-pixel text-[9px] text-gray-600 dark:text-gray-300">
                    <a href="#home" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">HOME</a>
                    <a href="#about" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">ABOUT</a>
                    <a href="#pathways" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">PATHWAYS</a>
                    <a href="#timeline" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">TIMELINE</a>
                </div>

                {{-- ALPINE THEME TOGGLE --}}
                <button @click="darkMode = !darkMode" 
                        class="p-2 border-2 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs sm:text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                        aria-label="Toggle Theme">
                    <span x-show="!darkMode">🌙</span>
                    <span x-show="darkMode" x-cloak>☀️</span>
                </button>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 dark:text-gray-300 focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak class="md:hidden absolute top-full left-0 w-full bg-white dark:bg-[#151710] border-b border-gray-200 dark:border-gray-800 shadow-lg font-pixel text-[10px] text-center">
            <div class="flex flex-col py-4">
                <a href="#home" @click="mobileMenuOpen = false" class="py-3 text-gray-700 dark:text-gray-300">HOME</a>
                <a href="#about" @click="mobileMenuOpen = false" class="py-3 text-gray-700 dark:text-gray-300">ABOUT</a>
                <a href="#pathways" @click="mobileMenuOpen = false" class="py-3 text-gray-700 dark:text-gray-300">PATHWAYS</a>
                <a href="#timeline" @click="mobileMenuOpen = false" class="py-3 text-gray-700 dark:text-gray-300">TIMELINE</a>
                <a href="#register" @click="mobileMenuOpen = false" class="py-4 text-amber-700 dark:text-yellow-500 font-bold bg-amber-50 dark:bg-yellow-900/10">REGISTER NOW</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <footer class="bg-white dark:bg-[#0A0B08] border-t border-gray-200 dark:border-gray-900 transition-colors pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 md:mb-10">
                <span class="font-pixel text-[8px] sm:text-[9px] tracking-widest text-gray-400 dark:text-gray-500 block mb-2">COUNCIL OF CO-FOUNDERS</span>
                <h3 class="text-base sm:text-lg font-bold uppercase text-gray-800 dark:text-gray-200 tracking-wide">ORGANIZED BY & PARTNERS</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 max-w-5xl mx-auto">
                @php
                    $partners = [
                        ['name' => 'LGU LEGAZPI', 'label' => 'City Government Official Seal', 'img' => 'images/logo_legazpi.png'],
                        ['name' => 'IBALONG FESTIVAL', 'label' => 'Executive Festival Committee', 'img' => 'images/logo_ibalong.png'],
                        ['name' => 'DEVCON LEGAZPI', 'label' => 'Technology Ecosystem Partner', 'img' => 'images/logo_devcon.png'],
                        ['name' => 'BiCoRSE', 'label' => 'Lead Consortium Host', 'img' => 'images/logo_bicorse.png'],
                        ['name' => 'DOST V', 'label' => 'Innovation Support Partner', 'img' => 'images/logo_dost.png'],
                    ];
                @endphp

                @foreach($partners as $partner)
                    <div class="bg-gray-50 dark:bg-[#11120D] border-2 border-gray-200 dark:border-gray-800/80 p-4 sm:p-6 flex flex-col items-center justify-center text-center rounded-xl transition-all shadow-sm">
                        <div class="h-12 sm:h-16 w-full flex items-center justify-center mb-2 sm:mb-3">
                            <div class="font-pixel text-[8px] sm:text-[10px] text-amber-700 dark:text-yellow-500 leading-tight">
                                {{ $partner['name'] }}
                            </div>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider leading-tight">
                            {{ $partner['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12 pb-8 border-t border-gray-100 dark:border-gray-900 pt-6 text-gray-400 dark:text-gray-500 text-xs px-4">
                <p>&copy; {{ date('Y') }} BU MADYA & BiCoRSE.</p>
            </div>
        </div>
        <div class="tribal-trim"></div>
    </footer>

    @livewireScripts
</body>
</html>