<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-iba-light">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #131011; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }
        
        /* Premium Retro Neo-Brutalist Button */
        .btn-retro {
            border: 4px solid #131011;
            box-shadow: 6px 6px 0 0 #131011;
            transition: all 0.15s ease-in-out;
        }
        .btn-retro:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0 0 #131011;
        }
        .btn-retro:active {
            transform: translate(6px, 6px);
            box-shadow: 0px 0px 0 0 #131011;
        }

        /* 8-Bit Pixel Dot Pattern */
        .bg-pixel-pattern {
            background-image: radial-gradient(#131011 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }

        /* Non-Repeating Stretched Ethnic Trims */
        .trim-element-1 {
            background-image: url('{{ asset('images/ELEMENT 1.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            height: 32px; width: 100%;
        }
        
        .trim-element-2 {
            background-image: url('{{ asset('images/ELEMENT 2.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            height: 32px; width: 100%;
        }
        @media (min-width: 768px) { .trim-element-1, .trim-element-2 { height: 44px; } }
    </style>
</head>
<body class="antialiased overflow-x-hidden pt-20 md:pt-24">

    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-iba-light border-b-4 border-iba-black shadow-[0_4px_0_0_rgba(19,16,17,1)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            
            <div class="font-pixel text-[10px] sm:text-xs tracking-tight text-iba-red z-50">
                <span class="text-iba-black">BU</span> MADYA
            </div>
            
            <div class="flex items-center gap-4 sm:gap-6 z-50">
                <div class="hidden md:flex gap-6 font-pixel text-[9px] text-iba-black">
                    <a href="#home" class="hover:text-iba-orange transition-colors">HOME</a>
                    <a href="#about" class="hover:text-iba-teal transition-colors">ABOUT</a>
                    <a href="#pathways" class="hover:text-iba-green transition-colors">PATHWAYS</a>
                    <a href="#timeline" class="hover:text-iba-red transition-colors">TIMELINE</a>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-iba-black border-2 border-iba-black bg-iba-orange shadow-[2px_2px_0_0_#131011]">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak class="md:hidden absolute top-full left-0 w-full bg-iba-light border-b-4 border-iba-black shadow-lg font-pixel text-[10px] text-center">
            <div class="flex flex-col py-4">
                <a href="#home" @click="mobileMenuOpen = false" class="py-4 text-iba-black hover:bg-iba-orange hover:text-white border-b-2 border-dashed border-iba-black/20">HOME</a>
                <a href="#about" @click="mobileMenuOpen = false" class="py-4 text-iba-black hover:bg-iba-teal hover:text-white border-b-2 border-dashed border-iba-black/20">ABOUT</a>
                <a href="#pathways" @click="mobileMenuOpen = false" class="py-4 text-iba-black hover:bg-iba-green hover:text-white border-b-2 border-dashed border-iba-black/20">PATHWAYS</a>
                <a href="#timeline" @click="mobileMenuOpen = false" class="py-4 text-iba-black hover:bg-iba-red hover:text-white">TIMELINE</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <footer class="bg-iba-light pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 md:mb-10">
                <span class="font-pixel text-[8px] sm:text-[9px] tracking-widest text-iba-teal block mb-3">COUNCIL OF CO-FOUNDERS</span>
                <h3 class="text-base sm:text-lg font-bold uppercase text-iba-black tracking-wide">ORGANIZED BY & PARTNERS</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 max-w-5xl mx-auto">
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
                    <div class="bg-white border-4 border-iba-black p-4 sm:p-6 flex flex-col items-center justify-center text-center shadow-[4px_4px_0_0_#CF452C] hover:shadow-[6px_6px_0_0_#FF8623] hover:-translate-y-1 transition-all">
                        <div class="h-12 sm:h-16 w-full flex items-center justify-center mb-2 sm:mb-3">
                            <div class="font-pixel text-[8px] sm:text-[10px] text-iba-black leading-tight">
                                {{ $partner['name'] }}
                            </div>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-gray-500 uppercase tracking-wider leading-tight">
                            {{ $partner['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-16 pb-8 text-iba-black font-semibold text-xs px-4">
                <p>&copy; {{ date('Y') }} BU MADYA & BiCoRSE.</p>
            </div>
        </div>
        
        <div class="trim-element-2"></div>
    </footer>

    @livewireScripts
</body>
</html>