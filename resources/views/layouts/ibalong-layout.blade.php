<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="scroll-smooth" 
      x-data="{ darkMode: Alpine.$persist(true).as('ibalong_theme') }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heroes of Innovation Challenge 2026 | Ibalong Launchpad</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Alpine Persist Plugin (Required for keeping theme state across components) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s, color 0.3s; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }
        
        /* High-fidelity 8-Bit Button */
        .btn-retro {
            position: relative;
            border-style: solid;
            border-width: 4px;
            box-shadow: 0 4px 0 0 rgba(0,0,0,0.2);
            transition: transform 0.05s, box-shadow 0.05s;
        }
        .btn-retro:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 0 transparent;
        }

        /* RPG-Style Panel Trim */
        .tribal-trim {
            background: repeating-linear-gradient(45deg, #E879F9 0px, #E879F9 10px, #F59E0B 10px, #F59E0B 20px);
            height: 12px;
            width: 100%;
        }
        .dark .tribal-trim {
            background: repeating-linear-gradient(45deg, #D97706 0px, #D97706 10px, #10B981 10px, #10B981 20px);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-[#11120D] dark:text-gray-100 antialiased overflow-x-hidden pt-20">

    {{-- DYNAMIC FLOATING NAVBAR --}}
    <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-[#11120D]/90 backdrop-blur-md border-b border-gray-200 dark:border-yellow-600/20 transition-colors">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="font-pixel text-xs md:text-sm tracking-tight text-amber-600 dark:text-yellow-500">
                <span class="text-gray-900 dark:text-white">BU</span> MADYA
            </div>
            
            <div class="flex items-center gap-6">
                <div class="hidden md:flex gap-6 font-pixel text-[9px] text-gray-600 dark:text-gray-300">
                    <a href="#home" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">HOME</a>
                    <a href="#about" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">ABOUT</a>
                    <a href="#pathways" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">PATHWAYS</a>
                    <a href="#timeline" class="hover:text-amber-600 dark:hover:text-yellow-400 transition">TIMELINE</a>
                </div>

                {{-- THEME TOGGLER BUTTON --}}
                <button @click="darkMode = !darkMode" 
                        class="p-2 border-2 border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 rounded text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                        aria-label="Toggle Theme">
                    <span x-show="!darkMode">🌙</span>
                    <span x-show="darkMode" x-cloak>☀️</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- INJECT VIEW COMPONENT --}}
    {{ $slot }}

    {{-- RE-DESIGNED PARTNER BRAND SHOWCASE --}}
    <footer class="bg-white dark:bg-[#0A0B08] border-t border-gray-200 dark:border-gray-900 transition-colors pt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10">
                <span class="font-pixel text-[9px] tracking-widest text-gray-400 dark:text-gray-500 block mb-2">COUNCIL OF CO-FOUNDERS</span>
                <h3 class="text-xl font-bold uppercase text-gray-800 dark:text-gray-200 tracking-wide">ORGANIZED BY & ORGANIZATIONAL PARTNERS</h3>
            </div>

            {{-- Grid of Clean Container Enclosures for Logos --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 max-w-5xl mx-auto">
                @php
                    $partners = [
                        ['name' => 'LGU LEGAZPI', 'label' => 'City Government Official Seal', 'img' => 'images/logo_legazpi.png'],
                        ['name' => 'IBALONG FESTIVAL 2026', 'label' => 'Executive Festival Committee', 'img' => 'images/logo_ibalong.png'],
                        ['name' => 'DEVCON LEGAZPI', 'label' => 'Technology Ecosystem Partner', 'img' => 'images/logo_devcon.png'],
                        ['name' => 'BiCoRSE', 'label' => 'Lead Consortium Host', 'img' => 'images/logo_bicorse.png'],
                        ['name' => 'DOST V', 'label' => 'Innovation Support Partner', 'img' => 'images/logo_dost.png'],
                    ];
                @endphp

                @foreach($partners as $partner)
                    <div class="bg-gray-50 dark:bg-[#11120D] border-2 border-gray-200 dark:border-gray-800/80 p-6 flex flex-col items-center justify-center text-center rounded-xl hover:border-amber-500 dark:hover:border-yellow-500 group transition-all shadow-sm">
                        {{-- Image Container Box --}}
                        <div class="h-20 w-full flex items-center justify-center mb-3">
                            @if(file_exists(public_path($partner['img'])))
                                <img src="{{ asset($partner['img']) }}" alt="{{ $partner['name'] }}" class="max-h-full max-w-full object-contain filter dark:brightness-110 group-hover:scale-105 transition-transform duration-200">
                            @else
                                {{-- Smart Minimal fallback if file hasn't been uploaded yet --}}
                                <div class="font-pixel text-[11px] text-amber-600 dark:text-yellow-500 group-hover:scale-105 transition-transform duration-200 leading-tight">
                                    {{ $partner['name'] }}
                                </div>
                            @endif
                        </div>
                        <div class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider leading-tight">
                            {{ $partner['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-16 pb-12 border-t border-gray-100 dark:border-gray-900 pt-8 text-gray-400 dark:text-gray-600 text-xs space-y-1">
                <p class="font-medium">Direct Inquiries: <a href="mailto:bicorse@bicol-u.edu.ph" class="text-amber-600 dark:text-yellow-500 hover:underline">bicorse@bicol-u.edu.ph</a></p>
                <p>&copy; {{ date('Y') }} BU MADYA & BiCoRSE. Formulated for the Bicol Startup Ecosystem.</p>
            </div>
        </div>

        {{-- Dynamic Border --}}
        <div class="tribal-trim"></div>
    </footer>

    @livewireScripts
</body>
</html>